@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Employee Add'))

@push('css_or_js')
<link href="{{asset('public/assets/back-end')}}/css/select2.min.css" rel="stylesheet" />
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')

<div class="content-wrapper">

    <!-- Page Header -->
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-account-plus"></i>
            </span>
            {{\App\CPU\translate('Add_New_Admin')}}
        </h3>

        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    {{\App\CPU\translate('Employee')}}
                    <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                </li>
            </ul>
        </nav>
    </div>

    <!-- Form Row -->
    <div class="row">
        <div class="col-lg-12">

            <form action="{{route('admin.employee.add-new')}}" method="post" enctype="multipart/form-data"
                style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                @csrf

                <!-- General Information Card -->
                <div class="card">
                    <div class="card-header">
                        <h4>{{\App\CPU\translate('General_Information')}}</h4>
                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name">{{\App\CPU\translate('Full Name')}} <span class="text-danger">*</span></label>
                                   <input type="text" 
       name="name" 
       class="form-control" 
       id="name"
       placeholder="{{\App\CPU\translate('Ex')}} : John Doe"
       value="{{ old('name') }}"
       oninput="this.value = this.value.replace(/[^A-Za-z\s]/g, '')"
       pattern="[A-Za-z\s]+"
       title="Only letters and spaces allowed"
       required maxlength="25" minlength="3"> 
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="phone">{{\App\CPU\translate('Phone')}} <span class="text-danger">*</span></label>
                                    <input type="tel" 
       name="phone" 
       value="{{ old('phone') }}" 
       class="form-control"
       id="phone"
       placeholder="Enter 10 digit mobile number"
       maxlength="10"
       pattern="[0-9]{10}"
       oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,10)"
       title="Enter exactly 10 digits"
       required>  </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{\App\CPU\translate('Role')}} <span class="text-danger">*</span></label>
                                    <select class="form-control form-select" name="role_id" required>
    <option value="" disabled selected>
        ---{{\App\CPU\translate('select')}}---
    </option>

    @foreach($rls as $r)
        @if($r->id != 3)
            <option value="{{ $r->id }}"
                {{ old('role_id') == $r->id ? 'selected' : '' }}>
                {{ $r->name }}
            </option>
        @endif
    @endforeach
</select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{\App\CPU\translate('Admin_Image')}} <span class="text-danger">*</span></label>
                                    <span class="ml-1 text-info">( {{\App\CPU\translate('ratio')}} 1:1 )</span>

                                    <input type="file" name="image" id="customFileUpload"
                                        class="form-control"
                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*"
                                        required>

                                    <img class="img-thumbnail mt-2"
                                        id="viewer"
                                        src="{{asset('public/assets/back-end/img/400x400/img2.jpg')}}"
                                        style="width:150px;height:auto;object-fit:cover;"
                                        alt="Product thumbnail" />
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Login Information Card -->
                <div class="card mt-3">

                    <div class="card-header">
                        <h4>{{\App\CPU\translate('Login_Information')}}</h4>
                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{\App\CPU\translate('Email')}} <span class="text-danger">*</span></label>
                                    <input maxlength="40" type="email" name="email" value="{{old('email')}}" class="form-control"
                                        id="email"
                                        placeholder="{{\App\CPU\translate('Ex')}} : ex@gmail.com" required>
                                </div>
                            </div>

                            <div class="col-md-4">
    <div class="form-group">
        <label>{{\App\CPU\translate('password')}} <span class="text-danger">*</span></label>
        <input type="password" 
               name="password" 
               class="form-control" 
               id="password"
               placeholder="{{\App\CPU\translate('Password')}}"
               minlength="6"
               maxlength="20"
               required>
    </div>
</div>

<div class="col-md-4">
    <div class="form-group">
        <label>{{\App\CPU\translate('confirm_password')}} <span class="text-danger">*</span></label>
        <input type="password" 
               name="confirm_password" 
               class="form-control"
               id="confirm_password"
               placeholder="{{\App\CPU\translate('Confirm Password')}}"
               minlength="6"
               maxlength="20"
               required>
    </div>
</div>

                            <div class="col-md-12 d-flex justify-content-end gap-3">
                                <button type="reset" id="reset" class="btn btn-secondary px-4">
                                    {{\App\CPU\translate('reset')}}
                                </button>

                                <button type="submit" class="btn btn-primary px-4">
                                    {{\App\CPU\translate('submit')}}
                                </button>
                            </div>

                        </div>

                    </div>
                </div>

            </form>

        </div>
    </div>
</div>

@endsection