<?php

namespace App\Console\Commands;

use App\Models\Campaign;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\CampaignTransaction;
use App\Models\CoinWallet;
use App\Models\CoinTransaction;
use Carbon\Carbon;

class ProcessScrapeResults extends Command
{
    protected $signature = 'campaign:process-results';

    protected $description = 'Verify campaign posts, keep rewards pending, and release them 3 days after campaign completion';

    public function handle(): void
    {
        $this->info('Processing scrape results...');die;

        $transactions = CampaignTransaction::with(['campaign'])
            ->whereIn('status', [
                CampaignTransaction::STATUS_PENDING,
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

            if ($transaction->status === CampaignTransaction::STATUS_APPROVED) {
                if ($this->canReleaseReward($transaction)) {
                    $this->releaseReward($transaction, $rewardTransaction);
                    $released++;
                } else {
                    $pending++;
                }

                continue;
            }

            $scrapedPost = $this->getLatestScrapedPost($transaction->unique_code, $transaction->shared_on, $transaction->post_url);

            if ($scrapedPost) {
                $transaction->status = CampaignTransaction::STATUS_APPROVED;
                $transaction->post_url = $transaction->post_url ?: ($scrapedPost->post_url ?? null);
                $transaction->violation_reason = null;
                $transaction->save();
                $approved++;
            } else {
                $endDate = Carbon::parse($transaction->end_date)->endOfDay();
                if ($transaction->status === CampaignTransaction::STATUS_FLAGGED) {

                    $this->markDeleted($transaction, $rewardTransaction);
                    
                    $this->info('Syncing campaign post day status...',$transaction->campaign_id);
                    $deleted++;
                } elseif (Carbon::now()->gt($endDate)) {
                    $this->markFlagged($transaction);
                    $flagged++;
                } else {
                    $pending++;
                }
            }
        }

        $this->info("Done. Approved: {$approved} | Released: {$released} | Flagged: {$flagged} | Deleted: {$deleted} | Pending: {$pending}");
    }

    private function getLatestScrapedPost(string $uniqueCode, string $platform, ?string $postUrl = null): ?object
    {
        $table = $platform === 'facebook' ? 'facebook_posts_test' : 'tagged_posts_test';

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

    private function ensurePendingRewardTransaction(CampaignTransaction $transaction): CoinTransaction
    {
        $coins = $transaction->campaign->reward_per_user ?? $transaction->campaign->coins ?? 0;

        $wallet = CoinWallet::firstOrCreate(
            ['user_id' => $transaction->user_id],
            ['balance' => 0]
        );

        return CoinTransaction::firstOrCreate([
            'coin_wallet_id'   => $wallet->id,
            'campaign_id'      => $transaction->campaign_id,
            'transaction_type' => 'campaign_reward',
            'type'             => 'credit',
        ], [
            'coin_wallet_id'   => $wallet->id,
            'transaction_id'   => 'CAMP-PENDING-' . $transaction->id,
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
    }

    private function markFlagged(CampaignTransaction $transaction): void
    {
        $transaction->status = CampaignTransaction::STATUS_FLAGGED;
        $transaction->violation_reason = 'Post not verified. Submit a valid post URL to avoid deletion.';
        $transaction->save();
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
        }
    }

    private function canReleaseReward(CampaignTransaction $transaction): bool
    {
        $releaseDate = Carbon::parse($transaction->campaign->end_date)->endOfDay()->addDays(3);

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

        $transaction->status = CampaignTransaction::STATUS_COMPLETED;
        $transaction->save();
    }
}
