@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('Contact View'))
@push('css_or_js')
    <link href="{{asset('public/assets/back-end')}}/css/select2.min.css" rel="stylesheet"/>
    <link href="{{asset('public/assets/back-end/css/croppie.css')}}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-baseline gap-2">
                <!-- <img width="20" src="{{asset('/public/assets/back-end/img/message.png')}}" alt=""> -->
                <a class="textfont-set" href=""> 
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="24" fill="none">
                    <path d="M10.2988 18.2985L4.24883 12.2745L10.2988 6.24951" stroke="#000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" width="20px"/>
                </svg>Back</a>
                 {{\App\CPU\translate('Email_Template')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Content Row -->
        <div class="row">
            <div class="col-lg-12 card">
                <div class="card-header">
                    <h4>{{ \App\CPU\translate('Email_Template') }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.email-templates.store') }}" method="post" id="store-email-template">
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="">Title</label>
                                <input type="text" name="title" value="{{ old('title') }}" class="form-control">
                            </div>
                            <div class="col-lg-6">
                                <label for="">Subject</label>
                                <input type="text" name="subject" value="{{ old('subject') }}" class="form-control">
                            </div>
                            <div class="col-lg-12">
                                <label for="">Message</label>
                                <textarea name="message" class="form-control" cols="30" rows="10">{{ old('message') }}</textarea>
                            </div>
                            <div class="col-lg-12 mt-2">
                                <button type="submit" class="btn btn--primary px-4 px-md-5">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-footer"></div>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('message');

        $(document).on('submit','#store-email-template', function(e) {
            e.preventDefault()

            $.ajax({
                type: "POST",
                url: $(this).attr('action'),
                data: $(this).serialize(),
                headers:{
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "json",
                success: function (response) {
                    if(response.status) {
                        swal.fire(response.message, '', 'success').then(function() {
                            window.location.href = "{{ route('admin.email-templates.list') }}"
                        })
                    }
                },
                error: function(error) {
                    let errorText = ""
                    $.each(error.responseJSON.errors, function(ind, elm) {
                        errorText += `${elm[0]}\n`
                    })
                    swal.fire(errorText, '', 'error')
                }
            });
        })

    </script>
@endpush
