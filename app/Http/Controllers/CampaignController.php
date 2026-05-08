<?php

namespace App\Http\Controllers;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CampaignTransaction;
use App\Models\BrandCategory;
use App\Models\Seller;
use App\Models\SellerWallet;
use App\Models\SellerWalletHistory;
use App\Models\PaymentSplit;
// use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CampaignController extends Controller
{
    function list(Request $request)
    {
        $campaigns = Campaign::with(['brand'])
            ->when($request->filled('brand_name'), function ($query) use ($request) {
                $brandName = trim($request->brand_name);
                $query->whereHas('brand', function ($brandQuery) use ($brandName) {
                    $brandQuery->where('username', 'like', "%{$brandName}%")
                        ->orWhere('f_name', 'like', "%{$brandName}%")
                        ->orWhere('l_name', 'like', "%{$brandName}%");
                });
            })
            ->when($request->filled('title'), function ($query) use ($request) {
                $query->where('title', 'like', '%' . trim($request->title) . '%');
            })
            ->when($request->filled('city'), function ($query) use ($request) {
                $query->where('city', 'like', '%' . trim($request->city) . '%');
            })
            ->when($request->filled('state'), function ($query) use ($request) {
                $query->where('state', 'like', '%' . trim($request->state) . '%');
            })
            ->when($request->filled('date_from'), function ($query) use ($request) {
                $query->whereDate('start_date', '>=', $request->date_from);
            })
            ->when($request->filled('date_to'), function ($query) use ($request) {
                $query->whereDate('start_date', '<=', $request->date_to);
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin-views.campaign.view', compact('campaigns'));
    }

    public function create()
    {
        $sellers = Seller::where('status', 'approved')->get();
        $categories = BrandCategory::query()
            ->where('status', 1)
            ->where(function ($query) {
                $query->whereNull('parent_id')->orWhere('parent_id', 0);
            })
            ->with(['childes' => function ($query) {
                $query->where('status', 1)->orderBy('name');
            }])
            ->orderBy('name')
            ->get(['id', 'name']);
        $guidelineOptions = $this->getCampaignGuidelineOptions();
        $states = DB::table('states')->where('country_id', 101)->orderBy('name')->get(['state_id', 'name']);
        $cities = DB::table('cities')->orderBy('name')->get(['city_id', 'name', 'state_id']);
        $campaign_gst_percentage = (float) Helpers::get_business_settings('campaign_gst_percentage');
        return view('admin-views.campaign.add', compact('sellers', 'guidelineOptions', 'categories', 'states', 'cities', 'campaign_gst_percentage'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'status' => 'nullable|in:active,inactive,pending,violated,live,completed,paused,stopped,rejected,accepted',
        ]);
        $category = BrandCategory::where('id', $request->category_id)
            ->where(function ($query) {
                $query->whereNull('parent_id')->orWhere('parent_id', 0);
            })
            ->where('status', 1)
            ->first();
        if (!$category) {
            return back()->withErrors(['category_id' => 'Valid category is required.'])->withInput();
        }

        $subCategoryId = $request->sub_category_id ?: null;
        if ($subCategoryId) {
            $subCategory = BrandCategory::where('id', $subCategoryId)
                ->where('parent_id', $category->id)
                ->where('status', 1)
                ->first();
            if (!$subCategory) {
                return back()->withErrors(['sub_category_id' => 'Selected sub category is invalid for selected category.'])->withInput();
            }
        }

        $ageRange = $request->age_range;
        if ($request->filled('age_range_min') && $request->filled('age_range_max')) {
            $ageRange = $request->age_range_min . '-' . $request->age_range_max;
        }

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
            $campaign->images = implode(',', $product_images);
        }  
        
        $paymentSplit = PaymentSplit::first();

        $campaign->brand_id = $request->brand_id;
        $campaign->title = $request->title ?? $request->caption;
        $campaign->descriptions = $request->caption;
        $campaign->tags = $request->hashtags;
        $campaign->share_on = $request->social_media ? implode(',', $request->social_media) : '';
        $campaign->status = $request->filled('status') ? $request->status : 'pending';
        $campaign->category_id = $category->id;
        $campaign->sub_category_id = $subCategoryId;
        $campaign->start_date = $request->start_date;
        $campaign->end_date = $request->end_date;
        $campaign->gender = $request->gender;
        $campaign->state = $request->state;
        $campaign->city = $request->city;
        $campaign->guidelines = implode('|', $request->input('guidelines', []));
        $campaign->coins = $request->reward_per_user;

        $campaign->total_user_required = $request->number_of_post;
        $campaign->reward_per_user = $request->reward_per_user;
        // $campaign->reward_per_post = $request->reward_per_post;
        $campaign->number_of_post = $request->number_of_post;
        $campaign->daily_budget_cap = $request->filled('daily_budget_cap') ? $request->daily_budget_cap : null;
        $campaign->total_campaign_budget = $request->total_campaign_budget;
        $campaign->age_range = $ageRange;
        $campaign->admin_percentage = $paymentSplit->admin_percentage;
        $campaign->user_percentage = $paymentSplit->user_percentage;
        $campaign->sales_percentage = $paymentSplit->sales_percentage;
        $campaign->sales_referal_code = $request->sales_referal_code;
        
        $gst_percentage = (int) Helpers::get_business_settings('campaign_gst_percentage');
        $compign_budget_with_gst = $request->total_campaign_budget + ($request->total_campaign_budget * $gst_percentage / 100);
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
        // Set unique_code as RXC-campaign_id
        $campaign->unique_code = 'RXC-' . $campaign->id;
        $campaign->save();
        return redirect()->route('admin.campaign.list');
    }

    public function show($id)
    {
        $campaign = Campaign::with(['brand'])->where('id', $id)->first();
        return view('admin-views.campaign.show', compact('campaign'));
    }

    public function status(Request $request)
    {
        if ($request->ajax()) {
            $banner = Campaign::find($request->id);
            $banner->status = $request->status;
            $banner->save();
            $data = $request->status;
            return response()->json(['status' => true, 'message' => 'Status updated success']);
        }
    }

    public function edit($id)
    {
        $campaign = Campaign::with(['brand'])->where('id', $id)->first();
        // dd($campaign->images);
        $sellers = Seller::where('status', 'approved')->get();
        $categories = BrandCategory::query()
            ->where('status', 1)
            ->where(function ($query) {
                $query->whereNull('parent_id')->orWhere('parent_id', 0);
            })
            ->with(['childes' => function ($query) {
                $query->where('status', 1)->orderBy('name');
            }])
            ->orderBy('name')
            ->get(['id', 'name']);
        $guidelineOptions = $this->getCampaignGuidelineOptions();
        $states = DB::table('states')->where('country_id', 101)->orderBy('name')->get(['state_id', 'name']);
        $cities = DB::table('cities')->orderBy('name')->get(['city_id', 'name', 'state_id']);
        $campaign_gst_percentage = (float) Helpers::get_business_settings('campaign_gst_percentage');
        return view('admin-views.campaign.edit', compact('campaign', 'sellers', 'guidelineOptions', 'categories', 'states', 'cities', 'campaign_gst_percentage'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'nullable|in:active,inactive,pending,violated,live,completed,paused,stopped,rejected,accepted',
        ]);
        $category = BrandCategory::where('id', $request->category_id)
            ->where(function ($query) {
                $query->whereNull('parent_id')->orWhere('parent_id', 0);
            })
            ->where('status', 1)
            ->first();
        if (!$category) {
            return back()->withErrors(['category_id' => 'Valid category is required.'])->withInput();
        }

        $subCategoryId = $request->sub_category_id ?: null;
        if ($subCategoryId) {
            $subCategory = BrandCategory::where('id', $subCategoryId)
                ->where('parent_id', $category->id)
                ->where('status', 1)
                ->first();
            if (!$subCategory) {
                return back()->withErrors(['sub_category_id' => 'Selected sub category is invalid for selected category.'])->withInput();
            }
        }

        $ageRange = $request->age_range;
        if ($request->filled('age_range_min') && $request->filled('age_range_max')) {
            $ageRange = $request->age_range_min . '-' . $request->age_range_max;
        }

        $campaign = Campaign::find($id);
        if($request->hasFile('thumbnail')) {
            $campaign->thumbnail = ImageManager::update('profile/', $campaign->thumbnail, 'png', $request->file('thumbnail'));
        }

        if ($request->file('images')) {
            $product_images = [];
            foreach ($request->file('images') as $img) {
                if($img) {
                    try {
                        // code...
                        $image_name = ImageManager::upload('profile/', 'png', $img);
                        $product_images[] = $image_name;
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
                }
            }
            $campaign->images = implode(',', $product_images);
        }
        $campaign->brand_id = $request->brand_id;
        $campaign->title = $request->title ?? $request->caption;
        $campaign->descriptions = $request->caption;
        $campaign->tags = $request->hashtags;
        $campaign->share_on = $request->social_media ? implode(',', $request->social_media) : '';
        $campaign->status = $request->filled('status') ? $request->status : ($campaign->status ?: 'pending');
        $campaign->category_id = $category->id;
        $campaign->sub_category_id = $subCategoryId;
        $campaign->start_date = $request->start_date;
        $campaign->end_date = $request->end_date;
        $campaign->gender = $request->gender;
        $campaign->state = $request->state;
        $campaign->city = $request->city;
        $campaign->guidelines = implode('|', $request->input('guidelines', []));
        $campaign->coins = $request->coins ?? $campaign->coins;
        $campaign->reward_per_user = $request->reward_per_user;
        $campaign->total_user_required = $request->number_of_post;
        $campaign->number_of_post = $request->number_of_post;
        $campaign->used_post = $request->used_post ?? $campaign->used_post;
        $campaign->daily_budget_cap = $request->filled('daily_budget_cap') ? $request->daily_budget_cap : null;
        $campaign->total_campaign_budget = $request->total_campaign_budget;
        $campaign->age_range = $ageRange;
        $campaign->sales_referal_code = $request->sales_referal_code;
        $campaign->admin_percentage = $request->admin_percentage ?? $campaign->admin_percentage;
        $campaign->user_percentage = $request->user_percentage ?? $campaign->user_percentage;
        $campaign->sales_percentage = $request->sales_percentage ?? $campaign->sales_percentage;
        $campaign->feedback_percentage = $request->feedback_percentage ?? $campaign->feedback_percentage;
        $campaign->campaign_user_budget = $request->campaign_user_budget ?? $campaign->campaign_user_budget;
        $campaign->compign_budget_with_gst = $request->compign_budget_with_gst ?? $campaign->compign_budget_with_gst;
        $campaign->final_reward_for_user = $request->final_reward_for_user ?? $campaign->final_reward_for_user;
        $campaign->feedback_coin = $request->feedback_coin ?? $campaign->feedback_coin;
        $campaign->save();

        return redirect()->route('admin.campaign.list');
        
    }

    public function delete(Request $request)
    {
        $br = Campaign::query()->where('id', '=', $request->id, 'and')->first();
        if ($br) {
            $thumbnailName = $br->getRawOriginal('thumbnail');
            if (!empty($thumbnailName)) {
                ImageManager::delete('profile/' . ltrim($thumbnailName, '/'));
            }
            Campaign::query()->where('id', '=', $request->id, 'and')->delete();
        }
        return response()->json();
    }

    public function campaignTransctions(Request $request)
    {
        $transactions = CampaignTransaction::with(['campaign.brand', 'user'])
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();
        return view('admin-views.campaign.transactions', compact('transactions'));
    }

    /**
     * Show the refund calculation preview for a stopped campaign.
     */
    public function refundPreview(int $id)
    {
        $campaign = Campaign::with('brand')->findOrFail($id);
        $sellerWallet = SellerWallet::firstOrCreate(
            ['seller_id' => $campaign->brand_id],
            ['wallet_amount' => 0, 'hold_for_campaign' => 0]
        );

        $refundData = $this->calculateRefund($campaign);

        return view('admin-views.campaign.refund-preview', compact('campaign', 'sellerWallet', 'refundData'));
    }

    /**
     * Process and credit the refund for a stopped campaign.
     */
    public function processRefund(Request $request, int $id)
    {
        $campaign = Campaign::findOrFail($id);

        if ($campaign->status !== 'stopped') {
            return back()->with('error', 'Refund can only be processed for stopped campaigns.');
        }

        if ($campaign->refund_status === 'processed') {
            return back()->with('error', 'Refund has already been processed for this campaign.');
        }

        $refundData = $this->calculateRefund($campaign);
        $refundableAmount = $refundData['refundable_amount'];

        DB::transaction(function () use ($campaign, $refundableAmount, $request) {
            // Credit wallet
            $wallet = SellerWallet::firstOrCreate(
                ['seller_id' => $campaign->brand_id],
                ['wallet_amount' => 0, 'hold_for_campaign' => 0]
            );
            $wallet->wallet_amount = $wallet->wallet_amount + $refundableAmount;
            $wallet->save();

            // Log wallet credit
            SellerWalletHistory::create([
                'seller_id'         => $campaign->brand_id,
                'amount'            => $refundableAmount,
                'remarks'           => 'Refund for stopped campaign: ' . $campaign->title,
                'type'              => 'credit',
                'available_balance' => $wallet->wallet_amount,
            ]);

            // Mark campaign refund as processed
            $campaign->refund_status    = 'processed';
            $campaign->refunded_amount  = $refundableAmount;
            $campaign->refund_note      = $request->input('refund_note');
            $campaign->save();

            Helpers::systemActivity('campaign_refund', auth()->user(), 'processed',
                "Refund of ₹{$refundableAmount} processed for campaign: {$campaign->title}", $campaign);
        });

        return redirect()->route('admin.campaign.show', $id)
            ->with('success', 'Refund of ₹' . number_format($refundableAmount, 2) . ' processed and credited to brand wallet.');
    }

    /**
     * Calculate the refundable amount for a campaign.
     *
     * Utilized budget = slots in (pending|active|approved|completed) × reward_per_user × (1 + gst%)
     * Refundable      = max(0, total_budget_with_gst − utilized_with_gst)
     */
    private function calculateRefund(Campaign $campaign): array
    {
        $utilizedSlots = CampaignTransaction::where('campaign_id', $campaign->id)
            ->whereIn('status', [
                CampaignTransaction::STATUS_PENDING,
                CampaignTransaction::STATUS_ACTIVE,
                CampaignTransaction::STATUS_APPROVED,
                CampaignTransaction::STATUS_COMPLETED,
            ])
            ->count();

        $gstPercentage    = (float) Helpers::get_business_settings('campaign_gst_percentage');
        $rewardPerUser    = (float) ($campaign->reward_per_user ?? 0);
        $totalBudgetGst   = (float) ($campaign->compign_budget_with_gst ?? 0);

        $utilizedRaw      = $utilizedSlots * $rewardPerUser;
        $utilizedWithGst  = $utilizedRaw * (1 + $gstPercentage / 100);
        $refundableAmount = max(0, $totalBudgetGst - $utilizedWithGst);

        return [
            'utilized_slots'    => $utilizedSlots,
            'reward_per_user'   => $rewardPerUser,
            'gst_percentage'    => $gstPercentage,
            'utilized_raw'      => round($utilizedRaw, 2),
            'utilized_with_gst' => round($utilizedWithGst, 2),
            'total_budget_gst'  => round($totalBudgetGst, 2),
            'refundable_amount' => round($refundableAmount, 2),
        ];
    }

    private function getCampaignGuidelineOptions(): array
    {
        $rawGuidelines = (string) (Helpers::get_business_settings('campaign_guideline') ?? '');
        if ($rawGuidelines === '') {
            return [];
        }

        preg_match_all('/<li[^>]*>(.*?)<\/li>/is', $rawGuidelines, $liMatches);
        if (!empty($liMatches[1])) {
            $options = array_map(static fn($item) => trim(strip_tags(html_entity_decode($item))), $liMatches[1]);
            return array_values(array_unique(array_filter($options)));
        }

        $text = html_entity_decode(strip_tags(str_replace(['<br>', '<br/>', '<br />'], PHP_EOL, $rawGuidelines)));
        $parts = preg_split('/\r\n|\r|\n/', $text) ?: [];
        $options = array_map(static fn($item) => trim($item), $parts);

        return array_values(array_unique(array_filter($options)));
    }

}
