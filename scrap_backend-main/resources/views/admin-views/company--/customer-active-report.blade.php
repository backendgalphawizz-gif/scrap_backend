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
                    @if($type == 1)
                        {{ \App\CPU\translate('Customer') }}
                    @elseif($type == 2)
                        {{ \App\CPU\translate('Vendor') }}
                    @elseif($type == 3)
                        {{ \App\CPU\translate('Driver') }}
                    @endif
                        {{ \App\CPU\translate(' Active Report') }}
                </h2>
            </div>
            <!-- End Page Title -->

            @php
                $filter = "";
                $to = "";
                $from = "";
                $status = "all";
            @endphp

            <!-- Order States -->
            <div class="card">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ url()->current() }}" id="form-data" method="GET">
                            <div class="row gy-3 gx-2">
                                <div class="col-12 pb-0">
                                    <h4>{{\App\CPU\translate('select')}} {{\App\CPU\translate('date')}} {{\App\CPU\translate('range')}}</h4>
                                </div>
                                <input type="hidden" name="type" value="1">

                                <div class="col-sm-6 col-md-3">
                                    <div class="form-floating">
                                        <input type="date" name="from" value="{{$from}}" id="from_date" class="form-control" max="{{ date('Y-m-d') }}">
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
                                    <button type="button" class="btn btn--primary btn-block" onclick="window.location.href= '{{ route('admin.user-active.user.report','user') }}'">
                                        {{ \App\CPU\translate('reset') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card-body">

                    <!-- Table -->
                    <div class="table-responsive    ">
                        <table class="user-report-list-table table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100" style="text-align: {{ Session::get('direction') === "rtl" ? 'right' : 'left' }}">
                            <thead class="thead-light thead-50 text-capitalize">
                                <tr>
                                    <th class="">{{ \App\CPU\translate('SL') }}</th>
                                    <th>{{ \App\CPU\translate('Username') }}</th>
                                    <th>{{ \App\CPU\translate('Status') }}</th>
                                    <th>{{ \App\CPU\translate('Date & Time') }}</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>


                </div>
            </div>
            <!-- End Order States -->

        </div>
        <!-- End Page Header -->
    </div>
@endsection

@push('script_2')
    <script>

        var status = "{{ $status }}"
        var type = "{{ $type }}"

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

        $(".js-example-responsive").select2({
            // dir: "rtl",
            width: 'resolve'
        });

        var orderTable = $(".user-report-list-table").DataTable({
            dom: 'frltip',
            // dom: '<"bottom"lf><<t>ip>',
            // "dom": '<"top"f><"right"rt><"bottom"lp><"clear">',
            // dom: 'Bfrt<"bottom"l><"center"i><"right"p><"clear">',
            ordering: true,
            processing: true,
            serverSide: true,
            // // responsive: true,
            order: [[0, "desc"]],
            ajax: {
                url:"{{ route('admin.user-active.user.paginate') }}/" + type,
                data: function(data) {
                    data.search_by_id = $('#datatableSearch_').val(),
                    data.customer_id = $('select[name=filter]').val(),
                    data.from = $('input[name=from]').val(),
                    data.to = $('input[name=to]').val(),
                    data.type = type
                }
            },
            columns: [
                {'name': 'id'},
                {'name': 'customer_name', 'orderable': false},
                {'name': 'description', 'orderable': false},
                {'name': 'created_at'}
            ]
        });

        $(document).on('keyup', '#datatableSearch_', function() {
            orderTable.draw()
        })

        $(document).on('submit','#form-data', function(e) {
            e.preventDefault()
            orderTable.draw()
        })

        $(document).on('click','.order-stats', function(e) {
            e.preventDefault()

            // status = $(this).data('status')

            // if($('.order-stats').hasClass('active')) {
            //     $('.order-stats').removeClass('active')
            // }
            // $(this).addClass('active')
            orderTable.draw()
        })

    </script>
@endpush
