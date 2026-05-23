@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Financial_Report'))

@push('css_or_js')
    <style>
        .financial-report-table th,
        .financial-report-table td {
            white-space: nowrap;
            font-size: 0.8125rem;
            vertical-align: middle;
        }

        .financial-report-table thead th {
            text-align: center;
        }

        .financial-report-table .group-header {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        .premium-pagination-wrap {
            border-top: 1px solid #e8ebef;
            margin-top: 22px;
            padding: 12px 18px 16px;
        }

        .premium-pagination-shell {
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }

        .premium-pagination-inline {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            overflow-x: auto;
        }

        .premium-pagination-nav {
            float: none;
            margin: 0;
            flex: 0 0 auto;
        }

        .premium-pagination-shell .pagination {
            margin: 0;
        }
    </style>
@endpush

@section('content')

@php
    $fmt = function ($value) {
        return rtrim(rtrim(number_format((float) $value, 2, '.', ''), '0'), '.');
    };
@endphp

<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-currency-inr"></i>
            </span> {{\App\CPU\translate('Financial_Report')}}
        </h3>
    </div>

    <div class="row">
        <div class="col-lg-12">

            <div class="card mb-2">
                <div class="card-body">
                    <form action="" id="form-data" method="GET">
                        <h4 class="mb-3">{{ \App\CPU\translate('Filter_Data')}}</h4>
                        <div class="row gy-3 gx-2 align-items-center text-left">
                            <div class="col-sm-6 col-md-3">
                                <select class="form-select form-control __form-control" name="date_type" id="date_type">
                                    <option value="this_year" {{ $date_type == 'this_year'? 'selected' : '' }}>
                                        {{\App\CPU\translate('This_Year')}}
                                    </option>
                                    <option value="this_month" {{ $date_type == 'this_month'? 'selected' : '' }}>
                                        {{\App\CPU\translate('This_Month')}}
                                    </option>
                                    <option value="this_week" {{ $date_type == 'this_week'? 'selected' : '' }}>
                                        {{\App\CPU\translate('This_Week')}}
                                    </option>
                                    <option value="custom_date" {{ $date_type == 'custom_date'? 'selected' : '' }}>
                                        {{\App\CPU\translate('Custom_Date')}}
                                    </option>
                                </select>
                            </div>
                            <div class="col-sm-6 col-md-3" id="from_div">
                                <div class="form-floating">
                                    <input type="date" name="from" value="{{ $from instanceof \Carbon\Carbon ? $from->format('Y-m-d') : $from }}" id="from_date" class="form-control">
                                    <label>{{ \App\CPU\translate('Start Date')}}</label>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3" id="to_div">
                                <div class="form-floating">
                                    <input type="date" value="{{ $to instanceof \Carbon\Carbon ? $to->format('Y-m-d') : $to }}" name="to" id="to_date" class="form-control">
                                    <label>{{ \App\CPU\translate('End Date')}}</label>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-2">
                                <button type="submit" class="btn btn-primary px-4 w-100">
                                    {{ \App\CPU\translate('Filter')}}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="w-100 d-flex flex-wrap gap-3 align-items-center">
                        <h4 class="mb-0 mr-auto">{{\App\CPU\translate('Financial_Report')}}</h4>
                        <a class="btn btn-outline-primary ms-auto" href="{{ route('admin.financial.reports.export', ['date_type' => $date_type, 'from' => ($from instanceof \Carbon\Carbon ? $from->format('Y-m-d') : $from), 'to' => ($to instanceof \Carbon\Carbon ? $to->format('Y-m-d') : $to)]) }}">
                            {{ \App\CPU\translate('Export CSV') }}
                        </a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table financial-report-table">
                        <thead>
                            <tr class="group-header">
                                <th rowspan="2">Brand</th>
                                <th rowspan="2">Campaign</th>
                                <th rowspan="2">Start Date</th>
                                <th rowspan="2">End Date</th>
                                <th rowspan="2">Total Amount with GST</th>
                                <th rowspan="2">Total Amount without GST</th>
                                <th rowspan="2">Per Post Cost</th>
                                <th rowspan="2">Total Post Required</th>
                                <th rowspan="2">Discount</th>
                                <th colspan="3">Post Completed</th>
                                <th colspan="3">Already Spent</th>
                                <th colspan="3">To Users</th>
                                <th colspan="3">To Sales</th>
                                <th colspan="3">For Referral</th>
                                <th colspan="3">Admin</th>
                            </tr>
                            <tr class="group-header">
                                <th>Total</th><th>Verified</th><th>Not Verified</th>
                                <th>Total</th><th>Verified</th><th>Not Verified</th>
                                <th>Total</th><th>Verified</th><th>Not Verified</th>
                                <th>Total</th><th>Verified</th><th>Not Verified</th>
                                <th>Total</th><th>Verified</th><th>Not Verified</th>
                                <th>Total</th><th>Verified</th><th>Not Verified</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(($rows->count() > 0) && isset($totals))
                                <tr class="fw-bold">
                                    <td>Total</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>{{ $fmt($totals['amount_with_gst'] ?? 0) }}</td>
                                    <td>{{ $fmt($totals['amount_without_gst'] ?? 0) }}</td>
                                    <td>{{ $fmt($totals['per_post_cost'] ?? 0) }}</td>
                                    <td>{{ $fmt($totals['total_post_required'] ?? 0) }}</td>
                                    <td>{{ $fmt($totals['discount'] ?? 0) }}</td>
                                    <td>{{ $fmt($totals['posts_completed_total'] ?? 0) }}</td>
                                    <td>{{ $fmt($totals['posts_verified'] ?? 0) }}</td>
                                    <td>{{ $fmt($totals['posts_not_verified'] ?? 0) }}</td>
                                    <td>{{ $fmt($totals['already_spent_total'] ?? 0) }}</td>
                                    <td>{{ $fmt($totals['already_spent_verified'] ?? 0) }}</td>
                                    <td>{{ $fmt($totals['already_spent_not_verified'] ?? 0) }}</td>
                                    <td>{{ $fmt($totals['users_total'] ?? 0) }}</td>
                                    <td>{{ $fmt($totals['users_verified'] ?? 0) }}</td>
                                    <td>{{ $fmt($totals['users_not_verified'] ?? 0) }}</td>
                                    <td>{{ $fmt($totals['sales_total'] ?? 0) }}</td>
                                    <td>{{ $fmt($totals['sales_verified'] ?? 0) }}</td>
                                    <td>{{ $fmt($totals['sales_not_verified'] ?? 0) }}</td>
                                    <td>{{ $fmt($totals['referral_total'] ?? 0) }}</td>
                                    <td>{{ $fmt($totals['referral_verified'] ?? 0) }}</td>
                                    <td>{{ $fmt($totals['referral_not_verified'] ?? 0) }}</td>
                                    <td>{{ $fmt($totals['admin_total'] ?? 0) }}</td>
                                    <td>{{ $fmt($totals['admin_verified'] ?? 0) }}</td>
                                    <td>{{ $fmt($totals['admin_not_verified'] ?? 0) }}</td>
                                </tr>
                            @endif

                            @forelse($rows as $row)
                                <tr>
                                    <td>{{ $row['brand'] }}</td>
                                    <td>{{ $row['campaign'] }}</td>
                                    <td>{{ $row['start_date'] }}</td>
                                    <td>{{ $row['end_date'] }}</td>
                                    <td>{{ $fmt($row['amount_with_gst'] ?? 0) }}</td>
                                    <td>{{ $fmt($row['amount_without_gst'] ?? 0) }}</td>
                                    <td>{{ $fmt($row['per_post_cost'] ?? 0) }}</td>
                                    <td>{{ $fmt($row['total_post_required'] ?? 0) }}</td>
                                    <td>{{ $fmt($row['discount'] ?? 0) }}</td>
                                    <td>{{ $fmt($row['posts_completed_total'] ?? 0) }}</td>
                                    <td>{{ $fmt($row['posts_verified'] ?? 0) }}</td>
                                    <td>{{ $fmt($row['posts_not_verified'] ?? 0) }}</td>
                                    <td>{{ $fmt($row['already_spent_total'] ?? 0) }}</td>
                                    <td>{{ $fmt($row['already_spent_verified'] ?? 0) }}</td>
                                    <td>{{ $fmt($row['already_spent_not_verified'] ?? 0) }}</td>
                                    <td>{{ $fmt($row['users_total'] ?? 0) }}</td>
                                    <td>{{ $fmt($row['users_verified'] ?? 0) }}</td>
                                    <td>{{ $fmt($row['users_not_verified'] ?? 0) }}</td>
                                    <td>{{ $fmt($row['sales_total'] ?? 0) }}</td>
                                    <td>{{ $fmt($row['sales_verified'] ?? 0) }}</td>
                                    <td>{{ $fmt($row['sales_not_verified'] ?? 0) }}</td>
                                    <td>{{ $fmt($row['referral_total'] ?? 0) }}</td>
                                    <td>{{ $fmt($row['referral_verified'] ?? 0) }}</td>
                                    <td>{{ $fmt($row['referral_not_verified'] ?? 0) }}</td>
                                    <td>{{ $fmt($row['admin_total'] ?? 0) }}</td>
                                    <td>{{ $fmt($row['admin_verified'] ?? 0) }}</td>
                                    <td>{{ $fmt($row['admin_not_verified'] ?? 0) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="27" class="text-center">No Report Available</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($rows->hasPages())
                <div class="premium-pagination-wrap">
                    <div class="premium-pagination-shell">
                        <div class="premium-pagination-inline">
                            {!! $rows->onEachSide(1)->links('vendor.pagination.premium') !!}
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>

@endsection

@push('script_2')
<script>
    $('#from_date,#to_date').change(function() {
        let fr = $('#from_date').val();
        let to = $('#to_date').val();

        if (fr != '') {
            $('#to_date').attr('required', 'required');
        }

        if (to != '') {
            $('#from_date').attr('required', 'required');
        }

        if (fr != '' && to != '') {
            if (fr > to) {
                $('#from_date').val('');
                $('#to_date').val('');
                toastr.error('Invalid date range!', Error, {
                    CloseButton: true,
                    ProgressBar: true
                });
            }
        }
    });

    $("#date_type").change(function() {
        let val = $(this).val();
        $('#from_div').toggle(val === 'custom_date');
        $('#to_div').toggle(val === 'custom_date');

        if (val === 'custom_date') {
            $('#from_date').attr('required', 'required');
            $('#to_date').attr('required', 'required');
        } else {
            $('#from_date').val(null).removeAttr('required');
            $('#to_date').val(null).removeAttr('required');
        }
    }).change();
</script>
@endpush
