@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Activity Logs'))

@push('css_or_js')
<style>
    .activity-filter-scroll {
        overflow-x: auto;
        overflow-y: hidden;
        padding-bottom: 4px;
    }

    .activity-filter-form {
        flex-wrap: nowrap !important;
        min-width: max-content;
    }

    .activity-filter-form .form-control {
        min-width: 135px;
    }

    .activity-filter-form .search-control {
        min-width: 260px;
    }

    .activity-filter-form .date-control {
        min-width: 160px;
    }

    .activity-filter-form .btn {
        height: 38px;
        white-space: nowrap;
    }

    .premium-pagination-wrap {
        border-top: 1px solid #e8ebef;
        margin-top: 0;
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
        margin-left: 0;
        flex: 0 0 auto;
    }

    .premium-pagination-shell .pagination {
        margin: 0;
        gap: 8px;
        flex-wrap: nowrap;
        justify-content: flex-end;
    }

    .premium-pagination-shell .page-link {
        border: 1px solid #d0d6dd;
        border-radius: 4px;
        color: #6c757d;
        font-weight: 600;
        min-width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8f9fa;
        padding: 0 14px;
        transition: background-color .15s ease, border-color .15s ease, color .15s ease;
    }

    .premium-pagination-shell .page-link:hover {
        border-color: #1367ad;
        background: #e7f1fb;
        color: #0f4c81;
        text-decoration: none;
    }

    .premium-pagination-shell .page-item.active .page-link {
        background: linear-gradient(135deg, #0f4c81 0%, #1367ad 100%);
        border-color: #0f4c81;
        color: #fff;
        box-shadow: 0 2px 8px rgba(15, 76, 129, 0.25);
    }

    .premium-pagination-shell .page-item.disabled .page-link {
        background: #f8f9fa;
        color: #adb5bd;
        border-color: #d0d6dd;
        pointer-events: none;
    }
</style>
@endpush

@section('content')

<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-history menu-icon"></i>
            </span> {{\App\CPU\translate('Activity Logs')}}
        </h3>
        <nav aria-label="breadcrumb"></nav>
    </div>

    <div class="d-flex justify-content-end mb-3">
        <div class="activity-filter-scroll">
            <form method="GET" action="{{ route('admin.activity.logs') }}" class="activity-filter-form d-flex align-items-center gap-2">
                <input type="text" class="form-control search-control" name="search" value="{{ $search }}" placeholder="Search activity, user, module">

                <select class="form-select" name="date_type" id="date_type">
                    <option value="today" {{ $date_type === 'today' ? 'selected' : '' }}>Today</option>
                    <option value="this_week" {{ $date_type === 'this_week' ? 'selected' : '' }}>This Week</option>
                    <option value="this_month" {{ $date_type === 'this_month' ? 'selected' : '' }}>This Month</option>
                    <option value="this_year" {{ $date_type === 'this_year' ? 'selected' : '' }}>This Year</option>
                    <option value="custom_date" {{ $date_type === 'custom_date' ? 'selected' : '' }}>Custom Date</option>
                </select>

                <input type="date" class="form-control date-control" id="from_date" name="from" value="{{ $from != date('Y-m-d') && $date_type === 'custom_date' ? $from : '' }}" placeholder="From date">
                <input type="date" class="form-control date-control" id="to_date" name="to" value="{{ $to != date('Y-m-d') && $date_type === 'custom_date' ? $to : '' }}" placeholder="To date">

                <button type="submit" class="btn btn-primary px-4">Filter</button>
                <a href="{{ route('admin.activity.logs') }}" class="btn btn-outline-secondary px-4">Reset</a>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">

            <!-- Table Section -->
            <div class="card">
                <div class="table-responsive">
                    <table class="table">
                        <thead class="text-capitalize">
                            <tr>
                                <th>Activity By</th>
                                <th>User</th>
                                <th>Action</th>
                                <th>Module</th>
                                <th>Date</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($logs as $log)
                                <tr>
                                    <td>{{ ucwords($log->causer->name ?? ($log->causer->username ?? 'System')) }}</td>
                                    <td>
                                        @if($log->causer_type == 'App\Models\Seller')
                                            Brand
                                        @elseif($log->causer_type == 'App\Models\Sale')
                                            Sale
                                        @elseif($log->causer_type == 'App\Models\Admin')
                                            Admin
                                        @else
                                            User
                                        @endif

                                    </td>
                                    <td>{{ $log->description }}</td>
                                    <td>{{ str_replace('_', ' ', ucwords($log->log_name)) }}</td>
                                    <td>{{ \App\CPU\Helpers::setDateTime($log) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">{{\App\CPU\translate('No activity logs found')}}</td>
                                </tr>
                            @endforelse

                        </tbody>

                    </table>
                </div>

                @if($logs->hasPages())
                <div class="premium-pagination-wrap">
                    <div class="premium-pagination-shell">
                        <div class="premium-pagination-inline">
                            {!! $logs->onEachSide(1)->links('vendor.pagination.premium') !!}
                        </div>
                    </div>
                </div>
                @endif

            </div>

        </div>
    </div>

</div>

<div class="content container-fluid"></div>

@endsection

@push('script')
@endpush

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

        $('#from_date').closest('input').toggle(val === 'custom_date');
        $('#to_date').closest('input').toggle(val === 'custom_date');

        if (val === 'custom_date') {

            $('#from_date').attr('required', 'required');
            $('#to_date').attr('required', 'required');

        } else {

            $('#from_date').val(null).removeAttr('required')
            $('#to_date').val(null).removeAttr('required');

        }

    }).change();
</script>

@endpush