<?php

namespace App\Console\Commands;

use App\Models\Campaign;
use App\Models\SaleCommissionLedger;
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
    private const MAX_VERIFIED_DAYS = 3;
    private const GRACE_PERIOD_DAYS = 1;

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
            $verifiedDays = $this->calculateVerifiedDays($transaction);
            $transaction->day_status = $verifiedDays;

            if ($verifiedDays >= self::MAX_VERIFIED_DAYS) {
                if ($transaction->status !== CampaignTransaction::STATUS_APPROVED) {
                    $approved++;
                }
                $transaction->status = CampaignTransaction::STATUS_APPROVED;
                $transaction->violation_reason = null;
                $transaction->save();

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

        $autoCompletedCampaigns = $this->autoCompleteEligibleCampaigns();

        $this->info("Auto-completed campaigns: {$autoCompletedCampaigns}");
        $this->info("Done. Approved: {$approved} | Released: {$released} | Flagged: {$flagged} | Deleted: {$deleted} | Pending: {$pending}");
    }

    private function autoCompleteEligibleCampaigns(): int
    {
        $updated = 0;
        $eligibleStatuses = ['active', 'live', 'pending', 'accepted', 'paused'];

        Campaign::query()
            ->whereIn('status', $eligibleStatuses)
            ->withCount(['occupiedTransactions as occupied_slots'])
            ->orderBy('id')
            ->chunkById(100, function ($campaigns) use (&$updated) {
                foreach ($campaigns as $campaign) {
                    $endDatePassed = $campaign->end_date
                        ? Carbon::parse($campaign->end_date)->endOfDay()->isPast()
                        : false;

                    $occupiedSlots = (int) ($campaign->occupied_slots ?? 0);
                    $requiredSlots = (int) ($campaign->total_user_required ?? 0);
                    $slotsExhausted = $requiredSlots > 0 && $occupiedSlots >= $requiredSlots;

                    $rewardPerUser = (float) ($campaign->reward_per_user ?: $campaign->coins ?: 0);
                    $totalBudget = (float) ($campaign->total_campaign_budget ?? 0);
                    $estimatedSpend = $rewardPerUser * $occupiedSlots;
                    $budgetExhausted = $totalBudget > 0 && $estimatedSpend >= $totalBudget;

                    if (! $endDatePassed && ! $slotsExhausted && ! $budgetExhausted) {
                        continue;
                    }

                    $campaign->status = 'completed';
                    $campaign->save();
                    $this->createCampaignSalesCommission($campaign);
                    $updated++;
                }
            });

        return $updated;
    }

    private function createCampaignSalesCommission(Campaign $campaign): void
    {
        if (empty($campaign->sale_id)) {
            return;
        }

        $salesPercentage = (float) ($campaign->sales_percentage ?? 0);
        if ($salesPercentage <= 0) {
            return;
        }

        // Idempotent: skip if a commission entry already exists for this campaign
        $alreadyExists = SaleCommissionLedger::where('campaign_id', $campaign->id)
            ->where('reference_type', 'campaign_reward')
            ->exists();

        if ($alreadyExists) {
            return;
        }

        // Base amount: sum of reward_per_user for all completed (rewarded) transactions
        $completedCount = CampaignTransaction::where('campaign_id', $campaign->id)
            ->where('status', CampaignTransaction::STATUS_COMPLETED)
            ->count();

        $rewardPerUser = (float) ($campaign->reward_per_user ?? 0);
        $amount = $completedCount * $rewardPerUser;

        if ($amount <= 0) {
            return;
        }

        SaleCommissionLedger::create([
            'sale_id'         => $campaign->sale_id,
            'brand_id'        => $campaign->brand_id,
            'campaign_id'     => $campaign->id,
            'amount'          => $amount,
            'commission_rate' => $salesPercentage,
            'commission_amount' => round($amount * $salesPercentage / 100, 2),
            'reference_type'  => 'campaign_reward',
            'status'          => 'pending',
        ]);
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

    private function calculateVerifiedDays(CampaignTransaction $transaction): int
    {
        $row = $this->getLatestScrapedPost(
            $transaction->unique_code,
            $transaction->shared_on,
            $transaction->post_url ?? null
        );

        if (!$row || !$row->scraped_at) {
            return 0;
        }

        $start     = Carbon::parse($transaction->start_date)->startOfDay();
        $scrapedAt = Carbon::parse($row->scraped_at)->startOfDay();

        // Post must have been scraped on or after the campaign start date
        if ($scrapedAt->lt($start)) {
            return 0;
        }

        // Day 1 = start_date itself, day 2 = start_date + 1, etc.
        // scraped_at advances each day the scraper confirms the post is still live
        $days = (int) $start->diffInDays($scrapedAt) + 1;

        return min(self::MAX_VERIFIED_DAYS, $days);
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

    private function canReleaseReward(CampaignTransaction $transaction, int $verifiedDays): bool
    {
        if ($verifiedDays < self::MAX_VERIFIED_DAYS) {
            return false;
        }

        $releaseDate = Carbon::parse($transaction->campaign->end_date)->endOfDay()->addDays(self::GRACE_PERIOD_DAYS);

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

                    CoinTransaction::create([
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
                }
            }
        }

        $transaction->status = CampaignTransaction::STATUS_COMPLETED;
        $transaction->save();
    }
}
