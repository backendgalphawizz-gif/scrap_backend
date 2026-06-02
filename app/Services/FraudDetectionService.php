<?php

namespace App\Services;

use App\CPU\Helpers;
use App\Models\CampaignTransaction;
use App\Models\CoinTransaction;
use App\Models\CoinWallet;
use App\Models\FraudSignal;
use App\Models\ScrappedPost;
use App\Models\User;

class FraudDetectionService
{
    /**
     * Check if the given device_id is associated with too many accounts.
     * Always updates the user's device_id, returns true if a signal was created.
     */
    public function checkDuplicateDevice(User $user, ?string $deviceId): bool
    {
        if (empty($deviceId)) {
            return false;
        }

        // Always store/update device_id
        $user->device_id = $deviceId;
        $user->saveQuietly();

        $maxAccounts = (int) Helpers::get_business_settings('max_accounts_per_device') ?: 2;

        $count = User::where('device_id', $deviceId)
            ->where('id', '!=', $user->id)
            ->count();

        if ($count < $maxAccounts) {
            return false;
        }

        // Don't create duplicate unresolved signal for same device
        $alreadyFlagged = FraudSignal::where('user_id', $user->id)
            ->where('signal_type', FraudSignal::TYPE_DUPLICATE_DEVICE)
            ->where('signal_value', $deviceId)
            ->where('resolved', false)
            ->exists();

        if ($alreadyFlagged) {
            return false;
        }

        FraudSignal::create([
            'user_id'      => $user->id,
            'signal_type'  => FraudSignal::TYPE_DUPLICATE_DEVICE,
            'signal_value' => $deviceId,
            'severity'     => FraudSignal::SEVERITY_HIGH,
            'meta'         => ['accounts_on_device' => $count + 1],
        ]);

        return true;
    }

    /**
     * Check if the UPI/bank value is already used by another user.
     * Returns true if a critical signal was created (caller should block withdrawal).
     */
    public function checkDuplicateUpi(User $user, ?string $upiId): bool
    {
        if (empty($upiId)) {
            return false;
        }

        $usedByOther = CoinTransaction::where('type', 'debit')
            ->where('value', $upiId)
            ->whereHas('wallet', fn($q) => $q->where('user_id', '!=', $user->id))
            ->exists();

        if (!$usedByOther) {
            return false;
        }

        // Idempotent — don't stack signals for same UPI
        $alreadyFlagged = FraudSignal::where('user_id', $user->id)
            ->where('signal_type', FraudSignal::TYPE_DUPLICATE_UPI)
            ->where('signal_value', $upiId)
            ->where('resolved', false)
            ->exists();

        if ($alreadyFlagged) {
            // Signal already exists, still block the withdrawal
            return true;
        }

        FraudSignal::create([
            'user_id'      => $user->id,
            'signal_type'  => FraudSignal::TYPE_DUPLICATE_UPI,
            'signal_value' => $upiId,
            'severity'     => FraudSignal::SEVERITY_CRITICAL,
        ]);

        // Freeze wallet immediately
        CoinWallet::where('user_id', $user->id)->update(['withdrawal_frozen' => true]);

        return true;
    }

    /**
     * Check for referral abuse at registration time.
     * Two checks: velocity (too many uses) and cycle (A refers B who refers A).
     */
    public function checkReferralAbuse(User $user, ?string $friendsCode): bool
    {
        if (empty($friendsCode)) {
            return false;
        }

        $signalCreated = false;

        // Check 1: velocity — same referral code used by too many new accounts
        $maxUses = (int) Helpers::get_business_settings('max_referral_uses') ?: 20;
        $usageCount = User::where('friends_code', $friendsCode)
            ->where('id', '!=', $user->id)
            ->count();

        if ($usageCount >= $maxUses) {
            FraudSignal::create([
                'user_id'      => $user->id,
                'signal_type'  => FraudSignal::TYPE_REFERRAL_ABUSE,
                'signal_value' => $friendsCode,
                'severity'     => FraudSignal::SEVERITY_MEDIUM,
                'meta'         => ['reason' => 'velocity', 'usage_count' => $usageCount + 1],
            ]);
            $signalCreated = true;
        }

        // Check 2: cycle detection — referrer's friends_code == this user's own referral_code
        $referrer = User::where('referral_code', $friendsCode)->first();
        if ($referrer && filled($referrer->friends_code) && $referrer->friends_code === $user->referral_code) {
            FraudSignal::create([
                'user_id'      => $user->id,
                'signal_type'  => FraudSignal::TYPE_REFERRAL_ABUSE,
                'signal_value' => $friendsCode,
                'severity'     => FraudSignal::SEVERITY_HIGH,
                'meta'         => ['reason' => 'cycle', 'referrer_id' => $referrer->id],
            ]);
            $signalCreated = true;
        }

        return $signalCreated;
    }

    /**
     * Check if a social handle is already verified on another account.
     * Auto-freezes the wallet on critical detection.
     */
    public function checkDuplicateSocialHandle(User $user, string $platform, string $username): bool
    {
        if (empty($username)) {
            return false;
        }

        $statusField   = $platform . '_status';
        $usernameField = $platform . '_username';

        $usedByOther = User::where($usernameField, $username)
            ->where($statusField, 'verified')
            ->where('id', '!=', $user->id)
            ->exists();

        if (!$usedByOther) {
            return false;
        }

        $alreadyFlagged = FraudSignal::where('user_id', $user->id)
            ->where('signal_type', FraudSignal::TYPE_DUPLICATE_SOCIAL)
            ->where('signal_value', $username)
            ->where('resolved', false)
            ->exists();

        if (!$alreadyFlagged) {
            FraudSignal::create([
                'user_id'      => $user->id,
                'signal_type'  => FraudSignal::TYPE_DUPLICATE_SOCIAL,
                'signal_value' => $username,
                'severity'     => FraudSignal::SEVERITY_CRITICAL,
                'meta'         => ['platform' => $platform],
            ]);
        }

        // Always freeze wallet on duplicate social handle
        CoinWallet::where('user_id', $user->id)->update(['withdrawal_frozen' => true]);

        return true;
    }

    /**
     * Check if a completed campaign post has been deleted from scraped posts.
     * Returns true if a signal was created.
     */
    public function checkPostDeletedAfterCredit(CampaignTransaction $transaction): bool
    {
        $stillExists = ScrappedPost::where('unique_code', $transaction->unique_code)->exists();

        if ($stillExists) {
            return false;
        }

        // Don't create a duplicate unresolved signal for the same transaction
        $alreadyFlagged = FraudSignal::where('user_id', $transaction->user_id)
            ->where('signal_type', FraudSignal::TYPE_POST_DELETED)
            ->where('signal_value', $transaction->unique_code)
            ->where('resolved', false)
            ->exists();

        if ($alreadyFlagged) {
            return false;
        }

        FraudSignal::create([
            'user_id'      => $transaction->user_id,
            'signal_type'  => FraudSignal::TYPE_POST_DELETED,
            'signal_value' => $transaction->unique_code,
            'severity'     => FraudSignal::SEVERITY_HIGH,
            'meta'         => [
                'campaign_id'    => $transaction->campaign_id,
                'transaction_id' => $transaction->id,
                'earning'        => $transaction->earning,
            ],
        ]);

        return true;
    }
}
