<?php

namespace App\Console\Commands;

use App\CPU\Helpers;
use App\Models\Campaign;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\CampaignTransaction;
use App\Models\CoinWallet;
use App\Models\CoinTransaction;
use Carbon\Carbon;

class ProcessScrapeResults extends Command
{   
    protected $signature = 'campaign:process-results';

    protected $description = 'Verify campaign posts, keep rewards pending, and release them after campaign completion';
    private const GRACE_PERIOD_DAYS = 1;

    private ?int $maxVerifiedDays = null;

    private function getMaxVerifiedDays(): int
    {
        if ($this->maxVerifiedDays === null) {
            $setting = (int) env('CAMPAIGN_VERIFICATION_DAYS', 3);
            $this->maxVerifiedDays = $setting > 0 ? $setting : 3;
        }
        return $this->maxVerifiedDays;
    }

    public function handle(): void
    {
        $this->info('Processing scrape results...');

        $transactions = CampaignTransaction::with(['campaign'])
            ->whereIn('status', [
                CampaignTransaction::STATUS_PENDING,
                CampaignTransaction::STATUS_ACTIVE,
                CampaignTransaction::STATUS_APPROVED,
                CampaignTransaction::STATUS_FLAGGED,
            ])
            ->get();

        $approved = 0;
        $released = 0;
        $flagged = 0;
        $deleted = 0;
        $pending  = 0;

        foreach ($transactions as $transaction) {
            if (!$transaction->campaign) {
                continue;
            }

            if (!$transaction->unique_code) {
                continue;
            }

            $rewardTransaction = $this->ensurePendingRewardTransaction($transaction);
            ['days' => $verifiedDays, 'post_url' => $scrapedPostUrl] = $this->calculateVerifiedDays($transaction);
            $transaction->day_status = $verifiedDays;

            if ($verifiedDays >= $this->getMaxVerifiedDays()) {
                $wasAlreadyApproved = $transaction->status === CampaignTransaction::STATUS_APPROVED;
                if (!$wasAlreadyApproved) {
                    $approved++;
                }
                $transaction->status = CampaignTransaction::STATUS_APPROVED;
                $transaction->violation_reason = null;
                if ($scrapedPostUrl) {
                    $transaction->post_url = $scrapedPostUrl;
                }
                $transaction->save();

                // Send FCM notification only on first approval (not on repeated cron runs)
                if (!$wasAlreadyApproved) {
                    $this->sendApprovedNotification($transaction);
                }

                if ($this->canReleaseReward($transaction, $verifiedDays)) {
                    $this->releaseReward($transaction, $rewardTransaction);
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

        $closedCampaigns = $this->closeEligibleCampaigns();
        $settledCampaigns = $this->settleEligibleCampaigns();

        $this->info("Closed campaigns (enrollment ended): {$closedCampaigns}");
        $this->info("Settled campaigns: {$settledCampaigns}");
        $this->info("Done. Approved: {$approved} | Released: {$released} | Flagged: {$flagged} | Deleted: {$deleted} | Pending: {$pending}");
    }

    private function closeEligibleCampaigns(): int
    {
        $updated = 0;
        $settlementService = app(\App\Services\CampaignSettlementService::class);
        $eligibleStatuses = ['active', 'live', 'pending', 'accepted', 'paused'];

        Campaign::query()
            ->whereIn('status', $eligibleStatuses)
            ->withCount(['occupiedTransactions as occupied_slots'])
            ->orderBy('id')
            ->chunkById(100, function ($campaigns) use (&$updated, $settlementService) {
                foreach ($campaigns as $campaign) {
                    if (! $settlementService->shouldCloseForEnrollment($campaign)) {
                        continue;
                    }

                    $campaign->status = 'closed';
                    $campaign->save();
                    $updated++;
                }
            });

        return $updated;
    }

    private function settleEligibleCampaigns(): int
    {
        $settled = 0;
        $settlementService = app(\App\Services\CampaignSettlementService::class);

        Campaign::query()
            ->where('settlement_status', \App\Services\CampaignSettlementService::SETTLEMENT_PENDING)
            ->whereIn('status', ['closed', 'stopped', 'completed'])
            ->orderBy('id')
            ->chunkById(50, function ($campaigns) use (&$settled, $settlementService) {
                foreach ($campaigns as $campaign) {
                    $force = $campaign->status === 'stopped';
                    $result = $settlementService->settle($campaign, $force);
                    if ($result['settled']) {
                        $settled++;
                    }
                }
            });

        return $settled;
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
            ->select('scraped_at', 'post_url')
            ->first();

        return $row;
    }

    private function calculateVerifiedDays(CampaignTransaction $transaction): array
    {
        $row = $this->getLatestScrapedPost(
            $transaction->unique_code,
            $transaction->shared_on,
            $transaction->post_url ?? null
        );

        if (!$row || !$row->scraped_at) {
            return ['days' => 0, 'post_url' => null];
        }

        $start     = Carbon::parse($transaction->start_date)->startOfDay();
        $scrapedAt = Carbon::parse($row->scraped_at)->startOfDay();

        // Post must have been scraped on or after the campaign start date
        if ($scrapedAt->lt($start)) {
            return ['days' => 0, 'post_url' => null];
        }

        // Day 1 = start_date itself, day 2 = start_date + 1, etc.
        // scraped_at advances each day the scraper confirms the post is still live
        $days = (int) $start->diffInDays($scrapedAt) + 1;

        return [
            'days'     => min($this->getMaxVerifiedDays(), $days),
            'post_url' => $row->post_url ?? null,
        ];
    }

    private function ensurePendingRewardTransaction(CampaignTransaction $transaction): CoinTransaction
    {
        $coins = $transaction->campaign->reward_per_user ?? $transaction->campaign->coins ?? 0;

        $wallet = CoinWallet::firstOrCreate(
            ['user_id' => $transaction->user_id],
            ['balance' => 0]
        );

        $rewardTransaction = CoinTransaction::firstOrCreate([
            'coin_wallet_id'   => $wallet->id,
            'campaign_id'      => $transaction->campaign_id,
            'transaction_type' => 'campaign_reward',
            'type'             => 'credit',
        ], [
            'coin_wallet_id'   => $wallet->id,
            'transaction_id'   => 'TXN-' . $transaction->id,
            'campaign_id'      => $transaction->campaign_id,
            'coin'             => $coins,
            'type'             => 'credit',
            'status'           => 'pending',
            'amount'           => 0,
            'tds'              => 0,
            'convertion_rate'  => 0,
            'transaction_type' => 'campaign_reward',
            'description'      => 'Pending campaign reward for ' . ($transaction->campaign->title ?? 'campaign'),
        ]);

        if ($rewardTransaction->wasRecentlyCreated) {
            $user = User::find($transaction->user_id);
            Helpers::logUserWalletTransaction('created', $rewardTransaction, $user, 'Pending campaign reward created');
        }

        return $rewardTransaction;
    }

    private function markFlagged(CampaignTransaction $transaction): void
    {
        $transaction->status = CampaignTransaction::STATUS_FLAGGED;
        $transaction->violation_reason = 'Post not verified. Submit a valid post URL to avoid deletion.';
        $transaction->save();
        
        // Send FCM notification to user about post being flagged
        $user = User::find($transaction->user_id);
        if ($user && $user->fcm_id) {
            $title = 'Post Flagged ⚠️';
            $body = "Your post for campaign \"{$transaction->campaign->title}\" has been flagged. Please submit a valid post URL to avoid deletion.";
            Helpers::send_push_notif_to_topic($user->fcm_id, $title, $body);
        }
    }

    private function markDeleted(CampaignTransaction $transaction, CoinTransaction $rewardTransaction): void
    {
        $transaction->status = CampaignTransaction::STATUS_DELETED;
        $transaction->violation_reason = 'Post could not be verified after flagging. Participation removed and slot released.';
        $transaction->save();

        if ($rewardTransaction->status === 'pending') {
            $rewardTransaction->status = 'rejected';
            $rewardTransaction->description = 'Campaign reward cancelled for ' . ($transaction->campaign->title ?? 'campaign');
            $rewardTransaction->save();

            $user = User::find($transaction->user_id);
            Helpers::logUserWalletTransaction('rejected', $rewardTransaction, $user, 'Campaign reward cancelled');
        }
        
        // Send FCM notification to user about post being deleted
        $user = User::find($transaction->user_id);
        if ($user && $user->fcm_id) {
            $title = 'Post Deleted ❌';
            $body = "Your post for campaign \"{$transaction->campaign->title}\" could not be verified and has been deleted. Your participation has been removed.";
            Helpers::send_push_notif_to_topic($user->fcm_id, $title, $body);
        }
    }

    private function canReleaseReward(CampaignTransaction $transaction, int $verifiedDays): bool
    {
        if ($verifiedDays < $this->getMaxVerifiedDays()) {
            return false;
        }

        $releaseDate = Carbon::parse($transaction->end_date)->endOfDay()->addDays(self::GRACE_PERIOD_DAYS);

        return Carbon::now()->greaterThanOrEqualTo($releaseDate);
    }

    private function releaseReward(CampaignTransaction $transaction, CoinTransaction $rewardTransaction): void
    {
        if ($rewardTransaction->status === 'completed') {
            $transaction->status = CampaignTransaction::STATUS_COMPLETED;
            $transaction->save();

            return;
        }

        $wallet = CoinWallet::findOrFail($rewardTransaction->coin_wallet_id);
        $wallet->balance += $rewardTransaction->coin;
        $wallet->save();

        $rewardTransaction->status = 'completed';
        $rewardTransaction->description = 'Campaign reward released for ' . ($transaction->campaign->title ?? 'campaign');
        $rewardTransaction->save();

        $postOwner = User::find($transaction->user_id);
        Helpers::logUserWalletTransaction('completed', $rewardTransaction, $postOwner, 'Campaign reward released');

        // Referral bonus: reward the user who referred this user when their post is approved
        $referralCoin = $transaction->campaign->referral_coin ?? 0;
        if ($referralCoin > 0) {
            $postOwner = \App\Models\User::find($transaction->user_id);
            if ($postOwner && !empty($postOwner->friends_code)) {
                $referrer = \App\Models\User::where('referral_code', $postOwner->friends_code)->first();
                if ($referrer) {
                    $referrerWallet = CoinWallet::firstOrCreate(
                        ['user_id' => $referrer->id],
                        ['balance' => 0]
                    );
                    $referrerWallet->balance += $referralCoin;
                    $referrerWallet->save();

                    $referralTransaction = CoinTransaction::create([
                        'coin_wallet_id'   => $referrerWallet->id,
                        'transaction_id'   => 'REF-' . $transaction->id,
                        'campaign_id'      => $transaction->campaign_id,
                        'coin'             => $referralCoin,
                        'amount'           => 0,
                        'tds'              => 0,
                        'convertion_rate'  => 0,
                        'type'             => 'credit',
                        'status'           => 'completed',
                        'transaction_type' => 'referral_reward',
                        'description'      => 'Referral bonus for campaign: ' . ($transaction->campaign->title ?? ''),
                    ]);

                    Helpers::logUserWalletTransaction('created', $referralTransaction, $referrer, 'Referral bonus credited');
                }
            }
        }

        $transaction->status = CampaignTransaction::STATUS_COMPLETED;
        $transaction->save();
    }

    private function sendApprovedNotification(CampaignTransaction $transaction): void
    {
        $user = User::find($transaction->user_id);
        if ($user && $user->fcm_id) {
            $title = 'Post Approved! ✅';
            $body = "Your post for campaign \"{$transaction->campaign->title}\" has been approved. Reward will be released upon campaign completion.";
            Helpers::send_push_notif_to_topic($user->fcm_id, $title, $body);
        }
    }
}
