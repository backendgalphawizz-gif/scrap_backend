<?php

namespace App\Http\Controllers\Api\User;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Model\BusinessSetting;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{

    public function redirect($provider) {
        // comment
        return Socialite::driver($provider)->redirect();
    }

    public function social_login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'unique_id' => 'required',
            // 'email' => 'required',
            'medium' => 'required|in:google,facebook,apple',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $client = new Client();
        $token = $request['token'];
        $email = $request['email'] ?? "facebook".rand().'@gmail.com';
        $unique_id = $request['unique_id'];

        try {
            if ($request['medium'] == 'google') {
                $res = $client->request('GET', 'https://www.googleapis.com/oauth2/v1/userinfo?access_token=' . $token);
                $data = json_decode($res->getBody()->getContents(), true);
            } elseif ($request['medium'] == 'facebook') {
                $res = $client->request('GET', 'https://graph.facebook.com/' . $unique_id . '?access_token=' . $token . '&fields=name,email');
                $data = json_decode($res->getBody()->getContents(), true);
            } elseif ($request['medium'] == 'apple') {
                // $res = $client->request('GET', 'https://graph.facebook.com/' . $unique_id . '?access_token=' . $token . '&&fields=name,email');
                // $data = json_decode($res->getBody()->getContents(), true);
                $socialLogin = BusinessSetting::where('type', 'social_login')->first();
                $client_id = '';
                $client_secret = '';
                foreach(json_decode($socialLogin['value'], true) as $key => $social){
                    if($social['login_medium'] == 'apple'){
                        $client_id = $social['service_id'];
                        $client_secret = $social['client_secret'];
                    }
                }
                $apple_data = [
                    'grant_type' => 'authorization_code',
                    'redirect_uri' => 'www.test.com',
                    'client_id' => $client_id,
                    'client_secret' => $client_secret,
                    'code' => $request['token']
                ];
                $response = Request::create('/oauth/token', 'POST', $apple_data);
                $data = json_decode($response->getBody()->getContent(), true);
            }
        } catch (\Exception $exception) {
            return response()->json(['status' => false, 'token' => '', 'message' => $exception->getMessage() ?? 'Something went wrong', 'data' => [], 'errors' => []]);
        }

        // if (strcmp($email, $data['email']) === 0) {
            $user = User::where('email', $email)->first();
            if (isset($user) == false) {
                $message = 'User Registered success';
                $user = User::create([
                    'name' => $data['name'],
                    'mobile' => "",
                    'email' => $email,
                    'image' => $data['picture'] ?? '',
                    // 'dob' => "",
                    // 'gender' => "",
                    // 'profession' => "",
                    'referral_code' => Helpers::generate_referral_code(),
                    'email_verified_at' => now(),
                    'provider' => $request['medium'],
                    'provider_id' => $unique_id,
                    'role_id' => 1, // Default to Supervisor or any role you want   
                ]);
                return response()->json([
                    'status' => true,
                    'token' => $user->createToken('UserToken')->accessToken,
                    'message' => $message,
                ]);

            } else {
                return response()->json([
                    'status' => true,
                    'token' => $user->createToken('UserToken')->accessToken,
                    'message' => 'User Logged in success',
                ]);
            }
        // }

        return response()->json(['status' => false, 'message' => translate('email_does_not_match'), 'token' => "", 'data' => [], 'errors' => []]);
    }

    public static function login_process_passport($user, $email, $password)
    {
        $data = [
            'email' => $email,
            'password' => $password
        ];

        if (isset($user) && $user->is_active && auth()->attempt($data)) {
            $token = auth()->user()->createToken('LaravelAuthApp')->accessToken;
        } else {
            $token = null;
        }

        return $token;
    }
    public function update_phone(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'temporary_token' => 'required',
            'phone' => 'required|min:11|max:14'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $user = User::where(['temporary_token' => $request->temporary_token])->first();
        $user->phone = $request->phone;
        $user->save();


        $phone_verification = BusinessSetting::where('type', 'phone_verification')->first();

        if($phone_verification->value == 1)
        {
            return response()->json([
                'token_type' => 'phone verification on',
                'temporary_token' => $request->temporary_token
            ]);

        }else{
            return response()->json(['message' =>'Phone number updated successfully']);
        }
    }

}
