@extends('layouts.back-end.app')

@section('title', $seller->shop? $seller->shop->name : \App\CPU\translate("Shop Name"))

@push('css_or_js')
@endpush

@section('content')
<div class="content-wrapper">

    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-store menu-icon"></i>
            </span> User Brand
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <span></span>User Brand
                    <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">

        {{-- Seller Account --}}
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header text-capitalize">
                    <h5 class="mb-0">{{\App\CPU\translate('Seller')}} {{\App\CPU\translate('Account')}}</h5>
                </div>

                <div class="card-body" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{\App\CPU\translate('name')}}</label>
                                <input type="text" class="form-control"
                                       value="{{$seller->f_name}} {{$seller->l_name}}" disabled>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{\App\CPU\translate('Email')}}</label>
                                <input type="text" class="form-control"
                                       value="{{$seller->email}}" disabled>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{\App\CPU\translate('Phone')}}</label>
                                <input type="text" class="form-control"
                                       value="{{$seller->phone}}" disabled>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>


        {{-- Seller Information --}}
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-capitalize">
                    <h5 class="mb-0">{{\App\CPU\translate('Seller')}} {{\App\CPU\translate('Information')}}</h5>
                </div>

                <div class="card-body" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{\App\CPU\translate('Brand Name')}}</label>
                                <input type="text" class="form-control" value="{{$seller->username}}" disabled>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{\App\CPU\translate('Name')}}</label>
                                <input type="text" class="form-control" value="{{$seller->f_name}}" disabled>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{\App\CPU\translate('Phone')}}</label>
                                <input type="text" class="form-control" value="{{$seller->phone}}" disabled>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{\App\CPU\translate('Email')}}</label>
                                <input type="text" class="form-control" value="{{$seller->email}}" disabled>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{\App\CPU\translate('City')}}</label>
                                <input type="text" class="form-control" value="{{$seller->city}}" disabled>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{\App\CPU\translate('State')}}</label>
                                <input type="text" class="form-control" value="{{$seller->state}}" disabled>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{\App\CPU\translate('Business Type')}}</label>
                                <input type="text" class="form-control"
                                       value="{{$seller->business_registeration_type}}" disabled>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{\App\CPU\translate('Referral Code')}}</label>
                                <input type="text" class="form-control"
                                       value="{{$seller->referral_code}}" disabled>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{\App\CPU\translate('Instagram')}}</label>
                                <input type="text" class="form-control"
                                       value="{{$seller->instagram_username ?? 'N/A'}}" disabled>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{\App\CPU\translate('Facebook')}}</label>
                                <input type="text" class="form-control"
                                       value="{{$seller->facebook_username ?? 'N/A'}}" disabled>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{\App\CPU\translate('gst_number')}}</label>
                                <input type="text" class="form-control"
                                       value="{{$seller->gst_number ?? 'N/A'}}" disabled>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{\App\CPU\translate('pan_number')}}</label>
                                <input type="text" class="form-control"
                                       value="{{$seller->pan_number ?? 'N/A'}}" disabled>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{\App\CPU\translate('primary_contact')}}</label>
                                <input type="text" class="form-control"
                                       value="{{$seller->primary_contact ?? 'N/A'}}" disabled>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{\App\CPU\translate('alternate_contact')}}</label>
                                <input type="text" class="form-control"
                                       value="{{$seller->alternate_contact ?? 'N/A'}}" disabled>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{\App\CPU\translate('full_address')}}</label>
                                <input type="text" class="form-control"
                                       value="{{$seller->full_address ?? 'N/A'}}" disabled>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{\App\CPU\translate('google_map_link')}}</label>
                                <input type="text" class="form-control"
                                       value="{{$seller->google_map_link ?? 'N/A'}}" disabled>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{\App\CPU\translate('website_link')}}</label>
                                <input type="text" class="form-control"
                                       value="{{$seller->website_link ?? 'N/A'}}" disabled>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{\App\CPU\translate('Status')}}</label>
                                <input type="text" class="form-control"
                                       value="{{$seller->status=='approved' ? 'Active' : 'In-Active'}}" disabled>
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