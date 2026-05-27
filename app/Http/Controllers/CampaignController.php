<?php

namespace App\Http\Controllers;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CampaignRefund;
use App\Models\CampaignTransaction;
use App\Models\BrandCategory;
use App\Models\Seller;
use App\Models\SellerWallet;
use App\Models\SellerWalletHistory;
use App\Models\PaymentSplit;
use App\Services\CampaignCreditNoteService;
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
        $campaign->city = implode(',', array_filter((array)($request->city ?? [])));
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
        $campaign->generate_gst_invoice = $request->boolean('generate_gst_invoice');
        
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

    public function show(Request $request, $id)
    {
        $campaign = Campaign::with(['brand', 'category', 'subCategory', 'sale', 'creditNote'])
            ->where('id', $id)
            ->firstOrFail();

        $participantStatuses = [
            CampaignTransaction::STATUS_PENDING,
            CampaignTransaction::STATUS_ACTIVE,
            CampaignTransaction::STATUS_APPROVED,
            CampaignTransaction::STATUS_COMPLETED,
            CampaignTransaction::STATUS_REJECTED,
            CampaignTransaction::STATUS_FLAGGED,
            CampaignTransaction::STATUS_DELETED,
        ];

        $statusCounts = CampaignTransaction::where('campaign_id', $id)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $participantStatus = $request->get('participant_status', 'all');
        if ($participantStatus !== 'all' && !in_array($participantStatus, $participantStatuses, true)) {
            $participantStatus = 'all';
        }

        $participants = CampaignTransaction::with('user')
            ->where('campaign_id', $id)
            ->when($participantStatus !== 'all', fn ($q) => $q->where('status', $participantStatus))
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        $totalParticipants = (int) $statusCounts->sum();

        return view('admin-views.campaign.show', compact(
            'campaign',
            'participants',
            'statusCounts',
            'participantStatuses',
            'participantStatus',
            'totalParticipants'
        ));
    }

    public function status(Request $request)
    {
        if ($request->ajax()) {
            $campaign = Campaign::with('brand')->find($request->id);
            $oldStatus = $campaign->status;
            $campaign->status = $request->status;
            $campaign->save();
            
            // Send FCM notification to brand about campaign status change
            if ($campaign->brand && $campaign->brand->cm_firebase_token) {
                $title = '';
                $body = '';
                
                if ($request->status === 'active' || $request->status === 'live' || $request->status === 'accepted') {
                    $title = 'Campaign Approved! 🎉';
                    $body = "Your campaign \"{$campaign->title}\" has been approved and is now live.";
                } elseif ($request->status === 'rejected') {
                    $title = 'Campaign Rejected';
                    $body = "Your campaign \"{$campaign->title}\" has been rejected. Please review and resubmit.";
                } elseif ($request->status === 'paused') {
                    $title = 'Campaign Paused';
                    $body = "Your campaign \"{$campaign->title}\" has been paused.";
                } elseif ($request->status === 'stopped') {
                    $title = 'Campaign Stopped';
                    $body = "Your campaign \"{$campaign->title}\" has been stopped.";
                }
                
                if ($title && $body) {
                    Helpers::send_push_notif_to_topic($campaign->brand->cm_firebase_token, $title, $body);
                }
            }
            
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
        $campaign->city = implode(',', array_filter((array)($request->city ?? [])));
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
        if ($request->has('generate_gst_invoice')) {
            $campaign->generate_gst_invoice = $request->boolean('generate_gst_invoice');
        }
        $campaign->final_reward_for_user = $request->final_reward_for_user ?? $campaign->final_reward_for_user;
        $campaign->feedback_coin = $request->feedback_coin ?? $campaign->feedback_coin;
        $campaign->save();

        return redirect()->route('admin.campaign.list');
        
    }

    public function delete(Request $request)
    {
        $br = Campaign::query()->where('id', '=', $request->id)->first();
        if (!$br) {
            return response()->json(['status' => false, 'message' => 'Campaign not found'], 404);
        }
        if ($br->status !== 'pending') {
            return response()->json(['status' => false, 'message' => 'Only pending campaigns can be deleted'], 403);
        }
        $thumbnailName = $br->getRawOriginal('thumbnail');
        if (!empty($thumbnailName)) {
            ImageManager::delete('profile/' . ltrim($thumbnailName, '/'));
        }
        $br->delete();
        return response()->json(['status' => true, 'message' => 'Campaign deleted successfully']);
    }

    public function campaignTransctions(Request $request)
    {
        $transactions = CampaignTransaction::with(['campaign.brand', 'user'])
            ->when($request->filled('user_id'), fn($q) => $q->where('user_id', $request->user_id))
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
        $campaign    = Campaign::with('brand')->findOrFail($id);
        $refundData  = $this->calculateRefund($campaign);
        $refundEntry = CampaignRefund::where('campaign_id', $id)->latest()->first();

        return view('admin-views.campaign.refund-preview',
            compact('campaign', 'refundData', 'refundEntry'));
    }

    /**
     * Initiate a refund entry (status = pending) with a bank detail snapshot.
     * Admin transfers the money externally, then marks it complete via completeRefund.
     */
    public function processRefund(Request $request, int $id)
    {
        $campaign = Campaign::with('brand')->findOrFail($id);

        if ($campaign->status !== 'stopped') {
            return back()->with('error', 'Refund can only be initiated for stopped campaigns.');
        }

        if (CampaignRefund::where('campaign_id', $id)->exists()) {
            return back()->with('error', 'A refund entry already exists for this campaign.');
        }

        $refundData = $this->calculateRefund($campaign);
        $brand      = $campaign->brand;

        DB::transaction(function () use ($campaign, $refundData, $request, $brand) {
            CampaignRefund::create([
                'campaign_id'              => $campaign->id,
                'brand_id'                 => $campaign->brand_id,
                'calculated_amount'        => $refundData['refundable_amount'],
                'refunded_amount'          => null,
                'bank_account_number'      => $brand->bank_account_number,
                'bank_ifsc_code'           => $brand->bank_ifsc_code,
                'bank_account_holder_name' => $brand->bank_account_holder_name,
                'bank_account_type'        => $brand->bank_account_type,
                'status'                   => CampaignRefund::STATUS_PENDING,
                'admin_note'               => $request->input('admin_note'),
            ]);

            $campaign->refund_status = 'pending';
            $campaign->refund_note   = $request->input('admin_note');
            $campaign->save();

            Helpers::systemActivity('campaign_refund', auth()->user(), 'initiated',
                "Refund initiated (₹{$refundData['refundable_amount']}) for campaign: {$campaign->title}", $campaign);
        });

        return redirect()->route('admin.campaign.refund-preview', $id)
            ->with('success', 'Refund entry created. Transfer ₹' . number_format($refundData['refundable_amount'], 2) . ' to the brand bank account, then mark it complete.');
    }

    /**
     * Mark an existing pending refund entry as completed (money has been transferred).
     */
    public function completeRefund(Request $request, int $id, CampaignCreditNoteService $creditNoteService)
    {
        $campaign    = Campaign::findOrFail($id);
        $refundEntry = CampaignRefund::where('campaign_id', $id)
                        ->where('status', CampaignRefund::STATUS_PENDING)
                        ->firstOrFail();

        $refundData = $this->calculateRefund($campaign);

        DB::transaction(function () use ($campaign, $refundEntry, $request, $creditNoteService, $refundData) {
            $confirmedAmount = $request->filled('confirmed_amount')
                ? (float) $request->confirmed_amount
                : $refundEntry->calculated_amount;

            $refundEntry->status          = CampaignRefund::STATUS_COMPLETED;
            $refundEntry->refunded_amount  = $confirmedAmount;
            $refundEntry->admin_note       = $request->input('admin_note', $refundEntry->admin_note);
            $refundEntry->completed_at    = now();
            $refundEntry->save();

            $campaign->refund_status   = 'processed';
            $campaign->refunded_amount = $confirmedAmount;
            $campaign->save();

            $reason = $request->input('admin_note', $refundEntry->admin_note);
            $creditNoteService->issueForRefund($campaign, $refundEntry, $refundData, $reason);

            Helpers::systemActivity('campaign_refund', auth()->user(), 'completed',
                "Refund of ₹{$confirmedAmount} marked complete for campaign: {$campaign->title}", $campaign);
        });

        return redirect()->route('admin.campaign.show', $id)
            ->with('success', 'Refund marked as completed successfully.');
    }

    /**
     * Calculate the refundable amount for a campaign.
     *
     * Taxable paid = total_campaign_budget; GST paid = budget_with_gst − taxable.
     * Utilized taxable = utilized_slots × reward_per_user; utilized GST = taxable × gst%.
     * Reversal amounts = paid − utilized (per component).
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

        $gstPercentage  = (float) Helpers::get_business_settings('campaign_gst_percentage');
        if ($gstPercentage <= 0) {
            $gstPercentage = 18.0;
        }

        $rewardPerUser  = (float) ($campaign->reward_per_user ?? 0);
        $taxablePaid    = round((float) ($campaign->total_campaign_budget ?? 0), 2);
        $totalBudgetGst = round((float) ($campaign->compign_budget_with_gst ?? 0), 2);
        $gstPaid        = round(max(0, $totalBudgetGst - $taxablePaid), 2);

        $utilizedTaxable = round($utilizedSlots * $rewardPerUser, 2);
        $utilizedGst     = round($utilizedTaxable * $gstPercentage / 100, 2);
        $utilizedWithGst = round($utilizedTaxable + $utilizedGst, 2);

        $taxableReversal = round(max(0, $taxablePaid - $utilizedTaxable), 2);
        $gstReversal     = round(max(0, $gstPaid - $utilizedGst), 2);
        $cgstReversal    = round($gstReversal / 2, 2);
        $sgstReversal    = round($gstReversal - $cgstReversal, 2);
        $refundableAmount = round($taxableReversal + $gstReversal, 2);

        return [
            'utilized_slots'      => $utilizedSlots,
            'reward_per_user'     => $rewardPerUser,
            'gst_percentage'      => $gstPercentage,
            'taxable_paid'        => $taxablePaid,
            'gst_paid'            => $gstPaid,
            'utilized_raw'        => $utilizedTaxable,
            'utilized_taxable'    => $utilizedTaxable,
            'utilized_gst'        => $utilizedGst,
            'utilized_with_gst'   => $utilizedWithGst,
            'taxable_reversal'    => $taxableReversal,
            'gst_reversal'        => $gstReversal,
            'cgst_reversal'       => $cgstReversal,
            'sgst_reversal'       => $sgstReversal,
            'total_budget_gst'    => $totalBudgetGst,
            'refundable_amount'   => $refundableAmount,
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
