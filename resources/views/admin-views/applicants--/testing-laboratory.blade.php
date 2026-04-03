@php
$testingDetail = \App\Model\SchemeTestingLaboratory::where('application_id', $application->id)->first();
@endphp

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h4>For Calibration Laboratory Applicant</h4>
        </div>
        @foreach($application->scheme->area as $area)

        <div class="col-lg-4">
            <label for=""> <input type="checkbox" disabled {{ in_array($area->id, explode(',', $testingDetail->area_ids)) ? 'checked' : '' }}> {{ $area->title }}</label>
        </div>
        @endforeach
    </div>
    <div class="table-responsive">
        <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
            class="table table-hover table-borderless table-thead-bordered table-align-middle card-table w-100 horizontalTable">
            <thead>
                <tr>
                    <th colspan="9">Scope of testing for which accreditation is sought</th>
                </tr>
                <tr>
                    <th>{{ \App\CPU\translate('Area Title')}}</th>
                    <th>{{ \App\CPU\translate('Discipline / Group')}}</th>
                    <th>{{ \App\CPU\translate('Materials / Products Tested')}}</th>
                    <th>{{ \App\CPU\translate('Component, parameter or characteristic tested / Specific Test Performed / Tests or type of tests performed')}}</th>
                    <th>{{ \App\CPU\translate('Test Method Specification and / or the techniques / equipment used')}}</th>
                    <th>{{ \App\CPU\translate('Range of Testing')}}</th>
                    <th>{{ \App\CPU\translate('Measurement Uncertainty')}}</th>
                    <th>{{ \App\CPU\translate('remark')}}</th>
                    <th>{{ \App\CPU\translate('remark_by')}}</th>
                </tr>
            </thead>
            <tbody>
                @if(is_array($testingDetail->scope_of_testing))
                @foreach ($testingDetail->scope_of_testing as $scope)
                <tr>
                    <td>{{ $scope['area_title'] ?? '-' }}</td>
                    <td>{{ $scope['discipline'] ?? '-' }}</td>
                    <td>{{ $scope['component_characteristic'] ?? '-' }}</td>
                    <td>{{ $scope['test_method'] ?? '-' }}</td>
                    <td>{{ $scope['range'] ?? '-' }}</td>
                    <td>{{ $scope['measurement'] ?? '-' }}</td>
                    <td>{{ $scope['remark'] ?? '-' }}</td>
                    <td>{{ $scope['remark_by'] ?? '-' }}</td>
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
    </div>

    <hr>
    <div class="table-responsive">
        <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
            class="table table-hover table-borderless table-thead-bordered table-align-middle card-table w-100 horizontalTable">
            <thead>
                <tr>
                    <th colspan="2">List the major items of equipment currently used for the types of tests</th>
                </tr>
                <tr>
                    <th>{{ \App\CPU\translate('Description of equipment (Include Manufacturer, Model & Serial number/ Code number)')}}</th>
                    <th>{{ \App\CPU\translate('Range/ Capacity of equipment and other relevant information')}}</th>
                    <th>{{ \App\CPU\translate('remark')}}</th>
                    <th>{{ \App\CPU\translate('remark_by')}}</th>
                </tr>
            </thead>
            <tbody>
                @if(is_array($testingDetail->list_of_equipment))
                @foreach ($testingDetail->list_of_equipment as $equipment)
                <tr>
                    <td>{{ $equipment['description'] ?? '-' }}</td>
                    <td>{{ $equipment['capacity'] ?? '-' }}</td>
                    <td>{{ $equipment['remark'] ?? '-' }}</td>
                    <td>{{ $equipment['remark_by'] ?? '-' }}</td>
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
    </div>

    <hr>
    <div class="tabl-responsive">
        <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
            class="table table-hover table-borderless table-thead-bordered table-align-middle card-table w-100 horizontalTable">
            <thead>
                <tr>
                    <th colspan="2">
                        Type of calibration for the calibration lab equipment
                    </th>
                </tr>
            </thead>
            <tbody>
                @if(is_array($testingDetail->lab_equipment))
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
                @endif
            </tbody>
        </table>
    </div>

    <hr>
    <div class="table-responsive">
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
                @if(is_array($testingDetail->calibration_sites))
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
                @endif
            </tbody>
        </table>
    </div>

    <hr>
    <div class="table-responsive">
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
                @if(is_array($testingDetail->calibration_sites_table_data))
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
                @endif
            </tbody>
        </table>

    </div>
</div>