<?php

namespace App\Services;

use App\CPU\Helpers;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class TdsCalculationService
{
    public function getConversionRate(): float
    {
        return max(0.0001, (float) Helpers::get_business_settings('upi_value'));
    }

    public function getMaxCoinWithdrawal(): float
    {
        $max = Helpers::get_business_settings('max_coin_withdrawal');

        return (float) ($max !== null && $max !== '' ? $max : 20000);
    }

    public function getMinCoinWithdrawal(): float
    {
        $min = Helpers::get_business_settings('minimum_coin_withdrawl');

        return (float) ($min !== null && $min !== '' ? $min : 0);
    }

    public function getTdsRateValidPan(): float
    {
        $rate = Helpers::get_business_settings('tds_rate_valid_pan');
        if ($rate === null || $rate === '') {
            $rate = Helpers::get_business_settings('tds_percent');
        }

        return (float) ($rate !== null && $rate !== '' ? $rate : 1);
    }

    public function getTdsRateInvalidPan(): float
    {
        $rate = Helpers::get_business_settings('tds_rate_invalid_pan');

        return (float) ($rate !== null && $rate !== '' ? $rate : 20);
    }

    public function getTdsSection(): string
    {
        $section = Helpers::get_business_settings('tds_section');

        return (string) ($section !== null && $section !== '' ? $section : '194C');
    }

    public function assertKycVerified(User $user): void
    {
        $missing = [];
        if ($user->pan_status !== 'Verified') {
            $missing[] = 'PAN';
        }
        if ($user->upi_status !== 'Verified') {
            $missing[] = 'UPI';
        }

        if ($missing !== []) {
            throw ValidationException::withMessages([
                'kyc' => 'Complete verification for ' . implode(', ', $missing) . ' before redeeming coins.',
            ]);
        }
    }

    /**
     * @return array{
     *     coins: float,
     *     conversion_rate: float,
     *     gross_amount: float,
     *     tds_amount: float,
     *     tds_rate: float,
     *     tds_section: string,
     *     net_amount: float,
     *     pan_status_at_withdrawal: string|null
     * }
     */
    // ── Sales-specific TDS ────────────────────────────────────────────────────

    public function getSalesTdsRateValidPan(): float
    {
        $rate = Helpers::get_business_settings('sales_tds_rate_valid_pan');

        return (float) ($rate !== null && $rate !== '' ? $rate : 5);
    }

    public function getSalesTdsRateInvalidPan(): float
    {
        $rate = Helpers::get_business_settings('sales_tds_rate_invalid_pan');

        return (float) ($rate !== null && $rate !== '' ? $rate : 20);
    }

    public function getSalesTdsSection(): string
    {
        $section = Helpers::get_business_settings('sales_tds_section');

        return (string) ($section !== null && $section !== '' ? $section : '194H');
    }

    /**
     * Compute TDS breakdown for a sales withdrawal (direct rupee amount).
     *
     * @return array{
     *     gross_amount: float,
     *     tds_amount: float,
     *     tds_rate: float,
     *     tds_section: string,
     *     net_amount: float,
     *     pan_status_at_withdrawal: string|null
     * }
     */
    public function computeSalesWithdrawal(\App\Models\Sale $sale, float $grossAmount): array
    {
        $tdsRatePercent = $sale->pan_status === 'Verified'
            ? $this->getSalesTdsRateValidPan()
            : $this->getSalesTdsRateInvalidPan();

        $tdsAmount = round($grossAmount * ($tdsRatePercent / 100), 2);
        $netAmount = round($grossAmount - $tdsAmount, 2);

        return [
            'gross_amount'             => $grossAmount,
            'tds_amount'               => $tdsAmount,
            'tds_rate'                 => $tdsRatePercent,
            'tds_section'              => $this->getSalesTdsSection(),
            'net_amount'               => $netAmount,
            'pan_status_at_withdrawal' => $sale->pan_status,
        ];
    }

    // ── User coin withdrawals ─────────────────────────────────────────────────

    public function computeWithdrawal(User $user, float $coins): array
    {
        $this->assertKycVerified($user);

        $conversionRate = $this->getConversionRate();
        $minCoins = $this->getMinCoinWithdrawal();
        $maxCoins = $this->getMaxCoinWithdrawal();

        if ($minCoins > 0 && $coins < $minCoins) {
            throw ValidationException::withMessages([
                'coins' => 'Minimum withdrawal is ' . number_format($minCoins, 0) . ' coins.',
            ]);
        }

        if ($coins > $maxCoins) {
            throw ValidationException::withMessages([
                'coins' => 'Maximum withdrawal per request is ' . number_format($maxCoins, 0) . ' coins.',
            ]);
        }

        $grossRupees = round($coins * $conversionRate, 2);

        $tdsRatePercent = $user->pan_status === 'Verified'
            ? $this->getTdsRateValidPan()
            : $this->getTdsRateInvalidPan();

        $tdsAmount = round($grossRupees * ($tdsRatePercent / 100), 2);
        $netRupees = round($grossRupees - $tdsAmount, 2);

        return [
            'coins' => $coins,
            'conversion_rate' => $conversionRate,
            'gross_amount' => $grossRupees,
            'tds_amount' => $tdsAmount,
            'tds_rate' => $tdsRatePercent,
            'tds_section' => $this->getTdsSection(),
            'net_amount' => $netRupees,
            'pan_status_at_withdrawal' => $user->pan_status,
        ];
    }
}
