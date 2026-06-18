<?php

namespace App\Services;

use App\CPU\Helpers;
use App\Models\Campaign;
use App\Models\CampaignCreditNote;
use App\Models\CampaignRefund;
use App\Models\Seller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class CampaignCreditNoteService
{
    public function __construct(
        protected CampaignInvoiceService $invoiceService
    ) {
    }

    public function creditNoteNumber(Campaign $campaign): string
    {
        $prefix = (bool) $campaign->generate_gst_invoice ? 'CN-GST-CAM-' : 'CN-CAM-';
        return $prefix . str_pad((string) $campaign->id, 6, '0', STR_PAD_LEFT);
    }

    public function originalInvoiceNumber(Campaign $campaign): string
    {
        return $this->invoiceService->invoiceNumber($campaign);
    }

    /**
     * @param  array<string, mixed>  $settlementData  From CampaignSettlementService::calculateReleasableAmount
     */
    public function issueForSettlement(Campaign $campaign, array $settlementData, ?string $reason = null): ?CampaignCreditNote
    {
        $existing = CampaignCreditNote::where('campaign_id', $campaign->id)->first();
        if ($existing) {
            return $existing;
        }

        $taxableReversal = (float) ($settlementData['net_credit_taxable'] ?? $settlementData['taxable_reversal'] ?? 0);
        $gstReversal     = (float) ($settlementData['gst_reversal'] ?? 0);

        if ($taxableReversal <= 0 && $gstReversal <= 0) {
            return null;
        }

        $isIntraState = (bool) ($settlementData['is_intra_state'] ?? true);
        $gstFallback  = round($gstReversal / 2, 2);

        return CampaignCreditNote::create([
            'campaign_id'          => $campaign->id,
            'campaign_refund_id'   => null,
            'brand_id'             => $campaign->brand_id,
            'original_invoice_no'  => $this->originalInvoiceNumber($campaign),
            'credit_note_no'       => $this->creditNoteNumber($campaign),
            'taxable_reversal_amount' => $taxableReversal,
            'gst_reversal_amount'  => $gstReversal,
            'cgst_reversal'        => (float) ($settlementData['cgst_reversal'] ?? ($isIntraState ? $gstFallback : 0)),
            'sgst_reversal'        => (float) ($settlementData['sgst_reversal'] ?? ($isIntraState ? round($gstReversal - $gstFallback, 2) : 0)),
            'reason'               => $reason ?: 'Unused campaign budget settlement',
            'credit_note_date'     => ($campaign->settled_at ?? now())->toDateString(),
            'status'               => CampaignCreditNote::STATUS_ISSUED,
            'purchased_posts'      => (int) ($settlementData['total_posts'] ?? 0) ?: null,
            'completed_posts'      => (int) ($settlementData['completed_count'] ?? 0) ?: null,
            'unutilized_posts'     => (int) ($settlementData['unused_posts'] ?? 0) ?: null,
            'per_post_amount'      => (float) ($settlementData['per_post_amount'] ?? 0),
            'gross_reversal_amount' => (float) ($settlementData['gross_reversal'] ?? 0),
            'discount_reversal'    => (float) ($settlementData['unused_discount'] ?? 0),
            'igst_reversal'        => (float) ($settlementData['igst_reversal'] ?? 0),
            'is_intra_state'       => $isIntraState,
        ]);
    }

    /**
     * @param  array<string, mixed>  $refundData  From CampaignController::calculateRefund
     */
    public function issueForRefund(Campaign $campaign, CampaignRefund $refund, array $refundData, ?string $reason = null): ?CampaignCreditNote
    {
        $existing = CampaignCreditNote::where('campaign_id', $campaign->id)->first();
        if ($existing) {
            return $existing;
        }

        $taxableReversal = (float) ($refundData['net_credit_taxable'] ?? $refundData['taxable_reversal'] ?? 0);
        $gstReversal     = (float) ($refundData['gst_reversal'] ?? 0);

        if ($taxableReversal <= 0 && $gstReversal <= 0) {
            return null;
        }

        $isIntraState = (bool) ($refundData['is_intra_state'] ?? true);
        $gstFallback  = round($gstReversal / 2, 2);

        return CampaignCreditNote::create([
            'campaign_id'          => $campaign->id,
            'campaign_refund_id'   => $refund->id,
            'brand_id'             => $campaign->brand_id,
            'original_invoice_no'  => $this->originalInvoiceNumber($campaign),
            'credit_note_no'       => $this->creditNoteNumber($campaign),
            'taxable_reversal_amount' => $taxableReversal,
            'gst_reversal_amount'  => $gstReversal,
            'cgst_reversal'        => (float) ($refundData['cgst_reversal'] ?? ($isIntraState ? $gstFallback : 0)),
            'sgst_reversal'        => (float) ($refundData['sgst_reversal'] ?? ($isIntraState ? round($gstReversal - $gstFallback, 2) : 0)),
            'reason'               => $reason ?: 'Unused campaign budget refund',
            'credit_note_date'     => ($refund->completed_at ?? now())->toDateString(),
            'status'               => CampaignCreditNote::STATUS_ISSUED,
            'purchased_posts'      => (int) ($refundData['total_posts'] ?? 0) ?: null,
            'completed_posts'      => (int) ($refundData['completed_count'] ?? 0) ?: null,
            'unutilized_posts'     => (int) ($refundData['unused_posts'] ?? 0) ?: null,
            'per_post_amount'      => (float) ($refundData['per_post_amount'] ?? 0),
            'gross_reversal_amount' => (float) ($refundData['gross_reversal'] ?? 0),
            'discount_reversal'    => (float) ($refundData['unused_discount'] ?? 0),
            'igst_reversal'        => (float) ($refundData['igst_reversal'] ?? 0),
            'is_intra_state'       => $isIntraState,
        ]);
    }

    public function canDownload(Campaign $campaign): bool
    {
        return CampaignCreditNote::where('campaign_id', $campaign->id)->exists();
    }

    /**
     * @return array{ok: bool, message: string, code: int}
     */
    public function validateDownload(Campaign $campaign, ?int $brandId = null): array
    {
        if ($brandId !== null && (int) $campaign->brand_id !== $brandId) {
            return [
                'ok' => false,
                'message' => 'Campaign not found or you do not have permission to access this campaign.',
                'code' => 404,
            ];
        }

        if (!$this->canDownload($campaign)) {
            return [
                'ok' => false,
                'message' => 'Credit note is not available for this campaign.',
                'code' => 422,
            ];
        }

        return ['ok' => true, 'message' => '', 'code' => 200];
    }

    public function buildCreditNoteData(Campaign $campaign): array
    {
        $campaign->loadMissing('brand');
        $creditNote = CampaignCreditNote::where('campaign_id', $campaign->id)->firstOrFail();

        $brand = $campaign->brand instanceof Seller
            ? $campaign->brand
            : Seller::find($campaign->brand_id);

        $invoiceData   = $this->invoiceService->buildInvoiceData($campaign);
        $gstPercentage = (float) ($invoiceData['amounts']['gst_percentage'] ?? 18);
        $isIntraState  = (bool) ($creditNote->is_intra_state ?? $invoiceData['is_intra_state'] ?? true);

        $brandName = trim(($brand->f_name ?? '') . ' ' . ($brand->l_name ?? ''));
        if ($brandName === '') {
            $brandName = $brand->username ?? 'Brand';
        }

        $grossReversal   = (float) ($creditNote->gross_reversal_amount ?? $creditNote->taxable_reversal_amount);
        $discountReversal = (float) ($creditNote->discount_reversal ?? 0);
        $taxableReversal = (float) $creditNote->taxable_reversal_amount;
        $gstReversal     = (float) $creditNote->gst_reversal_amount;
        $cgstReversal    = (float) $creditNote->cgst_reversal;
        $sgstReversal    = (float) $creditNote->sgst_reversal;
        $igstReversal    = (float) ($creditNote->igst_reversal ?? ($isIntraState ? 0.0 : $gstReversal));

        // Derive discount % for display (e.g. "10%")
        $purchasedPosts   = (int) ($creditNote->purchased_posts ?? 0);
        $unutilizedPosts  = (int) ($creditNote->unutilized_posts ?? 0);
        $perPostAmount    = (float) ($creditNote->per_post_amount ?? 0);
        $totalDiscount    = $invoiceData['amounts']['discount_amount'] ?? 0;
        $discountPct      = ($grossReversal > 0 && $discountReversal > 0)
            ? round($discountReversal / $grossReversal * 100, 2)
            : ($totalDiscount > 0 && ($purchasedPosts * $perPostAmount) > 0
                ? round($totalDiscount / ($purchasedPosts * $perPostAmount) * 100, 2)
                : 0.0);

        return [
            'credit_note'       => $creditNote,
            'campaign'          => $campaign,
            'is_gst'            => (bool) $campaign->generate_gst_invoice,
            'original_invoice_no' => $creditNote->original_invoice_no,
            'credit_note_no'    => $creditNote->credit_note_no,
            'credit_note_date'  => $creditNote->credit_note_date->format('d/m/Y'),
            'reason'            => $creditNote->reason,
            'company'           => $invoiceData['company'],
            'brand'             => $invoiceData['brand'],
            'is_intra_state'    => $isIntraState,
            'amounts' => [
                'gross_reversal'   => $grossReversal,
                'discount_reversal' => $discountReversal,
                'discount_pct'     => $discountPct,
                'taxable_reversal' => $taxableReversal,
                'gst_reversal'     => $gstReversal,
                'cgst_reversal'    => $cgstReversal,
                'sgst_reversal'    => $sgstReversal,
                'igst_reversal'    => $igstReversal,
                'total_reversal'   => round($taxableReversal + $gstReversal, 2),
                'gst_percentage'   => $gstPercentage,
                'cgst_rate'        => $isIntraState ? $gstPercentage / 2 : 0.0,
                'sgst_rate'        => $isIntraState ? $gstPercentage / 2 : 0.0,
                'igst_rate'        => $isIntraState ? 0.0 : $gstPercentage,
                'is_intra_state'   => $isIntraState,
            ],
            'posts' => [
                'purchased'       => $purchasedPosts,
                'completed'       => (int) ($creditNote->completed_posts ?? 0),
                'unutilized'      => $unutilizedPosts,
                'per_post_amount' => $perPostAmount,
            ],
            'brand_display_name' => $brandName,
        ];
    }

    public function downloadResponse(Campaign $campaign): SymfonyResponse
    {
        $data = $this->buildCreditNoteData($campaign);
        $isGst = (bool) $campaign->generate_gst_invoice;
        $view = $isGst ? 'invoices.campaign-gst-credit-note' : 'invoices.campaign-normal-credit-note';
        $filename = ($isGst ? 'campaign-gst-credit-note-' : 'campaign-credit-note-') . $campaign->id . '.html';
        $html = View::make($view, $data)->render();

        return response($html, Response::HTTP_OK, [
            'Content-Type' => 'text/html; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
        ]);
    }
}
