<?php

namespace App\Http\Controllers\Api\Seller;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommonResource;
use App\Models\Seller;
use App\Models\SocialVerificationTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SellerSocialVerificationController extends Controller
{
    private function resolveseller(Request $request): array
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] != 1) {
            return ['seller' => null, 'error' => response()->json([
                'status'  => false,
                'message' => 'Your existing session token does not authorize you any more',
                'data'    => [],
            ], 401)];
        }

        $seller = Seller::find($data['data']['id']);

        return ['seller' => $seller, 'error' => null];
    }

    public function verifySocial(Request $request)
    {
        ['seller' => $seller, 'error' => $error] = $this->resolveseller($request);

        if ($error) {
            return $error;
        }

        $validator = Validator::make($request->all(), [
            'platform'    => 'required|in:instagram,facebook',
            'username'    => 'required|string|max:100',
            'unique_code' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => Helpers::single_error_processor($validator),
            ], 422);
        }

        $platform    = $request->platform;
        $statusField = $platform . '_status';

        if ($seller->$statusField === SocialVerificationTransaction::STATUS_VERIFIED) {
            return response()->json([
                'status'  => false,
                'message' => ucfirst($platform) . ' account is already verified.',
            ], 422);
        }

        // Cancel any existing pending transaction for this seller + platform
        SocialVerificationTransaction::where('seller_id', $seller->id)
            ->where('platform', $platform)
            ->where('status', SocialVerificationTransaction::STATUS_PENDING)
            ->update(['status' => SocialVerificationTransaction::STATUS_NOT_VERIFIED]);

        $transaction = SocialVerificationTransaction::create([
            'seller_id'    => $seller->id,
            'platform'     => $platform,
            'username'     => $request->username,
            'unique_code'  => $request->unique_code,
            'status'       => SocialVerificationTransaction::STATUS_PENDING,
            'submitted_at' => now(),
            'end_date'     => now()->addDays(7)->toDateString(),
        ]);

        $seller->$statusField = SocialVerificationTransaction::STATUS_PENDING;
        $seller->save();

        return response()->json([
            'status'  => true,
            'message' => 'Verification initiated. Post this unique code on your ' . ucfirst($platform) . ' account.',
            'data'    => [
                'unique_code' => $request->unique_code,
                'platform'    => $platform,
                'username'    => $request->username,
                'end_date'    => $transaction->end_date,
            ],
        ]);
    }

    public function socialVerificationStatus(Request $request)
    {
        ['seller' => $seller, 'error' => $error] = $this->resolveseller($request);

        if ($error) {
            return $error;
        }

        $instagram = SocialVerificationTransaction::where('seller_id', $seller->id)
            ->where('platform', SocialVerificationTransaction::PLATFORM_INSTAGRAM)
            ->latest()
            ->first();

        $facebook = SocialVerificationTransaction::where('seller_id', $seller->id)
            ->where('platform', SocialVerificationTransaction::PLATFORM_FACEBOOK)
            ->latest()
            ->first();

        return response()->json([
            'status'  => true,
            'message' => 'Social verification status retrieved successfully',
            'data'    => [
                'instagram' => [
                    'status'   => $seller->instagram_status,
                    'username' => $seller->instagram_username,
                ],
                'facebook'  => [
                    'status'   => $seller->facebook_status,
                    'username' => $seller->facebook_username,
                ],
            ],
        ]);
    }
}
