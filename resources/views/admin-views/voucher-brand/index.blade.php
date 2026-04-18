@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Voucher Brand List'))

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
                <div class="col-md-2">
                    <input type="text" name="id" value="{{ request('id') }}" class="form-control" placeholder="ID">
                </div>
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
                        <th>Status</th>
                        <th class="text-right" style="width: 220px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($voucherBrands as $key => $voucherBrand)
                        <tr>
                            <td>{{ $voucherBrands->firstItem() + $key }}</td>
                            <td>
                                @if($voucherBrand->logo)
                                    <img src="{{ asset('storage/voucher-brand/' . $voucherBrand->logo) }}" alt="logo" style="width:50px;height:50px;object-fit:cover;border-radius:6px;">
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>{{ $voucherBrand->name }}</td>
                            <td>
                                @if($voucherBrand->is_active)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-danger">Inactive</span>
                                @endif
                            </td>
                            <td class="text-right" style="width: 220px; white-space: nowrap;">
                                <div class="d-inline-flex gap-2 justify-content-end w-100">
                                    <a href="{{ route('admin.voucher-brand.edit', $voucherBrand->id) }}" class="btn btn-outline-primary btn-sm">Edit</a>

                                    <form action="{{ route('admin.voucher-brand.status') }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $voucherBrand->id }}">
                                        <input type="hidden" name="is_active" value="{{ $voucherBrand->is_active ? 0 : 1 }}">
                                        <button type="submit" class="btn btn-outline-warning btn-sm">
                                            {{ $voucherBrand->is_active ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.voucher-brand.delete') }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this voucher brand?');">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $voucherBrand->id }}">
                                        <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                                    </form>
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
