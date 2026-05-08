@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Sale Add'))

@push('css_or_js')
<meta name="csrf-token" content="{{ csrf_token() }}">
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
            <div class="card">
                <div class="card-header">
                    <h4>{{\App\CPU\translate('create_sale')}}</h4>
                </div>
                <div class="card-body">
                    <form action="{{route('admin.sale.store')}}" method="post" enctype="multipart/form-data"
                        class="banner_form" id="saleAddForm" novalidate>
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">{{\App\CPU\translate('Full Name')}} <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="saleName"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name') }}"
                                        maxlength="40"
                                        required>
                                    <small class="text-muted">Max 40 characters. Letters and spaces only.</small>
                                    @error('name') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                                    <span class="text-danger d-none small" id="nameError"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">{{\App\CPU\translate('email')}} <span class="text-danger">*</span></label>
                                    <input type="email" name="email" id="saleEmail"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email') }}"
                                        pattern="^[^@\s]+@[^@\s]+\.[^@\s]{2,}$"
                                        title="Enter a valid email address (e.g. user@example.com)"
                                        required>
                                    <span class="text-danger d-none small" id="emailError">Please enter a valid email (e.g. user@example.com).</span>
                                    @error('email') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">{{\App\CPU\translate('mobile')}} <span class="text-danger">*</span></label>
                                    <input type="tel" name="mobile" id="saleMobile"
                                        class="form-control @error('mobile') is-invalid @enderror"
                                        value="{{ old('mobile') }}"
                                        maxlength="10"
                                        inputmode="numeric"
                                        required>
                                    <small class="text-muted">Exactly 10 digits.</small>
                                    @error('mobile') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">{{\App\CPU\translate('password')}} <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" name="password" id="salePassword"
                                            class="form-control @error('password') is-invalid @enderror"
                                            minlength="8" maxlength="32" required>
                                        <button type="button" class="btn btn-outline-secondary" id="togglePassword" tabindex="-1">
                                            <i class="mdi mdi-eye" id="togglePasswordIcon"></i>
                                        </button>
                                    </div>
                                    <small class="text-muted">8–32 characters.</small>
                                    @error('password') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">{{\App\CPU\translate('confirm_password')}} <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" name="password_confirmation" id="salePasswordConfirm"
                                            class="form-control"
                                            minlength="8" maxlength="32" required>
                                        <button type="button" class="btn btn-outline-secondary" id="togglePasswordConfirm" tabindex="-1">
                                            <i class="mdi mdi-eye" id="togglePasswordConfirmIcon"></i>
                                        </button>
                                    </div>
                                    <span class="text-danger d-none small" id="passwordMismatch">Passwords do not match.</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">{{\App\CPU\translate('image')}} <span class="text-danger">*</span></label>
                                    <input type="file" name="image" class="form-control @error('image') is-invalid @enderror"
                                        id="mbimageFileUploader" accept="image/*" required>
                                    <img id="mbImageviewer" src="{{ asset('assets/logo/logo-icon.png') }}" alt="Image Preview" class="img-thumbnail mt-2" style="max-width: 100px;">
                                    @error('image') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            <div class="col-md-12 d-flex justify-content-end gap-3">
                                <button type="submit" class="btn btn-primary px-4">{{ \App\CPU\translate('Save')}}</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    // Image preview
    $('#mbimageFileUploader').change(function() {
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) { $('#mbImageviewer').attr('src', e.target.result); };
            reader.readAsDataURL(this.files[0]);
        }
    });

    // Email validation: must have a TLD (at least one dot after @)
    var emailRegex = /^[^@\s]+@[^@\s]+\.[^@\s]{2,}$/;
    $('#saleEmail').on('input blur', function() {
        var val = $(this).val();
        var valid = emailRegex.test(val);
        $('#emailError').toggleClass('d-none', valid || val === '');
        $(this).toggleClass('is-invalid', !valid && val !== '');
    });

    // Block non-numeric input on mobile
    $('#saleMobile').on('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
        if (this.value.length > 10) this.value = this.value.slice(0, 10);
    });

    // Name validation: letters + spaces only, max 40, no 4+ consecutive same chars
    $('#saleName').on('input', function() {
        var val = this.value;
        var err = '';
        if (/[^a-zA-Z ]/.test(val)) {
            err = 'Only letters and spaces are allowed.';
            this.value = val.replace(/[^a-zA-Z ]/g, '');
        } else if (/(.)(\1{3,})/.test(val)) {
            err = 'Repeating the same character is not allowed.';
            this.value = val.replace(/(.)(\1{3,})/g, '$1$1$1');
        } else if (val.length > 40) {
            err = 'Name cannot exceed 40 characters.';
        }
        $('#nameError').text(err).toggle(err !== '');
        $(this).toggleClass('is-invalid', err !== '');
    });

    // Toggle password visibility
    $('#togglePassword').click(function() {
        var input = $('#salePassword');
        var isText = input.attr('type') === 'text';
        input.attr('type', isText ? 'password' : 'text');
        $('#togglePasswordIcon').toggleClass('mdi-eye mdi-eye-off');
    });
    $('#togglePasswordConfirm').click(function() {
        var input = $('#salePasswordConfirm');
        var isText = input.attr('type') === 'text';
        input.attr('type', isText ? 'password' : 'text');
        $('#togglePasswordConfirmIcon').toggleClass('mdi-eye mdi-eye-off');
    });

    // Password match check
    $('#salePasswordConfirm, #salePassword').on('input', function() {
        var match = $('#salePassword').val() === $('#salePasswordConfirm').val();
        $('#passwordMismatch').toggleClass('d-none', match);
        $('#salePasswordConfirm').toggleClass('is-invalid', !match && $('#salePasswordConfirm').val().length > 0);
    });

    // Form submit guard
    $('#saleAddForm').on('submit', function(e) {
        var name = $('#saleName').val();
        var email = $('#saleEmail').val();
        var mobile = $('#saleMobile').val();
        var pass = $('#salePassword').val();
        var passConf = $('#salePasswordConfirm').val();
        var valid = true;

        if (!emailRegex.test(email)) {
            $('#emailError').removeClass('d-none');
            $('#saleEmail').addClass('is-invalid');
            valid = false;
        }
        if (/[^a-zA-Z ]/.test(name) || /(.)(\1{3,})/.test(name) || name.trim().length === 0) {
            $('#nameError').text('Please enter a valid name (letters/spaces only, no repeating characters).').show();
            $('#saleName').addClass('is-invalid');
            valid = false;
        }
        if (!/^[0-9]{10}$/.test(mobile)) {
            $('#saleMobile').addClass('is-invalid');
            valid = false;
        }
        if (pass !== passConf) {
            $('#passwordMismatch').removeClass('d-none');
            $('#salePasswordConfirm').addClass('is-invalid');
            valid = false;
        }
        if (!valid) e.preventDefault();
    });
</script>
@endpush