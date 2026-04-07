@php
    $testingDetail = \App\Model\SchemeValidationVerification::where('application_id', $application->id)->first();
@endphp

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <h4>Describe the main activities of the V/VB and its experience conducting validation/verification activities. </h4>
        </div>
        <div class="col-lg-2">{{ $testingDetail->main_activities ?? '--'}}</div>

        <div class="col-lg-12">
            <hr>
            <h4>Documentation of Program Procedures</h4>
            <p>Provide a list here of the main procedures defined within the ISO/IEC 17029 & ISO 14065 & ISO 14064-3 &  ISO 14066 & VVB Programme/ Scheme requirements</p>
    
            <div class="table-responsive">
                <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                    class="table table-striped table-hover table-borderless table-thead-bordered table-align-middle card-table w-100 horizontalTable">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>List of enclosures</th>
                            <th>Attached</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ( $testingDetail->list_of_enclosures as $key=>$value)
                            <tr>
                                <td>{{ $value['list'] ?? '-' }}</td>
                                <td>{{ $value['title'] ?? '--' }}</td>
                                <td>{{ $value['value'] ?? '--' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <hr>
        
        <div class="col-lg-12">
            <hr>
            <h4>Number of Employees</h4>
    
            <div class="table-responsive">
                <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                    class="table table-striped table-hover table-borderless table-thead-bordered table-align-middle card-table w-100 horizontalTable">
                    <!-- <thead>
                        <tr>
                            <th>No.</th>
                            <th>List of enclosures</th>
                            <th>Attached</th>
                        </tr>
                    </thead> -->
                    <tbody>
                        @foreach ( $testingDetail->no_of_employees as $key=>$value)
                            <tr>
                                <td>{{ $value['list'] ?? '-' }}</td>
                                <td>{{ $value['title'] ?? '--' }}</td>
                                <td>{{ $value['value'] ?? '--' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>    

        <hr>
        
        <div class="col-lg-12">
            <hr>
            <h4>Confirmation of meeting Minimum Eligibility Requirements for accreditation</h4>
    
            <div class="table-responsive">
                <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                    class="table table-striped table-hover table-borderless table-thead-bordered table-align-middle card-table w-100 horizontalTable">
                    <!-- <thead>
                        <tr>
                            <th>No.</th>
                            <th>List of enclosures</th>
                            <th>Attached</th>
                        </tr>
                    </thead> -->
                    <tbody>
                        @foreach ( $testingDetail->meeting_minimum_eligibility as $key=>$value)
                            <tr>
                                <td>{{ $value['list'] ?? '-' }}</td>
                                <td>{{ $value['title'] ?? '--' }}</td>
                                <td>{{ $value['value'] ?? '--' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>    

        <hr>
        
        <div class="col-lg-8">
            <hr>
            <h4>Counties where the VVB will operate</h4>
        </div>
        <div class="col-lg-2">
            <hr>
            {{ $testingDetail->counties_vvb_operate ?? '--'}}
        </div>
        
        
        <hr>
        <div class="col-lg-8">
            <hr>
            <h4>Current Recognitions/Accreditations</h4>
            <p>List all relevant accreditations (e.g. ISO/IEC 17021, 17025, 17065, etc.) and any recognitions/accreditations related to V/V activities</p>
        </div>
        
        <div class="col-lg-2">
            <hr>
            {{ $testingDetail->current_accreditations ?? '--'}}
        </div>

        <hr>

        <div class="col-lg-12">
           <hr>
           <h4>Type of Validation or Verification activities for which accreditation is sought:</h4>

           <div class="table-responsive">
                <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                    class="table table-striped table-hover table-borderless table-thead-bordered table-align-middle card-table w-100 horizontalTable">
                    <tbody>
                        @foreach ( $testingDetail->type_of_validation_activities as $key=>$value)
                            <tr>
                                <td>{{ $value['list'] ?? '-' }}</td>
                                <td>{{ $value['title'] ?? '--' }}</td>
                                <td>
                                    <input type="checkbox" name="cb_has" {{ ($value['value']) == 'true' ? 'checked' : '' }} disabled>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div> 

        <hr>

        <div class="col-lg-12">
           <hr>
           <h4>Standard/ Normative documents and/ Or VVB Programme/ Scheme</h4>

           <div class="table-responsive">
                <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                    class="table table-striped table-hover table-borderless table-thead-bordered table-align-middle card-table w-100 horizontalTable">
                    <tbody>
                        @foreach ( $testingDetail->standard_documents as $key=>$value)
                            <tr>
                                <td>
                                    <input type="checkbox" name="cb_has" {{ ($value['value']) == 'true' ? 'checked' : '' }} disabled>
                                </td>
                                <td>{{ $value['title'] ?? '--' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div> 
        
        <hr>

        <h4>Sector As per IAF MD 14</h4>
        <div class="container-fluid d-flex">
            <div class="col-lg-6">

               <h4>Organization–Level Sector</h4>
    
               <div class="table-responsive">
                    <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                        class="table table-striped table-hover table-borderless table-thead-bordered table-align-middle card-table w-100 horizontalTable">
                        <tbody>
                            @foreach ( $testingDetail->organization_level_sector as $key=>$value)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="cb_has" {{ ($value['value']) == 'true' ? 'checked' : '' }} disabled>
                                    </td>
                                    <td>{{ $value['title'] ?? '--' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
           </div>

           <div class="col-lg-6">

               <h4>Project–Level Sector</h4>
    
               <div class="table-responsive">
                    <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                        class="table table-striped table-hover table-borderless table-thead-bordered table-align-middle card-table w-100 horizontalTable">
                        <tbody>
                            @foreach ( $testingDetail->project_level_sector as $key=>$value)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="cb_has" {{ ($value['value']) == 'true' ? 'checked' : '' }} disabled>
                                    </td>
                                    <td>{{ $value['title'] ?? '--' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
           </div>
        </div> 


    </div>
</div>        