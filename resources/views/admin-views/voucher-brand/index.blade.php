@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Voucher Brand List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .voucher-brand-action-btn {
            width: 36px;
            height: 36px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
        }

        .voucher-brand-action-btn i {
            font-size: 18px;
            line-height: 1;
        }

        .voucher-brand-status-switch .form-check-input {
            width: 2.75em;
            height: 1.4em;
            cursor: pointer;
            margin: 0;
        }

        .voucher-brand-status-switch .form-check-input:focus {
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.2);
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
            {{ \App\CPU\translate('Voucher Brands') }}
        </h3>
        <div>
            <a href="{{ route('admin.voucher-brand.create') }}" class="btn btn-primary">
                {{ \App\CPU\translate('Add Voucher Brand') }}
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.voucher-brand.index') }}" class="row g-2">
                <div class="col-md-4">
                    <input type="text" name="name" value="{{ request('name') }}" class="form-control" placeholder="Name">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.voucher-brand.index') }}" class="btn btn-outline-secondary">Reset</a>
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
                        <th>Logo</th>
                        <th>Name</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($voucherBrands as $key => $voucherBrand)
                        <tr id="data-{{ $voucherBrand->id }}">
                            <td>{{ $voucherBrands->firstItem() + $key }}</td>
                            <td>
                                @if($voucherBrand->logo)
                                    <img src="{{ asset('storage/voucher-brand/' . $voucherBrand->logo) }}" alt="logo" style="width:50px;height:50px;object-fit:cover;border-radius:6px;">
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>{{ $voucherBrand->name }}</td>
                            <td class="text-center">
                                <div class="form-check form-switch voucher-brand-status-switch d-inline-flex justify-content-center mb-0">
                                    <input class="form-check-input voucher-brand-status"
                                        type="checkbox"
                                        role="switch"
                                        aria-label="{{ \App\CPU\translate('Activate or deactivate') }}"
                                        data-id="{{ $voucherBrand->id }}"
                                        {{ (int) $voucherBrand->is_active === 1 ? 'checked' : '' }}>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    <a class="btn btn-outline-primary btn-sm cursor-pointer voucher-brand-action-btn"
                                        title="{{ \App\CPU\translate('Edit') }}"
                                        href="{{ route('admin.voucher-brand.edit', $voucherBrand->id) }}">
                                        <i class="mdi mdi-pencil-outline"></i>
                                    </a>
                                    <a class="btn btn-outline-danger btn-sm cursor-pointer delete voucher-brand-action-btn"
                                        title="{{ \App\CPU\translate('Delete') }}"
                                        id="{{ $voucherBrand->id }}">
                                        <i class="mdi mdi-delete-outline"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">No data found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-body d-flex justify-content-end">
            {{ $voucherBrands->links() }}
        </div>
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

    $(document).on('change', '.voucher-brand-status', function() {
        var id = $(this).data('id');
        var isActive = $(this).prop('checked') ? 1 : 0;
        var $toggle = $(this);

        $.ajax({
            url: "{{ route('admin.voucher-brand.status') }}",
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
                    url: "{{ route('admin.voucher-brand.delete') }}",
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    data: { id: id },
                    success: function(response) {
                        if (response && response.status === false) {
                            notifyError(response.message || '{{ \App\CPU\translate('Failed to delete voucher brand') }}');
                            return;
                        }
                        $('#data-' + id).remove();
                        notifySuccess('{{ \App\CPU\translate('Voucher brand deleted successfully') }}');
                    },
                    error: function(xhr) {
                        var message = (xhr.responseJSON && xhr.responseJSON.message)
                            ? xhr.responseJSON.message
                            : '{{ \App\CPU\translate('Failed to delete voucher brand') }}';
                        notifyError(message);
                    }
                });
            }
        });
    });
</script>
@endpush
