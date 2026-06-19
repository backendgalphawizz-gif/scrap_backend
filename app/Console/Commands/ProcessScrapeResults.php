<?php

namespace App\Console\Commands;

use App\CPU\Helpers;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\CampaignTransaction;
use App\Services\CampaignVerificationService;
use App\Services\CaptionVerificationService;
use App\Services\FraudDetectionService;
use App\Services\FraudScoreService;
use Carbon\Carbon;

class ProcessScrapeResults extends Command
{   
    protected $signature = 'campaign:process-results';

    protected $description = 'Verify campaign posts, keep rewards pending, and release them after campaign completion';

    private function getMaxVerifiedDays(): int
    {
        return app(CampaignVerificationService::class)->getMaxVerifiedDays();
    }

    public function handle(): void
    {
        $this->info('Processing scrape results...');

        $verificationService = app(CampaignVerificationService::class);
        $captionService      = app(CaptionVerificationService::class);

        $transactions = CampaignTransaction::with(['campaign.brand'])
            ->whereIn('status', [
                CampaignTransaction::STATUS_PENDING,
                CampaignTransaction::STATUS_ACTIVE,
                CampaignTransaction::STATUS_APPROVED,
                CampaignTransaction::STATUS_FLAGGED,
            ])
            ->get();

        $approved = 0;
        $released = 0;
        $flagged  = 0;
        $deleted  = 0;
        $pending  = 0;

        foreach ($transactions as $transaction) {
            if (!$transaction->campaign) {
                continue;
            }

            if (!$transaction->unique_code) {
                continue;
            }

            $rewardTransaction = $verificationService->ensurePendingRewardTransaction($transaction);
            $scrapedRow = $this->getLatestScrapedPost(
                $transaction->unique_code,
                $transaction->shared_on,
                $transaction->post_url ?? null
            );

            if ($this->hasCaptionMismatch($transaction, $scrapedRow, $captionService)) {
                $endDate = Carbon::parse($transaction->end_date)->endOfDay();
                $reason  = CaptionVerificationService::MISMATCH_REASON;

                if ($transaction->status === CampaignTransaction::STATUS_FLAGGED && Carbon::now()->gt($endDate)) {
                    $this->markDeleted($transaction, $rewardTransaction, $reason);
                    $deleted++;
                } elseif ($transaction->status !== CampaignTransaction::STATUS_FLAGGED) {
                    $this->markFlagged($transaction, $reason);
                    $flagged++;
                }
                continue;
            }

            ['days' => $verifiedDays, 'post_url' => $scrapedPostUrl] = $this->calculateVerifiedDays($transaction, $scrapedRow);
            $transaction->day_status = $verifiedDays;

            if ($verifiedDays >= $this->getMaxVerifiedDays()) {
                $wasAlreadyApproved = $transaction->status === CampaignTransaction::STATUS_APPROVED;
                if (!$wasAlreadyApproved) {
                    $approved++;
                    $transaction->verified_at = now();
                }
                $transaction->status = CampaignTransaction::STATUS_APPROVED;
                $transaction->violation_reason = null;
                if ($scrapedPostUrl) {
                    $transaction->post_url = $scrapedPostUrl;
                }
                $transaction->save();

                if (!$wasAlreadyApproved) {
                    $this->sendApprovedNotification($transaction);
                }

                if ($verificationService->canReleaseReward($transaction)) {
                    $verificationService->releaseReward($transaction, $rewardTransaction);
                    $released++;
                } else {
                    $pending++;
                }
                continue;
            }

            if ($verifiedDays > 0) {
                $transaction->status = CampaignTransaction::STATUS_ACTIVE;
                $transaction->violation_reason = null;
                if ($scrapedPostUrl) {
                    $transaction->post_url = $scrapedPostUrl;
                }
                $transaction->save();
                $pending++;
                continue;
            }

            $endDate = Carbon::parse($transaction->end_date)->endOfDay();
            if ($transaction->status === CampaignTransaction::STATUS_FLAGGED && Carbon::now()->gt($endDate)) {
                $this->markDeleted($transaction, $rewardTransaction);
                $deleted++;
            } elseif (Carbon::now()->gt($endDate)) {
                $this->markFlagged($transaction);
                $flagged++;
            } else {
                $transaction->status = CampaignTransaction::STATUS_PENDING;
                $transaction->save();
                $pending++;
            }
        }

        $closedCampaigns  = $this->closeEligibleCampaigns();
        $settledCampaigns = $this->settleEligibleCampaigns();

        $this->checkPostDeletions();

        $this->info("Closed campaigns (enrollment ended): {$closedCampaigns}");
        $this->info("Settled campaigns: {$settledCampaigns}");
        $this->info("Done. Approved: {$approved} | Released: {$released} | Flagged: {$flagged} | Deleted: {$deleted} | Pending: {$pending}");
    }

    /**
     * Re-verify completed transactions from the last 45 days to catch post-delete fraud.
     */
    private function checkPostDeletions(): void
    {
        $fraudService = app(FraudDetectionService::class);
        $scoreService = app(FraudScoreService::class);

        $completedTransactions = CampaignTransaction::where('status', CampaignTransaction::STATUS_COMPLETED)
            ->whereNotNull('unique_code')
            ->where('updated_at', '>=', now()->subDays(45))
            ->get(['id', 'user_id', 'campaign_id', 'unique_code', 'earning']);

        $userIdsToRecalculate = [];

        foreach ($completedTransactions as $tx) {
            $signalCreated = $fraudService->checkPostDeletedAfterCredit($tx);
            if ($signalCreated) {
                $userIdsToRecalculate[$tx->user_id] = true;
                $this->info("Post-delete signal: user_id={$tx->user_id} unique_code={$tx->unique_code}");
            }
        }

        foreach (array_keys($userIdsToRecalculate) as $userId) {
            $user = User::find($userId);
            if ($user) {
                $scoreService->recalculate($user);
            }
        }
    }

    private function closeEligibleCampaigns(): int
    {
        return app(\App\Services\CampaignSettlementService::class)->closeEligibleCampaigns();
    }

    private function settleEligibleCampaigns(): int
    {
        return app(\App\Services\CampaignSettlementService::class)->settleEligibleCampaigns();
    }

    private function scrapedTableForPlatform(string $platform): string
    {
        return 'scrapped_posts';
    }

    private function getLatestScrapedPost(string $uniqueCode, string $platform, ?string $postUrl = null): ?object
    {
        $table = $this->scrapedTableForPlatform($platform);

        $row = DB::table($table)
            ->when($uniqueCode, function ($query) use ($uniqueCode) {
                $query->where('unique_code', $uniqueCode);
            })
            ->when($postUrl, function ($query) use ($postUrl) {
                $query->orWhere('post_url', $postUrl);
            })
            ->orderByDesc('scraped_at')
            ->select('scraped_at', 'post_url', 'caption')
            ->first();

        return $row;
    }

    private function hasCaptionMismatch(
        CampaignTransaction $transaction,
        ?object $scrapedRow,
        CaptionVerificationService $captionService
    ): bool {
        if (!$scrapedRow || empty($scrapedRow->caption)) {
            return false;
        }

        $expectedCaption = $captionService->buildExpectedCaption($transaction->campaign, $transaction);

        return $captionService->hasMismatch($expectedCaption, (string) $scrapedRow->caption);
    }

    private function calculateVerifiedDays(CampaignTransaction $transaction, ?object $row = null): array
    {
        $row ??= $this->getLatestScrapedPost(
            $transaction->unique_code,
            $transaction->shared_on,
            $transaction->post_url ?? null
        );

        if (!$row || !$row->scraped_at) {
            return ['days' => 0, 'post_url' => null];
        }

        $start     = Carbon::parse($transaction->start_date)->startOfDay();
        $scrapedAt = Carbon::parse($row->scraped_at)->startOfDay();

        if ($scrapedAt->lt($start)) {
            return ['days' => 0, 'post_url' => null];
        }

        $days = (int) $start->diffInDays($scrapedAt) + 1;

        return [
            'days'     => min($this->getMaxVerifiedDays(), $days),
            'post_url' => $row->post_url ?? null,
        ];
    }

    private function determinePostFailureReason(string $username, string $platform, string $uniqueCode): string
    {
        $post = DB::table('scrapped_posts')
            ->where('username', $username)
            ->where('platform', $platform)
            ->orderByDesc('scraped_at')
            ->first(['caption', 'unique_code']);

        if (!$post) {
            return 'Your post was not found. Please make sure you have tagged @rexarix in your post.';
        }
        if (empty($post->caption)) {
            return 'Your post is missing a caption. Please add the unique code to your caption.';
        }
        return 'The unique code in your caption is missing or incorrect. Please check and ensure the code matches exactly.';
    }

    private function getPlatformUsername(User $user, string $platform): string
    {
        $field = $platform . '_username';
        return (string) ($user->$field ?? '');
    }

    private function markFlagged(CampaignTransaction $transaction, ?string $reason = null): void
    {
        $user     = User::find($transaction->user_id);
        $username = $user ? $this->getPlatformUsername($user, $transaction->shared_on) : '';
        $reason ??= $username
            ? $this->determinePostFailureReason($username, $transaction->shared_on, $transaction->unique_code)
            : 'Post not verified. Submit a valid post URL to avoid deletion.';

        $transaction->status           = CampaignTransaction::STATUS_FLAGGED;
        $transaction->violation_reason = $reason;
        $transaction->save();

        if ($user && $user->fcm_id) {
            $title = 'Post Flagged ⚠️';
            $body  = "Your post for campaign \"{$transaction->campaign->title}\" has been flagged. {$reason}";
            Helpers::send_push_notif_to_topic($user->fcm_id, $title, $body);
        }
    }

    private function markDeleted(CampaignTransaction $transaction, $rewardTransaction, ?string $reason = null): void
    {
        $user     = User::find($transaction->user_id);
        $username = $user ? $this->getPlatformUsername($user, $transaction->shared_on) : '';
        $reason ??= $username
            ? $this->determinePostFailureReason($username, $transaction->shared_on, $transaction->unique_code)
            : 'Post could not be verified after flagging. Participation removed and slot released.';

        $transaction->status           = CampaignTransaction::STATUS_DELETED;
        $transaction->violation_reason = $reason;
        $transaction->save();

        if ($rewardTransaction->status === 'pending') {
            $rewardTransaction->status      = 'rejected';
            $rewardTransaction->description = 'Campaign reward cancelled for ' . ($transaction->campaign->title ?? 'campaign');
            $rewardTransaction->save();

            Helpers::logUserWalletTransaction('rejected', $rewardTransaction, $user, 'Campaign reward cancelled');
        }

        if ($user && $user->fcm_id) {
            $title = 'Post Deleted ❌';
            $body  = "Your post for campaign \"{$transaction->campaign->title}\" has been removed. {$reason}";
            Helpers::send_push_notif_to_topic($user->fcm_id, $title, $body);
        }
    }

    private function sendApprovedNotification(CampaignTransaction $transaction): void
    {
        $user = User::find($transaction->user_id);
        if ($user && $user->fcm_id) {
            $title = 'Post Approved! ✅';
            $body  = "Your post for campaign \"{$transaction->campaign->title}\" has been approved. Reward will be released upon campaign completion.";
            Helpers::send_push_notif_to_topic($user->fcm_id, $title, $body);
        }
    }
}
