@php
    $testingDetail = \App\Model\SchemeForensicService::where('application_id', $application->id)->first();
@endphp

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h4>For Forensic Service Provider Applicant</h4>
            <p><small>The forensic services apply for accreditation in accordance with:</small></p>
            <label> <input type="checkbox" name="checkservice" checked disabled> {{ $testingDetail->service_apply_for }}</label>
        </div>
    </div>

    <hr>
    <div class="row">
        <div class="col-lg-12">
            <h5>Field for which accreditation is sought: (Please tick the appropriate boxes)</h5>
        </div>
        @foreach($application->scheme->area as $area)
            
            <div class="col-lg-2">
                <label for=""> <input type="checkbox" disabled {{ in_array($area->id, explode(',', $testingDetail->area_ids)) ? 'checked' : '' }}> {{ $area->title }}</label>
            </div>
        @endforeach
    </div>
    <hr>
    <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
        class="table table-hover table-borderless table-thead-bordered table-align-middle card-table w-100 horizontalTable">
        <thead>
            <tr>
                <th colspan="3">
                    <h4>Sampling that you carry out of sites</h4>
                </th>
            </tr>
            <tr>
                <th colspan="3"><p>Please indicate separately any Tests or Sampling that you carry out of sites, or in temporary or mobile facilities and complete all columns of the form below for such work. Your quality system and procedures must clearly indicate how you ensure that such work carried out away from your permanent premises meets the requirements of the standard.</p></th>
            </tr>
            <tr>
                <td>Type of test / Sample</td>
                <td>Temporary / Mobile</td>
                <td>Organization</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($testingDetail->sample_details as $scope)
                <tr>
                    <td>{{ $scope['sample'] ?? '-' }}</td>
                    <td>{{ $scope['mobile'] ?? '-' }}</td>
                    <td>{{ $scope['organization'] ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <hr>
    <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
        class="table table-hover table-borderless table-thead-bordered table-align-middle card-table w-100 horizontalTable">
        <thead>
            <tr>
                <th colspan="3">List of forensic tests and method of measurement currently used:</th>
            </tr>
            <tr>
                <td colspan="2">Forensic service provider laboratory according to ISO/IEC 17025</td>
                <td>{{ $testingDetail->forensic_test['title']??'-' }}</td>
            </tr>
            <tr>
                <td colspan="2">Discipline</td>
                <td>{{ $testingDetail->forensic_test['discipline']??'-' }}</td>
            </tr>
            <tr>
                <th>{{ \App\CPU\translate('Physical Location')}}</th>
                <th>{{ \App\CPU\translate('Materials / Sample Type')}}</th>
                <th>{{ \App\CPU\translate('Method of Measurement')}}</th>
                <th>{{ \App\CPU\translate('Standard Specifications / Techniques used')}}</th>
                <th>{{ \App\CPU\translate('Description of Equipment (include Manufacturer, Model & Serial number/ Code number)')}}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($testingDetail->forensic_test_details as $scope)
                <tr>
                    <td>{{ $scope['location'] ?? '-' }}</td>
                    <td>{{ $scope['sample'] ?? '-' }}</td>
                    <td>{{ $scope['method'] ?? '-' }}</td>
                    <td>{{ $scope['technique_used'] ?? '-' }}</td>
                    <td>{{ $scope['description'] ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <hr>
    <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
        class="table table-hover table-borderless table-thead-bordered table-align-middle card-table w-100 horizontalTable">
        <thead>
            <tr>
                <th>Forensic laboratory branches</th>
            </tr>
            <tr>
                <th>
                    <p>Does the Forensic Laboratory have branches?</p>
                    <label><input type="checkbox" name="forensic_lab" {{ $testingDetail->laboratory_branches == 1 ? 'checked' : '' }} disabled> Yes</label>
                    <label><input type="checkbox" name="forensic_lab" {{ $testingDetail->laboratory_branches == 0 ? 'checked' : '' }} disabled> No</label>
                </th>
            </tr>
            <tr>
                <th>{{ \App\CPU\translate('Do the forensic laboratory branches have separate management systems?')}}</th>
                <th>
                    <label><input type="checkbox" name="branch_separate_management" {{ $testingDetail->branch_separate_management == 1 ? 'checked' : '' }} disabled> Yes</label>
                    <label><input type="checkbox" name="branch_separate_management" {{ $testingDetail->branch_separate_management == 0 ? 'checked' : '' }} disabled> No</label>
                </th>
            </tr>
            <tr>
                <th>-</th>
                <th>-</th>
                <th colspan="2">{{ \App\CPU\translate('Department')}}: {{ $testingDetail->main_lab['department']??'' }}</th>
                <th colspan="2"></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ \App\CPU\translate('s_no')}}</td>
                <td>Branch Name</td>
                <td>Branch Address</td>
                <td>Branch Contact name</td>
                <td>Contact information (Phone/mail)</td>
            </tr>
            @foreach ($testingDetail->laboratory_branch_details??[] as $main_lab_data)
                <tr>
                    <td>{{ $main_lab_data['s_no'] ?? '-' }}</td>
                    <td>{{ $main_lab_data['name'] ?? '-' }}</td>
                    <td>{{ $main_lab_data['address'] ?? '-' }}</td>
                    <td>{{ $main_lab_data['contact_name'] ?? '-' }}</td>
                    <td>{{ $main_lab_data['contact_info'] ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>


    <hr>
    <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
        class="table table-hover table-borderless table-thead-bordered table-align-middle card-table w-100 horizontalTable">
        <thead>
            <tr>
                <th colspan="3">Laboratory Branches: Does the Laboratory have branches?</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><input type="checkbox" name="laboratory_branches" {{$testingDetail->laboratory_branches == 1 ? 'checked' : ''}} disabled> Yes</td>
                <td><input type="checkbox" name="laboratory_branches" {{$testingDetail->laboratory_branches == 0 ? 'checked' : ''}} disabled> No</td>
            </tr>
        </tbody>
    </table>

    <hr>
    <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
        class="table table-hover table-borderless table-thead-bordered table-align-middle card-table w-100 horizontalTable">
        <thead>
            <tr>
                <th colspan="3">Please mention clearly with full addresses the laboratory branches within the accreditation scope.</th>
            </tr>
            <tr>
                <th>{{ \App\CPU\translate('S. No.')}}</th>
                <th>{{ \App\CPU\translate('Branch')}}</th>
                <th>{{ \App\CPU\translate('Address')}}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($testingDetail->branch_address??[] as $scope)
                <tr>
                    <td>{{ $scope['s_no'] ?? '-' }}</td>
                    <td>{{ $scope['branch'] ?? '-' }}</td>
                    <td>{{ $scope['address'] ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>


    <hr>
    <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
        class="table table-hover table-borderless table-thead-bordered table-align-middle card-table w-100 horizontalTable">
        <thead>
            <tr>
                <th colspan="4">Branch Activities</th>
            </tr>
            <tr>
                <th colspan="4">List of medical scopes and major items of equipment currently used:</th>
            </tr>
            <tr>
                <th>{{ \App\CPU\translate('Branch Location')}}</th>
                <th>{{ \App\CPU\translate('Sample Type')}}</th>
                <th>{{ \App\CPU\translate('Discipline / Types of Tests')}}</th>
                <th colspan="2">{{ \App\CPU\translate('Standard Specifications / Techniques Used / Equipment')}}</th>
                <th>{{ \App\CPU\translate('Remark')}}</th>
                <th>{{ \App\CPU\translate('Remark By')}}</th>
            </tr>
            <tr>
                <th>-</th>
                <th>-</th>
                <th colspan="2">{{ \App\CPU\translate('Department')}}: {{ $testingDetail->branch_activites['department']??'' }}</th>
                <th colspan="2"></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>-</td>
                <td>-</td>
                <td>Name of test</td>
                <td>Method name and Reference</td>
                <td>Equipment name and SN</td>
                <td>-</td>
                <td>-</td>
            </tr>
            @foreach ($testingDetail->branch_activites['data']??[] as $branch_lab_data)
                <tr>
                    <td>{{ $branch_lab_data['lab_location'] ?? '-' }}</td>
                    <td>{{ $branch_lab_data['simple_type'] ?? '-' }}</td>
                    <td>{{ $branch_lab_data['name_of_test'] ?? '-' }}</td>
                    <td>{{ $branch_lab_data['method_name'] ?? '-' }}</td>
                    <td>{{ $branch_lab_data['equipment_name'] ?? '-' }}</td>
                    <td>{{ $branch_lab_data['remark'] ?? '-' }}</td>
                    <td>{{ $branch_lab_data['remark_by'] ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <hr>
    <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
        class="table table-hover table-borderless table-thead-bordered table-align-middle card-table w-100 horizontalTable">
        <thead>
            <tr>
                <th colspan="3">Do the laboratory branches have separate management systems?</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><input type="checkbox" name="branch_separate" {{$testingDetail->branch_separate == 1 ? 'checked' : ''}} disabled> Yes (branches should apply for accreditation with separate applications)</td>
                <td><input type="checkbox" name="branch_separate" {{$testingDetail->branch_separate == 0 ? 'checked' : ''}} disabled> No (Please specify the scope of each branch in the following tables)</td>
            </tr>
        </tbody>
    </table>










    <hr>
    <div class="tabl-responsive">
        <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
            class="table table-hover table-borderless table-thead-bordered table-align-middle card-table w-100 horizontalTable">
            <thead>
                <tr>
                    <th colspan="2">
                        Type of calibration for the medical lab equipment
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <label for=""><input type="checkbox" disabled {{ $testingDetail->lab_equipment['external']??'' == 0 ? 'checked' : '' }}> External</label>
                    </td>
                    <td>
                        <label for=""><input type="checkbox" disabled {{ $testingDetail->lab_equipment['internal']??'' == 0 ? 'checked' : '' }}> Internal</label>
                    </td>
                    <td>
                        <label for="">Remark: {{ $testingDetail->lab_equipment['remark']??'' }}</label>
                    </td>
                    <td>
                        <label for="">Remark By: {{ $testingDetail->lab_equipment['remark_by']??'' }}</label>
                    </td>
                </tr>
            </tbody>
        </table> 
    </div>

    <hr>
    <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
        class="table table-hover table-borderless table-thead-bordered table-align-middle card-table w-100 horizontalTable">
        <thead>
            <tr>
                <th colspan="2">
                    Type of calibration sites
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <label for=""><input type="checkbox" disabled {{ $testingDetail->calibration_sites['customer_premises']??'' == 0 ? 'checked' : '' }}> Customer Premises</label>
                </td>
                <td>
                    <label for=""><input type="checkbox" disabled {{ $testingDetail->calibration_sites['mobile_facilities']??'' == 0 ? 'checked' : '' }}> Mobile Facilities</label>
                </td>
                <td>
                    <label for=""><input type="checkbox" disabled {{ $testingDetail->calibration_sites['temporary_sites']??'' == 0 ? 'checked' : '' }}> Temporary Sites</label>
                </td>
                <td>
                    <label for=""><input type="checkbox" disabled {{ $testingDetail->calibration_sites['collection_sites']??'' == 0 ? 'checked' : '' }}> Collection Sites</label>
                </td>
                <td>
                    <label for="">Other: {{ $testingDetail->calibration_sites['other']??'' }}</label>
                </td>
                <td>
                    <label for="">Remark: {{ $testingDetail->calibration_sites['remark']??'' }}</label>
                </td>
                <td>
                    <label for="">Remark By: {{ $testingDetail->calibration_sites['remark_by']??'' }}</label>
                </td>
            </tr>
        </tbody>
    </table> 

    <hr>
    <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
        class="table table-hover table-borderless table-thead-bordered table-align-middle card-table w-100 horizontalTable">
        <thead>
            <tr>
                <th>{{ \App\CPU\translate('s_no')}}</th>
                <th>{{ \App\CPU\translate('Measurand  Quantity')}}</th>
                <th>{{ \App\CPU\translate('Range')}}</th>
                <th>{{ \App\CPU\translate('Calibration & Measurement Capability  Uncertainty')}}</th>
                <th colspan="2">{{ \App\CPU\translate('Brief Description of Measurement and Equipment Used')}}</th>
                <th>{{ \App\CPU\translate('remark')}}</th>
                <th>{{ \App\CPU\translate('remark_by')}}</th>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            @foreach ($testingDetail->calibration_sites_table_data as $equipment)
                <tr>
                    <td>{{ $equipment['s_no'] ?? '-' }}</td>
                    <td>{{ $equipment['measured_quantity'] ?? '-' }}</td>
                    <td>{{ $equipment['range'] ?? '-' }}</td>
                    <td>{{ $equipment['calibration'] ?? '-' }}</td>
                    <td>{{ $equipment['equipment_used'] ?? '-' }}</td>
                    <td>{{ $equipment['remark'] ?? '-' }}</td>
                    <td>{{ $equipment['remark_by'] ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>