<?php

namespace App\Services;

use App\CPU\Helpers;
use App\Models\Campaign;
use App\Models\Seller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class CampaignInvoiceService
{
    public const TYPE_GST = 'gst';
    public const TYPE_NORMAL = 'normal';

    public function invoiceType(Campaign $campaign): string
    {
        return (bool) $campaign->generate_gst_invoice ? self::TYPE_GST : self::TYPE_NORMAL;
    }

    public function canDownload(Campaign $campaign): bool
    {
        if ($campaign->status === 'stopped') {
            return true;
        }

        return $campaign->status === 'completed'
            && $campaign->settlement_status === \App\Services\CampaignSettlementService::SETTLEMENT_SETTLED;
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

        if ($campaign->status === 'stopped') {
            return ['ok' => true, 'message' => '', 'code' => 200];
        }

        if ($campaign->status !== 'completed' || $campaign->settlement_status !== \App\Services\CampaignSettlementService::SETTLEMENT_SETTLED) {
            return [
                'ok' => false,
                'message' => 'Invoice is only available after campaign settlement is complete.',
                'code' => 422,
            ];
        }

        return ['ok' => true, 'message' => '', 'code' => 200];
    }

    public function buildInvoiceData(Campaign $campaign): array
    {
        $campaign->loadMissing('brand');

        $brand = $campaign->brand instanceof Seller
            ? $campaign->brand
            : Seller::find($campaign->brand_id);

        $isGstInvoice = (bool) $campaign->generate_gst_invoice;
        $invoiceType = $isGstInvoice ? self::TYPE_GST : self::TYPE_NORMAL;

        $gstPercentage = (float) Helpers::get_business_settings('campaign_gst_percentage');
        if ($gstPercentage <= 0) {
            $gstPercentage = 18.0;
        }

        $taxableAmount = round((float) ($campaign->total_campaign_budget ?? 0), 2);
        $discountAmount = round((float) ($campaign->discount_amount ?? 0), 2);
        $netTaxableAmount = round($taxableAmount - $discountAmount, 2);
        // GST is calculated on net taxable (after discount)
        $gstAmount = round($netTaxableAmount * $gstPercentage / 100, 2);
        $totalAmount = round((float) ($campaign->compign_budget_with_gst ?? ($netTaxableAmount + $gstAmount)), 2);

        // Determine if intra-state (CGST+SGST) or inter-state (IGST)
        // Compare platform's registered state with brand's billing state (case-insensitive trim)
        $platformState = strtolower(trim((string) (Helpers::get_business_settings('company_state') ?? '')));
        $brandState    = strtolower(trim((string) ($brand->state ?? '')));
        $isIntraState  = $platformState !== '' && $brandState !== '' && $platformState === $brandState;

        $halfGstRate = $gstPercentage / 2;
        $cgstAmount  = $isIntraState ? round($gstAmount / 2, 2) : 0.0;
        $sgstAmount  = $isIntraState ? round($gstAmount - $cgstAmount, 2) : 0.0;
        $igstAmount  = $isIntraState ? 0.0 : $gstAmount;

        $brandName = trim(($brand->f_name ?? '') . ' ' . ($brand->l_name ?? ''));
        if ($brandName === '') {
            $brandName = $brand->username ?? 'Brand';
        }

        $invoiceDate = $campaign->updated_at ?? $campaign->created_at ?? now();
        $prefix = $isGstInvoice ? 'INV-GST-CAM-' : 'INV-CAM-';

        return [
            'invoice_type' => $invoiceType,
            'is_gst_invoice' => $isGstInvoice,
            'invoice_number' => $prefix . str_pad((string) $campaign->id, 6, '0', STR_PAD_LEFT),
            'invoice_date' => $invoiceDate->format('d/m/Y'),
            'campaign' => $campaign,
            'company' => [
                'name' => (string) (Helpers::get_business_settings('company_name') ?? 'Scrap'),
                'email' => (string) (Helpers::get_business_settings('company_email') ?? ''),
                'phone' => (string) (Helpers::get_business_settings('company_phone') ?? ''),
                'address' => (string) (Helpers::get_business_settings('shop_address') ?? ''),
                'gst_number' => (string) (Helpers::get_business_settings('company_gst_number') ?? ''),
            ],
            'brand' => [
                'name' => $brandName,
                'username' => $brand->username ?? '',
                'gst_number' => $brand->gst_number ?? '',
                'gst_status' => $brand->gst_status ?? '',
                'address' => $brand->full_address ?? '',
                'city' => $brand->city ?? '',
                'state' => $brand->state ?? '',
                'phone' => $brand->phone ?? $brand->primary_contact ?? '',
                'email' => $brand->email ?? '',
            ],
            'is_intra_state' => $isIntraState,
            'amounts' => [
                'taxable' => $taxableAmount,
                'discount_amount' => $discountAmount,
                'net_taxable' => $netTaxableAmount,
                'gst_percentage' => $gstPercentage,
                'gst_amount' => $gstAmount,
                'is_intra_state' => $isIntraState,
                'cgst_rate' => $halfGstRate,
                'sgst_rate' => $halfGstRate,
                'cgst_amount' => $cgstAmount,
                'sgst_amount' => $sgstAmount,
                'igst_rate' => $gstPercentage,
                'igst_amount' => $igstAmount,
                'total' => $totalAmount,
            ],
        ];
    }

    public function downloadResponse(Campaign $campaign): SymfonyResponse
    {
        $data = $this->buildInvoiceData($campaign);
        $isGst = $data['is_gst_invoice'];

        $view = $isGst ? 'invoices.campaign-gst-invoice' : 'invoices.campaign-normal-invoice';
        $filename = ($isGst ? 'campaign-gst-invoice-' : 'campaign-invoice-') . $campaign->id . '.html';

        $html = View::make($view, $data)->render();

        return response($html, Response::HTTP_OK, [
            'Content-Type' => 'text/html; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
        ]);
    }
}
