
@php
    $calibarationDetail = \App\Model\SchemeCalibrationLaboratory::where('application_id', $application->id)->first();
@endphp

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h4>For Calibration Laboratory Applicant</h4>
        </div>
        @foreach($application->scheme->area as $area)
            
            <div class="col-lg-2">
                <label for=""> <input type="checkbox" disabled {{ in_array($area->id, explode(',', $calibarationDetail->area_ids)) ? 'checked' : '' }}> {{ $area->title }}</label>
            </div>
        @endforeach
    </div>
    <div class="row">
        <div class="col-lg-12">
            <hr>
            <div class="tabl-responsive">
                <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                    class="table table-hover table-borderless table-thead-bordered table-align-middle card-table w-100 horizontalTable">
                    <thead>
                        <tr>
                            <th>{{ \App\CPU\translate('Area Title')}}</th>
                            <th>{{ \App\CPU\translate('Discipline / Group')}}</th>
                            <th>{{ \App\CPU\translate('Parameters/ Measured qualtity / Device Under Calibration')}}</th>
                            <th>{{ \App\CPU\translate('Range')}}</th>
                            <th>{{ \App\CPU\translate('Calibration & Measurement Capability* (±)')}}</th>
                            <th>{{ \App\CPU\translate('Standard Equipment & method used')}}</th>
                            <th>{{ \App\CPU\translate('remark')}}</th>
                            <th>{{ \App\CPU\translate('remark_by')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($calibarationDetail->scope_of_calibration as $scope)
                            <tr>
                                <td>{{ $scope['area_title'] ?? '-' }}</td>
                                <td>{{ $scope['discipline'] ?? '-' }}</td>
                                <td>{{ $scope['parameteres'] ?? '-' }}</td>
                                <td>{{ $scope['range'] ?? '-' }}</td>
                                <td>{{ $scope['measurement'] ?? '-' }}</td>
                                <td>{{ $scope['method_used'] ?? '-' }}</td>
                                <td>{{ $scope['remark'] ?? '-' }}</td>
                                <td>{{ $scope['remark_by'] ?? '-' }}</td>
                            </tr>
                        @endforeach
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
                                List the major items of equipment currently used for the types of calibration
                            </th>
                        </tr>
                        <tr>
                            <th>{{ \App\CPU\translate('Description of equipment (Include Manufacturer, Model & Serial number / Code number)')}}</th>
                            <th>{{ \App\CPU\translate('Range / Capacity of equipment and other relevant information')}}</th>
                            <th>{{ \App\CPU\translate('Remarks')}}</th>
                            <th>{{ \App\CPU\translate('Remarks By')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($calibarationDetail->list_of_equipment as $equipment)
                            <tr>
                                <td>{{ $equipment['description'] ?? '-' }}</td>
                                <td>{{ $equipment['capacity'] ?? '-' }}</td>
                                <td>{{ $equipment['remark'] ?? '-' }}</td>
                                <td>{{ $equipment['remark_by'] ?? '-' }}</td>
                            </tr>
                        @endforeach
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
                        <tr>
                            <td>
                                <label for=""><input type="checkbox" disabled {{ $calibarationDetail->lab_equipment['external']??'' == 0 ? 'checked' : '' }}> External</label>
                            </td>
                            <td>
                                <label for=""><input type="checkbox" disabled {{ $calibarationDetail->lab_equipment['internal']??'' == 0 ? 'checked' : '' }}> Internal</label>
                            </td>
                            <td>
                                <label for="">Remark: {{ $calibarationDetail->lab_equipment['remark'] }}</label>
                            </td>
                            <td>
                                <label for="">Remark By: {{ $calibarationDetail->lab_equipment['remark_by'] }}</label>
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
                            <label for=""><input type="checkbox" disabled {{ $calibarationDetail->calibration_sites['customer_premises']??'' == 0 ? 'checked' : '' }}> Customer Premises</label>
                        </td>
                        <td>
                            <label for=""><input type="checkbox" disabled {{ $calibarationDetail->calibration_sites['mobile_facilities']??'' == 0 ? 'checked' : '' }}> Mobile Facilities</label>
                        </td>
                        <td>
                            <label for=""><input type="checkbox" disabled {{ $calibarationDetail->calibration_sites['temporary_sites']??'' == 0 ? 'checked' : '' }}> Temporary Sites</label>
                        </td>
                        <td>
                            <label for=""><input type="checkbox" disabled {{ $calibarationDetail->calibration_sites['collection_sites']??'' == 0 ? 'checked' : '' }}> Collection Sites</label>
                        </td>
                        <td>
                            <label for="">Other: {{ $calibarationDetail->calibration_sites['other'] }}</label>
                        </td>
                        <td>
                            <label for="">Remark: {{ $calibarationDetail->calibration_sites['remark'] }}</label>
                        </td>
                        <td>
                            <label for="">Remark By: {{ $calibarationDetail->calibration_sites['remark_by'] }}</label>
                        </td>
                    </tr>
                </tbody>
            </table> 
            
            <hr>
            <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                class="table table-hover table-borderless table-thead-bordered table-align-middle card-table w-100 horizontalTable">
                <thead>
                    <tr>
                        <th colspan="2">
                            Type of calibration sites
                        </th>
                    </tr>
                    <tr>
                        <th>{{ \App\CPU\translate('S.No')}}</th>
                        <th>{{ \App\CPU\translate('Discipline / Group')}}</th>
                        <th>{{ \App\CPU\translate('Measurand or Reference Material/Type of instrument or material to be calibrated or measured / Quantity Measured /Instrument')}}</th>
                        <th>{{ \App\CPU\translate('Calibration or Measurement Method or procedure')}}</th>
                        <th>{{ \App\CPU\translate('Measurement range and additional parameters where applicable(Range and Frequency)')}}</th>
                        <th>{{ \App\CPU\translate('Calibration and Measurement Capability(CMC)(±')}}</th>
                        <th>{{ \App\CPU\translate('Remarks')}}</th>
                        <th>{{ \App\CPU\translate('Remarks By')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($calibarationDetail->calibration_sites_table_data as $calibration_site)
                        <tr>
                            <td>{{ $calibration_site['s_no'] ?? '-' }}</td>
                            <td>{{ $calibration_site['discipline'] ?? '-' }}</td>
                            <td>{{ $calibration_site['measurement'] ?? '-' }}</td>
                            <td>{{ $calibration_site['calibration'] ?? '-' }}</td>
                            <td>{{ $calibration_site['range'] ?? '-' }}</td>
                            <td>{{ $calibration_site['measurement_capability'] ?? '-' }}</td>
                            <td>{{ $calibration_site['remark'] ?? '-' }}</td>
                            <td>{{ $calibration_site['remark_by'] ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</div>

