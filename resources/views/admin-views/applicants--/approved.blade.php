@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('All Applicants'))

@push('css_or_js')

@endpush
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 d-flex gap-10">
                <img src="{{asset('/public/assets/back-end/img/brand-setup.png')}}" alt="">
                {{\App\CPU\translate('All Applicants')}}
            </h2>
        </div>
        <div class="row g-2 mb-4">
            <div class="col-sm-6 col-lg-3 col-6">
                <a class="order-stats order-stats_pending"
                    href="{{url()->current() }}">
                    <div class="order-stats__content">
                        <!-- <img width="20" src="{{asset('/public/assets/back-end/img/pending.png')}}" class="svg" alt=""> -->
                        <h6 class="order-stats__subtitle">{{\App\CPU\translate('All Applications')}}</h6>
                    </div>
                    <span class="order-stats__title">
                        {{ $applicationCounts['all'] ?? 0 }}
                    </span>
                </a>
            </div>
            <div class="col-sm-6 col-lg-3 col-6">
                <a class="order-stats order-stats_pending"
                    href="{{route('admin.application.approved-list', ['type'=>'initial'])}}">
                    <div class="order-stats__content">
                        <!-- <img width="20" src="{{asset('/public/assets/back-end/img/pending.png')}}" class="svg" alt=""> -->
                        <h6 class="order-stats__subtitle">{{\App\CPU\translate('Initial accreditation')}}</h6>
                    </div>
                    <span class="order-stats__title">
                        {{ $applicationCounts['initial'] ?? 0 }}
                    </span>
                </a>
            </div>

            <div class="col-sm-6 col-lg-3 col-6">
                <a class="order-stats order-stats_confirmed"
                    href="{{route('admin.application.approved-list', ['type'=>'re-accreditation'])}}">
                    <div class="order-stats__content"
                        style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                        <!-- <img width="20" src="{{asset('/public/assets/back-end/img/confirmed.png')}}" alt=""> -->
                        <h6 class="order-stats__subtitle">{{\App\CPU\translate('Re-accreditation')}}</h6>
                    </div>
                    <span class="order-stats__title">
                        {{ $applicationCounts['re-accreditation'] ?? 0 }}
                    </span>
                </a>
            </div>

            <div class="col-sm-6 col-lg-3 col-6">
                <a class="order-stats order-stats_confirmed"
                    href="{{route('admin.application.approved-list', ['type'=>'surveillance'])}}">
                    <div class="order-stats__content"
                        style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                        <!-- <img width="20" src="{{asset('/public/assets/back-end/img/confirmed.png')}}" alt=""> -->
                        <h6 class="order-stats__subtitle">{{\App\CPU\translate('Surveillance')}}</h6>
                    </div>
                    <span class="order-stats__title">
                        {{ $applicationCounts['surveillance'] ?? 0 }}
                    </span>
                </a>
            </div>

        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card py-3">
                    <div class="card-body">
                        <h6 class="customHeading">Select by Applicants</h6>
                        <ul class="nav nav-pills applicantTab" id="pills-tab" role="tablist">

                            <a href="{{ route('admin.application.approved-list', ['type'=>$type,'status' => 'schedule']) }}">
                                <li role="presentation">
                                    <button class="{{ ($status == 'schedule') ? 'active' : '' }}">
                                        Scheduled but not started ({{ $statusCounts['schedule'] }})
                                    </button>
                                </li>
                            </a>

                            <a href="{{ route('admin.application.approved-list', ['type'=>$type,'status' => 'in_process']) }}">
                                <li role="presentation">
                                    <button class="{{ ($status == 'in_process') ? 'active' : '' }}">
                                        Assessment in progress ({{ $statusCounts['in_process'] }})
                                    </button>
                                </li>
                            </a>

                            <a href="{{ route('admin.application.approved-list', ['type'=>$type,'status' => 'quality_check']) }}">
                                <li role="presentation">
                                    <button class="{{ ($status == 'quality_check') ? 'active' : '' }}">
                                        Quality Check ({{ $statusCounts['quality_check'] }})
                                    </button>
                                </li>
                            </a>

                            <a href="{{ route('admin.application.approved-list', ['type'=>$type,'status' => 'non_conformance']) }}">
                                <li role="presentation">
                                    <button class="{{ ($status == 'non_conformance') ? 'active' : '' }}">
                                        Non Conformance ({{ $statusCounts['non_conformance'] }})
                                    </button>
                                </li>
                            </a>

                            <a href="{{ route('admin.application.approved-list', ['type'=>$type,'status' => 'reject']) }}">
                                <li role="presentation">
                                    <button class="{{ ($status == 'reject') ? 'active' : '' }}">
                                        Reject ({{ $statusCounts['reject'] }})
                                    </button>
                                </li>
                            </a>

                            <a href="{{ route('admin.application.approved-list', ['type'=>$type,'status' => 'complete']) }}">
                                <li role="presentation">
                                    <button class="{{ ($status == 'complete') ? 'active' : '' }}">
                                        Complete ({{ $statusCounts['complete'] }})
                                    </button>
                                </li>
                            </a>

                        </ul>

                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-20" id="cate-table">
            <div class="col-md-12">
                <div class="card">
                    <div class="px-3 py-4">
                        <div class="row align-items-center">
                            <div class="col-sm-4 col-md-6 col-lg-8 mb-2 mb-sm-0">
                                <h5 class="text-capitalize d-flex gap-1">
                                    {{ \App\CPU\translate('applicants_list')}}
                                    <!-- <span class="badge badge-soft-dark radius-50 fz-12">{{$status}}</span> -->
                                </h5>
                            </div>
                            <div class="col-sm-8 col-md-6 col-lg-4">
                                <form action="" method="GET">
                                    <div class="input-group input-group-custom input-group-merge">
                                        <input type="hidden" name="status" value="{{ request()->status }}">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="" type="search" name="search" class="form-control"
                                            placeholder="Search here" value="" required="">
                                        <button type="submit" class="btn btn--primary">Search</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-Sector" role="tabpanel"
                            aria-labelledby="pills-Sector-tab" tabindex="0">
                            <div class="table-responsive">
                                <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                                    class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                                    <thead class="thead-light thead-50 text-capitalize">
                                        <tr>
                                            <th>{{ \App\CPU\translate('S.No.')}}</th>
                                            <th>{{ \App\CPU\translate('User')}}</th>
                                            <th>{{ \App\CPU\translate('Company')}}</th>
                                            <th>{{ \App\CPU\translate('Scheme')}}</th>
                                            <th>{{ \App\CPU\translate('Mode of Assessment')}}</th>
                                            <th>{{ \App\CPU\translate('Team Leader')}}</th>
                                            <th>{{ \App\CPU\translate('Team Member')}}</th>
                                            <th>{{ \App\CPU\translate('action')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($applications as $a_key => $application)
                                            <tr>
                                                <td>{{ $a_key + 1 }}</td>
                                                <td>
                                                    <div class="tableDetails">
                                                        <span>{{ $application->user->phone }}</span>
                                                        <h6>{{ $application->user->name }}</h6>
                                                        <p> {{ $application->user->email }}</p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="tableDetails">
                                                        <span>{{ $application->company->name }}</span>
                                                        <h6>{{ $application->company->organization }}</h6>
                                                        <p>{{ $application->company->address }}</p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <h6>{{ $application->scheme->title ?? '' }}</h6>
                                                </td>
                                                <td>
                                                    <h6>{{ $application->mode_of_auditor }}</h6>
                                                </td>
                                                <td>
                                                    <div class="tableDetails">
                                                        @if (($application->status == 'schedule') || ($application->status == 'in_process'))
                                                            <span>{{ $application->auditor->role->name ?? '' }}</span>
                                                            <h6>{{ $application->auditor->name ?? '' }}</h6>

                                                        @elseif($application->status == 'quality_check')
                                                            <span>{{ $application->quality->role->name ?? '' }}</span>
                                                            <h6>{{ $application->quality->name ?? '' }}</h6>

                                                        @elseif($application->status == 'non_conformance')
                                                            <span>{{ $application->accreditation->role->name ?? '' }}</span>
                                                            <h6>{{ $application->accreditation->name ?? '' }}</h6>

                                                        @else
                                                            <span>{{ $application->auditor->role->name ?? '' }}</span>
                                                            <h6>{{ $application->auditor->name ?? '' }}</h6>
                                                            <p>{{ $application->auditor->assessor->work_address ?? '' }}</p>

                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="tableDetails">
                                                        @if (($application->status == 'schedule') || ($application->status == 'in_process'))
                                                            @if(!empty($application->auditor_team))
                                                                @foreach ($application->auditor_team as $key => $value)
                                                                    <h6>Name : {{ $value->name ?? '' }}</h6>
                                                                @endforeach
                                                            @endif
                                                        @elseif($application->status == 'quality_check')
                                                            @if(!empty($application->quality_team))
                                                                @foreach ($application->quality_team as $key => $value)
                                                                    <h6>Name : {{ $value->name ?? '' }}</h6>
                                                                @endforeach
                                                            @endif
                                                        @elseif($application->status == 'non_conformance')
                                                            @if(!empty($application->accreditation_team))
                                                                @foreach ($application->accreditation_team as $key => $value)
                                                                    <h6>Name : {{ $value->name ?? '' }}</h6>
                                                                @endforeach
                                                            @endif
                                                        @else
                                                            @if(!empty($application->auditor_team))
                                                                @foreach ($application->auditor_team as $key => $value)
                                                                    <h6>Name : {{ $value->name ?? '' }}</h6>
                                                                @endforeach
                                                            @endif
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <div
                                                        style="display: flex; flex-direction: column; gap: 7px; align-items: start;">
                                                        <a href="{{ route('admin.company.show', $application->company->id) }}"
                                                            class="customSecondBtn">
                                                            View Profile
                                                        </a>
                                                        <a href="{{ route('admin.application.view-detail', $application->id) }}"
                                                            class="customPrimaryBtn">
                                                            View Application
                                                        </a>
                                                        <a href="{{ route('admin.user-chat', $application->id) }}"
                                                            class="customPrimaryBtn d-none">
                                                            Chat
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty

                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td>
                                                {{ $applications->links() }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"
        integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y"
        crossorigin="anonymous"></script>


@endpush