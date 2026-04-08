@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Brand_Report'))

@push('css_or_js')
@endpush

@section('content')

<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-home"></i>
            </span> {{\App\CPU\translate('Brand_Reports')}}
        </h3>
        <nav aria-label="breadcrumb"></nav>
    </div>

    <div class="row">
        <div class="col-lg-12">

            <!-- Filter Section -->
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

                            <div class="col-sm-6 col-md-2">
                                <button type="submit" class="btn btn-primary px-4 w-100">
                                    {{ \App\CPU\translate('Filter')}}
                                </button>
                            </div>

                        </div>

                    </form>

                </div>
            </div>


            <!-- Stats Section Styled Same As Campaign Report -->
            <div class="card mb-2">
                <div class="card-body">

                    <div class="row">

                        <div class="col-md-6 col-lg-3 col-sm-12 stretch-card grid-margin">
                            <div class="card bg-gradient-danger card-img-holder text-white">
                                <div class="card-body">
                                    <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute">
                                    <h4 class="font-weight-normal mb-3 small cardText">
                                        <span>{{ \App\CPU\translate('Total_Brand') }}</span>
                                        <i class="mdi mdi-tag-multiple-outline mdi-24px float-end"></i>
                                    </h4>
                                    <h2 class="small">{{ $brandsCount ?? 0 }}</h2>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3 col-sm-12 stretch-card grid-margin">
                            <div class="card bg-gradient-info card-img-holder text-white">
                                <div class="card-body">
                                    <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute">
                                    <h4 class="font-weight-normal mb-3 small cardText">
                                        <span>{{ \App\CPU\translate('Total_Brand_Campaign') }}</span>
                                        <i class="mdi mdi-bullhorn-outline mdi-24px float-end"></i>
                                    </h4>
                                    <h2 class="small">{{ $campaignCount ?? 0 }}</h2>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3 col-sm-12 stretch-card grid-margin">
                            <div class="card bg-gradient-success card-img-holder text-white">
                                <div class="card-body">
                                    <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute">
                                    <h4 class="font-weight-normal mb-3 small cardText">
                                        <span>{{ \App\CPU\translate('Approved') }}</span>
                                        <i class="mdi mdi-check-circle-outline mdi-24px float-end"></i>
                                    </h4>
                                    <h2 class="small">{{ $data['active_product'] ?? 0 }}</h2>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3 col-sm-12 stretch-card grid-margin">
                            <div class="card bg-gradient-warning card-img-holder text-white">
                                <div class="card-body">
                                    <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute">
                                    <h4 class="font-weight-normal mb-3 small cardText">
                                        <span>{{ \App\CPU\translate('Pending Request') }}</span>
                                        <i class="mdi mdi-clock-outline mdi-24px float-end"></i>
                                    </h4>
                                    <h2 class="small">{{ $data['pending_product'] ?? 0 }}</h2>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3 col-sm-12 stretch-card grid-margin">
                            <div class="card bg-gradient-danger card-img-holder text-white">
                                <div class="card-body">
                                    <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute">
                                    <h4 class="font-weight-normal mb-3 small cardText">
                                        <span>{{ \App\CPU\translate('Denied') }}</span>
                                        <i class="mdi mdi-close-circle-outline mdi-24px float-end"></i>
                                    </h4>
                                    <h2 class="small">{{ $data['rejected_product'] ?? 0 }}</h2>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>


            <!-- Table Section -->
            <div class="card">

                <div class="card-header">
                    <div class="w-100 d-flex flex-wrap gap-3 align-items-center">
                        <h4 class="mb-0 mr-auto">
                            {{\App\CPU\translate('Total_Brand')}}
                            <span class="badge badge-soft-dark radius-50 fz-12">
                                {{ ($brandsCount ?? 0) }}
                            </span>
                        </h4>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="datatable"
                        class="table">

                        <thead class="text-capitalize">
                            <tr>
                                <th>Brand</th>
                                <th>Campaigns</th>
                                <th>Budget</th>
                                <th>Spent</th>
                                <th>Participants</th>
                                <th>Engagement</th>
                                <th>Avg Rating</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($brands as $brand)

                            <tr>
                                <td>{{ $brand->username }}</td>
                                <td>{{ $brand->total_campaign }}</td>
                                <td>{{ $brand->total_campaign_budget }}</td>
                                <td>{{ $brand->total_campaign_budget_spent }}</td>
                                <td>{{ $brand->total_campaign_participant }}</td>
                                <td>{{ $brand->campaign_engagement }}</td>
                                <td>{{ $brand->total_campaign_ratings }}</td>
                            </tr>

                            @empty

                            <tr>
                                <th colspan="6">
                                    No Report Available
                                </th>
                            </tr>

                            @endforelse

                        </tbody>

                    </table>
                </div>
            </div>
            <!-- Pagination Styled Same As Campaign Page -->
            <div class="table-responsive mt-4">
                <div class="px-4 d-flex justify-content-lg-end">
                    {{ $brands->links() }}
                </div>
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

        $('#from_div').toggle(val === 'custom_date');
        $('#to_div').toggle(val === 'custom_date');

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