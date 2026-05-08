<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
// use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
// use Gregwar\Captcha\CaptchaBuilder;
use App\CPU\Helpers;
use Illuminate\Support\Facades\Session;
use App\Models\AdminNotification;
use App\Models\BusinessSetting;
use App\Models\Campaign;
use App\Models\CampaignTransaction;
use App\Models\CoinTransaction;
use App\Models\CoinWallet;
use App\Models\Seller;
use App\Models\User;
use App\Models\UserCampaignActivityLog;
use App\Models\Voucher;
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

        // Notification data for initial server-side render
        $notificationCounts    = $this->getNotificationTaskCounts();
        $recentNotifications   = AdminNotification::orderByDesc('created_at')->limit(10)->get();

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
            'statusSeries',
            'notificationCounts',
            'recentNotifications'
        ));
    }

    // ── Notification aggregator ───────────────────────────────────────────────

    /**
     * Return aggregated pending-task counts for all admin notification categories.
     */
    private function getNotificationTaskCounts(): array
    {
        $counts = [
            'brand_campaign_approval'   => Campaign::where('status', 'pending')->count(),
            'brand_gst_validation'      => Seller::whereIn('gst_status', ['Submitted', 'Under Verification'])->count(),
            'brand_new_registration'    => Seller::where('status', 'pending')->count(),
            'user_pan_verification'     => User::whereIn('pan_status', ['Submitted', 'Under Verification'])->count(),
            'user_aadhar_verification'  => User::whereIn('aadhar_status', ['Submitted', 'Under Verification'])->count(),
            'user_upi_payment_requests' => CoinTransaction::where('type', 'debit')->where('status', 'pending')->count(),
            'user_voucher_allocation'   => Voucher::where('status', 'pending')->count(),
        ];

        $counts['total_pending']   = array_sum($counts);
        $counts['unread_notifs']   = AdminNotification::where('is_read', false)->count();
        $counts['last_updated_at'] = now()->toIso8601String();

        return $counts;
    }

    /**
     * JSON endpoint: polled every 45 s by the dashboard frontend.
     */
    public function notificationCounts(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => true,
            'data'   => $this->getNotificationTaskCounts(),
        ]);
    }

    /**
     * JSON endpoint: recent admin notification feed (latest 20).
     */
    public function notificationFeed(): \Illuminate\Http\JsonResponse
    {
        $feed = AdminNotification::orderByDesc('created_at')
            ->limit(20)
            ->get()
            ->map(fn ($n) => [
                'id'         => $n->id,
                'type'       => $n->type,
                'title'      => $n->title,
                'message'    => $n->message,
                'link'       => $n->link,
                'icon'       => $n->icon,
                'color'      => $n->color,
                'is_read'    => $n->is_read,
                'created_at' => $n->created_at->diffForHumans(),
            ]);

        return response()->json(['status' => true, 'data' => $feed]);
    }

    /**
     * Mark notifications as read.
     * Pass `ids[]` array to mark specific ones; omit to mark all.
     */
    public function markNotificationsRead(Request $request): \Illuminate\Http\JsonResponse
    {
        $query = AdminNotification::where('is_read', false);

        if ($request->filled('ids')) {
            $query->whereIn('id', (array) $request->ids);
        }

        $count = $query->count();
        $query->update(['is_read' => true]);

        return response()->json([
            'status'  => true,
            'message' => "{$count} notification(s) marked as read.",
        ]);
    }
        // Sales Terms & Conditions
    public function sales_terms_condition()
    {
        $terms_condition = BusinessSetting::where('type', 'sales_terms_condition')->first();
        return view('admin-views.business-settings.sales-terms-condition', compact('terms_condition'));
    }

    public function sales_updateTermsCondition(Request $data)
    {
        $validatedData = $data->validate([
            'value' => 'required',
        ]);
        BusinessSetting::updateOrCreate(
            ['type' => 'sales_terms_condition'],
            ['value' => $data->value]
        );
        return redirect()->back();
    }

    // Sales Privacy Policy
    public function sales_privacy_policy()
    {
        $privacy_policy = BusinessSetting::where('type', 'sales_privacy_policy')->first();
        return view('admin-views.business-settings.sales-privacy-policy', compact('privacy_policy'));
    }

    public function sales_privacy_policy_update(Request $data)
    {
        $validatedData = $data->validate([
            'value' => 'required',
        ]);
        BusinessSetting::updateOrCreate(
            ['type' => 'sales_privacy_policy'],
            ['value' => $data->value]
        );
        return redirect()->back();
    }
    public function updateProfile(Request $request) {
        $admin = auth('admin')->user();
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->phone = $request->phone;

        if ($request->has('image')) {
            $admin->image = ImageManager::upload('profile/', 'png', $request->file('image'), $admin->image);
        }

        // Password update logic
        if ($request->filled('password')) {
            $request->validate([
                'old_password' => 'required',
                'password' => 'required|string|min:6|confirmed',
            ]);
            // Check old password
            if (!\Hash::check($request->old_password, $admin->password)) {
                return redirect()->back()->withErrors(['old_password' => 'Old password is incorrect.']);
            }
            $admin->password = bcrypt($request->password);
        }

        $admin->save();
        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    public function profile(Request $request) {
        $data = auth('admin')->user();
        return view('admin-views.profile.edit', compact('data'));
    }

    public function users(Request $request) {
        $customers = User::query()
            ->when($request->filled('id'), function ($query) use ($request) {
                $query->where('id', $request->id);
            })
            ->when($request->filled('name'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . trim($request->name) . '%');
            })
            ->when($request->filled('mobile'), function ($query) use ($request) {
                $query->where('mobile', 'like', '%' . trim($request->mobile) . '%');
            })
            ->when($request->filled('email'), function ($query) use ($request) {
                $query->where('email', 'like', '%' . trim($request->email) . '%');
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin-views.customer.list', compact('customers'));
    }

     public function viewUser(Request $request, $id) {
        $user = User::with(['coinWallet'])->withCount('campaigns')->findOrFail($id);
        return view('admin-views.customer.view-user', compact('user'));
    }
    public function editUser(Request $request, $id) {
        $user = User::find($id);
        return view('admin-views.customer.edit-customer', compact('user'));
    }

    public function userActivityLogs(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $logs = UserCampaignActivityLog::query()
            ->with(['campaign:id,title'])
            ->where('user_id', $user->id)
            ->when($request->filled('name'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . trim($request->name) . '%');
            })
            ->when($request->filled('campaigns_id'), function ($query) use ($request) {
                $query->where('campaigns_id', (int) $request->campaigns_id);
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin-views.customer.activity-logs', compact('user', 'logs'));
    }
   

    public function updateUser(Request $request) {
    try {
        $user = User::find($request->id);

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'phone' => 'required|digits:10|unique:users,mobile,'.$user->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'instagram_status' => 'nullable|in:not_submitted,pending,verified,not_verified',
            'facebook_status' => 'nullable|in:not_submitted,pending,verified,not_verified',
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
        $user->instagram_status = $request->instagram_status ?? $user->instagram_status;
        $user->facebook_status = $request->facebook_status ?? $user->facebook_status;
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
        $sellers = Seller::query()
            ->when($request->filled('id'), function ($query) use ($request) {
                $query->where('id', $request->id);
            })
            ->when($request->filled('name'), function ($query) use ($request) {
                $name = trim($request->name);
                $query->where(function ($q) use ($name) {
                    $q->where('username', 'like', "%{$name}%")
                        ->orWhere('f_name', 'like', "%{$name}%")
                        ->orWhere('l_name', 'like', "%{$name}%");
                });
            })
            ->when($request->filled('mobile'), function ($query) use ($request) {
                $query->where('phone', 'like', '%' . trim($request->mobile) . '%');
            })
            ->when($request->filled('email'), function ($query) use ($request) {
                $query->where('email', 'like', '%' . trim($request->email) . '%');
            })
            ->when($request->filled('registration_date'), function ($query) use ($request) {
                $query->whereDate('created_at', $request->registration_date);
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->orderBy('id', 'DESC')
            ->paginate(10)
            ->withQueryString();

        return view('admin-views.seller.index', compact('sellers'));
    }

    public function showBrand(Request $request, $id) {
        $seller = Seller::withCount('campaigns')->findOrFail($id);
        return view('admin-views.seller.view', compact('seller'));
    }

    public function updateBrand(Request $request, $id) {
        $seller = Seller::findOrFail($id);

        $request->validate([
            'status' => 'nullable|in:approved,pending',
            'instagram_status' => 'nullable|in:not_verified,pending,verified',
            'facebook_status' => 'nullable|in:not_verified,pending,verified',
            'gst_status' => 'nullable|string|in:Not Submitted,Submitted,Under Verification,Verified,Rejected',
            'pan_status' => 'nullable|string|in:Not Submitted,Submitted,Under Verification,Verified,Rejected',
        ]);

        if ($request->filled('status')) {
            $seller->status = $request->status;
        }

        if ($request->filled('instagram_status')) {
            $seller->instagram_status = $request->instagram_status;
        }

        if ($request->filled('facebook_status')) {
            $seller->facebook_status = $request->facebook_status;
        }

        if ($request->filled('gst_status')) {
            $seller->gst_status = $request->gst_status;
            if ($request->gst_status !== 'Rejected') {
                $seller->gst_rejection_reason = null;
            }
        }

        if ($request->filled('pan_status')) {
            $seller->pan_status = $request->pan_status;
            if ($request->pan_status !== 'Rejected') {
                $seller->pan_rejection_reason = null;
            }
        }

        $seller->save();

        return redirect()->back()->with('success', 'Brand user updated successfully.');
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
        DB::table('business_settings')->updateOrInsert(['type' => 'campaign_gst_percentage'], [
            'value' => $request->filled('campaign_gst_percentage') ? $request['campaign_gst_percentage'] : '18'
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

        // Social media links
        foreach (['social_facebook', 'social_twitter', 'social_instagram', 'social_youtube', 'social_linkedin'] as $key) {
            DB::table('business_settings')->updateOrInsert(['type' => $key], [
                'value' => $request->input($key, '')
            ]);
        }

        // Footer extras
        DB::table('business_settings')->updateOrInsert(['type' => 'footer_short_desc'], [
            'value' => $request->input('footer_short_desc', '')
        ]);
        DB::table('business_settings')->updateOrInsert(['type' => 'footer_copyright'], [
            'value' => $request->input('footer_copyright', '')
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

    public function campaign_guideline()
    {
        $campaign_guideline = BusinessSetting::where('type', 'campaign_guideline')->first();
        return view('admin-views.business-settings.campaign-guideline', compact('campaign_guideline'));
    }

    public function campaign_guideline_update(Request $request)
    {
        $request->validate([
            'value' => 'required',
        ]);

        \DB::table('business_settings')->updateOrInsert(
            ['type' => 'campaign_guideline'],
            ['value' => $request->value]
        );

        return redirect()->back();
    }

    public function userWallet(Request $request) {
        $transactions = $this->getFilteredUserWalletTransactions($request);
        return view('admin-views.customer.coin-transactions', compact('transactions'));
    }

    public function userWalletTransactions(Request $request) {
        $transactions = $this->getFilteredUserWalletTransactions($request);
        return view('admin-views.customer.coin-transactions', compact('transactions'));
    }

    private function getFilteredUserWalletTransactions(Request $request)
    {
        return CoinTransaction::with(['wallet.user'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = trim($request->search);
                $query->where(function ($q) use ($search) {
                    $q->where('transaction_id', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhereHas('wallet.user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->filled('type'), function ($query) use ($request) {
                $query->where('type', $request->type);
            })
            ->when($request->filled('transaction_type'), function ($query) use ($request) {
                $query->where('transaction_type', $request->transaction_type);
            })
            ->when($request->filled('date_from'), function ($query) use ($request) {
                $query->whereDate('created_at', '>=', $request->date_from);
            })
            ->when($request->filled('date_to'), function ($query) use ($request) {
                $query->whereDate('created_at', '<=', $request->date_to);
            })
            ->orderBy('id', 'DESC')
            ->paginate(25)
            ->withQueryString();
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

    public function approveWithdrawal(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:coin_transactions,id',
        ]);

        $transaction = CoinTransaction::with('wallet')->findOrFail($request->id);
        if ($transaction->type !== 'debit' || $transaction->status !== 'pending') {
            return response()->json([
                'status' => false,
                'message' => 'Only pending debit withdrawals can be approved.'
            ], 422);
        }

        $wallet = $transaction->wallet;
        if (!$wallet) {
            return response()->json([
                'status' => false,
                'message' => 'Wallet not found.'
            ], 404);
        }

        $transaction->status = 'completed';
        $transaction->save();

        return response()->json([
            'status' => true,
            'message' => 'Withdrawal approved successfully.'
        ]);
    }

}

