<?php

namespace App\Http\Controllers\Api\User;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Models\UserCampaignActivityLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserCampaignActivityLogController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'campaigns_id' => 'nullable|integer|required_without:campaign_id|exists:campaigns,id',
            'campaign_id' => 'nullable|integer|required_without:campaigns_id|exists:campaigns,id',
            'name' => 'required|string|max:150',
            'data' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => Helpers::single_error_processor($validator),
            ], 422);
        }

        $campaignId = $request->input('campaigns_id', $request->input('campaign_id'));

        $log = UserCampaignActivityLog::create([
            'campaigns_id' => (int) $campaignId,
            'user_id' => $user->id,
            'name' => trim((string) $request->input('name')),
            'data' => $request->input('data', []),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User campaign activity logged successfully',
            'data' => $log,
        ]);
    }
}
