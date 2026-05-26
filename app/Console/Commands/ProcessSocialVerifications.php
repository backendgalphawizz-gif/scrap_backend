<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\SocialVerificationTransaction;
use App\Models\Seller;
use App\Models\User;
use App\Services\FcmNotificationService;
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

    private function findUniqueCodeInScrapedPosts(string $uniqueCode, string $platform, string $username): bool
    {
        return DB::table('scrapped_posts')
            ->where('unique_code', $uniqueCode)
            ->exists();
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
            User::where('id', $transaction->user_id)->update($updates);
        }
        if ($transaction->seller_id) {
            Seller::where('id', $transaction->seller_id)
                ->update([$statusField => SocialVerificationTransaction::STATUS_VERIFIED]);
        }
    }

    private function markNotVerified(SocialVerificationTransaction $transaction, FcmNotificationService $fcm): void
    {
        $transaction->status          = SocialVerificationTransaction::STATUS_NOT_VERIFIED;
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
        $body     = "We couldn't verify your {$platform} post. Open the app to try again.";
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
