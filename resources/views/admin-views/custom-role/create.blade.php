@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('Admin Role'))
@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{asset('public/assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <style>
        .custom-role-action-btn {
            width: 36px;
            height: 36px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
        }

        .custom-role-action-btn i {
            font-size: 18px;
            line-height: 1;
        }

        .custom-role-status-switch .form-check-input {
            width: 2.75em;
            height: 1.4em;
            cursor: pointer;
            margin: 0;
        }

        .custom-role-status-switch .form-check-input:focus {
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.2);
        }
    </style>
@endpush

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-shield-account"></i>
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
            <!-- Content Row -->
            <div class="card">
                <div class="card-body">
                    <form id="submit-create-role" method="post" action="{{route('admin.custom-role.store')}}"
                        style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group mb-4">
                                    <label for="name" class="title-color">{{\App\CPU\translate('role_name')}} <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" id="name"
                                        aria-describedby="emailHelp"
                                        placeholder="{{\App\CPU\translate('Ex')}} : {{\App\CPU\translate('Store')}}" required maxlength="28">          </div>
                            </div>
                        </div>

                        <div class="d-flex gap-4 flex-wrap">
                            <label for="name" class="title-color font-weight-bold mb-0">{{\App\CPU\translate('module_permission')}} </label>
                            <div class="form-group d-flex gap-2">
                                <input type="checkbox" id="select_all">
                                <label class="title-color mb-0" for="select_all">{{\App\CPU\translate('Select All')}}</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6 col-lg-3">
                                <div class="form-group d-flex gap-2">
                                    <input type="checkbox" name="modules[]" value="dashboard" class="module-permission" id="dashboard">
                                    <label class="title-color mb-0" style="{{Session::get('direction') === "rtl" ? 'margin-right: 1.25rem;' : ''}};"
                                        for="dashboard">{{\App\CPU\translate('Dashboard')}}</label>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="form-group d-flex gap-2">
                                    <input type="checkbox" name="modules[]" value="user_management" class="module-permission" id="user_management">
                                    <label class="title-color mb-0" style="{{Session::get('direction') === "rtl" ? 'margin-right: 1.25rem;' : ''}};"
                                        for="user_management">{{\App\CPU\translate('user_management')}}</label>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="form-group d-flex gap-2">
                                    <input type="checkbox" name="modules[]" value="sale_management" class="module-permission" id="sale_management">
                                    <label class="title-color mb-0" style="{{Session::get('direction') === "rtl" ? 'margin-right: 1.25rem;' : ''}};"
                                        for="sale_management">{{\App\CPU\translate('sale_management')}}</label>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="form-group d-flex gap-2">
                                    <input type="checkbox" name="modules[]" value="brand_management" class="module-permission" id="brand_management">
                                    <label class="title-color mb-0" style="{{Session::get('direction') === "rtl" ? 'margin-right: 1.25rem;' : ''}};"
                                        for="brand_management">{{\App\CPU\translate('brand_management')}}</label>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="form-group d-flex gap-2">
                                    <input type="checkbox" name="modules[]" value="admin_management" class="module-permission" id="admin_management">
                                    <label class="title-color mb-0" style="{{Session::get('direction') === "rtl" ? 'margin-right: 1.25rem;' : ''}};"
                                        for="admin_management">{{\App\CPU\translate('admin_management')}}</label>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="form-group d-flex gap-2">
                                    <input type="checkbox" name="modules[]" value="banner_management" class="module-permission" id="banner_management">
                                    <label class="title-color mb-0" style="{{Session::get('direction') === "rtl" ? 'margin-right: 1.25rem;' : ''}};"
                                        for="banner_management">{{\App\CPU\translate('banner_management')}}</label>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="form-group d-flex gap-2">
                                    <input type="checkbox" name="modules[]" value="feedback_management" class="module-permission" id="feedback_management">
                                    <label class="title-color mb-0" style="{{Session::get('direction') === "rtl" ? 'margin-right: 1.25rem;' : ''}};"
                                        for="feedback_management">{{\App\CPU\translate('Feedback')}}</label>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="form-group d-flex gap-2">
                                    <input type="checkbox" name="modules[]" value="voucher_management" class="module-permission" id="voucher_management">
                                    <label class="title-color mb-0" style="{{Session::get('direction') === "rtl" ? 'margin-right: 1.25rem;' : ''}};"
                                        for="voucher_management">{{\App\CPU\translate('Voucher Manage')}}</label>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="form-group d-flex gap-2">
                                    <input type="checkbox" name="modules[]" value="notification_management" class="module-permission" id="notification_management">
                                    <label class="title-color mb-0" style="{{Session::get('direction') === "rtl" ? 'margin-right: 1.25rem;' : ''}};"
                                        for="notification_management">{{\App\CPU\translate('Notification')}}</label>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="form-group d-flex gap-2">
                                    <input type="checkbox" name="modules[]" value="activity_logs" class="module-permission" id="activity_logs">
                                    <label class="title-color mb-0" style="{{Session::get('direction') === "rtl" ? 'margin-right: 1.25rem;' : ''}};"
                                        for="activity_logs">{{\App\CPU\translate('Activity Logs')}}</label>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="form-group d-flex gap-2">
                                    <input type="checkbox" name="modules[]" value="support_management" class="module-permission" id="support_management">
                                    <label class="title-color mb-0" style="{{Session::get('direction') === "rtl" ? 'margin-right: 1.25rem;' : ''}};"
                                        for="support_management">{{\App\CPU\translate('Support Chat')}}</label>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="form-group d-flex gap-2">
                                    <input type="checkbox" name="modules[]" value="report_management" class="module-permission" id="report_management">
                                    <label class="title-color mb-0" style="{{Session::get('direction') === "rtl" ? 'margin-right: 1.25rem;' : ''}};"
                                        for="report_management">{{\App\CPU\translate('report_management')}}</label>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="form-group d-flex gap-2">
                                    <input type="checkbox" name="modules[]" value="business_settings" class="module-permission" id="business_settings">
                                    <label class="title-color mb-0" style="{{Session::get('direction') === "rtl" ? 'margin-right: 1.25rem;' : ''}};"
                                        for="business_settings">{{\App\CPU\translate('business_settings')}}</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">{{\App\CPU\translate('Submit')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card mt-3">
                <div class="px-3 py-4">
                    <div class="row justify-content-between align-items-center flex-grow-1">
                        <div class="col-md-4 col-lg-6 mb-2 mb-sm-0">
                            <h5 class="d-flex align-items-center gap-2">
                                {{\App\CPU\translate('Employee Roles')}}
                                <span class="badge badge-soft-dark radius-50 fz-12 ml-1">{{ count($rl) }}</span>
                            </h5>
                        </div>
                        <div class="col-md-8 col-lg-6 d-flex flex-wrap flex-sm-nowrap justify-content-sm-end gap-3">
                            <!-- Search -->
                            <form action="{{url()->current()}}?search={{$search}}" method="GET">
                                <div class="input-group input-group-merge input-group-custom">
                                    <!-- <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div> -->
                                    <input id="datatableSearch_" type="search" name="search" class="form-control" placeholder="{{\App\CPU\translate('search_role')}}"
                                        value="{{$search}}">
                                    <button type="submit" class="btn btn-primary">{{\App\CPU\translate('search')}}</button>
                                </div>
                            </form>
                            <!-- End Search -->
                            <div class="">
                                <button type="button" class="btn btn-outline-primary text-nowrap" data-toggle="dropdown">
                                    <i class="tio-download-to"></i>
                                    {{\App\CPU\translate('export')}}
                                    <i class="tio-chevron-down"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a class="dropdown-item" href="{{route('admin.custom-role.export')}}">{{\App\CPU\translate('excel')}}</a></li>
                                    <div class="dropdown-divider"></div>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pb-3">
                    <div class="table-responsive">
                        <table class="table" cellspacing="0">
                            <thead class="text-capitalize">
                                <tr>
                                    <th>{{\App\CPU\translate('SL')}}</th>
                                    <th>{{\App\CPU\translate('role_name')}}</th>
                                    <th>{{\App\CPU\translate('modules')}}</th>
                                    <th>{{\App\CPU\translate('created_at')}}</th>
                                    <th class="text-center">{{\App\CPU\translate('status')}}</th>
                                    <th class="text-center">{{\App\CPU\translate('action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rl as $k=>$r)
                                <tr id="data-{{ $r['id'] }}">
                                    <td>{{$k+1}}</td>
                                    <td>{{$r['name']}}</td>
                                    <td class="text-capitalize">
                                        @if($r['module_access']!=null)
                                        @foreach((array)json_decode($r['module_access']) as $m)
                                        @if($m == 'report')
                                        {{\App\CPU\translate('reports_and_analytics')}} <br>
                                        @elseif($m == 'user_section')
                                        {{\App\CPU\translate('user_management')}} <br>
                                        @elseif($m == 'support_section')
                                        {{\App\CPU\translate('Help_&_Support_Section')}} <br>
                                        @else
                                        {{\App\CPU\translate(str_replace('_',' ',$m))}} <br>
                                        @endif
                                        @endforeach
                                        @endif
                                    </td>
                                    <td>{{ \App\CPU\Helpers::formatAdminDate($r['created_at']) }}</td>
                                    <td class="text-center">
                                        <div class="form-check form-switch custom-role-status-switch d-inline-flex justify-content-center mb-0">
                                            <input class="form-check-input custom-role-status"
                                                type="checkbox"
                                                role="switch"
                                                aria-label="{{ \App\CPU\translate('Activate or deactivate') }}"
                                                data-id="{{ $r['id'] }}"
                                                {{ (int) $r['status'] === 1 ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2 justify-content-center">
                                            <a href="{{ route('admin.custom-role.update', [$r['id']]) }}"
                                                class="btn btn-outline-primary btn-sm custom-role-action-btn"
                                                title="{{ \App\CPU\translate('Edit') }}">
                                                <i class="mdi mdi-pencil-outline"></i>
                                            </a>
                                            <a href="#"
                                                class="btn btn-outline-danger btn-sm custom-role-action-btn cursor-pointer delete"
                                                title="{{ \App\CPU\translate('Delete') }}"
                                                id="{{ $r['id'] }}">
                                                <i class="mdi mdi-delete-outline"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="content container-fluid">





</div>
@endsection

@push('script')
<!-- Page level plugins -->
<script src="{{asset('public/assets/back-end')}}/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="{{asset('public/assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.js"></script>
<!-- Page level custom scripts -->
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable();
    });

    function notifySuccess(message) {
        if (typeof toastr !== 'undefined' && toastr.success) {
            toastr.success(message);
            return;
        }
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: message,
            showConfirmButton: false,
            timer: 2000
        });
    }

    function notifyError(message) {
        if (typeof toastr !== 'undefined' && toastr.error) {
            toastr.error(message);
            return;
        }
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: message,
            showConfirmButton: false,
            timer: 2500
        });
    }

    $(document).on('click', '.delete', function() {
        var id = $(this).attr('id');
        Swal.fire({
            title: '{{ \App\CPU\translate('Are you sure ?') }}',
            text: "{{ \App\CPU\translate('You won\'t be able to revert this!') }}",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '{{ \App\CPU\translate('Yes, delete it!') }}',
            cancelButtonText: '{{ \App\CPU\translate('cancel') }}'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('admin.custom-role.delete') }}",
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    data: { id: id },
                    success: function(response) {
                        if (response && response.status === false) {
                            notifyError(response.message || '{{ \App\CPU\translate('Failed to delete role') }}');
                            return;
                        }
                        $('#data-' + id).remove();
                        notifySuccess('{{ \App\CPU\translate('Role deleted successfully') }}');
                    },
                    error: function(xhr) {
                        var message = (xhr.responseJSON && xhr.responseJSON.message)
                            ? xhr.responseJSON.message
                            : '{{ \App\CPU\translate('Failed to delete role') }}';
                        notifyError(message);
                    }
                });
            }
        });
    });
</script>
<script>
    $('#submit-create-role').on('submit', function(e) {
        var fields = $("input[name='modules[]']").serializeArray();
        if (fields.length === 0) {
            toastr.warning('{{ \App\CPU\translate('select_minimum_one_selection_box') }}', {
                CloseButton: true,
                ProgressBar: true
            });
            return false;
        }
    });
</script>
<script>
    $(document).on('change', '.custom-role-status', function() {
        var id = $(this).data('id');
        var status = $(this).prop('checked') ? 1 : 0;
        var $toggle = $(this);

        $.ajax({
            url: "{{ route('admin.custom-role.employee-role-status') }}",
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            data: { id: id, status: status },
            success: function(response) {
                if (response.status) {
                    notifySuccess(response.message || '{{ \App\CPU\translate('Status updated successfully') }}');
                } else {
                    notifyError(response.message || '{{ \App\CPU\translate('Failed to update status') }}');
                    $toggle.prop('checked', !$toggle.prop('checked'));
                }
            },
            error: function(xhr) {
                var message = (xhr.responseJSON && xhr.responseJSON.message)
                    ? xhr.responseJSON.message
                    : '{{ \App\CPU\translate('Failed to update status') }}';
                notifyError(message);
                $toggle.prop('checked', !$toggle.prop('checked'));
            }
        });
    });
</script>

<script>
    $("#select_all").on('change', function() {
        if ($("#select_all").is(":checked") === true) {
            console.log($("#select_all").is(":checked"));
            $(".module-permission").prop("checked", true);
        } else {
            $(".module-permission").removeAttr("checked");
        }
    });
</script>
@endpush