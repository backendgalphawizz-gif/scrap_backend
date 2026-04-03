<?php

namespace App\Http\Controllers\Api\Seller;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\CPU\ProductManager;
use App\Http\Controllers\Controller;

use App\Models\Campaign;
use App\Models\Seller;
use App\Models\SellerWallet;
use App\Models\SellerWalletHistory;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use function App\CPU\translate;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\CommonResource;



class SellerWalletController extends Controller
{
    public function index(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];

            $sellerWallet = Helpers::get_seller_wallet($seller['id']);
            
        } else {
            return response()->json([
                'status' => false,
                'message' => translate('Your existing session token does not authorize you any more'),
                'data' => []
            ], 401);
        }

        $response = [
            'status' => true,
            'message' => 'Seller Wallet',
            'data' => [new CommonResource($sellerWallet)]
        ];

        return response()->json($response, 200);
    }
    
    public function createWalletTransaction(Request $request) {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0.01',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => translate('Validation failed'),
                'errors' => $validator->errors()
            ], 422);
        }

        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] != 1) {
            return response()->json([
                'status' => false,
                'message' => translate('Your existing session token does not authorize you any more'),
            ], 401);
        }

        $seller = $data['data'];
        $amount = $request->input('amount');

        $sellerWallet = Helpers::get_seller_wallet($seller['id']);

        if ($sellerWallet) {
            $sellerWallet->wallet_amount += $amount;
            $sellerWallet->save();
            
            $walletHistory = new SellerWalletHistory;
            $walletHistory->seller_id = $seller['id'];
            $walletHistory->amount = $amount;
            $walletHistory->type = 'credit';
            $walletHistory->remarks = 'Amount added to wallet';
            $walletHistory->available_balance = $sellerWallet->wallet_amount;
            $walletHistory->save();

            return response()->json([
                'status' => true,
                'message' => translate('Balance added successfully'),
                'data' => new CommonResource($sellerWallet)
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => translate('Wallet not found'),
        ], 404);
    }

    public function walletTransactionList(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] != 1) {
            return response()->json([
                'status' => false,
                'message' => translate('Your existing session token does not authorize you any more'),
            ], 401);
        }

        $seller = $data['data'];
        $limit = $request->input('limit', 10);
        $offset = $request->input('offset', 0);

        $transactions = SellerWalletHistory::whereSellerId($seller['id'])
            ->orderByDesc('created_at')
            ->limit($limit)
            ->offset($offset)
            ->get();

        $total = SellerWalletHistory::whereSellerId($seller['id'])->count();

        return response()->json([
            'status' => true,
            'message' => translate('Wallet transaction list'),
            'data' => CommonResource::collection($transactions),
            'total' => $total,
            'offset' => $offset,
            'limit' => $limit
        ], 200);
    }

}
