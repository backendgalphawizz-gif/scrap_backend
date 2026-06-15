@extends('layouts.back-end.app')

@section('title', 'GST Report')

@push('css_or_js')
    <style>
        .gst-report-table th,
        .gst-report-table td {
            white-space: nowrap;
            font-size: 0.8125rem;
            vertical-align: middle;
        }

        .gst-report-table thead th {
            text-align: center;
        }

        .gst-report-table .group-header {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        .badge-gst    { background-color: #17a2b8; color: #fff; }
        .badge-normal { background-color: #6c757d; color: #fff; }

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
                <i class="mdi mdi-file-document-outline"></i>
            </span> GST Report
        </h3>
    </div>

    <div class="row">
        <div class="col-lg-12">

            {{-- Filter --}}
            <div class="card mb-2">
                <div class="card-body">
                    <form action="" id="form-data" method="GET">
                        <input type="hidden" name="tab" value="{{ $tab }}">
                        <h4 class="mb-3">{{ \App\CPU\translate('Filter_Data') }}</h4>
                        <div class="row gy-3 gx-2 align-items-center">
                            <div class="col-sm-6 col-md-3">
                                <select class="form-select form-control __form-control" name="date_type" id="date_type">
                                    <option value="this_year"   {{ $date_type == 'this_year'   ? 'selected' : '' }}>{{ \App\CPU\translate('This_Year') }}</option>
                                    <option value="this_month"  {{ $date_type == 'this_month'  ? 'selected' : '' }}>{{ \App\CPU\translate('This_Month') }}</option>
                                    <option value="this_week"   {{ $date_type == 'this_week'   ? 'selected' : '' }}>{{ \App\CPU\translate('This_Week') }}</option>
                                    <option value="custom_date" {{ $date_type == 'custom_date' ? 'selected' : '' }}>{{ \App\CPU\translate('Custom_Date') }}</option>
                                </select>
                            </div>
                            <div class="col-sm-6 col-md-3" id="from_div">
                                <div class="form-floating">
                                    <input type="date" name="from" value="{{ $fromStr }}" id="from_date" class="form-control">
                                    <label>{{ \App\CPU\translate('Start Date') }}</label>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3" id="to_div">
                                <div class="form-floating">
                                    <input type="date" name="to" value="{{ $toStr }}" id="to_date" class="form-control">
                                    <label>{{ \App\CPU\translate('End Date') }}</label>
                                </div>
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
                                Total Campaigns
                                <i class="mdi mdi-bullhorn-outline mdi-24px float-end"></i>
                            </h4>
                            <h2 class="small">{{ ($totals['gst_campaigns'] ?? 0) + ($totals['normal_campaigns'] ?? 0) }}</h2>
                            <p class="mb-0 small">GST: {{ $totals['gst_campaigns'] ?? 0 }} &nbsp;|&nbsp; Normal: {{ $totals['normal_campaigns'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 col-sm-12 stretch-card grid-margin">
                    <div class="card bg-gradient-success card-img-holder text-white w-100">
                        <div class="card-body">
                            <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute">
                            <h4 class="font-weight-normal mb-3 small cardText">
                                Total Taxable Amount
                                <i class="mdi mdi-currency-inr mdi-24px float-end"></i>
                            </h4>
                            <h2 class="small">{{ $fmt($totals['total_taxable'] ?? 0) }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 col-sm-12 stretch-card grid-margin">
                    <div class="card bg-gradient-warning card-img-holder text-white w-100">
                        <div class="card-body">
                            <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute">
                            <h4 class="font-weight-normal mb-3 small cardText">
                                Total GST Collected
                                <i class="mdi mdi-percent mdi-24px float-end"></i>
                            </h4>
                            <h2 class="small">{{ $fmt($totals['total_gst'] ?? 0) }}</h2>
                            <p class="mb-0 small">Total with GST: {{ $fmt($totals['total_with_gst'] ?? 0) }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 col-sm-12 stretch-card grid-margin">
                    <div class="card bg-gradient-danger card-img-holder text-white w-100">
                        <div class="card-body">
                            <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute">
                            <h4 class="font-weight-normal mb-3 small cardText">
                                Credit Notes Issued
                                <i class="mdi mdi-file-undo mdi-24px float-end"></i>
                            </h4>
                            <h2 class="small">{{ $totals['credit_notes_count'] ?? 0 }}</h2>
                            <p class="mb-0 small">GST Reversed: {{ $fmt($totals['credit_notes_gst_rev'] ?? 0) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabs --}}
            <div class="card">
                <div class="card-header pb-0">
                    <ul class="nav nav-tabs card-header-tabs" id="gstTabs">
                        <li class="nav-item">
                            <a class="nav-link {{ $tab === 'invoices' ? 'active' : '' }}"
                               href="{{ route('admin.gst.reports', array_merge(request()->except(['tab', 'page']), ['tab' => 'invoices', 'date_type' => $date_type, 'from' => $fromStr, 'to' => $toStr])) }}">
                                GST Invoices
                                <span class="badge bg-primary ms-1">{{ $rows->total() ?? 0 }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $tab === 'credit_notes' ? 'active' : '' }}"
                               href="{{ route('admin.gst.reports', array_merge(request()->except(['tab', 'cn_page']), ['tab' => 'credit_notes', 'date_type' => $date_type, 'from' => $fromStr, 'to' => $toStr])) }}">
                                Credit Notes
                                <span class="badge bg-secondary ms-1">{{ $creditNotes->total() ?? 0 }}</span>
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- Tab: Invoices --}}
                @if ($tab === 'invoices')
                    <div class="card-header border-top-0 pt-3">
                        <div class="w-100 d-flex flex-wrap gap-3 align-items-center">
                            <h5 class="mb-0 mr-auto">Campaign GST Invoices</h5>
                            <a class="btn btn-outline-primary ms-auto"
                               href="{{ route('admin.gst.reports.export', ['tab' => 'invoices', 'date_type' => $date_type, 'from' => $fromStr, 'to' => $toStr]) }}">
                                Export CSV
                            </a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table gst-report-table">
                            <thead>
                                <tr class="group-header">
                                    <th>Invoice No.</th>
                                    <th>Type</th>
                                    <th>Brand</th>
                                    <th>Brand GST No.</th>
                                    <th>Campaign</th>
                                    <th>Invoice Date</th>
                                    <th>Taxable (₹)</th>
                                    <th>Discount (₹)</th>
                                    <th>Net Taxable (₹)</th>
                                    <th>GST %</th>
                                    <th>GST Amt (₹)</th>
                                    <th>CGST (₹)</th>
                                    <th>SGST (₹)</th>
                                    <th>IGST (₹)</th>
                                    <th>Total (₹)</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rows as $row)
                                    <tr>
                                        <td><code>{{ $row['invoice_no'] }}</code></td>
                                        <td>
                                            @if ($row['invoice_type'] === 'GST')
                                                <span class="badge badge-gst">GST</span>
                                            @else
                                                <span class="badge badge-normal">Normal</span>
                                            @endif
                                        </td>
                                        <td>{{ $row['brand'] }}</td>
                                        <td>{{ $row['brand_gst'] }}</td>
                                        <td>{{ $row['campaign'] }}</td>
                                        <td>{{ $row['invoice_date'] }}</td>
                                        <td>{{ $fmtNum($row['taxable']) }}</td>
                                        <td>{{ $fmtNum($row['discount']) }}</td>
                                        <td>{{ $fmtNum($row['net_taxable']) }}</td>
                                        <td>{{ $row['gst_pct'] }}%</td>
                                        <td>{{ $fmtNum($row['gst_amount']) }}</td>
                                        <td>
                                            @if ($row['is_intra_state'])
                                                {{ $fmtNum($row['cgst']) }}<br><small class="text-muted">{{ $row['cgst_rate'] }}%</small>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($row['is_intra_state'])
                                                {{ $fmtNum($row['sgst']) }}<br><small class="text-muted">{{ $row['sgst_rate'] }}%</small>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if (!$row['is_intra_state'])
                                                {{ $fmtNum($row['igst']) }}<br><small class="text-muted">{{ $row['igst_rate'] }}%</small>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td><strong>{{ $fmtNum($row['total']) }}</strong></td>
                                        <td>
                                            <span class="badge bg-{{ match($row['status']) { 'active','live' => 'success', 'completed' => 'primary', 'pending' => 'warning', 'rejected','stopped' => 'danger', default => 'secondary' } }}">
                                                {{ ucfirst($row['status']) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="16" class="text-center py-4">No invoices found for the selected period.</td>
                                    </tr>
                                @endforelse
                            </tbody>
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
                @endif

                {{-- Tab: Credit Notes --}}
                @if ($tab === 'credit_notes')
                    <div class="card-header border-top-0 pt-3">
                        <div class="w-100 d-flex flex-wrap gap-3 align-items-center">
                            <h5 class="mb-0 mr-auto">GST Credit Notes</h5>
                            <a class="btn btn-outline-primary ms-auto"
                               href="{{ route('admin.gst.reports.export', ['tab' => 'credit_notes', 'date_type' => $date_type, 'from' => $fromStr, 'to' => $toStr]) }}">
                                Export CSV
                            </a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table gst-report-table">
                            <thead>
                                <tr class="group-header">
                                    <th>Credit Note No.</th>
                                    <th>Original Invoice No.</th>
                                    <th>Brand</th>
                                    <th>Brand GST No.</th>
                                    <th>Campaign</th>
                                    <th>Date</th>
                                    <th>Taxable Reversal (₹)</th>
                                    <th>GST Reversal (₹)</th>
                                    <th>CGST (₹)</th>
                                    <th>SGST (₹)</th>
                                    <th>Reason</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($creditNotes as $note)
                                    <tr>
                                        <td><code>{{ $note->credit_note_no }}</code></td>
                                        <td><code>{{ $note->original_invoice_no }}</code></td>
                                        <td>{{ $note->brand->username ?? '-' }}</td>
                                        <td>{{ $note->brand->gst_number ?? '-' }}</td>
                                        <td>
                                            {{ $note->campaign->unique_code ?? ('RXC_' . str_pad($note->campaign_id, 5, '0', STR_PAD_LEFT)) }}
                                        </td>
                                        <td>{{ $note->credit_note_date?->format('d/m/Y') ?? '-' }}</td>
                                        <td>{{ $fmtNum($note->taxable_reversal_amount) }}</td>
                                        <td>{{ $fmtNum($note->gst_reversal_amount) }}</td>
                                        <td>{{ $fmtNum($note->cgst_reversal) }}</td>
                                        <td>{{ $fmtNum($note->sgst_reversal) }}</td>
                                        <td>{{ $note->reason ?? '-' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $note->status === 'issued' ? 'success' : 'secondary' }}">
                                                {{ ucfirst($note->status ?? 'issued') }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="text-center py-4">No credit notes found for the selected period.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($creditNotes->hasPages())
                        <div class="premium-pagination-wrap">
                            <div class="premium-pagination-shell">
                                <div class="premium-pagination-inline">
                                    {!! $creditNotes->onEachSide(1)->links('vendor.pagination.premium') !!}
                                </div>
                            </div>
                        </div>
                    @endif
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
