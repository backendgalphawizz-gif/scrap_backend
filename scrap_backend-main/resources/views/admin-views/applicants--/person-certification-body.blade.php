@php
    $testingDetail = \App\Model\SchemePersonCertification::where('application_id', $application->id)->first();
@endphp

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h4>For Person Certification Applicant</h4>
        </div>
        <div class="col-lg-12">
            <table class="table table-hover table-borderless table-thead-bordered table-align-middle card-table w-100 horizontalTable">
                <thead>
                    <tr>
                        <th>
                            Does the applying organization/ certification body operate additional locations / test centers:
                        </th>
                        <td><input type="checkbox" name="additional_location" {{ $testingDetail->additional_location == 1 ? 'checked' : '' }} disabled> Yes</td>
                        <td><input type="checkbox" name="additional_location" {{ $testingDetail->additional_location == 0 ? 'checked' : '' }} disabled> No</td>
                    </tr>
                    <tr>
                        <th colspan="3">Locations of the applicant organization/ certification body:</th>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <th>PO box / Code</th>
                        <th>City</th>
                        <th>Remark</th>
                        <th>Remark By</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($testingDetail->location_data as $location_data)
                        <tr>
                            <td>{{ $location_data['address'] }}</td>
                            <td>{{ $location_data['pincode'] }}</td>
                            <td>{{ $location_data['city'] }}</td>
                            <td>{{ $location_data['remark'] }}</td>
                            <td>{{ $location_data['remark_by'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <table class="table table-hover table-borderless table-thead-bordered table-align-middle card-table w-100 horizontalTable">
                <thead>
                    <tr>
                        <th>
                            Identify the certification scheme for persons for which accreditation is sought:
                        </th>
                        <td>{{ $testingDetail->scheme_for_person }}</td>
                    </tr>
                </thead>
            </table>
            <table class="table table-hover table-borderless table-thead-bordered table-align-middle card-table w-100 horizontalTable">
                <thead>
                    <tr>
                        <th>
                            General information
                        </th>
                    </tr>
                    <tr>
                        <th>
                            Is the certification body already accredited by another accreditation body (including abroad)?
                        </th>
                        <td><input type="checkbox" name="is_already_accrediation" {{ $testingDetail->is_already_accreditation == 1 ? 'checked' : '' }} disabled id=""> Yes</td>
                        <td><input type="checkbox" name="is_already_accrediation" {{ $testingDetail->is_already_accreditation == 0 ? 'checked' : '' }} disabled id=""> No</td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Name of AB: {{$testingDetail->already_accreditation_detail['name']}}</td>
                        <td>Scope of accreditation: {{$testingDetail->already_accreditation_detail['scope']}}</td>
                        <td>Date of last assessment: {{$testingDetail->already_accreditation_detail['last_date']}}</td>
                    </tr>
                    <tr>
                        <td>Has an application for accreditation been submitted to another accreditation body?</td>
                        <td><input type="checkbox" name="is_another_accreditation" {{ $testingDetail->is_another_accreditation == 1 ? 'checked' : '' }} disabled id=""> Yes</td>
                        <td><input type="checkbox" name="is_another_accreditation" {{ $testingDetail->is_another_accreditation == 0 ? 'checked' : '' }} disabled id=""> No</td>
                    </tr>
                    <tr>
                        <td>Name of the accreditation body</td>
                        <td>{{ $testingDetail->another_accreditation_detail['name'] }} </td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>Date of application</td>
                        <td>{{ $testingDetail->another_accreditation_detail['date'] }}</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>Fields of conformity evaluation which are accredited or for which accreditation has been applied:</td>
                        <td>{{ $testingDetail->another_accreditation_detail['applied_field'] }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Approvals and other recognitions of the certification body</td>
                        <td>{{ $testingDetail->another_accreditation_detail['approvals'] }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Has PAC already sent a quotation to the certification body?</td>
                        <td><input type="checkbox" name="is_already_quotation" {{ $testingDetail->is_already_quotation == 1 ? 'checked' : '' }} disabled id=""> Yes</td>
                        <td><input type="checkbox" name="is_already_quotation" {{ $testingDetail->is_already_quotation == 0 ? 'checked' : '' }} disabled id=""> No</td>
                    </tr>
                    <tr>
                        <td>If yes, indicate the reference number (if available)</td>
                        <td>{{ $testingDetail->reference_number }}</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            <table class="table table-hover table-borderless table-thead-bordered table-align-middle card-table w-100 horizontalTable">
                <thead>
                    <tr>
                        <th>
                            Documented structure to safeguard impartiality
                        </th>
                        <td><input type="checkbox" name="documented_structure" {{ $testingDetail->documented_structure == 1 ? 'checked' : '' }} disabled id=""> Yes</td>
                        <td><input type="checkbox" name="documented_structure" {{ $testingDetail->documented_structure == 0 ? 'checked' : '' }} disabled id=""> No</td>
                    </tr>
                    <tr>
                        <th>
                            Please provide details of the membership of the governing board / impartiality committee and the interests they represent:
                        </th>
                        <td>{{ $testingDetail->structure_detail }}</td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th colspan="3">
                            Staff of the applying organization /certification body
                        </th>
                    </tr>
                    <tr>
                        <th>
                            Number of
                        </th>
                        <th>
                            Staff
                        </th>
                        <th>
                            Other staff (part-time employees)
                        </th>
                    </tr>
                    @foreach($testingDetail->staff_applying_details as $staffDetail)
                        <tr>
                            <td></td>
                            <td>{{$staffDetail['staff']}}</td>
                            <td>{{$staffDetail['other_staff']}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <hr>
            <table class="table table-hover table-borderless table-thead-bordered table-align-middle card-table w-100 horizontalTable">
                <thead>
                    <tr>
                        <th>Certification Scheme</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>Accepted personal certification scheme including quality procedures for verification and certification, quality manual</th>
                        <td>{{ $testingDetail->personal_quality_manual }}</td>
                    </tr>
                    <tr>
                        <th>Owner / authors of the personal certification scheme (if different form applicant organization/ certification body):</th>
                        <td>{{ $testingDetail->owner_personal_scheme }}</td>
                    </tr>
                    <tr>
                        <th>Interested parties represented in the scheme (scheme committee)</th>
                        <td>{{ $testingDetail->scheme_committee }}</td>
                    </tr>
                    <tr>
                        <th>Is the scheme nationally / internationally accepted within the industry?</th>
                        <td>{{ $testingDetail->scheme_nationality }}</td>
                    </tr>
                </tbody>
            </table>

            <hr>
            <table class="table table-hover table-borderless table-thead-bordered table-align-middle card-table w-100 horizontalTable">
                <thead>
                    <tr>
                        <th>Quality system</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Do the applicant organization / the certification body comply with any standard for quality system?</td>
                        <td><input type="checkbox" name="documented_structure" {{ $testingDetail->quality_system[0]['option_1'] == 1 ? 'checked' : '' }} disabled id=""> Yes</td>
                        <td><input type="checkbox" name="documented_structure" {{ $testingDetail->quality_system[0]['option_1'] == 0 ? 'checked' : '' }} disabled id=""> No</td>
                    </tr>
                    <tr>
                        <td>If yes, which one</td>
                        <td>{{ $testingDetail->quality_system[0]['reference'] }}</td>
                    </tr>
                    <tr>
                        <td>Has a quality manager been appointed</td>
                        <td><input type="checkbox" name="documented_structure" {{ $testingDetail->quality_system[1]['option_2'] == 1 ? 'checked' : '' }} disabled id=""> Yes</td>
                        <td><input type="checkbox" name="documented_structure" {{ $testingDetail->quality_system[1]['option_2'] == 0 ? 'checked' : '' }} disabled id=""> No</td>
                    </tr>
                    <tr>
                        <td>If yes, name</td>
                        <td>{{ $testingDetail->quality_system[1]['reference'] }}</td>
                    </tr>
                    <tr>
                        <td>Is there a documented system for internal quality audits to ensure the compliance with ISO 17024?</td>
                        <td><input type="checkbox" name="documented_structure" {{ $testingDetail->quality_system[2]['option_3'] == 1 ? 'checked' : '' }} disabled id=""> Yes</td>
                        <td><input type="checkbox" name="documented_structure" {{ $testingDetail->quality_system[2]['option_3'] == 0 ? 'checked' : '' }} disabled id=""> No</td>
                    </tr>
                    <tr>
                        <td>Reference document</td>
                        <td>{{ $testingDetail->quality_system[2]['reference'] }}</td>
                    </tr>
                    <tr>
                        <td>Are there documented procedures to ensure confidentiality?</td>
                        <td><input type="checkbox" name="documented_structure" {{ $testingDetail->quality_system[3]['option_4'] == 1 ? 'checked' : '' }} disabled id=""> Yes</td>
                        <td><input type="checkbox" name="documented_structure" {{ $testingDetail->quality_system[3]['option_4'] == 0 ? 'checked' : '' }} disabled id=""> No</td>
                    </tr>
                    <tr>
                        <td>Reference document</td>
                        <td>{{ $testingDetail->quality_system[3]['reference'] }}</td>
                    </tr>
                    <tr>
                        <td>Are there procedures regarding the misuse of certificates?</td>
                        <td><input type="checkbox" name="documented_structure" {{ $testingDetail->quality_system[4]['option_5'] == 1 ? 'checked' : '' }} disabled id=""> Yes</td>
                        <td><input type="checkbox" name="documented_structure" {{ $testingDetail->quality_system[4]['option_5'] == 0 ? 'checked' : '' }} disabled id=""> No</td>
                    </tr>
                    <tr>
                        <td>Reference document</td>
                        <td>
                            {{ $testingDetail->quality_system[4]['reference'] }}
                        </td>
                    </tr>
                    <tr>
                        <td>Is a description of the certification system available in published form?</td>
                        <td><input type="checkbox" name="documented_structure" {{ $testingDetail->quality_system[5]['option_6'] == 1 ? 'checked' : '' }} disabled id=""> Yes</td>
                        <td><input type="checkbox" name="documented_structure" {{ $testingDetail->quality_system[5]['option_6'] == 0 ? 'checked' : '' }} disabled id=""> No</td>
                    </tr>
                    <tr>
                        <td>Reference document</td>
                        <td>{{ $testingDetail->quality_system[5]['reference'] }}</td>
                    </tr>
                </tbody>
            </table>

            <hr>
            <table class="table table-hover table-borderless table-thead-bordered table-align-middle card-table w-100 horizontalTable">
                <thead>
                    <tr>
                        <th colspan="4">Scope of application</th>
                    </tr>
                    <tr>
                        <td>S. No.</td>
                        <td>Certification Scheme for Persons</td>
                        <td>Sector</td>
                        <td>Method & Level</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($testingDetail->scope_of_application as $scopeOfApplication)
                        <tr>
                            <td>{{ $scopeOfApplication['s_no'] }}</td>
                            <td>{{ $scopeOfApplication['person'] }}</td>
                            <td>{{ $scopeOfApplication['sector'] }}</td>
                            <td>{{ $scopeOfApplication['method'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>