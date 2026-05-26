<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Services\CampaignInvoiceService;
use Illuminate\Http\Request;

class CampaignInvoiceController extends Controller
{
    public function __construct(
        protected CampaignInvoiceService $invoiceService
    ) {
    }

    public function download(Request $request, int $id)
    {
        $campaign = Campaign::find($id);

        if (!$campaign) {
            return redirect()->back()->with('error', translate('Campaign not found'));
        }

        $validation = $this->invoiceService->validateDownload($campaign);
        if (!$validation['ok']) {
            return redirect()->back()->with('error', translate($validation['message']));
        }

        return $this->invoiceService->downloadResponse($campaign);
    }
}
