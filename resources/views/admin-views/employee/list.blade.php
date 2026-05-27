@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Employee List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .employee-action-btn {
            width: 36px;
            height: 36px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
        }

        .employee-action-btn i {
            font-size: 18px;
            line-height: 1;
        }

        .employee-status-switch .form-check-input {
            width: 2.75em;
            height: 1.4em;
            cursor: pointer;
            margin: 0;
        }

        .employee-status-switch .form-check-input:focus {
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.2);
        }
    </style>
@endpush

@section('content')

<div class="content-wrapper">
    <!-- Page Header -->
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-account-group"></i>
            </span>
            {{\App\CPU\translate('Admin List')}}
        </h3>

        <!-- Search + Add -->
        <!-- <div class=" d-flex gap-2">
            <div class="">
                <form action="{{ url()->current() }}" method="GET">
                    <div class="input-group input-group-merge input-group-custom">
                        <input type="search" name="search" class="form-control"
                            placeholder="{{\App\CPU\translate('search_by_name_or_email_or_phone')}}"
                            value="{{$search}}" required>

                        <button type="submit" class="btn btn-primary">
                            {{\App\CPU\translate('search')}}
                        </button>
                    </div>
                </form>
            </div>
            <div class="">
                <a href="{{route('admin.employee.add-new')}}" class="btn btn-primary">
                    <i class="tio-add"></i>
                    <span>{{\App\CPU\translate('Add')}} {{\App\CPU\translate('New')}}</span>
                </a>
            </div>
        </div> -->
 <div class="">
                <a href="{{route('admin.employee.add-new')}}" class="btn btn-primary">
                    <i class="tio-add"></i>
                    <span>{{\App\CPU\translate('Add')}} {{\App\CPU\translate('New')}}</span>
                </a>
            </div>

        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    {{\App\CPU\translate('Employee List')}}
                    <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                </li>
            </ul>
        </nav>
    </div>

     <div class=" d-flex gap-2 mb-2">
            <div class="">
                <form action="{{ url()->current() }}" method="GET">
                    <div class="input-group input-group-merge input-group-custom">
                        <input type="search" name="search" class="form-control"
                            placeholder="{{\App\CPU\translate('search_by_name_or_email_or_phone')}}"
                            value="{{$search}}" required>

                        <button type="submit" class="btn btn-primary">
                            {{\App\CPU\translate('search')}}
                        </button>
                    </div>
                </form>
            </div>
            <!-- <div class="">
                <a href="{{route('admin.employee.add-new')}}" class="btn btn-primary">
                    <i class="tio-add"></i>
                    <span>{{\App\CPU\translate('Add')}} {{\App\CPU\translate('New')}}</span>
                </a>
            </div> -->
        </div>
    <!-- Table -->
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="table-responsive">
                    <table
                        class="table">

                        <thead class="text-capitalize">
                            <tr>
                                <th>{{\App\CPU\translate('SL')}}</th>
                                <th>{{\App\CPU\translate('Name')}}</th>
                                <th>{{\App\CPU\translate('Email')}}</th>
                                <th>{{\App\CPU\translate('Phone')}}</th>
                                <th>{{\App\CPU\translate('Role')}}</th>
                                <th class="text-center">{{\App\CPU\translate('Status')}}</th>
                                <th class="text-center">{{\App\CPU\translate('Action')}}</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($em as $k=>$e)
                            @if($e->role)
                            <tr id="data-{{ $e->id }}">
                                <td>{{$em->firstItem()+$k}}</td>

                                <td class="text-capitalize">
                                    {{$e['name']}}
                                </td>

                                <td>
                                    <a class="title-color hover-c1" href="mailto:{{$e['email']}}">
                                        {{$e['email']}}
                                    </a>
                                </td>

                                <td>
                                    <a class="title-color hover-c1" href="tel:{{$e['phone']}}">
                                        {{$e['phone']}}
                                    </a>
                                </td>

                                <td>{{$e->role['name']}}</td>

                                <td class="text-center">
                                    <div class="form-check form-switch employee-status-switch d-inline-flex justify-content-center mb-0">
                                        <input class="form-check-input employee-status"
                                            type="checkbox"
                                            role="switch"
                                            aria-label="{{ \App\CPU\translate('Activate or deactivate') }}"
                                            data-id="{{ $e->id }}"
                                            {{ (int) $e->status === 1 ? 'checked' : '' }}>
                                    </div>
                                </td>

                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('admin.employee.update', [$e['id']]) }}"
                                            class="btn btn-outline-primary btn-sm employee-action-btn"
                                            title="{{ \App\CPU\translate('Edit') }}">
                                            <i class="mdi mdi-pencil-outline"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endif
                            @endforeach
                        </tbody>

                    </table>
                </div>

                @if($em->hasPages())
                    <div class="table-responsive mt-4">
                        <div class="px-4 d-flex justify-content-center justify-content-md-end">
                            {{$em->links()}}
                        </div>
                    </div>
                @endif

                @if(count($em)==0)
                    @include('admin-views.partials._empty-state')
                @endif
            </div>

        </div>
    </div>


</div>
@endsection

@push('script')
<script>
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

    $(document).on('change', '.employee-status', function() {
        var id = $(this).data('id');
        var status = $(this).prop('checked') ? 1 : 0;
        var $toggle = $(this);

        $.ajax({
            url: "{{ route('admin.employee.status') }}",
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
@endpush