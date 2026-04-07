<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\CommonResource;
use App\Models\CampaignTransaction;
use App\Models\Campaign;
use Illuminate\Support\Str;


class DashboardController extends Controller
{

    public function index(Request $request)
    {
        $user = $request->user();
        $filters = [];
        $query = Campaign::with(['brand']);
        switch ($request->sort) {
            case 'highest_reward':
                $query->orderBy('coins', 'DESC');
                break;

            case 'ending_soon':
                $query->orderBy('end_date', 'ASC');
                break;

            case 'most_participated':
                $query->withCount('campaign_transactions')
                    ->orderBy('campaign_transactions_count', 'DESC');
                break;

            case 'newest':
                $query->orderBy('created_at', 'DESC');
                break;

            default:
                $query->orderBy('id', 'DESC');
                break;
        }

        $gender = $user->gender??'';
        $city = $user->city??'';
        $state = $user->state??'';

        $campaigns = $query
            ->when($gender!='', function($q) use($gender) {
                $q->where('gender', $gender);
            })
            ->when($city!='', function($q) use($city) {
                $q->where('city', $city);
            })
            ->when($state!='', function($q) use($state) {
                $q->where('state', $state);
            })
            ->where(['status' => 'active'])->paginate($request->input('limit', 10));
        return response()->json([
            'status' => true,
            'message' => 'Campaign Lists retrieved successfully',
            'data' => CommonResource::collection($campaigns)
        ]);
    }

    public function show(Request $request, $id)
    {
        $user = $request->user();

        $campaigns = Campaign::with(['brand', 'campaign_transactions' => function($q) use($user) {
            $q->where('user_id', $user->id);
        }])->where(['status' => 'active', 'id' => $id])->orderBy('id', 'DESC')->get();
        $shared_on = CampaignTransaction::where([
            'user_id' => $user->id,
            'campaign_id' => $id
        ])->get()->pluck('shared_on');

        return response()->json([
            'status' => true,
            'message' => 'Campaign Lists retrieved successfully',
            'data' => new CommonResource($campaigns),
            'shared_on' => $shared_on
        ]);
    }

    public function shareCampaign(Request $request, $id)
    {
        $user = $request->user();
        $campaign = Campaign::find($id);

        if (!$campaign || $campaign->status != 'active') {
            return response()->json([
                'status' => false,
                'message' => 'Campaign not found or inactive'
            ], 404);
        }

        $transaction = CampaignTransaction::where([
            'user_id' => $user->id,
            'campaign_id' => $campaign->id,
            'shared_on' => $request->share_on
        ])->first();

        if (!$transaction) {
            $transaction = CampaignTransaction::create([
                'user_id' => $user->id,
                'campaign_id' => $campaign->id,
                'shared_on' => $request->share_on,
                'status' => 'active',
                'earning' => $campaign->coins ?? 0,
                'start_date' => date('Y-m-d'),
                'end_date' => date('Y-m-d', strtotime('+7 days')),
                'unique_code' => $request->unique_code,
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Campaign joined successfully',
        ]);
    }

    public function myCampaigns(Request $request)
    {
        $user = $request->user();
        
        $campaign_ids = CampaignTransaction::where('user_id', $user->id)->orderBy('id', 'DESC')->get()->pluck('campaign_id');

        $campaigns = Campaign::withCount(['feedbacks' => function($q) use($user) {
            $q->where('user_id', $user->id);
        }])->with(['brand', 'campaign_transactions'])->whereIn('id', $campaign_ids)->get();
 
        $total_coins_earned = strval(0);
        $total_campaigns = strval(CampaignTransaction::where('user_id', $user->id)->count());

        return response()->json([
            'status' => true,
            'message' => 'Campaign Lists retrieved successfully',
            'data' => CommonResource::collection($campaigns),
            'total_coins_earned' => $total_coins_earned,
            'total_campaigns' => $total_campaigns
        ]);
    }
}