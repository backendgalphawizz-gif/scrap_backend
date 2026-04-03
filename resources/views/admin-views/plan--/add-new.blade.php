@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('Brand Add'))

@push('css_or_js')
    <link href="{{asset('public/assets/back-end')}}/css/select2.min.css" rel="stylesheet"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="content container-fluid">
    <!-- Page Title -->
    <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
        <h2 class="h1 mb-0 d-flex align-items-baseline gap-2">
            <!-- <img width="20" src="{{asset('/public/assets/back-end/img/brand.png')}}" alt=""> -->
            <a class="textfont-set" href=""> 
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="24" fill="none">
                    <path d="M10.2988 18.2985L4.24883 12.2745L10.2988 6.24951" stroke="#000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" width="20px"/>
                </svg>Back</a>
            {{\App\CPU\translate('Subscription Plan')}}
        </h2>
    </div>
    <!-- End Page Title -->

    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-body" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                    <form action="{{route('admin.plan.add-new')}}" method="post" enctype="multipart/form-data">
                        @csrf

                        @php($default_lang = json_decode($language)[0])
                        <ul class="nav nav-tabs w-fit-content mb-4">
                            @foreach(json_decode($language) as $lang)
                                <li class="nav-item">
                                    <a class="nav-link lang_link {{ $lang == $default_lang? 'active':'' }}" href="#"
                                        id="{{$lang}}-link">{{ucfirst(\App\CPU\Helpers::get_language_name($lang)).'('.strtoupper($lang).')'}}</a>
                                </li>
                            @endforeach
                        </ul>
                        <div class="row">
                            <div class="col-md-6">
                                @foreach(json_decode($language) as $lang)
                                    <div class="form-group {{$lang != $default_lang ? 'd-none':''}} lang_form"
                                            id="{{$lang}}-form">
                                        <label for="name" class="title-color">{{ \App\CPU\translate('title')}}<span class="text-danger">*</span> ({{strtoupper($lang)}})</label>
                                        <input type="text" name="name[]" class="form-control" id="name" value="" placeholder="{{\App\CPU\translate('Ex')}} : {{\App\CPU\translate('LUX')}}" {{$lang == $default_lang? 'required':''}}>
                                    </div>
                                    <input type="hidden" name="lang[]" value="{{$lang}}">
                                @endforeach
                                <div class="form-group">
                                    <label for="name" class="title-color">{{ \App\CPU\translate('Logo')}}<span class="text-danger">*</span></label>
                                    <span class="ml-1 text-info">( {{\App\CPU\translate('ratio')}} 1:1 )</span>
                                    <div class="custom-file text-left" required>
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

                            <div class="col-md-6 mb-4">
                                <div class="text-center">
                                    <img class="upload-img-view" id="viewer"
                                        src="{{asset('public\assets\back-end\img\400x400\img2.jpg')}}" alt="banner image"/>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group" id="en-form">
                                            <label for="price" class="title-color">{{ \App\CPU\translate('price')}}<span class="text-danger">*</span></label>
                                            <input type="number" name="price" class="form-control">
                                        </div>
                                    </div>
                                    
                                    <div class="col lg-4">
                                        <div class="form-group" id="en-form">
                                            <label for="time" class="title-color">{{ \App\CPU\translate('time') }}<span class="text-danger">*</span></label>
                                            <input type="number" name="time" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="form-group" id="en-form">
                                            <label for="type" class="title-color">{{ \App\CPU\translate('type') }}<span class="text-danger">*</span></label>
                                            <select name="type" id="" class="form-control">
                                                <option value="">-- Select Type --</option>
                                                <option value="1">Day</option>
                                                <option value="2">Month</option>
                                                <option value="3">Year</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="form-group" id="en-form">
                                            <label for="type" class="title-color">{{ \App\CPU\translate('Subscription For') }}<span class="text-danger">*</span></label>
                                            <select name="user_type" id="" class="form-control">
                                                <option value="">-- Select Type --</option>
                                                <option value="1">Customer</option>
                                                <option value="2">Vendor</option>
                                                <option value="3">Delivery Boy</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="d-flex gap-3 justify-content-end">
                            <button type="reset" id="reset" class="btn btn-secondary px-4">{{ \App\CPU\translate('reset')}}</button>
                            <button type="submit" class="btn btn--primary px-4">{{ \App\CPU\translate('submit')}}</button>
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
        $(".lang_link").click(function (e) {
            e.preventDefault();
            $(".lang_link").removeClass('active');
            $(".lang_form").addClass('d-none');
            $(this).addClass('active');

            let form_id = this.id;
            let lang = form_id.split("-")[0];

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


        $(document).ready(function() {
            $('#dataTable').DataTable();
        });
        $(document).on('click', '.delete', function () {
            var id = $(this).attr("id");
            Swal.fire({
                title: '{{\App\CPU\translate('are_you_sure?')}}',
                text: "{{\App\CPU\translate('You_will_not_be_able_to_revert_this!')}}",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{\App\CPU\translate('Yes')}} {{\App\CPU\translate('delete_it')}}!'
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{route('admin.plan.delete')}}",
                        method: 'POST',
                        data: {id: id},
                        success: function () {
                            toastr.success('{{\App\CPU\translate('Plan_deleted_successfully')}}');
                            location.reload();
                        }
                    });
                }
            })
        });
    </script>
@endpush
