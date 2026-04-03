@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Sale Edit'))

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
                    <h4>{{\App\CPU\translate('edit_sale')}}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.sale.update', $sale->id) }}" method="post" enctype="multipart/form-data" class="banner_form">
                        @csrf
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name">{{ \App\CPU\translate('Name')}}</label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $sale->name) }}" required>
                                    @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="email">{{ \App\CPU\translate('Email')}}</label>
                                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $sale->email) }}" required>
                                    @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="mobile">{{ \App\CPU\translate('Mobile')}}</label>
                                    <input type="tel" name="mobile" id="mobile" class="form-control @error('mobile') is-invalid @enderror" value="{{ old('mobile', $sale->mobile) }}" required>
                                    @error('mobile') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="saleImage">{{ \App\CPU\translate('Image')}}</label>
                                    <input type="file" name="image" id="saleImage" class="form-control @error('image') is-invalid @enderror" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                    @error('image') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    @if($sale->image)
                                        <img src="{{ $sale->image }}" class="img-thumbnail mt-2" style="width: 100px; height: 100px; object-fit: cover;">
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-12 d-flex justify-content-end gap-3">
                                <a href="{{ route('admin.campaign.list') }}" class="btn btn-secondary">{{ \App\CPU\translate('cancel') }}</a>
                                <button type="submit" class="btn btn-primary px-4">{{ \App\CPU\translate('update')}}</button>
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
        $('#mbimageFileUploader').change(function () {
            readURL(this);
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#mbImageviewer').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endpush