<?php

namespace App\CPU;
use App\Models\User;
use App\Models\Sale;
use App\Models\Seller;
use App\Models\SellerWallet;
use App\Models\BusinessSetting;
use App\Providers\FirebaseService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Kreait\Firebase\Messaging\Notification;

class Helpers
{

    public static function sendNotification($token, $title, $body, $data = [])
    {
        $notification = Notification::create($title, $body);
        if($token != '') {
            $firebase = new FirebaseService;
            return $firebase->sendNotification($token, $title, $body, $data);
        }
    }

    /**
     * Device wise notification send
     */
    public static function send_push_notif_to_device($fcm_token, $data)
    {
        $key = BusinessSetting::where(['type' => 'push_notification_key'])->first()->value;
        $url = "https://fcm.googleapis.com/fcm/send";
        $header = array(
            "authorization: key=" . $key . "",
            "content-type: application/json"
        );

        if (isset($data['order_id']) == false) {
            // dd($data);
            $data['order_id'] = '';
        }

        $postdata = '{
            "to" : "' . $fcm_token . '",
            "data" : {
                "title" :"' . $data['title'] . '",
                "body" : "' . $data['description'] . '",
                "image" : "' . $data['image'] . '",
                "order_id":"' . $data['order_id'] . '",
                "is_read": 0
              },
              "notification" : {
                "title" :"' . $data['title'] . '",
                "body" : "' . $data['description'] . '",
                "image" : "' . $data['image'] . '",
                "order_id":"' . $data['order_id'] . '",
                "title_loc_key":"' . $data['order_id'] . '",
                "is_read": 0,
                "icon" : "new",
                "sound" : "notification.mp3"
              }
        }';

        $postdata = [
            'to' => $fcm_token,
            "title" =>$data['title'],
            "body" => $data['description'],
            "image" => asset('public/notification-image.png'),
            "icon" => asset('public/notification-icon.png'),
            "link" => $data['link']??'',
            "order_id"=>$data['order_id'],
            "title_loc_key"=>$data['order_id'],
            "is_read"=> 0,
            // "icon" => "new",
            "sound" => "test.mp3"
        ];

        self::sendNotification($fcm_token, $data['title'], $data['description'], $postdata);
        return true;
        $ch = curl_init();
        $timeout = 120;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        // Get URL content
        $result = curl_exec($ch);
        // close handle to release resources
        curl_close($ch);

        return $result;
    }

    public static function getAccessToken()
	{
	    $keyFile = base_path('public/rexarix-f24a2-firebase-adminsdk-fbsvc-1d679aaaca.json');
	    $jsonKey = json_decode(file_get_contents($keyFile), true);

	    $header = ['alg' => 'RS256', 'typ' => 'JWT'];
	    $now = time();

	    $payload = [
	        'iss' => $jsonKey['client_email'],
	        'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
	        'aud' => 'https://oauth2.googleapis.com/token',
	        'exp' => $now + 3600,
	        'iat' => $now
	    ];

	    $base64UrlEncode = function ($data) {
	        return rtrim(strtr(base64_encode(json_encode($data)), '+/', '-_'), '=');
	    };

	    $jwtHeader = $base64UrlEncode($header);
	    $jwtPayload = $base64UrlEncode($payload);

	    $signature = '';
	    openssl_sign(
	        $jwtHeader . "." . $jwtPayload,
	        $signature,
	        $jsonKey['private_key'],
	        'SHA256'
	    );

	    $jwtSignature = rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');

	    $jwt = $jwtHeader . "." . $jwtPayload . "." . $jwtSignature;

	    // Get access token
	    $ch = curl_init();
	    curl_setopt_array($ch, [
	        CURLOPT_URL => 'https://oauth2.googleapis.com/token',
	        CURLOPT_POST => true,
	        CURLOPT_RETURNTRANSFER => true,
	        CURLOPT_POSTFIELDS => http_build_query([
	            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
	            'assertion' => $jwt
	        ])
	    ]);

	    $response = curl_exec($ch);
	    curl_close($ch);

	    $result = json_decode($response, true);

	    return $result['access_token'] ?? null;
	}
	public static function send_push_notif_to_topic($token, $title, $body, $data = [], $android = [])
	{
        if (empty($token)) {
            return null;
        }

        try {
            $firebase = new FirebaseService();
            return $firebase->sendNotification((string)$token, (string)$title, (string)$body, $data);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('FCM send failed: ' . $e->getMessage());
            return null;
        }
	}

    

    public static function single_error_processor($validator)
    {
        $err_keeper = 'Something went wrong';
        foreach ($validator->errors()->getMessages() as $index => $error) {
            return $error[0];
        }
        return $err_keeper;
    }

    public static function get_business_settings($name)
    {
        $config = null;
        $check = ['currency_model', 'currency_symbol_position', 'system_default_currency', 'language', 'company_name', 'decimal_point_settings', 'product_brand', 'digital_product', 'company_email', 'recaptcha'];

        if (in_array($name, $check) == true && session()->has($name)) {
            $config = session($name);
        } else {
            $data = BusinessSetting::where(['type' => $name])->first();
            if (isset($data)) {
                $config = json_decode($data['value'], true);
                if (is_null($config)) {
                    $config = $data['value'];
                }
            }

            if (in_array($name, $check) == true) {
                session()->put($name, $config);
            }
        }

        return $config;
    }

    public static function get_settings($object, $type)
    {
        $config = null;
        foreach ($object as $setting) {
            if ($setting['type'] == $type) {
                $config = $setting;
            }
        }
        return $config;
    }

    public static function default_lang()
    {
        if (strpos(url()->current(), '/api')) {
            $lang = App::getLocale();
        } elseif (session()->has('local')) {
            $lang = session('local');
        } else {
            $data = Helpers::get_business_settings('language');
            $code = 'en';
            $direction = 'ltr';
            foreach ($data as $ln) {
                if (array_key_exists('default', $ln) && $ln['default']) {
                    $code = $ln['code'];
                    if (array_key_exists('direction', $ln)) {
                        $direction = $ln['direction'];
                    }
                }
            }
            session()->put('local', $code);
            Session::put('direction', $direction);
            $lang = $code;
        }
        return $lang;
    }

    public static function remove_invalid_charcaters($str)
    {
        return str_ireplace(['\'', '"', ',', ';', '<', '>', '?'], ' ', preg_replace('/\s\s+/', ' ', $str));
    }

    public static function generate_referral_code(): string {
        do {
            $code = strtoupper(Str::random(8)); // Example: 8-character string
        } while (User::where('referral_code', $code)->exists());
        return $code;
    }

    public static function get_seller_by_token($request)
    {
        $data = '';
        $success = 0;

        $token = explode(' ', $request->header('authorization'));
        if (count($token) > 1 && strlen($token[1]) > 30) {
            $seller = Seller::where(['auth_token' => $token['1']])->first();
            if (isset($seller)) {
                $data = $seller;
                $success = 1;
            }
        }

        return [
            'success' => $success,
            'data' => $data
        ];
    }

    public static function get_sale_by_token($request)
    {
        $data = '';
        $success = 0;

        $token = explode(' ', $request->header('authorization'));
        if (count($token) > 1 && strlen($token[1]) > 30) {
            $seller = Sale::where(['auth_token' => $token['1']])->first();
            if (isset($seller)) {
                $data = $seller;
                $success = 1;
            }
        }

        return [
            'success' => $success,
            'data' => $data
        ];
    }

    public static function get_seller_wallet($seller_id) {
        $sellerWallet = SellerWallet::whereSellerId($seller_id)->first();
        if($sellerWallet == null) {
            $sellerWallet = new SellerWallet;
            $sellerWallet->seller_id = $seller_id;
            $sellerWallet->wallet_amount = 0;
            $sellerWallet->hold_for_campaign = 0;
            $sellerWallet->save();
        }
        return $sellerWallet;
    }

    public static function getTimeZoneList()
    {
        return [
            "UTC" => "UTC",
            "Etc/GMT+12" => "(GMT-12:00) International Date Line West",
            "Pacific/Midway" => "(GMT-11:00) Midway Island, Samoa",
            "Pacific/Honolulu" => "(GMT-10:00) Hawaii",
            "US/Alaska" => "(GMT-09:00) Alaska",
            "America/Los_Angeles" => "(GMT-08:00) Pacific Time (US & Canada)",
            "America/Tijuana" => "(GMT-08:00) Tijuana, Baja California",
            "US/Arizona" => "(GMT-07:00) Arizona",
            "America/Chihuahua" => "(GMT-07:00) Chihuahua, La Paz, Mazatlan",
            "US/Mountain" => "(GMT-07:00) Mountain Time (US & Canada)",
            "America/Managua" => "(GMT-06:00) Central America",
            "US/Central" => "(GMT-06:00) Central Time (US & Canada)",
            "America/Mexico_City" => "(GMT-06:00) Guadalajara, Mexico City, Monterrey",
            "Canada/Saskatchewan" => "(GMT-06:00) Saskatchewan",
            "America/Bogota" => "(GMT-05:00) Bogota, Lima, Quito",
            "US/Eastern" => "(GMT-05:00) Eastern Time (US & Canada)",
            "Canada/Atlantic" => "(GMT-04:00) Atlantic Time (Canada)",
            "America/Caracas" => "(GMT-04:00) Caracas",
            "America/Santiago" => "(GMT-04:00) Santiago",
            "America/Sao_Paulo" => "(GMT-03:00) Brasilia",
            "Atlantic/Cape_Verde" => "(GMT-01:00) Cape Verde",
            "Europe/London" => "(GMT+00:00) London",
            "Europe/Amsterdam" => "(GMT+01:00) Amsterdam, Berlin, Rome",
            "Europe/Paris" => "(GMT+01:00) Paris",
            "Europe/Athens" => "(GMT+02:00) Athens, Istanbul",
            "Africa/Cairo" => "(GMT+02:00) Cairo",
            "Asia/Kuwait" => "(GMT+03:00) Kuwait, Riyadh",
            "Europe/Moscow" => "(GMT+03:00) Moscow",
            "Asia/Tehran" => "(GMT+03:30) Tehran",
            "Asia/Dubai" => "(GMT+04:00) Dubai",
            "Asia/Kabul" => "(GMT+04:30) Kabul",
            "Asia/Karachi" => "(GMT+05:00) Karachi",
            "Asia/Kolkata" => "(GMT+05:30) Chennai, Mumbai, New Delhi",
            "Asia/Kathmandu" => "(GMT+05:45) Kathmandu",
            "Asia/Dhaka" => "(GMT+06:00) Dhaka",
            "Asia/Yangon" => "(GMT+06:30) Yangon",
            "Asia/Bangkok" => "(GMT+07:00) Bangkok",
            "Asia/Hong_Kong" => "(GMT+08:00) Hong Kong",
            "Asia/Singapore" => "(GMT+08:00) Singapore",
            "Asia/Tokyo" => "(GMT+09:00) Tokyo",
            "Asia/Seoul" => "(GMT+09:00) Seoul",
            "Australia/Sydney" => "(GMT+10:00) Sydney",
            "Pacific/Auckland" => "(GMT+12:00) Auckland",
            "Pacific/Fiji" => "(GMT+12:00) Fiji"
        ];
    }

    public static function setDateTime($data) {
        return $data->created_at->timezone(self::get_business_settings('timezone'))->format('d M, Y h:i A');
    }

    public static function currency_to_usd($amount)
    {
        $currency_model = Helpers::get_business_settings('currency_model');
        if ($currency_model == 'multi_currency') {
            $default = Currency::find(BusinessSetting::where(['type' => 'system_default_currency'])->first()->value);
            $usd = Currency::where('code', 'USD')->first()->exchange_rate;
            $rate = $default['exchange_rate'] / $usd;
            $value = floatval($amount) / floatval($rate);
        } else {
            $value = floatval($amount);
        }

        return $value;
    }

    public static function usd_to_currency($amount)
    {
        $currency_model = Helpers::get_business_settings('currency_model');
        if ($currency_model == 'multi_currency') {

            $default = 1;

            $rate = $default / 1;
            $value = floatval($amount) * floatval($rate);
        } else {
            $value = floatval($amount);
        }

        return round($value, 2);
    }

    public static function currency_symbol()
    {
        return 'INR ';
    }

    public static function set_symbol($amount)
    {
        $decimal_point_settings = Helpers::get_business_settings('decimal_point_settings');
        $position = Helpers::get_business_settings('currency_symbol_position');
        if (!is_null($position) && $position == 'left') {
            $string = self::currency_symbol() . '' . number_format($amount, (!empty($decimal_point_settings) ? $decimal_point_settings: 0));
        } else {
            $string = number_format($amount, !empty($decimal_point_settings) ? $decimal_point_settings: 0) . '' . self::currency_symbol();
        }
        return $string;
    }

    public static function currency_code()
    {
        $currency = Currency::where('id', Helpers::get_business_settings('system_default_currency'))->first();
        return $currency->code;
    }

    public static function max_earning()
    {

        $from = \Carbon\Carbon::now()->startOfYear()->format('Y-m-d');
        $to = Carbon::now()->endOfYear()->format('Y-m-d');

        $data = Order::where([
            'seller_is' => 'admin',
            'order_status'=>'delivered'
        ])->select(
            DB::raw('IFNULL(sum(order_amount),0) as sums'),
            DB::raw('YEAR(created_at) year, MONTH(created_at) month')
        )->whereBetween('created_at', [$from, $to])->groupby('year', 'month')->get()->toArray();

        $max = 0;
        foreach ($data as $month) {
            $count = 0;
            foreach ($month as $order) {
                $count += $order['order_amount'];
            }
            if ($count > $max) {
                $max = $count;
            }
        }

        return $max;
    }

    public static function max_orders()
    {
        $from = \Carbon\Carbon::now()->startOfYear()->format('Y-m-d');
        $to = Carbon::now()->endOfYear()->format('Y-m-d');

        $data = Order::where([
            'order_type'=>'default_type'
        ])->select(
            DB::raw('COUNT(id) as count'),
            DB::raw('YEAR(created_at) year, MONTH(created_at) month')
        )->whereBetween('created_at', [$from, $to])->groupby('year', 'month')->get()->toArray();

        $max = 0;
        foreach ($data as $item) {
            if ($item['count'] > $max) {
                $max = $item['count'];
            }
        }

        return $max;
    }

    public static function module_permission_check($mod_name)
    {
        $user_role = auth('admin')->user()->role;
        $permission = $user_role->module_access;
        if (isset($permission) && $user_role->status == 1 && in_array($mod_name, (array)json_decode($permission)) == true) {
            return true;
        }

        if (auth('admin')->user()->admin_role_id == 1) {
            return true;
        }
        return false;
    }

    public static function pagination_limit()
    {
        $pagination_limit = BusinessSetting::where('type', 'pagination_limit')->first();
        if ($pagination_limit != null) {
            return $pagination_limit->value;
        } else {
            return 25;
        }
    }

    public static function systemActivity($module, $user, $event, $description, $model = null) {
        activity($module)
            ->causedBy($user)
            ->performedOn($model)
            ->event($event)
            ->log($description);
        return true;
    }

}



function translate($key)
{
    $local = Helpers::default_lang();
    App::setLocale($local);

    try {
        $lang_array = include(base_path('resources/lang/' . $local . '/messages.php'));
        $processed_key = ucfirst(str_replace('_', ' ', Helpers::remove_invalid_charcaters($key)));
        $key = Helpers::remove_invalid_charcaters($key);
        if (!array_key_exists($key, $lang_array)) {
            $lang_array[$key] = $processed_key;
            $str = "<?php return " . var_export($lang_array, true) . ";";
            file_put_contents(base_path('resources/lang/' . $local . '/messages.php'), $str);
            $result = $processed_key;
        } else {
            $result = __('messages.' . $key);
        }
    } catch (\Exception $exception) {
        $result = __('messages.' . $key);
    }

    return $result;
}