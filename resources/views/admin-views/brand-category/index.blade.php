@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Brand Category List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .brand-category-action-btn {
            width: 36px;
            height: 36px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
        }

        .brand-category-action-btn i {
            font-size: 18px;
            line-height: 1;
        }

        .brand-category-status-switch .form-check-input {
            width: 2.75em;
            height: 1.4em;
            cursor: pointer;
            margin: 0;
        }

        .brand-category-status-switch .form-check-input:focus {
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.2);
        }

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

        .premium-pagination-shell .pagination {
            margin: 0;
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
                <i class="mdi mdi-shape-outline"></i>
            </span>
            {{ \App\CPU\translate('Brand Categories') }}
        </h3>
        <div>
            <a href="{{ route('admin.brand-category.create') }}" class="btn btn-primary">
                {{ \App\CPU\translate('Add Brand Category') }}
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.brand-category.index') }}" class="row g-2 align-items-center">
                <div class="col-6 col-md-1">
                    <input type="text" name="id" value="{{ request('id') }}" class="form-control" placeholder="ID">
                </div>
                <div class="col-6 col-md-2">
                    <input type="text" name="name" value="{{ request('name') }}" class="form-control" placeholder="Name">
                </div>
                <div class="col-md-3">
                    <select name="parent_id" class="form-select">
                        <option value="">All Types</option>
                        <option value="0" {{ request('parent_id') === '0' ? 'selected' : '' }}>Root Category</option>
                        @foreach($parents as $parent)
                            <option value="{{ $parent->id }}" {{ request('parent_id') == $parent->id ? 'selected' : '' }}>
                                Sub of {{ $parent->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="mdi mdi-filter-variant me-1"></i> Filter
                    </button>
                    <a href="{{ route('admin.brand-category.index') }}" class="btn btn-outline-secondary flex-fill">
                        <i class="mdi mdi-close me-1"></i> Reset
                    </a>
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
                        <th>Name</th>
                        <th>Type</th>
                        <th>Parent</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($brandCategories as $key => $brandCategory)
                        <tr id="data-{{ $brandCategory->id }}">
                            <td>{{ $brandCategories->firstItem() + $key }}</td>
                            <td>{{ $brandCategory->name }}</td>
                            <td>
                                @if((int) $brandCategory->parent_id === 0)
                                    <span class="badge badge-info">Category</span>
                                @else
                                    <span class="badge badge-dark">Sub Category</span>
                                @endif
                            </td>
                            <td>{{ optional($brandCategory->parent)->name ?? 'N/A' }}</td>
                            <td class="text-center">
                                <div class="form-check form-switch brand-category-status-switch d-inline-flex justify-content-center mb-0">
                                    <input class="form-check-input brand-category-status"
                                        type="checkbox"
                                        role="switch"
                                        aria-label="{{ \App\CPU\translate('Activate or deactivate') }}"
                                        data-id="{{ $brandCategory->id }}"
                                        {{ (int) $brandCategory->status === 1 ? 'checked' : '' }}>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    <a class="btn btn-outline-primary btn-sm cursor-pointer brand-category-action-btn"
                                        title="{{ \App\CPU\translate('Edit') }}"
                                        href="{{ route('admin.brand-category.edit', $brandCategory->id) }}">
                                        <i class="mdi mdi-pencil-outline"></i>
                                    </a>
                                    <a class="btn btn-outline-danger btn-sm cursor-pointer delete brand-category-action-btn"
                                        title="{{ \App\CPU\translate('Delete') }}"
                                        id="{{ $brandCategory->id }}">
                                        <i class="mdi mdi-delete-outline"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">No data found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($brandCategories->hasPages())
            <div class="premium-pagination-wrap">
                <div class="premium-pagination-shell">
                    <div class="premium-pagination-inline">
                        {!! $brandCategories->onEachSide(1)->links('vendor.pagination.premium') !!}
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

    $(document).on('change', '.brand-category-status', function() {
        var id = $(this).data('id');
        var status = $(this).prop('checked') ? 1 : 0;
        var $toggle = $(this);

        $.ajax({
            url: "{{ route('admin.brand-category.status') }}",
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            data: { id: id, status: status },
            success: function(response) {
                if (response.status) {
                    notifySuccess(response.message || '{{ \App\CPU\translate('Status updated successfully') }}');
                    if (status === 0) {
                        setTimeout(function() { location.reload(); }, 800);
                    }
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
                    url: "{{ route('admin.brand-category.delete') }}",
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    data: { id: id },
                    success: function(response) {
                        if (response && response.status === false) {
                            notifyError(response.message || '{{ \App\CPU\translate('Failed to delete brand category') }}');
                            return;
                        }
                        $('#data-' + id).remove();
                        notifySuccess('{{ \App\CPU\translate('Brand category deleted successfully') }}');
                    },
                    error: function(xhr) {
                        var message = (xhr.responseJSON && xhr.responseJSON.message)
                            ? xhr.responseJSON.message
                            : '{{ \App\CPU\translate('Failed to delete brand category') }}';
                        notifyError(message);
                    }
                });
            }
        });
    });
</script>
@endpush
