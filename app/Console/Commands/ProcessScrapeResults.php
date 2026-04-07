<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\CampaignTransaction;
use App\Models\CoinWallet;
use App\Models\CoinTransaction;
use Carbon\Carbon;

class ProcessScrapeResults extends Command
{
    protected $signature = 'campaign:process-results';

    protected $description = 'Check scraped post results and credit coins for verified 7-day campaigns';

    public function handle(): void
    {
        $this->info('Processing scrape results...');

        $transactions = CampaignTransaction::with(['campaign'])
            ->where('status', 'active')
            ->get();

        $rewarded = 0;
        $rejected = 0;
        $pending  = 0;

        foreach ($transactions as $transaction) {
            if (!$transaction->unique_code) {
                continue;
            }

            $scrapedAt = $this->getLatestScrapedAt($transaction->unique_code, $transaction->shared_on);

            if ($scrapedAt) {
                $startDate = Carbon::parse($transaction->start_date)->startOfDay();
                $daysDiff  = $startDate->diffInDays(Carbon::parse($scrapedAt));

                if ($daysDiff >= 7) {
                    $this->rewardUser($transaction);
                    $rewarded++;
                } else {
                    $pending++;
                }
            } else {
                // Code never found and deadline has passed
                $endDate = Carbon::parse($transaction->end_date)->endOfDay();
                if (Carbon::now()->gt($endDate)) {
                    $transaction->status = 'rejected';
                    $transaction->save();
                    $rejected++;
                } else {
                    $pending++;
                }
            }
        }

        $this->info("Done. Rewarded: {$rewarded} | Rejected: {$rejected} | Pending: {$pending}");
    }

    private function getLatestScrapedAt(string $uniqueCode, string $platform): ?string
    {
        $table = $platform === 'facebook' ? 'facebook_posts_test' : 'tagged_posts_test';

        $row = DB::table($table)
            ->where('unique_code', $uniqueCode)
            ->orderByDesc('scraped_at')
            ->value('scraped_at');

        return $row;
    }

    private function rewardUser(CampaignTransaction $transaction): void
    {
        $coins = $transaction->campaign->reward_per_user ?? $transaction->campaign->coins ?? 0;

        $wallet = CoinWallet::firstOrCreate(
            ['user_id' => $transaction->user_id],
            ['balance' => 0]
        );

        $wallet->balance += $coins;
        $wallet->save();

        CoinTransaction::create([
            'coin_wallet_id'   => $wallet->id,
            'transaction_id'   => 'CAMP-' . $transaction->unique_code . '-' . time(),
            'campaign_id'      => $transaction->campaign_id,
            'coin'             => $coins,
            'type'             => 'credit',
            'amount'           => 0,
            'tds'              => 0,
            'convertion_rate'  => 0,
            'transaction_type' => 'campaign_reward',
            'description'      => 'Campaign reward for ' . ($transaction->campaign->title ?? 'campaign'),
        ]);

        $transaction->status = 'completed';
        $transaction->save();
    }
}
