@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Assessment Details'))

@push('css_or_js')

@endpush
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 d-flex gap-10">
                <img src="{{asset('/public/assets/back-end/img/brand-setup.png')}}" alt="">
                {{\App\CPU\translate('Assessment Details')}}
            </h2>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card py-3 mt-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <h6 class="customHeading">Select by Applicants</h6>
                            </div>
                            @if (auth('admin')->user()->admin_role_id == 1)
                            <div class="col-6">
                                <button class="btn btn-primary mb-3" style="float: inline-end;background-color: #252424;"
                                    type="button" data-toggle="modal" data-target="#exampleModal">Add Remark</button>
                                    @if(
                                        $application->status == 'complete'
                                        &&
                                        \Carbon\Carbon::parse($application->certificate_issue_date)
                                            ->addYear()
                                            ->lt(\Carbon\Carbon::now())
                                        &&
                                        (
                                            is_null($application->last_surveillance_date)
                                            ||
                                            \Carbon\Carbon::parse($application->last_surveillance_date)
                                                ->addYear()
                                                ->lt(\Carbon\Carbon::now())
                                        )
                                    )
                                        <button class="btn btn-primary mb-3" style="float: inline-end;background-color: #f17814ff;"
                                            type="button" id="surveillanceButton" data-applicationId="{{ $application->id }}">Surveillance Start</button>
                                    @endif
                            </div>
                            @endif

                            @if (auth('admin')->user()->admin_role_id == 2)
                                @if(($qualityStatus == 0)  && ($application->application_status == 'quality_check'))
                                    <div class="col-6">
                                        <button class="startEndButton btn btn-primary mb-3" style="float: inline-end;background-color: #25bd25ff;"
                                            type="button" data-toggle="modal" data-type="quality_check" data-status="1" data-target="#exampleAdminModal">Start</button>
                                    </div>
                                @elseif(($qualityStatus == 1)  && ($application->application_status == 'quality_check'))
                                    <div class="col-6">
                                        <button class="startEndButton btn btn-primary mb-3" style="float: inline-end;background-color: #f3290eff;"
                                            type="button" data-toggle="modal" data-type="quality_check" data-status="2" data-target="#exampleAdminModal">End</button>
                                    </div>
                                @elseif($qualityStatus == 2)
                                    <div class="col-6">
                                        <button class="startEndButton btn btn-primary mb-3" style="float: inline-end;background-color: #2a73faff;"
                                            type="button" >Completed</button>
                                    </div>
                                @endif
                            @endif
                            @if(auth('admin')->user()->admin_role_id == 5)
                                @if(($accreditationStatus == 0) && ($application->application_status == 'accreditation_committee'))
                                    <div class="col-6">
                                        <button class="startEndButton btn btn-primary mb-3" style="float: inline-end;background-color: #25bd25ff;"
                                            type="button" data-toggle="modal" data-type="accreditation_committee" data-status="1" data-target="#exampleAdminModal">Start</button>
                                    </div>
                                @elseif(($accreditationStatus == 1) && ($application->application_status == 'accreditation_committee'))
                                    <div class="col-6">
                                        <button class="startEndButton btn btn-primary mb-3" style="float: inline-end;background-color: #f3290eff;"
                                            type="button" data-toggle="modal" data-type="accreditation_committee" data-status="2" data-target="#exampleAdminModal">End</button>
                                    </div>
                                @elseif($accreditationStatus == 2)
                                    <div class="col-6">
                                        <button class="startEndButton btn btn-primary mb-3" style="float: inline-end;background-color: #2a73faff;"
                                            type="button" >Completed</button>
                                    </div>
                                @endif
                            @endif
                        </div>
                        <ul class="nav nav-pills applicantTab" id="pills-tab" role="tablist">
                            <li class="" role="presentation">
                                <button class=" active" id="pills-Sector-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-Sector" type="button" role="tab" aria-controls="pills-Sector"
                                    aria-selected="true">Company Profile</button>
                            </li>
                            <li class="" role="presentation">
                                <button class="" id="pills-Document-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-Document" type="button" role="tab" aria-controls="pills-Document"
                                    aria-selected="true">Documents</button>
                            </li>
                            <li class="" role="presentation">
                                <button class="" id="pills-State-tab" data-bs-toggle="pill" data-bs-target="#pills-State"
                                    type="button" role="tab" aria-controls="pills-State" aria-selected="false">Accreditation
                                    Form</button>
                            </li>
                            <li class="" role="presentation">
                                <button class="" id="pills-Size-tab" data-bs-toggle="pill" data-bs-target="#pills-Size"
                                    type="button" role="tab" aria-controls="pills-Size"
                                    aria-selected="false">{{ $application->scheme->title }}</button>
                            </li>
                            <li class="" role="presentation">
                                <button class="" id="pills-Checklist-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-Checklist" type="button" role="tab"
                                    aria-controls="pills-Checklist" aria-selected="false">Self Assessment CheckList</button>
                            </li>
                            <li class="" role="presentation">
                                <button class="" id="pills-office-assessment-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-office-assessment" type="button" role="tab"
                                    aria-controls="pills-office-assessment" aria-selected="false">Office Assessment</button>
                            </li>
                            <li class="d-none" role="presentation">
                                <button class="" id="pills-witness-assessment-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-witness-assessment" type="button" role="tab"
                                    aria-controls="pills-witness-assessment" aria-selected="false">Witness Assessment</button>
                            </li>
                            <li class="" role="presentation">
                                <button class="" id="pills-payment-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-payment" type="button" role="tab"
                                    aria-controls="pills-payment" aria-selected="false">Payment Details</button>
                            </li>
                            <li class="" role="presentation">
                                <button class="" id="pills-teamDetail-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-teamDetail" type="button" role="tab"
                                    aria-controls="pills-teamDetail" aria-selected="false">Team Details</button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-20" id="cate-table">
            <div class="col-md-12">
                <div class="card">

                    <div class="tab-content pb-4" id="pills-tabContent">

                        <div class="tab-pane fade show active" id="pills-Sector" role="tabpanel"
                            aria-labelledby="pills-Sector-tab" tabindex="0">
                            <div class="px-3 py-4">
                                <div class="row align-items-center">
                                    <div class="col-sm-4 col-md-6 col-lg-8 mb-2 mb-sm-0">
                                        <h6 class="customHeading mb-0">Profile Detail</h6>
                                    </div>
                                </div>
                            </div>
                            <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                                class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100 horizontalTable">
                                <tr>
                                    <td>{{ \App\CPU\translate('Company Name')}}</td>
                                    <td>{{ $application->company?->name }}</td>
                                </tr>
                                <tr>
                                    <td>{{ \App\CPU\translate('Organization')}}</td>
                                    <td>{{ $application->company?->organization }}</td>
                                </tr>
                                <tr>
                                    <td>Phone</td>
                                    <td>{{ $application->company?->phone }}</td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td>{{ $application->company?->email }}</td>
                                </tr>
                                <tr>
                                    <td>Address</td>
                                    <td>{{ $application->company?->address }}</td>
                                </tr>
                                <tr>
                                    <td>Address In Other Language</td>
                                    <td>{{ $application->company?->address_other_language }}</td>
                                </tr>
                                <tr>
                                    <td>City</td>
                                    <td>{{ $application->company?->city }}</td>
                                </tr>
                                <tr>
                                    <td>Country</td>
                                    <td>{{ $application->company?->country }}</td>
                                </tr>
                                <tr>
                                    <td>Fax</td>
                                    <td>{{ $application->company?->fax }}</td>
                                </tr>
                                <tr>
                                    <td>Pincode</td>
                                    <td>{{ $application->company?->pincode }}</td>
                                </tr>
                                <tr>
                                    <td>Website</td>
                                    <td>{{ $application->company?->website }}</td>
                                </tr>
                            </table>
                            <div class="row">
                                <div class="col-lg-12">
                                    <hr>
                                    <div class="px-3 py-4">
                                        <div class="row align-items-center">
                                            <div class="col-sm-4 col-md-6 col-lg-8 mb-2 mb-sm-0">
                                                <h3 class="customHeading mb-0">Contact Person Detail</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <table
                                        class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100 horizontalTable">
                                        <tbody>
                                            @php $contactPerson = $application->company?->contact_person_details; @endphp
                                            @foreach ($contactPerson as $cKey => $contact)
                                                <tr>
                                                    <td>{{ ucwords($cKey) }}</td>
                                                    <td>{{ $contact }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-lg-12">
                                    <hr>
                                    <div class="px-3 py-4">
                                        <div class="row align-items-center">
                                            <div class="col-sm-4 col-md-6 col-lg-8 mb-2 mb-sm-0">
                                                <h3 class="customHeading mb-0">Parent Organization</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <table
                                        class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100 horizontalTable">
                                        <tbody>
                                            @php $parentOrganization = $application->company?->parent_organization; @endphp
                                            @foreach ($parentOrganization as $pKey => $porg)
                                                <tr>
                                                    <td>{{ ucwords($pKey) }}</td>
                                                    <td>{{ $porg }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-lg-12">
                                    <hr>
                                    <div class="px-3 py-4">
                                        <div class="row align-items-center">
                                            <div class="col-sm-4 col-md-6 col-lg-8 mb-2 mb-sm-0">
                                                <h3 class="customHeading mb-0">Invoice Address</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <table
                                        class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100 horizontalTable">
                                        <tbody>
                                            @php $parentOrganization = $application->company?->invoice_address; @endphp
                                            @foreach ($parentOrganization as $pKey => $porg)
                                                <tr>
                                                    <td>{{ ucwords($pKey) }}</td>
                                                    <td>{{ $porg }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-lg-12">
                                    <hr>
                                    <div class="px-3 py-4">
                                        <div class="row align-items-center">
                                            <div class="col-sm-4 col-md-6 col-lg-8 mb-2 mb-sm-0">
                                                <h3 class="customHeading mb-0">Ownership Details</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <table
                                        class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100 horizontalTable">
                                        <tbody>
                                            @php $parentOrganization = $application->company?->ownership_details; @endphp
                                            @foreach ($parentOrganization as $pKey => $porg)
                                                <tr>
                                                    <td>{{ ucwords($pKey) }}</td>
                                                    <td>{{ $porg }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="pills-Document" role="tabpanel" aria-labelledby="pills-Document-tab"
                            tabindex="0">
                            <div class="px-3 py-4">
                                <div class="row align-items-center">
                                    <div class="col-sm-4 col-md-6 col-lg-8 mb-2 mb-sm-0">
                                        <h6 class="customHeading mb-0">Documents</h6>
                                    </div>
                                </div>
                            </div>
                            <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                                class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100 horizontalTable">

                                <tr>
                                    <td>CAB legal entity evidence</td>
                                    <td>
                                        @if(isset($application->document) && !empty($application->document->legal_entity))
                                            <a href="{{$application->document->legal_entity_file}}" target="_blank">View</a>
                                        @else
                                            --No--
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>CAB Logo electronic copy</td>
                                    <td>
                                        @if(isset($application->document) && !empty($application->document->logo_electronic))
                                            <a href="{{$application->document->logo_electronic_file}}" target="_blank">View</a>
                                        @else
                                            --No--
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>CAB agreement</td>
                                    <td>
                                        @if(isset($application->document) && !empty($application->document->cab_agreement))
                                            <a href="{{$application->document->cab_agreement_file}}" target="_blank">View</a>
                                        @else
                                            --No--
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>PAC relevant assessment checklist report</td>
                                    <td>
                                        @if(isset($application->document) && !empty($application->document->assessment_checklist))
                                            <a href="{{$application->document->assessment_checklist_file}}"
                                                target="_blank">View</a>
                                        @else
                                            --No--
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Standard used by laboratory</td>
                                    <td>
                                        @if(isset($application->document) && !empty($application->document->standard))
                                            <a href="{{$application->document->standard_file}}" target="_blank">View</a>
                                        @else
                                            --No--
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Applicant’s quality system documentation</td>
                                    <td>
                                        @if(isset($application->document) && !empty($application->document->quality_documentation))
                                            <a href="{{$application->document->quality_documentation_file}}"
                                                target="_blank">View</a>
                                        @else
                                            --No--
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Copy of the relevant associated method(s)</td>
                                    <td>
                                        @if(isset($application->document) && !empty($application->document->relevant_associate))
                                            <a href="{{$application->document->relevant_associate_file}}"
                                                target="_blank">View</a>
                                        @else
                                            --No--
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Information regarding active participation with a successfully result of in
                                        Proficiency testing scheme</td>
                                    <td>
                                        @if(isset($application->document) && !empty($application->document->testing_scheme))
                                            <a href="{{$application->document->testing_scheme_file}}" target="_blank">View</a>
                                        @else
                                            --No--
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>A Proficiency testing plan covering all activities and a calibration plan covering the standard equipment used in the process for laboratory only.</td>
                                    <td>
                                        @if(isset($application->document) && !empty($application->document->proficiency_testing))
                                            <a href="{{$application->document->proficiency_testing_file}}"
                                                target="_blank">View</a>
                                        @else
                                            --No--
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Procedure for validation/verification of methods and validation data for tests requiring accreditation. Accreditation (for Labs.).
                                    </td>
                                    <td>
                                        @if(isset($application->document) && !empty($application->document->requiring_accreditation))
                                            <a href="{{$application->document->requiring_accreditation_file}}"
                                                target="_blank">View</a>
                                        @else
                                            --No--
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Detailed job description of applicant personnel seeking accreditation.</td>
                                    <td>
                                        @if(isset($application->document) && !empty($application->document->job_description))
                                            <a href="{{$application->document->job_description_file}}" target="_blank">View</a>
                                        @else
                                            --No--
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Risk analysis for confidentiality, impartiality & technical activities.</td>
                                    <td>
                                        @if(isset($application->document) && !empty($application->document->risk_analysis))
                                            <a href="{{$application->document->risk_analysis_file}}" target="_blank">View</a>
                                        @else
                                            --No--
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Risk analysis, List of auditors, List of clients, Job description (technical and quality manager,) MS certification Department.  </td>
                                    <td>
                                        @if(isset($application->document) && !empty($application->document->technical_and_quality))
                                            <a href="{{$application->document->technical_and_quality_file}}"
                                                target="_blank">View</a>
                                        @else
                                            --No--
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Signature</td>
                                    <td>
                                        @if(isset($application->document) && !empty($application->document->signature))
                                            <a href="{{$application->document->signature_file}}" target="_blank">View</a>
                                        @else
                                            --No--
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Selfie</td>
                                    <td>
                                        @if(isset($application->document) && !empty($application->document->selfie))
                                            <a href="{{$application->document->selfie_file}}" target="_blank">View</a>
                                        @else
                                            --No--
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Application Fee</td>
                                    <td>
                                        @if(isset($application->document) && !empty($application->document->application_fee))
                                            <a href="{{$application->document->application_fee_file}}" target="_blank">View</a>
                                        @else
                                            --No--
                                        @endif
                                    </td>
                                </tr>

                            </table>
                        </div>

                        <div class="tab-pane fade" id="pills-State" role="tabpanel" aria-labelledby="pills-State-tab"
                            tabindex="0">
                            <div class="px-3 py-4">
                                <div class="row align-items-center">
                                    <div class="col-sm-4 col-md-6 col-lg-8 mb-2 mb-sm-0">
                                        <h6 class="customHeading mb-0">Basic Info</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive ">
                                <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                                    class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100 horizontalTable">
                                    <thead>
                                        <tr>
                                            <th >-</th>
                                            <th >
                                                {{ \App\CPU\translate('Description of the main activities of the organization seeking accreditation') }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ \App\CPU\translate('Total no. of employees.')}}</td>
                                            <td>{{$application->basic_info->main_activities['no_of_employee'] ?? '-'}}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ \App\CPU\translate('Number of employees involved in area(s) seeking accreditation')}}
                                            </td>
                                            <td>{{$application->basic_info->main_activities['no_of_involve_emplpoyee'] ?? '-'}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>{{ \App\CPU\translate('(*) Attach an organization chart indicating the structure of the areas to be accredited and their relation to the rest of the organization.')}}
                                            </td>
                                            <td>-</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <hr>
                                <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                                    class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100 horizontalTable">
                                    <thead>
                                        <tr>
                                            <th colspan="2">
                                                {{ \App\CPU\translate('Indicate exactly how the name of your CAB appears on the accreditation certificate:') }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ \App\CPU\translate('In English.')}}</td>
                                            <td>{{$application->basic_info->name_of_cab_appears['in_english'] ?? '-'}}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ \App\CPU\translate('In Other (optional)')}}</td>
                                            <td>{{$application->basic_info->name_of_cab_appears['in_other'] ?? '-'}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <hr>
                                <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                                    class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100 horizontalTable">
                                    <thead>
                                        <tr>
                                            <th colspan="2">{{ \App\CPU\translate('Internal Audit and Management Review') }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ \App\CPU\translate('Last internal audit report')}}</td>
                                            <td>{{$application->basic_info->internal_audit_review['last_audit_report'] ?? '-'}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>{{ \App\CPU\translate('Last management review report')}}</td>
                                            <td>{{$application->basic_info->internal_audit_review['last_management_report'] ?? '-'}}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <hr>
                                <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                                    class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100 horizontalTable">
                                    <thead>
                                        <tr>
                                            <th colspan="2">{{ \App\CPU\translate('Information on Senior Staff') }}</th>
                                        </tr>
                                        <tr>
                                            <th colspan="2">
                                                {{ \App\CPU\translate('Name and position (Director level) of person authorizing this application') }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ \App\CPU\translate('Name')}}</td>
                                            <td>{{$application->basic_info->senior_staff_info['name'] ?? '-'}}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ \App\CPU\translate('Qualifications')}}</td>
                                            <td>{{$application->basic_info->senior_staff_info['qualifications'] ?? '-'}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>{{ \App\CPU\translate('Relevant Experience')}}</td>
                                            <td>{{$application->basic_info->senior_staff_info['relevent_experience'] ?? '-'}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>{{ \App\CPU\translate('Position within the organization')}}</td>
                                            <td>{{$application->basic_info->senior_staff_info['position_in_organisation'] ?? '-'}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>{{ \App\CPU\translate('Training relevant to application scope')}}</td>
                                            <td>{{$application->basic_info->senior_staff_info['traininig_relevant_scope'] ?? '-'}}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <hr>
                                <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                                    class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100 horizontalTable">
                                    <thead>
                                        <tr>
                                            <th colspan="2">{{ \App\CPU\translate('Technical/ Scheme Manager') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ \App\CPU\translate('Name')}}</td>
                                            <td>{{$application->basic_info->scheme_manager_info['name'] ?? '-'}}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ \App\CPU\translate('Qualifications')}}</td>
                                            <td>{{$application->basic_info->scheme_manager_info['qualifications'] ?? '-'}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>{{ \App\CPU\translate('Relevant Experience')}}</td>
                                            <td>{{$application->basic_info->scheme_manager_info['relevent_experience'] ?? '-'}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>{{ \App\CPU\translate('Position within the organization')}}</td>
                                            <td>{{$application->basic_info->scheme_manager_info['position_in_organisation'] ?? '-'}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>{{ \App\CPU\translate('Training relevant to application scope')}}</td>
                                            <td>{{$application->basic_info->scheme_manager_info['traininig_relevant_scope'] ?? '-'}}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <hr>
                                <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                                    class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100 horizontalTable">
                                    <thead>
                                        <tr>
                                            <th colspan="2">{{ \App\CPU\translate('Another Key Person') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ \App\CPU\translate('Name')}}</td>
                                            <td>{{$application->basic_info->another_key_person_info['name'] ?? '-'}}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ \App\CPU\translate('Qualifications')}}</td>
                                            <td>{{$application->basic_info->another_key_person_info['qualifications'] ?? '-'}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>{{ \App\CPU\translate('Relevant Experience')}}</td>
                                            <td>{{$application->basic_info->another_key_person_info['relevent_experience'] ?? '-'}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>{{ \App\CPU\translate('Position within the organization')}}</td>
                                            <td>{{$application->basic_info->another_key_person_info['position_in_organisation'] ?? '-'}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>{{ \App\CPU\translate('Training relevant to application scope')}}</td>
                                            <td>{{$application->basic_info->another_key_person_info['traininig_relevant_scope'] ?? '-'}}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <hr>
                                <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                                    class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100 horizontalTable">
                                    <thead>
                                        <tr>
                                            <th colspan="2">{{ \App\CPU\translate('Quality Manager') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ \App\CPU\translate('Name')}}</td>
                                            <td>{{$application->basic_info->quality_manager_info['name'] ?? '-'}}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ \App\CPU\translate('Qualifications')}}</td>
                                            <td>{{$application->basic_info->quality_manager_info['qualifications'] ?? '-'}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>{{ \App\CPU\translate('Relevant Experience')}}</td>
                                            <td>{{$application->basic_info->quality_manager_info['relevent_experience'] ?? '-'}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>{{ \App\CPU\translate('Position within the organization')}}</td>
                                            <td>{{$application->basic_info->quality_manager_info['position_in_organisation'] ?? '-'}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>{{ \App\CPU\translate('Training relevant to application scope')}}</td>
                                            <td>{{$application->basic_info->quality_manager_info['traininig_relevant_scope'] ?? '-'}}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <hr>
                                <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                                    class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100 horizontalTable">
                                    <thead>
                                        <tr>
                                            <th colspan="2">{{ \App\CPU\translate('local regulation') }}</th>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                {{ \App\CPU\translate('Please mention the current regulation / law that related to your organization activities according to the following table') }}
                                            </td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th>{{ \App\CPU\translate('Name of the regulation / law')}}</th>
                                            <th>{{ \App\CPU\translate('Issue date')}}</th>
                                            <th>{{ \App\CPU\translate('Item(s) related to the applied scope of accreditation')}}
                                            </th>
                                        </tr>

                                        @foreach ($application->basic_info?->local_regulation as $local)
                                            <tr>
                                                <td>{{$local['name_of_ragulation'] ?? '-'}}</td>
                                                <td>{{$local['issue_date'] ?? '-'}}</td>
                                                <td>{{$local['items'] ?? '-'}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <hr>
                                <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                                    class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100 horizontalTable">
                                    <thead>
                                        <tr>
                                            <th colspan="2">
                                                {{ \App\CPU\translate('Accreditation / Certifications: (including PAC accreditation)') }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th>{{ \App\CPU\translate('Name & address of Accreditation / certification body')}}
                                            </th>
                                            <th>{{ \App\CPU\translate('Scope of accreditation / certification')}}</th>
                                            <th colspan="2">{{ \App\CPU\translate('Period of accreditation/certification')}}
                                            </th>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td>{{ \App\CPU\translate('Start Date')}}</td>
                                            <td>{{ \App\CPU\translate('End Date')}}</td>
                                        </tr>
                                        @foreach ($application->basic_info->certifications as $certification)
                                            <tr>
                                                <td>{{$certification['certification_body'] ?? '-'}}</td>
                                                <td>{{$certification['certification'] ?? '-'}}</td>
                                                <td>{{$certification['certification_start_date'] ?? '-'}}</td>
                                                <td>{{$certification['certification_end_date'] ?? '-'}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <hr>
                                <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                                    class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100 horizontalTable">
                                    <thead>
                                        <tr>
                                            <th colspan="2">
                                                {{ \App\CPU\translate('Is CAB has Suspended or withdrawn by any AB?') }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ \App\CPU\translate('Status')}}</td>
                                            <td>{{$application->basic_info->suspended_or_withdrawn['status'] == 0 ? 'No' : 'Yes'}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>{{ \App\CPU\translate('Date of Suspension/Withdrawn')}}</td>
                                            <td>{{$application->basic_info->suspended_or_withdrawn['suspension_from_date'] ?? '-'}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>{{ \App\CPU\translate('Period of Suspension (in Case of Suspension)')}}</td>
                                            <td>{{$application->basic_info->suspended_or_withdrawn['suspension_to_date'] ?? '-'}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>{{ \App\CPU\translate('Reason for Suspension/Withdrawn')}}</td>
                                            <td>{{$application->basic_info->suspended_or_withdrawn['reason'] ?? '-'}}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ \App\CPU\translate('Application Accepted')}}</td>
                                            <td>-</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-Size" role="tabpanel" aria-labelledby="pills-Size-tab"
                            tabindex="0">
                            <div class="px-3 py-4">
                                <div class="row align-items-center">
                                    <div class="col-sm-4 col-md-6 col-lg-8 mb-2 mb-sm-0">
                                        <h6 class="customHeading mb-0">Accreditation Scheme</h6>
                                    </div>
                                </div>
                            </div>
                            @include('admin-views.applicants.' . str_replace(' ', '-', strtolower($application->scheme->title)))
                        </div>
                        
                        <div class="tab-pane fade" id="pills-Checklist" role="tabpanel"
                            aria-labelledby="pills-Checklist-tab" tabindex="0">
                            @include('admin-views.applicants.checklist')
                        </div>
                        <div class="tab-pane fade" id="pills-office-assessment" role="tabpanel"
                            aria-labelledby="pills-office-assessment-tab" tabindex="0">
                            @include('admin-views.applicants.office-assessment')
                        </div>
                        <div class="tab-pane fade d-none" id="pills-witness-assessment" role="tabpanel"
                            aria-labelledby="pills-witness-assessment-tab" tabindex="0">
                            @include('admin-views.applicants.witness-assessment')
                        </div>
                        <div class="tab-pane fade" id="pills-payment" role="tabpanel"
                            aria-labelledby="pills-payment-tab" tabindex="0">
                            @include('admin-views.applicants.payment')
                        </div>
                        <div class="tab-pane fade" id="pills-teamDetail" role="tabpanel"
                            aria-labelledby="pills-teamDetail-tab" tabindex="0">
                            @include('admin-views.applicants.team-detail')
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form class="adminRemark" method="post">
                    @csrf
                    <input type="hidden" name="application_id" value="{{ $application->id }}">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Admin Remark</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Remark</label>
                            <textarea name="remark" style="min-height: 115px;" class="form-control"  placeholder="Enter comment..." required>{{ $application->remark ?? '' }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-dark">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleAdminModal" tabindex="-1" role="dialog" aria-labelledby="exampleAdminModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form class="employeeRemark" method="post">
                    @csrf
                    <input type="hidden" name="application_id" value="{{ $application->id }}">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleAdminModalLabel"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="type" id="applicationType">
                        <input type="hidden" name="status" id="applicationStatus">
                        <div class="mb-2">
                            <label class="form-label fw-semibold">Date</label>
                            <input type="date" name="date" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Remark</label>
                            <textarea name="remark" style="min-height: 115px;" class="form-control"  placeholder="Enter comment..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-dark">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endpush

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"
        integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y"
        crossorigin="anonymous"></script>



<script>
$(document).ready(function () {
    $('.adminRemark').on('submit', function (e) {
        e.preventDefault();

        let form = $(this);
        let formData = form.serialize();
        let modal = form.closest('.modal');

        $.ajax({
            url: "{{ route('admin.application.admin-remark') }}", 
            type: "POST",
            data: formData,
            success: function(response) {
                if(response.status){
                    modal.modal('hide');

                    Swal.fire({
                        icon: 'success',
                        title: 'Saved!',
                        text: response.message ?? 'Remark saved successfully.',
                        confirmButtonColor: '#050505ff'
                    }).then(() => {

                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    });
                    
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message ?? 'Something went wrong!'
                    });

                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                    
                }
            },
            error: function(xhr) {
                let errMsg = xhr.responseJSON?.message ?? 'Something went wrong!';
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errMsg
                });
            }
        });
    });
});

$(document).ready(function () {
    $('.employeeRemark').on('submit', function (e) {
        e.preventDefault();

        let form = $(this);
        let formData = form.serialize();
        let modal = form.closest('.modal');

        $.ajax({
            url: "{{ route('admin.application.update-application-status') }}", 
            type: "POST",
            data: formData,
            success: function(response) {
                console.log(response);
                if(response.status){
                    modal.modal('hide');

                    Swal.fire({
                        icon: 'success',
                        title: 'Saved!',
                        text: response.message ?? 'Remark saved successfully.',
                        confirmButtonColor: '#050505ff'
                    }).then(() => {

                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    });
                    
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message ?? 'Something went wrong!'
                    });

                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                    
                }
            },
            error: function(xhr) {
                let errMsg = xhr.responseJSON?.message ?? 'Something went wrong!';
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errMsg
                });
            }
        });
    });
});

$(document).on('click', '#surveillanceButton', function () {

    Swal.fire({
        title: 'Are you sure?',
        text: 'You will not be able to revert this action after confirmation!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#f17814ff',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Start Surveillance',
        cancelButtonText: 'Cancel'
    }).then((result) => {

        if (result.isConfirmed) {

            $.ajax({
                url: "{{ route('admin.application.start-surveillance') }}", 
                type: 'POST',
                data: {
                    _token: $('meta[name="_token"]').attr('content'),
                    application_id: {{ $application->id }}
                },
                beforeSend: function () {
                    Swal.fire({
                        title: 'Please wait...',
                        text: 'Processing your request',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function (response) {

                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message ?? 'Surveillance started successfully'
                    }).then(() => {
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    });

                },
                error: function () {

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Something went wrong. Please try again.'
                    });

                }
            });
        }
    });
});


$(document).on('click','.startEndButton',function(){
    const type = $(this).data('type');
    const status = $(this).data('status');

    $('#applicationType').val(type);
    $('#applicationStatus').val(status);

    if (type === 'quality_check') {
        if (status == 1) {
            $('#exampleAdminModalLabel').html('Start Quality Check');
        } else if (status == 2) {
            $('#exampleAdminModalLabel').html('End Quality Check');
        }
    } 
    else if (type === 'accreditation_committee') {
        if (status == 1) {
            $('#exampleAdminModalLabel').html('Start Accreditation');
        } else if (status == 2) {
            $('#exampleAdminModalLabel').html('End Accreditation');
        }
    }
})
</script>    
@endpush