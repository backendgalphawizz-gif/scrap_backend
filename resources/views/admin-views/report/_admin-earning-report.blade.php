@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Admin_Earning_Report'))

@push('css_or_js')
<style>
    .earning-table th,
    .earning-table td {
        white-space: nowrap;
        font-size: 0.8125rem;
        vertical-align: middle;
    }

    .earning-table thead th {
        background-color: #f8f9fa;
        font-weight: 600;
    }

    .summary-card .card-body {
        padding: 1.25rem 1.5rem;
    }

    .summary-value {
        font-size: 1.5rem;
        font-weight: 700;
        line-height: 1.2;
    }

    .summary-label {
        font-size: 0.78rem;
        color: #6c757d;
        margin-top: 4px;
    }

    .badge-status-active      { background-color: #28a745; color: #fff; }
    .badge-status-completed   { background-color: #17a2b8; color: #fff; }
    .badge-status-pending     { background-color: #ffc107; color: #212529; }
    .badge-status-stopped     { background-color: #fd7e14; color: #fff; }
    .badge-status-rejected    { background-color: #dc3545; color: #fff; }
    .badge-status-inactive    { background-color: #6c757d; color: #fff; }

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
        return '₹ ' . number_format((float) $value, 2);
    };

    $statusBadge = function ($status) {
        $map = [
            'active'    => 'badge-status-active',
            'completed' => 'badge-status-completed',
            'pending'   => 'badge-status-pending',
            'stopped'   => 'badge-status-stopped',
            'rejected'  => 'badge-status-rejected',
            'inactive'  => 'badge-status-inactive',
        ];
        $cls = $map[$status] ?? 'badge-status-inactive';
        return '<span class="badge ' . $cls . '">' . ucwords($status) . '</span>';
    };
@endphp

<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-currency-inr"></i>
            </span>
            {{ \App\CPU\translate('Admin_Earning_Report') }}
        </h3>
        <nav aria-label="breadcrumb"></nav>
    </div>

    <div class="row">
        <div class="col-lg-12">

            {{-- ── Filter ── --}}
            <div class="card mb-2">
                <div class="card-body">
                    <form action="" id="form-data" method="GET">
                        <h4 class="mb-3">{{ \App\CPU\translate('Filter_Data') }}</h4>
                        <div class="row gy-3 gx-2 align-items-center text-left">

                            <div class="col-sm-6 col-md-3">
                                <select class="form-select form-control __form-control" name="date_type" id="date_type">
                                    <option value="this_year"  {{ $date_type == 'this_year'   ? 'selected' : '' }}>{{ \App\CPU\translate('This_Year')  }}</option>
                                    <option value="this_month" {{ $date_type == 'this_month'  ? 'selected' : '' }}>{{ \App\CPU\translate('This_Month') }}</option>
                                    <option value="this_week"  {{ $date_type == 'this_week'   ? 'selected' : '' }}>{{ \App\CPU\translate('This_Week')  }}</option>
                                    <option value="today"      {{ $date_type == 'today'        ? 'selected' : '' }}>{{ \App\CPU\translate('Today')      }}</option>
                                    <option value="custom_date"{{ $date_type == 'custom_date' ? 'selected' : '' }}>{{ \App\CPU\translate('Custom_Date') }}</option>
                                </select>
                            </div>

                            <div class="col-sm-6 col-md-3" id="from_div">
                                <div class="form-floating">
                                    <input type="date" name="from"
                                           value="{{ $from instanceof \Carbon\Carbon ? $from->format('Y-m-d') : $from }}"
                                           id="from_date" class="form-control">
                                    <label>{{ \App\CPU\translate('Start Date') }}</label>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-3" id="to_div">
                                <div class="form-floating">
                                    <input type="date" name="to"
                                           value="{{ $to instanceof \Carbon\Carbon ? $to->format('Y-m-d') : $to }}"
                                           id="to_date" class="form-control">
                                    <label>{{ \App\CPU\translate('End Date') }}</label>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-3">
                                <button type="submit" class="btn btn-primary px-4 w-100">
                                    {{ \App\CPU\translate('Filter') }}
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

            {{-- ── Summary Cards ── --}}
            <div class="row mb-3 g-3">

                <div class="col-6 col-md-3">
                    <div class="card summary-card h-100">
                        <div class="card-body">
                            <div class="summary-value text-primary">{{ $fmt($summary['total_actual_earnings']) }}</div>
                            <div class="summary-label">Actual Admin Earnings<br><small class="text-muted">(from completed posts)</small></div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="card summary-card h-100">
                        <div class="card-body">
                            <div class="summary-value text-secondary">{{ $fmt($summary['total_projected_earnings']) }}</div>
                            <div class="summary-label">Projected Admin Earnings<br><small class="text-muted">(if all slots filled)</small></div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="card summary-card h-100">
                        <div class="card-body">
                            <div class="summary-value">{{ $fmt($summary['total_budget']) }}</div>
                            <div class="summary-label">Total Campaign Budget</div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="card summary-card h-100">
                        <div class="card-body">
                            <div class="summary-value">
                                {{ number_format($summary['total_completed_posts']) }}
                                <small class="text-muted fs-6">/ {{ number_format($summary['total_posts_required']) }}</small>
                            </div>
                            <div class="summary-label">Completed Posts / Required</div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ── Table ── --}}
            <div class="card">
                <div class="card-header">
                    <div class="w-100 d-flex flex-wrap gap-3 align-items-center">
                        <h4 class="mb-0 mr-auto">{{ \App\CPU\translate('Admin_Earning_Report') }}</h4>
                        <a class="btn btn-outline-primary ms-auto"
                           href="{{ route('admin.earning.reports.export', [
                               'date_type' => $date_type,
                               'from' => ($from instanceof \Carbon\Carbon ? $from->format('Y-m-d') : $from),
                               'to'   => ($to   instanceof \Carbon\Carbon ? $to->format('Y-m-d')   : $to),
                           ]) }}">
                            {{ \App\CPU\translate('Export CSV') }}
                        </a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table earning-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Campaign</th>
                                <th>Brand</th>
                                <th>Status</th>
                                <th>Campaign Budget</th>
                                <th>Admin %</th>
                                <th>Per-Post Cost</th>
                                <th>Posts Required</th>
                                <th>Completed Posts</th>
                                <th>Utilisation</th>
                                <th>Projected Earnings</th>
                                <th class="text-success">Actual Earnings</th>
                            </tr>
                        </thead>
                        <tbody>

                            {{-- Totals row --}}
                            @if($rows->count() > 0)
                            <tr class="fw-bold table-light">
                                <td></td>
                                <td>Total (this page)</td>
                                <td></td>
                                <td></td>
                                <td>{{ $fmt($summary['total_budget']) }}</td>
                                <td></td>
                                <td></td>
                                <td>{{ number_format($summary['total_posts_required']) }}</td>
                                <td>{{ number_format($summary['total_completed_posts']) }}</td>
                                <td></td>
                                <td>{{ $fmt($summary['total_projected_earnings']) }}</td>
                                <td class="text-success">{{ $fmt($summary['total_actual_earnings']) }}</td>
                            </tr>
                            @endif

                            @forelse($rows as $row)
                            <tr>
                                <td>{{ $row['campaign_id'] }}</td>
                                <td>{{ $row['campaign'] }}</td>
                                <td>{{ $row['brand'] }}</td>
                                <td>{!! $statusBadge($row['status']) !!}</td>
                                <td>{{ $fmt($row['campaign_budget']) }}</td>
                                <td>{{ $row['admin_percentage'] }}%</td>
                                <td>{{ $fmt($row['per_post_cost']) }}</td>
                                <td>{{ $row['posts_required'] }}</td>
                                <td>{{ $row['completed_posts'] }}</td>
                                <td>
                                    <div class="progress" style="height:6px; min-width:60px;">
                                        <div class="progress-bar bg-primary"
                                             style="width: {{ min(100, $row['utilisation_pct']) }}%">
                                        </div>
                                    </div>
                                    <small>{{ $row['utilisation_pct'] }}%</small>
                                </td>
                                <td class="text-secondary">{{ $fmt($row['projected_earnings']) }}</td>
                                <td class="text-success fw-semibold">{{ $fmt($row['actual_earnings']) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="12" class="text-center py-4">No data available for the selected period.</td>
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
    // Show/hide custom date pickers
    (function () {
        var $dateType = $('#date_type');
        var $fromDiv  = $('#from_div');
        var $toDiv    = $('#to_div');

        function toggleDates() {
            var show = $dateType.val() === 'custom_date';
            $fromDiv.toggle(show);
            $toDiv.toggle(show);
        }

        toggleDates();
        $dateType.on('change', toggleDates);

        // Auto-submit on date change
        $('#from_date, #to_date').on('change', function () {
            if ($dateType.val() === 'custom_date') {
                $('#form-data').submit();
            }
        });
    })();
</script>
@endpush
