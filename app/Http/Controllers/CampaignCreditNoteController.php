<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Services\CampaignCreditNoteService;
use Illuminate\Http\Request;

class CampaignCreditNoteController extends Controller
{
    public function __construct(
        protected CampaignCreditNoteService $creditNoteService
    ) {
    }

    public function download(Request $request, int $id)
    {
        $campaign = Campaign::find($id);

        if (!$campaign) {
            return redirect()->back()->with('error', translate('Campaign not found'));
        }

        $validation = $this->creditNoteService->validateDownload($campaign);
        if (!$validation['ok']) {
            return redirect()->back()->with('error', translate($validation['message']));
        }

        return $this->creditNoteService->downloadResponse($campaign);
    }
}
