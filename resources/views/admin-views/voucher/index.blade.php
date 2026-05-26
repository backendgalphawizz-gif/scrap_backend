@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Voucher List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

    .voucher-status-switch .form-check-input {
        width: 2.75em;
        height: 1.4em;
        cursor: pointer;
        margin: 0;
    }

    .voucher-status-switch .form-check-input:focus {
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.2);
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
                        <th class="text-center">Active</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vouchers as $key => $voucher)
                        <tr id="data-{{ $voucher->id }}">
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
                                {{ \App\CPU\Helpers::formatAdminDate($voucher->valid_from, 'N/A') }}
                                to
                                {{ \App\CPU\Helpers::formatAdminDate($voucher->valid_to, 'N/A') }}
                            </td>
                            <td>
                                {{ (int) $voucher->used_count }} / {{ $voucher->max_uses ?: 'Unlimited' }}
                                <br>
                                <small class="text-muted">Per user: {{ (int) ($voucher->max_uses_per_user ?? 1) }}</small>
                            </td>
                            <td class="text-center">
                                <div class="form-check form-switch voucher-status-switch d-inline-flex justify-content-center mb-0">
                                    <input class="form-check-input voucher-active-status"
                                        type="checkbox"
                                        role="switch"
                                        aria-label="{{ \App\CPU\translate('Activate or deactivate') }}"
                                        data-id="{{ $voucher->id }}"
                                        {{ (int) $voucher->is_active === 1 ? 'checked' : '' }}>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="{{ route('admin.voucher.edit', $voucher->id) }}"
                                       class="btn btn-outline-primary btn-sm voucher-action-btn"
                                       title="{{ \App\CPU\translate('Edit') }}"
                                       aria-label="{{ \App\CPU\translate('Edit') }}">
                                        <i class="mdi mdi-pencil-outline"></i>
                                    </a>
                                    <a class="btn btn-outline-danger btn-sm voucher-action-btn cursor-pointer delete"
                                       title="{{ \App\CPU\translate('Delete') }}"
                                       aria-label="{{ \App\CPU\translate('Delete') }}"
                                       id="{{ $voucher->id }}">
                                        <i class="mdi mdi-delete-outline"></i>
                                    </a>
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

@push('script')
<script>
    function notifySuccess(message) {
        if (typeof toastr !== 'undefined' && toastr.success) {
            toastr.success(message);
            return;
        }
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: message,
            showConfirmButton: false,
            timer: 2000
        });
    }

    function notifyError(message) {
        if (typeof toastr !== 'undefined' && toastr.error) {
            toastr.error(message);
            return;
        }
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: message,
            showConfirmButton: false,
            timer: 2500
        });
    }

    $(document).on('change', '.voucher-active-status', function() {
        var id = $(this).data('id');
        var isActive = $(this).prop('checked') ? 1 : 0;
        var $toggle = $(this);

        $.ajax({
            url: "{{ route('admin.voucher.active-status') }}",
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            data: { id: id, is_active: isActive },
            success: function(response) {
                if (response.status) {
                    notifySuccess(response.message || '{{ \App\CPU\translate('Status updated successfully') }}');
                } else {
                    notifyError(response.message || '{{ \App\CPU\translate('Failed to update status') }}');
                    $toggle.prop('checked', !$toggle.prop('checked'));
                }
            },
            error: function(xhr) {
                var message = (xhr.responseJSON && xhr.responseJSON.message)
                    ? xhr.responseJSON.message
                    : '{{ \App\CPU\translate('Failed to update status') }}';
                notifyError(message);
                $toggle.prop('checked', !$toggle.prop('checked'));
            }
        });
    });

    $(document).on('click', '.delete', function() {
        var id = $(this).attr('id');
        Swal.fire({
            title: '{{ \App\CPU\translate('Are you sure ?') }}',
            text: "{{ \App\CPU\translate('You won\'t be able to revert this!') }}",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '{{ \App\CPU\translate('Yes, delete it!') }}'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('admin.voucher.delete') }}",
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    data: { id: id },
                    success: function(response) {
                        if (response && response.status === false) {
                            notifyError(response.message || '{{ \App\CPU\translate('Failed to delete voucher') }}');
                            return;
                        }
                        $('#data-' + id).remove();
                        notifySuccess('{{ \App\CPU\translate('Voucher deleted successfully') }}');
                    },
                    error: function(xhr) {
                        var message = (xhr.responseJSON && xhr.responseJSON.message)
                            ? xhr.responseJSON.message
                            : '{{ \App\CPU\translate('Failed to delete voucher') }}';
                        notifyError(message);
                    }
                });
            }
        });
    });
</script>
@endpush
