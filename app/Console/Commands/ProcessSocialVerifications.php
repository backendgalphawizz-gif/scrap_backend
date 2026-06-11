<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\SocialVerificationTransaction;
use App\Models\Seller;
use App\Models\User;
use App\Services\FcmNotificationService;
use App\Services\FraudDetectionService;
use App\Services\FraudScoreService;
use App\Services\InstagramFollowerService;
use Carbon\Carbon;

class ProcessSocialVerifications extends Command
{
    protected $signature = 'social:process-verifications';

    protected $description = 'Check scraped posts for pending social verification unique codes and update user status';

    public function handle(FcmNotificationService $fcm): void
    {
        $this->info('Processing social verification transactions...');

        /** @var \Illuminate\Database\Eloquent\Collection<int, SocialVerificationTransaction> $transactions */
        $transactions = SocialVerificationTransaction::with(['user', 'seller'])
            ->where('status', SocialVerificationTransaction::STATUS_PENDING)
            ->get();

        $verified = 0;
        $failed   = 0;
        $pending  = 0;

        foreach ($transactions as $transaction) {
            $found = $this->findUniqueCodeInScrapedPosts(
                $transaction->unique_code,
                $transaction->platform,
                $transaction->username
            );

            if ($found) {
                $this->markVerified($transaction);
                $this->runFraudChecksForVerified($transaction);
                $verified++;
            } elseif (Carbon::now()->gt(Carbon::parse($transaction->submitted_at)->addHours(24))) {
                $this->markNotVerified($transaction, $fcm);
                $failed++;
            } else {
                $pending++;
            }
        }

        $this->info("Done. Verified: {$verified} | Not Verified: {$failed} | Pending: {$pending}");
    }

    private function runFraudChecksForVerified(SocialVerificationTransaction $transaction): void
    {
        if (!$transaction->user_id || empty($transaction->username)) {
            return;
        }

        $user = User::find($transaction->user_id);
        if (!$user) {
            return;
        }

        $fraudService = app(FraudDetectionService::class);
        $fraudService->checkDuplicateSocialHandle($user, $transaction->platform, $transaction->username);
        app(FraudScoreService::class)->recalculate($user);
    }

    private function findUniqueCodeInScrapedPosts(string $uniqueCode, string $platform, string $username): bool
    {
        return DB::table('scrapped_posts')
            ->where('unique_code', $uniqueCode)
            ->exists();
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

    private function markVerified(SocialVerificationTransaction $transaction): void
    {
        $transaction->status      = SocialVerificationTransaction::STATUS_VERIFIED;
        $transaction->verified_at = now();
        $transaction->save();

        $statusField = $transaction->platform . '_status';
        $usernameField = $transaction->platform . '_username';
        if ($transaction->user_id) {
            $updates = [$statusField => SocialVerificationTransaction::STATUS_VERIFIED];
            if (filled($transaction->username)) {
                $updates[$usernameField] = $transaction->username;
            }

            // Fetch follower count immediately on Instagram verification
            if ($transaction->platform === 'instagram' && filled($transaction->username)) {
                try {
                    $followers = app(InstagramFollowerService::class)->fetchFollowers($transaction->username);
                    if ($followers !== null) {
                        $updates['instagram_followers'] = $followers;
                    }
                } catch (\Throwable $e) {
                    // Never let follower fetch block verification
                    \Illuminate\Support\Facades\Log::warning('markVerified: failed to fetch instagram followers', [
                        'user_id'  => $transaction->user_id,
                        'username' => $transaction->username,
                        'error'    => $e->getMessage(),
                    ]);
                }
            }

            User::where('id', $transaction->user_id)->update($updates);
        }
        if ($transaction->seller_id) {
            Seller::where('id', $transaction->seller_id)
                ->update([$statusField => SocialVerificationTransaction::STATUS_VERIFIED]);
        }
    }

    private function markNotVerified(SocialVerificationTransaction $transaction, FcmNotificationService $fcm): void
    {
        $failureReason = $this->determinePostFailureReason(
            $transaction->username,
            $transaction->platform,
            $transaction->unique_code
        );

        $transaction->status          = SocialVerificationTransaction::STATUS_NOT_VERIFIED;
        $transaction->failure_reason  = $failureReason;
        $transaction->notified_24h_at = now();
        $transaction->save();

        $statusField = $transaction->platform . '_status';
        if ($transaction->user_id) {
            User::where('id', $transaction->user_id)
                ->update([$statusField => SocialVerificationTransaction::STATUS_NOT_VERIFIED]);
        }
        if ($transaction->seller_id) {
            Seller::where('id', $transaction->seller_id)
                ->update([$statusField => SocialVerificationTransaction::STATUS_NOT_VERIFIED]);
        }

        $platform = ucfirst($transaction->platform);
        $title    = 'Verification Unsuccessful';
        $body     = "We couldn't verify your {$platform} account. {$failureReason}";
        $data     = [
            'type'     => 'social_verification_failed',
            'platform' => $transaction->platform,
        ];

        if ($transaction->user_id && $transaction->user) {
            $fcm->sendToUser($transaction->user, $title, $body, $data);
        } elseif ($transaction->seller_id && $transaction->seller) {
            $fcm->sendToSeller($transaction->seller, $title, $body, $data);
        }
    }
}
