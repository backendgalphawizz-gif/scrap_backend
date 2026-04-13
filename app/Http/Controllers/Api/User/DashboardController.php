<?php

namespace App\Http\Controllers\Api\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\CommonResource;
use App\Models\CampaignTransaction;
use App\Models\Campaign;
use App\Models\UserCampaignSkip;
use Illuminate\Support\Carbon;


class DashboardController extends Controller
{

    public function localForVocal(Request $request)
    {
        $user = $request->user();
        $city = trim((string) ($user->city ?? ''));
        $state = trim((string) ($user->state ?? ''));

        $campaigns = Campaign::with(['brand'])
            ->withCount('campaign_transactions')
            ->when($city !== '', function ($q) use ($city) {
                $q->where('city', $city);
            })
            ->when($state !== '', function ($q) use ($state) {
                $q->where('state', $state);
            })
            ->where('status', 'active')
            ->whereNotIn('id', function ($sub) use ($user) {
                $sub->select('campaign_id')
                    ->from('user_campaign_skips')
                    ->where('user_id', $user->id);
            })
            ->orderBy('campaign_transactions_count', 'DESC')
            ->orderBy('id', 'DESC')
            ->limit(5)
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Local for vocal campaigns retrieved successfully',
            'filters' => [
                'city' => $city,
                'state' => $state,
            ],
            'data' => CommonResource::collection($campaigns),
        ]);
    }

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
            ->where(['status' => 'active'])
            ->whereNotIn('id', function ($sub) use ($user) {
                $sub->select('campaign_id')
                    ->from('user_campaign_skips')
                    ->where('user_id', $user->id);
            })
            ->paginate($request->input('limit', 10));
        return response()->json([
            'status' => true,
            'message' => 'Campaign Lists retrieved successfully',
            'data' => CommonResource::collection($campaigns)
        ]);
    }

    public function show(Request $request, $id)
    {
        $user = $request->user();

        if (UserCampaignSkip::where('user_id', $user->id)->where('campaign_id', $id)->exists()) {
            return response()->json([
                'status' => false,
                'message' => 'Campaign not found or inactive',
            ], 404);
        }

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
            if (Carbon::parse($campaign->end_date)->endOfDay()->isPast()) {
                return response()->json([
                    'status' => false,
                    'message' => 'This campaign has ended; you can no longer participate.',
                ], 422);
            }

            $transaction = CampaignTransaction::create([
                'user_id' => $user->id,
                'campaign_id' => $campaign->id,
                'shared_on' => $request->share_on,
                'status' => 'pending',
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

    public function skipCampaign(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'campaign_id' => 'required|integer|exists:campaigns,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $user = $request->user();
        $campaignId = (int) $request->input('campaign_id');

        UserCampaignSkip::firstOrCreate([
            'user_id' => $user->id,
            'campaign_id' => $campaignId,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Campaign skipped successfully',
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