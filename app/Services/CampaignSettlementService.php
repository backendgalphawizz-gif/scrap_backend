<?php

namespace App\Services;

use App\CPU\Helpers;
use App\Models\Campaign;
use App\Models\CampaignTransaction;
use App\Models\SaleCommissionLedger;
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

        $rewardPerUser = (float) ($campaign->reward_per_user ?? 0);
        $taxablePaid = round((float) ($campaign->total_campaign_budget ?? 0), 2);
        $totalBudgetGst = round((float) ($campaign->compign_budget_with_gst ?? 0), 2);
        $gstPaid = round(max(0, $totalBudgetGst - $taxablePaid), 2);

        $utilizedTaxable = round($completedCount * $rewardPerUser, 2);
        $utilizedGst = round($utilizedTaxable * $gstPercentage / 100, 2);
        $utilizedWithGst = round($utilizedTaxable + $utilizedGst, 2);

        $taxableReversal = round(max(0, $taxablePaid - $utilizedTaxable), 2);
        $gstReversal = round(max(0, $gstPaid - $utilizedGst), 2);
        $cgstReversal = round($gstReversal / 2, 2);
        $sgstReversal = round($gstReversal - $cgstReversal, 2);
        $releasableAmount = round($taxableReversal + $gstReversal, 2);

        return [
            'completed_count' => $completedCount,
            'reserved_count' => $reservedCount,
            'utilized_slots' => $completedCount,
            'reward_per_user' => $rewardPerUser,
            'gst_percentage' => $gstPercentage,
            'taxable_paid' => $taxablePaid,
            'gst_paid' => $gstPaid,
            'utilized_raw' => $utilizedTaxable,
            'utilized_taxable' => $utilizedTaxable,
            'utilized_gst' => $utilizedGst,
            'utilized_with_gst' => $utilizedWithGst,
            'taxable_reversal' => $taxableReversal,
            'gst_reversal' => $gstReversal,
            'cgst_reversal' => $cgstReversal,
            'sgst_reversal' => $sgstReversal,
            'total_budget_gst' => $totalBudgetGst,
            'refundable_amount' => $releasableAmount,
            'releasable_amount' => $releasableAmount,
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

        $salesPercentage = (float) ($campaign->sales_percentage ?? 0);
        if ($salesPercentage <= 0) {
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

        SaleCommissionLedger::create([
            'sale_id' => $campaign->sale_id,
            'brand_id' => $campaign->brand_id,
            'campaign_id' => $campaign->id,
            'amount' => $amount,
            'commission_rate' => $salesPercentage,
            'commission_amount' => round($amount * $salesPercentage / 100, 2),
            'reference_type' => 'campaign_reward',
            'status' => 'pending',
        ]);
    }
}
