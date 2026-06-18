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
use App\Models\SocialVerificationTransaction;
use App\Models\PaymentSplit;
use App\Models\BusinessSetting;
use App\Models\BrandCategory;
use App\Models\Sale;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function App\CPU\translate;
use App\Http\Resources\CommonResource;
use App\Services\CampaignCreditNoteService;
use App\Services\CampaignSettlementService;
use App\Services\CampaignInvoiceService;
use App\Services\PanValidationService;



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

            $participants = CampaignTransaction::whereHas('campaign', function ($q) use ($userId) {
                    $q->where('brand_id', $userId);
                })
                ->count();

            $violated = Campaign::where('brand_id', $userId)->where('status', 'violated')->count();

            $campaignList = Campaign::where('brand_id', $userId)->select('id', 'title', 'status')->get();

            $campaignIds = Campaign::where('brand_id', $userId)->pluck('id');

            $engagement = CampaignTransaction::whereIn('campaign_id', $campaignIds)
                ->whereIn('status', CampaignTransaction::SLOT_OCCUPIED_STATUSES)
                ->count();

            $avgFeedback = Feedback::where('brand_id', $userId)->avg('ratings');

            $sellerCampaigns = Campaign::where('brand_id', $userId)->get();
            $costPerClick = round(
                (float) ($sellerCampaigns->avg(fn (Campaign $campaign) => (float) $campaign->cost_per_click) ?? 0),
                2
            );

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

            $campaign = Campaign::where('brand_id', $userId)
                ->where('id', $campaignId)
                ->withCount(['occupiedTransactions as occupied_slots'])
                ->first();

            if (!$campaign) {
                return response()->json([
                    'status' => false,
                    'message' => 'Campaign not found or you do not have permission to access this campaign.',
                    'data' => [],
                ], 404);
            }

            $campaignList = Campaign::where('brand_id', $userId)
                ->where('id', $campaignId)
                ->select('id', 'title', 'status')
                ->get();

            $chartData = CampaignTransaction::where('campaign_id', $campaign->id)
                ->whereIn('status', CampaignTransaction::SLOT_OCCUPIED_STATUSES)
                ->selectRaw('DATE(created_at) as date, count(*) as value')
                ->groupBy('date')
                ->get();

            $metrics = $this->buildCampaignReportMetrics($campaign);

            $avgFeedback = Feedback::where('campaign_id', $campaignId)->avg('ratings');

            return response()->json([
                "status" => true,
                "message" => "Statistics Data",
                "data" => new CommonResource([
                    'campaign_list' => $campaignList,
                    'engagement' => $metrics['engagement'],
                    'avg_feedback' => round($avgFeedback, 1),
                    'average_feedbacks' => round($avgFeedback, 1),
                    'cost_per_click' => $metrics['cost_per_click'],
                    'budget' => $metrics['budget'],
                    'budget_spent' => $metrics['budget_utilized'],
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
            if ($request->has('pan_number') && $shop->pan_status !== 'Verified') {
                $panValidationService = app(PanValidationService::class);
                $panVerification = $panValidationService->verifyPanNumber($request->pan_number);

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
                    ], 422);
                }

                $assignError = $panValidationService->validateAssignment(
                    $request->pan_number,
                    PanValidationService::sellerDisplayName($shop),
                    $panVerification['name'] ?? null,
                    null,
                    $shop->id
                );
                if ($assignError !== null) {
                    return response()->json([
                        'status'  => false,
                        'message' => $assignError,
                    ], 422);
                }

                $shop->pan_number = $panValidationService->normalizePan($request->pan_number);
                $shop->pan_status = 'Submitted';
            }
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
            $shop->instagram_status = 'pending';
        }
        if ($request->has('facebook_username') && $shop->facebook_status !== 'Verified') {
            $shop->facebook_username = $request->facebook_username;
            $shop->facebook_status = 'pending';
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
            // Verify PAN with third-party API before accepting it
            $panValidationService = app(PanValidationService::class);
            $panVerification = $panValidationService->verifyPanNumber($request->pan_number);

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

            $assignError = $panValidationService->validateAssignment(
                $request->pan_number,
                PanValidationService::sellerDisplayName($shop),
                $panVerification['name'] ?? null,
                null,
                $shop->id
            );
            if ($assignError !== null) {
                return response()->json([
                    'status'  => false,
                    'message' => $assignError,
                ], 422);
            }

            $shop->pan_number = $panValidationService->normalizePan($request->pan_number);
            $shop->pan_status = 'Submitted';
        }

        if ($request->has('gst_number') && $shop->gst_status !== 'Verified') {
            if ($request->filled('gst_number')) {
                // Verify GST with third-party API before accepting it
                $gstVerification = $this->verifyGstNumber($request->gst_number);

                if ($gstVerification['error'] !== null) {
                    return response()->json([
                        'status'  => false,
                        'message' => $gstVerification['error'],
                    ], 502);
                }

                if (!$gstVerification['valid']) {
                    return response()->json([
                        'status'  => false,
                        'message' => 'GST number is invalid or does not exist. Please enter a valid GSTIN.',
                        'data'    => [
                            'gst_number' => $request->gst_number,
                            'gst_status' => $gstVerification['status'],
                        ],
                    ], 422);
                }
            }

            $shop->gst_number = $request->gst_number;
            $shop->gst_status = $request->filled('gst_number') ? 'Submitted' : 'Not Submitted';
            $shop->billing_name = $request->billing_name;
            $shop->billing_address = $request->billing_address;
            $shop->billing_phone = $request->billing_phone;
        }

        if (
            $shop->bank_status !== 'Verified' &&
            ($request->filled('bank_account_number') || $request->filled('bank_ifsc_code') ||
                $request->filled('bank_account_holder_name') || $request->filled('bank_account_type'))
        ) {
            $shop->bank_account_number       = $request->bank_account_number ?? $shop->bank_account_number;
            $shop->bank_ifsc_code            = $request->bank_ifsc_code ?? $shop->bank_ifsc_code;
            $shop->bank_account_holder_name  = $request->bank_account_holder_name ?? $shop->bank_account_holder_name;
            $shop->bank_account_type         = strtolower($request->bank_account_type ?? $shop->bank_account_type);
            $shop->bank_status               = 'Submitted';
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
            $shop = Seller::find($seller['id']);

            if ($shop && $shop->status === 'banned') {
                return response()->json([
                    'status' => false,
                    'message' => 'Your account is Banned. Please contact to admin.',
                    'data' => [],
                ], 403);
            }

            // $verifiedSocial = SocialVerificationTransaction::STATUS_VERIFIED;
            // if ($shop->instagram_status !== $verifiedSocial || $shop->facebook_status !== $verifiedSocial) {
            //     return response()->json([
            //         'status' => false,
            //         'message' => translate('Please verify your Instagram and Facebook accounts before creating a campaign.'),
            //         'data' => [],
            //     ], 200);
            // }

            // if ($shop->pan_status !== 'Verified') {
            //     return response()->json([
            //         'status' => false,
            //         'message' => translate('Please complete KYC verification before creating a campaign.'),
            //         'data' => [],
            //     ], 200);
            // }

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

            $saleRecordForReferral = null;
            if ($request->filled('sales_referal_code')) {
                $salesReferralCode = trim((string) $request->sales_referal_code);
                $saleRecordForReferral = Sale::where('referral_code', $salesReferralCode)->first();

                if (!$saleRecordForReferral) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Invalid sales referral code.',
                        'data' => [],
                    ], 422);
                }
            }

            // ── Discount voucher validation ───────────────────────────────────────
            $discountAmount  = 0.0;
            $discountCode    = null;
            $discountVoucher = null;

            if ($request->filled('discount_code')) {
                if (!$saleRecordForReferral) {
                    return response()->json([
                        'status' => false,
                        'message' => 'A valid sales referral code is required to apply a discount code.',
                        'data' => [],
                    ], 422);
                }

                $discountCode    = trim((string) $request->discount_code);
                $discountVoucher = \App\Models\CampaignDiscountVoucher::where('code', $discountCode)
                    ->where('sale_id', $saleRecordForReferral->id)
                    ->first();

                if (!$discountVoucher || !$discountVoucher->isValid()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Invalid, expired, or exhausted discount voucher code.',
                        'data' => [],
                    ], 422);
                }

                $paymentSplitCheck = PaymentSplit::first();
                $maxAllowedDiscount = ($request->total_campaign_budget * ($paymentSplitCheck->sales_percentage ?? 0)) / 100;
                if ($discountVoucher->discount_amount > $maxAllowedDiscount) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Discount amount exceeds the maximum allowed from the sales commission for this campaign.',
                        'data' => [],
                    ], 422);
                }

                $discountAmount = (float) $discountVoucher->discount_amount;
            }

            $generateGstInvoice = $request->boolean('generate_gst_invoice');
            if ($generateGstInvoice && empty(trim((string) ($shop->gst_number ?? '')))) {
                return response()->json([
                    'status' => false,
                    'message' => 'Please add your GST number in profile before creating a campaign with GST invoice.',
                    'data' => [],
                ], 422);
            }

            $campaign = new Campaign;
            if ($request->hasFile('thumbnail')) {
                $campaign->thumbnail = ImageManager::upload('profile/', 'png', $request->file('thumbnail'));
            }

            $mediaType = $request->input('media_type', 'image');
            $campaign->media_type = $mediaType;

            if ($mediaType === 'video') {
                if ($request->hasFile('video')) {
                    $campaign->video = ImageManager::upload('profile/', 'mp4', $request->file('video'));
                }
            } else {
                if ($request->file('images')) {
                    $product_images = [];
                    foreach ($request->file('images') as $img) {
                        try {
                            $image_name = ImageManager::upload('profile/', 'png', $img);
                            $product_images[] = $image_name;
                        } catch (\Throwable $th) {
                            //throw $th;
                        }
                    }
                    $campaign->images = implode(',', $product_images);
                }
            }



            $paymentSplit = PaymentSplit::first();
            $gst_percentage = (int) Helpers::get_business_settings('campaign_gst_percentage');
            $total_campaign_budget = $request->total_campaign_budget;
            // Apply discount before GST: GST is charged on the net taxable amount only
            $net_taxable_amount      = $total_campaign_budget - $discountAmount;
            $compign_budget_with_gst = $net_taxable_amount + ($net_taxable_amount * $gst_percentage / 100);

            $sellerWallet = Helpers::get_seller_wallet($seller['id']);

            if ($compign_budget_with_gst > $sellerWallet->wallet_amount) {
                return response()->json([
                    'status' => false,
                    'message' => 'Insufficient fund. Please recharge wallet.',
                    'data' => [],
                    'balance_sufficient' => false,
                    'current_balance' => $sellerWallet->wallet_amount,
                    'balance_required' => $compign_budget_with_gst,
                    'required_balance' => $compign_budget_with_gst,
                ], 200);
            }

            $campaign->brand_id = $seller['id'];
            $campaign->created_by = Campaign::CREATED_BY_BRAND;
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
            $campaign->sales_referal_code = $request->sales_referal_code;
            if ($saleRecordForReferral) {
                $campaign->sale_id = $saleRecordForReferral->id;
            }
            $campaign->discount_amount       = $discountAmount;
            $campaign->discount_code         = $discountCode;
            $campaign->compign_budget_with_gst = $compign_budget_with_gst;
            $campaign->generate_gst_invoice = $generateGstInvoice;
            $upi_value =  strval(Helpers::get_business_settings('upi_value'));

            if ($paymentSplit->feedback_percentage) {
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

            if ($paymentSplit->user_percentage) {
                $campaign->campaign_user_budget = ($request->total_campaign_budget * $paymentSplit->user_percentage) / 100;
                $final_reward_for_user = ($request->reward_per_user * $paymentSplit->user_percentage) / 100;
                $campaign->final_reward_for_user = $final_reward_for_user;
                $campaign->coins = $final_reward_for_user / $upi_value;
            } else {
                $campaign->campaign_user_budget = ($request->total_campaign_budget * 50) / 100;
                $final_reward_for_user = ($request->reward_per_user * 50) / 100;
                $campaign->final_reward_for_user = $final_reward_for_user;
                $campaign->coins = $final_reward_for_user / $upi_value;
            }

            $campaign->save();

            // Mark discount voucher as used
            if ($discountVoucher) {
                $discountVoucher->increment('used_count');
            }

            // Debit brand wallet by actual amount charged (net_taxable + GST = compign_budget_with_gst)
            $sellerWallet->wallet_amount -= $compign_budget_with_gst;
            $sellerWallet->save();

            // Log wallet debit transaction for campaign creation
            \App\Models\SellerWalletHistory::create([
                'seller_id' => $seller['id'],
                'amount'    => $compign_budget_with_gst,
                'remarks'   => 'Campaign creation: ' . $campaign->title,
                'type'      => 'debit',
            ]);

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

    public function downloadCampaignInvoice(Request $request, $id, CampaignInvoiceService $invoiceService)
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
        $campaign = Campaign::where('id', $id)->where('brand_id', $seller['id'])->first();

        if (!$campaign) {
            return response()->json([
                'status' => false,
                'message' => 'Campaign not found or you do not have permission to access this campaign.',
                'data' => [],
            ], 404);
        }

        $validation = $invoiceService->validateDownload($campaign, (int) $seller['id']);
        if (!$validation['ok']) {
            return response()->json([
                'status' => false,
                'message' => $validation['message'],
                'data' => [],
            ], $validation['code']);
        }

        return $invoiceService->downloadResponse($campaign);
    }

    public function downloadCampaignCreditNote(Request $request, $id, CampaignCreditNoteService $creditNoteService)
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
        $campaign = Campaign::where('id', $id)->where('brand_id', $seller['id'])->first();

        if (!$campaign) {
            return response()->json([
                'status' => false,
                'message' => 'Campaign not found or you do not have permission to access this campaign.',
                'data' => [],
            ], 404);
        }

        $validation = $creditNoteService->validateDownload($campaign, (int) $seller['id']);
        if (!$validation['ok']) {
            return response()->json([
                'status' => false,
                'message' => $validation['message'],
                'data' => [],
            ], $validation['code']);
        }

        return $creditNoteService->downloadResponse($campaign);
    }

    public function detailCampaign(Request $request, $id)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
            // Logic to create campaign

            $campaign = Campaign::with(['brand', 'feedbacks.user', 'campaign_transactions.user'])
                ->withCount(['occupiedTransactions as occupied_slots'])
                ->find($id);

            if ($campaign) {
                $totalCampaignBudget = (float) ($campaign->total_campaign_budget ?? 0);
                $numberOfPost = (int) ($campaign->number_of_post ?? 0);
                $occupiedSlots = (int) ($campaign->occupied_slots ?? 0);

                $perPostBudget = $numberOfPost > 0 ? ($totalCampaignBudget / $numberOfPost) : 0;
                $budgetUtilized = $perPostBudget * $occupiedSlots;

                $campaign->setAttribute('per_post_budget', round($perPostBudget, 2));
                $campaign->setAttribute('budget_utilized', round($budgetUtilized, 2));

                // Reach-based CPC: sum follower counts of occupied participants by platform
                $estimatedReach = (int) DB::table('campaign_transactions as ct')
                    ->join('users as u', 'u.id', '=', 'ct.user_id')
                    ->where('ct.campaign_id', $campaign->id)
                    ->whereIn('ct.status', CampaignTransaction::SLOT_OCCUPIED_STATUSES)
                    ->whereNull('u.deleted_at')
                    ->selectRaw(
                        'SUM(CASE WHEN ct.shared_on = "instagram" THEN COALESCE(u.instagram_followers, 0) ELSE 0 END)' .
                        ' + SUM(CASE WHEN ct.shared_on = "facebook"  THEN COALESCE(u.facebook_followers,  0) ELSE 0 END)' .
                        ' AS estimated_reach'
                    )
                    ->value('estimated_reach');

                $campaign->setAttribute('estimated_reach', $estimatedReach);

                // Override cost_per_click with reach-based formula when we have follower data;
                // fall back to budget / posts when reach is unknown (no followers collected yet).
                if ($estimatedReach > 0) {
                    $campaign->setAttribute(
                        'cost_per_click',
                        (string) round($totalCampaignBudget / $estimatedReach, 4)
                    );
                }

                $settlementService = app(CampaignSettlementService::class);
                $settlementPreview = $settlementService->calculateReleasableAmount($campaign);
                $campaign->setAttribute('pending_wallet_return', (string) ($settlementPreview['releasable_amount'] ?? '0'));
                $campaign->setAttribute(
                    'settlement_deadline',
                    $settlementService->settlementDeadline($campaign)->toDateTimeString()
                );
                $campaign->setAttribute(
                    'awaiting_settlement',
                    $campaign->settlement_status !== CampaignSettlementService::SETTLEMENT_SETTLED
                    && in_array($campaign->status, ['closed', 'stopped', 'completed'], true)
                );
            }

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
    }

    public function updateCampaign(Request $request, $id)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
            // Logic to create campaign

            $campaign = Campaign::find($id);
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

            $mediaType = $request->input('media_type', $campaign->media_type ?? 'image');
            $campaign->media_type = $mediaType;

            if ($mediaType === 'video') {
                if ($request->hasFile('video')) {
                    $oldVideo = $campaign->getRawOriginal('video');
                    if ($oldVideo) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete('profile/' . $oldVideo);
                    }
                    $campaign->video = ImageManager::upload('profile/', 'mp4', $request->file('video'));
                }
            } else {
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
            $campaign->city = is_array($request->city)
                ? implode(',', array_filter($request->city))
                : ($request->city ?? '');
            $campaign->category_id = $category->id;
            $campaign->sub_category_id = $subCategoryId;
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

        if ($data['success'] != 1) {
            return response()->json([
                'status' => false,
                'message' => translate('Your existing session token does not authorize you any more'),
                'data' => []
            ], 401);
        }

        $seller = $data['data'];
        $campaign = Campaign::where('id', $id)->where('brand_id', $seller['id'])->first();

        if (!$campaign) {
            return response()->json([
                'status' => false,
                'message' => 'Campaign not found.',
                'data' => []
            ], 404);
        }

        $newStatus = $request->status;
        $shouldChargeWallet = $newStatus === 'accepted'
            && $campaign->created_by === Campaign::CREATED_BY_SALES_PERSON
            && $campaign->status === 'pending';

        if ($shouldChargeWallet) {
            $charge = (float) $campaign->compign_budget_with_gst;

            return DB::transaction(function () use ($seller, $campaign, $newStatus, $charge) {
                $sellerWallet = SellerWallet::where('seller_id', $seller['id'])->lockForUpdate()->first();
                if (!$sellerWallet) {
                    $sellerWallet = Helpers::get_seller_wallet($seller['id']);
                }

                if ($charge > (float) $sellerWallet->wallet_amount) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Insufficient fund. Please recharge wallet.',
                        'data' => [],
                        'balance_sufficient' => false,
                        'current_balance' => $sellerWallet->wallet_amount,
                        'balance_required' => $charge,
                        'required_balance' => $charge,
                    ], 200);
                }

                $sellerWallet->wallet_amount -= $charge;
                $sellerWallet->save();

                \App\Models\SellerWalletHistory::create([
                    'seller_id' => $seller['id'],
                    'amount'    => $charge,
                    'remarks'   => 'Campaign accepted: ' . $campaign->title,
                    'type'      => 'debit',
                ]);

                $campaign->status = $newStatus;
                $campaign->save();

                Helpers::systemActivity('campaign', $seller, 'updated', 'Campaign status updated to ' . $newStatus, $campaign);

                return response()->json([
                    'status' => true,
                    'message' => 'Campaign updated successfully',
                    'data' => []
                ], 200);
            });
        }

        $campaign->status = $newStatus;
        $campaign->save();

        Helpers::systemActivity('campaign', $seller, 'updated', 'Campaign status updated to ' . $newStatus, $campaign);

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
            $campaigns = Campaign::with('sale')
                ->where('brand_id', $seller['id'])
                ->when($request->has('status'), function ($query) use ($request) {
                    $query->where('status', $request->input('status'));
                })
                ->orderBy('id', 'DESC')
                ->get()
                ->each(function ($campaign) {
                    $campaign->makeHidden('sale');
                });
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
        $notifications = Notification::where(['status' => 1, 'user_type' => 'brand'])->orderBy('id', 'DESC')->paginate($limit);
        return response()->json([
            'status' => true,
            'message' => 'Notification retrieved successfully',
            'data' => CommonResource::collection($notifications)
        ]);
    }

    public function notificationsCount(Request $request)
    {
        $notifications = Notification::where(['status' => 1, 'type' => 'brand'])->count();
        return response()->json([
            'status' => true,
            'message' => 'Notification count retrieved successfully',
            'data' => $notifications
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

    public function hasCampaignInLast100Days(Request $request)
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
        $brand = Seller::find($seller['id']);

        if (!$brand) {
            return response()->json([
                'status' => false,
                'message' => 'Brand not found',
                'data' => false
            ], 404);
        }

        // Find sale person using brand's friends_code matching sale's referral_code
        $salePerson = Sale::where('referral_code', $brand->friends_code)->first();

        if (!$salePerson) {
            return response()->json([
                'status' => true,
                'message' => 'No associated sale person found',
                'data' => false
            ]);
        }

        $total = Campaign::where('brand_id', $brand->id)
            ->where('sale_id', $salePerson->id);


        $exists = $total
            ->where('created_at', '>=', now()->subDays(100))
            ->exists();

        $count = $total->count();

        return response()->json([
            'status' => true,
            'message' => 'Campaign check completed',
            'data' => $exists,
            'total' => $count
        ]);
    }

    public function listRefunds(Request $request)
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

        $refunds = \App\Models\CampaignRefund::where('brand_id', $seller['id'])
            ->with(['campaign:id,title,status,stopped_at'])
            ->orderBy('created_at', 'desc')
            ->get([
                'id',
                'campaign_id',
                'brand_id',
                'calculated_amount',
                'refunded_amount',
                'bank_account_number',
                'bank_ifsc_code',
                'bank_account_holder_name',
                'bank_account_type',
                'status',
                'admin_note',
                'completed_at',
                'created_at',
            ]);

        return response()->json([
            'status'  => true,
            'message' => 'Refund list retrieved successfully',
            'data'    => CommonResource::collection($refunds),
        ], 200);
    }

    /**
     * Verify a GSTIN against the Nerofy third-party API.
     *
     * Returns an array with keys:
     *   'valid'    (bool)        – true if GSTIN Exists
     *   'status'   (string|null) – raw gst_status from the API
     *   'name'     (string|null) – legal_name_of_business from the API
     *   'error'    (string|null) – human-readable error when API call fails
     */
    private function verifyGstNumber(string $gstNumber): array
    {
        $token = env('NEROFY_API_TOKEN');

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL            => 'https://api.nerofy.in/api/v1/service/gstin/verify',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 15,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => json_encode(['gstinNumber' => strtoupper(trim($gstNumber))]),
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token,
            ],
        ]);

        $response  = curl_exec($curl);
        $curlError = curl_error($curl);
        curl_close($curl);

        if ($curlError) {
            return ['valid' => false, 'status' => null, 'name' => null, 'error' => 'GST verification service unreachable: ' . $curlError];
        }

        $decoded = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE || !isset($decoded['data']['gst_status'])) {
            return ['valid' => false, 'status' => null, 'name' => null, 'error' => 'Invalid response from GST verification service.'];
        }

        $gstStatus = trim($decoded['data']['gst_status'] ?? '');
        $isValid   = $gstStatus === 'GSTIN Exists';

        return [
            'valid'  => $isValid,
            'status' => $gstStatus,
            'name'   => $decoded['data']['legal_name_of_business'] ?? null,
            'error'  => null,
        ];
    }

    private function buildCampaignReportMetrics(Campaign $campaign): array
    {
        $totalCampaignBudget = (float) ($campaign->total_campaign_budget ?? 0);
        $numberOfPost = (int) ($campaign->number_of_post ?? 0);
        $occupiedSlots = (int) ($campaign->occupied_slots ?? 0);

        $perPostBudget = $numberOfPost > 0 ? ($totalCampaignBudget / $numberOfPost) : 0;
        $budgetUtilized = round($perPostBudget * $occupiedSlots, 2);

        $estimatedReach = (int) DB::table('campaign_transactions as ct')
            ->join('users as u', 'u.id', '=', 'ct.user_id')
            ->where('ct.campaign_id', $campaign->id)
            ->whereIn('ct.status', CampaignTransaction::SLOT_OCCUPIED_STATUSES)
            ->whereNull('u.deleted_at')
            ->selectRaw(
                'SUM(CASE WHEN ct.shared_on = "instagram" THEN COALESCE(u.instagram_followers, 0) ELSE 0 END)' .
                ' + SUM(CASE WHEN ct.shared_on = "facebook"  THEN COALESCE(u.facebook_followers,  0) ELSE 0 END)' .
                ' AS estimated_reach'
            )
            ->value('estimated_reach');

        $costPerClick = (float) $campaign->cost_per_click;
        if ($estimatedReach > 0) {
            $costPerClick = round($totalCampaignBudget / $estimatedReach, 4);
        }

        return [
            'engagement' => $occupiedSlots,
            'cost_per_click' => $costPerClick,
            'budget_utilized' => $budgetUtilized,
            'budget' => $totalCampaignBudget,
            'estimated_reach' => $estimatedReach,
        ];
    }
}
