@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Voucher List'))

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-ticket-percent"></i>
            </span>
            {{ \App\CPU\translate('Vouchers') }}
        </h3>
        <div>
            <a href="{{ route('admin.voucher.create') }}" class="btn btn-primary">
                {{ \App\CPU\translate('Add Voucher') }}
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.voucher.index') }}" class="row g-2">
                <div class="col-md-1">
                    <input type="text" name="id" value="{{ request('id') }}" class="form-control" placeholder="ID">
                </div>
                <div class="col-md-2">
                    <input type="text" name="title" value="{{ request('title') }}" class="form-control" placeholder="Title">
                </div>
                <div class="col-md-2">
                    <input type="text" name="code" value="{{ request('code') }}" class="form-control" placeholder="Code">
                </div>
                <div class="col-md-2">
                    <select name="voucher_brands_id" class="form-control">
                        <option value="">All Brands</option>
                        @foreach($voucherBrands as $brand)
                            <option value="{{ $brand->id }}" {{ (string) request('voucher_brands_id') === (string) $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Available</option>
                        <option value="purchased" {{ request('status') === 'purchased' ? 'selected' : '' }}>Purchased</option>
                        <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <select name="is_active" class="form-control">
                        <option value="">All</option>
                        <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.voucher.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Brand</th>
                        <th>Title</th>
                        <th>Code</th>
                        <th>Coin Price</th>
                        <th>Fiat Value</th>
                        <th>Status</th>
                        <th>Validity Days</th>
                        <th>Expires On</th>
                        <th>Active</th>
                        <th class="text-right" style="width: 220px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vouchers as $key => $voucher)
                        <tr>
                            <td>{{ $vouchers->firstItem() + $key }}</td>
                            <td>{{ optional($voucher->voucherBrand)->name ?? 'N/A' }}</td>
                            <td>{{ $voucher->title }}</td>
                            <td>{{ $voucher->code }}</td>
                            <td>{{ $voucher->coin_price }}</td>
                            <td>{{ $voucher->fiat_value }}</td>
                            <td>
                                <span class="badge {{ $voucher->status === 'purchased' ? 'badge-warning' : 'badge-info' }}">{{ ucfirst($voucher->status) }}</span>
                            </td>
                            <td>{{ $voucher->validity_days }}</td>
                            <td>
                                {{ optional($voucher->created_at)->addDays((int) $voucher->validity_days)?->format('Y-m-d') }}
                            </td>
                            <td>
                                @if($voucher->is_active)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-danger">Inactive</span>
                                @endif
                            </td>
                            <td class="text-right" style="width: 220px; white-space: nowrap;">
                                <div class="d-inline-flex gap-2 justify-content-end w-100">
                                    <a href="{{ route('admin.voucher.edit', $voucher->id) }}" class="btn btn-outline-primary btn-sm">Edit</a>

                                    <form action="{{ route('admin.voucher.active-status') }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $voucher->id }}">
                                        <input type="hidden" name="is_active" value="{{ $voucher->is_active ? 0 : 1 }}">
                                        <button type="submit" class="btn btn-outline-warning btn-sm">{{ $voucher->is_active ? 'Deactivate' : 'Activate' }}</button>
                                    </form>

                                    <form action="{{ route('admin.voucher.delete') }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this voucher?');">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $voucher->id }}">
                                        <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center py-4">No data found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-body d-flex justify-content-end">
            {{ $vouchers->links() }}
        </div>
    </div>
</div>
@endsection
