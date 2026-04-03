@extends('layouts.back-end.app')
@section('title', 'Fee Structure List')

@section('content')
<div class="content container-fluid">

    <div class="mb-3">
        <h2 class="h1 mb-0 text-capitalize d-flex align-items-baseline gap-2 backbtndiv w-100">
            <a class="textfont-set" href="{{route('admin.dashboard.index')}}">
                <i class="tio-chevron-left"></i> Back
            </a>
            Fee Structure List
        </h2>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">

               
                <div class="table-responsive">
                  <table class="table table-hover table-borderless table-thead-bordered w-100">
                    <thead class="thead-light thead-50 text-capitalize">
                    <tr>
                        <th>SL</th>
                        <th>Scheme</th>

                        <th>Application Fee Structure</th>
                        <th>Document Fee Structure</th>
                        <th>Assessment Fee Structure</th>
                        <th>Technical Fee Structure</th>

                        <th>Action</th>
                    </tr>
                    </thead>

                    <tbody>
                        @php $i=1; @endphp

                        @foreach($fees as $fee)
                            @if($fee->scheme_id != 10)

                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $fee->scheme->title ?? '-' }}</td>

                            
                                <td>
                                    <strong>Normal:</strong> ${{ number_format($fee->application_fee,2) }} <br>
                                    <strong>Additional:</strong> ${{ number_format($fee->application_additional_fee,2) }}
                                </td>

                            
                                <td>
                                    <strong>Normal:</strong> ${{ number_format($fee->document_fee,2) }} <br>
                                    <strong>Additional:</strong> ${{ number_format($fee->document_additional_fee,2) }}
                                </td>

                            
                                <td>
                                    <strong>Normal:</strong> ${{ number_format($fee->assessment_fee,2) }} <br>
                                    <strong>Additional:</strong> ${{ number_format($fee->assessment_additional_fee,2) }} <br>
                                    <strong>Mandays:</strong> {{ $fee->assessment_mandays }} Days
                                </td>

                            
                                <td>
                                    <strong>Normal:</strong> ${{ number_format($fee->technical_assessment_fee,2) }} <br>
                                    <strong>Mandays:</strong> {{ $fee->technical_mandays }} Days
                                </td>

                                <td class="text-center">
                                    <button class="btn btn-outline--primary btn-sm"
                                        data-toggle="modal"
                                        data-target="#editFeeModal{{$fee->id}}">
                                        <i class="tio-edit"></i>
                                    </button>
                                </td>
                            </tr>

                        
                            <div class="modal fade" id="editFeeModal{{$fee->id}}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content">

                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Fee Structure</h5>
                                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                        </div>

                                        <form action="{{ route('admin.training.fee-update', $fee->id) }}" method="POST">
                                            @csrf

                                            <div class="modal-body row">

                                                <div class="form-group col-md-12">
                                                    <label>Scheme</label>
                                                    <input type="text" class="form-control" value="{{ $fee->scheme->title }}" disabled>
                                                </div>

                                            
                                                <div class="col-md-12"><h6><strong>Application Structure</strong></h6></div>

                                                <div class="form-group col-md-6">
                                                    <label>Normal Fee ($)</label>
                                                    <input type="number" maxlength="5" step="0.01"
                                                        oninput="this.value=this.value.replace(/[^0-9.]/g,'').slice(0,5)"
                                                        name="application_fee"
                                                        value="{{ $fee->application_fee }}" class="form-control">
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label>Additional Fee ($)</label>
                                                    <input type="number" maxlength="5" step="0.01"
                                                        oninput="this.value=this.value.replace(/[^0-9.]/g,'').slice(0,5)"
                                                        name="application_additional_fee"
                                                        value="{{ $fee->application_additional_fee }}" class="form-control">
                                                </div>

                                                
                                                <div class="col-md-12"><h6><strong>Document Structure</strong></h6></div>

                                                <div class="form-group col-md-6">
                                                    <label>Normal Fee ($)</label>
                                                    <input type="number" maxlength="5" step="0.01"
                                                        oninput="this.value=this.value.replace(/[^0-9.]/g,'').slice(0,5)"
                                                        name="document_fee"
                                                        value="{{ $fee->document_fee }}" class="form-control">
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label>Additional Fee ($)</label>
                                                    <input type="number" maxlength="5" step="0.01"
                                                        oninput="this.value=this.value.replace(/[^0-9.]/g,'').slice(0,5)"
                                                        name="document_additional_fee"
                                                        value="{{ $fee->document_additional_fee }}" class="form-control">
                                                </div>

                                            
                                                <div class="col-md-12"><h6><strong>Assessment Structure</strong></h6></div>

                                                <div class="form-group col-md-6">
                                                    <label>Normal Fee ($)</label>
                                                    <input type="number" maxlength="5" step="0.01"
                                                        oninput="this.value=this.value.replace(/[^0-9.]/g,'').slice(0,5)"
                                                        name="assessment_fee"
                                                        value="{{ $fee->assessment_fee }}" class="form-control">
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label>Additional Fee ($)</label>
                                                    <input type="number" maxlength="5" step="0.01"
                                                        oninput="this.value=this.value.replace(/[^0-9.]/g,'').slice(0,5)"
                                                        name="assessment_additional_fee"
                                                        value="{{ $fee->assessment_additional_fee }}" class="form-control">
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label>Mandays</label>
                                                    <input type="number" name="assessment_mandays"
                                                        value="{{ $fee->assessment_mandays }}" class="form-control">
                                                </div>

                                            
                                                <div class="col-md-12"><h6><strong>Technical Structure</strong></h6></div>

                                                <div class="form-group col-md-6">
                                                    <label>Technical Fee ($)</label>
                                                    <input type="number" maxlength="5" step="0.01"
                                                        oninput="this.value=this.value.replace(/[^0-9.]/g,'').slice(0,5)"
                                                        name="technical_assessment_fee"
                                                        value="{{ $fee->technical_assessment_fee }}" class="form-control">
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label>Technical Mandays</label>
                                                    <input type="number" name="technical_mandays"
                                                        value="{{ $fee->technical_mandays }}" class="form-control">
                                                </div>

                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn--primary">Save</button>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>

                            @endif
                        @endforeach
                    </tbody>
                </table>

                </div>

            </div>
        </div>
    </div>

</div>
@endsection
