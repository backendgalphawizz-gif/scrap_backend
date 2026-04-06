@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('Edit Role'))
@push('css_or_js')

@endpush

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-home"></i>
                </span> Admin Role Setup
            </h3>
            <nav aria-label="breadcrumb">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">
                        <span></span>Admin Role Setup <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form id="submit-create-role" action="{{route('admin.custom-role.update',[$role['id']])}}" method="post"
                            style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group mb-4">
                                        <label for="name" class="title-color">{{\App\CPU\translate('role_name')}} <span class="text-danger">*</span></label>
                                        <input required type="text" name="name" value="{{$role['name']}}" class="form-control" id="name"
                                            aria-describedby="emailHelp"
                                            placeholder="{{\App\CPU\translate('Ex')}} : {{\App\CPU\translate('Store')}}">
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex gap-4 flex-wrap">
                                <label for="module" class="title-color mb-0">{{\App\CPU\translate('module_permission')}}
                                    : </label>
                                <div class="form-group d-flex gap-2">
                                    <input type="checkbox" id="select_all">
                                    <label class="title-color mb-0"
                                        for="select_all">{{\App\CPU\translate('Select_All')}}</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6 col-lg-3">
                                    <div class="form-group d-flex gap-2">
                                        <input type="checkbox" name="modules[]" value="dashboard"  {{in_array('dashboard',(array)json_decode($role['module_access']))?'checked':''}} class="module-permission" id="dashboard">
                                        <label class="title-color mb-0" style="{{Session::get('direction') === "rtl" ? 'margin-right: 1.25rem;' : ''}};"
                                                for="dashboard">{{\App\CPU\translate('Dashboard')}}</label>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-3">
                                    <div class="form-group d-flex gap-2">
                                        <input type="checkbox" name="modules[]" value="user_management"  {{in_array('user_management',(array)json_decode($role['module_access']))?'checked':''}} class="module-permission" id="user_management">
                                        <label class="title-color mb-0" style="{{Session::get('direction') === "rtl" ? 'margin-right: 1.25rem;' : ''}};"
                                                for="user_management">{{\App\CPU\translate('user_management')}}</label>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-3">
                                    <div class="form-group d-flex gap-2">
                                        <input type="checkbox" name="modules[]" value="sale_management"  {{in_array('sale_management',(array)json_decode($role['module_access']))?'checked':''}} class="module-permission" id="sale_management">
                                        <label class="title-color mb-0" style="{{Session::get('direction') === "rtl" ? 'margin-right: 1.25rem;' : ''}};"
                                                for="sale_management">{{\App\CPU\translate('sale_management')}}</label>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-3">
                                    <div class="form-group d-flex gap-2">
                                        <input type="checkbox" name="modules[]" value="brand_management"  {{in_array('brand_management',(array)json_decode($role['module_access']))?'checked':''}} class="module-permission" id="brand_management">
                                        <label class="title-color mb-0" style="{{Session::get('direction') === "rtl" ? 'margin-right: 1.25rem;' : ''}};"
                                                for="brand_management">{{\App\CPU\translate('brand_management')}}</label>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-3">
                                    <div class="form-group d-flex gap-2">
                                        <input type="checkbox" name="modules[]" value="admin_management"  {{in_array('admin_management',(array)json_decode($role['module_access']))?'checked':''}} class="module-permission" id="admin_management">
                                        <label class="title-color mb-0" style="{{Session::get('direction') === "rtl" ? 'margin-right: 1.25rem;' : ''}};"
                                                for="admin_management">{{\App\CPU\translate('admin_management')}}</label>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-3">
                                    <div class="form-group d-flex gap-2">
                                        <input type="checkbox" name="modules[]" value="banner_management"  {{in_array('banner_management',(array)json_decode($role['module_access']))?'checked':''}} class="module-permission" id="banner_management">
                                        <label class="title-color mb-0" style="{{Session::get('direction') === "rtl" ? 'margin-right: 1.25rem;' : ''}};"
                                                for="banner_management">{{\App\CPU\translate('banner_management')}}</label>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-3">
                                    <div class="form-group d-flex gap-2">
                                        <input type="checkbox" name="modules[]" value="report_management"  {{in_array('report_management',(array)json_decode($role['module_access']))?'checked':''}} class="module-permission" id="report_management">
                                        <label class="title-color mb-0" style="{{Session::get('direction') === "rtl" ? 'margin-right: 1.25rem;' : ''}};"
                                                for="report_management">{{\App\CPU\translate('report_management')}}</label>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-3">
                                    <div class="form-group d-flex gap-2">
                                        <input type="checkbox" name="modules[]" value="business_settings"  {{in_array('business_settings',(array)json_decode($role['module_access']))?'checked':''}} class="module-permission" id="business_settings">
                                        <label class="title-color mb-0" style="{{Session::get('direction') === "rtl" ? 'margin-right: 1.25rem;' : ''}};"
                                                for="business_settings">{{\App\CPU\translate('business_settings')}}</label>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-3">
                                <button type="reset" class="btn btn-secondary">{{\App\CPU\translate('reset')}}</button>
                                <button type="submit" class="btn btn-primary">{{\App\CPU\translate('update')}}</button>
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

        $('#submit-create-role').on('submit', function (e) {

            var fields = $("input[name='modules[]']").serializeArray();
            if (fields.length === 0) {
                toastr.warning('{{ \App\CPU\translate('select_minimum_one_selection_box') }}', {
                    CloseButton: true,
                    ProgressBar: true
                });
                return false;
            } else {
                $('#submit-create-role').submit();
            }
        });
    </script>

    <script>
        $("#select_all").on('change', function () {
            if ($("#select_all").is(":checked") === true) {
                console.log($("#select_all").is(":checked"));
                $(".module-permission").prop("checked", true);
            } else {
                $(".module-permission").removeAttr("checked");
            }
        });
    </script>
@endpush
