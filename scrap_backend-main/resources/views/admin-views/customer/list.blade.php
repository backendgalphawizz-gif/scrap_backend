@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Customer List'))

@push('css_or_js')

    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-account-group menu-icon"></i>
                </span> Users
            </h3>
            <nav aria-label="breadcrumb">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">
                        <span></span>Users <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <!-- Table -->
                <div class="table-responsive datatable-custom">
                    <table
                        class="table">
                        <thead class="text-capitalize">
                        <tr>
                            <th>{{\App\CPU\translate('SL')}}</th>
                            <th>{{\App\CPU\translate('customer_name')}}</th>
                            <!-- <th>{{\App\CPU\translate('contact_email')}}</th> -->
                            <th>{{\App\CPU\translate('mobile')}}</th>
                            <th>{{\App\CPU\translate('Wallet Balance')}} </th>
                            <th>{{\App\CPU\translate('Wallet Status')}} </th>
                            <th>{{\App\CPU\translate('Total')}} {{\App\CPU\translate('Campaign')}} </th>
                            <th>{{\App\CPU\translate('block')}} / {{\App\CPU\translate('unblock')}}</th>
                            <th class="text-center">{{\App\CPU\translate('Action')}}</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($customers as $key=>$customer)
                            <tr>
                                <td>
                                    {{$customers->firstItem()+$key}}
                                </td>
                                <td>
                                    <!-- onerror="this.src='{{asset('public/assets/back-end/img/160x160/img1.jpg')}}'" -->
                                    <a href="#"
                                    class="title-color hover-c1 d-flex align-items-center gap-10">
                                        <img src="{{$customer->image}}"
                                            
                                            class="avatar rounded-circle" alt="" width="40">
                                        {{\Illuminate\Support\Str::limit($customer->name,20)}}
                                    </a>
                                </td>
                                <!-- <td>
                                    <div class="mb-1">
                                        <strong><a class="title-color hover-c1" href="mailto:{{$customer->email}}">{{$customer->email}}</a></strong>
                                    </div>
                                </td> -->
                                <td>
                                    <div class="mb-1">
                                        <strong><a class="title-color hover-c1" href="tel:{{$customer->mobile}}">{{$customer->mobile}}</a></strong>
                                    </div>
                                </td>
                                <td>
                                    @if($customer->coinWallet)
                                        <label class="btn text-info bg-soft-info font-weight-bold px-3 py-1 mb-0 fz-12">
                                            {{$customer->coinWallet?->balance ?? '0.00'}} {{\App\CPU\translate('Coins')}}
                                        </label>
                                    @else
                                        <label class="btn text-info bg-soft-info font-weight-bold px-3 py-1 mb-0 fz-12">
                                            {{'0.00'}} {{\App\CPU\translate('Coins')}}
                                        </label>
                                    @endif
                                </td>
                                <td>
                                    @if($customer->coinWallet)
                                        <label class="btn text-info bg-soft-info font-weight-bold px-3 py-1 mb-0 fz-12">
                                            <span class="update-wallet-status" data-id="{{ $customer->id }}">{!! $customer->coinWallet?->status==1?'<label class="badge badge-gradient-success">'.\App\CPU\translate('Active').'</label>':'<label class="badge badge-danger">'.\App\CPU\translate('In-Active').'</label>' !!}</span>
                                        </label>
                                    @else
                                        <label class="btn text-info bg-soft-info font-weight-bold px-3 py-1 mb-0 fz-12">
                                            <span class="update-wallet-status" data-id="{{ $customer->id }}"><label class="badge badge-gradient-danger">In-Active</label></span>
                                        </label>
                                    @endif
                                </td>
                                <td>
                                    <label class="btn text-info bg-soft-info font-weight-bold px-3 py-1 mb-0 fz-12">
                                        {{$customer->campaigns?->count()}}
                                    </label>
                                </td>

                                <td>
                                    <span class="update-account-status" data-id="{{ $customer->id }}">{!! $customer->status==1?'<label class="badge badge-gradient-success">'.\App\CPU\translate('Active').'</label>':'<label class="badge badge-danger">'.\App\CPU\translate('In-Active').'</label>' !!}</span>
                                </td>

                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a title="{{\App\CPU\translate('View')}}"
                                        class="btn btn-outline-info btn-sm square-btn"
                                        target="_blank"
                                        href="{{ route('admin.user.view',[$customer['id']]) }}">
                                            View
                                        </a>
                                        <a title="{{\App\CPU\translate('View')}}"
                                        class="btn btn-outline-danger btn-sm delete square-btn"
                                        onclick="return confirm('{{\App\CPU\translate('Are you sure you want to delete this customer?')}}');"
                                        href="{{ route('admin.user.delete',[$customer['id']]) }}">
                                            Delete
                                        </a>
                                    </div>
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
                        {!! $customers->links() !!}
                    </div>
                </div>

                @if(count($customers)==0)
                    <div class="text-center p-4">
                        <img class="mb-3 w-160" src="{{asset('public/assets/back-end')}}/svg/illustrations/sorry.svg"
                            alt="Image Description">
                        <p class="mb-0">{{\App\CPU\translate('No data to show')}}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('script_2')
    
    <script>
        $(document).on('click', '.update-wallet-status', function () {
            let id = $(this).attr("data-id");

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
                        url: "{{ route('admin.user.update-wallet-status') }}",
                        method: 'POST',
                        data: {
                            id: id,
                            status: status
                        },
                        dataType:'json',
                        success: function (response) {
                            if(response.status){
                                swal.fire('', '{{\App\CPU\translate('Status updated successfully')}}', 'success').then((result) => {
                                    location.reload();
                                });
                            } else {
                                swal.fire('', response.message, 'error');
                            }
                        }
                    });
                }
            })
        });

        $(document).on('click', '.update-account-status', function () {
            let id = $(this).attr("data-id");

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
                        url: "{{ route('admin.user.update-user-status') }}",
                        method: 'POST',
                        data: {
                            id: id,
                            status: status
                        },
                        dataType:'json',
                        success: function (response) {
                            if(response.status){
                                swal.fire('', '{{\App\CPU\translate('Status updated successfully')}}', 'success').then((result) => {
                                    location.reload();
                                });
                            } else {
                                swal.fire('', response.message, 'error');
                            }
                        }
                    });
                }
            })
        });
    </script>
@endpush
