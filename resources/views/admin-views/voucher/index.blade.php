@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Voucher List'))

@push('css_or_js')
<style>
    .premium-pagination-wrap {
        border-top: 1px solid #e8ebef;
        margin-top: 22px;
        padding: 12px 18px 16px;
    }

    .premium-pagination-shell {
        display: flex;
        justify-content: flex-end;
        align-items: center;
    }

    .premium-pagination-inline {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        overflow-x: auto;
    }

    .premium-pagination-nav {
        float: none;
        margin: 0;
        flex: 0 0 auto;
    }

    .premium-pagination-shell .pagination {
        margin: 0;
    }

    .voucher-action-group {
        display: inline-flex;
        align-items: center;
        justify-content: flex-end;
        gap: 8px;
        width: 100%;
    }

    .voucher-action-btn {
        width: 36px;
        height: 36px;
        min-width: 36px;
        border-radius: 10px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .voucher-action-btn i {
        font-size: 17px;
        line-height: 1;
    }

    @media (max-width: 767px) {
        .premium-pagination-wrap {
            padding: 12px;
        }

        .premium-pagination-inline {
            justify-content: flex-end;
        }
    }
</style>
@endpush

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
                        <th>Sales User</th>
                        <th>Brand</th>
                        <th>Title</th>
                        <th>Code</th>
                        <th>Coin Price</th>
                        <th>Fiat Value</th>
                        <th>Status</th>
                        <th>Validity</th>
                        <th>Usage</th>
                        <th>Active</th>
                        <th class="text-right" style="width: 220px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vouchers as $key => $voucher)
                        <tr>
                            <td>{{ $vouchers->firstItem() + $key }}</td>
                            <td>{{ optional($voucher->sale)->name ?? 'N/A' }}</td>
                            <td>{{ optional($voucher->voucherBrand)->name ?? 'N/A' }}</td>
                            <td>{{ $voucher->title }}</td>
                            <td>{{ $voucher->code }}</td>
                            <td>{{ $voucher->coin_price }}</td>
                            <td>{{ $voucher->fiat_value }}</td>
                            <td>
                                <span class="badge {{ $voucher->status === 'purchased' ? 'badge-warning' : 'badge-info' }}">{{ ucfirst($voucher->status) }}</span>
                            </td>
                            <td>
                                {{ optional($voucher->valid_from)->format('d/m/Y') ?? 'N/A' }}
                                to
                                {{ optional($voucher->valid_to)->format('d/m/Y') ?? 'N/A' }}
                            </td>
                            <td>
                                {{ (int) $voucher->used_count }} / {{ $voucher->max_uses ?: 'Unlimited' }}
                                <br>
                                <small class="text-muted">Per user: {{ (int) ($voucher->max_uses_per_user ?? 1) }}</small>
                            </td>
                            <td>
                                @if($voucher->is_active)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-danger">Inactive</span>
                                @endif
                            </td>
                            <td class="text-right" style="width: 220px; white-space: nowrap;">
                                <div class="voucher-action-group">
                                    <a href="{{ route('admin.voucher.edit', $voucher->id) }}"
                                       class="btn btn-outline-primary btn-sm voucher-action-btn"
                                       title="Edit"
                                       aria-label="Edit">
                                        <i class="mdi mdi-pencil-outline"></i>
                                    </a>

                                    <form action="{{ route('admin.voucher.active-status') }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $voucher->id }}">
                                        <input type="hidden" name="is_active" value="{{ $voucher->is_active ? 0 : 1 }}">
                                        <button type="submit"
                                                class="btn btn-outline-warning btn-sm voucher-action-btn"
                                                title="{{ $voucher->is_active ? 'Deactivate' : 'Activate' }}"
                                                aria-label="{{ $voucher->is_active ? 'Deactivate' : 'Activate' }}">
                                            <i class="mdi {{ $voucher->is_active ? 'mdi-toggle-switch-off-outline' : 'mdi-toggle-switch' }}"></i>
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.voucher.delete') }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this voucher?');">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $voucher->id }}">
                                        <button type="submit"
                                                class="btn btn-outline-danger btn-sm voucher-action-btn"
                                                title="Delete"
                                                aria-label="Delete">
                                            <i class="mdi mdi-delete-outline"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="text-center py-4">No data found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($vouchers->hasPages())
            <div class="premium-pagination-wrap">
                <div class="premium-pagination-shell">
                    <div class="premium-pagination-inline">
                        {!! $vouchers->onEachSide(1)->links('vendor.pagination.premium') !!}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
