<?php

namespace App\Http\Controllers;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CampaignTransaction;
use App\Models\Seller;
use App\Models\PaymentSplit;
// use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

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
        $guidelineOptions = $this->getCampaignGuidelineOptions();
        return view('admin-views.campaign.add', compact('sellers', 'guidelineOptions'));
    }

    public function store(Request $request)
    {
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
        $campaign->title = $request->caption;
        $campaign->descriptions = $request->caption;
        $campaign->tags = $request->hashtags;
        $campaign->share_on = $request->social_media ? implode(',', $request->social_media) : '';
        $campaign->start_date = $request->start_date;
        $campaign->end_date = $request->end_date;
        $campaign->gender = $request->gender;
        $campaign->state = $request->state;
        $campaign->city = $request->city;
        $campaign->guidelines = implode('|', $request->input('guidelines', []));
        $campaign->coins = $request->reward_per_user;

        $campaign->total_user_required = $request->total_user_required;
        $campaign->reward_per_user = $request->reward_per_user;
        // $campaign->reward_per_post = $request->reward_per_post;
        $campaign->number_of_post = $request->number_of_post;
        $campaign->daily_budget_cap = $request->daily_budget_cap;
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
        $guidelineOptions = $this->getCampaignGuidelineOptions();
        return view('admin-views.campaign.edit', compact('campaign', 'sellers', 'guidelineOptions'));
    }

    public function update(Request $request, $id)
    {
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
        $campaign->title = $request->caption;
        $campaign->descriptions = $request->caption;
        $campaign->tags = $request->hashtags;
        $campaign->share_on = $request->social_media ? implode(',', $request->social_media) : '';
        $campaign->start_date = $request->start_date;
        $campaign->end_date = $request->end_date;
        $campaign->gender = $request->gender;
        $campaign->state = $request->state;
        $campaign->city = $request->city;
        $campaign->guidelines = implode('|', $request->input('guidelines', []));
        $campaign->coins = $request->reward_per_user;
        $campaign->total_user_required = $request->total_user_required;
        $campaign->reward_per_user = $request->reward_per_user;
        $campaign->number_of_post = $request->number_of_post;
        $campaign->daily_budget_cap = $request->daily_budget_cap;
        $campaign->total_campaign_budget = $request->total_campaign_budget;
        $campaign->age_range = $ageRange;
        $campaign->save();

        return redirect()->route('admin.campaign.list');
        
    }

    public function delete(Request $request)
    {
        $br = Campaign::find($request->id);
        ImageManager::delete('/profile/' . $br['thumbnail']);
        Campaign::where('id', $request->id)->delete();
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
