@php
    use App\Model\ApplicationPaymentDetail;
    use App\CPU\Helpers;
    use App\CPU\translate;

    $payment = ApplicationPaymentDetail::where('application_id', $application->id)->first();
@endphp

<div class="container-fluid">
    <div class="px-3 py-4">
        <div class="row align-items-center">
            <div class="col-sm-4 col-md-6 col-lg-8 mb-2 mb-sm-0">
                <h6 class="customHeading mb-0">Payment Details</h6>
            </div>
        </div>
    </div>
    @if($payment)
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <table class="table table-hover table-borderless table-thead-bordered table-align-middle card-table w-100 horizontalTable customTableProfile">
                        <thead>
                            <tr>
                                <th>{{ translate('Fee Type') }}</th>
                                <th>{{ translate('Amount') }}</th>
                                <th>{{ translate('Payment Slip') }}</th>
                                <th>{{ translate('Date/Time') }}</th>
                                <th>{{ translate('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Application Fees</td>
                                <td>{{($payment['application_fee']) ? '$'.$payment['application_fee'] : '--'}}</td>
                                <td>
                                    @if ($payment['application_fee_image'])
                                        <a href="{{asset($payment['application_fee_image'])}}" target="_blank">
                                            <img src="{{asset($payment['application_fee_image'])}}" class="w-50 h-50"/>
                                        </a>
                                    @else 
                                       -- 
                                    @endif
                                </td>
                                <td>{{($payment['application_fee_date']) ? \Carbon\Carbon::parse($payment['application_fee_date'])->format('d-M-Y h:i:A') : '--'}}</td>
                                <td>
                                    @if($payment['application_fee_status'] != '')
                                        <select name="status" data-type="application_fee" class="form-control feeStatus">
                                            <option value="0" {{($payment['application_fee_status'] == 0) ? 'selected' : ''}}>Pending</option>
                                            <option value="1" {{($payment['application_fee_status'] == 1) ? 'selected' : ''}}>Approve</option>
                                            <option value="2" {{($payment['application_fee_status'] == 2) ? 'selected' : ''}}>Reject</option>
                                        </select>
                                    @else
                                        --
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Document Review Fees</td>
                                <td>{{($payment['document_fee']) ? '$'.$payment['document_fee'] : '--'}}</td>
                                <td>
                                    @if ($payment['document_fee_image'])
                                        <a href="{{asset($payment['document_fee_image'])}}" target="_blank">
                                            <img src="{{asset($payment['document_fee_image'])}}" class="w-50 h-50"/>
                                        </a>
                                    @else 
                                       -- 
                                    @endif
                                </td>
                                <td>{{($payment['document_fee_date']) ? \Carbon\Carbon::parse($payment['document_fee_date'])->format('d-M-Y h:i:A') : '--'}}</td>
                                <td>
                                    @if($payment['document_fee_status'] != '')
                                        <select name="status" data-type="document_fee" class="form-control feeStatus">
                                            <option value="0" {{($payment['document_fee_status'] == 0) ? 'selected' : ''}}>Pending</option>
                                            <option value="1" {{($payment['document_fee_status'] == 1) ? 'selected' : ''}}>Approve</option>
                                            <option value="2" {{($payment['document_fee_status'] == 2) ? 'selected' : ''}}>Reject</option>
                                        </select>
                                    @else
                                        --
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Initial Assessment Fees</td>
                                <td>{{($payment['assessment_fee']) ? '$'.$payment['assessment_fee'] : '--'}}</td>
                                <td>
                                    @if ($payment['assessment_fee_image'])
                                        <a href="{{asset($payment['assessment_fee_image'])}}" target="_blank">
                                            <img src="{{asset($payment['assessment_fee_image'])}}" class="w-50 h-50"/>
                                        </a>
                                    @else 
                                       -- 
                                    @endif
                                </td>
                                <td>{{($payment['assessment_fee_date']) ? \Carbon\Carbon::parse($payment['assessment_fee_date'])->format('d-M-Y h:i:A') : '--'}}</td>
                                <td>
                                    @if($payment['assessment_fee_status'] != '')
                                        <select name="status" data-type="assessment_fee" class="form-control feeStatus">
                                            <option value="0" {{($payment['assessment_fee_status'] == 0) ? 'selected' : ''}}>Pending</option>
                                            <option value="1" {{($payment['assessment_fee_status'] == 1) ? 'selected' : ''}}>Approve</option>
                                            <option value="2" {{($payment['assessment_fee_status'] == 2) ? 'selected' : ''}}>Reject</option>
                                        </select>
                                    @else
                                        --
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Initial Technical Assessment Fees</td>
                                <td>{{($payment['technical_assessment_fee']) ? '$'.$payment['technical_assessment_fee'] : '--'}}</td>
                                <td>
                                    @if ($payment['technical_assessment_fee_image'])
                                        <a href="{{asset($payment['technical_assessment_fee_image'])}}" target="_blank">
                                            <img src="{{asset($payment['technical_assessment_fee_image'])}}" class="w-50 h-50"/>
                                        </a>
                                    @else 
                                       -- 
                                    @endif
                                </td>
                                <td>{{($payment['technical_assessment_fee_date']) ? \Carbon\Carbon::parse($payment['technical_assessment_fee_date'])->format('d-M-Y h:i:A') : '--'}}</td>
                                <td>
                                    @if($payment['technical_assessment_fee_status'] != '')
                                        <select name="status" data-type="technical_assessment_fee" class="form-control feeStatus">
                                            <option value="0" {{($payment['technical_assessment_fee_status'] == 0) ? 'selected' : ''}}>Pending</option>
                                            <option value="1" {{($payment['technical_assessment_fee_status'] == 1) ? 'selected' : ''}}>Approve</option>
                                            <option value="2" {{($payment['technical_assessment_fee_status'] == 2) ? 'selected' : ''}}>Reject</option>
                                        </select>
                                    @else
                                        --
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                        
            </div>
        </div>
    @else
        <h5 class="text-center text-muted py-5">No Data Found</h5>
    @endif
</div>

@push('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// const baseUrl = "{{ asset('') }}";

$(document).ready(function () {
    let activeOuterTab = localStorage.getItem('activeOuterTab');
    if (activeOuterTab) {
        let outerTabButton = $('#' + activeOuterTab);
        if (outerTabButton.length) {
            let tab = new bootstrap.Tab(outerTabButton[0]);
            tab.show();
        }
        localStorage.removeItem('activeOuterTab');
    }

});


$(document).on('change','.feeStatus', function(){
    var selectedValue = $(this).val();
    var selectedType = $(this).data('type');
    var applicationId = {{$application->id}};

    if(applicationId && selectedType && selectedValue){
       Swal.fire({
            title: "Are you sure?",
            text: "Do you want to change the status?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes",
            cancelButtonText: "No"
       }).then((result)=>{
            if(result.isConfirmed){
                $.ajax({
                    url:"{{ route('admin.application.update-payment-status') }}",
                    type: "POST",
                    data: { 
                        _token: "{{ csrf_token() }}",
                        applicationId:applicationId,
                        status: selectedValue,
                        type: selectedType
                    },
                    success: function(response){
                        if(response.status){
                            Swal.fire({
                                icon: 'success',
                                title: 'Status Updated!',
                                text: response.message ?? 'Payment Status Update successfully.',
                                confirmButtonColor: '#050505ff'
                            }).then(()=>{
                                let activeOuterTab = $('.applicantTab button.active').attr('id');
                                if (activeOuterTab) {
                                    localStorage.setItem('activeOuterTab', activeOuterTab);
                                    setTimeout(() => {
                                        location.reload();
                                    }, 1000);
                                }
                            })
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message ?? 'Payment Status Not Update.',
                                confirmButtonColor: '#050505ff'
                            }).then(()=>{
                                let activeOuterTab = $('.applicantTab button.active').attr('id');
                                if (activeOuterTab) {
                                    localStorage.setItem('activeOuterTab', activeOuterTab);
                                    setTimeout(() => {
                                        location.reload();
                                    }, 1000);
                                }
                            })
                        }
                    },
                    error: function(xhr){
                        let errMsg = xhr.responseJSON?.message ?? 'Something went wrong!';
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errMsg
                        });
                    } 
                })
            }
       })
    }

    
})
</script>

@endpush
