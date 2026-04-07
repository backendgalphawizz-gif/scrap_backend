@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Social Login'))

@push('css_or_js')
    <link href="{{ asset('public/assets/back-end/css/tags-input.min.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assets/select2/css/select2.min.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-4 pb-2">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{asset('/public/assets/back-end/img/3rd-party.png')}}" alt="">
                {{\App\CPU\translate('3rd_party')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Inlile Menu -->
        @include('admin-views.business-settings.third-party-inline-menu')
        <!-- End Inlile Menu -->
        <form action="{{ route('admin.social-login.update-app-home') }}" enctype="multipart/form-data" method="post">
            @csrf
            <div class="card">
                <div class="card-header">
                    <label for="">App Features</label>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4">
                            <label for="">Label 1</label>
                            <textarea name="label_1" class="form-control" rows="2">{{ $settings['frame_one']['label_1'] ?? "" }}</textarea>
                        </div>
    
                        <div class="col-lg-4">
                            <label for="">Label 2</label>
                            <textarea name="label_2" class="form-control" rows="2">{{ $settings['frame_one']['label_2'] ?? "" }}</textarea>
                        </div>
    
                        <div class="col-lg-4">
                            <label for="">Label 3</label>
                            <textarea name="label_3" class="form-control" rows="2">{{ $settings['frame_one']['label_3'] ?? "" }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-2">
                <div class="card-header">
                    <label for="">Summer Sale Banner</label>
                </div>                
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <label for="">Banner Image</label>
                            <input type="file" name="banner_image" class="form-control">
                        </div>
                        <div class="col-lg-6">
                            <label for="">Discount %</label>
                            <input type="number" min="0" max="100" name="discount_percent" class="form-control" value="{{ $settings['summer_sale_banner']['discount_percent'] ?? '' }}">
                        </div>
                        <div class="col-lg-6">
                            <img src="{{ asset('storage/app/public/product/' . $settings['summer_sale_banner']['image']) }}" alt="" srcset="">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-2">
                <div class="card-header">
                    <label for="">Prime Time Deals</label>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <label for="">Prime Time Deals Image</label>
                            <input type="file" name="prime_image" class="form-control">
                        </div>
                        <div class="col-lg-6">
                            <img src="{{ asset('storage/app/public/product/' . $settings['prime_time_banner']['image']) }}" alt="" srcset="">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mt-2">
                <div class="card-header">
                    <label for="">Top Deals Section</label>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <label for="">Title</label>
                            <input type="text" name="title" class="form-control" value="{{ $settings['top_deal_product']['title'] ?? '' }}">
                        </div>
                        <div class="col-lg-6">
                            <label for="">Product</label>
                            <select name="product_ids[]" class="js-example-responsive" multiple>
                                <option value="">Select</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ in_array($product->id, explode(',', $settings['top_deal_product']['product_ids'])) ? 'selected' : '' }}>{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-2">
                <div class="card-header">
                    <label for="">Gift Section</label>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <label for="">Title</label>
                            <input type="text" name="gift_title" class="form-control" value="{{ isset($settings['gift_section']) ? $settings['gift_section']['gift_title'] : '' }}">
                        </div>
                        <div class="col-lg-6">
                            <label for="">Product</label>
                            <select name="gift_product_ids[]" class="js-example-responsive" multiple>
                                <option value="">Select</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ isset($settings['gift_section']) && in_array($product->id, explode(',', $settings['gift_section']['gift_product_ids'])) ? 'selected' : '' }}>{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row gy-3">
                <div class="col-lg-12">
                    <button type="submit" class="btn btn--primary">Save</button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('script')


    <script>
        $(".js-example-responsive").select2({
            // dir: "rtl",
            width: 'resolve'
        });
    </script>

@endpush
