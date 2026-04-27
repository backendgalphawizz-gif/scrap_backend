@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Brand Category List'))

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
            <form method="GET" action="{{ route('admin.brand-category.index') }}" class="row g-2">
                <div class="col-md-2">
                    <input type="text" name="id" value="{{ request('id') }}" class="form-control" placeholder="ID">
                </div>
                <div class="col-md-3">
                    <input type="text" name="name" value="{{ request('name') }}" class="form-control" placeholder="Name">
                </div>
                <div class="col-md-3">
                    <select name="parent_id" class="form-control">
                        <option value="">All Types</option>
                        <option value="0" {{ request('parent_id') === '0' ? 'selected' : '' }}>Root Category</option>
                        @foreach($parents as $parent)
                            <option value="{{ $parent->id }}" {{ request('parent_id') == $parent->id ? 'selected' : '' }}>
                                Sub Category of {{ $parent->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.brand-category.index') }}" class="btn btn-outline-secondary">Reset</a>
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
                        <th>Status</th>
                        <th class="text-right" style="width: 220px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($brandCategories as $key => $brandCategory)
                        <tr>
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
                            <td>
                                @if($brandCategory->status)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-danger">Inactive</span>
                                @endif
                            </td>
                            <td class="text-right" style="width: 220px; white-space: nowrap;">
                                <div class="d-inline-flex gap-2 justify-content-end w-100">
                                    <a href="{{ route('admin.brand-category.edit', $brandCategory->id) }}" class="btn btn-outline-primary btn-sm">Edit</a>

                                    <form action="{{ route('admin.brand-category.status') }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $brandCategory->id }}">
                                        <input type="hidden" name="status" value="{{ $brandCategory->status ? 0 : 1 }}">
                                        <button type="submit" class="btn btn-outline-warning btn-sm">
                                            {{ $brandCategory->status ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.brand-category.delete') }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this brand category?');">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $brandCategory->id }}">
                                        <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                                    </form>
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
