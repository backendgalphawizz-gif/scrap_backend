<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\CPU\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\CommonResource;
use App\Models\CampaignTransaction;
use App\Models\Campaign;
use App\Models\CoinTransaction;
use App\Models\CoinWallet;
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
            ->withCount(['occupiedTransactions as occupied_slots'])
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
        $ageRange = trim((string) $request->input('age_range', ''));
        $isLocalForVocal = filter_var($request->input('local_for_vocal', false), FILTER_VALIDATE_BOOLEAN);
   
        $filters = [];
        $query = Campaign::with(['brand'])
            ->withCount(['occupiedTransactions as occupied_slots']);
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

        $gender = $user->gender ?? '';
        $city = $user->city ?? '';
        $state = $user->state ?? '';

        $campaigns = $query
            ->when($gender != '' && $gender != 'both', function ($q) use ($gender) {
                $q->where(function ($sub) use ($gender) {
                    $sub->where('gender', $gender)
                        ->orWhere('gender', 'both');
                });
            })
            ->when($isLocalForVocal && $city != '', function ($q) use ($city) {
                $q->where('city', $city);
            })
            ->when(!$isLocalForVocal && $city != '' && $city != 'any', function ($q) use ($city) {
                $q->where(function ($sub) use ($city) {
                    $sub->where('city', $city)
                        ->orWhere('city', 'any');
                });
            })
            ->when($isLocalForVocal && $state != '', function ($q) use ($state) {
                $q->where('state', $state);
            })
            ->when(!$isLocalForVocal && $state != '' && $state != 'any', function ($q) use ($state) {
                $q->where(function ($sub) use ($state) {
                    $sub->where('state', $state)
                        ->orWhere('state', 'any');
                });
            })
            ->when($ageRange !== '' && preg_match('/^\d{1,2}\s*-\s*\d{1,2}$/', $ageRange), function ($q) use ($ageRange) {
                $normalizedAgeRange = preg_replace('/\s+/', '', $ageRange);
                $q->whereRaw('REPLACE(age_range, " ", "") = ?', [$normalizedAgeRange]);
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

        $campaigns = Campaign::with(['brand', 'campaign_transactions' => function ($q) use ($user) {
            $q->where('user_id', $user->id);
        }])
            ->withCount(['occupiedTransactions as occupied_slots'])
            ->where('id', $id)
            ->orderBy('id', 'DESC')
            ->get();

        $userTransactions = CampaignTransaction::where([
            'user_id' => $user->id,
            'campaign_id' => $id
        ])->get();

        $shared_on = $userTransactions->pluck('shared_on');
        $share_count = $userTransactions->count();

        return response()->json([
            'status' => true,
            'message' => 'Campaign Lists retrieved successfully',
            'data' => new CommonResource($campaigns),
            'shared_on' => $shared_on,
            'share_count' => $share_count
        ]);
    }

    public function shareCampaign(Request $request, $id)
    {
        $user = $request->user();
        $validator = Validator::make($request->all(), [
            'share_on' => 'required|in:instagram,facebook',
            'unique_code' => 'required|string|max:100',
            'post_url' => 'nullable|url|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => Helpers::single_error_processor($validator),
            ], 422);
        }

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

        if ($transaction) {
            if (in_array($transaction->status, [
                CampaignTransaction::STATUS_COMPLETED,
                CampaignTransaction::STATUS_DELETED,
                CampaignTransaction::STATUS_REJECTED,
            ], true)) {
                return response()->json([
                    'status' => false,
                    'message' => 'This participation can no longer be updated.',
                ], 422);
            }

            $transaction->unique_code = $request->unique_code;
            if ($request->filled('post_url')) {
                $transaction->post_url = $request->post_url;
            }
            if ($transaction->status === CampaignTransaction::STATUS_FLAGGED) {
                $transaction->violation_reason = null;
            }
            $transaction->save();

            $this->createPendingCampaignReward($user->id, $campaign, $transaction);

            return response()->json([
                'status' => true,
                'message' => 'Campaign post details updated successfully.',
            ]);
        }

        if (Carbon::parse($campaign->end_date)->endOfDay()->isPast()) {
            return response()->json([
                'status' => false,
                'message' => 'This campaign has ended; you can no longer participate.',
            ], 422);
        }

        if ($this->isCampaignSlotFull($campaign)) {
            return response()->json([
                'status' => false,
                'message' => 'All campaign slots are filled. Please try another campaign.',
                'available_slots' => (string) $campaign->available_slots,
            ], 422);
        }

        $transaction = CampaignTransaction::create([
            'user_id' => $user->id,
            'campaign_id' => $campaign->id,
            'shared_on' => $request->share_on,
            'status' => CampaignTransaction::STATUS_PENDING,
            'earning' => $campaign->coins ?? 0,
            'start_date' => date('Y-m-d'),
            'end_date' => date('Y-m-d', strtotime('+7 days')),
            'unique_code' => $request->unique_code,
            'post_url' => $request->post_url,
        ]);

        $this->createPendingCampaignReward($user->id, $campaign, $transaction);

        return response()->json([
            'status' => true,
            'message' => 'Campaign joined successfully. Reward added to wallet as pending.',
        ]);
    }

    private function createPendingCampaignReward($userId, Campaign $campaign, CampaignTransaction $transaction): void
    {
        $wallet = CoinWallet::firstOrCreate(
            ['user_id' => $userId],
            ['balance' => 0]
        );

        $existingReward = CoinTransaction::where('coin_wallet_id', $wallet->id)
            ->where('campaign_id', $campaign->id)
            ->where('transaction_type', 'campaign_reward')
            ->where('type', 'credit')
            ->first();

        if ($existingReward) {
            return;
        }

        $coins = $campaign->coins ?? 0;

        CoinTransaction::create([
            'coin_wallet_id'   => $wallet->id,
            'transaction_id'   => 'CAMP-PENDING-' . $transaction->id,
            'campaign_id'      => $campaign->id,
            'coin'             => $coins,
            'type'             => 'credit',
            'status'           => 'pending',
            'amount'           => 0,
            'tds'              => 0,
            'convertion_rate'  => 0,
            'transaction_type' => 'campaign_reward',
            'description'      => 'Pending campaign reward for ' . ($campaign->title ?? 'campaign'),
        ]);
    }

    public function updateScrappedPosts(Request $request)
    {
        $transactions_id = $request->input('transactions_id');
        $post_url = $request->input('post_url');
        $scrapped_posts = CampaignTransaction::where('id', $transactions_id)
            ->where('id', $transactions_id)->first();
        if ($scrapped_posts) {
            CampaignTransaction::where('id', $transactions_id)->update(['post_url' => $post_url]);
            return response()->json([
                'status' => true,
                'message' => 'Scrapped post updated successfully',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Scrapped post not found',
            ], 404);
        }
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

        $campaigns = Campaign::withCount([
            'feedbacks' => function ($q) use ($user) {
                $q->where('user_id', $user->id);
            },
            'occupiedTransactions as occupied_slots'
        ])->with(['brand', 'campaign_transactions'])->whereIn('id', $campaign_ids)->get();

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

    private function isCampaignSlotFull(Campaign $campaign): bool
    {
        $requiredSlots = (int) $campaign->total_user_required;

        if ($requiredSlots <= 0) {
            return false;
        }

        return $campaign->occupiedTransactions()->count() >= $requiredSlots;
    }
}
