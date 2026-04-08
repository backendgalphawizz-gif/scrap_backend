<?php

namespace App\Http\Controllers\Api\Seller;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;

use App\Models\Seller;
use App\Models\Campaign;
use App\Models\Feedback;
use App\Models\SellerWallet;
use App\Models\Notification;
use App\Models\CampaignTransaction;

use Illuminate\Http\Request;
use function App\CPU\translate;
use App\Http\Resources\CommonResource;



class SellerDashboardController extends Controller
{
    public function index(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
            // $product_ids = Product::where(['user_id' => $seller['id'], 'added_by' => 'seller'])->pluck('id')->toArray();

            $shop = Seller::find($seller['id']);

        } else {
            return response()->json([
                'status' => false,
                'message' => translate('Your existing session token does not authorize you any more'),
                'data' => []
            ], 401);
        }

        $response = [
            'status' => true,
            'message' => 'Seller profile',
            'data' => [new CommonResource($shop)]
        ];

        return response()->json($response, 200);
    }

    public function statistics(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];

            $userId = $seller['id'];

            $walletAmount = SellerWallet::where('seller_id', $userId)->value('wallet_amount');

            $rating = Feedback::where('brand_id', $userId)->avg('ratings');

            $totalCampaigns = Campaign::where('brand_id', $userId)->count();

            $liveCampaigns = Campaign::where('brand_id', $userId)->where('status', 'live')->count();

            $participants = CampaignTransaction::
                whereHas('campaign', function ($q) use ($userId) {
                    $q->where('brand_id', $userId);
                })
                ->count();

            $violated = Campaign::where('brand_id', $userId)->where('status', 'violated')->count();

            $campaignList = Campaign::where('brand_id', $userId)->select('id', 'title', 'status')->get();

            $engagement = 0;

            $avgFeedback = Feedback::where('brand_id', $userId)->avg('ratings');

            $costPerClick = 0; // Campaign::avg('cpc');

            $budget = Campaign::where('brand_id', $userId)->sum('total_campaign_budget');

            $chartData = Campaign::where('brand_id', $userId)->selectRaw('DATE(created_at) as date, count(*) as value')
                ->groupBy('date')
                ->limit(7)
                ->get();

            return response()->json(
                [
                    "status" => true,
                    "data" => new CommonResource([
                        'wallet_amount' => $walletAmount,
                        'rating' => round($rating, 1),
                        'statistics' => [
                            'total_campaigns' => $totalCampaigns,
                            'live_campaigns' => $liveCampaigns,
                            'total_participants' => $participants,
                            'violated_campaigns' => $violated
                        ],
                        'campaign_stats' => [
                            'engagement' => $engagement,
                            'avg_feedback' => $avgFeedback,
                            'cost_per_click' => $costPerClick,
                            'budget' => $budget
                        ],
                        'campaign_list' => $campaignList,
                        'report_chart' => $chartData
                    ])
                ]
            );
        }

    }

    public function getCampaignWiseChartData(Request $request, $campaignId)
    {

        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
            $userId = $seller['id'];

            $campaignList = []; // Campaign::where('brand_id', $userId)->where('id', $campaignId)->select('id','title' ,'status')->get();

            $chartData = CampaignTransaction::whereHas('campaign', function ($q) use ($userId, $campaignId) {
                $q->where('brand_id', $userId)->where('id', $campaignId);
            })
                ->selectRaw('DATE(created_at) as date, count(*) as value')
                ->groupBy('date')
                ->get();

            $engagement = CampaignTransaction::whereHas('campaign', function ($q) use ($userId, $campaignId) {
                $q->where('brand_id', $userId)->where('id', $campaignId);
            })->count();

            $avgFeedback = Feedback::whereHas('campaign', function ($q) use ($campaignId) {
                $q->where('id', $campaignId);
            })->avg('ratings');

            $costPerClick = Campaign::where('id', $campaignId)->value('daily_budget_cap');

            $budget = Campaign::where('id', $campaignId)->value('total_campaign_budget');

            return response()->json([
                "status" => true,
                "message" => "Statistics Data",
                "data" => new CommonResource([
                    'campaign_list' => $campaignList,
                    'engagement' => $engagement,
                    'avg_feedback' => round($avgFeedback, 1),
                    'cost_per_click' => $costPerClick,
                    'budget' => $budget,
                    'report_chart' => $chartData
                ])
            ], 200);

        } else {
            return response()->json([
                'status' => false,
                'message' => translate('Your existing session token does not authorize you any more'),
                'data' => []
            ], 401);
        }

    }

    public function update(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];

            $shop = Seller::find($seller['id']);

            // website_url
            // visibility_status
            $shop->update($request->only(['f_name', 'l_name', 'username', 'phone', 'email', 'city', 'state', 'instagram_username', 'facebook_username', 'website_url', 'visibility_status']));

            $shop->category_id = $request->category_id ?: null;
            $shop->sub_category_id = $request->sub_category_id ?: null;
            $shop->gst_number = $request->gst_number ?? '';
            if ($request->has('gst_number')) {
                $shop->gst_status = $request->filled('gst_number') ? 'Submitted' : 'Not Submitted';
            }
            $shop->business_registeration_type = $request->business_registeration_type ?? 'Proprietor';
            $shop->pan_number = $request->pan_number ?? '';
            if ($request->has('pan_number') || $request->hasFile('pan_image')) {
                $shop->pan_status = 'Submitted';
            }
            if ($request->hasFile('pan_image')) {
                $shop->pan_image = ImageManager::upload('profile/', 'png', $request->file('pan_image'), $shop->pan_image);
            }
            // $shop->pan_image = $request->pan_image ?? '';
            $shop->primary_contact = $request->primary_contact ?? '';
            $shop->alternate_contact = $request->alternate_contact ?? '';
            $shop->full_address = $request->full_address ?? '';
            $shop->google_map_link = $request->google_map_link ?? '';
            $shop->website_link = $request->website_link ?? '';
            $shop->save();

            if ($request->hasFile('image')) {
                $shop->image = ImageManager::upload('profile/', 'png', $request->file('image'), $shop->image);
                $shop->save();
            }

            Helpers::systemActivity('profile_updated', $shop, 'updated', 'Brand Profile updated successfull', $shop);

        } else {
            return response()->json([
                'status' => false,
                'message' => translate('Your existing session token does not authorize you any more'),
                'data' => []
            ], 401);
        }

        $response = [
            'status' => true,
            'message' => 'Seller profile updated successfully',
            'data' => [new CommonResource($shop)]
        ];

        return response()->json($response, 200);
    }

    public function updateSocials(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);
        if ($data['success'] == 1) {
            $seller = $data['data'];
            $shop = Seller::find($seller['id']);
        }
        if ($request->has('instagram_username') && $shop->instagram_status !== 'Verified') {
            $shop->instagram_username = $request->instagram_username;
            $shop->instagram_status = 'Submitted';
        }
        if ($request->has('facebook_username') && $shop->facebook_status !== 'Verified') {
            $shop->facebook_username = $request->facebook_username;
            $shop->facebook_status = 'Submitted';
        }
        $shop->save();
        return response()->json([
            'status' => true,
            'message' => 'Seller socials updated successfully',
            'data' => [new CommonResource($shop)]
        ], 200);
    }

    public function updateKyc(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] != 1) {
            return response()->json([
                'status' => false,
                'message' => translate('Your existing session token does not authorize you any more'),
                'data' => [],
            ], 401);
        }

        $seller = $data['data'];
        $shop = Seller::find($seller['id']);

        if ($request->has('pan_number') && $shop->pan_status !== 'Verified') {
            $shop->pan_number = $request->pan_number;
            $shop->pan_status = 'Submitted';
            if ($request->hasFile('pan_image')) {
                $shop->pan_image = ImageManager::upload('profile/', 'png', $request->file('pan_image'), $shop->pan_image);
            }
        }

        if ($request->has('gst_number') && $shop->gst_status !== 'Verified') {
            $shop->gst_number = $request->gst_number;
            $shop->gst_status = $request->filled('gst_number') ? 'Submitted' : 'Not Submitted';
        }

        $kycDirty = $shop->isDirty();
        if ($kycDirty) {
            $shop->save();
            Helpers::systemActivity('kyc_updated', $shop, 'updated', 'Brand KYC updated successfully', $shop);
        }

        return response()->json([
            'status' => true,
            'message' => $kycDirty
                ? 'Brand KYC updated successfully'
                : 'No KYC fields were updated (verified items cannot be changed).',
            'data' => new CommonResource($shop),
        ], 200);
    }

    public function createCampaign(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
            // Logic to create campaign

            $maxPerWindow = (int) Helpers::get_business_settings('brand_max_campaigns_per_timeframe');
            $windowHours = (int) Helpers::get_business_settings('brand_campaign_creation_timeframe_hours');
            if ($maxPerWindow > 0 && $windowHours > 0) {
                $recentCount = Campaign::where('brand_id', $seller['id'])
                    ->where('created_at', '>=', now()->subHours($windowHours))
                    ->count();
                if ($recentCount >= $maxPerWindow) {
                    return response()->json([
                        'status' => false,
                        'message' => translate('You have reached the maximum number of campaigns allowed for your brand in the current period. Please try again later.'),
                        'data' => [],
                        'campaign_rate_limit' => [
                            'max_campaigns_per_timeframe' => $maxPerWindow,
                            'timeframe_hours' => $windowHours,
                            'campaigns_created_in_window' => $recentCount,
                        ],
                    ], 200);
                }
            }

            $sellerWallet = Helpers::get_seller_wallet($seller['id']);

            if ($request->total_campaign_budget > $sellerWallet->wallet_amount) {
                return response()->json([
                    'status' => false,
                    'message' => 'Insufficient fund. Please recharge wallet.',
                    'data' => [],
                    'balance_sufficient' => false,
                    'current_balance' => $sellerWallet->wallet_amount,
                    'balance_required' => $request->total_campaign_budget
                ], 200);
            }

            $campaign = new Campaign;
            if ($request->hasFile('thumbnail')) {
                $campaign->thumbnail = ImageManager::upload('profile/', 'png', $request->file('thumbnail'));
            }
            if ($request->file('images')) {
                $product_images = [];
                foreach ($request->file('images') as $img) {
                    try {
                        //code...
                        $image_name = ImageManager::upload('profile/', 'png', $img);
                        $product_images[] = $image_name;
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
                }
                $campaign->images = implode(',', $product_images);
            }
            $campaign->brand_id = $seller['id'];
            $campaign->title = $request->caption;
            $campaign->post_type = $request->post_type ?? 'post';
            $campaign->descriptions = $request->caption;
            $campaign->tags = $request->hashtags;
            $campaign->share_on = $request->social_media;
            $campaign->start_date = $request->start_date;
            $campaign->end_date = $request->end_date;
            $campaign->gender = $request->gender;
            $campaign->state = $request->state;
            $campaign->city = $request->city;
            $campaign->guidelines = implode('|', $request->guidelines);
            $campaign->coins = $request->reward_per_user;

            $campaign->total_user_required = $request->total_user_required;
            $campaign->reward_per_user = $request->reward_per_user;
            // $campaign->reward_per_post = $request->reward_per_post;
            $campaign->number_of_post = $request->number_of_post;
            $campaign->daily_budget_cap = $request->daily_budget_cap;
            $campaign->total_campaign_budget = $request->total_campaign_budget;
            $campaign->age_range = $request->age_range;
            $campaign->save();

            Helpers::systemActivity('campaign', $seller, 'created', 'Campaign created successfully', $campaign);

        } else {
            return response()->json([
                'status' => false,
                'message' => translate('Your existing session token does not authorize you any more'),
                'data' => []
            ], 401);
        }

        return response()->json([
            'status' => true,
            'message' => 'Campaign created successfully',
            'data' => []
        ], 200);
    }

    public function detailCampaign(Request $request, $id)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
            // Logic to create campaign

            $campaign = Campaign::with(['brand', 'feedbacks.user', 'campaign_transactions.user'])->find($id);

            return response()->json([
                'status' => true,
                'message' => 'Campaign detail',
                'data' => [new CommonResource($campaign)]
            ], 200);

        } else {
            return response()->json([
                'status' => false,
                'message' => translate('Your existing session token does not authorize you any more'),
                'data' => []
            ], 401);
        }

        return response()->json([
            'status' => true,
            'message' => 'Campaign updated successfully',
            'data' => []
        ], 200);
    }

    public function updateCampaign(Request $request, $id)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
            // Logic to create campaign

            $campaign = Campaign::find($id);
            if ($request->hasFile('thumbnail')) {
                try {
                    //code...
                    $campaign->thumbnail = ImageManager::upload('profile/', 'png', $request->file('thumbnail'));
                } catch (\Throwable $th) {
                    //throw $th;
                    return response()->json([
                        'status' => true,
                        'message' => $th->getMessage()
                    ]);
                }
            }
            if ($request->file('images')) {
                $product_images = [];
                foreach ($request->file('images') as $img) {
                    try {
                        //code...
                        // $campaign->thumbnail = ImageManager::upload('profile/', 'png', $request->file('thumbnail'));
                        $image_name = ImageManager::upload('profile/', 'png', $img);
                        $product_images[] = $image_name;
                    } catch (\Throwable $th) {
                        //throw $th;
                        return response()->json([
                            'status' => true,
                            'message' => $th->getMessage()
                        ]);
                    }
                }
                $campaign->images = implode(',', $product_images);
            }
            $campaign->post_type = $request->post_type ?? 'post';
            $campaign->brand_id = $seller['id'];
            $campaign->title = $request->caption;
            $campaign->descriptions = $request->caption;
            $campaign->tags = $request->hashtags;
            $campaign->share_on = $request->social_media;
            $campaign->start_date = $request->start_date;
            $campaign->end_date = $request->end_date;
            $campaign->gender = $request->gender;
            $campaign->state = $request->state;
            $campaign->city = $request->city;
            $campaign->guidelines = implode('|', $request->guidelines);
            $campaign->coins = $request->reward_per_user;

            $campaign->total_user_required = $request->total_user_required;
            $campaign->reward_per_user = $request->reward_per_user;
            // $campaign->reward_per_post = $request->reward_per_post;
            $campaign->number_of_post = $request->number_of_post;
            $campaign->daily_budget_cap = $request->daily_budget_cap;
            $campaign->total_campaign_budget = $request->total_campaign_budget;
            $campaign->age_range = $request->age_range;
            $campaign->save();

            Helpers::systemActivity('campaign', $seller, 'updated', 'Campaign updated successfully', $campaign);


        } else {
            return response()->json([
                'status' => false,
                'message' => translate('Your existing session token does not authorize you any more'),
                'data' => []
            ], 401);
        }

        return response()->json([
            'status' => true,
            'message' => 'Campaign updated successfully',
            'data' => []
        ], 200);
    }

    public function updateCampaignStatus(Request $request, $id)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
            // Logic to create campaign

            $campaign = Campaign::find($id);

            $campaign->status = $request->status;
            $campaign->save();

            Helpers::systemActivity('campaign', $seller, 'updated', 'Campaign status updated to ' . ($request->status), $campaign);

        } else {
            return response()->json([
                'status' => false,
                'message' => translate('Your existing session token does not authorize you any more'),
                'data' => []
            ], 401);
        }

        return response()->json([
            'status' => true,
            'message' => 'Campaign updated successfully',
            'data' => []
        ], 200);
    }

    public function listCampaign(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
            $campaigns = Campaign::where('brand_id', $seller['id'])
                ->when($request->has('status'), function ($query) use ($request) {
                    // pending, active, completed
                    $query->where('status', $request->input('status'));
                    // if ($request->status == 'active') {
                    //     $query->whereDate('start_date', '<=', Carbon::now())->whereDate('end_date', '>=', Carbon::now());
                    // } elseif ($request->status == 'upcoming') {
                    //     $query->whereDate('start_date', '>', Carbon::now());
                    // } elseif ($request->status == 'completed') {
                    //     $query->whereDate('end_date', '<', Carbon::now());
                    // }
                })
                ->orderBy('id', 'DESC')
                ->get();
            return response()->json([
                'status' => true,
                'message' => 'Campaign list retrieved successfully',
                'data' => CommonResource::collection($campaigns)
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => translate('Your existing session token does not authorize you any more'),
                'data' => []
            ], 401);
        }
    }

    public function deleteCampaign(Request $request, $id)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
            $campaign = Campaign::where('id', $id)->where('brand_id', $seller['id'])->first();
            if ($campaign) {
                Helpers::systemActivity('campaign', $seller, 'deleted', 'Campaign deleted ', $campaign);
                $campaign->delete();


                return response()->json([
                    'status' => true,
                    'message' => 'Campaign deleted successfully',
                    'data' => []
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Campaign not found or you do not have permission to delete this campaign',
                    'data' => []
                ], 404);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => translate('Your existing session token does not authorize you any more'),
                'data' => []
            ], 401);
        }
    }

    public function deleteAccount(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
            // Logic to create campaign

            $campaign = Seller::find($seller['id']);
            $campaign->delete();

            Helpers::systemActivity('brand', $seller, 'deleted', 'Account deleted by brand user', $seller);

            return response()->json([
                'status' => true,
                'message' => "Account deleted successfully",
                'data' => []
            ], 200);

        } else {
            return response()->json([
                'status' => false,
                'message' => translate('Your existing session token does not authorize you any more'),
                'data' => []
            ], 401);
        }
    }

    public function notifications(Request $request)
    {

        $limit = $request->limit ?? 25;
        $notifications = Notification::where(['status' => 1, 'type' => 'brand'])->orderBy('id', 'DESC')->paginate($limit);
        return response()->json([
            'status' => true,
            'message' => 'Notification retrieved successfully',
            'data' => CommonResource::collection($notifications)
        ]);
    }

    public function reportViolation(Request $request, $id)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] != 1) {
            return response()->json([
                'status' => false,
                'message' => translate('Your existing session token does not authorize you any more'),
                'data' => []
            ], 401);
        }

        $seller = $data['data'];

        $transaction = CampaignTransaction::with('campaign')->find($id);

        if (!$transaction) {
            return response()->json([
                'status' => false,
                'message' => 'Transaction not found',
            ], 404);
        }

        $campaign = Campaign::where('id', $transaction->campaign_id)
            ->where('brand_id', $seller['id'])
            ->first();

        if (!$campaign) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized: this transaction does not belong to your campaign',
            ], 403);
        }

        if (!in_array($transaction->status, ['active'])) {
            return response()->json([
                'status' => false,
                'message' => 'Violation can only be reported on active transactions',
            ], 422);
        }

        $transaction->status = 'flagged';
        $transaction->violation_reason = $request->input('reason');
        $transaction->save();

        return response()->json([
            'status' => true,
            'message' => 'Violation reported successfully. It will be reviewed by admin.',
        ]);
    }

}
