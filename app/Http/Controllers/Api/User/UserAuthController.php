<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\Banner;
use App\Models\BrandCategory;
use App\Models\Profession;
use App\Models\User;
use App\CPU\Helpers;

class UserAuthController extends Controller
{
    //
    public function sendOtp(Request $request)
    {

        $rules = [
            'mobile' => 'required|digits:10',
            'type' => 'required|in:login,forgot_password,signup',
        ];

        if ($request->type === 'signup') {

            $deletedUser = User::onlyTrashed()
                            ->where('mobile', $request->mobile)
                            ->first();

            if ($deletedUser) {
                return response()->json([
                    'status' => false,
                    'message' => 'This account was deleted. Please contact support.'
                ], 422);
            }

            $rules['mobile'] = 'required|unique:users,mobile';
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
        $otpExpiresAt = Carbon::now()->addMinutes(5);
        $token = "";
        if (in_array($request->type, ['login', 'forgot_password'])) {
            $user = User::where('mobile', $request->mobile)->first();
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Mobile number not registered. Please sign up first.',
                    'data' => [],
                    'otp' => $otp, // REMOVE in production
                    'mobile' => $mobile,
                    'otp_expires_at' => $otpExpiresAt->toDateTimeString()
                ], 404);
            }

            $user->otp = $otp;
            $user->otp_expires_at = $otpExpiresAt;
            $user->fcm_id = $request->fcm_id;
            $user->device_type = $request->device_type;
            $user->unique_code = 'RX-' . $user->id;
            $user->save();

            $token = $user->createToken('UserToken')->accessToken;
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
            'otp_expires_at' => $otpExpiresAt->toDateTimeString()
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|digits:10',
            'otp'    => 'required|digits:4',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::with('role:id,name')->select('id','name','role_id','image','mobile')->where('mobile', $request->mobile)
                    ->where('otp', $request->otp)
                    ->where('otp_expires_at', '>=', now())
                    ->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid or expired OTP'
            ], 401);
        }

        $user->otp = null;
        $user->otp_expires_at = null;
        // $user->fcm_id = $request->fcm_id??null;
        $user->save();

        $token =  $user->createToken('AdminToken')->accessToken;

        return response()->json([
            'status' => true,
            'message' => 'Login successful',
            'token' => $token,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'mobile' => $user->mobile,
                'image' => $user->image,
                'role_id' => $user->role_id,
                'status' => $user->status==1?'Active':'Suspended',
                'role'=> $user->role
            ]
        ]);
    }

    public function register(Request $request)
    {
        try {
           
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'mobile' => 'required|digits:10|unique:users,mobile',
                'email' => 'nullable|email|unique:users,email',
                'dob' => 'required|date',
                'gender' => 'nullable|in:male,female,other',
                'profession' => 'nullable|string|max:255',
                'referral_code' => 'nullable|string|max:50'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => Helpers::single_error_processor($validator)
                ], 422);
            }

            $user = User::create([
                'name' => $request->name,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'dob' => $request->dob,
                'gender' => $request->gender ?? 'male',
                'profession' => $request->profession,
                'referral_code' => Helpers::generate_referral_code(),
                'friends_code' => $request->referral_code ?? '',
                'role_id' => 1, // Default to Supervisor or any role you want   
                'city' => $request->city,
                'state' => $request->state,
                'post_slots' => '10', // Default post slots, can be updated later based on user level
                'instagram_username' => $request->instagram_username,
                'instagram_status' => $request->instagram_username ? 'pending' : 'not_submitted',
                'facebook_username' => $request->facebook_username,
                'facebook_status' => $request->facebook_username ? 'pending' : 'not_submitted',
            ]);

            $user->fcm_id = $request->fcm_id;
            $user->device_type = $request->device_type;
            $user->save();

            if(User::where('referral_code', $request->referral_code)->first()) {

            }

            return response()->json([
                'status' => true,
                'message' => 'Registration successful. Please login to continue.',
                'token' => $user->createToken('UserToken')->accessToken
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
            'professions' => Profession::where('status', 1)->get(),
            'minimum_coin_withdrawl' => strval(Helpers::get_business_settings('minimum_coin_withdrawl')),
            'upi_value' => strval(Helpers::get_business_settings('upi_value')),
            'voucher_value' => strval(Helpers::get_business_settings('voucher_value')),
            'post_footer_content' => Helpers::get_business_settings('post_footer_content'),
            'company_name' => Helpers::get_business_settings('company_name'),
            'company_phone' => Helpers::get_business_settings('company_phone'),
            'company_email' => Helpers::get_business_settings('company_email'),
            'about_us' => Helpers::get_business_settings('about_us'),
            'privacy_policy' => Helpers::get_business_settings('privacy_policy'),
            'terms_nd_conditions' => Helpers::get_business_settings('terms_condition'),
            'brand_privacy_policy' => Helpers::get_business_settings('privacy_policy'),
            'brand_terms_nd_conditions' => Helpers::get_business_settings('terms_condition'),

            'kyc_amount' => Helpers::get_business_settings('kyc_amount'),
            'max_posts_per_user' => Helpers::get_business_settings('max_posts_per_user'),
            'brand_wise_posting_limits' => Helpers::get_business_settings('brand_wise_posting_limits'),
            'cost_per_post' => Helpers::get_business_settings('cost_per_post'),
            'post_sharing_reward' => Helpers::get_business_settings('post_sharing_reward'),
            'feedback_incentive' => Helpers::get_business_settings('feedback_incentive'),
            'platform_commission' => Helpers::get_business_settings('platform_commission'),
            'tds_percent' => Helpers::get_business_settings('tds_percent'),
            'sale_post_commission' => Helpers::get_business_settings('sale_post_commission'),
            'sale_brand_commission' => Helpers::get_business_settings('sale_brand_commission'),
            'minimum_wallet_balance' => Helpers::get_business_settings('minimum_wallet_balance'),
            'campaign_gst_percentage' => Helpers::get_business_settings('campaign_gst_percentage'), 


            'brand_faq' => [
                [
                    'question' => 'How to use the app?',
                    'answer' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'
                ],
                [
                    'question' => 'How to earn coins?',
                    'answer' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'
                ],
                [
                    'question' => 'How to withdraw coins?',
                    'answer' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'
                ]
            ],
            'faq' => [
                [
                    'question' => 'How to use the app?',
                    'answer' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'
                ],
                [
                    'question' => 'How to earn coins?',
                    'answer' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'
                ],
                [
                    'question' => 'How to withdraw coins?',
                    'answer' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'
                ]
            ],
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

    public function popupBanner()
    {
        $popupBanner = Helpers::get_business_settings('popup_banner');

        if (is_string($popupBanner)) {
            $decoded = json_decode($popupBanner, true);
            $popupBanner = is_array($decoded) ? $decoded : [];
        }

        if (!is_array($popupBanner)) {
            $popupBanner = [];
        }

        $image = $popupBanner['image'] ?? null;
        $imageUrl = null;

        if (!empty($image)) {
            if (filter_var($image, FILTER_VALIDATE_URL)) {
                $imageUrl = $image;
            } elseif (str_starts_with($image, '/')) {
                $imageUrl = asset(ltrim($image, '/'));
            } else {
                $imageUrl = asset('storage/popup_banner/' . ltrim($image, '/'));
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Popup banner retrieved successfully',
            'data' => [
                'status' => (int) ($popupBanner['status'] ?? 0),
                'title' => $popupBanner['title'] ?? '',
                'description' => $popupBanner['description'] ?? '',
                'image' => $imageUrl,
            ]
        ]);
    }

    public function professions()
    {
        $banners = Profession::where('status', 1)->latest()->get();
        return response()->json([
            'status' => true,
            'message' => 'Professions retrieved successfully',
            'data' => $banners
        ]);
    }

    public function categories()
    {
        $banners = BrandCategory::with(['childes'])->where('status', 1)->latest()->get();
        return response()->json([
            'status' => true,
            'message' => 'Brand Category retrieved successfully',
            'data' => $banners
        ]);
    }

}
