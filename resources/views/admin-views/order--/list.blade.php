@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Order List'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div>
            <!-- Page Title -->
            <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
                <h2 class="h1 mb-0">
                    <img src="{{asset('/public/assets/back-end/img/all-orders.png')}}" class="mb-1 mr-1" alt="">
                    <span class="page-header-title">
                        @if($status =='processing')
                            {{\App\CPU\translate('packaging')}}
                        @elseif($status =='failed')
                            {{\App\CPU\translate('Failed_to_Deliver')}}
                        @elseif($status == 'all')
                            {{\App\CPU\translate('all')}}
                        @else
                            {{\App\CPU\translate(str_replace('_',' ',$status))}}
                        @endif
                    </span>
                    {{\App\CPU\translate('Orders')}}
                </h2>
                <span class="badge badge-soft-dark radius-50 fz-14">{{$orders->total()}}</span>
            </div>
            <!-- End Page Title -->

            <!-- Order States -->
            <div class="card">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ url()->current() }}" id="form-data" method="GET">
                            <div class="row gy-3 gx-2">
                                <div class="col-12 pb-0">
                                    <h4>{{\App\CPU\translate('select')}} {{\App\CPU\translate('date')}} {{\App\CPU\translate('range')}}</h4>
                                </div>
                                @if(request('delivery_man_id'))
                                    <input type="hidden" name="delivery_man_id" value="{{ request('delivery_man_id') }}">
                                @endif

                                <div class="col-sm-6 col-md-3">
                                    <select name="filter" class="form-control">
                                        <option value="all" {{ $filter == 'all' ? 'selected' : '' }}>{{\App\CPU\translate('all')}}</option>
                                        {{-- <option value="admin" {{ $filter == 'admin' ? 'selected' : '' }}>{{\App\CPU\translate('In_House')}}</option> --}}
                                        {{-- <option value="seller" {{ $filter == 'seller' ? 'selected' : '' }}>{{\App\CPU\translate('Seller')}}</option> --}}
                                        @if(($status == 'all' || $status == 'delivered') && !request()->has('delivery_man_id'))
                                        {{-- <option value="POS" {{ $filter == 'POS' ? 'selected' : '' }}>POS</option> --}}
                                        @endif
                                        @foreach($sellers as $seller)
                                            <option value="{{ $seller->id }}">{{ $seller->f_name }}{{ $seller->l_name }} ({{ $seller->shop->name }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <div class="form-floating">
                                        <input type="date" name="from" value="{{$from}}" id="from_date"
                                            class="form-control" max="{{ date('Y-m-d') }}">
                                        <label>{{\App\CPU\translate('Start_Date')}}</label>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3  mt-2 mt-sm-0">
                                    <div class="form-floating">
                                        <input type="date" value="{{$to}}" name="to" id="to_date" class="form-control" max="{{ date('Y-m-d') }}">
                                        <label>{{\App\CPU\translate('End_Date')}}</label>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-3 col-md-3 mt-2 mt-sm-0  ">
                                    <button type="submit" class="btn btn--primary btn-block" onclick="formUrlChange(this)" data-action="{{ url()->current() }}">
                                        {{\App\CPU\translate('show')}} {{\App\CPU\translate('data')}}
                                    </button>
                                </div>
                                <div class="col-lg-1 col-sm-3 col-md-3 mt-2 mt-sm-0  ">
                                    <button type="button" class="btn btn--primary btn-block" onclick="window.location.href= '{{ route('admin.orders.list','all') }}'">
                                        {{ \App\CPU\translate('reset') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Order stats -->
                    {{-- @if($status == 'all' && $filter != 'POS') --}}
                    <div class="row g-2 mb-20">
                        <div class="col-sm-6 col-lg-3 col-6">
                            <!-- Card -->
                            <a class="order-stats order-stats_pending {{ $status == 'pending' ? 'active' : '' }}" href="{{route('admin.orders.list',['pending'])}}" data-status="pending">
                                <div class="order-stats__content">
                                    <img width="20" src="{{asset('/public/assets/back-end/img/pending.png')}}" class="svg" alt="">
                                    <h6 class="order-stats__subtitle">{{\App\CPU\translate('pending')}}</h6>
                                </div>
                                <span class="order-stats__title">
                                    {{ $pending_count }}
                                </span>
                            </a>
                            <!-- End Card -->
                        </div>

                        <div class="col-sm-6 col-lg-3 col-6">
                            <!-- Card -->
                            <a class="order-stats order-stats_confirmed {{ $status == 'confirmed' ? 'active' : '' }}" href="{{route('admin.orders.list',['confirmed'])}}" data-status="confirmed">
                                <div class="order-stats__content" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                                    <img width="20" src="{{asset('/public/assets/back-end/img/confirmed.png')}}" alt="">
                                    <h6 class="order-stats__subtitle">{{\App\CPU\translate('confirmed')}}</h6>
                                </div>
                                <span class="order-stats__title">
                                    {{ $confirmed_count }}
                                </span>
                            </a>
                            <!-- End Card -->
                        </div>

                        <div class="col-sm-6 col-lg-3 col-6 d-none">
                            <!-- Card -->
                            <a class="order-stats order-stats_packaging {{ $status == 'processing' ? 'active' : '' }}" href="{{route('admin.orders.list',['processing'])}}" data-status="processing">
                                <div class="order-stats__content" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                                    <img width="20" src="{{asset('/public/assets/back-end/img/packaging.png')}}" alt="">
                                    <h6 class="order-stats__subtitle">{{\App\CPU\translate('Packaging')}}</h6>
                                </div>
                                <span class="order-stats__title">
                                    {{ $processing_count }}
                                </span>
                            </a>
                            <!-- End Card -->
                        </div>
                        <div class="col-sm-6 col-lg-3 col-6">
                            <!-- Card -->
                            <a class="order-stats order-stats_packaging {{ $status == 'shipped' ? 'active' : '' }}" href="{{route('admin.orders.list',['shipped'])}}" data-status="shipped">
                                <div class="order-stats__content" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                                    <img width="20" src="{{asset('/public/assets/back-end/img/packaging.png')}}" alt="">
                                    <h6 class="order-stats__subtitle">{{\App\CPU\translate('Shipped')}}</h6>
                                </div>
                                <span class="order-stats__title">
                                    {{ $shipped_count }}
                                </span>
                            </a>
                            <!-- End Card -->
                        </div>

                        <div class="col-sm-6 col-lg-3 col-6">
                            <!-- Card -->
                            <a class="order-stats order-stats_out-for-delivery {{ $status == 'out_for_delivery' ? 'active' : '' }}" href="{{route('admin.orders.list',['out_for_delivery'])}}" data-status="out_for_delivery">
                                <div class="order-stats__content" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                                    <img width="20" src="{{asset('/public/assets/back-end/img/out-of-delivery.png')}}" alt="">
                                    <h6 class="order-stats__subtitle">{{\App\CPU\translate('out_for_delivery')}}</h6>
                                </div>
                                <span class="order-stats__title">
                                    {{ $out_for_delivery_count }}
                                </span>
                            </a>
                            <!-- End Card -->
                        </div>

                        <div class="col-sm-6 col-lg-3 col-6">
                            <div class="order-stats order-stats_delivered cursor-pointer {{ $status == 'delivered' ? 'active' : '' }}" data-status="delivered">
                                <div class="order-stats__content" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                                    <img width="20" src="{{asset('/public/assets/back-end/img/delivered.png')}}" alt="">
                                    <h6 class="order-stats__subtitle">{{\App\CPU\translate('delivered')}}</h6>
                                </div>
                                <span class="order-stats__title">
                                    {{ $delivered_count }}
                                </span>
                            </div>
                        </div>

                        <div class="col-sm-6 col-lg-3 col-6">
                            <div class="order-stats order-stats_canceled cursor-pointer  {{ $status == 'canceled' ? 'active' : '' }}" data-status="canceled">
                                <div class="order-stats__content" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                                    <img width="20" src="{{asset('/public/assets/back-end/img/canceled.png')}}" alt="">
                                    <h6 class="order-stats__subtitle">{{\App\CPU\translate('cancelled')}}</h6>
                                </div>
                                <span class="order-stats__title">
                                    {{ $canceled_count }}
                                </span>
                            </div>
                        </div>

                        <div class="col-sm-6 col-lg-3 col-6">
                            <div class="order-stats order-stats_returned cursor-pointer   {{ $status == 'returned' ? 'active' : '' }}" data-status="returned">
                                <div class="order-stats__content" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                                    <img width="20" src="{{asset('/public/assets/back-end/img/returned.png')}}" alt="">
                                    <h6 class="order-stats__subtitle">{{\App\CPU\translate('returned')}}</h6>
                                </div>
                                <span class="order-stats__title">
                                    {{ $returned_count }}
                                </span>
                            </div>
                        </div>

                        <div class="col-sm-6 col-lg-3 col-6">
                            <div class="order-stats order-stats_failed cursor-pointer  {{ $status == 'failed' ? 'active' : '' }}" data-status="failed">
                                <div class="order-stats__content" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                                    <img width="20" src="{{asset('/public/assets/back-end/img/failed-to-deliver.png')}}" alt="">
                                    <h6 class="order-stats__subtitle">{{\App\CPU\translate('Failed_To_Delivery')}}</h6>
                                </div>
                                <span class="order-stats__title">
                                    {{ $failed_count }}
                                </span>
                            </div>
                        </div>
                    </div>
                    {{-- @endif --}}
                    <!-- End Order stats -->

                    <!-- Data Table Top -->
                    <div class="px-3 py-4 light-bg">
                        <div class="row g-2 flex-grow-1 justify-content-end ">
                            <div class="col-sm-8 col-md-6 col-lg-6 d-flex align-items-center justify-content-end">
                                <button type="button" class="btn btn-outline--primary mr-3" data-toggle="dropdown">
                                    <i class="tio-download-to"></i>
                                    {{\App\CPU\translate('export')}}
                                    <i class="tio-chevron-down"></i>
                                </button>

                                <ul class="dropdown-menu dropdown-menu-right">
                                    <!-- <li>
                                        <a type="submit" class="dropdown-item d-flex align-items-center gap-2" href="{{ route('admin.orders.order-bulk-export', ['delivery_man_id' => request('delivery_man_id'), 'status' => $status, 'from' => $from, 'to' => $to, 'filter' => $filter, 'search' => $search]) }}">
                                            <img width="14" src="{{asset('/public/assets/back-end/img/excel.png')}}" alt="">
                                            {{\App\CPU\translate('Excel')}}
                                        </a>
                                    </li> -->
                                    <!-- <li>
                                        <a type="submit" class="dropdown-item d-flex align-items-center gap-2" href="{{ route('admin.orders.order-bulk-export', ['delivery_man_id' => request('delivery_man_id'), 'status' => $status, 'from' => $from, 'to' => $to, 'filter' => $filter, 'search' => $search, 'type' => 'csv']) }}">
                                            <img width="14" src="{{asset('/public/assets/back-end/img/csv.png')}}" alt="">
                                            {{\App\CPU\translate('CSV')}}
                                        </a>
                                    </li> -->
                                    <a id="exportCsvBtn" href="#" class="dropdown-item d-flex align-items-center gap-2">
                                        <img width="14" src="{{asset('/public/assets/back-end/img/csv.png')}}" alt="">
                                        {{\App\CPU\translate('CSV')}}
                                    </a>

                                </ul> 
                                <form action="" method="GET">
                                    <!-- Search -->
                                    <div class="input-group input-group-custom input-group-merge d-none">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="datatableSearch_" type="search" name="search" class="form-control d-none"
                                            placeholder="{{\App\CPU\translate('Search by Order ID')}}" aria-label="Search by Order ID" value="{{ $search }}"
                                            required>
                                        <button type="submit" class="d-none btn btn--primary input-group-text">{{\App\CPU\translate('search')}}</button>
                                    </div>
                                    <!-- End Search -->
                                </form>
                            </div>
                            
                        </div>
                        <!-- End Row -->
                    </div>
                    <!-- End Data Table Top -->

                    <!-- Table -->
                    <div class="table-responsive datatable-custom d-none">
                        <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100" style="text-align: {{ Session::get('direction') === "rtl" ? 'right' : 'left' }}">
                            <thead class="thead-light thead-50 text-capitalize">
                                <tr>
                                    <th class="">{{\App\CPU\translate('SL')}}</th>
                                    <th>{{\App\CPU\translate('Order')}} {{\App\CPU\translate('ID')}}</th>
                                    <th>{{\App\CPU\translate('Order')}} {{\App\CPU\translate('Date')}}</th>
                                    <th>{{\App\CPU\translate('customer')}} {{\App\CPU\translate('info')}}</th>
                                    <th>{{\App\CPU\translate('Store')}}</th>
                                    <th class="text-right">{{\App\CPU\translate('Total')}} {{\App\CPU\translate('Amount')}}</th>
                                    <th class="text-center">{{\App\CPU\translate('Order')}} {{\App\CPU\translate('Status')}} </th>
                                    <th class="text-center">{{\App\CPU\translate('Action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($orders->isNotEmpty())
                                    @foreach($orders as $key=>$order)

                                        <tr class="status-{{$order['order_status']}} class-all">
                                            <td class="">
                                                {{$orders->firstItem()+$key}}
                                            </td>
                                            <td >
                                                <a class="title-color" href="{{route('admin.orders.details',['id'=>$order['id']])}}">{{$order['id']}}</a>
                                            </td>
                                            <td>
                                                <div>{{date('d M Y',strtotime($order['created_at']))}},</div>
                                                <div>{{ date("h:i A",strtotime($order['created_at'])) }}</div>
                                            </td>
                                            <td>
                                                @if($order->customer_id == 0)
                                                    <strong class="title-name">{{\App\CPU\translate('walking_customer')}}</strong>
                                                @else
                                                    @if($order->customer)
                                                        <a class="text-body text-capitalize" href="{{route('admin.orders.details',['id'=>$order['id']])}}">
                                                            <strong class="title-name">{{$order->customer['f_name'].' '.$order->customer['l_name']}}</strong>
                                                        </a>
                                                        <a class="d-block title-color" href="tel:{{ $order->customer['phone'] }}">{{ $order->customer['phone'] }}</a>
                                                    @else
                                                        <label class="badge badge-danger fz-12">{{\App\CPU\translate('invalid_customer_data')}}</label>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                <span class="store-name font-weight-medium">
                                                    @if($order->seller_is == 'seller')
                                                        {{ isset($order->seller->shop) ? $order->seller->shop->name : 'Store not found' }}
                                                    @elseif($order->seller_is == 'admin')
                                                        {{\App\CPU\translate('In-House')}}
                                                    @endif
                                                </span>
                                            </td>
                                            <td class="text-right">
                                                <div>
                                                    @php($discount = 0)
                                                    @if($order->coupon_discount_bearer == 'inhouse' && !in_array($order['coupon_code'], [0, NULL]))
                                                        @php($discount = $order->discount_amount)
                                                    @endif
                                                    {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($order->order_amount+$discount))}}
                                                </div>

                                                @if($order->payment_status=='paid')
                                                    <span class="badge text-success fz-12 px-0">
                                                        {{\App\CPU\translate('paid')}}
                                                    </span>
                                                @else
                                                    <span class="badge text-danger fz-12 px-0">
                                                        {{\App\CPU\translate('unpaid')}}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-center text-capitalize">
                                                @if($order['order_status']=='pending')
                                                    <span class="badge badge-soft-info fz-12">
                                                        {{\App\CPU\translate($order['order_status'])}}
                                                    </span>

                                                @elseif($order['order_status']=='processing' || $order['order_status']=='out_for_delivery')
                                                    <span class="badge badge-soft-warning fz-12">
                                                        {{str_replace('_',' ',$order['order_status'] == 'processing' ? \App\CPU\translate('packaging'):\App\CPU\translate($order['order_status']))}}
                                                    </span>
                                                @elseif($order['order_status']=='confirmed')
                                                    <span class="badge badge-soft-success fz-12">
                                                        {{\App\CPU\translate($order['order_status'])}}
                                                    </span>
                                                @elseif($order['order_status']=='failed')
                                                    <span class="badge badge-danger fz-12">
                                                        {{\App\CPU\translate('failed_to_deliver')}}
                                                    </span>
                                                @elseif($order['order_status']=='delivered')
                                                    <span class="badge badge-soft-success fz-12">
                                                        {{\App\CPU\translate($order['order_status'])}}
                                                    </span>
                                                @else
                                                    <span class="badge badge-soft-danger fz-12">
                                                        {{\App\CPU\translate($order['order_status'])}}
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center gap-2">
                                                    <a class="btn btn-outline--primary square-btn btn-sm mr-1" title="{{\App\CPU\translate('view')}}"
                                                        href="{{route('admin.orders.details',['id'=>$order['id']])}}">
                                                        <img src="{{asset('/public/assets/back-end/img/eye.svg')}}" class="svg" alt="">
                                                    </a>
                                                    <a class="btn btn-outline-success square-btn btn-sm mr-1" target="_blank" title="{{\App\CPU\translate('invoice')}}"
                                                        href="{{route('admin.orders.generate-invoice',[$order['id']])}}">
                                                        <i class="tio-download-to"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <th colspan="8">
                                            <div class="text-center p-4">
                                                <img class="mb-3 w-160" src="{{asset('public/assets/back-end')}}/svg/illustrations/sorry.svg" alt="Image Description">
                                                <p class="mb-0">{{\App\CPU\translate('No data to show')}}</p>
                                            </div>
                                        </th>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <!-- End Table -->

                    <!-- Pagination -->
                    <div class="table-responsive mt-4 d-none">
                        <div class="d-flex justify-content-lg-end">
                            <!-- Pagination -->
                            {!! $orders->links() !!}
                        </div>
                    </div>
                    <!-- End Pagination -->

                    <!-- Table -->
                    <div class="table-responsive    ">
                        <table class="order-list-table table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100" style="text-align: {{ Session::get('direction') === "rtl" ? 'right' : 'left' }}">
                            <thead class="thead-light thead-50 text-capitalize">
                                <tr>
                                    <th class="">{{\App\CPU\translate('SL')}}</th>
                                    <th>{{\App\CPU\translate('Order')}} {{\App\CPU\translate('ID')}}</th>
                                    <th>{{\App\CPU\translate('Order')}} {{\App\CPU\translate('Date')}}</th>
                                    <th>{{\App\CPU\translate('customer')}} {{\App\CPU\translate('Info')}}</th>
                                    <th>{{\App\CPU\translate('Store')}} {{\App\CPU\translate('Name')}}</th>
                                    <th class="text-right">{{\App\CPU\translate('Total')}} {{\App\CPU\translate('Amount')}}</th>
                                    <th class="text-center">{{\App\CPU\translate('Order')}} {{\App\CPU\translate('Status')}} </th>
                                    <th class="text-center">{{\App\CPU\translate('Payment')}} {{\App\CPU\translate('Status')}} </th>
                                    <th class="text-center">{{\App\CPU\translate('Payment')}} {{\App\CPU\translate('Method')}} </th>
                                    <!-- <th class="text-center">{{\App\CPU\translate('Delivery')}} {{\App\CPU\translate('Date')}} </th> -->
                                    <th class="text-center">{{\App\CPU\translate('Action')}}</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>


                </div>
            </div>
            <!-- End Order States -->

            <!-- Nav Scroller -->
            <div class="js-nav-scroller hs-nav-scroller-horizontal d-none">
                <span class="hs-nav-scroller-arrow-prev d-none">
                    <a class="hs-nav-scroller-arrow-link" href="javascript:;">
                        <i class="tio-chevron-left"></i>
                    </a>
                </span>

                <span class="hs-nav-scroller-arrow-next d-none">
                    <a class="hs-nav-scroller-arrow-link" href="javascript:;">
                        <i class="tio-chevron-right"></i>
                    </a>
                </span>

                <!-- Nav -->
                <ul class="nav nav-tabs page-header-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">{{\App\CPU\translate('order_list')}}</a>
                    </li>
                </ul>
                <!-- End Nav -->
            </div>
            <!-- End Nav Scroller -->
        </div>
        <!-- End Page Header -->
    </div>
@endsection

@push('script_2')
    <link rel="stylesheet" href="//cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <script>

        var status = "{{ $status }}"

        function filter_order() {
            $.get({
                url: '{{route('admin.orders.inhouse-order-filter')}}',
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    toastr.success('{{\App\CPU\translate('order_filter_success')}}');
                    location.reload();
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        };

        $('#from_date,#to_date').change(function () {
            let fr = $('#from_date').val();
            let to = $('#to_date').val();
            if(fr != ''){
                $('#to_date').attr('required','required');
            }
            if(to != ''){
                $('#from_date').attr('required','required');
            }
            if (fr != '' && to != '') {
                if (fr > to) {
                    $('#from_date').val('');
                    $('#to_date').val('');
                    toastr.error('{{\App\CPU\translate('Invalid date range')}}!', Error, {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            }

        })

        // let productTable = $('.order-list-table').DataTable({
        //     processing: true,
        //     serverSide: true,
        //     ajax: {
        //         method: "GET",
        //         url: "{{ route('admin.orders.paginate', $status) }}"
        //     }
        // });

        var orderTable = $(".order-list-table").DataTable({
            // dom: 'rl<"right"Bf>tipv',
            "dom": '<"top m-2"Bf>rt<"bottom"ilp>',
            buttons: [
                {
                    extend: 'colvis',
                    className: 'btn btn--primary btn-block',
                    text: "Columns",
                    postfixButtons: [ 'colvisRestore' ]
                }
            ],
            "searchPlaceholder": "placeholderText",
            // Bfrtip
            // dom: '<"bottom"lf><<t>ip>',
            // "dom": '<"top"f><"right"rt><"bottom"lp><"clear">',
            // dom: 'Bfrt<"bottom"l><"center"i><"right"p><"clear">',
            ordering: true,
            processing: true,
            serverSide: true,
            // // responsive: true,
            order: [[0, "desc"]],
            ajax: {
                url:"{{ route('admin.orders.paginate') }}" + '/' + status,
                data: function(data) {
                    console.log(data);
                    
                    data.search_by_id = $('#datatableSearch_').val(),
                    data.filter = $('select[name=filter]').val(),
                    data.from = $('input[name=from]').val(),
                    data.to = $('input[name=to]').val(),
                    data.status = status
                }
            },
            // columns: [
            //     {'name': 'orders.id'},
            //     {'name': 'orders.id'},
            //     {'name': 'created_at', visible:false},
            //     {'name': 'customer_name', 'orderable': false},
            //     {'name': 'seller_name', 'orderable': false},
            //     {'name': 'order_amount'},
            //     {'name': 'order_status'},
            //     {'name': 'payment_status'},
            //     {'name': 'payment_method', visible:false},
            //     // {'name': 'delivery_date', visible:false},
            //     {'name': 'action', 'orderable': false}
            // ]
        });

        $(document).on('keyup', '#datatableSearch_', function() {
            orderTable.draw()
        })

        $(document).on('submit','#form-data', function(e){
            e.preventDefault()
            orderTable.draw()
        })

        $(document).on('click','.order-stats', function(e){
            e.preventDefault()
            
            status = $(this).data('status')

            if($('.order-stats').hasClass('active')) {
                $('.order-stats').removeClass('active')
            }
            $(this).addClass('active')
            orderTable.draw()
        })

        $('div.dataTables_filter input').attr('placeholder', 'Search By Order ID, Customer, Vendor');

    </script>

    <script>
        $(document).on('click', '#exportCsvBtn', function(e) {
            e.preventDefault();

            // Get current filter values from form
            let from = $('#from_date').val();
            let to = $('#to_date').val();
            let filter = $('select[name=filter]').val();
            let search = $('#datatableSearch_').val();
            let deliveryManId = $('input[name=delivery_man_id]').val();
            let status = "{{ $status }}"; // Comes from backend

            // Build export URL dynamically
            let exportUrl = `{{ route('admin.orders.order-bulk-export', ['status' => 'STATUS']) }}`;
            exportUrl = exportUrl.replace('STATUS', status);

            let params = new URLSearchParams({
                from: from || '',
                to: to || '',
                filter: filter || '',
                search: search || '',
                delivery_man_id: deliveryManId || '',
                type: 'csv'
            });

            window.location.href = `${exportUrl}?${params.toString()}`;
        });
    </script>

@endpush
