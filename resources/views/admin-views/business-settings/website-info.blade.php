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
                            <div class="col-md-4">
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
                            <div class="col-md-4">
                                @php($company_web_logo=\App\CPU\Helpers::get_business_settings('company_web_logo'))
                                <div class="form-group">
                                    <label class="title-color d-flex">{{\App\CPU\translate('company_web_logo')}}</label>
                                    <input type="file" name="company_web_logo" class="form-control" accept="image/*" id="customFileUploadLogo">
                                    @if($company_web_logo)
                                    <img id="viewerLogo" src="{{ asset('storage/company/'.$company_web_logo) }}" style="max-width: 100px; margin-top: 10px;">
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                @php($company_favicon=\App\CPU\Helpers::get_business_settings('company_favicon'))
                                <div class="form-group">
                                    <label class="title-color d-flex">{{\App\CPU\translate('Favicon')}}</label>
                                    <input type="file" name="company_favicon" class="form-control" accept="image/*" id="customFileUploadFavicon">
                                    <small class="text-muted">Recommended: 32×32 or 16×16 px PNG/ICO</small>
                                    @if($company_favicon)
                                    <img id="viewerFavicon" src="{{ asset('storage/company/'.$company_favicon) }}" style="max-width: 48px; margin-top: 10px;">
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
                            @php($brand_max_campaigns_per_timeframe=optional(\App\Models\BusinessSetting::where('type','brand_max_campaigns_per_timeframe')->first())->value ?? '0')
                            @php($brand_campaign_creation_timeframe_hours=optional(\App\Models\BusinessSetting::where('type','brand_campaign_creation_timeframe_hours')->first())->value ?? '24')
                            @php($post_sharing_reward=\App\Models\BusinessSetting::where('type','post_sharing_reward')->first()->value ?? '')
                            @php($feedback_incentive=\App\Models\BusinessSetting::where('type','feedback_incentive')->first()->value ?? '')
                            @php($platform_commission=\App\Models\BusinessSetting::where('type','platform_commission')->first()->value ?? '')
                            @php($tds_percent=\App\Models\BusinessSetting::where('type','tds_percent')->first()->value ?? '')
                            @php($sale_post_commission=\App\Models\BusinessSetting::where('type','sale_post_commission')->first()->value ?? '')
                            @php($sale_brand_commission=\App\Models\BusinessSetting::where('type','sale_brand_commission')->first()->value ?? '')
                            @php($minimum_wallet_balance=\App\Models\BusinessSetting::where('type','minimum_wallet_balance')->first()->value ?? '')
                            @php($campaign_gst_percentage=\App\Models\BusinessSetting::where('type','campaign_gst_percentage')->first()->value ?? '18')
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label class="title-color d-flex">KYC Amount (₹)</label>
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
                                    <label class="title-color d-flex">Cost per post (₹)</label>
                                    <input class="form-control" type="text" name="cost_per_post" value="{{ $cost_per_post }}">
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
                                    <label class="title-color d-flex">TSD (%)</label>
                                    <input class="form-control" type="text" name="tds_percent" value="{{ $tds_percent }}">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label class="title-color d-flex">Platform commission</label>
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
                                    <label class="title-color d-flex">Brand Minimum wallet balance(₹)</label>
                                    <input class="form-control" type="text" name="minimum_wallet_balance" value="{{ $minimum_wallet_balance }}">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label class="title-color d-flex">Campaign GST (%)</label>
                                    <input class="form-control" type="text" name="campaign_gst_percentage" value="{{ $campaign_gst_percentage }}" placeholder="18">
                                </div>
                            </div>
                        </div>

                        {{-- Social Media Links --}}
                        <hr class="mt-2 mb-4">
                        <h6 class="fw-bold mb-3"><i class="mdi mdi-share-variant me-1 text-primary"></i>Social Media Links <small class="text-muted fw-normal">(leave blank to hide from landing page)</small></h6>
                        @php($sm_facebook  = \App\CPU\Helpers::get_business_settings('social_facebook')  ?? '')
                        @php($sm_twitter   = \App\CPU\Helpers::get_business_settings('social_twitter')   ?? '')
                        @php($sm_instagram = \App\CPU\Helpers::get_business_settings('social_instagram') ?? '')
                        @php($sm_youtube   = \App\CPU\Helpers::get_business_settings('social_youtube')   ?? '')
                        @php($sm_linkedin  = \App\CPU\Helpers::get_business_settings('social_linkedin')  ?? '')
                        @php($footer_short_desc  = \App\CPU\Helpers::get_business_settings('footer_short_desc')  ?? '')
                        @php($footer_copyright   = \App\CPU\Helpers::get_business_settings('footer_copyright')   ?? '')
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="title-color d-flex">Short Description <small class="text-muted ms-1">(shown in footer)</small></label>
                                    <textarea name="footer_short_desc" class="form-control" rows="2" placeholder="A short description about your company…">{{ $footer_short_desc }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="title-color d-flex">Copyright Text</label>
                                    <input class="form-control" type="text" name="footer_copyright" value="{{ $footer_copyright }}" placeholder="© 2025 Company Name. All rights reserved.">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="title-color d-flex"><i class="mdi mdi-facebook me-1 text-primary"></i>Facebook URL</label>
                                    <input class="form-control" type="url" name="social_facebook" value="{{ $sm_facebook }}" placeholder="https://facebook.com/yourpage">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="title-color d-flex"><i class="mdi mdi-twitter me-1" style="color:#1da1f2"></i>X (Twitter) URL</label>
                                    <input class="form-control" type="url" name="social_twitter" value="{{ $sm_twitter }}" placeholder="https://x.com/yourhandle">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="title-color d-flex"><i class="mdi mdi-instagram me-1" style="color:#e1306c"></i>Instagram URL</label>
                                    <input class="form-control" type="url" name="social_instagram" value="{{ $sm_instagram }}" placeholder="https://instagram.com/yourhandle">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="title-color d-flex"><i class="mdi mdi-youtube me-1" style="color:#ff0000"></i>YouTube URL</label>
                                    <input class="form-control" type="url" name="social_youtube" value="{{ $sm_youtube }}" placeholder="https://youtube.com/@yourchannel">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="title-color d-flex"><i class="mdi mdi-linkedin me-1" style="color:#0077b5"></i>LinkedIn URL</label>
                                    <input class="form-control" type="url" name="social_linkedin" value="{{ $sm_linkedin }}" placeholder="https://linkedin.com/company/yourcompany">
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