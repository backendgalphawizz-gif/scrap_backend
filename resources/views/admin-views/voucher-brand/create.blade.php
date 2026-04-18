@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Create Voucher Brand'))

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-ticket-percent"></i>
            </span>
            {{ \App\CPU\translate('Create Voucher Brand') }}
        </h3>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.voucher-brand.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group mb-3">
                    <label>Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label>Logo</label>
                    <input type="file" name="logo" class="form-control" accept="image/*" required>
                    @error('logo')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group form-check mb-4">
                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Active</label>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="{{ route('admin.voucher-brand.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
