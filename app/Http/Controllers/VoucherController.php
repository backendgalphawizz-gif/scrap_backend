<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use App\Models\VoucherBrand;
use App\Models\Sale;
use App\Models\SaleWalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class VoucherController extends Controller
{
    public function index(Request $request)
    {
        $vouchers = Voucher::with(['voucherBrand', 'sale'])
            ->when($request->filled('id'), function ($query) use ($request) {
                $query->where('id', $request->id);
            })
            ->when($request->filled('title'), function ($query) use ($request) {
                $query->where('title', 'like', '%' . trim($request->title) . '%');
            })
            ->when($request->filled('code'), function ($query) use ($request) {
                $query->where('code', 'like', '%' . trim($request->code) . '%');
            })
            ->when($request->filled('voucher_brands_id'), function ($query) use ($request) {
                $query->where('voucher_brands_id', $request->voucher_brands_id);
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->filled('is_active'), function ($query) use ($request) {
                $query->where('is_active', (int) $request->is_active);
            })
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        $voucherBrands = VoucherBrand::orderBy('name', 'asc')->get();

        return view('admin-views.voucher.index', compact('vouchers', 'voucherBrands'));
    }

    public function create()
    {
        $voucherBrands = VoucherBrand::where('is_active', '=', 1, 'and')->orderBy('name', 'asc')->get();

        return view('admin-views.voucher.create', compact('voucherBrands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'voucher_brands_id' => 'required|integer|exists:voucher_brands,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'coin_price' => 'required|numeric|min:0',
            'fiat_value' => 'required|numeric|min:0.01',
            'code' => 'required|string|max:100|unique:vouchers,code',
            'status' => 'required|in:available,purchased,expired',
            'validity_days' => 'required|integer|min:0',
            'valid_from' => 'required|date',
            'valid_to' => 'required|date|after_or_equal:valid_from',
            'max_uses' => 'nullable|integer|min:1',
            'max_uses_per_user' => 'required|integer|min:1',
            'is_active' => 'nullable|boolean',
        ]);

        Voucher::create([
            'voucher_brands_id' => $request->voucher_brands_id,
            'sale_id' => null,
            'title' => $request->title,
            'description' => $request->description,
            'coin_price' => $request->coin_price,
            'fiat_value' => $request->fiat_value,
            'code' => $request->code,
            'status' => $request->status,
            'validity_days' => $request->validity_days,
            'valid_from' => $request->valid_from,
            'valid_to' => $request->valid_to,
            'max_uses' => $request->max_uses,
            'max_uses_per_user' => $request->max_uses_per_user,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.voucher.index')->with('success', 'Voucher created successfully.');
    }

    public function edit($id)
    {
        $voucher = Voucher::findOrFail($id);
        $voucherBrands = VoucherBrand::orderBy('name', 'asc')->get();
        $sales = Sale::where('status', '=', 'active', 'and')->orderBy('name', 'asc')->get(['id', 'name', 'balance']);

        return view('admin-views.voucher.edit', compact('voucher', 'voucherBrands', 'sales'));
    }

    public function update(Request $request, $id)
    {
        $voucher = Voucher::findOrFail($id);

        $request->validate([
            'voucher_brands_id' => 'required|integer|exists:voucher_brands,id',
            'sale_id' => 'required|integer|exists:sales,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'coin_price' => 'required|numeric|min:0',
            'fiat_value' => 'required|numeric|min:0.01',
            'code' => 'required|string|max:100|unique:vouchers,code,' . $voucher->id,
            'status' => 'required|in:available,purchased,expired',
            'validity_days' => 'required|integer|min:0',
            'valid_from' => 'required|date',
            'valid_to' => 'required|date|after_or_equal:valid_from',
            'max_uses' => 'nullable|integer|min:1',
            'max_uses_per_user' => 'required|integer|min:1',
            'is_active' => 'nullable|boolean',
        ]);

        DB::transaction(function () use ($request, $voucher) {
            $originalSaleId = (int) $voucher->sale_id;
            $newSaleId = (int) $request->sale_id;
            $oldAmount = (float) $voucher->fiat_value;
            $newAmount = (float) $request->fiat_value;

            if ($originalSaleId !== $newSaleId) {
                $oldSale = Sale::where('id', '=', $originalSaleId, 'and')->lockForUpdate()->first();
                if ($oldSale) {
                    $this->creditSaleBalance($oldSale, $oldAmount, 'Voucher funding refunded: ' . $voucher->title . ' (' . $voucher->code . ')');
                }

                $newSale = Sale::where('id', '=', $newSaleId, 'and')->lockForUpdate()->firstOrFail();
                $this->ensureSaleHasBalance($newSale, $newAmount);
                $this->debitSaleBalance($newSale, $newAmount, 'Voucher discount funded (updated): ' . $request->title . ' (' . $request->code . ')');
            } else {
                $sale = Sale::where('id', '=', $newSaleId, 'and')->lockForUpdate()->firstOrFail();
                $difference = $newAmount - $oldAmount;

                if ($difference > 0) {
                    $this->ensureSaleHasBalance($sale, $difference);
                    $this->debitSaleBalance($sale, $difference, 'Voucher funding increased: ' . $request->title . ' (' . $request->code . ')');
                } elseif ($difference < 0) {
                    $this->creditSaleBalance($sale, abs($difference), 'Voucher funding reduced: ' . $request->title . ' (' . $request->code . ')');
                }
            }

            $voucher->update([
                'voucher_brands_id' => $request->voucher_brands_id,
                'sale_id' => $request->sale_id,
                'title' => $request->title,
                'description' => $request->description,
                'coin_price' => $request->coin_price,
                'fiat_value' => $request->fiat_value,
                'code' => $request->code,
                'status' => $request->status,
                'validity_days' => $request->validity_days,
                'valid_from' => $request->valid_from,
                'valid_to' => $request->valid_to,
                'max_uses' => $request->max_uses,
                'max_uses_per_user' => $request->max_uses_per_user,
                'is_active' => $request->boolean('is_active'),
            ]);
        });

        return redirect()->route('admin.voucher.index')->with('success', 'Voucher updated successfully.');
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:vouchers,id',
        ]);

        Voucher::where('id', '=', $request->id, 'and')->delete();

        return redirect()->route('admin.voucher.index')->with('success', 'Voucher deleted successfully.');
    }

    public function activeStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:vouchers,id',
            'is_active' => 'required|boolean',
        ]);

        $voucher = Voucher::findOrFail($request->id);
        $voucher->is_active = (bool) $request->is_active;
        $voucher->save();

        return redirect()->route('admin.voucher.index')->with('success', 'Voucher active status updated successfully.');
    }

    private function ensureSaleHasBalance(Sale $sale, float $amount): void
    {
        if ((float) $sale->balance < $amount) {
            throw ValidationException::withMessages([
                'fiat_value' => 'Voucher discount cannot be greater than available sales commission balance.',
            ]);
        }
    }

    private function debitSaleBalance(Sale $sale, float $amount, string $remarks): void
    {
        $sale->balance = (float) $sale->balance - $amount;
        $sale->save();

        SaleWalletTransaction::create([
            'sale_id' => $sale->id,
            'amount' => $amount,
            'type' => 'debit',
            'remarks' => $remarks,
            'status' => 'success',
        ]);
    }

    private function creditSaleBalance(Sale $sale, float $amount, string $remarks): void
    {
        $sale->balance = (float) $sale->balance + $amount;
        $sale->save();

        SaleWalletTransaction::create([
            'sale_id' => $sale->id,
            'amount' => $amount,
            'type' => 'credit',
            'remarks' => $remarks,
            'status' => 'success',
        ]);
    }
}
