@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Profile Settings'))

@push('css_or_js')
<link href="{{asset('public/assets/back-end/css/croppie.css')}}" rel="stylesheet">
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="content-wrapper">

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

                    <form action="{{route('admin.profile.update',[$data->id])}}" method="post" enctype="multipart/form-data">
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
                                        value="{{$data->email}}">
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
</script>
@endpush