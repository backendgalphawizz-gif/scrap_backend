@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Brand Edit'))

@push('css_or_js')
    <link href="{{asset('public/assets/back-end')}}/css/select2.min.css" rel="stylesheet"/>
    <link href="{{asset('public/assets/back-end/css/croppie.css')}}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="content container-fluid">
    <!-- Page Title -->
    <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
        <h2 class="h1 mb-0 align-items-baseline d-flex gap-2">
            <!-- <img width="20" src="{{asset('/public/assets/back-end/img/brand.png')}}" alt=""> -->
            <a class="textfont-set" href=""> 
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="24" fill="none">
                    <path d="M10.2988 18.2985L4.24883 12.2745L10.2988 6.24951" stroke="#000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" width="20px"/>
                </svg>Back</a>
            {{\App\CPU\translate('Subscription Plan')}} {{\App\CPU\translate('Update')}}
        </h2>
    </div>
    <!-- End Page Title -->

    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                    <form action="{{route('admin.plan.update',[$b['id']])}}" method="post" enctype="multipart/form-data">
                        @csrf
                        @php($language=\App\Model\BusinessSetting::where('type','pnc_language')->first())
                            @php($language = $language->value ?? null)
                            @php($default_lang = 'en')

                            @php($default_lang = json_decode($language)[0])
                            <ul class="nav nav-tabs w-fit-content mb-4">
                                @foreach(json_decode($language) as $lang)
                                    <li class="nav-item text-capitalize">
                                        <a class="nav-link lang_link {{$lang == $default_lang? 'active':''}}"
                                           href="#"
                                           id="{{$lang}}-link">{{ucfirst(\App\CPU\Helpers::get_language_name($lang)).'('.strtoupper($lang).')'}}</a>
                                    </li>
                                @endforeach
                            </ul>
                        <div class="row">
                            <div class="col-md-6">
                                @foreach(json_decode($language) as $lang)
                                        <?php
                                        if (count($b['translations'])) {
                                            $translate = [];
                                            foreach ($b['translations'] as $t) {
                                                if ($t->locale == $lang && $t->key == "title") {
                                                    $translate[$lang]['title'] = $t->value;
                                                }
                                            }
                                        }
                                        ?>
                                    <div class="form-group {{$lang != $default_lang ? 'd-none':''}} lang_form"
                                            id="{{$lang}}-form">
                                        <label class="title-color" for="name">{{ \App\CPU\translate('Plan Name')}}</label>
                                        <input type="text" name="name[]" value="{{$lang==$default_lang?$b['title']:($translate[$lang]['title']??'')}}"
                                                class="form-control" id="name" placeholder="{{ \App\CPU\translate('Ex')}} : {{ \App\CPU\translate('LUX')}}" {{$lang == $default_lang? 'required':''}}>
                                    </div>
                                    <input type="hidden" name="lang[]" value="{{$lang}}">
                                @endforeach
                                <div class="form-group">
                                    <label class="title-color" for="brand">{{ \App\CPU\translate('Brand_Logo')}}</label>
                                    <span class="ml-2 text-info">{{ \App\CPU\translate('ratio')}} 1:1</span>
                                    <div class="custom-file text-left">
                                        <input type="file" name="image" id="customFileUpload" class="custom-file-input"
                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        <label class="custom-file-label" for="customFileUpload">{{\App\CPU\translate('choose')}} {{\App\CPU\translate('file')}}</label>
                                    </div>
                                </div>
                                <div class="form-group" id="en-form">
                                    <label for="description" class="title-color">{{ \App\CPU\translate('description')}}<span class="text-danger">*</span></label>
                                    <textarea name="description" class="form-control" cols="10" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="text-center">
                                    <img class="upload-img-view" id="viewer"
                                        onerror="this.src='{{asset('public/assets/back-end/img/160x160/img2.jpg')}}'"
                                        src="{{asset('storage/app/public/brand')}}/{{$b['image']}}" alt="banner image"/>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group" id="en-form">
                                            <label for="price" class="title-color">{{ \App\CPU\translate('price')}}<span class="text-danger">*</span></label>
                                            <input type="number" min="0" step="0.01" name="price" class="form-control" value="{{ App\CPU\Convert::default($b['price']) }}">
                                        </div>
                                    </div>
                                    
                                    <div class="col lg-4">
                                        <div class="form-group" id="en-form">
                                            <label for="time" class="title-color">{{ \App\CPU\translate('time') }}<span class="text-danger">*</span></label>
                                            <input type="number" min="0" step="1" name="time" class="form-control" value="{{$b['time']}}">
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="form-group" id="en-form">
                                            <label for="type" class="title-color">{{ \App\CPU\translate('type') }}<span class="text-danger">*</span></label>
                                            <select name="type" id="" class="form-control">
                                                <option value="">-- Select Type --</option>
                                                <option value="1" {{$b['type'] == '1' ? 'selected' : ''}}>Day</option>
                                                <option value="2" {{$b['type'] == '2' ? 'selected' : ''}}>Month</option>
                                                <option value="3" {{$b['type'] == '3' ? 'selected' : ''}}>Year</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="form-group" id="en-form">
                                            <label for="type" class="title-color">{{ \App\CPU\translate('Subscription For') }}<span class="text-danger">*</span></label>
                                            <select name="user_type" id="" class="form-control">
                                                <option value="">-- Select Type --</option>
                                                <option value="1" {{ $b['user_type'] == '1' ? 'selected' : '' }}>Customer</option>
                                                <option value="2" {{ $b['user_type'] == '2' ? 'selected' : '' }}>Vendor</option>
                                                <option value="3" {{ $b['user_type'] == '3' ? 'selected' : '' }}>Delivery Boy</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-3">
                            <button type="reset" id="reset" class="btn btn-secondary px-4">{{ \App\CPU\translate('reset')}}</button>
                            <button type="submit" class="btn btn--primary px-4">{{ \App\CPU\translate('update')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!--modal-->
    @include('shared-partials.image-process._image-crop-modal',['modal_id'=>'brand-image-modal','width'=>1000,'margin_left'=>'-53%'])
    <!--modal-->
</div>
@endsection

@push('script')
<script>
        $(".lang_link").click(function (e) {
            e.preventDefault();
            $(".lang_link").removeClass('active');
            $(".lang_form").addClass('d-none');
            $(this).addClass('active');

            let form_id = this.id;
            let lang = form_id.split("-")[0];
            console.log(lang);
            $("#" + lang + "-form").removeClass('d-none');
            if (lang == '{{$default_lang}}') {
                $(".from_part_2").removeClass('d-none');
            } else {
                $(".from_part_2").addClass('d-none');
            }
        });

        $(document).ready(function () {
            $('#dataTable').DataTable();
        });
    </script>
    <script src="{{asset('public/assets/back-end')}}/js/select2.min.js"></script>
    <script>
        $(".js-example-theme-single").select2({
            theme: "classic"
        });

        $(".js-example-responsive").select2({
            width: 'resolve'
        });
    </script>

    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileUpload").change(function () {
            readURL(this);
        });
    </script>
@endpush
