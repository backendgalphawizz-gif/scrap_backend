@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Popup_Banner'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">

        @php
            $bannerImage = $popupBanner['image'] ?? null;
            $bannerImageSrc = null;
            if (!empty($bannerImage)) {
                if (str_contains($bannerImage, '/')) {
                    $bannerImageSrc = asset(ltrim($bannerImage, '/'));
                } else {
                    if (file_exists(public_path('storage/popup_banner/' . $bannerImage))) {
                        $bannerImageSrc = asset('storage/popup_banner/' . $bannerImage);
                    } elseif (file_exists(public_path('storage/app/public/popup_banner/' . $bannerImage))) {
                        $bannerImageSrc = asset('storage/app/public/popup_banner/' . $bannerImage);
                    } elseif (file_exists(public_path('storage/popup-banner/' . $bannerImage))) {
                        $bannerImageSrc = asset('storage/popup-banner/' . $bannerImage);
                    }
                }
            }
        @endphp

        <!-- Page Title -->
        <div class="mb-4 pb-2">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{asset('/public/assets/back-end/img/business-setup.png')}}" alt="">
                {{\App\CPU\translate('Business_Setup')}}
            </h2>
        </div>
        <!-- End Page Title -->

        @if($bannerImageSrc)
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="mb-3">{{\App\CPU\translate('Popup_Banner')}}</h5>
                    <img src="{{ $bannerImageSrc }}" alt="Popup Banner" class="w-100" style="max-height: 260px; object-fit: cover; border-radius: 8px;">
                </div>
            </div>
        @endif

        <form action="{{ route('admin.business-settings.popup-banner-update') }}" method="post"
              enctype="multipart/form-data" id="update-settings">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <!-- Enable/Disable Status -->
                        <div class="col-md-12 mb-4">
                            <div class="form-group">
                                <label class="d-flex align-items-center gap-2">
                                    <input type="checkbox" name="status" value="1" {{isset($popupBanner) && $popupBanner['status']==1?'checked':''}} class="form-check-input">
                                    <span class="title-color">{{\App\CPU\translate('Enable_Popup_Banner')}}</span>
                                </label>
                            </div>
                        </div>

                        <!-- Banner Title -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="input-label" for="title">{{\App\CPU\translate('Title')}}
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="title" name="title" class="form-control" 
                                       value="{{isset($popupBanner) && isset($popupBanner['title'])?$popupBanner['title']:''}}"
                                       placeholder="{{\App\CPU\translate('Enter_banner_title')}}">
                            </div>
                        </div>

                        <!-- Banner Description -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="input-label" for="description">{{\App\CPU\translate('Description')}}
                                    <span class="text-danger">*</span>
                                </label>
                                <textarea id="description" name="description" class="form-control" rows="5"
                                          placeholder="{{\App\CPU\translate('Enter_banner_description')}}">{{isset($popupBanner) && isset($popupBanner['description'])?$popupBanner['description']:''}}</textarea>
                            </div>
                        </div>

                        <!-- Banner Image -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="input-label" for="image">{{\App\CPU\translate('Image')}}
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="file" id="image" name="image" class="form-control" accept="image/*">
                                <small class="form-text text-muted">{{\App\CPU\translate('Recommended_size_600x400px')}}</small>
                                
                                @if($bannerImageSrc)
                                    <div class="mt-3">
                                        <p class="text-muted">{{\App\CPU\translate('Current_Image')}}</p>
                                        <img src="{{ $bannerImageSrc }}" alt="Banner Image" style="max-width: 300px; max-height: 200px; border: 1px solid #ddd; padding: 5px;">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-3 justify-content-end mt-4">
                        <a href="{{ route('admin.settings') }}" class="btn btn-secondary px-4">{{\App\CPU\translate('back')}}</a>
                        <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}" onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}" class="btn btn--primary px-4">
                            {{\App\CPU\translate('update')}}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection

@push('script')
<script>
    $(document).ready(function() {
        // Handle form type change to show/hide relevant fields
        $('#type').change(function() {
            const type = $(this).val();
            // You can add dynamic show/hide logic based on banner type if needed
        });
    });
</script>
@endpush
