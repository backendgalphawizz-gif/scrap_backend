@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Sale Edit'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .sale-edit-header {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: #fff;
            border-radius: 14px;
            padding: 1rem 1.25rem;
        }

        .sale-edit-section .card-header {
            background: #f8fafc;
            border-bottom: 1px solid #edf2f7;
        }

        .sale-avatar-preview {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 12px;
            border: 1px solid #dbe4f0;
        }
    </style>
@endpush

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-home"></i>
            </span> {{\App\CPU\translate('Sale')}}
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <span></span>{{\App\CPU\translate('Sale')}} <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                </li>
            </ul>
        </nav>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="sale-edit-header mb-3 d-flex flex-column flex-md-row justify-content-between gap-2">
                <div>
                    <h4 class="mb-1">{{ \App\CPU\translate('edit_sale') }}</h4>
                    <p class="mb-0">Update all sale profile fields, account status, KYC, and bank details.</p>
                </div>
                <div class="text-md-end">
                    <span class="badge badge-light text-dark px-3 py-2 text-uppercase">{{ $sale->status ?? 'pending' }}</span>
                </div>
            </div>

            <form action="{{ route('admin.sale.update', $sale->id) }}" method="post" enctype="multipart/form-data" class="banner_form">
                @csrf
                <div class="card sale-edit-section mb-3">
                    <div class="card-header">
                        <h5 class="mb-0">Basic Profile</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="name" class="form-label">{{ \App\CPU\translate('name') }}</label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $sale->name) }}" required>
                                @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="email" class="form-label">{{ \App\CPU\translate('email') }}</label>
                                <input type="email" id="email" class="form-control" value="{{ $sale->email }}" readonly disabled>
                            </div>

                            <div class="col-md-4">
                                <label for="mobile" class="form-label">{{ \App\CPU\translate('mobile') }}</label>
                                <input type="tel" id="mobile" class="form-control" value="{{ $sale->mobile }}" readonly disabled>
                            </div>
                            <div class="col-md-1">
                                <label class="form-label"></label>
                                <div>
                                    <img id="saleImagePreview" src="{{ $sale->image }}" class="sale-avatar-preview" alt="sale image">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="saleImage" class="form-label">{{ \App\CPU\translate('profile_image') }}</label>
                                <input type="file" name="image" id="saleImage" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                                @error('image') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                           
                            <div class="col-md-4">
                                <label for="status" class="form-label">{{ \App\CPU\translate('status') }}</label>
                                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                                    <option value="pending" {{ old('status', $sale->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="active" {{ old('status', $sale->status) === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $sale->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="blocked" {{ old('status', $sale->status) === 'blocked' ? 'selected' : '' }}>Blocked</option>
                                </select>
                                @error('status') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="balance" class="form-label">{{ \App\CPU\translate('balance') }}</label>
                                <input type="number" step="0.01" id="balance" class="form-control" value="{{ $sale->balance }}" readonly disabled>
                            </div>
                            <div class="col-md-4">
                                <label for="referral_code" class="form-label">{{ \App\CPU\translate('referral_code') }}</label>
                                <input type="text" id="referral_code" class="form-control" value="{{ $sale->referral_code }}" readonly disabled>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card sale-edit-section mb-3">
                    <div class="card-header">
                        <h5 class="mb-0">PAN and KYC</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="pan_number" class="form-label">{{ \App\CPU\translate('pan_number') }}</label>
                                <input type="text" name="pan_number" id="pan_number" class="form-control @error('pan_number') is-invalid @enderror" value="{{ old('pan_number', $sale->pan_number) }}">
                                @error('pan_number') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="pan_status" class="form-label">{{ \App\CPU\translate('pan_status') }}</label>
                                <select name="pan_status" id="pan_status" class="form-select @error('pan_status') is-invalid @enderror">
                                    <option value="Not Submitted" {{ old('pan_status', $sale->pan_status) === 'Not Submitted' ? 'selected' : '' }}>Not Submitted</option>
                                    <option value="Submitted" {{ old('pan_status', $sale->pan_status) === 'Submitted' ? 'selected' : '' }}>Submitted</option>
                                    <option value="Under Verification" {{ old('pan_status', $sale->pan_status) === 'Under Verification' ? 'selected' : '' }}>Under Verification</option>
                                    <option value="Verified" {{ old('pan_status', $sale->pan_status) === 'Verified' ? 'selected' : '' }}>Verified</option>
                                    <option value="Rejected" {{ old('pan_status', $sale->pan_status) === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                                @error('pan_status') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="kyc_status" class="form-label">{{ \App\CPU\translate('kyc_status') }}</label>
                                <select name="kyc_status" id="kyc_status" class="form-select @error('kyc_status') is-invalid @enderror">
                                    <option value="pending" {{ old('kyc_status', $sale->kyc_status) === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="verified" {{ old('kyc_status', $sale->kyc_status) === 'verified' ? 'selected' : '' }}>Verified</option>
                                    <option value="rejected" {{ old('kyc_status', $sale->kyc_status) === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                                @error('kyc_status') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="pan_image" class="form-label">{{ \App\CPU\translate('pan_image') }}</label>
                                <input type="file" name="pan_image" id="panImageUploader" class="form-control @error('pan_image') is-invalid @enderror" accept="image/*">
                                @error('pan_image') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="pan_rejection_reason" class="form-label">{{ \App\CPU\translate('pan_rejection_reason') }}</label>
                                <textarea name="pan_rejection_reason" id="pan_rejection_reason" class="form-control @error('pan_rejection_reason') is-invalid @enderror" rows="3">{{ old('pan_rejection_reason', $sale->pan_rejection_reason) }}</textarea>
                                @error('pan_rejection_reason') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="kyc_rejection_reason" class="form-label">{{ \App\CPU\translate('kyc_rejection_reason') }}</label>
                                <textarea name="kyc_rejection_reason" id="kyc_rejection_reason" class="form-control @error('kyc_rejection_reason') is-invalid @enderror" rows="3">{{ old('kyc_rejection_reason', $sale->kyc_rejection_reason) }}</textarea>
                                @error('kyc_rejection_reason') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">PAN Image Preview</label>
                                <div>
                                    <img id="panImagePreview" src="{{ $sale->pan_image }}" class="sale-avatar-preview" alt="pan image">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card sale-edit-section mb-3">
                    <div class="card-header">
                        <h5 class="mb-0">{{ \App\CPU\translate('bank_detail') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="bank_name" class="form-label">{{ \App\CPU\translate('bank_name') }}</label>
                                <input type="text" name="bank_name" id="bank_name" class="form-control @error('bank_name') is-invalid @enderror" value="{{ old('bank_name', $bankDetail['bank_name'] ?? '') }}">
                                @error('bank_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="account_number" class="form-label">{{ \App\CPU\translate('account_number') }}</label>
                                <input type="text" name="account_number" id="account_number" class="form-control @error('account_number') is-invalid @enderror" value="{{ old('account_number', $bankDetail['account_number'] ?? '') }}">
                                @error('account_number') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="ifsc_code" class="form-label">{{ \App\CPU\translate('ifsc_code') }}</label>
                                <input type="text" name="ifsc_code" id="ifsc_code" class="form-control @error('ifsc_code') is-invalid @enderror" value="{{ old('ifsc_code', $bankDetail['ifsc_code'] ?? '') }}">
                                @error('ifsc_code') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="branch_name" class="form-label">{{ \App\CPU\translate('branch_name') }}</label>
                                <input type="text" name="branch_name" id="branch_name" class="form-control @error('branch_name') is-invalid @enderror" value="{{ old('branch_name', $bankDetail['branch_name'] ?? '') }}">
                                @error('branch_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="bank_status" class="form-label">{{ \App\CPU\translate('bank_status') }}</label>
                                <select name="bank_status" id="bank_status" class="form-select @error('bank_status') is-invalid @enderror">
                                    <option value="Not Submitted" {{ old('bank_status', $sale->bank_status) === 'Not Submitted' ? 'selected' : '' }}>Not Submitted</option>
                                    <option value="Submitted" {{ old('bank_status', $sale->bank_status) === 'Submitted' ? 'selected' : '' }}>Submitted</option>
                                    <option value="Under Verification" {{ old('bank_status', $sale->bank_status) === 'Under Verification' ? 'selected' : '' }}>Under Verification</option>
                                    <option value="Verified" {{ old('bank_status', $sale->bank_status) === 'Verified' ? 'selected' : '' }}>Verified</option>
                                    <option value="Rejected" {{ old('bank_status', $sale->bank_status) === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                                @error('bank_status') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-9">
                                <label for="bank_rejection_reason" class="form-label">{{ \App\CPU\translate('bank_rejection_reason') }}</label>
                                <textarea name="bank_rejection_reason" id="bank_rejection_reason" class="form-control @error('bank_rejection_reason') is-invalid @enderror" rows="3">{{ old('bank_rejection_reason', $sale->bank_rejection_reason) }}</textarea>
                                @error('bank_rejection_reason') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-3">
                    <a href="{{ route('admin.sale.list') }}" class="btn btn-secondary">{{ \App\CPU\translate('cancel') }}</a>
                    <button type="submit" class="btn btn-primary px-4">{{ \App\CPU\translate('update') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script')
    <script>
        $('#saleImage').change(function () {
            readURL(this, '#saleImagePreview');
        });

        $('#panImageUploader').change(function () {
            readURL(this, '#panImagePreview');
        });

        function readURL(input, targetId) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $(targetId).attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endpush