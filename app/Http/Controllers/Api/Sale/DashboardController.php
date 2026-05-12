<?php

namespace App\Http\Controllers\Api\Sale;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Seller;
use App\Models\SaleWalletTransaction;
use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Models\Campaign;
use App\Models\Notification;
use App\Models\PaymentSplit;
use App\Models\SaleCommissionLedger;
use App\Models\BrandCategory;
use App\Http\Resources\CommonResource;
use Illuminate\Support\Str;
use function App\CPU\translate;
use Hash;

class DashboardController extends Controller
{
   
    public function index(Request $request) {
        // check city test
        $data = Helpers::get_sale_by_token($request);
        if ($data['success'] == 1) {
            $seller = $data['data'];
            // $product_ids = Product::where(['user_id' => $seller['id'], 'added_by' => 'seller'])->pluck('id')->toArray();

            $shop = Sale::with(['brands', 'campaigns'])->find($seller['id']);
            // $shop->brands;
            
        } else {
            return response()->json([
                'status' => false,
                'message' => ('Your existing session token does not authorize you any more'),
                'data' => []
            ], 401);
        }

        $response = [
            'status' => true,
            'message' => 'Sale profile',
            'data' => [new CommonResource($shop)]
        ];

        return response()->json($response, 200);
    }
    
    public function update(Request $request) {
        $data = Helpers::get_sale_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
            $shop = Sale::find($seller['id']);
            
            if ($request->has('name')) {
                $shop->name = $request->name;
            }
            if ($request->has('password')) {
                $shop->password = Hash::make($request->password);
            }
            
            if ($request->hasFile('image')) {
                $shop->image = ImageManager::upload('profile/', 'png', $request->file('image'));
            }
            
            if ($request->has('address')) {
                $shop->address = $request->address;
            }
            
            $shop->save();

            Helpers::systemActivity('profile_updated', $shop, 'updated', 'Sale User Profile updated successfully', $shop);
            
            return response()->json([
                'status' => true,
                'message' => 'Sale profile updated successfully',
                'data' => [new CommonResource($shop)]
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Your existing session token does not authorize you any more',
                'data' => []
            ], 401);
        }
    }

    public function updateKyc(Request $request)
    {
        $data = Helpers::get_sale_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
            $shop = Sale::find($seller['id']);
            if ($request->has('pan_number') && $shop->pan_status !== 'Verified') {
                // Verify PAN with third-party API before accepting it
                $panVerification = $this->verifyPanNumber($request->pan_number);

                if ($panVerification['error'] !== null) {
                    return response()->json([
                        'status'  => false,
                        'message' => $panVerification['error'],
                    ], 502);
                }

                if (!$panVerification['valid']) {
                    return response()->json([
                        'status'  => false,
                        'message' => 'PAN number is invalid. Please enter a valid PAN.',
                        'data'    => [
                            'pan_number' => $request->pan_number,
                            'pan_status' => $panVerification['status'],
                        ],
                    ], 422);
                }

                $shop->pan_number = $request->pan_number;
                $shop->pan_status = 'Submitted';
                if ($request->hasFile('pan_image')) {
                    $shop->pan_image = ImageManager::upload('profile/', 'png', $request->file('pan_image'));
                }
            }

            if (($request->has('bank_name') || $request->has('ifsc_code')) && $shop->bank_status !== 'Verified') {
                $shop->bank_detail = json_encode([
                    'bank_name' => $request->bank_name,
                    'ifsc_code' => $request->ifsc_code,
                    'account_number' => $request->account_number,
                    'branch_name' => $request->branch_name
                ]);

                $shop->bank_status = $request->bank_name != '' ? 'Submitted' : 'Not Submitted';
            }

            $kycDirty = $shop->isDirty();
            if ($kycDirty) {
                $shop->save();
                Helpers::systemActivity('profile_updated', $shop, 'updated', 'Sale Profile KYC updated successfully', $shop);
            }

            return response()->json([
                'status' => true,
                'message' => $kycDirty
                    ? 'Sale KYC updated successfully'
                    : 'No KYC fields were updated (verified items cannot be changed).',
                'data' => new CommonResource($shop)
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => ('Your existing session token does not authorize you any more'),
                'data' => []
            ], 401);
        }

    }

    public function createCampaign(Request $request) {
        $data = Helpers::get_sale_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
            $salesId = $seller['id'];

            $brand = Seller::find($request->brand_id);
            if (!$brand) {
                return response()->json([
                    'status' => false,
                    'message' => 'Brand not found.',
                    'data' => [],
                ], 404);
            }

            // if ($brand->pan_status !== 'Verified') {
            //     return response()->json([
            //         'status' => false,
            //         'message' => translate('Please complete KYC verification before creating a campaign.'),
            //         'data' => [],
            //     ], 200);
            // }

            $maxPerWindow = (int) Helpers::get_business_settings('brand_max_campaigns_per_timeframe');
            $windowHours = (int) Helpers::get_business_settings('brand_campaign_creation_timeframe_hours');
            if ($maxPerWindow > 0 && $windowHours > 0) {
                $recentCount = Campaign::where('brand_id', $request->brand_id)
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


            $sellerWallet = Helpers::get_seller_wallet($request->brand_id);

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

            $category = BrandCategory::where('id', $request->category_id)
                ->where(function ($query) {
                    $query->whereNull('parent_id')->orWhere('parent_id', 0);
                })
                ->first();
            if (!$category) {
                return response()->json([
                    'status' => false,
                    'message' => 'Valid category is required.',
                    'data' => [],
                ], 422);
            }

            $subCategoryId = $request->sub_category_id ?: null;
            if ($subCategoryId) {
                $subCategory = BrandCategory::where('id', $subCategoryId)
                    ->where('parent_id', $category->id)
                    ->first();
                if (!$subCategory) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Selected sub category is invalid for selected category.',
                        'data' => [],
                    ], 422);
                }
            }


            // Logic to create campaign

            $campaign = new Campaign;
            if($request->hasFile('thumbnail')) {
                $campaign->thumbnail = ImageManager::upload('profile/', 'png', $request->file('thumbnail'));
            }
            if ($request->file('images')) {
                $product_images = [];
                foreach ($request->file('images') as $img) {
                    $image_name = ImageManager::upload('profile/', 'png', $img);
                    $product_images[] = $image_name;
                }
                $campaign->images = json_encode($product_images);
            } 
            
              
            $paymentSplit = PaymentSplit::first();
            $gst_percentage = (int) Helpers::get_business_settings('campaign_gst_percentage');
            $total_campaign_budget = $request->total_campaign_budget;
            $compign_budget_with_gst = $total_campaign_budget + ($total_campaign_budget * $gst_percentage / 100);

            $campaign->brand_id = $request->brand_id;
            $caption = (string) ($request->caption ?? '');
            $campaign->title = mb_substr($caption, 0, 20, 'UTF-8');
            $campaign->post_type = $request->post_type ?? 'post';
            $campaign->descriptions = $request->caption;
            $campaign->tags = $request->hashtags;
            $campaign->share_on = $request->social_media;
            $campaign->start_date = $request->start_date;
            $campaign->end_date = $request->end_date;
            $campaign->gender = $request->gender;
            $campaign->state = $request->state;
            $campaign->city = is_array($request->city)
                ? implode(',', array_filter($request->city))
                : ($request->city ?? '');
            $campaign->category_id = $category->id;
            $campaign->sub_category_id = $subCategoryId;
            $campaign_guideline = Helpers::get_business_settings('campaign_guideline');
            $campaign->guidelines = $campaign_guideline ?? '';
            //$campaign->coins = $request->reward_per_user;

            $campaign->total_user_required = $request->total_user_required;
            $campaign->reward_per_user = $request->reward_per_user;
            // $campaign->reward_per_post = $request->reward_per_post;
            $campaign->number_of_post = $request->number_of_post;
            $campaign->daily_budget_cap = $request->daily_budget_cap;
            $campaign->total_campaign_budget = $request->total_campaign_budget;
            $campaign->age_range = $request->age_range;
            $campaign->admin_percentage = $paymentSplit->admin_percentage;
            $campaign->user_percentage = $paymentSplit->user_percentage;
            $campaign->sales_percentage = $paymentSplit->sales_percentage;
            $campaign->sales_referal_code = $data['referral_code'] ?? null;
            $campaign->compign_budget_with_gst = $compign_budget_with_gst;
            $upi_value =  strval(Helpers::get_business_settings('upi_value'));

            if($paymentSplit->feedback_percentage){
                $campaign->feedback_percentage = $paymentSplit->feedback_percentage;
                $final_feedback_reward = ($request->reward_per_user * $paymentSplit->feedback_percentage) / 100;
                $campaign->feedback_coin = $final_feedback_reward / $upi_value;
            } else {
                $campaign->feedback_percentage = 0;
                $campaign->feedback_coin = 0;
            }

            if ($paymentSplit->user_referral_percentage) {
                $campaign->user_referral_percentage = $paymentSplit->user_referral_percentage;
                $referral_reward = ($request->reward_per_user * $paymentSplit->user_referral_percentage) / 100;
                $campaign->referral_coin = $referral_reward / $upi_value;
            } else {
                $campaign->user_referral_percentage = 0;
                $campaign->referral_coin = 0;
            }
            $campaign->repeat_brand_percentage = $paymentSplit->repeat_brand_percentage ?? 0;

            if($paymentSplit->user_percentage){
                $campaign->campaign_user_budget = ($request->total_campaign_budget * $paymentSplit->user_percentage) / 100;
                $final_reward_for_user = ($request->reward_per_user * $paymentSplit->user_percentage) / 100;
                $campaign->final_reward_for_user = $final_reward_for_user;
                $campaign->coins = $final_reward_for_user / $upi_value;
            }else{
                $campaign->campaign_user_budget = ($request->total_campaign_budget * 50) / 100;
                $final_reward_for_user = ($request->reward_per_user * 50) / 100;
                $campaign->final_reward_for_user = $final_reward_for_user;
                $campaign->coins = $final_reward_for_user / $upi_value;
            }
           
            $campaign->save();

            // here remove amount from wellert and create transaction for campaign creation

            Helpers::systemActivity('campaign', $seller, 'created', 'Campaign created successfully', $campaign);

            $commissionRate = Helpers::get_business_settings('sale_post_commission');
            $commissionAmount = ($campaign->total_campaign_budget * $commissionRate) / 100;


            SaleCommissionLedger::create([
                'sale_id' => $salesId,
                'brand_id' => $request->brand_id,
                'campaign_id' => $campaign->id,
                'amount' => $campaign->total_campaign_budget,
                'commission_rate' => $commissionRate,
                'commission_amount' => $commissionAmount,
                'reference_type' => 'campaign_budget'
            ]);

            // Repeat brand bonus: extra commission if same salesperson brought same brand within 100 days
            $repeatBrandRate = $paymentSplit->repeat_brand_percentage ?? 0;
            if ($repeatBrandRate > 0) {
                $saleAgent = Sale::find($salesId);
                $isRepeatBrand = $saleAgent && Campaign::where('brand_id', $request->brand_id)
                    ->where('sales_referal_code', $saleAgent->referral_code)
                    ->where('id', '!=', $campaign->id)
                    ->where('created_at', '>=', now()->subDays(100))
                    ->exists();

                if ($isRepeatBrand) {
                    $repeatAmount = ($campaign->total_campaign_budget * $repeatBrandRate) / 100;
                    SaleCommissionLedger::create([
                        'sale_id'           => $salesId,
                        'brand_id'          => $request->brand_id,
                        'campaign_id'       => $campaign->id,
                        'amount'            => $campaign->total_campaign_budget,
                        'commission_rate'   => $repeatBrandRate,
                        'commission_amount' => $repeatAmount,
                        'reference_type'    => 'repeat_brand',
                    ]);
                }
            }
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


  

    public function detailCampaign(Request $request, $id) {
        $data = Helpers::get_sale_by_token($request);

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
                'message' => ('Your existing session token does not authorize you any more'),
                'data' => []
            ], 401);
        }

        return response()->json([
            'status' => true,
            'message' => 'Campaign updated successfully',
            'data' => []
        ], 200);
    }

    public function updateCampaign(Request $request, $id) {
        $data = Helpers::get_sale_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
            // Logic to create campaign

            $campaign = Campaign::find($id);
            if($request->hasFile('thumbnail')) {
                $campaign->thumbnail = ImageManager::upload('profile/', 'png', $request->file('thumbnail'));
            }
            if ($request->file('images')) {
                $product_images = [];
                foreach ($request->file('images') as $img) {
                    $image_name = ImageManager::upload('profile/', 'png', $img);
                    $product_images[] = $image_name;
                }
                $campaign->images = implode(',', $product_images);
            }            
            $campaign->sale_id = $seller['id'];
            $campaign->brand_id = $request->brand_id;
            $campaign->title = $request->caption;
            $campaign->descriptions = $request->caption;
            $campaign->tags = $request->hashtags;
            $campaign->share_on = $request->social_media;
            $campaign->start_date = $request->start_date;
            $campaign->end_date = $request->end_date;
            $campaign->gender = $request->gender;
            $campaign->state = $request->state;
            $campaign->city = is_array($request->city)
                ? implode(',', array_filter($request->city))
                : ($request->city ?? '');
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

            /**
             * Create Activity Log
             */
            Helpers::systemActivity('campaign', $seller, 'updated', 'Campaign updated', $campaign);
            /**
             * Create Activity Log
             */

        } else {
            return response()->json([
                'status' => false,
                'message' => ('Your existing session token does not authorize you any more'),
                'data' => []
            ], 401);
        }

        return response()->json([
            'status' => true,
            'message' => 'Campaign updated successfully',
            'data' => []
        ], 200);
    }

    public function listCampaign(Request $request) {
        $data = Helpers::get_sale_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
            $campaigns = Campaign::with(['brand', 'feedbacks.user', 'campaign_transactions.user'])->where('sale_id', $seller['id'])
                ->when($request->has('status'), function($query) use ($request) {
                    // if ($request->status == 'active') {
                    //     $query->whereDate('start_date', '<=', Carbon::now())->whereDate('end_date', '>=', Carbon::now());
                    // } elseif ($request->status == 'upcoming') {
                    //     $query->whereDate('start_date', '>', Carbon::now());
                    // } elseif ($request->status == 'completed') {
                    //     $query->whereDate('end_date', '<', Carbon::now());
                    // }
                    $query->where('status', $request->status);
                })
                ->orderBy('id','DESC')
                ->paginate(25);

            return response()->json([
                'status' => true,
                'message' => 'Campaign list retrieved successfully',
                'data' => CommonResource::collection($campaigns)
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => ('Your existing session token does not authorize you any more'),
                'data' => []
            ], 401);
        }
    }

    public function deleteCampaign(Request $request, $id) {
        $data = Helpers::get_sale_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
            $campaign = Campaign::where('id', $id)->where('sale_id', $seller['id'])->first();
            if ($campaign) {
                $campaign->delete();

            /**
             * Create Activity Log
             */
            Helpers::systemActivity('campaign', $seller, 'deleted', 'Campaign deleted', $campaign);
            /**
             * Create Activity Log
             */

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
                'message' => ('Your existing session token does not authorize you any more'),
                'data' => []
            ], 401);
        }
    }

    public function registerBrand(Request $request)
    {
        try {
            $data = Helpers::get_sale_by_token($request);

            if ($data['success'] == 1) {
                $sale = $data['data'];
                $validator = Validator::make($request->all(), [
                    'f_name' => 'required|string|max:255',
                    'l_name' => 'nullable|string|max:255',
                    'username' => 'required|string|max:255',
                    'mobile' => 'required|digits:10|unique:sellers,phone',
                    'email' => 'nullable|email|unique:sellers,email',
                    'referral_code' => 'nullable|string|max:50'
                ]);
    
                if ($validator->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => Helpers::single_error_processor($validator)
                    ], 422);
                }
                $panSubmitted = $request->filled('pan_number') || $request->hasFile('pan_image');
                $gstSubmitted = $request->filled('gst_number');

                $user = Seller::create([
                    'f_name' => $request->f_name,
                    'l_name' => $request->l_name,
                    'username' => $request->username,
                    'phone' => $request->mobile,
                    'email' => $request->email,
                    'referral_code' => Helpers::generate_referral_code(),
                    'friends_code' => $data['referral_code'] ?? null,
                    'city' => $request->city,
                    'sale_id' => $sale['id'],
                    'state' => $request->state,
                    'instagram_username' => $request->instagram_username,
                    'facebook_username' => $request->facebook_username,
                    'auth_token' => '',
                    'category_id' => $request->filled('category_id') ? $request->category_id : null,
                    'sub_category_id' => $request->filled('sub_category_id') ? $request->sub_category_id : null,
                    'gst_number' => $request->gst_number ?? '',
                    'gst_status' => $gstSubmitted ? 'Submitted' : 'Not Submitted',
                    'business_registeration_type' => $request->business_registeration_type ?? 'Proprietor',
                    'pan_number' => $request->pan_number ?? '',
                    'pan_status' => $panSubmitted ? 'Submitted' : 'Not Submitted',
                    'primary_contact' => $request->primary_contact ?? '',
                    'alternate_contact' => $request->alternate_contact ?? '',
                    'full_address' => $request->full_address ?? '',
                    'google_map_link' => $request->google_map_link ?? '',
                    'website_link' => $request->website_link ?? ''
                ]);
    
                Helpers::systemActivity('brand', $sale, 'created', 'Brand Registration', $user);

                return response()->json([
                    'status' => true,
                    'message' => 'Brand Registration successful.',
                    'token' => ''
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Your existing session token does not authorize you any more',
                    'token' => ''
                ], 401);
            }
            
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
                'token' => ''
            ]);
        }
    }

    public function listBrand(Request $request) {
        $data = Helpers::get_sale_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
            $brands = Seller::where('sale_id', $seller['id'])
                ->when($request->has('status'), function($query) use ($request) {
                    $query->where('status', $request->status);
                })
                ->orderBy('created_at', 'DESC')
                ->paginate($request->get('per_page', 25));
            
            return response()->json([
                'status' => true,
                'message' => 'Brand list retrieved successfully',
                'data' => CommonResource::collection($brands)
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Your existing session token does not authorize you any more',
                'data' => []
            ], 401);
        }
    }

    public function detailBrand(Request $request, int|string $id) {
        $data = Helpers::get_sale_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
            $brand = Seller::where('id', '=', $id, 'and')
                ->where('sale_id', '=', $seller['id'], 'and')
                ->first();
            
            if ($brand && $brand->sale_id == $seller['id']) {
                return response()->json([
                    'status' => true,
                    'message' => 'Brand detail retrieved successfully',
                    'data' => [new CommonResource($brand)]
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Brand not found or you do not have permission to view this brand',
                    'data' => []
                ], 404);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Your existing session token does not authorize you any more',
                'data' => []
            ], 401);
        }
    }

    public function walletTransactions(Request $request) {
        $data = Helpers::get_sale_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
            $transactions = SaleWalletTransaction::where('sale_id', '=', $seller['id'], 'and')
                ->orderBy('id', 'DESC')
                ->paginate($request->get('per_page', 10));
            
            return response()->json([
                'status' => true,
                'message' => 'Wallet transactions retrieved successfully',
                'data' => CommonResource::collection($transactions),
                'wallet_balance' => strval($seller->balance ?? 0)
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Your existing session token does not authorize you any more',
                'data' => []
            ], 401);
        }
    }

    public function createWithdrawl(Request $request) {
        $data = Helpers::get_sale_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];

            if($seller->balance < $request->amount) {
                return response()->json([
                    'status' => false,
                    'message' => 'Insufficient balance',
                    'data' => []
                ], 200);
            }

            if (SaleWalletTransaction::where('sale_id', '=', $seller['id'], 'and')
                ->where('amount', '=', $request->amount, 'and')
                ->where('type', '=', 'debit', 'and')
                ->where('status', '=', 'pending', 'and')
                ->first()) {
                return response()->json([
                    'status' => false,
                    'message' => 'One request is already pending',
                    'data' => []
                ], 200);
            }

            $transactions = SaleWalletTransaction::create([
                'sale_id' => $seller['id'],
                'amount' => $request->amount,
                'type' => 'debit',
                'status' => 'pending',
                'remarks' => $request->remarks
            ]);
            $seller->balance -= $request->amount;
            $seller->save();

            Helpers::systemActivity('sale_wallet_transaction', $seller, 'created', 'Wallet Withdrawl Request', $transactions);
            Helpers::systemActivity('sale_wallet_transaction', $seller, 'created', 'Balance debited from wallet', $seller);

            return response()->json([
                'status' => true,
                'message' => 'Wallet withdrawl created successfully',
                'data' => [],
                'wallet_balance' => strval($seller->balance ?? 0)
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Your existing session token does not authorize you any more',
                'data' => []
            ], 401);
        }
    }

    public function notifications(Request $request) {

        $limit = $request->limit ?? 25;
        $notifications = Notification::where('status', '=', 1, 'and')
            ->where('type', '=', 'sale', 'and')
            ->orderBy('id', 'DESC')
            ->paginate($limit);
        return response()->json([
            'status' => true,
            'message' => 'Notification retrieved successfully',
            'data' => CommonResource::collection($notifications)
        ]);
    }

    public function ledgerTransactions(Request $request)
    {
        $data = Helpers::get_sale_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
            $saleId = $seller['id'];
            $transactions = SaleCommissionLedger::where('sale_id', '=', $saleId, 'and')
            ->with(['sale', 'brand','campaign'])
            ->orderBy('id', 'desc')->paginate(25);
            return response()->json([
                'status' => true,
                'message' => 'Ledger Transactions retrieved successfully',
                'data' => CommonResource::collection($transactions)
            ],200);
        }
        return response()->json([
            'status' => false,
            'message' => 'Your existing session token does not authorize you any more',
            'data' => []
        ], 401);
    }

    public function salesTermsAndConditions(Request $request)
    {
        $terms = Helpers::get_business_settings('sales_terms_condition');
        return response()->json([
            'status' => true,
            'message' => 'Sales Terms and Conditions retrieved successfully',
            'data' => $terms
        ], 200);
    }

    public function salesPrivacyPolicy(Request $request)
    {
        $policy = Helpers::get_business_settings('sales_privacy_policy');
        return response()->json([
            'status' => true,
            'message' => 'Sales Privacy Policy retrieved successfully',
            'data' => $policy
        ], 200);
    }

    /**
     * Verify a PAN number with the Nerofy API.
     *
     * @return array{valid: bool, status: string|null, name: string|null, error: string|null}
     */
    private function verifyPanNumber(string $panNumber): array
    {
        $token = env('NEROFY_API_TOKEN');

        $ch = curl_init('https://api.nerofy.in/api/v1/service/pancard/verify');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode(['panNumber' => strtoupper(trim($panNumber))]),
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token,
            ],
            CURLOPT_TIMEOUT        => 15,
        ]);
        $raw = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err || $raw === false) {
            return ['valid' => false, 'status' => null, 'name' => null, 'error' => 'Could not reach PAN verification service.'];
        }

        $body = json_decode($raw, true);
        if (!isset($body['data'])) {
            return ['valid' => false, 'status' => null, 'name' => null, 'error' => $body['message'] ?? 'Invalid response from PAN verification service.'];
        }

        $data  = $body['data'];
        $valid = isset($data['pan_status']) && strtoupper($data['pan_status']) === 'PAN IS VALID';

        return [
            'valid'  => $valid,
            'status' => $data['pan_status'] ?? null,
            'name'   => $data['name'] ?? null,
            'error'  => null,
        ];
    }

}
