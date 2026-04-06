@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Banner'))

@push('css_or_js')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="content-wrapper">

    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-image menu-icon"></i>
            </span>
            {{\App\CPU\translate('banner')}}
        </h3>

        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    {{\App\CPU\translate('banner')}}
                    <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-12">

            <div class="card">

                <div class="card-header">
                    <h4>{{\App\CPU\translate('update_banner')}}</h4>
                </div>

                <div class="card-body">

                    <form action="{{route('admin.banner.update',[$banner['id']])}}"
                        method="post"
                        enctype="multipart/form-data"
                        class="banner_form">
                        @csrf

                        <div class="row g-3">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">{{ \App\CPU\translate('Title')}}</label>
                                    <input
                                        type="text"
                                        name="title"
                                        class="form-control"
                                        id="title"
                                        placeholder="{{ \App\CPU\translate('Enter_banner_title') }}"
                                        value="{{$banner['title']}}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">

                                    <label>{{ \App\CPU\translate('Image')}} </label>
                                    <span class="ml-1 text-info">( {{\App\CPU\translate('ratio')}} 4:1 )</span>

                                    <input 
                                        type="file"
                                        name="image"
                                        id="mbimageFileUploader"
                                        class="form-control"
                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">

                                    <img
                                        id="mbImageviewer"
                                        src="{{$banner['image']}}"
                                        onerror='this.src="{{asset('assets/logo/logo-3.png')}}"'
                                        class="img-thumbnail mt-2"
                                        style="width:200px;height:auto;object-fit:cover;">
                                </div>
                            </div>

                            <div class="col-md-12 d-flex justify-content-end gap-3">
                                <button type="submit" class="btn btn-primary px-4">
                                    {{ \App\CPU\translate('update')}}
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


@push('script')
<script>
    $('#mbimageFileUploader').change(function() {
        readURL(this);
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#mbImageviewer').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush