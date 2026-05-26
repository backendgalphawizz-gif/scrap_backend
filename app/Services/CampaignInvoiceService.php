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
        return $campaign->status === 'completed';
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

        if ($campaign->status !== 'completed') {
            return [
                'ok' => false,
                'message' => 'Invoice is only available for completed campaigns.',
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
        $gstAmount = round($taxableAmount * $gstPercentage / 100, 2);
        $totalAmount = round((float) ($campaign->compign_budget_with_gst ?? ($taxableAmount + $gstAmount)), 2);
        $halfGstRate = $gstPercentage / 2;
        $cgstAmount = round($gstAmount / 2, 2);
        $sgstAmount = round($gstAmount - $cgstAmount, 2);

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
            'amounts' => [
                'taxable' => $taxableAmount,
                'gst_percentage' => $gstPercentage,
                'gst_amount' => $gstAmount,
                'cgst_rate' => $halfGstRate,
                'sgst_rate' => $halfGstRate,
                'cgst_amount' => $cgstAmount,
                'sgst_amount' => $sgstAmount,
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
