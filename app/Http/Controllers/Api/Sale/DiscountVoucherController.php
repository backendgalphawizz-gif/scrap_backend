<?php

namespace App\Http\Controllers\Api\Sale;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Models\CampaignDiscountVoucher;
use Illuminate\Http\Request;

class DiscountVoucherController extends Controller
{
    /**
     * GET /sale/discount-vouchers
     * List the authenticated sales person's discount vouchers.
     */
    public function index(Request $request)
    {
        $data = Helpers::get_sale_by_token($request);
        if ($data['success'] != 1) {
            return $this->unauthorized();
        }

        $saleId = $data['data']['id'];
        $perPage = (int) ($request->query('per_page', 15));

        $vouchers = CampaignDiscountVoucher::where('sale_id', $saleId)
            ->orderByDesc('created_at')
            ->paginate($perPage);

        return response()->json([
            'status'  => true,
            'message' => 'Discount vouchers fetched successfully.',
            'data'    => $vouchers,
        ], 200);
    }

    /**
     * GET /sale/discount-vouchers/{id}
     * Show a single voucher owned by the authenticated sales person.
     */
    public function show(Request $request, int $id)
    {
        $data = Helpers::get_sale_by_token($request);
        if ($data['success'] != 1) {
            return $this->unauthorized();
        }

        $saleId  = $data['data']['id'];
        $voucher = CampaignDiscountVoucher::where('id', $id)
            ->where('sale_id', $saleId)
            ->first();

        if (!$voucher) {
            return response()->json([
                'status'  => false,
                'message' => 'Voucher not found.',
                'data'    => [],
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Voucher fetched successfully.',
            'data'    => $voucher,
        ], 200);
    }

    /**
     * POST /sale/discount-vouchers
     * Create a new discount voucher for the authenticated sales person.
     */
    public function store(Request $request)
    {
        $data = Helpers::get_sale_by_token($request);
        if ($data['success'] != 1) {
            return $this->unauthorized();
        }

        $saleId = $data['data']['id'];

        $request->validate([
            'discount_amount' => 'required|numeric|min:1',
            'valid_from'      => 'nullable|date',
            'valid_to'        => 'nullable|date|after_or_equal:valid_from',
            'max_uses'        => 'nullable|integer|min:1',
        ]);

        $code = CampaignDiscountVoucher::generateCode();

        $voucher = CampaignDiscountVoucher::create([
            'sale_id'         => $saleId,
            'code'            => $code,
            'discount_amount' => $request->discount_amount,
            'valid_from'      => $request->valid_from,
            'valid_to'        => $request->valid_to,
            'max_uses'        => $request->max_uses,
            'used_count'      => 0,
            'is_active'       => true,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Discount voucher created successfully.',
            'data'    => $voucher,
        ], 201);
    }

    /**
     * PUT /sale/discount-vouchers/{id}
     * Toggle is_active on a voucher owned by the authenticated sales person.
     */
    public function update(Request $request, int $id)
    {
        $data = Helpers::get_sale_by_token($request);
        if ($data['success'] != 1) {
            return $this->unauthorized();
        }

        $saleId  = $data['data']['id'];
        $voucher = CampaignDiscountVoucher::where('id', $id)
            ->where('sale_id', $saleId)
            ->first();

        if (!$voucher) {
            return response()->json([
                'status'  => false,
                'message' => 'Voucher not found.',
                'data'    => [],
            ], 404);
        }

        $request->validate([
            'is_active' => 'required|boolean',
        ]);

        $voucher->is_active = (bool) $request->is_active;
        $voucher->save();

        return response()->json([
            'status'  => true,
            'message' => 'Voucher updated successfully.',
            'data'    => $voucher,
        ], 200);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function unauthorized()
    {
        return response()->json([
            'status'  => false,
            'message' => 'Your existing session token does not authorize you any more.',
            'data'    => [],
        ], 401);
    }
}
