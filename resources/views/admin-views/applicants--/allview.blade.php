@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('All Applicants'))

@push('css_or_js')

@endpush
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

@section('content')
<div class="content container-fluid">
    <div class="mb-3">
        <h2 class="h1 mb-0 d-flex gap-10">
            <img src="{{asset('/public/assets/back-end/img/brand-setup.png')}}" alt="">
            {{\App\CPU\translate('All Applicants')}}
        </h2>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card py-3 mt-4">
                <div class="card-body">
                    <h6 class="customHeading">Select by Applicants</h6>
                    <ul class="nav nav-pills applicantTab" id="pills-tab" role="tablist">
                        <li class="" role="presentation">
                            <button class=" active" id="pills-Sector-tab" data-bs-toggle="pill" data-bs-target="#pills-Sector" type="button" role="tab" aria-controls="pills-Sector" aria-selected="true">Scheduled but not started</button>
                        </li>
                        <li class="" role="presentation">
                            <button class="" id="pills-State-tab" data-bs-toggle="pill" data-bs-target="#pills-State" type="button" role="tab" aria-controls="pills-State" aria-selected="false">Assessment in progress</button>
                        </li>
                        <li class="" role="presentation">
                            <button class="" id="pills-Size-tab" data-bs-toggle="pill" data-bs-target="#pills-Size" type="button" role="tab" aria-controls="pills-Size" aria-selected="false">Quality Check</button>
                        </li>
                        <li class="" role="presentation">
                            <button class="" id="pills-Certification-tab" data-bs-toggle="pill" data-bs-target="#pills-Certification" type="button" role="tab" aria-controls="pills-Certification" aria-selected="false">Non conformance</button>
                        </li>
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
                                <span class="badge badge-soft-dark radius-50 fz-12">All</span>
                            </h5>
                        </div>
                        <div class="col-sm-8 col-md-6 col-lg-4">
                            <form action="" method="GET">
                                <div class="input-group input-group-custom input-group-merge">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="tio-search"></i>
                                        </div>
                                    </div>
                                    <input id="" type="search" name="search" class="form-control" placeholder="Search here" value="" required="">
                                    <button type="submit" class="btn btn--primary">Search</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-Sector" role="tabpanel" aria-labelledby="pills-Sector-tab" tabindex="0">
                        <div class="table-responsive">
                            <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                                class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                                <thead class="thead-light thead-50 text-capitalize">
                                    <tr>
                                        <th>{{ \App\CPU\translate('S. No.')}}</th>
                                        <th>{{ \App\CPU\translate('	Certification Lavel')}}</th>
                                        <th>{{ \App\CPU\translate('	Unit Details')}}</th>
                                        <th>{{ \App\CPU\translate('Agency')}}</th>
                                        <th>{{ \App\CPU\translate('Mode of Assessment')}}</th>
                                        <th>{{ \App\CPU\translate('Assessment Details')}}</th>
                                        <th>{{ \App\CPU\translate('Duplicates')}}</th>
                                        <th>{{ \App\CPU\translate('	ACTION')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>01</td>
                                        <td>Silver</td>
                                        <td>
                                            <div class="tableDetails">
                                                <span>28Y7I600EMN81Z</span>
                                                <h6>BEENA CASHEW COMPANY</h6>
                                                <p> Flat No:- 0, Building:- PUJA AGRO FOOD</p>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="tableDetails">
                                                <span>28Y7I600EMN81Z</span>
                                                <h6>BEENA CASHEW COMPANY</h6>
                                            </div>
                                        </td>
                                        <td> Desktop</td>
                                        <td>
                                            <div class="tableDetails">
                                                <span>28Y7I600EMN81Z</span>
                                                <h6>BEENA CASHEW COMPANY</h6>
                                              
                                            </div>
                                        </td>
                                        <td> 0</td>
                                        <td>
                                            <div style="display: flex; flex-direction: column; gap: 7px; align-items: start;">
                                                <a href="https://abms.developmentalphawizz.com/admin/applicants/assessment-details" class="customSecondBtn">
                                                    View Prifile
                                                </a>
                                                <a class="customPrimaryBtn">
                                                    View Application
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>02</td>
                                        <td>Gold</td>
                                        <td>
                                            <div class="tableDetails">
                                                <span>28Y7I600EMN81Z</span>
                                                <h6>BEENA CASHEW COMPANY</h6>
                                                <p> Flat No:- 0, Building:- PUJA AGRO FOOD</p>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="tableDetails">
                                                <span>28Y7I600EMN81Z</span>
                                                <h6>BEENA CASHEW COMPANY</h6>
                                            </div>
                                        </td>
                                        <td> Desktop</td>
                                        <td>
                                            <div class="tableDetails">
                                                <span>28Y7I600EMN81Z</span>
                                                <h6>BEENA CASHEW COMPANY</h6>
                                              
                                            </div>
                                        </td>

                                        <td> 0</td>

                                        <td>
                                            <div style="display: flex; flex-direction: column; gap: 7px; align-items: start;">
                                                <a href="https://abms.developmentalphawizz.com/admin/applicants/assessment-details" class="customSecondBtn">
                                                    View Prifile
                                                </a>
                                                <a class="customPrimaryBtn">
                                                    View Application
                                                </a>
                                            </div>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-State" role="tabpanel" aria-labelledby="pills-State-tab" tabindex="0">
                        <div class="table-responsive">
                            <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                                class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                                <thead class="thead-light thead-50 text-capitalize">
                                    <tr>
                                        <th>{{ \App\CPU\translate('S. No.')}}</th>
                                        <th>{{ \App\CPU\translate('	ENTERPRISE NAME')}}</th>
                                        <th>{{ \App\CPU\translate('NIC 2 SECTOR')}}</th>
                                        <th>{{ \App\CPU\translate('UNIT ADDRESS')}}</th>
                                        <th>{{ \App\CPU\translate('STATE')}}</th>
                                        <th>{{ \App\CPU\translate('SIZE')}}</th>
                                        <th>{{ \App\CPU\translate('CERTIFICATION')}}</th>
                                        <th>{{ \App\CPU\translate('	CERTIFICATE STATUS')}}</th>
                                        <th>{{ \App\CPU\translate('	ACTION')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>01</td>
                                        <td> BEENA CASHEW COMPANY</td>
                                        <td> 10-Manufacture of food products</td>
                                        <td> Flat No:- 157, Building:- BEENA CASHEW</td>
                                        <td> KERALA</td>
                                        <td> Small</td>
                                        <td> Gold</td>
                                        <td> SUSPENDED</td>
                                        <td>
                                            <div style="display: flex; flex-direction: column; gap: 7px; align-items: start;">
                                                <a class="customSecondBtn">
                                                    View Prifile
                                                </a>
                                                <a class="customPrimaryBtn">
                                                    View Application
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>01</td>
                                        <td> PUJA AGRO FOOD</td>
                                        <td> 10-Manufacture of food products</td>
                                        <td> FFlat No:- 0, Building:- PUJA AGRO FOOD</td>
                                        <td> BIHAR</td>
                                        <td> Medium</td>
                                        <td> Gold</td>
                                        <td> ACTIVE</td>
                                        <td>
                                            <div style="display: flex; flex-direction: column; gap: 7px; align-items: start;">
                                                <a href="https://abms.developmentalphawizz.com/admin/applicants/assessment-details" class="customSecondBtn">
                                                    View Prifile
                                                </a>
                                                <a class="customPrimaryBtn">
                                                    View Application
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-Size" role="tabpanel" aria-labelledby="pills-Size-tab" tabindex="0">
                        <div class="table-responsive">
                            <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                                class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                                <thead class="thead-light thead-50 text-capitalize">
                                    <tr>
                                        <th>{{ \App\CPU\translate('S. No.')}}</th>
                                        <th>{{ \App\CPU\translate('	ENTERPRISE NAME')}}</th>
                                        <th>{{ \App\CPU\translate('NIC 2 SECTOR')}}</th>
                                        <th>{{ \App\CPU\translate('UNIT ADDRESS')}}</th>
                                        <th>{{ \App\CPU\translate('STATE')}}</th>
                                        <th>{{ \App\CPU\translate('SIZE')}}</th>
                                        <th>{{ \App\CPU\translate('CERTIFICATION')}}</th>
                                        <th>{{ \App\CPU\translate('	CERTIFICATE STATUS')}}</th>
                                        <th>{{ \App\CPU\translate('	ACTION')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>01</td>
                                        <td> BEENA CASHEW COMPANY</td>
                                        <td> 10-Manufacture of food products</td>
                                        <td> Flat No:- 157, Building:- BEENA CASHEW</td>
                                        <td> KERALA</td>
                                        <td> Small</td>
                                        <td> Gold</td>
                                        <td> SUSPENDED</td>
                                        <td>
                                            <div style="display: flex; flex-direction: column; gap: 7px; align-items: start;">
                                                <a href="https://abms.developmentalphawizz.com/admin/applicants/assessment-details" class="customSecondBtn">
                                                    View Prifile
                                                </a>
                                                <a class="customPrimaryBtn">
                                                    View Application
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>01</td>
                                        <td> PUJA AGRO FOOD</td>
                                        <td> 10-Manufacture of food products</td>
                                        <td> FFlat No:- 0, Building:- PUJA AGRO FOOD</td>
                                        <td> BIHAR</td>
                                        <td> Medium</td>
                                        <td> Gold</td>
                                        <td> ACTIVE</td>
                                        <td>
                                            <div style="display: flex; flex-direction: column; gap: 7px; align-items: start;">
                                                <a class="customSecondBtn">
                                                    View Prifile
                                                </a>
                                                <a class="customPrimaryBtn">
                                                    View Application
                                                </a>
                                            </div>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-Certification" role="tabpanel" aria-labelledby="pills-Certification-tab" tabindex="0">
                        <div class="table-responsive">
                            <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                                class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                                <thead class="thead-light thead-50 text-capitalize">
                                    <tr>
                                        <th>{{ \App\CPU\translate('S. No.')}}</th>
                                        <th>{{ \App\CPU\translate('	ENTERPRISE NAME')}}</th>
                                        <th>{{ \App\CPU\translate('NIC 2 SECTOR')}}</th>
                                        <th>{{ \App\CPU\translate('UNIT ADDRESS')}}</th>
                                        <th>{{ \App\CPU\translate('STATE')}}</th>
                                        <th>{{ \App\CPU\translate('SIZE')}}</th>
                                        <th>{{ \App\CPU\translate('CERTIFICATION')}}</th>
                                        <th>{{ \App\CPU\translate('	CERTIFICATE STATUS')}}</th>
                                        <th>{{ \App\CPU\translate('	ACTION')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>01</td>
                                        <td> BEENA CASHEW COMPANY</td>
                                        <td> 10-Manufacture of food products</td>
                                        <td> Flat No:- 157, Building:- BEENA CASHEW</td>
                                        <td> KERALA</td>
                                        <td> Small</td>
                                        <td> Gold</td>
                                        <td> SUSPENDED</td>
                                        <td>
                                            <div style="display: flex; flex-direction: column; gap: 7px; align-items: start;">
                                                <a href="https://abms.developmentalphawizz.com/admin/applicants/assessment-details" class="customSecondBtn">
                                                    View Prifile
                                                </a>
                                                <a class="customPrimaryBtn">
                                                    View Application
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>02</td>
                                        <td> PUJA AGRO FOOD</td>
                                        <td> 10-Manufacture of food products</td>
                                        <td> FFlat No:- 0, Building:- PUJA AGRO FOOD</td>
                                        <td> BIHAR</td>
                                        <td> Medium</td>
                                        <td> Gold</td>
                                        <td> ACTIVE</td>
                                        <td>
                                            <div style="display: flex; flex-direction: column; gap: 7px; align-items: start;">
                                                <a href="https://abms.developmentalphawizz.com/admin/applicants/assessment-details" class="customSecondBtn">
                                                    View Prifile
                                                </a>
                                                <a class="customPrimaryBtn">
                                                    View Application
                                                </a>
                                            </div>
                                        </td>
                                    </tr>

                                </tbody>
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
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>


@endpush