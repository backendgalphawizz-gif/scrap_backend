<?php

namespace App\Http\Controllers\Api\Sale;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\Sale;
use App\CPU\Helpers;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function sendOtp(Request $request)
    {

        $rules = [
            'mobile' => 'required|digits:10',
            // 'type' => 'required|in:login,forgot_password,signup',
        ];

        if ($request->type === 'signup') {
            $rules['mobile'] = 'required|unique:sales,phone';
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

        $user = Sale::where('mobile', $request->mobile)->first();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Mobile number not registered. Please sign up first.',
                'data' => [],
                'otp' => $otp, // REMOVE in production
                'mobile' => $mobile,
                'otp_expires_at' => Carbon::now()->addMinutes(5)->toDateTimeString()
            ], 404);
        }

        if($user->status != 'active') {
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
        // $token = $user->createToken('SellerToken')->accessToken;

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

    public function login(Request $request) {
        $rules = [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => Helpers::single_error_processor($validator)
            ], 422);
        }

        $user = Sale::where('email', $request->email)->first();

        if (!$user || !password_verify($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid email or password',
            ], 401);
        }

        if ($user->status != 'active') {
            return response()->json([
                'status' => false,
                'message' => 'You account is inactive, please contact admin.',
            ], 403);
        }

        $token = Str::random(50);
        $user->auth_token = $token;
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $rules = [
            'email' => 'required|email',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => Helpers::single_error_processor($validator)
            ], 422);
        }

        $user = Sale::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Email not registered.',
            ], 404);
        }

        $otp = strval(rand(1000, 9999));
        $token = Str::random(50);

        $user->reset_token = $token;
        $user->reset_otp = $otp;
        $user->reset_otp_expires_at = Carbon::now()->addMinutes(5);
        $user->save();

        // 🔹 Integrate SMS API to send OTP
        return response()->json([
            'status' => true,
            'message' => 'OTP sent to your email',
            'token' => $token,
            'otp' => $otp, // REMOVE in production
            'otp_expires_at' => Carbon::now()->addMinutes(5)->toDateTimeString()
        ]);
    }

    public function resetPassword(Request $request)
    {
        $rules = [
            'token' => 'required',
            'otp' => 'required|digits:4',
            'password' => 'required|min:6',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => Helpers::single_error_processor($validator)
            ], 422);
        }

        $user = Sale::where('reset_token', $request->token)->first();

        if (!$user || $user->reset_otp !== $request->otp) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid token or OTP',
            ], 401);
        }

        if (Carbon::now()->greaterThan($user->reset_otp_expires_at)) {
            return response()->json([
                'status' => false,
                'message' => 'OTP has expired',
            ], 401);
        }

        $user->password = bcrypt($request->password);
        $user->reset_token = null;
        $user->reset_otp = null;
        $user->reset_otp_expires_at = null;
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Password reset successfully',
        ]);
    }

}
