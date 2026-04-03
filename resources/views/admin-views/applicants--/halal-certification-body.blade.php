@php
    $testingDetail = \App\Model\SchemeHalalCertification::where('application_id', $application->id)->first();
@endphp

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <h4>Does CB has any other critical location(s) other than the main/ head office or branches where *key activities takes place then please specify the names of cities & countries where critical locations or branches are situated.</h4>
        </div>
        <div class="col-lg-2"><input type="checkbox" name="cb_has" {{ $testingDetail->critical_location == 1 ? 'checked' : '' }} disabled> Yes</div>
        <div class="col-lg-2"><input type="checkbox" name="cb_has" {{ $testingDetail->critical_location == 0 ? 'checked' : '' }} disabled> No</div>

        <div class="col-lg-12">
            <hr>
            <div class="table-responsive">
                <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                    class="table table-striped table-hover table-borderless table-thead-bordered table-align-middle card-table w-100 horizontalTable">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Location Type (such as Regional office, Branch, outsourced Location etc.)</th>
                            <th>City & Country</th>
                            <th>*Key Activities carried out at this location</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4">
                                Note:* Key activities include:
                                Policy formulation, process and/or procedure development, proceedings of safeguarding impartiality committee/scheme committee, application & contract review, (approval of, selection of, handling of contractual agreements with & monitoring of auditors/examiners/inspectors), (planning of and review/approval & decision on the results of audits/examinations/inspections) and preparation, release & control of certificates, Final decision on appeals and complaints.
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Please list down the type, name(s) & location(s) of establishments under supervision of the organization (i.e. slaughterhouses, manufacturers, service providers. etc….) which come under the scope of Halal certification:</th>
                        </tr>
                        <tr>
                            <th>No.</th>
                            <th>Type of Establishment *</th>
                            <th>Name of Establishments</th>
                            <th>Location/Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($testingDetail->location_details as $locationDetail)
                            <tr>
                                <td>{{ $locationDetail['s_no'] ?? '' }}</td>
                                <td>{{ $locationDetail['type'] ?? '' }}</td>
                                <td>{{ $locationDetail['name'] ?? '' }}</td>
                                <td>{{ $locationDetail['address'] ?? '' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <th>No Record Added</th>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>List the names of the authorized persons for signing the Halal certificates:</th>
                        </tr>
                        <tr>
                            <th>No.</th>
                            <th>Name of the authorized persons for signing the halal certificates</th>
                            <th>Signature</th>
                            <th>Contact Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($testingDetail->authorized_person_detail as $authorizedPerson)
                            <tr>
                                <td>{{ $authorizedPerson['s_no'] ?? '' }}</td>
                                <td>{{ $authorizedPerson['name'] ?? '' }}</td>
                                <td>{{ $authorizedPerson['signature'] ?? '' }}</td>
                                <td>{{ $authorizedPerson['contact'] ?? '' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">No Record Found.</td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Insert stamp & logo used on halal certificates:</th>
                        </tr>
                        <tr>
                            <th>Stamp</th>
                            <th>Logo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><img src="{{ asset($testingDetail->stamp_url->first()->file ?? '-') }}" alt=""></td>
                            <td><img src="{{ asset($testingDetail->logo_url->first()->file ?? '-') }}" alt=""></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th colspan="7">List the names of the authorized persons for signing the Halal certificates:</th>
                        </tr>
                        <tr>
                            <th>{{ __('title') }}</th>
                            <th>{{ __('name') }}</th>
                            <th>{{ __('mobile') }}</th>
                            <th>{{ __('email') }}</th>
                            <th>{{ __('qualification') }}</th>
                            <th>{{ __('experience') }}</th>
                            <th>{{ __('position') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $testingDetail->islamic_affairs['title'] ?? '' }}</td>
                            <td>{{ $testingDetail->islamic_affairs['name'] ?? '' }}</td>
                            <td>{{ $testingDetail->islamic_affairs['mobile'] ?? '' }}</td>
                            <td>{{ $testingDetail->islamic_affairs['email'] ?? '' }}</td>
                            <td>{{ $testingDetail->islamic_affairs['qualification'] ?? '' }}</td>
                            <td>{{ $testingDetail->islamic_affairs['experience'] ?? '' }}</td>
                            <td>{{ $testingDetail->islamic_affairs['position'] ?? '' }}</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="7">
                                Scope of accreditation & Geographical scope
                            </th>
                        </tr>
                        <tr>
                            <th colspan="7">
                                Please indicate the standard against which you are applying for PAC Accreditation of Halal product/service categories
                            </th>
                        </tr>
                        <tr>
                            <th colspan="7">
                                {{ $testingDetail->scope_of_accreditation ?? '-' }}
                            </th>
                        </tr>
                    </tfoot>

                </table>
            </div>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th colspan="4">
                                <h5>Identify the Halal product/service categories for which accreditation is sought:</h5>
                                <p>Halal product/service categories according to Table A.1 of UAE.S 2055-2 </p>
                            </th>
                        </tr>
                        <tr>
                            <th>{{ __('category_code') }}</th>
                            <th>{{ __('category') }}</th>
                            <th>{{ __('sectors') }}</th>
                            <th>{{ __('area') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($testingDetail->halal_product_table_1 as $halalTable1)
                            <tr>
                                <td>{{ $halalTable1['category_code'] ?? '' }}</td>
                                <td>{{ $halalTable1['category'] ?? '' }}</td>
                                <td>{{ $halalTable1['sectors'] ?? '' }}</td>
                                <td>{{ $halalTable1['area'] ?? '' }}</td>
                            </tr>
                        @empty
                            
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th colspan="5">
                                <h5>Identify the Halal product/service categories for which accreditation is sought::</h5>
                                <p>Halal product/service/process and/or management system categories Table A.1 of GSO 2055-2:2021</p>
                            </th>
                        </tr>
                        <tr>
                            <th>{{ __('Tick') }}</th>
                            <th>{{ __('Cluster') }}</th>
                            <th>{{ __('Category') }}</th>
                            <th>{{ __('Subcategory') }}</th>
                            <th>{{ __('Examples of included activities') }}</th>
                            <th>{{ __('Geographical Areas') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($testingDetail->halal_product_table_2 as $halalTable2)
                            <tr>
                                <td><input type="checkbox" name="check" checked disabled></td>
                                <td>{{ $halalTable2['cluster'] ?? '' }}</td>
                                <td>{{ $halalTable2['category_code'] ?? '' }}</td>
                                <td>{{ $halalTable2['category'] ?? '' }}</td>
                                <td>{{ $halalTable2['subcategory'] ?? '' }}</td>
                                <td>{{ $halalTable2['activity'] ?? '' }}</td>
                                <td>{{ $halalTable2['area'] ?? '' }}</td>
                            </tr>
                        @empty
                            
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th colspan="5">
                                <h5>Identify the Halal product/service categories for which accreditation is sought::</h5>
                                <p>Halal product/service/process and/or management system categories Table A.1 of OIC/SMIIC 2:2019</p>
                            </th>
                        </tr>
                        <tr>
                            <th>{{ __('Tick') }}</th>
                            <th>{{ __('Cluster') }}</th>
                            <th>{{ __('Category') }}</th>
                            <th>{{ __('Subcategory') }}</th>
                            <th>{{ __('Examples of included activities') }}</th>
                            <th>{{ __('Geographical Areas') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($testingDetail->halal_product_table_3 as $halalTable3)
                            <tr>
                                <td><input type="checkbox" name="check" checked disabled></td>
                                <td>{{ $halalTable3['cluster'] ?? '' }}</td>
                                <td>{{ $halalTable3['category_code'] ?? '' }}</td>
                                <td>{{ $halalTable3['category'] ?? '' }}</td>
                                <td>{{ $halalTable3['subcategory'] ?? '' }}</td>
                                <td>{{ $halalTable3['activity'] ?? '' }}</td>
                                <td>{{ $halalTable3['area'] ?? '' }}</td>
                            </tr>
                        @empty
                            
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

    </div>

</div>