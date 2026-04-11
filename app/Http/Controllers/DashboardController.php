<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
// use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
// use Gregwar\Captcha\CaptchaBuilder;
use App\CPU\Helpers;
use Illuminate\Support\Facades\Session;
use App\Models\BusinessSetting;
use App\Models\Campaign;
use App\Models\CampaignTransaction;
use App\Models\CoinTransaction;
use App\Models\CoinWallet;
use App\Models\Seller;
use App\Models\User;
use App\Http\Resources\CommonResource;
use DB;
use App\CPU\ImageManager;
use Carbon\Carbon;
// use Gregwar\Captcha\PhraseBuilder;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userCount = User::count();
        $brandCount = Seller::count();
        $campaignCount = Campaign::count();
        $liveCampaignCount = Campaign::where('status', 'active')->count();
        $totalCampaignBudget = Campaign::sum('total_campaign_budget');
        $totalCampaignBudgetSpent = Campaign::where('status', 'completed')->sum('total_campaign_budget');
        $totalCampaignparticipants = CampaignTransaction::count();

        $trendLabels = [];
        $campaignTrend = [];
        $participantTrend = [];

        for ($i = 5; $i >= 0; $i--) {
            $monthDate = Carbon::now()->subMonths($i);
            $start = $monthDate->copy()->startOfMonth();
            $end = $monthDate->copy()->endOfMonth();

            $trendLabels[] = $monthDate->format('M Y');
            $campaignTrend[] = Campaign::whereBetween('created_at', [$start, $end])->count();
            $participantTrend[] = CampaignTransaction::whereBetween('created_at', [$start, $end])->count();
        }

        $statusKeys = ['active', 'completed', 'inactive'];
        $statusLabels = ['Active', 'Completed', 'Inactive'];
        $statusSeries = [];

        foreach ($statusKeys as $status) {
            $statusSeries[] = Campaign::where('status', $status)->count();
        }


        $campaigns = Campaign::with(['brand'])->where('start_date', '<=', now())->where('end_date', '>=', now())->orderBy('id', 'DESC')->limit(5)->get();
        return view('admin-views.system.dashboard', compact(
            'totalCampaignparticipants',
            'totalCampaignBudget',
            'totalCampaignBudgetSpent',
            'liveCampaignCount',
            'userCount',
            'brandCount',
            'campaignCount',
            'campaigns',
            'trendLabels',
            'campaignTrend',
            'participantTrend',
            'statusLabels',
            'statusSeries'
        ));
    }

    public function updateProfile(Request $request) {
        $admin = auth('admin')->user();
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->phone = $request->phone;
        
        if ($request->has('image')) {
            $admin->image = ImageManager::upload('profile/', 'png', $request->file('image'), $admin->image);
        }

        if ($request->password) {
            $admin->password = bcrypt($request->password);
        }
        $admin->save();
        return redirect()->back();
    }

    public function profile(Request $request) {
        $data = auth('admin')->user();
        return view('admin-views.profile.edit', compact('data'));
    }

    public function users(Request $request) {
        $customers = User::latest()->paginate(10);
        return view('admin-views.customer.list', compact('customers'));
    }

    public function viewUser(Request $request, $id) {
        $user = User::find($id);
        return view('admin-views.customer.edit-customer', compact('user'));
    }

    // public function updateUser(Request $request) {
    //     $user = User::find($request->id);

    //     $request->validate([
    //         'name' => 'required',
    //         'email' => 'required|email|unique:users,email,'.$user->id,
    //         'phone' => 'required|digits:10|unique:users,mobile,'.$user->id,
    //     ]);

    //     $request->validate([
    //         'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    //     ]);

    //     $request->has('image') && $user->image = ImageManager::upload('profile/', 'png', $request->file('image'), $user->image);
    //     $user->dob = $request->dob;
    //     $user->gender = $request->gender;
    //     $user->profession = $request->profession;
    //     $user->instagram_username = $request->instagram_username;
    //     $user->facebook_username = $request->facebook_username;
    //     $user->name = $request->name;
    //     $user->email = $request->email;
    //     $user->mobile = $request->phone;
    //     $user->save();
    //     return redirect()->back();
    // }

    public function updateUser(Request $request) {
    try {
        $user = User::find($request->id);

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'phone' => 'required|digits:10|unique:users,mobile,'.$user->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'upi_status' => 'nullable|string|in:Not Submitted,Submitted,Under Verification,Verified,Rejected',
            'bank_status' => 'nullable|string|in:Not Submitted,Submitted,Under Verification,Verified,Rejected',
            'pan_status' => 'nullable|string|in:Not Submitted,Submitted,Under Verification,Verified,Rejected',
            'aadhar_status' => 'nullable|string|in:Not Submitted,Submitted,Under Verification,Verified,Rejected',
            'upi_reason' => 'nullable|string|max:255',
            'bank_reason' => 'nullable|string|max:255',
            'pan_reason' => 'nullable|string|max:255',
            'aadhar_reason' => 'nullable|string|max:255',
        ]);

        $request->has('image') && $user->image = ImageManager::upload('profile/', 'png', $request->file('image'), $user->image);
        $user->dob = $request->dob;
        $user->gender = $request->gender;
        $user->profession = $request->profession;
        $user->instagram_username = $request->instagram_username;
        $user->facebook_username = $request->facebook_username;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile = $request->phone;

        // KYC fields from admin edit user page
        $user->upi_status = $request->upi_status;
        $user->bank_status = $request->bank_status;
        $user->pan_status = $request->pan_status;
        $user->aadhar_status = $request->aadhar_status;
        $user->upi_rejection_reason = $request->upi_status === 'Rejected' ? $request->upi_reason : null;
        $user->bank_rejection_reason = $request->bank_status === 'Rejected' ? $request->bank_reason : null;
        $user->pan_rejection_reason = $request->pan_status === 'Rejected' ? $request->pan_reason : null;
        $user->aadhar_rejection_reason = $request->aadhar_status === 'Rejected' ? $request->aadhar_reason : null;

        $user->save();

        // ✅ Success message
        return redirect()->back()->with('success', 'User updated successfully!');
    } catch (\Exception $e) {
        // ❌ Error message
        return redirect()->back()->with('error', 'Something went wrong: '.$e->getMessage());
    }
}
    public function brands(Request $request) {
        $sellers = Seller::orderBy('id', 'DESC')->paginate(25);
        return view('admin-views.seller.index', compact('sellers'));
    }

    public function showBrand(Request $request, $id) {
        $seller = Seller::find($id);
        return view('admin-views.seller.view', compact('seller'));
    }

    public function updateBrand(Request $request, $id) {
        $seller = Seller::find($id);
        $seller->status = $request->status;
        $seller->save();
        return redirect()->back();
    }
    
    public function settings(Request $request) {
        $businessSettings = BusinessSetting::get();
        return view('admin-views.business-settings.website-info', compact('businessSettings'));
    }

    public function terms_condition()
    {
        $terms_condition = BusinessSetting::where('type', 'terms_condition')->first();
        return view('admin-views.business-settings.terms-condition', compact('terms_condition'));
    }

    public function updateTermsCondition(Request $data)
    {
        $validatedData = $data->validate([
            'value' => 'required',
        ]);
        BusinessSetting::where('type', 'terms_condition')->update(['value' => $data->value]);
        // Toastr::success('Terms and Condition Updated successfully!');
        return redirect()->back();
    }

    public function privacy_policy()
    {
        $privacy_policy = BusinessSetting::where('type', 'privacy_policy')->first();
        return view('admin-views.business-settings.privacy-policy', compact('privacy_policy'));
    }

    public function privacy_policy_update(Request $data)
    {
        $validatedData = $data->validate([
            'value' => 'required',
        ]);
        BusinessSetting::where('type', 'privacy_policy')->update(['value' => $data->value]);
        // Toastr::success('Privacy policy Updated successfully!');
        return redirect()->back();
    }

    public function updateInfo(Request $request)
    {

        //comapy shop banner
        $imgBanner = BusinessSetting::where(['type' => 'shop_banner'])->first();
        if ($request->has('shop_banner')) {
            $imgBanner = ImageManager::update('shop/', $imgBanner, 'png', $request->file('shop_banner'));
            DB::table('business_settings')->updateOrInsert(['type' => 'shop_banner'], [
                'value' => $imgBanner
            ]);
        }
        // comapny name
        DB::table('business_settings')->updateOrInsert(['type' => 'company_name'], [
            'value' => $request['company_name']
        ]);
        
        DB::table('business_settings')->updateOrInsert(['type' => 'tds_percent'], [
            'value' => $request['tds_percent']
        ]);
        // company email
        DB::table('business_settings')->updateOrInsert(['type' => 'company_email'], [
            'value' => $request['company_email']
        ]);
        // company Phone
        DB::table('business_settings')->updateOrInsert(['type' => 'company_phone'], [
            'value' => $request['company_phone']
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'timezone'], [
            'value' => $request['timezone']
        ]);
        
        DB::table('business_settings')->updateOrInsert(['type' => 'minimum_coin_withdrawl'], [
            'value' => $request['minimum_coin_withdrawl']
        ]);
        DB::table('business_settings')->updateOrInsert(['type' => 'sale_post_commission'], [
            'value' => $request['sale_post_commission']
        ]);
        DB::table('business_settings')->updateOrInsert(['type' => 'sale_brand_commission'], [
            'value' => $request['sale_brand_commission']
        ]);
        DB::table('business_settings')->updateOrInsert(['type' => 'upi_value'], [
            'value' => $request['upi_value']
        ]);
        DB::table('business_settings')->updateOrInsert(['type' => 'voucher_value'], [
            'value' => $request['voucher_value']
        ]);
        DB::table('business_settings')->updateOrInsert(['type' => 'post_footer_content'], [
            'value' => $request['post_footer_content']
        ]);
        DB::table('business_settings')->updateOrInsert(['type' => 'minimum_wallet_balance'], [
            'value' => $request['minimum_wallet_balance']
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'kyc_amount'], [
            'value' => $request['kyc_amount']
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'max_posts_per_user'], [
            'value' => $request['max_posts_per_user']
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'brand_wise_posting_limits'], [
            'value' => strtoupper($request['brand_wise_posting_limits']) // always uppercase
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'cost_per_post'], [
            'value' => $request['cost_per_post']
        ]);

        DB::table('business_settings')->updateOrInsert(['type' => 'brand_max_campaigns_per_timeframe'], [
            'value' => $request['brand_max_campaigns_per_timeframe'] ?? '0',
        ]);
        DB::table('business_settings')->updateOrInsert(['type' => 'brand_campaign_creation_timeframe_hours'], [
            'value' => $request['brand_campaign_creation_timeframe_hours'] ?? '24',
        ]);
        DB::table('business_settings')->updateOrInsert(['type' => 'post_sharing_reward'], [
            'value' => $request['post_sharing_reward']
        ]);
        DB::table('business_settings')->updateOrInsert(['type' => 'feedback_incentive'], [
            'value' => $request['feedback_incentive']
        ]);
        DB::table('business_settings')->updateOrInsert(['type' => 'platform_commission'], [
            'value' => $request['platform_commission']
        ]);

        // web logo
        $webLogo = BusinessSetting::where(['type' => 'company_web_logo'])->first();
        if ($request->has('company_web_logo')) {
            try {
                //code...
                $webLogo = ImageManager::upload('company/', 'png', $request->file('company_web_logo'));
                BusinessSetting::where(['type' => 'company_web_logo'])->update([
                    'value' => $webLogo,
                ]);
            } catch (\Throwable $th) {
                //throw $th;
            }
        }

        return back();
    }

    public function updateUserWalletStatus(Request $request)
    {
        $user = User::find($request->id);
        if ($user && $user->coinWallet) {
            $user->coinWallet->status = !$user->coinWallet->status; // toggle status
            $user->coinWallet->save();
            return response()->json([
                'status' => true,
                'message' => 'Wallet status updated successfully',
                'data' => new CommonResource($user->coinWallet)
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'User or wallet not found',
            'data' => []
        ]);
    }

    public function updateUserWalletWithdrawalFreeze(Request $request)
    {
        $user = User::find($request->id);
        if (! $user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found',
                'data' => [],
            ]);
        }

        $wallet = CoinWallet::firstOrCreate(
            ['user_id' => $user->id],
            ['balance' => 0]
        );

        $wallet->withdrawal_frozen = ! $wallet->withdrawal_frozen;
        $wallet->save();

        return response()->json([
            'status' => true,
            'message' => $wallet->withdrawal_frozen
                ? 'Withdrawals frozen for this wallet'
                : 'Withdrawals enabled for this wallet',
            'data' => new CommonResource($wallet->fresh()),
        ]);
    }

    public function updateUserStatus(Request $request)
    {
        $user = User::find($request->id);
        if ($user) {
            $user->status = !$user->status; // toggle status
             $user->save();
            return response()->json([
                'status' => true,
                'message' => 'User status updated successfully',
                'data' => new CommonResource($user)
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'User not found',
            'data' => []
        ]);
    }

    public function deleteUser(Request $request, $id)
    {
        $user = User::find($request->id);
        if ($user) {
            $user->delete();
            return redirect()->back()->with('success', 'User deleted successfully');
        }
        return redirect()->back()->with('success', 'Something went wrong');
    }

    public function logout(Request $request) {
        auth('admin')->logout();
        // Toastr::success('Logged out successfully!');
        return redirect()->route('admin.login');
    }

    public function updateSellerStatus(Request $request)
    {
        $seller = Seller::find($request->id);
        if ($seller) {
            $seller->status = $seller->status == 'approved' ? 'pending' : 'approved'; // toggle status
            $seller->save();
            return response()->json([
                'status' => true,
                'message' => 'Seller status updated successfully',
                'data' => new CommonResource($seller)
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'Seller not found',
            'data' => []
        ]);
    }

    public function brand_terms_condition()
    {
        $terms_condition = BusinessSetting::where('type', 'brand_terms_condition')->first();
        return view('admin-views.business-settings.brand-terms-condition', compact('terms_condition'));
    }

    public function brand_updateTermsCondition(Request $data)
    {
        $validatedData = $data->validate([
            'value' => 'required',
        ]);
        BusinessSetting::where('type', 'brand_terms_condition')->update(['value' => $data->value]);
        // Toastr::success('Terms and Condition Updated successfully!');
        return redirect()->back();
    }

    public function brand_privacy_policy()
    {
        $privacy_policy = BusinessSetting::where('type', 'brand_privacy_policy')->first();
        return view('admin-views.business-settings.brand-privacy-policy', compact('privacy_policy'));
    }

    public function brand_privacy_policy_update(Request $data)
    {
        $validatedData = $data->validate([
            'value' => 'required',
        ]);
        BusinessSetting::where('type', 'brand_privacy_policy')->update(['value' => $data->value]);
        // Toastr::success('Privacy policy Updated successfully!');
        return redirect()->back();
    }

    public function userWallet(Request $request) {
        $transactions = CoinTransaction::with(['wallet.user'])->orderBy('id', 'DESC')->paginate(25);
        return view('admin-views.customer.coin-transactions', compact('transactions'));
    }

    public function userWalletTransactions(Request $request) {
        $transactions = CoinTransaction::with(['wallet.user'])->paginate(25);
        return view('admin-views.customer.coin-transactions', compact('transactions'));
    }

    public function rolesNdPermission(Request $request) {
        $roles = [];
        return view('admin-views.sale-roles.index', compact('roles'));
    }
    
    public function storeRolesNdPermission(Request $request) {

    }

    public function popupBanner(Request $request)
    {
        $popupBanner = \App\CPU\Helpers::get_business_settings('popup_banner');
        if ($popupBanner && is_string($popupBanner)) {
            $popupBanner = json_decode($popupBanner, true);
        }
        return view('admin-views.business-settings.popup-banner', compact('popupBanner'));
    }

    public function popupBannerUpdate(Request $request)
    {
        try {
            $existing = \App\CPU\Helpers::get_business_settings('popup_banner');
            $existingData = [];
            if (is_string($existing)) {
                $decoded = json_decode($existing, true);
                $existingData = is_array($decoded) ? $decoded : [];
            } elseif (is_array($existing)) {
                $existingData = $existing;
            } elseif (is_object($existing)) {
                $existingData = (array) $existing;
            }

            $popupBannerData = [
                'status' => $request->has('status') ? 1 : 0,
                'title' => $request->title ?? '',
                'description' => $request->description ?? '',
                'image' => $existingData['image'] ?? null,
            ];

            // Handle image upload
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                 $image = ImageManager::upload('popup_banner/', 'png', $request->file('image'));
                $popupBannerData['image'] = $image;
            }

            // Store in database
            BusinessSetting::where('type', 'popup_banner')->updateOrInsert(
                ['type' => 'popup_banner'],
                ['value' => json_encode($popupBannerData)]
            );

            return redirect()->back()->with('success', 'Popup Banner settings updated successfully!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Error updating popup banner settings: ' . $th->getMessage());
        }
    }

}

