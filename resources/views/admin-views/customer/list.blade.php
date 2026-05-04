@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Customer List'))

@push('css_or_js')

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
         .premium-pagination-wrap {
        margin-top: 28px !important;
    }   
        .premium-table-card {
            background: linear-gradient(145deg, #ffffff 0%, #f8fbff 100%);
            border: 1px solid #e8eef7;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(11, 36, 71, 0.08);
            overflow: hidden;
        }

        .premium-table-header {
            background: linear-gradient(90deg, #0f4c81 0%, #1367ad 100%);
            color: #fff;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }

        .premium-table-header h5 {
            margin: 0;
            font-size: 16px;
            font-weight: 600;
            letter-spacing: 0.2px;
        }

        .premium-table-header .count-pill {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 999px;
            padding: 6px 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .premium-table-wrap {
            padding: 10px 14px 14px;
        }

        .premium-user-table {
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        .premium-user-table thead th {
            border: none;
            color: #4d5f76;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 8px 14px;
        }

        .premium-user-table tbody tr {
            background: #fff;
            box-shadow: 0 3px 12px rgba(18, 42, 66, 0.08);
            transition: transform .18s ease, box-shadow .18s ease;
        }

        .premium-user-table tbody tr:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(18, 42, 66, 0.12);
        }

        .premium-user-table tbody td {
            border: none;
            vertical-align: middle;
            padding: 14px;
            color: #33475b;
        }

        .premium-user-table tbody tr td:first-child {
            border-top-left-radius: 12px;
            border-bottom-left-radius: 12px;
        }

        .premium-user-table tbody tr td:last-child {
            border-top-right-radius: 12px;
            border-bottom-right-radius: 12px;
        }

        .premium-user-table .avatar {
            border: 2px solid #e7f1fb;
            box-shadow: 0 2px 10px rgba(15, 76, 129, 0.18);
        }

        .premium-user-table .btn.square-btn {
            border-radius: 10px;
            font-weight: 600;
            min-width: 74px;
        }

        .premium-user-table .action-icon-group {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 7px;
            border-radius: 16px;
            background: linear-gradient(180deg, #ffffff 0%, #f3f8ff 100%);
            border: 1px solid #d7e5f4;
            box-shadow: 0 6px 16px rgba(16, 42, 67, 0.08), inset 0 1px 0 rgba(255, 255, 255, 0.95);
        }

        .premium-user-table .action-icon-btn {
            width: 42px;
            height: 42px;
            min-width: 42px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 13px;
            border: none;
            text-decoration: none;
            position: relative;
            overflow: hidden;
            transition: transform .18s ease, box-shadow .18s ease, background-color .18s ease, border-color .18s ease, color .18s ease;
        }

        .premium-user-table .action-icon-btn i {
            font-size: 19px;
            line-height: 1;
            transition: transform .18s ease;
        }

        .premium-user-table .action-icon-btn:hover {
            transform: translateY(-1px);
            text-decoration: none;
        }

        .premium-user-table .action-icon-btn:hover i {
            transform: scale(1.05);
        }

        .premium-user-table .action-icon-btn:focus {
            box-shadow: 0 0 0 0.18rem rgba(19, 103, 173, 0.16);
        }

        .premium-user-table .action-icon-btn:focus-visible {
            outline: none;
            box-shadow: 0 0 0 0.22rem rgba(19, 103, 173, 0.2);
        }

        .premium-user-table .action-icon-btn.view-btn {
            background: linear-gradient(135deg, #f0f7ff 0%, #e4f0fb 100%);
            color: #0f4c81;
            box-shadow: 0 7px 14px rgba(15, 76, 129, 0.1);
        }

        .premium-user-table .action-icon-btn.view-btn:hover {
            background: linear-gradient(135deg, #0f4c81 0%, #1367ad 100%);
            color: #ffffff;
            box-shadow: 0 11px 20px rgba(15, 76, 129, 0.24);
        }

        .premium-user-table .action-icon-btn.log-btn {
            background: linear-gradient(135deg, #f3fff3 0%, #e9fbe9 100%);
            color: #177a3f;
            box-shadow: 0 7px 14px rgba(23, 122, 63, 0.12);
        }

        .premium-user-table .action-icon-btn.log-btn:hover {
            background: linear-gradient(135deg, #177a3f 0%, #239a52 100%);
            color: #ffffff;
            box-shadow: 0 11px 20px rgba(23, 122, 63, 0.24);
        }

        .premium-user-table .action-icon-btn.delete-btn {
            background: linear-gradient(135deg, #fff4f6 0%, #ffe9ee 100%);
            color: #c73a57;
            box-shadow: 0 7px 14px rgba(199, 58, 87, 0.1);
        }

        .premium-user-table .action-icon-btn.delete-btn:hover {
            background: linear-gradient(135deg, #c73a57 0%, #de5a74 100%);
            color: #ffffff;
            box-shadow: 0 11px 20px rgba(199, 58, 87, 0.24);
        }

        .premium-pagination-wrap {
            border-top: 1px solid #e8ebef;
            margin-top: 28px;
            padding: 12px 18px 16px;
        }

        .premium-pagination-shell {
            background: transparent;
            border: none;
            border-radius: 0;
            padding: 0;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            box-shadow: none;
        }

        .premium-pagination-inline {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 0;
            flex-wrap: nowrap;
            overflow-x: auto;
            scrollbar-width: thin;
        }

        .premium-pagination-nav {
            margin-left: 0;
            flex: 0 0 auto;
        }

        .premium-page-summary {
            display: none;
        }

        .premium-pagination-shell .pagination {
            margin: 0;
            gap: 8px;
            flex-wrap: nowrap;
            justify-content: flex-end;
            white-space: nowrap;
        }

        .premium-pagination-shell .page-link {
            border: 1px solid #d8dde3;
            border-radius: 4px;
            color: #5e6975;
            font-weight: 600;
            min-width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            box-shadow: none;
            text-decoration: none;
            padding: 0 14px;
            transition: background-color .15s ease, border-color .15s ease, color .15s ease;
        }

        .premium-pagination-shell .page-link:hover {
            border-color: #c8d0d9;
            background: #ffffff;
            color: #414c58;
            text-decoration: none;
        }

        .premium-pagination-shell .page-item.active .page-link {
            background: #ffffff;
            border-color: #1367ad;
            color: #1367ad;
            box-shadow: none;
        }

        .premium-pagination-shell .page-item.disabled .page-link {
            background: #ffffff;
            color: #a1abb6;
            border-color: #e1e5ea;
            box-shadow: none;
            pointer-events: none;
        }

        @media (max-width: 767px) {
            .premium-table-header {
                padding: 14px;
            }

            .premium-table-wrap {
                padding: 8px;
            }

            .premium-user-table tbody td {
                padding: 12px 10px;
            }

            .premium-pagination-wrap {
                padding: 14px;
            }

            .premium-pagination-shell {
                padding: 10px;
            }

            .premium-pagination-inline {
                justify-content: flex-end;
                gap: 8px;
            }
        }

      
    </style>
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
                <div class="premium-table-card">
                    <div class="px-3 pt-3 pb-3 d-flex justify-content-end">
                        <form method="GET" action="{{ route('admin.user') }}" class="d-flex flex-wrap align-items-center justify-content-end gap-2">
                            <input type="text" class="form-control" name="id" value="{{ request('id') }}" placeholder="ID" style="max-width: 120px;">
                            <input type="text" class="form-control" name="name" value="{{ request('name') }}" placeholder="Name" style="max-width: 220px;">
                            <input type="text" class="form-control" name="mobile" value="{{ request('mobile') }}" placeholder="Mobile" style="max-width: 180px;">
                            <input type="text" class="form-control" name="email" value="{{ request('email') }}" placeholder="Email" style="max-width: 220px;">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('admin.user') }}" class="btn btn-outline-secondary">Reset</a>
                        </form>
                    </div>

                    <div class="table-responsive datatable-custom premium-table-wrap">
                    <table class="table premium-user-table">
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
                            @php($customerImage = blank($customer->image)
                                ? asset('public/assets/front-end/img/image-place-holder.png')
                                : (\Illuminate\Support\Str::startsWith($customer->image, ['http://', 'https://'])
                                    ? $customer->image
                                    : asset('storage/profile/' . ltrim($customer->image, '/'))))
                            <tr>
                                <td>
                                    {{$customers->firstItem()+$key}}
                                </td>
                                <td>
                                    <!-- onerror="this.src='{{asset('public/assets/back-end/img/160x160/img1.jpg')}}'" -->
                                    <a href="#"
                                    class="title-color hover-c1 d-flex align-items-center gap-10">
                                        <img src="{{$customerImage}}"
                                            onerror="this.onerror=null;this.src=&quot;{{ asset('public/assets/front-end/img/image-place-holder.png') }}&quot;;"
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
                                    <div class="d-flex justify-content-center">
                                        <div class="action-icon-group">
                                        <a title="{{\App\CPU\translate('View')}}"
                                        class="btn btn-sm action-icon-btn view-btn"
                                        aria-label="{{\App\CPU\translate('View')}}"
                                        target="_self"
                                        href="{{ route('admin.user.view',[$customer['id']]) }}">
                                            <i class="mdi mdi-eye-outline"></i>
                                        </a>

                                         <a title="{{\App\CPU\translate('View')}}"
                                        class="btn btn-sm action-icon-btn view-btn"
                                        aria-label="{{\App\CPU\translate('View')}}"
                                        target="_self"
                                        href="{{ route('admin.user.edit',[$customer['id']]) }}">
                                            <i class="mdi mdi-pencil-outline"></i>
                                        </a>

                                        <a title="User Activity Logs"
                                        class="btn btn-sm action-icon-btn log-btn"
                                        aria-label="User Activity Logs"
                                        target="_self"
                                        href="{{ route('admin.user.activity.logs',[$customer['id']]) }}">
                                            <i class="mdi mdi-timeline-text-outline"></i>
                                        </a>
                                        <a title="{{\App\CPU\translate('Delete')}}"
                                        class="btn btn-sm delete action-icon-btn delete-btn"
                                        aria-label="{{\App\CPU\translate('Delete')}}"
                                        onclick="return confirm('{{\App\CPU\translate('Are you sure you want to delete this customer?')}}');"
                                        href="{{ route('admin.user.delete',[$customer['id']]) }}">
                                            <i class="mdi mdi-delete-outline"></i>
                                        </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    </div>

                    <div class="premium-pagination-wrap">
                        <div class="premium-pagination-shell">
                            <div class="premium-pagination-inline">
                                {!! $customers->onEachSide(1)->links('vendor.pagination.premium') !!}
                            </div>
                        </div>
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
