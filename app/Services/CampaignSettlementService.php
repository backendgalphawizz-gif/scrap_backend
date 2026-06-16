<?php

namespace App\Services;

use App\CPU\Helpers;
use App\Models\Campaign;
use App\Models\CampaignTransaction;
use App\Models\SaleCommissionLedger;
use App\Models\SalesCommissionSlab;
use App\Models\SellerWallet;
use App\Models\SellerWalletHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CampaignSettlementService
{
    public const GRACE_PERIOD_DAYS = 1;

    public const SETTLEMENT_PENDING = 'pending';
    public const SETTLEMENT_SETTLED = 'settled';

    public function __construct(
        protected CampaignCreditNoteService $creditNoteService
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function calculateReleasableAmount(Campaign $campaign): array
    {
        $campaign->loadMissing('brand');

        $completedCount = CampaignTransaction::where('campaign_id', $campaign->id)
            ->where('status', CampaignTransaction::STATUS_COMPLETED)
            ->count();

        $reservedCount = CampaignTransaction::where('campaign_id', $campaign->id)
            ->whereIn('status', [
                CampaignTransaction::STATUS_PENDING,
                CampaignTransaction::STATUS_ACTIVE,
                CampaignTransaction::STATUS_APPROVED,
                CampaignTransaction::STATUS_FLAGGED,
            ])
            ->count();

        $gstPercentage = (float) Helpers::get_business_settings('campaign_gst_percentage');
        if ($gstPercentage <= 0) {
            $gstPercentage = 18.0;
        }

        $rewardPerUser  = (float) ($campaign->reward_per_user ?? 0);
        $totalPosts     = (int) ($campaign->total_user_required ?? 0);
        $totalDiscount  = round((float) ($campaign->discount_amount ?? 0), 2);

        // Completed utilization (discount proportionally used for completed posts)
        $utilizedDiscount = $totalPosts > 0
            ? round($totalDiscount * ($completedCount / $totalPosts), 2)
            : 0.0;
        $utilizedGross    = round($completedCount * $rewardPerUser, 2);
        $utilizedTaxable  = round(max(0, $utilizedGross - $utilizedDiscount), 2);
        $utilizedGst      = round($utilizedTaxable * $gstPercentage / 100, 2);
        $utilizedWithGst  = round($utilizedTaxable + $utilizedGst, 2);

        // Unused portion credit note calculation (client formula)
        $unusedPosts      = max(0, $totalPosts - $completedCount);
        $unusedValue      = round($unusedPosts * $rewardPerUser, 2);
        $unusedDiscount   = $totalPosts > 0
            ? round($totalDiscount * ($unusedPosts / $totalPosts), 2)
            : 0.0;
        $netCreditTaxable = round(max(0, $unusedValue - $unusedDiscount), 2);

        // IGST vs CGST+SGST: intra-state when brand's state matches platform state
        $platformState = strtolower(trim((string) (Helpers::get_business_settings('company_state') ?? '')));
        $brand         = $campaign->brand;
        $brandState    = strtolower(trim((string) ($brand->state ?? '')));
        $isIntraState  = $platformState !== '' && $brandState !== '' && $platformState === $brandState;

        $gstCredit    = round($netCreditTaxable * $gstPercentage / 100, 2);
        $cgstReversal = $isIntraState ? round($gstCredit / 2, 2) : 0.0;
        $sgstReversal = $isIntraState ? round($gstCredit - $cgstReversal, 2) : 0.0;
        $igstReversal = $isIntraState ? 0.0 : $gstCredit;

        $releasableAmount = round($netCreditTaxable + $gstCredit, 2);

        return [
            'completed_count'    => $completedCount,
            'reserved_count'     => $reservedCount,
            'utilized_slots'     => $completedCount,
            'reward_per_user'    => $rewardPerUser,
            'gst_percentage'     => $gstPercentage,
            'total_posts'        => $totalPosts,
            'unused_posts'       => $unusedPosts,
            'per_post_amount'    => $rewardPerUser,
            'total_discount'     => $totalDiscount,
            'unused_discount'    => $unusedDiscount,
            'gross_reversal'     => $unusedValue,
            'utilized_taxable'   => $utilizedTaxable,
            'utilized_gst'       => $utilizedGst,
            'utilized_with_gst'  => $utilizedWithGst,
            'taxable_reversal'   => $netCreditTaxable,
            'net_credit_taxable' => $netCreditTaxable,
            'gst_reversal'       => $gstCredit,
            'cgst_reversal'      => $cgstReversal,
            'sgst_reversal'      => $sgstReversal,
            'igst_reversal'      => $igstReversal,
            'is_intra_state'     => $isIntraState,
            'refundable_amount'  => $releasableAmount,
            'releasable_amount'  => $releasableAmount,
        ];
    }

    public function hasInFlightParticipations(Campaign $campaign): bool
    {
        return CampaignTransaction::where('campaign_id', $campaign->id)
            ->whereIn('status', [
                CampaignTransaction::STATUS_PENDING,
                CampaignTransaction::STATUS_ACTIVE,
                CampaignTransaction::STATUS_APPROVED,
                CampaignTransaction::STATUS_FLAGGED,
            ])
            ->exists();
    }

    public function settlementDeadline(Campaign $campaign): Carbon
    {
        $campaignEnd = $campaign->end_date
            ? Carbon::parse($campaign->end_date)->endOfDay()
            : Carbon::parse($campaign->created_at)->endOfDay();

        $latestTransactionEnd = CampaignTransaction::where('campaign_id', $campaign->id)
            ->max('end_date');

        if ($latestTransactionEnd) {
            $txnEnd = Carbon::parse($latestTransactionEnd)->endOfDay();
            if ($txnEnd->gt($campaignEnd)) {
                $campaignEnd = $txnEnd;
            }
        }

        return $campaignEnd->copy()->addDays(self::GRACE_PERIOD_DAYS);
    }

    public function canSettle(Campaign $campaign, bool $forceForStopped = false): bool
    {
        if ($campaign->settlement_status === self::SETTLEMENT_SETTLED) {
            return false;
        }

        if ($this->hasInFlightParticipations($campaign)) {
            return false;
        }

        $eligibleStatuses = ['closed', 'stopped'];
        $legacyCompleted = $campaign->status === 'completed' && $campaign->settlement_status === self::SETTLEMENT_PENDING;

        if (! in_array($campaign->status, $eligibleStatuses, true) && ! $legacyCompleted) {
            return false;
        }

        if ($forceForStopped || $campaign->status === 'stopped') {
            return true;
        }

        return Carbon::now()->greaterThanOrEqualTo($this->settlementDeadline($campaign));
    }

    public function isClosedForEnrollment(Campaign $campaign): bool
    {
        return in_array($campaign->status, ['closed', 'completed', 'stopped'], true);
    }

    public function shouldCloseForEnrollment(Campaign $campaign): bool
    {
        if ($this->isClosedForEnrollment($campaign)) {
            return false;
        }

        $eligibleStatuses = ['active', 'live', 'pending', 'accepted', 'paused'];
        if (! in_array($campaign->status, $eligibleStatuses, true)) {
            return false;
        }

        $endDatePassed = $campaign->end_date
            ? Carbon::parse($campaign->end_date)->endOfDay()->isPast()
            : false;

        $occupiedSlots = $campaign->occupied_slots ?? $campaign->occupiedTransactions()->count();
        $requiredSlots = (int) ($campaign->total_user_required ?? 0);
        $slotsExhausted = $requiredSlots > 0 && $occupiedSlots >= $requiredSlots;

        $rewardPerUser = (float) ($campaign->reward_per_user ?: $campaign->coins ?: 0);
        $totalBudget = (float) ($campaign->total_campaign_budget ?? 0);
        $estimatedSpend = $rewardPerUser * $occupiedSlots;
        $budgetExhausted = $totalBudget > 0 && $estimatedSpend >= $totalBudget;

        return $endDatePassed || $slotsExhausted || $budgetExhausted;
    }

    /**
     * Close all campaigns whose end date has passed (or whose slots/budget are exhausted).
     * Safe to call multiple times — already-closed campaigns are skipped.
     */
    public function closeEligibleCampaigns(): int
    {
        $updated = 0;
        $eligibleStatuses = ['active', 'live', 'pending', 'accepted', 'paused'];

        Campaign::query()
            ->whereIn('status', $eligibleStatuses)
            ->withCount(['occupiedTransactions as occupied_slots'])
            ->orderBy('id')
            ->chunkById(100, function ($campaigns) use (&$updated) {
                foreach ($campaigns as $campaign) {
                    if (! $this->shouldCloseForEnrollment($campaign)) {
                        continue;
                    }

                    $campaign->status = 'closed';
                    $campaign->save();
                    $updated++;
                }
            });

        return $updated;
    }

    /**
     * Settle all campaigns that are closed/stopped/completed and past their grace period.
     * Safe to call multiple times — already-settled campaigns are skipped.
     */
    public function settleEligibleCampaigns(): int
    {
        $settled = 0;

        Campaign::query()
            ->where('settlement_status', self::SETTLEMENT_PENDING)
            ->whereIn('status', ['closed', 'stopped', 'completed'])
            ->orderBy('id')
            ->chunkById(50, function ($campaigns) use (&$settled) {
                foreach ($campaigns as $campaign) {
                    $force = $campaign->status === 'stopped';
                    $result = $this->settle($campaign, $force);
                    if ($result['settled']) {
                        $settled++;
                    }
                }
            });

        return $settled;
    }

    /**
     * @return array{settled: bool, amount: float, message: string}
     */
    public function settle(Campaign $campaign, bool $forceForStopped = false): array
    {
        if (! $this->canSettle($campaign, $forceForStopped)) {
            return [
                'settled' => false,
                'amount' => 0,
                'message' => 'Campaign is not eligible for settlement yet.',
            ];
        }

        $amounts = $this->calculateReleasableAmount($campaign);
        $releasableAmount = (float) ($amounts['releasable_amount'] ?? 0);
        $didSettle = false;

        DB::transaction(function () use ($campaign, $amounts, $releasableAmount, &$didSettle) {
            $locked = Campaign::where('id', $campaign->id)->lockForUpdate()->first();
            if (! $locked || $locked->settlement_status === self::SETTLEMENT_SETTLED) {
                return;
            }

            if ($releasableAmount > 0) {
                $wallet = SellerWallet::firstOrCreate(
                    ['seller_id' => $locked->brand_id],
                    ['wallet_amount' => 0]
                );
                $wallet->wallet_amount = round((float) $wallet->wallet_amount + $releasableAmount, 2);
                $wallet->save();

                SellerWalletHistory::create([
                    'seller_id' => $locked->brand_id,
                    'amount' => $releasableAmount,
                    'remarks' => 'Campaign settlement: ' . ($locked->title ?? 'Campaign #' . $locked->id),
                    'type' => 'credit',
                    'available_balance' => $wallet->wallet_amount,
                ]);
            }

            $locked->settlement_status = self::SETTLEMENT_SETTLED;
            $locked->settled_at = now();
            $locked->amount_returned_to_wallet = $releasableAmount;

            if ($locked->status === 'closed') {
                $locked->status = 'completed';
            }

            if ($locked->status === 'stopped') {
                $locked->refund_status = 'processed';
                $locked->refunded_amount = $releasableAmount;
            }

            $locked->save();

            $this->creditNoteService->issueForSettlement($locked, $amounts);

            if ($locked->status === 'completed') {
                $this->createCampaignSalesCommission($locked);
            }

            $didSettle = true;
        });

        $campaign->refresh();

        if (! $didSettle) {
            return [
                'settled' => false,
                'amount' => (float) ($campaign->amount_returned_to_wallet ?? 0),
                'message' => 'Campaign was already settled.',
            ];
        }

        return [
            'settled' => true,
            'amount' => $releasableAmount,
            'message' => $releasableAmount > 0
                ? "Settled ₹{$releasableAmount} to brand wallet."
                : 'Campaign settled with no wallet credit (fully utilized).',
        ];
    }

    private function createCampaignSalesCommission(Campaign $campaign): void
    {
        if (empty($campaign->sale_id)) {
            return;
        }

        $alreadyExists = SaleCommissionLedger::where('campaign_id', $campaign->id)
            ->where('reference_type', 'campaign_reward')
            ->exists();

        if ($alreadyExists) {
            return;
        }

        $completedCount = CampaignTransaction::where('campaign_id', $campaign->id)
            ->where('status', CampaignTransaction::STATUS_COMPLETED)
            ->count();

        $rewardPerUser = (float) ($campaign->reward_per_user ?? 0);
        $amount = $completedCount * $rewardPerUser;

        if ($amount <= 0) {
            return;
        }

        // ── Determine commission rate via slabs (if configured) ──────────────
        $commissionRate = $this->resolveSlabRate($campaign, $amount);

        // ── Deduct voucher discount from this commission (absorbed by sales) ─
        $discountAbsorbed = round((float) ($campaign->discount_amount ?? 0), 2);
        $grossCommission  = round($amount * $commissionRate / 100, 2);
        $netCommission    = max(0.0, $grossCommission - $discountAbsorbed);

        SaleCommissionLedger::create([
            'sale_id'           => $campaign->sale_id,
            'brand_id'          => $campaign->brand_id,
            'campaign_id'       => $campaign->id,
            'amount'            => $amount,
            'commission_rate'   => $commissionRate,
            'commission_amount' => $netCommission,
            'discount_absorbed' => $discountAbsorbed,
            'reference_type'    => 'campaign_reward',
            'status'            => 'pending',
        ]);
    }

    /**
     * Resolve the commission rate for a sales person using the dynamic slab config.
     *
     * Logic:
     *  1. Load all configured slabs ordered by min_earning.
     *  2. If no slabs exist, fall back to the campaign-snapshotted sales_percentage.
     *  3. Compute the salesperson's current total approved earnings
     *     (sum of commission_amount in approved campaign_reward ledger entries).
     *  4. Add the current payout base (`$amount`) to get the "new total".
     *  5. Find the slab where new_total falls: min_earning <= new_total < max_earning
     *     (null max_earning means the slab covers everything above min_earning).
     *  6. If no slab matches (gap in admin config), fall back to campaign-snapshotted rate.
     */
    private function resolveSlabRate(Campaign $campaign, float $amount): float
    {
        $slabs = SalesCommissionSlab::ordered();

        if ($slabs->isEmpty()) {
            // No slabs configured — use the flat rate snapshotted on the campaign.
            $flatRate = (float) ($campaign->sales_percentage ?? 0);
            return $flatRate;
        }

        // Sum of approved commission earnings for this salesperson (before this entry).
        $currentEarnings = (float) SaleCommissionLedger::where('sale_id', $campaign->sale_id)
            ->where('reference_type', 'campaign_reward')
            ->where('status', 'approved')
            ->sum('commission_amount');

        // The "new total" used to determine which slab applies.
        $newTotal = $currentEarnings + $amount;

        foreach ($slabs as $slab) {
            $min = (float) $slab->min_earning;
            $max = $slab->max_earning !== null ? (float) $slab->max_earning : null;

            $inSlab = $newTotal >= $min && ($max === null || $newTotal < $max);
            if ($inSlab) {
                return (float) $slab->rate;
            }
        }

        // Slab config has a gap — fall back to campaign-snapshotted rate.
        return (float) ($campaign->sales_percentage ?? 0);
    }
}
