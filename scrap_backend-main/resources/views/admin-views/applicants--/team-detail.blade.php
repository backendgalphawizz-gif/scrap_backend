@php
    use App\Model\ApplicationAssessmentStatus;
    use App\CPU\Helpers;
    use App\CPU\translate;

    $status = ApplicationAssessmentStatus::with('startby','endby')->where('application_id', $application->id)->get();
@endphp
<div class="container-fluid">
    <div class="px-3 py-4">
        <div class="row align-items-center">
            <div class="col-sm-4 col-md-6 col-lg-8 mb-2 mb-sm-0">
                <h6 class="customHeading mb-0">Team Details</h6>
            </div>
        </div>
    </div>
    @if($application)
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <table class="table table-hover table-borderless table-thead-bordered table-align-middle card-table w-100 horizontalTable customTableProfile">
                        <thead>
                            <tr>
                                <th>{{ translate('Role') }}</th>
                                <th>{{ translate('Team Lead') }}</th>
                                <th>{{ translate('Team Member') }}</th>
                                <th>{{ translate('Status') }}</th>
                                <th>{{ translate('COI(Conflict of Interest)') }}</th>
                                <th>{{ translate('Action') }}</th>
                                <th>{{ translate('Chat') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            <tr>
                                <td>Assessor/Auditor</td>
                                <td>{{!empty($application->auditor->name) ? $application->auditor->name : '--'}}</td>
                                <td>
                                    @if(!empty($application->auditor_team->pluck('name')->toArray()))
                                        {{ implode(', ', $application->auditor_team->pluck('name')->toArray()) }}
                                    @else
                                       -- 
                                    @endif
                                </td>
                                <td>
                                    @if($application->auditor_status == '0')
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif($application->auditor_status == '1')
                                        <span class="badge badge-primary">Start</span>
                                    @elseif($application->auditor_status == '2')
                                        <span class="badge badge-success">Complete</span>
                                    @else
                                        --
                                    @endif
                                </td>
                                <td>
                                    @if($application->client_auditor_team_status == 0)
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif($application->client_auditor_team_status == 1)
                                        <span class="badge badge-success">Approved</span>
                                    @else
                                        <span class="badge badge-danger">Reject</span>
                                    @endif    
                                    <br />
                                    Remark : {{ $application->client_auditor_team_remark ?? '-' }}
                                </td>
                                <td>
                                    {{-- @if($application->auditor_status < 2) --}}
                                        <button type="button" data-id="{{ $application->id }}" data-type="Assessor" class="view-application btn btn--primary">Allot Team</button>
                                    {{-- @endif --}}
                                </td>
                                <td>
                                    @if(auth('admin')->user()->is_admin())
                                        <a href="{{ route('admin.application-chat.index', ['auditor', $application->id]) }}" class="btn btn-warning btn-sm">
                                            <i class="tio-chat"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Office Assessement Team</td>
                                <td>{{!empty($application->office_assessment->name) ? $application->office_assessment->name : '--'}}</td>
                                <td>
                                    @if(!empty($application->office_assessment_team->pluck('name')->toArray()))
                                        {{ implode(', ', $application->office_assessment_team->pluck('name')->toArray()) }}
                                    @else
                                       -- 
                                    @endif
                                </td>
                                <td>
                                    @if($application->office_assessment_status == '0')
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif($application->office_assessment_status == '1')
                                        <span class="badge badge-primary">Start</span>
                                    @elseif($application->office_assessment_status == '2')
                                        <span class="badge badge-success">Complete</span>
                                    @else
                                        {{ "--" }}
                                    @endif
                                </td>
                                <td>
                                    @if ($application->client_office_assessment_team_status != null)
                                        @if($application->client_office_assessment_team_status == 0)
                                            <span class="badge badge-warning">Pending</span>
                                        @elseif($application->client_office_assessment_team_status == 1)
                                            <span class="badge badge-success">Approved</span>
                                        @else
                                            <span class="badge badge-danger">Reject</span>
                                        @endif    
                                        <br />
                                        Remark : {{ $application->client_office_assessment_team_remark ?? '-' }}
                                    @else 
                                    --
                                    @endif
                                </td>
                                <td>
                                    @if($application->auditor_status == 2 && $application->office_assessment_status < 2)
                                        <button type="button" data-id="{{ $application->id }}" data-type="office_assessment" class="view-application btn btn--primary"> Allot Team</button>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $officeAssessmentIds = $application->office_assessment_team->pluck('id')->toArray()
                                    @endphp
                                    @if((auth('admin')->user()->is_admin() || in_array(auth('admin')->user()->id, $officeAssessmentIds)) && $application->auditor_status == '2')
                                        <a href="{{ route('admin.application-chat.index', ['office_assessment', $application->id]) }}" class="btn btn-warning btn-sm">
                                            <i class="tio-chat"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Witness Team</td>
                                <td>{{!empty($application->witness_assessment->name) ? $application->witness_assessment->name : '--'}}</td>
                                <td>
                                    @if(!empty($application->witness_assessment_team->pluck('name')->toArray()))
                                        {{ implode(', ', $application->witness_assessment_team->pluck('name')->toArray()) }}
                                    @else
                                       -- 
                                    @endif
                                </td>
                                <td>
                                    @if($application->witness_assessment_status == '0')
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif($application->witness_assessment_status == '1')
                                        <span class="badge badge-primary">Start</span>
                                    @elseif($application->witness_assessment_status == '2')
                                        <span class="badge badge-success">Complete</span>
                                    @else
                                        {{ "--" }}
                                    @endif
                                </td>
                                <td>
                                    @if ($application->client_witness_assessment_team_status != null)
                                        @if($application->client_witness_assessment_team_status == 0)
                                            <span class="badge badge-warning">Pending</span>
                                        @elseif($application->client_witness_assessment_team_status == 1)
                                            <span class="badge badge-success">Approved</span>
                                        @else
                                            <span class="badge badge-danger">Reject</span>
                                        @endif    
                                        <br />
                                        Remark : {{ $application->client_witness_assessment_team_remark ?? '-' }}
                                    @else
                                    --
                                    @endif
                                </td>
                                <td>
                                    @if($application->office_assessment_status == '2' && $application->witness_assessment_status < 2)
                                        <button type="button" data-id="{{ $application->id }}" data-type="witness_assessment" class="view-application btn btn--primary"> Allot Team</button>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $witnessAssessmentIds = $application->witness_assessment_team->pluck('id')->toArray()
                                    @endphp
                                    @if((auth('admin')->user()->is_admin() || in_array(auth('admin')->user()->id, $witnessAssessmentIds)) && $application->office_assessment_status == '2')
                                        <a href="{{ route('admin.application-chat.index', ['office_assessment', $application->id]) }}" class="btn btn-warning btn-sm">
                                            <i class="tio-chat"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Quality/Technical</td>
                                <td>{{!empty($application->quality->name) ? $application->quality->name : '--'}}</td>
                                <td>
                                    @if(!empty($application->quality_team->pluck('name')->toArray()))
                                        {{ implode(', ', $application->quality_team->pluck('name')->toArray()) }}
                                    @else 
                                       -- 
                                    @endif
                                </td>
                                <td>
                                    @if($application->quality_status == '0')
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif($application->quality_status == '1')
                                        <span class="badge badge-primary">Start</span>
                                    @elseif($application->quality_status == '2')
                                        <span class="badge badge-success">Complete</span>
                                    @else
                                        --
                                    @endif
                                </td>
                                <td> N/A</td>
                                <td>
                                    @if($application->witness_assessment_status == '2' && $application->quality_status < 2)
                                        <button type="button" data-id="{{ $application->id }}" data-type="Quality" class="view-application btn btn--primary"> Allot Team</button>
                                    @endif
                                </td>
                                <td>
                                    @if((auth('admin')->user()->is_admin() || in_array(auth('admin')->user()->id, $qualityIds)) && $application->witness_assessment_status == '2')
                                        <a href="{{ route('admin.application-chat.index', ['quality', $application->id]) }}" class="btn btn-warning btn-sm">
                                            <i class="tio-chat"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Accreditation Committee</td>
                                <td>{{!empty($application->accreditation->name) ? $application->accreditation->name : '--'}}</td>
                                <td>
                                    @if(!empty($application->accreditation_team->pluck('name')->toArray()))
                                        {{ implode(', ', $application->accreditation_team->pluck('name')->toArray()) }}
                                    @else 
                                       -- 
                                    @endif
                                </td>
                                <td>
                                    @if($application->accreditation_status == '0')
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif($application->accreditation_status == '1')
                                        <span class="badge badge-primary">Start</span>
                                    @elseif($application->accreditation_status == '2')
                                        <span class="badge badge-success">Complete</span>
                                    @else
                                        --
                                    @endif
                                </td>
                                <td> N/A</td>
                                <td>
                                    @if($application->quality_status == 2 && $application->accreditation_status < 2)
                                        <button type="button" data-id="{{ $application->id }}" data-type="Accreditation" class="view-application btn btn--primary"> Allot Team</button>
                                    @endif
                                </td>
                                <td>
                                    @if((auth('admin')->user()->is_admin() || in_array(auth('admin')->user()->id, $accreditationIds)) && $application->quality_status == '2')
                                        <a href="{{ route('admin.application-chat.index', ['accreditation', $application->id]) }}" class="btn btn-warning btn-sm">
                                            <i class="tio-chat"></i>
                                        </a>
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

<div class="container-fluid">
    <div class="px-3 py-4">
        <div class="row align-items-center">
            <div class="col-sm-4 col-md-6 col-lg-8 mb-2 mb-sm-0">
                <h6 class="customHeading mb-0">Assessment Team Status</h6>
            </div>
        </div>
    </div>
    @if($status)
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <table class="table table-hover table-borderless table-thead-bordered table-align-middle card-table w-100 horizontalTable customTableStartEnd">
                        <thead>
                            <tr>
                                <th>{{ translate('Team Type') }}</th>
                                <th>{{ translate('Start Detail') }}</th>
                                <th>{{ translate('End Detail') }}</th>
                                <th>{{ translate('Remark') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($status as $key=>$value )
                                <tr>
                                    <td>{{ ucwords(str_replace('_',' ',$value['type']))  }}</td>
                                    <td>
                                        <p><b>Start Date : </b>{{ $value['start_date'] }}</p><br />
                                        <p><b>Selfie : </b>  
                                        @if ($value['start_selfie'])
                                            <a href="{{asset($value['start_selfie'])}}" target="_blank">
                                                <!-- <img src="{{asset($value['start_selfie'])}}" class="w-50 h-50"/> -->
                                                View Image
                                            </a>
                                        @else 
                                        -- 
                                        @endif
                                        </p>
                                        <br />
                                        <p><b>Location : </b>
                                        @if($value['start_latitude'] && $value['start_longitude'])
                                        <a href="https://www.google.com/maps/search/?api=1&query={{ $value['start_latitude'] }},{{ $value['start_longitude'] }}" target="_blank">
                                            View Location on Map
                                        </a>
                                        @endif
                                        </p>
                                        <br />
                                        <p><b>Start By : </b>
                                        {{!empty($value->startby->name) ? $value->startby->name : '--'}}</p>
                                        
                                    </td>
                                    <td>
                                        <p><b>End Date : </b>{{ $value['end_date'] }}</p><br />
                                        <p><b>Selfie : </b>  
                                        @if ($value['end_selfie'])
                                            <a href="{{asset($value['end_selfie'])}}" target="_blank">
                                                <!-- <img src="{{asset($value['end_selfie'])}}" class="w-50 h-50"/> -->
                                                View Image
                                            </a>
                                        @else 
                                        -- 
                                        @endif
                                        </p>
                                        <br />
                                        <p><b>Location : </b>
                                        @if($value['end_latitude'] && $value['end_longitude'])
                                        <a href="https://www.google.com/maps/search/?api=1&query={{ $value['end_latitude'] }},{{ $value['end_longitude'] }}" target="_blank">
                                            View Location on Map
                                        </a>
                                        @endif
                                        </p>
                                        <br />
                                        <p><b>End By : </b>
                                        {{!empty($value->endby->name) ? $value->endby->name : '--'}}</p>
                                    </td>
                                    <td>
                                        {{ $value['remark'] ?? '--' }}
                                    </td>

                                </tr>
                            @endforeach
                           
                        </tbody>
                    </table>
                </div>
                        
            </div>
        </div>
    @else
        <h5 class="text-center text-muted py-5">No Data Found</h5>
    @endif
</div>

<div class="modal fade assementModal" id="assementModal" tabindex="-1" aria-labelledby="assementModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="assementModalLabel">Allot Unit</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.application.update_accessor') }}" class="post" id="post-form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="id">
                    <input type="hidden" name="type" id="modalType">

                    <h4><span class="roleTitle">Assessment</span> Select Team</h4>
                    <div class="col-12 mt-3">
                        <div class="form-group">
                            <label class="title-color" for="priority">Team Lead of <span class="roleTitle">Assessment</span>
                            </label>
                            <select name="auditor_id" class="form-control" id="auditor_id" required>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-12 mt-3">
                        <div class="form-group">
                            <label class="title-color" for="priority">Other Team Member of <span class="roleTitle">Assessment</span>
                            </label>
                            <select name="other_auditor_id[]" class="form-control" id="other_auditor_id" multiple>
                            </select>
                        </div>
                    </div>

                    <div class="col-12 mt-3">
                        <div style="display: flex; gap: 7px; align-items: center; width:100%;">
                            <a class="customSecondBtn" data-bs-dismiss="modal" role="button">Cancel</a>
                            <button type="submit" class="customPrimaryBtn">
                                Allot
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
<script>
$(document).ready(function() {
    $('#other_auditor_id').select2({
      placeholder: "Select an option",
      allowClear: true
    });
});
</script>
<script>
    $(document).on('click', '.view-application', function() {
        var id = $(this).data('id');
        var type = $(this).data('type');

        $('#assementModal').find('input[name=id]').val('');
        $('.roleTitle').html(type.replace('_', ' '));
        $('#modalType').val(type);
        $('#auditor_id').empty();
        $('#other_auditor_id').empty();

        $.ajax({
            type: "POST",
            url: "{{ route('admin.application.get-list') }}",
            data: {
                id: id,
                type: type,
                _token: "{{ csrf_token() }}"
            },
            dataType: "json",
            success: function(response) {
                
                $('#auditor_id').html(response.acc_html);
                $('#other_auditor_id').html(response.other_html);

                $('#assementModal').find('input[name=id]').val(response.application.id);

                if (response.application.auditor_id) {
                    $('#auditor_id').val(response.application.auditor_id).trigger('change');
                } else {
                    $('#auditor_id').val('').trigger('change');
                }

                var otherCsv = response.application.other_auditor_id || '';
                var othersArr = [];
                if (otherCsv.length) {
                    othersArr = otherCsv.split(',').map(function(v){ return v.trim(); }).filter(Boolean);
                    $('#other_auditor_id').val(othersArr).trigger('change');
                } else {
                    $('#other_auditor_id').val([]).trigger('change');
                }

                $('#assementModal').modal('show');
            },
            error: function(xhr) {
                alert('Could not fetch assessors');
            }
        });
    });

    $(document).on('submit','#post-form-data', function(e) {
        e.preventDefault()

        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                Swal.fire('', 'Accessor alloted successfully', 'success').then(() => {
                    window.location.reload()
                })
            }
        });

    })

    $(document).on("change", "#auditor_id", function () {
        var leadId = $(this).val();
        $("#other_auditor_id option").each(function () {
            if ($(this).val() == leadId) {
                $(this).prop("disabled", true).prop("selected", false);
            } else {
                $(this).prop("disabled", false);
            }
        });

        $("#other_auditor_id").trigger("change");
    });

    $("#auditor_id").trigger("change");

</script>
@endpush
