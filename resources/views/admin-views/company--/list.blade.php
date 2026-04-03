@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('CAB'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">

        <!-- Page Title -->
        <div class="mb-4">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-baseline gap-2 backbtndiv w-100">
                <a class="textfont-set" href="{{route('admin.dashboard.index')}}"> 
                    <i class="tio-chevron-left"></i>Back
                </a>
                <div class="backdiv">
                    {{\App\CPU\translate('CAB')}}
                    <span class="badge badge-soft-dark radius-50">{{\App\Model\Seller::count()}}</span>
                </div>
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Card -->
        <div class="card">
            <!-- Header -->
            <div class="px-3 py-4">
                <div class="row gy-2 align-items-center">
                    <div class="col-sm-8 col-md-6 col-lg-4">
                        <!-- Search -->
                        <form action="{{ url()->current() }}" method="GET">
                            <div class="input-group input-group-merge input-group-custom">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="tio-search"></i>
                                    </div>
                                </div>
                                <input id="datatableSearch_" type="search" name="search" class="form-control"
                                       placeholder="{{\App\CPU\translate('Search by Name or Email or Phone')}}"
                                       aria-label="Search orders" value="{{ $search }}">
                                <button type="submit" class="btn btn--primary">{{\App\CPU\translate('search')}}</button>
                            </div>
                        </form>
                        <!-- End Search -->
                    </div>
                    <div class="col-sm-4 col-md-6 col-lg-8 mb-2 mb-sm-0">
                        <div class="d-flex justify-content-sm-end gap-2">
                            <a href="{{ route('admin.company.create') }}" class="btn btn--primary">
                                Add
                            </a>
                        </div>
                    </div>
                </div>
                <!-- End Row -->
            </div>
            <!-- End Header -->

            <!-- Table -->
            <div class="table-responsive datatable-custom">
                <table
                    style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                    class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                    <thead class="thead-light thead-50 text-capitalize">
                        <tr>
                            <th>{{\App\CPU\translate('SL')}}</th>
                            <th>{{\App\CPU\translate('username')}}</th>
                            <th>{{\App\CPU\translate('name')}}</th>
                            <th>{{\App\CPU\translate('mobile')}}</th>
                            <th>{{\App\CPU\translate('company_name')}}</th>
                            <th>{{\App\CPU\translate('address')}}</th>
                            <th>{{\App\CPU\translate('Status')}} </th>
                            <th class="text-center">{{\App\CPU\translate('Action')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($companies as $key => $company)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>{{ $company->username }}</td>
                            <td>{{ $company->f_name }}</td>
                            <td>{{ $company->phone }}</td>
                            <td>{{ $company->shop->name }}</td>
                            <td>{{ $company->shop->address }}</td>
                            <td>{!! ($company->status == 'pending') ? '<span class="badge badge-warning">'.ucwords($company->status).'</span>' : '<span class="badge badge-success">'.ucwords($company->status).'</span>' !!}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.company.show', $company->id) }}" class="btn btn-primary btn-sm"><i class="tio-invisible"></i></a>
                                <a href="{{ route('admin.company.edit', $company->id) }}" class="btn btn-danger btn-sm"><i class="tio-edit"></i></a>
                                <a href="{{ route('admin.company.delete', $company->id) }}" class="btn btn-danger btn-sm"><i class="tio-delete"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <!-- End Table -->

            <div class="table-responsive mt-4">
                <div class="px-4 d-flex justify-content-lg-end">
                    <!-- Pagination -->
                    {!! $companies->links() !!}
                </div>
            </div>

            @if(count($companies)==0)
                <div class="text-center p-4">
                    <img class="mb-3 w-160" src="{{asset('public/assets/back-end')}}/svg/illustrations/sorry.svg"
                         alt="Image Description">
                    <p class="mb-0">{{\App\CPU\translate('No data to show')}}</p>
                </div>
        @endif
        <!-- End Footer -->
        </div>
        <!-- End Card -->
    </div>
@endsection

@push('script_2')
    <script>
        $(document).on('change', '.switcher_input', function () {
            let id = $(this).attr("id");

            let status = 0;
            if (jQuery(this).prop("checked") === true) {
                status = 1;
            }

            Swal.fire({
                title: '{{\App\CPU\translate('Are you sure')}}?',
                text: '{{\App\CPU\translate('want_to_change_status')}}',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'No',
                confirmButtonText: 'Yes',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{route('admin.customer.status-update')}}",
                        method: 'POST',
                        data: {
                            id: id,
                            status: status
                        },
                        success: function () {
                            toastr.success('{{\App\CPU\translate('Status updated successfully')}}');
                        }
                    });
                }
            })
        });
    </script>
@endpush
