@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Campaign_Report'))

@push('css_or_js')
    <style>
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

        @media (max-width: 767px) {
            .premium-pagination-wrap {
                padding: 12px;
            }

            .premium-pagination-inline {
                justify-content: flex-end;
            }
        }
    </style>
@endpush

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-chart-bar"></i>
            </span> {{\App\CPU\translate('Campaign_Report')}}
        </h3>
        <nav aria-label="breadcrumb">
        </nav>
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
                                    <option value="this_year" {{ $date_type == 'this_year'? 'selected' : '' }}>{{\App\CPU\translate('This_Year')}}</option>
                                    <option value="this_month" {{ $date_type == 'this_month'? 'selected' : '' }}>{{\App\CPU\translate('This_Month')}}</option>
                                    <option value="this_week" {{ $date_type == 'this_week'? 'selected' : '' }}>{{\App\CPU\translate('This_Week')}}</option>
                                    <option value="custom_date" {{ $date_type == 'custom_date'? 'selected' : '' }}>{{\App\CPU\translate('Custom_Date')}}</option>
                                </select>
                            </div>
                            <div class="col-sm-6 col-md-3" id="from_div">
                                <div class="form-floating">
                                    <input type="date" name="from" value="{{ $from }}" id="from_date" class="form-control">
                                    <label>{{ \App\CPU\translate('Start Date')}}</label>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3" id="to_div">
                                <div class="form-floating">
                                    <input type="date" value="{{ $to }}" name="to" id="to_date" class="form-control">
                                    <label>{{ \App\CPU\translate('End Date')}}</label>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <button type="submit" class="btn btn-primary px-4 w-100">
                                    {{ \App\CPU\translate('Filter')}}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mb-2">
                <div class="card-body">
                    <div class="row">

                        <div class="col-md-6 col-lg-2 col-sm-12 stretch-card grid-margin">
                            <div class="card bg-gradient-danger card-img-holder text-white">
                                <div class="card-body">
                                    <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
                                    <h4 class="font-weight-normal mb-3 small cardText">
                                        <span>Total Campaigns</span>
                                        <i class="mdi mdi-bullhorn-outline mdi-24px float-end"></i>
                                    </h4>
                                    <h2 class="small">{{ $data['totalCampaigns'] ?? 0 }}</h2>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-2 col-sm-12 stretch-card grid-margin">
                            <div class="card bg-gradient-info card-img-holder text-white">
                                <div class="card-body">
                                    <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
                                    <h4 class="font-weight-normal mb-3 small cardText">
                                        <span>Live Campaigns</span>
                                        <i class="mdi mdi-access-point mdi-24px float-end"></i>
                                    </h4>
                                    <h2 class="small">{{ $data['liveCampaigns'] ?? 0 }}</h2>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-2 col-sm-12 stretch-card grid-margin">
                            <div class="card bg-gradient-success card-img-holder text-white">
                                <div class="card-body">
                                    <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
                                    <h4 class="font-weight-normal mb-3 small cardText">
                                        <span>Completed Campaigns</span>
                                        <i class="mdi mdi-check-decagram-outline mdi-24px float-end"></i>
                                    </h4>
                                    <h2 class="small">{{ $data['completedCampaigns'] ?? 0 }}</h2>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-2 col-sm-12 stretch-card grid-margin">
                            <div class="card bg-gradient-danger card-img-holder text-white">
                                <div class="card-body">
                                    <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
                                    <h4 class="font-weight-normal mb-3 small cardText">
                                        <span>Total Participants</span>
                                        <i class="mdi mdi-account-group-outline mdi-24px float-end"></i>
                                    </h4>
                                    <h2 class="small">{{ $data['totalParticipants'] ?? 0 }}</h2>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-2 col-sm-12 stretch-card grid-margin">
                            <div class="card bg-gradient-info card-img-holder text-white">
                                <div class="card-body">
                                    <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
                                    <h4 class="font-weight-normal mb-3 small cardText">
                                        <span>Approved Posts</span>
                                        <i class="mdi mdi-check-circle-outline mdi-24px float-end"></i>
                                    </h4>
                                    <h2 class="small">{{ $data['approvedPosts'] ?? 0 }}</h2>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-2 col-sm-12 stretch-card grid-margin">
                            <div class="card bg-gradient-success card-img-holder text-white">
                                <div class="card-body">
                                    <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
                                    <h4 class="font-weight-normal mb-3 small cardText">
                                        <span>Rejected Posts</span>
                                        <i class="mdi mdi-close-circle-outline mdi-24px float-end"></i>
                                    </h4>
                                    <h2 class="small">{{ $data['rejectedPosts'] ?? 0 }}</h2>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header border-0">
                    <div class="w-100 d-flex flex-wrap gap-3 align-items-center">
                        <h4 class="mb-0 mr-auto">
                            {{\App\CPU\translate('Total_Campaign')}}
                            <span class="badge badge-soft-dark radius-50 fz-12">{{ ($data['totalCampaigns'] ?? 0) }}</span>
                        </h4>
                        <a class="btn btn-outline-primary ms-auto" href="{{ route('admin.campaign.reports.export', ['date_type' => $date_type, 'from' => ($from instanceof \Carbon\Carbon ? $from->format('Y-m-d') : $from), 'to' => ($to instanceof \Carbon\Carbon ? $to->format('Y-m-d') : $to)]) }}">
                            {{ \App\CPU\translate('Export CSV') }}
                        </a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="datatable"

                        class="table">
                        <thead class="text-capitalize">
                            <tr>
                                <th>Campaign</th>
                                <th>Brand</th>
                                <th>Budget</th>
                                <th>Participants</th>
                                <th>Approved</th>
                                <th>Rejected</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($campaigns as $campaign)
                            <tr>
                                <td>{{ $campaign->title }}</td>
                                <td>{{ $campaign->brand->username }}</td>
                                <td>{{ $campaign->total_campaign_budget }}</td>
                                <td>{{ $campaign->participants }}</td>
                                <td>{{ $campaign->approved_posts }}</td>
                                <td>{{ $campaign->rejected_posts }}</td>
                                <td>

                                    <span class="badge badge-{{ ($campaign->status == 'active' || $campaign->status == 'completed') ? 'gradient-success' : 'gradient-danger' }}">
                                        {{ ucwords($campaign->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                            
                        <!-- <tfoot>

                            <tr>
                                <th>
                                    {{ $campaigns->links() }}
                                </th>
                            </tr>
                        </tfoot> -->
                    </table>
                </div>
                @if($campaigns->hasPages())
                    <div class="premium-pagination-wrap">
                        <div class="premium-pagination-shell">
                            <div class="premium-pagination-inline">
                                {!! $campaigns->onEachSide(1)->links('vendor.pagination.premium') !!}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="content container-fluid">


</div>
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

    })

    $("#date_type").change(function() {
        let val = $(this).val();
        $('#from_div').toggle(val === 'custom_date');
        $('#to_div').toggle(val === 'custom_date');

        if (val === 'custom_date') {
            $('#from_date').attr('required', 'required');
            $('#to_date').attr('required', 'required');
        } else {
            $('#from_date').val(null).removeAttr('required')
            $('#to_date').val(null).removeAttr('required')
        }
    }).change();
</script>
@endpush