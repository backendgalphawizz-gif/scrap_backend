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
use App\Models\SaleCommissionLedger;
use App\Http\Resources\CommonResource;
use Illuminate\Support\Str;
use Hash;

class DashboardController extends Controller
{
    // check city and stategit
    public function index(Request $request) {
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
            if($request->has('pan_number')) {
                $shop->pan_number = $request->pan_number;
                $shop->pan_status = 'Submitted';
                if($request->hasFile('pan_image')) {
                    $shop->pan_image = ImageManager::upload('profile/', 'png', $request->file('pan_image'));
                }
            }
            
            if($request->has('bank_name') || $request->has('ifsc_code')) {
                $shop->bank_detail = json_encode([
                                    'bank_name' => $request->bank_name,
                                    'ifsc_code' => $request->ifsc_code,
                                    'account_number' => $request->account_number,
                                    'branch_name' => $request->branch_name
                                ]);
                
                $shop->bank_status = $request->bank_name != '' ? 'Submitted' : 'Not Submitted';
            }
            $shop->save();
            Helpers::systemActivity('profile_updated', $shop, 'updated', 'Sale Profile KYC updated successfully', $shop);
            return response()->json([
                'status' => true,
                'message' => 'Sale KYC updated successfully',
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
            $campaign->brand_id = $request->brand_id;
            $campaign->sale_id = $seller['id'];
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
            $campaign->number_of_post = $request->number_of_post;
            $campaign->daily_budget_cap = $request->daily_budget_cap;
            $campaign->total_campaign_budget = $request->total_campaign_budget;
            $campaign->age_range = $request->age_range;
            $campaign->save();

            /**
             * Create Activity Log
             */
            Helpers::systemActivity('campaign', $seller, 'created', 'Campaign created', $campaign);
            /**
             * Create Activity Log
             */

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

        } else {
            return response()->json([
                'status' => false,
                'message' => ('Your existing session token does not authorize you any more'),
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
                $user = Seller::create([
                    'f_name' => $request->f_name,
                    'l_name' => $request->l_name,
                    'username' => $request->username,
                    'phone' => $request->mobile,
                    'email' => $request->email,
                    'referral_code' => Helpers::generate_referral_code(),
                    'friends_code' => $request->referral_code ?? '',
                    'city' => $request->city,
                    'sale_id' => $sale['id'],
                    'state' => $request->state,
                    'instagram_username' => $request->instagram_username,
                    'facebook_username' => $request->facebook_username,
                    'auth_token' => '',
                    'category_id' => $request->filled('category_id') ? $request->category_id : null,
                    'sub_category_id' => $request->filled('sub_category_id') ? $request->sub_category_id : null,
                    'gst_number' => $request->gst_number ?? '',
                    'business_registeration_type' => $request->business_registeration_type ?? 'Proprietor',
                    'pan_number' => $request->pan_number ?? '',
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

    public function detailBrand(Request $request, $id) {
        $data = Helpers::get_sale_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
            $brand = Seller::find($id);
            
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
            $transactions = SaleWalletTransaction::where('sale_id', $seller['id'])
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

            if(SaleWalletTransaction::where([
                'sale_id' => $seller['id'],
                'amount' => $request->amount,
                'type' => 'debit',
                'status' => 'pending'
            ])->first()) {
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
        $notifications = Notification::where(['status' => 1, 'type' => 'sale'])->orderBy('id', 'DESC')->paginate($limit);
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
            $transactions = SaleCommissionLedger::where('sale_id', $saleId)->with(['sale', 'brand','campaign'])->orderBy('id', 'desc')->paginate(25);
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

}
