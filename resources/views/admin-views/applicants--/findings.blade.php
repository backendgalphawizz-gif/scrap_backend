@php
    use App\Model\AssessmentFinding;
    use App\CPU\Helpers;
    use App\CPU\translate;
    $checklist = AssessmentFinding::where('finding_type', $type)->where('application_id',$application->id)->get();
@endphp

<div class="container-fluid">
    <div class="px-3 py-4">
        <div class="row align-items-center">
            <div class="col-sm-4 col-md-6 col-lg-8 mb-2 mb-sm-0">
                <h6 class="customHeading mb-0">Findings</h6>
            </div>
        </div>
    </div>
    @if($checklist->isNotEmpty())
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <table class="table table-hover table-borderless table-thead-bordered table-align-middle card-table w-100 horizontalTable findingTableClause">
                        <thead>
                            <tr>
                                <th>{{ translate('Clause no') }}</th>
                                <th>{{ translate('Detail Findings') }}</th>
                                <th>{{ translate('CAB Action') }}</th>
                                <th>{{ translate('Added By') }}</th>
                                <th>{{ translate('Status') }}</th>
                                <th>{{ translate('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($checklist as $index => $clause)
                                <tr>
                                    <td>{{ $clause->clause_no ?? '--' }}</td>
                                    <td>
                                        @if($clause->detailed_findings)
                                            @php
                                                $detailFinding = $clause->detailed_findings;   
                                            @endphp
                                            <b>Assessment standard : </b> 
                                            {{$detailFinding['assessment_standard'] ?? '--'}}
                                            <br>
                                            <b>Finding Description : </b> 
                                            {{$detailFinding['finding_description'] ?? '--'}}
                                            <br>
                                            <b>Objective Evidence : </b> 
                                            {{$detailFinding['objective_evidence'] ?? '--'}}
                                            <br>

                                        @else 
                                            --
                                        @endif
                                    </td>
                                    <td>
                                        @if($clause->action_proposed_by_cab)
                                            @php
                                                $cabAction = $clause->action_proposed_by_cab;  
                                            @endphp
                                            <b>Root Cause of Non-Conformity : </b> 
                                            {{$cabAction['root_cause_of_non_conformity'] ?? '--'}}
                                            <br>
                                            <b>Corrective Action Proposed : </b> 
                                            {{$cabAction['corrective_action_proposed'] ?? '--'}}
                                            <br>
                                            <b>Proposed Date of Corrective Action : </b> 
                                            {{($cabAction['proposed_date_of_corrective_action'][0]['value']) ?$cabAction['proposed_date_of_corrective_action'][0]['title'] : $cabAction['proposed_date_of_corrective_action'][1]['title']}}
                                            <br>

                                        @else 
                                            --
                                        @endif         
                                    </td>
                                    <td>
                                        {{ $clause->assessor_name ?? '-'}}
                                    </td>
                                    <td>
                                        {{ ($clause->status == 0) ? 'Not Accept' : 'Accept'}}
                                    </td>
                                    
                                    <td class="text-center">
                                        <button type="button" class="mt-1 btn btn-dark findingHistoryButton" data-findingid="{{ $clause->id }}"> History </button>
                                            
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

<!-- History Modal -->
<div class="modal fade findingHistoryModal" id="findingModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="findingModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable" style="max-width: 55%;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="findingModalLabel">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@push('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>

$(document).on('click', '.findingHistoryButton', function () {

    let findingid = $(this).data('findingid');

    if (!findingid) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Missing required data (Findings).'
        });
        return;
    }

    Swal.fire({
        title: 'Loading...',
        text: 'Fetching Finding history, please wait.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    $.ajax({
        url: "{{ route('admin.application.get-finding-history') }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            finding_id: findingid,
        },
        success: function (response) {
            Swal.close(); 

            if (response.status) {
                $('#findingModalLabel').text(`Finding History`);

                let html = `
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Detail Findings</th>
                                    <th>Assessor Detail</th>
                                    <th>Cab Actions</th>
                                    <th>Cab Details</th>
                                </tr>
                            </thead>
                            <tbody>
                `;

                if (response.data && response.data.length > 0) {
                    response.data.forEach(item => {

                        // Format old and new values (convert object to lines)
                        const formatAssessorData = (data) => {
                            if (!data) return '<em>No data</em>';

                            return `
                                <div><strong>Assessment Standard:</strong> ${data.assessment_standard ?? '--'}</div>
                                <div><strong>Finding Description:</strong> ${data.finding_description ?? '--'}</div>
                                <div><strong>Objective Evidence:</strong> ${data.objective_evidence ?? '--'}</div>
                            `;
                        };

                        const formatCabData = (data) => {
                            if (!data) return '<em>No data</em>';

                            let proposedDate = '--';
                            if (Array.isArray(data.proposed_date_of_corrective_action)) {
                                const selected = data.proposed_date_of_corrective_action.find(d => d.value === true);
                                proposedDate = selected ? selected.title : '--';
                            }

                            return `
                                <div><strong>Root Cause of Non-Conformity:</strong> ${data.root_cause_of_non_conformity ?? '--'}</div>
                                <div><strong>Corrective Action Proposed:</strong> ${data.corrective_action_proposed ?? '--'}</div>
                                <div><strong>Proposed Date of Corrective Action:</strong> ${proposedDate}</div>
                            `;
                        };

                        const formatDateTime = (datetime) => {
                            if (!datetime) return '-';
                            const date = new Date(datetime);
                            if (isNaN(date)) return datetime; 
                            const options = { day: '2-digit', month: 'short', year: 'numeric' };
                            const formattedDate = date.toLocaleDateString('en-GB', options);
                            const formattedTime = date.toLocaleTimeString('en-GB', {
                                hour: '2-digit',
                                minute: '2-digit',
                                hour12: false
                            });
                            return `${formattedDate.replace(/ /g, '-')} ${formattedTime}`;
                        };

                        html += `
                        <tr>
                            <td>${formatAssessorData(item.assessor_data)}</td>
                            <td>${item.update_by ?? ''} (${item.role ?? '-'}) (Date : ${formatDateTime(item.assessor_date)})</td>
                            <td>${formatCabData(item.cab_data)}</td>
                            <td>${item.cab_name ?? ''}(Date : ${formatDateTime(item.cab_date)})</td>
                        </tr>
                        `;
                    });
                } else {
                    html += `
                        <tr>
                            <td colspan="4" class="text-center text-muted py-3">
                                No comment history found.
                            </td>
                        </tr>
                    `;
                }

                html += `
                            </tbody>
                        </table>
                    </div>
                `;

                // Inject generated HTML into modal body
                $('#findingModal .modal-body').html(html);

                // Show the modal only after content is ready
                $('#findingModal').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message ?? 'Something went wrong!'
                });
            }
        },
        error: function (err) {
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: err.responseJSON?.message ?? 'Something went wrong!'
            });
        }
    });

});

</script>


<script>
$(document).ready(function () {
    $('.remarkForm').on('submit', function (e) {
        e.preventDefault();

        let form = $(this);
        let formData = form.serialize();
        let modal = form.closest('.modal');

        $.ajax({
            url: "{{ route('admin.application.update-comment') }}", 
            type: "POST",
            data: formData,
            // beforeSend: function() {
            //     Swal.fire({
            //         title: 'Saving...',
            //         text: 'Please wait',
            //         allowOutsideClick: false,
            //         didOpen: () => {
            //             Swal.showLoading();
            //         }
            //     });
            // },
            success: function(response) {
                console.log(response);
                if(response.status){
                    modal.modal('hide');

                    Swal.fire({
                        icon: 'success',
                        title: 'Saved!',
                        text: response.message ?? 'Remark saved successfully.',
                        confirmButtonColor: '#050505ff'
                    }).then(() => {

                        let activeOuterTab = $('.applicantTab button.active').attr('id');
                        if (activeOuterTab) {
                            localStorage.setItem('activeOuterTab', activeOuterTab);
                        }

                        let activeInnerTab = $('#pills-Checklist .nav-pills button.active').attr('id');
                        if (activeInnerTab) {
                            localStorage.setItem('activeInnerTab', activeInnerTab);
                        }

                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    });
                    
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message ?? 'Something went wrong!'
                    });

                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                    
                    // location.reload();
                    
                }
            },
            error: function(xhr) {
                let errMsg = xhr.responseJSON?.message ?? 'Something went wrong!';
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errMsg
                });
            }
        });
    });
});
</script>
@endpush
