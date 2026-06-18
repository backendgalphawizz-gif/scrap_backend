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

    public function invoiceNumber(Campaign $campaign): string
    {
        $invoiceDate = $campaign->created_at ?? now();

        return 'RXI-' . $invoiceDate->format('Y') . '-' . str_pad((string) $campaign->id, 6, '0', STR_PAD_LEFT);
    }

    public function canDownload(Campaign $campaign): bool
    {
        return $campaign->status !== 'rejected';
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

        if ($campaign->status === 'rejected') {
            return [
                'ok' => false,
                'message' => 'Invoice is not available for rejected campaigns.',
                'code' => 422,
            ];
        }

        return ['ok' => true, 'message' => '', 'code' => 200];
    }

    public function buildInvoiceData(Campaign $campaign): array
    {
        $brand = Seller::find($campaign->brand_id);
        if (!$brand) {
            $brand = new Seller();
        }

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

        $numberOfPost = (int) ($campaign->number_of_post ?? 0);
        $perPostAmount = $numberOfPost > 0
            ? round($taxableAmount / $numberOfPost, 2)
            : 0.0;
        $discountPct = ($taxableAmount > 0 && $discountAmount > 0)
            ? round($discountAmount / $taxableAmount * 100, 2)
            : 0.0;

        $brandName = trim(($brand->f_name ?? '') . ' ' . ($brand->l_name ?? ''));
        if ($brandName === '') {
            $brandName = $brand->username ?? 'Brand';
        }

        $invoiceDate = $campaign->created_at ?? now();

        return [
            'invoice_type' => $invoiceType,
            'is_gst_invoice' => $isGstInvoice,
            'invoice_number' => $this->invoiceNumber($campaign),
            'invoice_date' => $invoiceDate->format('d-M-y'),
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
                'pan_number' => $brand->pan_number ?? '',
                'gst_status' => $brand->gst_status ?? '',
                'address' => $brand->full_address ?? '',
                'city' => $brand->city ?? '',
                'state' => $brand->state ?? '',
                'phone' => $brand->phone ?? $brand->primary_contact ?? '',
                'email' => $brand->email ?? '',
            ],
            'is_intra_state' => $isIntraState,
            'posts' => [
                'per_post_amount' => $perPostAmount,
                'total_posts' => $numberOfPost,
            ],
            'amounts' => [
                'taxable' => $taxableAmount,
                'discount_amount' => $discountAmount,
                'discount_pct' => $discountPct,
                'net_taxable' => $netTaxableAmount,
                'gst_percentage' => $gstPercentage,
                'gst_amount' => $gstAmount,
                'is_intra_state' => $isIntraState,
                'cgst_rate' => $isIntraState ? $halfGstRate : 0.0,
                'sgst_rate' => $isIntraState ? $halfGstRate : 0.0,
                'cgst_amount' => $cgstAmount,
                'sgst_amount' => $sgstAmount,
                'igst_rate' => $isIntraState ? 0.0 : $gstPercentage,
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
