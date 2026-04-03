@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Dashboard'))

@push('css_or_js')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')

<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-view-dashboard menu-icon"></i>
            </span> Dashboard
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <span></span>Overview <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                </li>
            </ul>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-6 col-lg-3 col-sm-12 stretch-card grid-margin">
            <div class="card bg-gradient-danger card-img-holder text-white">
                <div class="card-body">
                    <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
                    <h4 class="font-weight-normal mb-3 small cardText">
                        <span>Users</span>
                        <i class="mdi mdi-account-group-outline mdi-24px float-end"></i>
                    </h4>
                    <h2 class="small">{{ $userCount ?? 0 }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3 col-sm-12 stretch-card grid-margin">
            <div class="card bg-gradient-info card-img-holder text-white">
                <div class="card-body">
                    <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
                    <h4 class="font-weight-normal mb-3 small cardText">
                        <span>Brands</span>
                        <i class="mdi mdi-tag-multiple-outline mdi-24px float-end"></i>
                    </h4>
                    <h2 class="small">{{ $brandCount ?? 0 }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3 col-sm-12 stretch-card grid-margin">
            <div class="card bg-gradient-success card-img-holder text-white">
                <div class="card-body">
                    <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
                    <h4 class="font-weight-normal mb-3 small cardText">
                        <span>Active Campaigns</span>
                        <i class="mdi mdi-bullhorn-outline mdi-24px float-end"></i>
                    </h4>
                    <h2 class="small">{{ $campaignCount ?? 0 }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3 col-sm-12 stretch-card grid-margin">
            <div class="card bg-gradient-danger card-img-holder text-white">
                <div class="card-body">
                    <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
                    <h4 class="font-weight-normal mb-3 small cardText">
                        <span>Live Campaign</span>
                        <i class="mdi mdi-access-point mdi-24px float-end"></i>
                    </h4>
                    <h2 class="small">{{ $liveCampaignCount ?? 0 }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3 col-sm-12 stretch-card grid-margin">
            <div class="card bg-gradient-info card-img-holder text-white">
                <div class="card-body">
                    <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
                    <h4 class="font-weight-normal mb-3 small cardText">
                        <span>Total Campaign Budget</span>
                        <i class="mdi mdi-currency-usd mdi-24px float-end"></i>
                    </h4>
                    <h2 class="small">{{ $totalCampaignBudget ?? 0 }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3 col-sm-12 stretch-card grid-margin">
            <div class="card bg-gradient-success card-img-holder text-white">
                <div class="card-body">
                    <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
                    <h4 class="font-weight-normal mb-3 small cardText">
                        <span>Total Campaign Budget Spent</span>
                        <i class="mdi mdi-cash-check mdi-24px float-end"></i>
                    </h4>
                    <h2 class="small">{{ $totalCampaignBudgetSpent ?? 0 }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3 col-sm-12 stretch-card grid-margin">
            <div class="card bg-gradient-success card-img-holder text-white">
                <div class="card-body">
                    <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
                    <h4 class="font-weight-normal mb-3 small cardText">
                        <span>Total Campaign Participants</span>
                        <i class="mdi mdi-account-multiple-check-outline mdi-24px float-end"></i>
                    </h4>
                    <h2 class="small">{{ $totalCampaignparticipants ?? 0 }}</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <h4 class="card-header">Recent Campaigns</h4>
                <div class="table-responsive">
                    <table class="table">
                        <thead class="text-capitalize">
                            <tr>
                                <th> Brand Name </th>
                                <th> Campaign Title </th>
                                <th> Status </th>
                                <th> Start Date </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($campaigns as $campaign)
                            <tr>
                                <td>{{ $campaign->brand->username }}</td>
                                <td>{{ $campaign->title }}</td>
                                <td>
                                    @if($campaign->status == 'active')
                                    <label class="badge badge-gradient-success">ACTIVE</label>
                                    @elseif($campaign->status == 'inactive')
                                    <label class="badge badge-gradient-secondary">INACTIVE</label>
                                    @else
                                    <label class="badge badge-gradient-warning">{{ strtoupper($campaign->status) }}</label>
                                    @endif
                                </td>
                                <td>{{ date('M d, Y', strtotime($campaign->start_date)) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">No active campaigns found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('script')
<!-- Custom js for this page -->
<script src="{{ asset('assets/js/dashboard.js') }}"></script>
<!-- End custom js for this page -->
@endpush


@push('script_2')


@endpush