<?php

namespace App\Http\Controllers;

use App\CPU\ImageManager;
use App\Models\VoucherBrand;
use Illuminate\Http\Request;

class VoucherBrandController extends Controller
{
    public function index(Request $request)
    {
        $voucherBrands = VoucherBrand::query()
            ->when($request->filled('id'), function ($query) use ($request) {
                $query->where('id', $request->id);
            })
            ->when($request->filled('name'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . trim($request->name) . '%');
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('is_active', (int) $request->status);
            })
            ->orderByDesc('id')
            ->paginate(25)
            ->withQueryString();

        return view('admin-views.voucher-brand.index', compact('voucherBrands'));
    }

    public function create()
    {
        return view('admin-views.voucher-brand.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:voucher_brands,name',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        $voucherBrand = new VoucherBrand();
        $voucherBrand->name = $request->name;
        $voucherBrand->logo = $request->hasFile('logo')
            ? ImageManager::upload('voucher-brand/', 'png', $request->file('logo'))
            : null;
        $voucherBrand->is_active = $request->boolean('is_active');
        $voucherBrand->save();

        return redirect()->route('admin.voucher-brand.index')->with('success', 'Voucher brand created successfully.');
    }

    public function edit($id)
    {
        $voucherBrand = VoucherBrand::findOrFail($id);

        return view('admin-views.voucher-brand.edit', compact('voucherBrand'));
    }

    public function update(Request $request, $id)
    {
        $voucherBrand = VoucherBrand::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:voucher_brands,name,' . $voucherBrand->id,
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        $voucherBrand->name = $request->name;
        if ($request->hasFile('logo')) {
            $voucherBrand->logo = ImageManager::update('voucher-brand/', $voucherBrand->logo, 'png', $request->file('logo'));
        }
        $voucherBrand->is_active = $request->boolean('is_active');
        $voucherBrand->save();

        return redirect()->route('admin.voucher-brand.index')->with('success', 'Voucher brand updated successfully.');
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:voucher_brands,id',
        ]);

        $voucherBrand = VoucherBrand::findOrFail($request->id);
        if (!empty($voucherBrand->logo)) {
            ImageManager::delete('/voucher-brand/' . $voucherBrand->logo);
        }

        $voucherBrand->delete();

        return redirect()->route('admin.voucher-brand.index')->with('success', 'Voucher brand deleted successfully.');
    }

    public function status(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:voucher_brands,id',
            'is_active' => 'required|boolean',
        ]);

        $voucherBrand = VoucherBrand::findOrFail($request->id);
        $voucherBrand->is_active = (bool) $request->is_active;
        $voucherBrand->save();

        return redirect()->route('admin.voucher-brand.index')->with('success', 'Voucher brand status updated successfully.');
    }
}
