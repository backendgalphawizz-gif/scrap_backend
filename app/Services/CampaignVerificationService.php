<?php

namespace App\Services;

use App\CPU\Helpers;
use App\Models\CampaignTransaction;
use App\Models\CoinTransaction;
use App\Models\CoinWallet;
use App\Models\User;
use Carbon\Carbon;

class CampaignVerificationService
{
    private const GRACE_PERIOD_DAYS = 0;

    public function getMaxVerifiedDays(): int
    {
        $setting = (int) env('CAMPAIGN_VERIFICATION_DAYS', 2);
        return $setting > 0 ? $setting : 2;
    }

    /**
     * Approve a transaction and immediately release the reward if eligible.
     * Used by both the cron and the admin manual-verify endpoint.
     * Sets manually_verified fields when $adminId is provided.
     */
    public function approveAndRelease(CampaignTransaction $transaction, ?int $adminId = null): string
    {
        $transaction->load('campaign');

        $rewardTransaction = $this->ensurePendingRewardTransaction($transaction);

        $wasAlreadyApproved = $transaction->status === CampaignTransaction::STATUS_APPROVED;

        if (!$wasAlreadyApproved) {
            $transaction->verified_at = now();
        }
        $transaction->status = CampaignTransaction::STATUS_APPROVED;
        $transaction->violation_reason = null;

        if ($adminId !== null) {
            $transaction->manually_verified    = true;
            $transaction->manually_verified_by = $adminId;
            $transaction->manually_verified_at = now();
        }

        $transaction->save();

        if (!$wasAlreadyApproved) {
            $this->sendApprovedNotification($transaction);
        }

        if ($this->canReleaseReward($transaction)) {
            $this->releaseReward($transaction, $rewardTransaction);
            return 'completed';
        }

        return 'approved';
    }

    public function canReleaseReward(CampaignTransaction $transaction): bool
    {
        $releaseDate = Carbon::parse($transaction->end_date)->endOfDay()->addDays(self::GRACE_PERIOD_DAYS);
        return Carbon::now()->greaterThanOrEqualTo($releaseDate);
    }

    public function ensurePendingRewardTransaction(CampaignTransaction $transaction): CoinTransaction
    {
        $transaction->loadMissing('campaign');
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

    public function releaseReward(CampaignTransaction $transaction, CoinTransaction $rewardTransaction): void
    {
        if ($rewardTransaction->status === 'completed') {
            $transaction->status = CampaignTransaction::STATUS_COMPLETED;
            $transaction->save();
            return;
        }

        $wallet = CoinWallet::findOrFail($rewardTransaction->coin_wallet_id);
        $wallet->balance += $rewardTransaction->coin;
        $wallet->save();

        $rewardTransaction->status      = 'completed';
        $rewardTransaction->description = 'Campaign reward released for ' . ($transaction->campaign->title ?? 'campaign');
        $rewardTransaction->save();

        $postOwner = User::find($transaction->user_id);
        Helpers::logUserWalletTransaction('completed', $rewardTransaction, $postOwner, 'Campaign reward released');

        $referralCoin = $transaction->campaign->referral_coin ?? 0;
        if ($referralCoin > 0) {
            $postOwner = $postOwner ?? User::find($transaction->user_id);
            if ($postOwner && !empty($postOwner->friends_code)) {
                $referrer = User::where('referral_code', $postOwner->friends_code)->first();
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
            $body  = "Your post for campaign \"{$transaction->campaign->title}\" has been approved. Reward will be released upon campaign completion.";
            Helpers::send_push_notif_to_topic($user->fcm_id, $title, $body);
        }
    }
}
