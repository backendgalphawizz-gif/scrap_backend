@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Profile Settings'))

@push('css_or_js')
<link href="{{asset('public/assets/back-end/css/croppie.css')}}" rel="stylesheet">
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="content-wrapper">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-home"></i>
            </span> Settings
        </h3>

        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <span></span>Admin Profile
                    <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-header">
                    <h4>{{\App\CPU\translate('Basic')}} {{\App\CPU\translate('information')}}</h4>
                </div>

                <div class="card-body">

                    <form id="profileForm" action="{{route('admin.profile.update',[$data->id])}}" method="post" enctype="multipart/form-data" onsubmit="return validatePasswordMatch()">
                        @csrf

                        <div class="row g-3 mb-3">

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{\App\CPU\translate('Full')}} {{\App\CPU\translate('name')}}</label>
                                    <input type="text" class="form-control"
                                        name="name"
                                        value="{{$data->name}}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{\App\CPU\translate('Phone')}}</label>
                                    <input type="text"
                                        class="form-control"
                                        name="phone"
                                        value="{{$data->phone}}"
                                        minlength="10"
                                        maxlength="10"
                                        onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{\App\CPU\translate('Email')}}</label>
                                    <input type="email"
                                        class="form-control"
                                        name="email"
                                        value="{{$data->email}}"
                                        readonly>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{\App\CPU\translate('Profile_Image')}}</label>

                                    <input type="file"
                                        name="image"
                                        id="customFileUpload"
                                        class="form-control"
                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">

                                    <img
                                        id="viewer"
                                        src="{{$data->image}}"
                                        onerror="this.src='{{asset('assets/logo/logo-2.png')}}'"
                                        class="img-thumbnail mt-2"
                                        style="width:100px;height:100px;object-fit:cover;">
                                </div>
                            </div>

                        </div>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{\App\CPU\translate('Old Password')}}</label>
                                    <input type="password" class="form-control" name="old_password" autocomplete="current-password" minlength="6">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{\App\CPU\translate('New Password')}}</label>
                                    <input type="password" class="form-control" name="password" autocomplete="new-password" minlength="6">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{\App\CPU\translate('Confirm Password')}}</label>
                                    <input type="password" class="form-control" name="password_confirmation" autocomplete="new-password" minlength="6">
                                </div>
                            </div>
                            <div class="col-12">
                                <div id="password-error" class="text-danger mb-2" style="display:none;"></div>
                            </div>
                            <div class="col-md-12 d-flex justify-content-end gap-3">
                                <button type="submit" class="btn btn-primary px-4">
                                    {{\App\CPU\translate('Save changes')}}
                                </button>
                            </div>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

</div>
@endsection


@push('script_2')
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#viewer').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#customFileUpload").change(function() {
        readURL(this);
    });

    function validatePasswordMatch(e) {
        var password = document.querySelector('input[name="password"]').value;
        var confirmPassword = document.querySelector('input[name="password_confirmation"]').value;
        var errorDiv = document.getElementById('password-error');
        if (password !== confirmPassword) {
            errorDiv.textContent = 'New Password and Confirm Password do not match.';
            errorDiv.style.display = 'block';
            if (e) e.preventDefault();
            return false;
        } else {
            errorDiv.textContent = '';
            errorDiv.style.display = 'none';
        }
        return true;
    }
</script>
@endpush