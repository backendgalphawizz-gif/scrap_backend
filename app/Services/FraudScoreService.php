<?php

namespace App\Services;

use App\Models\CoinWallet;
use App\Models\FraudSignal;
use App\Models\User;

class FraudScoreService
{
    /**
     * Severity → score contribution mapping.
     * Score is capped at 100.
     */
    private const SEVERITY_WEIGHTS = [
        FraudSignal::SEVERITY_CRITICAL => 50,
        FraudSignal::SEVERITY_HIGH     => 25,
        FraudSignal::SEVERITY_MEDIUM   => 10,
        FraudSignal::SEVERITY_LOW      => 5,
    ];

    public function recalculate(User $user): void
    {
        $signals = FraudSignal::where('user_id', $user->id)
            ->unresolved()
            ->get(['severity']);

        $score = $signals->sum(
            fn($s) => self::SEVERITY_WEIGHTS[$s->severity] ?? 5
        );

        $score = min($score, 100);

        $status = match (true) {
            $score >= 80 => 'blocked',
            $score >= 50 => 'flagged',
            $score >= 20 => 'watch',
            default      => 'clean',
        };

        $user->fraud_score         = $score;
        $user->fraud_status        = $status;
        $user->last_fraud_check_at = now();
        $user->saveQuietly();

        if ($status === 'blocked') {
            CoinWallet::where('user_id', $user->id)
                ->update(['withdrawal_frozen' => true]);
        }
    }
}
