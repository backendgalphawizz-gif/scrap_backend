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
                        class="banner_form">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">{{\App\CPU\translate('name')}}</label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>

                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">{{\App\CPU\translate('email')}}</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>

                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">{{\App\CPU\translate('mobile')}}</label>
                                    <input type="tel" name="mobile" class="form-control" required>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{\App\CPU\translate('image')}}</label>
                                    <input type="file" name="image" class="form-control" id="mbimageFileUploader" required>
                                    <img id="mbImageviewer" src="{{ asset('assets/logo/logo-icon.png') }}" alt="Image Preview" class="img-thumbnail mt-2" style="max-width: 100px;">
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
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