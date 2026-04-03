@php
    $testingDetail = \App\Model\SchemeBiotechnologyBiobank::where('application_id', $application->id)->first();
@endphp


<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h4>Define the Category of the biobank is sought to be accredited: (Please tick the appropriate boxes)</h4>
        </div>
        @foreach($application->scheme->area as $area)
            <div class="col-lg-2">
                <label for=""><input type="checkbox" disabled {{ in_array($area->id, explode(',', $testingDetail->area_ids)) ? 'checked' : '' }}> {{ $area->title }}</label>
            </div>
        @endforeach
    </div>
    <hr>
    <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
        class="table table-hover table-borderless table-thead-bordered table-align-middle card-table w-100 horizontalTable">
        <thead>
            <tr>
                <th colspan="3">Define the Source of biological material that will be under accreditation is sought: (Please tick the appropriate boxes)</th>
            </tr>
        </thead>
        <tbody>
            @if(is_array($testingDetail->source_of_material))
                @foreach ($testingDetail->source_of_material as $source_material)
                    <tr>
                        <td><input type="checkbox" name="laboratory_sample" {{ isset($source_material['title']) && $source_material['title'] !='' ? 'checked' : '' }} disabled> Yes</td>
                        <td>{{ $source_material['title']??'-' }}</td>
                        <td>{{ $source_material['value']??'-' }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
    <hr>
    <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
        class="table table-hover table-borderless table-thead-bordered table-align-middle card-table w-100 horizontalTable">
        <thead>
            <tr>
                <th colspan="3">Activities of the biobank are sought to be accredited: (Please tick the appropriate boxes)</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    <div class="row">
        @if(!empty($testingDetail->activities_of_biobank))
            @foreach (explode(',', $testingDetail->activities_of_biobank) as $activity)
                <div class="col-lg-3">
                    <input type="checkbox" name="laboratory_sample" checked disabled> {{ $activity }}
                </div>
            @endforeach
        @endif
    </div>

    <hr>
    <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
        class="table table-hover table-borderless table-thead-bordered table-align-middle card-table w-100 horizontalTable">
        <thead>
            <tr>
                <th colspan="3">Scope of testing for which accreditation is sought:</th>
            </tr>
            <tr>
                <th>{{ \App\CPU\translate('area_title')}}</th>
                <th>{{ \App\CPU\translate('category')}}</th>
                <th>{{ \App\CPU\translate('subcategory')}}</th>
                <th>{{ \App\CPU\translate('activities')}}</th>
                <th>{{ \App\CPU\translate('storage_condition')}}</th>
                <th>{{ \App\CPU\translate('specifications')}}</th>
                <th>{{ \App\CPU\translate('remark')}}</th>
                <th>{{ \App\CPU\translate('remark_by')}}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($testingDetail->scope_of_testing??[] as $scope)
                <tr>
                    <td>{{ $scope['area_title'] ?? '-' }}</td>
                    <td>{{ $scope['category'] ?? '-' }}</td>
                    <td>{{ $scope['subcategory'] ?? '-' }}</td>
                    <td>{{ $scope['activities'] ?? '-' }}</td>
                    <td>{{ $scope['storage_condition'] ?? '-' }}</td>
                    <td>{{ $scope['specifications'] ?? '-' }}</td>
                    <td>{{ $scope['remark'] ?? '-' }}</td>
                    <td>{{ $scope['remark_by'] ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <hr>
    <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
        class="table table-hover table-borderless table-thead-bordered table-align-middle card-table w-100 horizontalTable">
        <thead>
            <tr>
                <th colspan="3">List of equipment currently used for Activities of the biobank:</th>
            </tr>
            <tr>
                <th>{{ \App\CPU\translate('Description of equipment (Include Manufacturer, Model& Serial number/Code number)')}}</th>
                <th>{{ \App\CPU\translate('Methodology and other relevant information')}}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($testingDetail->equipment_activities_of_biobank??[] as $scope)
                <tr>
                    <td>{{ $scope['equipment'] ?? '-' }}</td>
                    <td>{{ $scope['methodology'] ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <hr>
    <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
        class="table table-hover table-borderless table-thead-bordered table-align-middle card-table w-100 horizontalTable">
        <thead>
            <tr>
                <th colspan="3">List the names of the authorized staff supporting or performing bio banking activities</th>
            </tr>
            <tr>
                <th>{{ \App\CPU\translate('s_no')}}</th>
                <th>{{ \App\CPU\translate('Name of the authorized Staff for biobank')}}</th>
                <th>{{ \App\CPU\translate('Signature')}}</th>
                <th>{{ \App\CPU\translate('Contact Details')}}</th>
                <th>{{ \App\CPU\translate('Activities')}}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($testingDetail->staff_list??[] as $scope)
                <tr>
                    <td>{{ $scope['s_no'] ?? '-' }}</td>
                    <td>{{ $scope['name'] ?? '-' }}</td>
                    <td>{{ $scope['signature'] ?? '-' }}</td>
                    <td>{{ $scope['contact'] ?? '-' }}</td>
                    <td>{{ $scope['activity'] ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <hr>
    <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
        class="table table-hover table-borderless table-thead-bordered table-align-middle card-table w-100 horizontalTable">
        <thead>
            <tr>
                <th colspan="3">
                    <p>Does the organization maintain multiple sites performing bio banking activities:?</p>
                    <p><input type="radio" name="radiocheck" value="Yes" {{ $testingDetail->bio_bank_activity == 1 ? 'checked' : '' }} disabled /> Yes <input type="radio" name="radiocheck" value="No" {{ $testingDetail->bio_bank_activity == 0 ? 'checked' : '' }}  disabled /> No</p>
                    <p>If yes, please describe below with full addresses sites performing bio banking activities</p>
                </th>
            </tr>
            <tr>
                <th>{{ \App\CPU\translate('s_no')}}</th>
                <th>{{ \App\CPU\translate('Sites Performing Bio banking Activities')}}</th>
                <th>{{ \App\CPU\translate('address')}}</th>
                <th>{{ \App\CPU\translate('equipment')}}</th>
                <th>{{ \App\CPU\translate('Activities')}}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($testingDetail->bio_bank_activity['data']??[] as $scope)
                <tr>
                    <td>{{ $scope['s_no'] ?? '-' }}</td>
                    <td>{{ $scope['name'] ?? '-' }}</td>
                    <td>{{ $scope['address'] ?? '-' }}</td>
                    <td>{{ $scope['equipment'] ?? '-' }}</td>
                    <td>{{ $scope['activity'] ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

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
                <th colspan="3">Details of primary sample collection facilities</th>
            </tr>
            <tr>
                <th colspan="3">Please mention clearly with full addresses the primary sample collection facilities</th>
            </tr>
            <tr>
                <th>{{ \App\CPU\translate('s_no')}}</th>
                <th>{{ \App\CPU\translate('measured_quantity')}}</th>
                <th>{{ \App\CPU\translate('range')}}</th>
                <th>{{ \App\CPU\translate('calibration')}}</th>
                <th>{{ \App\CPU\translate('equipment_used')}}</th>
                <th>{{ \App\CPU\translate('remark')}}</th>
                <th>{{ \App\CPU\translate('remark_by')}}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($testingDetail->calibration_sites_table_data??[] as $scope)
                <tr>
                    <td>{{ $scope['s_no'] ?? '-' }}</td>
                    <td>{{ $scope['measured_quantity'] ?? '-' }}</td>
                    <td>{{ $scope['range'] ?? '-' }}</td>
                    <td>{{ $scope['calibration'] ?? '-' }}</td>
                    <td>{{ $scope['equipment_used'] ?? '-' }}</td>
                    <td>{{ $scope['remark'] ?? '-' }}</td>
                    <td>{{ $scope['remark_by'] ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>


</div>