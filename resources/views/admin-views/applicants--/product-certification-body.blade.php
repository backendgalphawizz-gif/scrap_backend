@php
    $testingDetail = \App\Model\SchemeProductCertification::where('application_id', $application->id)->first();
@endphp

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h4>For Product Certification Body Applicant</h4>
            <h6>Conformity evaluation in the field (product, process and/or service groups)</h6>
            <p>{{ $testingDetail->conformity ?? '-' }}</p>
        </div>
        <div class="col-lg-12">
            <hr>
            <div class="table-responsive">
                <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                    class="table table-striped table-hover table-borderless table-thead-bordered table-align-middle card-table w-100 horizontalTable">
                    <thead>
                        <tr>
                            <td>{{ __('Product') }}</td>
                            <td>{{ __('Certification Scheme') }}</td>
                            <td>{{ __('Certification Procedures') }}</td>
                            <td>{{ __('Types Of Certification') }}</td>
                            <td>{{ __('Requirements') }}</td>
                            <td>{{ __('Remark') }}</td>
                            <td>{{ __('Remark by') }}</td>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($testingDetail->scope_of_product as $scope)
                            <tr>
                                <td>{{ $scope['product'] }}</td>
                                <td>{{ $scope['certification_scheme'] }}</td>
                                <td>{{ $scope['certification_procedures'] }}</td>
                                <td>{{ $scope['types_of_certification'] }}</td>
                                <td>{{ $scope['requirements'] }}</td>
                                <td>{{ $scope['remark'] }}</td>
                                <td>{{ $scope['remark_by'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <th colspan="7">No Record Found.</th>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <hr>
            <div class="table-responsive">
                <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                    class="table table-striped table-hover table-borderless table-thead-bordered table-align-middle card-table w-100 horizontalTable">
                    <thead>
                        <tr>
                            <th>Documented structure to safeguard impartiality</th>
                            <th><input type="checkbox" name="safeguard" disabled {{ $testingDetail->document_safeguard == '1' ? 'checked' : '' }}> Yes</th>
                            <th><input type="checkbox" name="safeguard" disabled {{ $testingDetail->document_safeguard == '0' ? 'checked' : '' }}> No</th>
                        </tr>
                        <tr>
                            <th colspan="3">{{ __('Who are the stakeholders represented in this structure (committee)?') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="3">{{ $testingDetail->committee_structure ?? '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <hr>
            <div class="table-responsive">
                <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                    class="table table-striped table-hover table-borderless table-thead-bordered table-align-middle card-table w-100 horizontalTable">
                    <thead>
                        <tr>
                            <th colspan="3">Does the certification body itself carry out the surveillance of products, processes and services in the fields of certification applied for?</th>
                            <td><input type="checkbox" name="safeguard" disabled {{ $testingDetail->surveillance_certification_applied == '1' ? 'checked' : '' }}> Yes</td>
                            <td><input type="checkbox" name="safeguard" disabled {{ $testingDetail->surveillance_certification_applied == '0' ? 'checked' : '' }}> No</td>
                        </tr>
                        <tr>
                            <th colspan="3">Does the certification body itself carry out tests of products, processes and services in the fields of certification applied for?</th>
                            <td><input type="checkbox" name="safeguard" disabled {{ $testingDetail->surveillance_certification_applied == '1' ? 'checked' : '' }}> Yes</td>
                            <td><input type="checkbox" name="safeguard" disabled {{ $testingDetail->surveillance_certification_applied == '0' ? 'checked' : '' }}> No</td>
                        </tr>
                        <tr>
                            <th colspan="3">Are the testing laboratories of the certification body are accredited?</th>
                            <td><input type="checkbox" name="safeguard" disabled {{ $testingDetail->surveillance_certification_applied == '1' ? 'checked' : '' }}> Yes</td>
                            <td><input type="checkbox" name="safeguard" disabled {{ $testingDetail->surveillance_certification_applied == '0' ? 'checked' : '' }}> No</td>
                        </tr>
                        <tr>
                            <th colspan="5">Which testing laboratories work for the certification body?</th>
                        </tr>
                        <tr>
                            <td>Name / Identification</td>
                            <td>Test fields</td>
                            <td>Accredited by</td>
                            <td>Remark</td>
                            <td>Remark by</td>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($testingDetail->testing_laboratory_data as $labData)
                            <tr>
                                <td>{{ $testingDetail['name'] ?? '-' }}</td>
                                <td>{{ $testingDetail['test'] ?? '-' }}</td>
                                <td>{{ $testingDetail['accredited'] ?? '-' }}</td>
                                <td>{{ $testingDetail['remark'] ?? '-' }}</td>
                                <td>{{ $testingDetail['remark_by'] ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <th colspan="5">No Record Found</th>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5">In the case of non-accredited subcontractors, in which way does the certification body make sure that it complies with the requirements of the concerning international documents (e.g., ISO/IEC 17025)?</td>
                        </tr>
                        <tr>
                            <td colspan="5">{{ $testingDetail->nonaccredited_subcontractors ?? '-' }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

</div>