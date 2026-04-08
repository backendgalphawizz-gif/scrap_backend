@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('general_settings'))

@push('css_or_js')
<link href="{{ asset('public/assets/select2/css/select2.min.css')}}" rel="stylesheet">
<link href="{{ asset('public/assets/back-end/css/custom.css')}}" rel="stylesheet">
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-cog"></i>
            </span> Settings
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <span></span>Web Setting <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                </li>
            </ul>
        </nav>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <form action="{{ route('admin.business-settings.updateInfo') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <!-- Company Information -->
                <div class="card mb-3 ">
                    <div class="card-header">
                        <h5 class="mb-0 text-capitalize d-flex gap-1">
                            <i class="tio-user-big"></i>
                            {{\App\CPU\translate('Company_Information')}}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                @php($companyName=\App\Models\BusinessSetting::where('type','company_name')->first())
                                <div class="form-group">
                                    <label
                                        class="title-color d-flex">{{\App\CPU\translate('company')}} {{\App\CPU\translate('name')}}</label>
                                    <input class="form-control" type="text" name="company_name"
                                        value="{{ $companyName->value?$companyName->value:" " }}"
                                        placeholder="New Business">
                                </div>
                            </div>
                            <div class="col-md-4">
                                @php($company_phone=\App\Models\BusinessSetting::where('type','company_phone')->first())
                                <div class="form-group">
                                    <label class="title-color d-flex">{{\App\CPU\translate('Phone')}}</label>
                                    <input class="form-control" type="text" name="company_phone"
                                        value="{{ $company_phone->value?$company_phone->value:"" }}"
                                        placeholder="New Business">
                                </div>
                            </div>
                            <div class="col-md-4">
                                @php($company_email=\App\Models\BusinessSetting::where('type','company_email')->first())
                                <div class="form-group">
                                    <label
                                        class="title-color d-flex">{{\App\CPU\translate('Email')}}</label>
                                    <input class="form-control" type="text" name="company_email"
                                        value="{{ $company_email->value?$company_email->value:" " }}"
                                        placeholder="New Business">
                                </div>
                            </div>
                            <div class="col-md-4">
                                @php($shop_address=\App\CPU\Helpers::get_business_settings('shop_address'))
                                <div class="form-group">
                                    <label class="title-color d-flex">{{\App\CPU\translate('company_address')}}</label>
                                    <input type="text" value="{{isset($shop_address)!=null?$shop_address:''}}"
                                        name="shop_address" class="form-control"
                                        placeholder="{{\App\CPU\translate('Your_shop_address')}}"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                @php($minimum_coin_withdrawl=\App\CPU\Helpers::get_business_settings('minimum_coin_withdrawl'))
                                <div class="form-group">
                                    <label class="title-color d-flex">{{\App\CPU\translate('minimum_coin_withdrawl')}}</label>
                                    <input type="text" value="{{isset($minimum_coin_withdrawl)!=null?$minimum_coin_withdrawl:''}}"
                                        name="minimum_coin_withdrawl" class="form-control"
                                        placeholder="{{\App\CPU\translate('Your_minimum_coin_withdrawl')}}"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                @php($upi_value=\App\CPU\Helpers::get_business_settings('upi_value'))
                                <div class="form-group">
                                    <label class="title-color d-flex">{{\App\CPU\translate('upi_value')}}</label>
                                    <input type="text" value="{{isset($upi_value)!=null?$upi_value:''}}"
                                        name="upi_value" class="form-control"
                                        placeholder="{{\App\CPU\translate('Your_upi_value')}}"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                @php($voucher_value=\App\CPU\Helpers::get_business_settings('voucher_value'))
                                <div class="form-group">
                                    <label class="title-color d-flex">{{\App\CPU\translate('voucher_value')}}</label>
                                    <input type="text" value="{{isset($voucher_value)!=null?$voucher_value:''}}"
                                        name="voucher_value" class="form-control"
                                        placeholder="{{\App\CPU\translate('Your_voucher_value')}}"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                @php($post_footer_content=\App\CPU\Helpers::get_business_settings('post_footer_content'))
                                <div class="form-group">
                                    <label class="title-color d-flex">{{\App\CPU\translate('post_footer_content')}}</label>
                                    <input type="text" value="{{isset($post_footer_content)!=null?$post_footer_content:''}}"
                                        name="post_footer_content" class="form-control"
                                        placeholder="{{\App\CPU\translate('Your_post_footer_content')}}"
                                        required>
                                </div>
                            </div>
                            @php($tz=\App\Models\BusinessSetting::where('type','timezone')->first())
                            @php($tz=$tz?$tz->value:0)
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="title-color d-flex">{{\App\CPU\translate('time')}} {{\App\CPU\translate('zone')}}</label>
                                    <select name="timezone" class="form-select form-control js-select2-custom">
                                        @php($timezones=\App\CPU\Helpers::getTimeZoneList())
                                        @foreach($timezones as $key => $timezone)
                                        <option value="{{ $key }}" {{$tz?($tz==$key?'selected':''):''}}>
                                            {{$timezone}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                @php($company_web_logo=\App\CPU\Helpers::get_business_settings('company_web_logo'))
                                <div class="form-group">
                                    <label class="title-color d-flex">{{\App\CPU\translate('company_web_logo')}}</label>
                                    <input type="file" name="company_web_logo" class="form-control" accept="image/*" id="customFileUploadLogo">
                                    @if($company_web_logo)
                                    <img id="viewerLogo" src="{{ asset('storage/company/'.$company_web_logo) }}" style="max-width: 100px; margin-top: 10px;">
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <hr class="mb-4 mt-4">
                            </div>
                            @php($kyc_amount=\App\Models\BusinessSetting::where('type','kyc_amount')->first()->value ?? '')
                            @php($max_posts_per_user=\App\Models\BusinessSetting::where('type','max_posts_per_user')->first()->value ?? '')
                            @php($brand_wise_posting_limits=\App\Models\BusinessSetting::where('type','brand_wise_posting_limits')->first()->value ?? '')
                            @php($cost_per_post=\App\Models\BusinessSetting::where('type','cost_per_post')->first()->value ?? '')
                            @php($cool_down_period_between_campaigns=\App\Models\BusinessSetting::where('type','cool_down_period_between_campaigns')->first()->value ?? '')
                            @php($brand_max_campaigns_per_timeframe=optional(\App\Models\BusinessSetting::where('type','brand_max_campaigns_per_timeframe')->first())->value ?? '0')
                            @php($brand_campaign_creation_timeframe_hours=optional(\App\Models\BusinessSetting::where('type','brand_campaign_creation_timeframe_hours')->first())->value ?? '24')
                            @php($post_sharing_reward=\App\Models\BusinessSetting::where('type','post_sharing_reward')->first()->value ?? '')
                            @php($feedback_incentive=\App\Models\BusinessSetting::where('type','feedback_incentive')->first()->value ?? '')
                            @php($platform_commission=\App\Models\BusinessSetting::where('type','platform_commission')->first()->value ?? '')
                            @php($tds_percent=\App\Models\BusinessSetting::where('type','tds_percent')->first()->value ?? '')
                            @php($sale_post_commission=\App\Models\BusinessSetting::where('type','sale_post_commission')->first()->value ?? '')
                            @php($sale_brand_commission=\App\Models\BusinessSetting::where('type','sale_brand_commission')->first()->value ?? '')
                            @php($minimum_wallet_balance=\App\Models\BusinessSetting::where('type','minimum_wallet_balance')->first()->value ?? '')
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label class="title-color d-flex">KYC Amount</label>
                                    <input class="form-control" type="text" name="kyc_amount" value="{{ $kyc_amount }}">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label class="title-color d-flex">Max posts per user</label>
                                    <input class="form-control" type="text" name="max_posts_per_user" value="{{ $max_posts_per_user }}">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label class="title-color d-flex">Brand-wise posting limits</label>
                                    <input class="form-control" type="text" name="brand_wise_posting_limits" value="{{ $brand_wise_posting_limits }}">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label class="title-color d-flex">Cost per post</label>
                                    <input class="form-control" type="text" name="cost_per_post" value="{{ $cost_per_post }}">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label class="title-color d-flex">Cool-down period between campaigns</label>
                                    <input class="form-control" type="text" name="cool_down_period_between_campaigns" value="{{ $cool_down_period_between_campaigns }}">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label class="title-color d-flex">Max campaigns per brand (per rolling window)</label>
                                    <input class="form-control" type="text" name="brand_max_campaigns_per_timeframe" value="{{ $brand_max_campaigns_per_timeframe }}" placeholder="0 = unlimited">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label class="title-color d-flex">Campaign creation rolling window (hours)</label>
                                    <input class="form-control" type="text" name="brand_campaign_creation_timeframe_hours" value="{{ $brand_campaign_creation_timeframe_hours }}">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label class="title-color d-flex">Post sharing reward</label>
                                    <input class="form-control" type="text" name="post_sharing_reward" value="{{ $post_sharing_reward }}">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label class="title-color d-flex">Feedback incentive</label>
                                    <input class="form-control" type="text" name="feedback_incentive" value="{{ $feedback_incentive }}">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label class="title-color d-flex">TSD Percent</label>
                                    <input class="form-control" type="text" name="tds_percent" value="{{ $tds_percent }}">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label class="title-color d-flex">Cool-down period between campaigns</label>
                                    <input class="form-control" type="text" name="platform_commission" value="{{ $platform_commission }}">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label class="title-color d-flex">Sale Campaign commission</label>
                                    <input class="form-control" type="text" name="sale_post_commission" value="{{ $sale_post_commission }}">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label class="title-color d-flex">Sale Brand commission</label>
                                    <input class="form-control" type="text" name="sale_brand_commission" value="{{ $sale_brand_commission }}">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label class="title-color d-flex">Brand Minimum wallet balance</label>
                                    <input class="form-control" type="text" name="minimum_wallet_balance" value="{{ $minimum_wallet_balance }}">
                                </div>
                            </div>
                        </div>

                        <div class="">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>

                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script')

@endpush