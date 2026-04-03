@php
    $testingDetail = \App\Model\SchemeMedicalLaboratory::where('application_id', $application->id)->first();
@endphp

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h4>For Calibration Laboratory Applicant</h4>
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
                <th colspan="3">Laboratory Sample Collection</th>
            </tr>
            <tr>
                <th colspan="3">Does the laboratory have Sample Collection?</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><input type="checkbox" name="laboratory_sample" {{$testingDetail->laboratory_sample == 1 ? 'checked' : ''}} disabled> Yes</td>
                <td><input type="checkbox" name="laboratory_sample" {{$testingDetail->laboratory_sample == 0 ? 'checked' : ''}} disabled> No</td>
            </tr>
        </tbody>
    </table>

    <hr>
    <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
        class="table table-hover table-borderless table-thead-bordered table-align-middle card-table w-100 horizontalTable">
        <thead>
            <tr>
                <th colspan="3">Details of primary sample collection facilities</th>
            </tr>
            <tr>
                <th colspan="3">Please mention clearly with full addresses the primary sample collection facilities</th>
            </tr>
            <tr>
                <th>{{ \App\CPU\translate('S. No.')}}</th>
                <th>{{ \App\CPU\translate('Primary sample collection facility')}}</th>
                <th>{{ \App\CPU\translate('Address')}}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($testingDetail->primary_sample_collections as $scope)
                <tr>
                    <td>{{ $scope['s_no'] ?? '-' }}</td>
                    <td>{{ $scope['sample_collection'] ?? '-' }}</td>
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
                <th colspan="4">Main Lab Activities</th>
            </tr>
            <tr>
                <th colspan="4">List of medical scopes and major items of equipment currently used</th>
            </tr>
            <tr>
                <th>{{ \App\CPU\translate('Main Lab Location')}}</th>
                <th>{{ \App\CPU\translate('Sample Type')}}</th>
                <th>{{ \App\CPU\translate('Discipline / Types of Tests')}}</th>
                <th colspan="2">{{ \App\CPU\translate('Standard Specifications / Techniques Used / Equipment')}}</th>
                <th>{{ \App\CPU\translate('Remark')}}</th>
                <th>{{ \App\CPU\translate('Remark By')}}</th>
            </tr>
            <tr>
                <th>-</th>
                <th>-</th>
                <th colspan="2">{{ \App\CPU\translate('Department')}}: {{ $testingDetail->main_lab['department'] }}</th>
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
            @foreach ($testingDetail->main_lab['data']??[] as $main_lab_data)
                <tr>
                    <td>{{ $main_lab_data['lab_location'] ?? '-' }}</td>
                    <td>{{ $main_lab_data['simple_type'] ?? '-' }}</td>
                    <td>{{ $main_lab_data['name_of_test'] ?? '-' }}</td>
                    <td>{{ $main_lab_data['method_name'] ?? '-' }}</td>
                    <td>{{ $main_lab_data['equipment_name'] ?? '-' }}</td>
                    <td>{{ $main_lab_data['remark'] ?? '-' }}</td>
                    <td>{{ $main_lab_data['remark_by'] ?? '-' }}</td>
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
            @foreach ($testingDetail->branch_address as $scope)
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
                <th colspan="2">{{ \App\CPU\translate('Department')}}: {{ $testingDetail->branch_activites['department'] }}</th>
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
                        <label for="">Remark: {{ $testingDetail->lab_equipment['remark'] }}</label>
                    </td>
                    <td>
                        <label for="">Remark By: {{ $testingDetail->lab_equipment['remark_by'] }}</label>
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
                    <label for="">Other: {{ $testingDetail->calibration_sites['other'] }}</label>
                </td>
                <td>
                    <label for="">Remark: {{ $testingDetail->calibration_sites['remark'] }}</label>
                </td>
                <td>
                    <label for="">Remark By: {{ $testingDetail->calibration_sites['remark_by'] }}</label>
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