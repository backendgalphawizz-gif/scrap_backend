<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\SocialVerificationTransaction;
use App\Models\Seller;
use App\Models\User;
use Carbon\Carbon;

class ProcessSocialVerifications extends Command
{
    protected $signature = 'social:process-verifications';

    protected $description = 'Check scraped posts for pending social verification unique codes and update user status';

    public function handle(): void
    {
        $this->info('Processing social verification transactions...');

        /** @var \Illuminate\Database\Eloquent\Collection<int, SocialVerificationTransaction> $transactions */
        $transactions = SocialVerificationTransaction::with('user')
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
            } elseif (Carbon::now()->gt(Carbon::parse($transaction->end_date)->endOfDay())) {
                $this->markNotVerified($transaction);
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
        if ($transaction->user_id) {
            User::where('id', $transaction->user_id)
                ->update([$statusField => SocialVerificationTransaction::STATUS_VERIFIED]);
        }
        if ($transaction->seller_id) {
            Seller::where('id', $transaction->seller_id)
                ->update([$statusField => SocialVerificationTransaction::STATUS_VERIFIED]);
        }
    }

    private function markNotVerified(SocialVerificationTransaction $transaction): void
    {
        $transaction->status = SocialVerificationTransaction::STATUS_NOT_VERIFIED;
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
    }
}
