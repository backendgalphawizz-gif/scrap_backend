@extends('layouts.back-end.app')

@section('title', 'TDS Report')

@push('css_or_js')
    <style>
        .tds-report-table th,
        .tds-report-table td {
            white-space: nowrap;
            font-size: 0.8125rem;
            vertical-align: middle;
        }

        .tds-report-table thead th {
            background-color: #f8f9fa;
            font-weight: 600;
            text-align: center;
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

        .premium-pagination-shell .pagination {
            margin: 0;
        }
    </style>
@endpush

@section('content')

@php
    $fmt = function ($value) {
        return '₹' . number_format((float) $value, 2);
    };
    $fmtNum = function ($value) {
        return number_format((float) $value, 2);
    };
    $fromStr = $from instanceof \Carbon\Carbon ? $from->format('Y-m-d') : $from;
    $toStr   = $to   instanceof \Carbon\Carbon ? $to->format('Y-m-d')   : $to;
@endphp

<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-bank-outline"></i>
            </span> TDS Report
        </h3>
    </div>

    <div class="row">
        <div class="col-lg-12">

            {{-- Filter --}}
            <div class="card mb-2">
                <div class="card-body">
                    <form action="" id="form-data" method="GET">
                        <h4 class="mb-3">{{ \App\CPU\translate('Filter_Data') }}</h4>
                        <div class="row gy-3 gx-2 align-items-center">
                            <div class="col-sm-6 col-md-2">
                                <select class="form-select form-control __form-control" name="date_type" id="date_type">
                                    <option value="this_year"   {{ $date_type == 'this_year'   ? 'selected' : '' }}>{{ \App\CPU\translate('This_Year') }}</option>
                                    <option value="this_month"  {{ $date_type == 'this_month'  ? 'selected' : '' }}>{{ \App\CPU\translate('This_Month') }}</option>
                                    <option value="this_week"   {{ $date_type == 'this_week'   ? 'selected' : '' }}>{{ \App\CPU\translate('This_Week') }}</option>
                                    <option value="custom_date" {{ $date_type == 'custom_date' ? 'selected' : '' }}>{{ \App\CPU\translate('Custom_Date') }}</option>
                                </select>
                            </div>
                            <div class="col-sm-6 col-md-2" id="from_div">
                                <div class="form-floating">
                                    <input type="date" name="from" value="{{ $fromStr }}" id="from_date" class="form-control">
                                    <label>{{ \App\CPU\translate('Start Date') }}</label>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-2" id="to_div">
                                <div class="form-floating">
                                    <input type="date" name="to" value="{{ $toStr }}" id="to_date" class="form-control">
                                    <label>{{ \App\CPU\translate('End Date') }}</label>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-2">
                                <select class="form-select form-control __form-control" name="status" id="status_filter">
                                    <option value="all"       {{ $status === 'all'       ? 'selected' : '' }}>All Statuses</option>
                                    <option value="pending"   {{ $status === 'pending'   ? 'selected' : '' }}>Pending</option>
                                    <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="rejected"  {{ $status === 'rejected'  ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>
                            <div class="col-sm-6 col-md-2">
                                <button type="submit" class="btn btn-primary px-4 w-100">{{ \App\CPU\translate('Filter') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Summary Cards --}}
            <div class="row mb-2">
                <div class="col-md-6 col-lg-3 col-sm-12 stretch-card grid-margin">
                    <div class="card bg-gradient-info card-img-holder text-white w-100">
                        <div class="card-body">
                            <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute">
                            <h4 class="font-weight-normal mb-3 small cardText">
                                Total Withdrawals
                                <i class="mdi mdi-bank-transfer-out mdi-24px float-end"></i>
                            </h4>
                            <h2 class="small">{{ number_format($totals['count'] ?? 0) }}</h2>
                            <p class="mb-0 small">{{ $fmtNum($totals['total_coins'] ?? 0) }} coins</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 col-sm-12 stretch-card grid-margin">
                    <div class="card bg-gradient-success card-img-holder text-white w-100">
                        <div class="card-body">
                            <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute">
                            <h4 class="font-weight-normal mb-3 small cardText">
                                Total Gross Amount
                                <i class="mdi mdi-currency-inr mdi-24px float-end"></i>
                            </h4>
                            <h2 class="small">{{ $fmt($totals['total_gross'] ?? 0) }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 col-sm-12 stretch-card grid-margin">
                    <div class="card bg-gradient-danger card-img-holder text-white w-100">
                        <div class="card-body">
                            <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute">
                            <h4 class="font-weight-normal mb-3 small cardText">
                                Total TDS Deducted
                                <i class="mdi mdi-percent mdi-24px float-end"></i>
                            </h4>
                            <h2 class="small">{{ $fmt($totals['total_tds'] ?? 0) }}</h2>
                            <p class="mb-0 small">Section 194C</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 col-sm-12 stretch-card grid-margin">
                    <div class="card bg-gradient-warning card-img-holder text-white w-100">
                        <div class="card-body">
                            <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute">
                            <h4 class="font-weight-normal mb-3 small cardText">
                                Total Net Payout
                                <i class="mdi mdi-cash-check mdi-24px float-end"></i>
                            </h4>
                            <h2 class="small">{{ $fmt($totals['total_net'] ?? 0) }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Table Card --}}
            <div class="card">
                <div class="card-header">
                    <div class="w-100 d-flex flex-wrap gap-3 align-items-center">
                        <h4 class="mb-0 mr-auto">TDS on User Withdrawals</h4>
                        <a class="btn btn-outline-primary ms-auto"
                           href="{{ route('admin.tds.reports.export', ['date_type' => $date_type, 'from' => $fromStr, 'to' => $toStr, 'status' => $status]) }}">
                            Export CSV
                        </a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table tds-report-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>PAN No.</th>
                                <th>PAN Status at Withdrawal</th>
                                <th>Coins</th>
                                <th>Gross (₹)</th>
                                <th>TDS Rate</th>
                                <th>TDS (₹)</th>
                                <th>Net Payout (₹)</th>
                                <th>Section</th>
                                <th>UPI / Bank</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($rows->count() > 0)
                                <tr class="fw-bold table-light">
                                    <td colspan="5">Total (this page)</td>
                                    <td>{{ $fmtNum($rows->sum('amount')) }}</td>
                                    <td></td>
                                    <td>{{ $fmtNum($rows->sum('tds')) }}</td>
                                    <td>{{ $fmtNum($rows->sum('net_amount')) }}</td>
                                    <td colspan="4"></td>
                                </tr>
                            @endif

                            @forelse ($rows as $i => $tx)
                                @php $user = $tx->wallet->user ?? null; @endphp
                                <tr>
                                    <td>{{ ($rows->currentPage() - 1) * $rows->perPage() + $i + 1 }}</td>
                                    <td>{{ $user->name ?? '-' }}</td>
                                    <td>
                                        @if ($user && $user->pan_number)
                                            <code>{{ $user->pan_number }}</code>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $panStatus = $tx->pan_status_at_withdrawal ?? '-';
                                            $panBadge  = match ($panStatus) {
                                                'Verified'   => 'success',
                                                'Submitted', 'Under Verification' => 'warning',
                                                'Rejected'   => 'danger',
                                                default      => 'secondary',
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $panBadge }}">{{ $panStatus }}</span>
                                    </td>
                                    <td>{{ $fmtNum($tx->coin) }}</td>
                                    <td>{{ $fmtNum($tx->amount) }}</td>
                                    <td>
                                        @if ($tx->tds_rate)
                                            <span class="badge bg-secondary">{{ $fmtNum($tx->tds_rate) }}%</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="text-danger fw-semibold">{{ $fmtNum($tx->tds) }}</td>
                                    <td class="text-success fw-semibold">{{ $fmtNum($tx->net_amount) }}</td>
                                    <td><span class="badge bg-info text-dark">{{ $tx->tds_section ?? '194C' }}</span></td>
                                    <td>{{ $tx->value ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-{{ match($tx->status) { 'completed' => 'success', 'pending' => 'warning', 'rejected' => 'danger', default => 'secondary' } }}">
                                            {{ ucfirst($tx->status) }}
                                        </span>
                                    </td>
                                    <td>{{ \App\CPU\Helpers::formatAdminDateTime($tx->created_at) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="13" class="text-center py-4">No withdrawal records found for the selected period.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if ($rows->count() > 0)
                            <tfoot>
                                <tr class="fw-bold table-secondary">
                                    <td colspan="5">Overall Total (all pages)</td>
                                    <td>{{ $fmtNum($totals['total_gross'] ?? 0) }}</td>
                                    <td></td>
                                    <td>{{ $fmtNum($totals['total_tds'] ?? 0) }}</td>
                                    <td>{{ $fmtNum($totals['total_net'] ?? 0) }}</td>
                                    <td colspan="4"></td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>

                @if ($rows->hasPages())
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
</div>

@endsection

@push('script_2')
<script>
    $('#from_date,#to_date').change(function () {
        let fr = $('#from_date').val();
        let to = $('#to_date').val();

        if (fr !== '') $('#to_date').attr('required', 'required');
        if (to !== '') $('#from_date').attr('required', 'required');

        if (fr !== '' && to !== '' && fr > to) {
            $('#from_date').val('');
            $('#to_date').val('');
            toastr.error('Invalid date range!', 'Error', { CloseButton: true, ProgressBar: true });
        }
    });

    $('#date_type').change(function () {
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
