<?php

namespace App\Http\Controllers\Api\User;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommonResource;
use App\Models\CoinWallet;
use App\Models\Voucher;
use App\Models\VoucherBrand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VoucherController extends Controller
{
    public function brands(Request $request)
    {
        try {
            $limit = (int) ($request->limit ?? 25);

            $brands = VoucherBrand::where('is_active', 1)
                ->withCount(['vouchers' => function ($q) {
                    $q->where('status', 'available')->where('is_active', 1)
                        ->whereRaw('DATE_ADD(created_at, INTERVAL validity_days DAY) >= NOW()');
                }])
                ->orderByDesc('id')
                ->paginate($limit);

            $brands->getCollection()->transform(function ($brand) {
                $logo = (string) ($brand->logo ?? '');

                if ($logo === '') {
                    $brand->logo_full_path = '';
                } elseif (preg_match('/^https?:\/\//i', $logo)) {
                    $brand->logo_full_path = $logo;
                } else {
                    $normalizedLogo = ltrim($logo, '/');
                    if (str_starts_with($normalizedLogo, 'storage/')) {
                        $brand->logo_full_path = asset($normalizedLogo);
                    } else {
                        $brand->logo_full_path = asset('storage/voucher-brand/' . $normalizedLogo);
                    }
                }

                return $brand;
            });

            return response()->json([
                'status' => true,
                'message' => 'Voucher brands retrieved successfully',
                'data' => CommonResource::collection($brands),
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
                'data' => [],
            ]);
        }
    }

    public function index(Request $request)
    {

   
        try {
            $limit = (int) ($request->limit ?? 25);
            $status = $request->status;

            $query = Voucher::with('voucherBrand:id,name,logo')
                ->where('is_active', 1)
                ->when($status, function ($q) use ($status) {
                    $q->where('status', $status);
                }, function ($q) {
                    $q->where('status', 'available');
                })
                ->whereRaw('DATE_ADD(created_at, INTERVAL validity_days DAY) >= NOW()')
                ->orderByDesc('id');

            $vouchers = $query->paginate($limit);

            return response()->json([
                'status' => true,
                'message' => 'Vouchers retrieved successfully',
                'data' => CommonResource::collection($vouchers),
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
                'data' => [],
            ]);
        }
    }

    public function byBrand(Request $request, $brandId)
    {
        try {
            $brand = VoucherBrand::where('id', $brandId)->where('is_active', 1)->first();
            if (!$brand) {
                return response()->json([
                    'status' => false,
                    'message' => 'Voucher brand not found',
                    'data' => [],
                ], 404);
            }

            $limit = (int) ($request->limit ?? 25);
            $status = $request->status;

            $vouchers = Voucher::with('voucherBrand:id,name,logo')
                ->where('voucher_brands_id', $brandId)
                ->where('is_active', 1)
                ->when($status, function ($q) use ($status) {
                    $q->where('status', $status);
                }, function ($q) {
                    $q->where('status', 'available');
                })
                ->whereRaw('DATE_ADD(created_at, INTERVAL validity_days DAY) >= NOW()')
                ->orderByDesc('id')
                ->paginate($limit);

            return response()->json([
                'status' => true,
                'message' => 'Vouchers retrieved successfully',
                'brand' => new CommonResource($brand),
                'data' => CommonResource::collection($vouchers),
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
                'data' => [],
            ]);
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $voucher = Voucher::with('voucherBrand:id,name,logo')->find($id);
            if (!$voucher) {
                return response()->json([
                    'status' => false,
                    'message' => 'Voucher not found',
                    'data' => [],
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Voucher retrieved successfully',
                'data' => new CommonResource($voucher),
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
                'data' => [],
            ]);
        }
    }

    public function purchase(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'voucher_id' => 'required|integer|exists:vouchers,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => Helpers::single_error_processor($validator),
                'data' => [],
            ], 422);
        }

        $user = $request->user();

        DB::beginTransaction();
        try {
            $voucher = Voucher::where('id', $request->voucher_id)->lockForUpdate()->first();
            if (!$voucher || !$voucher->is_active) {
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'message' => 'Voucher is not active.',
                    'data' => [],
                ], 422);
            }

            if ($voucher->status !== 'available') {
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'message' => 'Voucher is not available for purchase.',
                    'data' => [],
                ], 422);
            }

            $expiresAt = $voucher->created_at->copy()->addDays((int) $voucher->validity_days);
            if ($expiresAt->isPast()) {
                $voucher->status = 'expired';
                $voucher->save();

                DB::commit();
                return response()->json([
                    'status' => false,
                    'message' => 'Voucher has expired.',
                    'data' => [],
                ], 422);
            }

            $wallet = CoinWallet::where('user_id', $user->id)->lockForUpdate()->first();
            if (!$wallet) {
                $wallet = CoinWallet::create([
                    'user_id' => $user->id,
                    'balance' => 0,
                ]);
                $wallet = CoinWallet::where('id', $wallet->id)->lockForUpdate()->first();
            }

            if ((float) $wallet->balance < (float) $voucher->coin_price) {
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'message' => 'Insufficient wallet balance.',
                    'data' => [
                        'wallet_balance' => (string) $wallet->balance,
                        'required_coins' => (string) $voucher->coin_price,
                    ],
                ], 422);
            }

            $wallet->balance = (float) $wallet->balance - (float) $voucher->coin_price;
            $wallet->save();

            $transaction = $wallet->transactions()->create([
                'coin' => $voucher->coin_price,
                'amount' => $voucher->fiat_value,
                'tds' => 0,
                'convertion_rate' => Helpers::get_business_settings('upi_value') ?? 0,
                'campaign_id' => '0',
                'transaction_id' => 'VCH-' . time() . '-' . $user->id,
                'type' => 'debit',
                'status' => 'completed',
                'transaction_type' => 'voucher_purchase',
                'value' => (string) $voucher->id,
                'description' => 'Voucher purchase: ' . $voucher->title,
            ]);

            $voucher->status = 'purchased';
            $voucher->save();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Voucher purchased successfully.',
                'data' => [
                    'voucher' => new CommonResource($voucher),
                    'transaction' => new CommonResource($transaction),
                    'wallet_balance' => (string) $wallet->balance,
                ],
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
                'data' => [],
            ]);
        }
    }

    public function purchaseTransactions(Request $request)
    {
        try {
            $user = $request->user();
            $wallet = CoinWallet::firstOrCreate(
                ['user_id' => $user->id],
                ['balance' => 0]
            );

            $transactions = $wallet->transactions()
                ->where('transaction_type', 'voucher_purchase')
                ->latest()
                ->get();

            return response()->json([
                'status' => true,
                'message' => 'Voucher purchase transactions retrieved successfully',
                'data' => CommonResource::collection($transactions),
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
                'data' => [],
            ]);
        }
    }

    public function purchasedVouchers(Request $request)
    {
        try {
            $limit = (int) ($request->limit ?? 25);
            $user = $request->user();

            $wallet = CoinWallet::firstOrCreate(
                ['user_id' => $user->id],
                ['balance' => 0]
            );

            $transactionsQuery = $wallet->transactions()
                ->where('transaction_type', 'voucher_purchase')
                ->where('type', 'debit')
                ->where('status', 'completed')
                ->latest();

            $totalPurchased = (clone $transactionsQuery)->count();
            $transactions = $transactionsQuery->paginate($limit);

            $voucherIds = collect($transactions->items())
                ->pluck('value')
                ->filter()
                ->map(fn($id) => (int) $id)
                ->unique()
                ->values();

            $vouchers = Voucher::with('voucherBrand:id,name,logo')
                ->whereIn('id', $voucherIds)
                ->get()
                ->keyBy('id');

            $transactions->getCollection()->transform(function ($transaction) use ($vouchers) {
                $transaction->voucher = $vouchers->get((int) $transaction->value);
                return $transaction;
            });

            return response()->json([
                'status' => true,
                'message' => 'Purchased vouchers retrieved successfully',
                'total_purchased' => $totalPurchased,
                'data' => CommonResource::collection($transactions),
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
                'data' => [],
            ]);
        }
    }
}
