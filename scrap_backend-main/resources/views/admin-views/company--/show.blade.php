@extends('layouts.back-end.app')
{{--@section('title','Customer')--}}
@section('title', \App\CPU\translate('Company Details'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="d-print-none pb-2">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">

                    <!-- Page Title -->
                    <div class="mb-3">
                        <h2 class="h1 mb-0 text-capitalize d-flex gap-2">
                            <img width="20" src="{{asset('/public/assets/back-end/img/customer.png')}}" alt="">
                            {{\App\CPU\translate('seller_details')}}
                        </h2>
                    </div>
                    <!-- End Page Title -->

                    <div class="d-sm-flex align-items-sm-center">
                        <h3 class="page-header-title">{{ \App\CPU\translate('Name') }} #{{ $seller['f_name'] }} ({{ $seller['l_name'] }})</h3>
                        <span class="{{Session::get('direction') === "rtl" ? 'mr-2 mr-sm-3' : 'ml-2 ml-sm-3'}}">
                            <i class="tio-date-range"></i> {{ \App\CPU\translate('Joined At')}} : {{date('d M Y H:i:s',strtotime($seller['created_at'])) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Page Header -->

        <div class="row" id="printableArea">
            <div class="col-lg-12 mb-3 mb-lg-0">
                <div class="card">
                    <div class="p-3">
                    </div>
                    <!-- Table -->
                    <div class="table-responsive datatable-custom p-3">
                        <div class="media-body d-flex flex-column gap-1">
                            <div class="row">
                                <div class="col-lg-3">
                                    <span class="title-color"><label for=""><strong>First Name</strong>: {{ $seller->f_name }}</label></span>
                                </div>
                                <div class="col-lg-3">
                                    <span class="title-color"><label for=""><strong>Last Name</strong>: {{ $seller->l_name }}</label></span>
                                </div>
                                
                                <div class="col-lg-3">
                                    <span class="title-color"><label for=""><strong>Organization</strong>: {{ $seller->shop->name ?? '' }}</label></span>
                                </div>
                                <div class="col-lg-3">
                                    <span class="title-color"><label for=""><strong>Phone</strong>: {{ $seller->phone }}</label></span>
                                </div>
                                <div class="col-lg-3">
                                    <span class="title-color"><label for=""><strong>Email</strong>: {{ $seller->email }}</label></span>
                                </div>
                                <div class="col-lg-3">
                                    <span class="title-color"><label for=""><strong>Address</strong>: {{ $seller->shop->address ?? '' }}</label></span>
                                </div>
                                <div class="col-lg-3">
                                    <span class="title-color"><label for=""><strong>Status</strong>: {{ $seller->status }}</label></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Row -->
    </div>
@endsection

@push('script_2')

@endpush
