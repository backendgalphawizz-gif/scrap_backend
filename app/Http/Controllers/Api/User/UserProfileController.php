<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\CommonResource;
use App\Models\CoinWallet;
use App\Models\Feedback;
use App\CPU\ImageManager;
use App\CPU\Helpers;
use App\Models\User;
use App\Models\Seller;
use App\Models\Notification;


class UserProfileController extends Controller
{

    public function index(Request $request)
    {
        $user = $request->user();
        $user->referrers;

        return response()->json([
            'status' => true,
            'message' => 'User profile retrieved successfully',
            'data' => new CommonResource($user)
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $rules = [
            'name' => 'sometimes|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'mobile' => 'sometimes|digits:10|unique:users,mobile,' . $user->id,
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => Helpers::single_error_processor($validator)
            ], 422);
        }

        $user->update($request->only(['name', 'email', 'mobile', 'dob', 'gender', 'profession', 'address', 'city', 'state', 'native_state', 'native_city', 'instagram_username', 'facebook_username']));

        if ($request->hasFile('image')) {
            $user->image = ImageManager::upload('profile/', 'png', $request->file('image'), $user->image);
            $user->save();
        }

        return response()->json([
            'status' => true,
            'message' => 'User profile updated successfully',
            'data' => new CommonResource($user)
        ]);
    }

    public function updateKyc(Request $request)
    {
        $user = $request->user();

        // $rules = [
        //     'pan_number' => 'required',
        //     'aadhar_number' => 'required',
        //     'bank_name' => 'required',
        //     'ifsc_code' => 'required',
        //     'account_number' => 'required',
        //     'branch_name' => 'required',
        //     'upi_id' => 'required'
        // ];

        // $validator = Validator::make($request->all(), $rules);

        // if ($validator->fails()) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => Helpers::single_error_processor($validator)
        //     ], 422);
        // }

        // $user->update($request->only(['name', 'email', 'mobile','dob','gender','profession', 'address','city','state', 'native_state', 'native_city', 'instagram_username','facebook_username']));
        if ($request->has('pan_number')) {
            $user->pan_number = $request->pan_number;
            $user->pan_status = 'Submitted';
            if ($request->hasFile('pan_image')) {
                $user->pan_image = ImageManager::upload('profile/', 'png', $request->file('pan_image'));
            }
        }
        
        if ($request->has('aadhar_number')) {
            $user->aadhar_number = $request->aadhar_number;
            $user->aadhar_status = 'Submitted';
            $aadhar_images = [];
            if ($request->hasFile('aadhar_image')) {
                foreach ($request->file('aadhar_image') as $img) {
                    $aadhar_images[] = ImageManager::upload('profile/', 'png', $img, $user->image);
                }
                $user->aadhar_image = implode(',', $aadhar_images);
            }
        }

        if ($request->has('upi_id')) {
            $user->upi_id = $request->upi_id;
            $user->upi_status = 'Submitted';
        }
        if ($request->has('bank_name') || $request->has('ifsc_code')) {
            $user->bank_detail = json_encode([
                'bank_name' => $request->bank_name,
                'ifsc_code' => $request->ifsc_code,
                'account_number' => $request->account_number,
                'branch_name' => $request->branch_name
            ]);

            $user->bank_status = $request->bank_name != '' ? 'Submitted' : 'Not Submitted';
        }
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'User KYC updated successfully',
            'data' => new CommonResource($user)
        ]);
    }

    public function coinWallet(Request $request)
    {
        try {
            //code...
            $user = $request->user();

            CoinWallet::firstOrCreate(
                ['user_id' => $user->id],
                ['balance' => 0]
            );

            $wallet = $user->coinWallet;
            return response()->json([
                'status' => true,
                'message' => 'Coin wallet retrieved successfully',
                'data' => new CommonResource($wallet)
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
                'data' => []
            ]);
        }
    }

    public function walletTransctions(Request $request)
    {
        try {
            //code...
            $limit = $request->limit ?? 25;
            $status = $request->status ?? '';

            $user = $request->user();
            $transactions = $user->coinWallet->transactions()->when($status != '', function ($q) use ($status) {
                $q->where('status', $status);
            })->latest()->get();

            return response()->json([
                'status' => true,
                'message' => 'Wallet transactions retrieved successfully',
                'data' => CommonResource::collection($transactions)
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
                'data' => []
            ]);
            //throw $th;
        }
    }

    public function debitWalletCoin(Request $request)
    {
        try {
            $user = $request->user();

            $rules = [
                'coins' => 'required|numeric|min:1',
                'type' => 'required',
                'value' => 'required'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => Helpers::single_error_processor($validator)
                ], 422);
            }

            $wallet = $user->coinWallet;

            if ($wallet->balance < $request->coins) {
                return response()->json([
                    'status' => false,
                    'message' => 'Insufficient balance'
                ], 422);
            }

            $wallet->balance -= $request->coins;
            $wallet->save();

            $transaction = $wallet->transactions()->create([
                'coin' => $request->coins ?? 0,
                'amount' => $request->amount ?? 0,
                'tds' => $request->tds ?? 0,
                'convertion_rate' => Helpers::get_business_settings('upi_value'),
                'campaign_id' => 0,
                'transaction_id' => time(),
                'type' => 'debit',
                'status' => 'pending',
                'transaction_type' => $request->type,
                'value' => $request->value,
                'description' => $request->input('description'),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Debit request created successfully',
                'data' => new CommonResource($transaction)
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
                'data' => []
            ]);
        }
    }

    public function referrers(Request $request)
    {
        $user = $request->user();
        // $user->referrers;

        return response()->json([
            'status' => true,
            'message' => 'User profile retrieved successfully',
            'data' => $user->referrers
        ]);
    }

    public function getBrandFeedbackQuestion(Request $request, $id)
    {
        $brand = Seller::findOrFail($id);
        $questions = $brand->questions()->where('status', 1)->get();

        return response()->json([
            'status' => true,
            'message' => 'Brand questions retrieved successfully',
            'data' => CommonResource::collection($questions)
        ]);
    }

    public function submitCampaignFeedback(Request $request)
    {
        $user = $request->user();

        $rules = [
            'campaign_id' => 'required|exists:campaigns,id',
            'rating' => 'required|integer|min:1|max:5',
            'feedback' => 'nullable|string|max:1000',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => Helpers::single_error_processor($validator)
            ], 422);
        }

        $feedback = new Feedback;
        $feedback->campaign_id = $request->input('campaign_id');
        $feedback->brand_id = $request->input('brand_id');
        $feedback->user_id = $user->id;
        $feedback->ratings = $request->input('rating');
        $feedback->questions = $request->input('questions');
        $feedback->save();

        return response()->json([
            'status' => true,
            'message' => 'Campaign feedback submitted successfully',
            'data' => []
        ]);
    }

    public function listCampaignFeedback(Request $request)
    {
        $user = $request->user();
        $feedbacks = Feedback::with(['campaign', 'brand', 'user'])->where('user_id', $user->id)->latest()->get();

        return response()->json([
            'status' => true,
            'message' => 'Campaign feedbacks retrieved successfully',
            'data' => CommonResource::collection($feedbacks)
        ]);
    }

    public function notifications(Request $request)
    {
        $limit = $request->limit ?? 25;
        $notifications = Notification::where(['status' => 1, 'type' => 'user'])->orderBy('id', 'DESC')->paginate($limit);
        return response()->json([
            'status' => true,
            'message' => 'Notification retrieved successfully',
            'data' => CommonResource::collection($notifications)
        ]);
    }

    public function deleteAccount(Request $request)
    {
        $id = $request->user()->id;

        $user = User::find($id);
        $user->delete();

        return response()->json([
            'status' => true,
            'message' => 'Account deleted successfully',
            'data' => []
        ]);
    }

}