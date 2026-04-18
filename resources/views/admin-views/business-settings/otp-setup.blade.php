@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('OTP_setup'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">

        <!-- Page Title -->
        <div class="mb-4 pb-2">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{asset('/public/assets/back-end/img/business-setup.png')}}" alt="">
                {{\App\CPU\translate('Business_Setup')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Inlile Menu -->
        @include('admin-views.business-settings.business-setup-inline-menu')

    <!-- End Inlile Menu -->
        <form action="{{ route('admin.business-settings.otp-setup-update') }}" method="post"
              enctype="multipart/form-data" id="update-settings">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                                <label class="input-label" for="maximum_otp_hit">{{\App\CPU\translate('maximum_OTP_hit')}}
                                    <i class="tio-info-outined"
                                       data-toggle="tooltip"
                                       data-placement="top"
                                       title="{{ \App\CPU\translate('The_maximum_OTP_hit_is_a_measure_of_how_many_times_a_specific_one-time_password_has_been_generated_and_used_within_a_time') }}">
                                    </i>
                                </label>
                                <input type="number" min="0" value="{{$maximum_otp_hit}}"
                                       name="maximum_otp_hit" class="form-control" placeholder="" required>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                                <label class="input-label" for="otp_resend_time">{{\App\CPU\translate('OTP_resend_time')}}
                                    <i class="tio-info-outined"
                                       data-toggle="tooltip"
                                       data-placement="top"
                                       title="{{ \App\CPU\translate('If_the_user_fails_to_get_the_OTP_within_a_certain_time_user_can_request_a_resend') }}">
                                    </i>
                                    <span class="text-danger">( {{ \App\CPU\translate('in_seconds') }} )</span>
                                </label>
                                <input type="number" min="0" step="0.01" value="{{$otp_resend_time}}"
                                       name="otp_resend_time" class="form-control" placeholder="" required>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                                <label class="input-label" for="temporary_block_time">{{\App\CPU\translate('temporary_block_time')}}
                                    <i class="tio-info-outined"
                                       data-toggle="tooltip"
                                       data-placement="top"
                                       title="{{ \App\CPU\translate('Temporary_OTP_block_time_refers_to_a_security_measure_implemented_by_systems_to_restrict_access_to_OTP_service_for_a_specified_period_of_time_for_wrong_OTP_submission') }}">
                                    </i>
                                    <span class="text-danger">( {{ \App\CPU\translate('in_seconds') }} )</span>
                                </label>
                                <input type="number" min="0" value="{{$temporary_block_time}}" step="0.01"
                                       name="temporary_block_time" class="form-control" placeholder="" required>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                                <label class="input-label" for="maximum_otp_hit">{{\App\CPU\translate('maximum_Login_hit')}}
                                    <i class="tio-info-outined"
                                       data-toggle="tooltip"
                                       data-placement="top"
                                       title="{{ \App\CPU\translate('The_maximum_login_hit_is_a_measure_of_how_many_times_a_user_can_submit_password_within_a_time') }}">
                                    </i>
                                </label>
                                <input type="number" min="0" value="{{$maximum_login_hit}}"
                                       name="maximum_login_hit" class="form-control" placeholder="" required>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                                <label class="input-label" for="temporary_block_time">{{\App\CPU\translate('temporary_login_block_time')}}
                                    <i class="tio-info-outined"
                                       data-toggle="tooltip"
                                       data-placement="top"
                                       title="{{ \App\CPU\translate('Temporary_login_block_time_refers_to_a_security_measure_implemented_by_systems_to_restrict_access_for_a_specified_period_of_time_for_wrong_Password_submission') }}">
                                    </i>
                                    <span class="text-danger">( {{ \App\CPU\translate('in_seconds') }} )</span>
                                </label>
                                <input type="number" min="0" step="0.01" value="{{$temporary_login_block_time}}"
                                       name="temporary_login_block_time" class="form-control" placeholder="" required>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-3 justify-content-end">
                        <button type="reset" class="btn btn-secondary px-4">{{\App\CPU\translate('reset')}}</button>
                        <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}" onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}" class="btn btn--primary px-4">
                            {{\App\CPU\translate('save')}}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection
