@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('view_sale_profile'))

@push('css_or_js')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="content-wrapper">

    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-home"></i>
            </span>
            {{\App\CPU\translate('view_sale_profile')}}
        </h3>

        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    {{\App\CPU\translate('view_sale_profile')}}
                    <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-12">

            <div class="card">

                <div class="card-header">
                    <h4>{{ \App\CPU\translate('Profile Detail') }}</h4>
                </div>

                <div class="card-body">
                    <div class="row g-3">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ \App\CPU\translate('name')}}</label>
                                <input type="text" class="form-control" value="{{ $sale->name }}" disabled>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ \App\CPU\translate('email')}}</label>
                                <input type="text" class="form-control" value="{{ $sale->email }}" disabled>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ \App\CPU\translate('Image')}}</label> <br>
                                <img class="img-fluid img-thumbnail" style="max-width:150px;"
                                    src="{{ $sale->image }}"
                                    onerror='this.src="{{asset('assets/logo/logo-3.png')}}"'>
                            </div>
                        </div>

                    </div>
                </div>
            </div>


            <div class="card mt-3">

                <div class="card-header">
                    <h4>{{ \App\CPU\translate('Bank Detail') }}</h4>
                </div>

                <div class="card-body">
                    <div class="row g-3">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ \App\CPU\translate('bank_name')}}</label>
                                <input type="text" class="form-control"
                                    value="{{ $sale->bank_detail->bank_name ?? '' }}" disabled>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ \App\CPU\translate('account_number')}}</label>
                                <input type="text" class="form-control"
                                    value="{{ $sale->bank_detail->account_number ?? '' }}" disabled>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ \App\CPU\translate('ifsc_code')}}</label>
                                <input type="text" class="form-control"
                                    value="{{ $sale->bank_detail->ifsc_code ?? '' }}" disabled>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ \App\CPU\translate('balance')}}</label>
                                <input type="text" class="form-control" value="{{ $sale->balance }}" disabled>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ \App\CPU\translate('branch_name')}}</label>
                                <input type="text" class="form-control"
                                    value="{{ $sale->bank_detail->branch_name ?? '' }}" disabled>
                            </div>
                        </div>


                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ \App\CPU\translate('bank_status')}}</label>
                                <input type="text" class="form-control" value="{{ $sale->bank_status }}" disabled>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ \App\CPU\translate('kyc_status')}}</label>
                                <input type="text" class="form-control" value="{{ $sale->kyc_status }}" disabled>
                            </div>
                        </div>


                    </div>
                </div>
            </div>


            <div class="card mt-3">

                <div class="card-header">
                    <h4>{{ \App\CPU\translate('PAN Detail') }}</h4>
                </div>

                <div class="card-body">
                    <div class="row g-3">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ \App\CPU\translate('pan_number')}}</label>
                                <input type="text" class="form-control"
                                    value="{{ $sale->pan_number }}" disabled>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ \App\CPU\translate('pan_status')}}</label>
                                <input type="text" class="form-control"
                                    value="{{ $sale->pan_status }}" disabled>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ \App\CPU\translate('Pan Image')}}</label> <br>
                                <img class="img-fluid img-thumbnail"
                                    style="max-width:200px;"
                                    src="{{ $sale->pan_image }}"
                                    onerror='this.src="{{asset('assets/logo/logo-3.png')}}"'>
                            </div>
                        </div>

                        <div class="col-md-12 d-flex justify-content-end gap-3">
                            <a href="{{ route('admin.campaign.list') }}" class="btn btn-primary px-4">
                                {{ \App\CPU\translate('back') }}
                            </a>
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