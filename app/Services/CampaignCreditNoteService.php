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
        $prefix = (bool) $campaign->generate_gst_invoice ? 'INV-GST-CAM-' : 'INV-CAM-';
        return $prefix . str_pad((string) $campaign->id, 6, '0', STR_PAD_LEFT);
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

        $taxableReversal = (float) ($settlementData['taxable_reversal'] ?? 0);
        $gstReversal = (float) ($settlementData['gst_reversal'] ?? 0);

        if ($taxableReversal <= 0 && $gstReversal <= 0) {
            return null;
        }

        return CampaignCreditNote::create([
            'campaign_id' => $campaign->id,
            'campaign_refund_id' => null,
            'brand_id' => $campaign->brand_id,
            'original_invoice_no' => $this->originalInvoiceNumber($campaign),
            'credit_note_no' => $this->creditNoteNumber($campaign),
            'taxable_reversal_amount' => $taxableReversal,
            'gst_reversal_amount' => $gstReversal,
            'cgst_reversal' => (float) ($settlementData['cgst_reversal'] ?? round($gstReversal / 2, 2)),
            'sgst_reversal' => (float) ($settlementData['sgst_reversal'] ?? round($gstReversal - round($gstReversal / 2, 2), 2)),
            'reason' => $reason ?: 'Unused campaign budget settlement',
            'credit_note_date' => ($campaign->settled_at ?? now())->toDateString(),
            'status' => CampaignCreditNote::STATUS_ISSUED,
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

        $taxableReversal = (float) ($refundData['taxable_reversal'] ?? 0);
        $gstReversal = (float) ($refundData['gst_reversal'] ?? 0);

        if ($taxableReversal <= 0 && $gstReversal <= 0) {
            return null;
        }

        return CampaignCreditNote::create([
            'campaign_id' => $campaign->id,
            'campaign_refund_id' => $refund->id,
            'brand_id' => $campaign->brand_id,
            'original_invoice_no' => $this->originalInvoiceNumber($campaign),
            'credit_note_no' => $this->creditNoteNumber($campaign),
            'taxable_reversal_amount' => $taxableReversal,
            'gst_reversal_amount' => $gstReversal,
            'cgst_reversal' => (float) ($refundData['cgst_reversal'] ?? round($gstReversal / 2, 2)),
            'sgst_reversal' => (float) ($refundData['sgst_reversal'] ?? round($gstReversal - round($gstReversal / 2, 2), 2)),
            'reason' => $reason ?: 'Unused campaign budget refund',
            'credit_note_date' => ($refund->completed_at ?? now())->toDateString(),
            'status' => CampaignCreditNote::STATUS_ISSUED,
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

        $invoiceData = $this->invoiceService->buildInvoiceData($campaign);
        $gstPercentage = (float) ($invoiceData['amounts']['gst_percentage'] ?? 18);

        $brandName = trim(($brand->f_name ?? '') . ' ' . ($brand->l_name ?? ''));
        if ($brandName === '') {
            $brandName = $brand->username ?? 'Brand';
        }

        return [
            'credit_note' => $creditNote,
            'campaign' => $campaign,
            'is_gst' => (bool) $campaign->generate_gst_invoice,
            'original_invoice_no' => $creditNote->original_invoice_no,
            'credit_note_no' => $creditNote->credit_note_no,
            'credit_note_date' => $creditNote->credit_note_date->format('d/m/Y'),
            'reason' => $creditNote->reason,
            'company' => $invoiceData['company'],
            'brand' => $invoiceData['brand'],
            'amounts' => [
                'taxable_reversal' => (float) $creditNote->taxable_reversal_amount,
                'gst_reversal' => (float) $creditNote->gst_reversal_amount,
                'cgst_reversal' => (float) $creditNote->cgst_reversal,
                'sgst_reversal' => (float) $creditNote->sgst_reversal,
                'total_reversal' => round(
                    (float) $creditNote->taxable_reversal_amount + (float) $creditNote->gst_reversal_amount,
                    2
                ),
                'gst_percentage' => $gstPercentage,
                'cgst_rate' => $gstPercentage / 2,
                'sgst_rate' => $gstPercentage / 2,
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
