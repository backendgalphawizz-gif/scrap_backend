@php
    $testingDetail = \App\Model\SchemeProficiencyTesting::where('application_id', $application->id)->first();
@endphp

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h4>For Proficiency Testing Provider Applicant</h4>
        </div>
        <div class="col-lg-12">
            <hr>
            <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                class="table table-striped table-hover table-borderless table-thead-bordered table-align-middle card-table w-100 horizontalTable">
                <thead>
                    <tr>
                        <th colspan="5">Handling of Main Activities</th>
                    </tr>
                    <tr>
                        <th colspan="5">Mention in the following table all information regarding activities done, done by whom, where they are done and contact details, providing that your organization (PTP) undertakes the full responsibility:</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>Activity / Services</th>
                        <th colspan="2" class="text-center">Done by</th>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>PTP</td>
                        <td>Externally provided product or services</td>
                        <td>Remark</td>
                        <td>Remark By</td>
                    </tr>
                    @forelse ($testingDetail->table_data as $activityData)
                        <tr>
                            <td>{{ $activityData['activity'] }}</td>
                            <td>{{ $activityData['ptp'] }}</td>
                            <td>{{ $activityData['product'] }}</td>
                            <td>{{ $activityData['remark'] }}</td>
                            <td>{{ $activityData['remark_by'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <th colspan="5">No Record Found.</th>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        
            <hr>
            <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                class="table table-striped table-hover table-borderless table-thead-bordered table-align-middle card-table w-100 horizontalTable">
                <thead>
                    <tr>
                        <th colspan="6">Externally provided product or services Information</th>
                    </tr>
                    <tr>
                        <th colspan="6">Please complete this table for all externally provided product or services with which the proficiency testing provider has formal arrangements for the production, testing, measurement, sampling, storage, and distribution of the PT item and for data analysis</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>Externally provided product or services Name</th>
                        <th>Address/ Contact details</th>
                        <td>Accreditation held (If applicable)</td>
                        <td>Activities/services provided</td>
                        <td>Remark</td>
                        <td>Remark By</td>
                    </tr>
                    @forelse ($testingDetail->externally_data['data'] as $activityData)
                        <tr>
                            <td>{{ $activityData['name'] }}</td>
                            <td>{{ $activityData['address'] }}</td>
                            <td>{{ $activityData['held'] }}</td>
                            <td>{{ $activityData['activities'] }}</td>
                            <td>{{ $activityData['remark'] }}</td>
                            <td>{{ $activityData['remark_by'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <th colspan="5">No Record Found.</th>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        
            <hr>
            <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                class="table table-striped table-hover table-borderless table-thead-bordered table-align-middle card-table w-100 horizontalTable">
                <thead>
                    <tr>
                        <th colspan="5">Scope of proficiency testing for which accreditation is sought</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>PT Item </th>
                        <th>Tests/ Properties measured</th>
                        <td>Scheme Title/ Type</td>
                        <td>Remark</td>
                        <td>Remark By</td>
                    </tr>
                    @forelse ($testingDetail->scope_of_proficiency as $activityData)
                        <tr>
                            <td>{{ $activityData['item'] }}</td>
                            <td>{{ $activityData['tests'] }}</td>
                            <td>{{ $activityData['scheme_type'] }}</td>
                            <td>{{ $activityData['remark'] }}</td>
                            <td>{{ $activityData['remark_by'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <th colspan="5">No Record Found.</th>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>
</div>