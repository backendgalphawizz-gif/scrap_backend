@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('Contact View'))
@push('css_or_js')
    <link href="{{asset('public/assets/back-end')}}/css/select2.min.css" rel="stylesheet"/>
    <link href="{{asset('public/assets/back-end/css/croppie.css')}}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Heading -->
        <div class="container">
            <!-- Page Title -->
            <div class="mb-3">
                <h2 class="h1 mb-0 text-capitalize d-flex align-items-baseline gap-2">
                    <!-- <img width="20" src="{{asset('/public/assets/back-end/img/message.png')}}" alt=""> -->
                    <a class="textfont-set" href=""> 
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="24" fill="none">
                            <path d="M10.2988 18.2985L4.24883 12.2745L10.2988 6.24951" stroke="#000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" width="20px"/>
                        </svg>Back</a>
                    {{\App\CPU\translate('Message_View')}}
                </h2>
            </div>
            <!-- End Page Title -->

            <!-- Content Row -->
            <div class="row">
                <div class="col-lg-6">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="mb-0 text-capitalize d-flex">
                                <i class="tio-user-big"></i>
                                {{ $contact->title }}
                            </h5>
                        </div>
                        <div class="card-body">

                            <div class="pl-2 mb-3">
                                <p class="">{{ $contact->subject }}</p>
                                <p>@php echo $contact->message; @endphp</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body mt-3 mx-lg-4">
                            <div class="row " style="text-align: {{ Session::get('direction') === "rtl" ? 'right' : 'left' }};">
                                <div class="col-12">
                                    <center>
                                        <h3>{{\App\CPU\translate('Send_Mail')}}</h3>
                                        <label class="badge-soft-danger px-1">{{\App\CPU\translate('Configure_your_mail_setup_first')}}.</label>
                                    </center>
                                    <form action="{{route('admin.contact.send-mail',$contact->id)}}" method="post">
                                        @csrf
                                        <div class="form-group mt-2">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label class="title-color">{{\App\CPU\translate('Subject')}}</label>
                                                    <input class="form-control" name="subject" required>
                                                </div>
                                                <div class="col-md-12 mt-3">
                                                    <label class="title-color">{{\App\CPU\translate('Mail_Body')}}</label>
                                                    <textarea class="form-control h-100" name="mail_body"
                                                              placeholder="{{\App\CPU\translate('Please_send_a_Feedback')}}" required></textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-end pt-3 mt-5">
                                            <button type="submit" class="btn btn--primary px-4">
                                            {{\App\CPU\translate('send')}}<i class="tio-send ml-2"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')

@endpush
