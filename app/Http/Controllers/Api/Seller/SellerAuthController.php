<?php

namespace App\Http\Controllers\Api\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\Banner;
use App\Models\BrandCategory;
use App\Models\Seller;
use App\Models\User;
use App\CPU\Helpers;
use App\CPU\ImageManager;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class SellerAuthController extends Controller
{
    //
    public function sendOtp(Request $request)
    {

        $rules = [
            'mobile' => 'required|digits:10',
            'type' => 'required|in:login,forgot_password,signup',
        ];

        if ($request->type === 'signup') {

            // Check if mobile exists in deleted accounts
            $deletedSeller = Seller::onlyTrashed()
                ->where('phone', $request->mobile)
                ->first();

            if ($deletedSeller) {
                return response()->json([
                    'status' => false,
                    'message' => 'This account was deleted. Please contact support.'
                ], 422);
            }

            $rules['mobile'] = 'required|unique:sellers,phone';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => Helpers::single_error_processor($validator)
            ], 422);
        }

        $otp = strval(rand(1000, 9999));
        $mobile = strval($request->mobile);
        $token = Str::random(50);

        // Store OTP in cache for verification (no DB columns needed).
        Cache::put("brand_otp_{$mobile}", $otp, now()->addMinutes(5));
        Cache::put("brand_otp_{$mobile}_{$request->type}", $otp, now()->addMinutes(5));
        if (in_array($request->type, ['login', 'forgot_password'])) {
            $user = Seller::where('phone', $request->mobile)->first();
            if (!$user) {
                $user = Seller::withTrashed()->where('phone', $request->mobile)->first();
                if ($user) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Account Deleted.Please contact support.',
                        'data' => [],
                        'otp' => $otp, // REMOVE in production
                        'mobile' => $mobile,
                        'otp_expires_at' => Carbon::now()->addMinutes(5)->toDateTimeString()
                    ], 404);
                }

                return response()->json([
                    'status' => false,
                    'message' => 'Mobile number not registered. Please sign up first.',
                    'data' => [],
                    'otp' => $otp, // REMOVE in production
                    'mobile' => $mobile,
                    'otp_expires_at' => Carbon::now()->addMinutes(5)->toDateTimeString()
                ], 404);
            }

            if ($user->status != 'approved') {
                return response()->json([
                    'status' => false,
                    'message' => 'Your account is not approved yet. Please wait for approval.',
                    'data' => [],
                    'otp' => $otp, // REMOVE in production
                    'mobile' => $mobile,
                    'otp_expires_at' => Carbon::now()->addMinutes(5)->toDateTimeString()
                ], 403);
            }

            $user->auth_token = $token;
            $user->save();

            Helpers::systemActivity('login_activity', $user, 'login', 'OTP sent to user & token generated', $user);
            // $token = $user->createToken('SellerToken')->accessToken;
        }

        //     $user = User::firstOrCreate(
        //         ['mobile' => $request->mobile],
        //     );

        //    if($user->role_id==1 || $user->role_id==2){
        //         return response()->json([
        //             'status' => false,
        //             'message' => 'You are not authorized to access this!',
        //         ]);
        //    }
        //     $user->otp = $otp;
        //     $user->otp_expires_at = Carbon::now()->addMinutes(5);
        //     $user->save();

        // 🔹 Here integrate SMS API (Fast2SMS / MSG91 etc)
        // For testing:
        return response()->json([
            'status' => true,
            'message' => 'OTP sent successfully',
            'data' => [],
            'otp' => $otp, // REMOVE in production
            'mobile' => $mobile,
            'token' => $token,
            'otp_expires_at' => Carbon::now()->addMinutes(5)->toDateTimeString()
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|digits:10',
            'otp' => 'required|digits:4',
            'type' => 'nullable|in:login,forgot_password,signup',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $mobile = (string) $request->mobile;
        $inputOtp = (string) $request->otp;

        $cachedOtp = null;
        if ($request->filled('type')) {
            $cachedOtp = Cache::get("brand_otp_{$mobile}_{$request->type}");
        }
        if (!$cachedOtp) {
            $cachedOtp = Cache::get("brand_otp_{$mobile}");
        }

        if (! $cachedOtp || (string) $cachedOtp !== $inputOtp) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid or expired OTP'
            ], 401);
        }

        // OTP verified, clear cache keys.
        Cache::forget("brand_otp_{$mobile}");
        Cache::forget("brand_otp_{$mobile}_login");
        Cache::forget("brand_otp_{$mobile}_forgot_password");
        Cache::forget("brand_otp_{$mobile}_signup");

        $seller = Seller::where('phone', $mobile)->first();
        if (! $seller) {
            return response()->json([
                'status' => true,
                'message' => 'OTP verified successfully',
                'token' => null,
                'data' => [
                    'mobile' => $mobile,
                    'is_registered' => false,
                ]
            ]);
        }

        if (empty($seller->auth_token) || strlen((string) $seller->auth_token) < 30) {
            $seller->auth_token = Str::random(50);
            $seller->save();
        }

        return response()->json([
            'status' => true,
            'message' => 'Login successful',
            'token' => $seller->auth_token,
            'data' => [
                'id' => $seller->id,
                'name' => trim(($seller->f_name ?? '') . ' ' . ($seller->l_name ?? '')),
                'mobile' => $seller->phone,
                'image' => $seller->image,
                'status' => $seller->status,
                'is_registered' => true,
            ]
        ]);
    }

    public function register(Request $request)
    {
        try {
            //code...

            // Registration logic here (if needed)
            // name:Sawan Shakya
            // mobile:8962272839
            // email:sawan@mailinator.com // Optional
            // dob:2000-07-20
            // gender:male
            // profession:Digital Marketting Manager
            // referral_code: // Optional
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
            $token = Str::random(50);
            $pan_image = null;

            if ($request->hasFile('pan_image')) {
                $pan_image = ImageManager::upload('profile/', 'png', $request->file('pan_image'));
            }

            $panSubmitted = $request->filled('pan_number') || $pan_image !== null;
            $gstSubmitted = $request->filled('gst_number');

            $user = Seller::create([
                'f_name' => $request->f_name,
                'l_name' => $request->l_name,
                'username' => $request->username,
                'phone' => $request->mobile,
                'email' => $request->email,
                'referral_code' => Helpers::generate_referral_code(),
                'friends_code' => $request->referral_code ?? '',
                'city' => $request->city,
                'state' => $request->state,
                'instagram_username' => $request->instagram_username,
                'facebook_username' => $request->facebook_username,
                'auth_token' => $token,
                'category_id' => $request->category_id ?? NULL,
                'sub_category_id' => $request->sub_category_id ?? NULL,
                'gst_number' => $request->gst_number ?? '',
                'gst_status' => $gstSubmitted ? 'Submitted' : 'Not Submitted',
                'business_registeration_type' => $request->business_registeration_type ?? 'Proprietor',
                'pan_number' => $request->pan_number ?? NULL,
                'pan_image' => $pan_image ?? NULL,
                'pan_status' => $panSubmitted ? 'Submitted' : 'Not Submitted',
                'primary_contact' => $request->primary_contact ?? NULL,
                'alternate_contact' => $request->alternate_contact ?? NULL,
                'full_address' => $request->full_address ?? NULL,
                'google_map_link' => $request->google_map_link ?? NULL,
                'website_link' => $request->website_link ?? NULL
            ]);

            Helpers::systemActivity('new_register', $user, 'signup', 'New user registered', $user);
            if (User::where('referral_code', $request->referral_code)->first()) {

            }

            return response()->json([
                'status' => true,
                'message' => 'Registration successful. Please login to continue.',
                'token' => $token
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
                'token' => ''
            ]);
        }
    }

    public function config()
    {
        $data = [
            'is_mandatory_update' => true,
            'minimum_coin_withdrawl' => strval(100),
            'upi_value' => strval(0.1),
            'voucher_value' => strval(0.5),
            'post_footer_content' => "Follow us @rexarix_official",
            'company_name' => Helpers::get_business_settings('company_name'),
            'company_phone' => Helpers::get_business_settings('company_phone'),
            'company_email' => Helpers::get_business_settings('company_email'),
            'about_us' => Helpers::get_business_settings('about_us'),
            'privacy_policy' => Helpers::get_business_settings('privacy_policy'),
            'terms_nd_conditions' => Helpers::get_business_settings('terms_condition'),
        ];
        return response()->json([
            'status' => true,
            'message' => 'Config retrieved successfully',
            'data' => $data
        ]);
    }


    public function banners()
    {
        $banners = Banner::where('status', 1)->latest()->get();
        return response()->json([
            'status' => true,
            'message' => 'Banners retrieved successfully',
            'data' => $banners
        ]);
    }

    public function brandCategoryList()
    {
        $categories = BrandCategory::with(['childes'])
            ->where('status', 1)
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Brand Category retrieved successfully',
            'data' => $categories
        ]);
    }

    public function sendNotification(Request $request)
    {
        $token = $request->input('fcm_id');
        $title = $request->input('title') ?? 'Eatoz Developer';
        $body = $request->input('body') ?? 'This is test notification by eatoz developer';

        $data = [
            'title' => ('order'),
            'description' => 'You received new order',
            'order_id' => 1,
            'image' => '',
            'link' => ''
        ];
        Helpers::send_push_notif_to_device($token, $data);

        try {
            return response()->json(['message' => 'Notification sent successfully', 'result' => []]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
