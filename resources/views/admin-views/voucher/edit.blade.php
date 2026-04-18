@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Edit Voucher'))

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-ticket-percent"></i>
            </span>
            {{ \App\CPU\translate('Edit Voucher') }}
        </h3>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.voucher.update', $voucher->id) }}" method="POST">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label>Voucher Brand</label>
                        <select name="voucher_brands_id" class="form-control" required>
                            <option value="">Select Brand</option>
                            @foreach($voucherBrands as $brand)
                                <option value="{{ $brand->id }}" {{ (int) old('voucher_brands_id', $voucher->voucher_brands_id) === (int) $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                            @endforeach
                        </select>
                        @error('voucher_brands_id')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                    <div class="col-md-6">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $voucher->title) }}" required>
                        @error('title')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                    <div class="col-md-12">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $voucher->description) }}</textarea>
                        @error('description')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                    <div class="col-md-4">
                        <label>Coin Price</label>
                        <input type="number" step="0.01" min="0" name="coin_price" class="form-control" value="{{ old('coin_price', $voucher->coin_price) }}" required>
                        @error('coin_price')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                    <div class="col-md-4">
                        <label>Fiat Value</label>
                        <input type="number" step="0.01" min="0" name="fiat_value" class="form-control" value="{{ old('fiat_value', $voucher->fiat_value) }}" required>
                        @error('fiat_value')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                    <div class="col-md-4">
                        <label>Code</label>
                        <input type="text" name="code" class="form-control" value="{{ old('code', $voucher->code) }}" required>
                        @error('code')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                    <div class="col-md-4">
                        <label>Status</label>
                        <select name="status" class="form-control" required>
                            <option value="available" {{ old('status', $voucher->status) === 'available' ? 'selected' : '' }}>Available</option>
                            <option value="purchased" {{ old('status', $voucher->status) === 'purchased' ? 'selected' : '' }}>Purchased</option>
                            <option value="expired" {{ old('status', $voucher->status) === 'expired' ? 'selected' : '' }}>Expired</option>
                        </select>
                        @error('status')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                    <div class="col-md-4">
                        <label>Validity Days</label>
                        <input type="number" min="0" name="validity_days" class="form-control" value="{{ old('validity_days', $voucher->validity_days) }}" required>
                        @error('validity_days')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" value="1" id="is_active" name="is_active" {{ old('is_active', $voucher->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('admin.voucher.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
