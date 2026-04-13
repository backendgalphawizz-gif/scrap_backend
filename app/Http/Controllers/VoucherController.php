<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use App\Models\VoucherBrand;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index(Request $request)
    {
        $vouchers = Voucher::with('voucherBrand')
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
            ->paginate(25)
            ->withQueryString();

        $voucherBrands = VoucherBrand::orderBy('name')->get();

        return view('admin-views.voucher.index', compact('vouchers', 'voucherBrands'));
    }

    public function create()
    {
        $voucherBrands = VoucherBrand::where('is_active', 1)->orderBy('name')->get();

        return view('admin-views.voucher.create', compact('voucherBrands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'voucher_brands_id' => 'required|integer|exists:voucher_brands,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'coin_price' => 'required|numeric|min:0',
            'fiat_value' => 'required|numeric|min:0',
            'code' => 'required|string|max:100|unique:vouchers,code',
            'status' => 'required|in:available,purchased,expired',
            'validity_days' => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        Voucher::create([
            'voucher_brands_id' => $request->voucher_brands_id,
            'title' => $request->title,
            'description' => $request->description,
            'coin_price' => $request->coin_price,
            'fiat_value' => $request->fiat_value,
            'code' => $request->code,
            'status' => $request->status,
            'validity_days' => $request->validity_days,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.voucher.index')->with('success', 'Voucher created successfully.');
    }

    public function edit($id)
    {
        $voucher = Voucher::findOrFail($id);
        $voucherBrands = VoucherBrand::orderBy('name')->get();

        return view('admin-views.voucher.edit', compact('voucher', 'voucherBrands'));
    }

    public function update(Request $request, $id)
    {
        $voucher = Voucher::findOrFail($id);

        $request->validate([
            'voucher_brands_id' => 'required|integer|exists:voucher_brands,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'coin_price' => 'required|numeric|min:0',
            'fiat_value' => 'required|numeric|min:0',
            'code' => 'required|string|max:100|unique:vouchers,code,' . $voucher->id,
            'status' => 'required|in:available,purchased,expired',
            'validity_days' => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $voucher->update([
            'voucher_brands_id' => $request->voucher_brands_id,
            'title' => $request->title,
            'description' => $request->description,
            'coin_price' => $request->coin_price,
            'fiat_value' => $request->fiat_value,
            'code' => $request->code,
            'status' => $request->status,
            'validity_days' => $request->validity_days,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.voucher.index')->with('success', 'Voucher updated successfully.');
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:vouchers,id',
        ]);

        Voucher::where('id', $request->id)->delete();

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
}
