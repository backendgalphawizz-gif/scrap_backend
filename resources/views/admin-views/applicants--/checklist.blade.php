@php
    use App\Model\ApplicationChecklist;
    use App\CPU\Helpers;
    use App\CPU\translate;

    $checklist = ApplicationChecklist::where('application_id', $application->id)->first();
@endphp

<div class="container-fluid">
    <div class="px-3 py-4">
        <div class="row align-items-center">
            <div class="col-sm-4 col-md-6 col-lg-8 mb-2 mb-sm-0">
                <h6 class="customHeading mb-0">Checklist</h6>
            </div>
        </div>
    </div>
    @if($checklist)
        <div class="row">
            <div class="col-lg-12">
                {{-- ===== Tabs Navigation ===== --}}
                <ul class="nav nav-pills applicantTab mb-3" id="pills-tab" role="tablist">
                    
                    @foreach ($checklist->clause as $index => $clause)
                     
                        <li role="presentation">
                            <button 
                                class=" {{ $loop->first ? 'active' : '' }}" 
                                id="pills-{{ $clause['clause_no'] }}-tab" 
                                data-bs-toggle="pill"
                                data-bs-target="#pills-{{ $clause['clause_no'] }}" 
                                type="button" 
                                role="tab"
                                aria-controls="pills-{{ $clause['clause_no'] }}"
                                aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                            >
                                {{ $clause['clause_title'] }}
                            </button>
                        </li>
                    @endforeach
                </ul>

                {{-- ===== Tabs Content ===== --}}
                <div class="tab-content" id="pills-tabContent">
                    @foreach ($checklist->clause as $index => $clause)
                        @php
                            $requirements = Helpers::findClause($checklist->requirements, $clause['clause_no']);
                            $subClauses = Helpers::findClauseArray($requirements['sub_clauses'] ?? []);
                        @endphp

                        <div 
                            class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" 
                            id="pills-{{ $clause['clause_no'] }}" 
                            role="tabpanel"
                            aria-labelledby="pills-{{ $clause['clause_no'] }}-tab"
                            tabindex="0"
                        >
                            <div class="table-responsive">
                                <table class="table table-hover table-borderless table-thead-bordered table-align-middle card-table w-100 horizontalTable customTableClause">
                                    <thead>
                                        <tr>
                                            <th>{{ translate('Clause') }}</th>
                                            <th>{{ translate('Requirements') }}</th>
                                            <th>{{ translate('CAB Self-Assessment') }}</th>
                                            <th>{{ translate('C/ NC/ NA') }}</th>
                                            <th>{{ translate('Team Leader or Assessor Comments') }}</th>
                                            <th>{{ translate('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($subClauses as $sub)
                                            @php
                                                $is_child = $sub['child_array']
                                            @endphp
                                            <tr>
                                                <td>{{ $sub['clause_no'] }}</td>
                                                <td>
                                                    <div>
                                                        {!! $is_child == 1 
                                                            ? '<b>'.e($sub['clause_text'] ?? '').'</b>' 
                                                            : e($sub['clause_text'] ?? '') !!}
                                                        <br>
                                                        <small class="text-muted">{{ $sub['clause_description'] ?? '' }}</small>
                                                    </div>
                                                </td>
                                                <td>
                                                    {{ $sub['cab_self_assessment'] ?? '' }}
                                                    @if(!empty($sub['other_compliance']))
                                                        <br>   
                                                        <a href="{{ asset($sub['other_compliance']) }}" target="_blank">View File</a>
                                                    @endif            
                                                </td>
                                                <td>
                                                    {{ $sub['compliance'] ?? '' }}
                                                </td>
                                                <td>
                                                    {{ $sub['team_leader_comments'] ?? '' }}
                                                    <br>
                                                    @if (!empty($sub['comment_by']))
                                                        <b>Comment By : </b> {{ $sub['comment_by'] }}
                                                    @endif
                                                </td>
                                               <td class="text-center">
                                                    @if ($is_child == 0)
                                                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal{{ $sub['clause_no'] }}">
                                                            Remark
                                                        </button>
                                                        @if (!empty($sub['team_leader_comments']))
                                                            <button type="button" class="mt-1 btn btn-dark historyButton" data-id="{{ $sub['clause_no'] }}" data-checklistid="{{ $checklist->id }}" 
                                                            data-applicationid="{{ $application->id }}"> History </button>
                                                        @endif

                                                        <!-- Modal -->
                                                        <div class="modal fade" id="modal{{ $sub['clause_no'] }}" tabindex="-1" aria-labelledby="modal{{ $sub['clause_no'] }}Label" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered">
                                                                <div class="modal-content">
                                                                    <form class="remarkForm"  method="post">
                                                                        @csrf
                                                                        <input type="hidden" name="application_id" value="{{ $application->id }}">
                                                                        <input type="hidden" name="checklist_id" value="{{ $checklist->id }}">
                                                                        <input type="hidden" name="clause_no" value="{{ $sub['clause_no'] }}">
                                                                        <div class="modal-header bg-light">
                                                                            <h5 class="modal-title" id="modal{{ $sub['clause_no'] }}Label">
                                                                                TL or Assessor Remark — <span class="text-muted">{{ $sub['clause_no'] }}</span>
                                                                            </h5>
                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                        </div>

                                                                        <div class="modal-body">
                                                                            <div class="mb-3">
                                                                                <label class="form-label fw-semibold">Compliance Mode</label>
                                                                                <select name="compliance" class="form-select" required>
                                                                                    <option value="NC">Non-Compliance</option>
                                                                                    <option value="C">Compliance</option>
                                                                                    <option value="NA">Not Applicable</option>
                                                                                </select>
                                                                            </div>

                                                                            <div class="mb-3">
                                                                                <label class="form-label fw-semibold">Comment</label>
                                                                                <textarea name="team_leader_comments" style="min-height: 115px;" class="form-control"  placeholder="Enter comment..." required></textarea>
                                                                            </div>
                                                                        </div>

                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                                                                Close
                                                                            </button>
                                                                            <button type="submit" class="btn btn-dark">
                                                                                Save changes
                                                                            </button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted">No sub-clauses found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @else
        <h5 class="text-center text-muted py-5">No Data Found</h5>
    @endif
</div>

<!-- History Modal -->
<div class="modal fade historyModal" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable" style="max-width: 55%;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
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
const baseUrl = "{{ asset('') }}";

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

    let activeInnerTab = localStorage.getItem('activeInnerTab');
    if (activeInnerTab) {
        // Wait a short time to ensure inner tabs are rendered
        setTimeout(() => {
            let innerTabButton = $('#' + activeInnerTab);
            if (innerTabButton.length) {
                let innerTab = new bootstrap.Tab(innerTabButton[0]);
                innerTab.show();
            }
            localStorage.removeItem('activeInnerTab');
        }, 400);
    }
});

$(document).on('click', '.historyButton', function () {

    let clauseNo = $(this).data('id');
    let checklistId = $(this).data('checklistid');
    let applicationId = $(this).data('applicationid');

    if (!clauseNo || !checklistId || !applicationId) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Missing required data (Clause, Checklist, or Application).'
        });
        return;
    }

    Swal.fire({
        title: 'Loading...',
        text: 'Fetching comment history, please wait.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    $.ajax({
        url: "{{ route('admin.application.get-comment-history') }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            checklist_id: checklistId,
            application_id: applicationId,
            clause_no: clauseNo,
        },
        success: function (response) {
            Swal.close(); 

            if (response.status) {
                $('#staticBackdropLabel').text(`Comment History - Clause ${clauseNo}`);

                let html = `
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Old Values</th>
                                    <th>New Values</th>
                                    <th>Updated By</th>
                                    <th>Date Time</th>
                                </tr>
                            </thead>
                            <tbody>
                `;

                if (response.data && response.data.length > 0) {
                    response.data.forEach(item => {

                        // Format old and new values (convert object to lines)
                        const formatValues = (values) => {
                            if (!values || Object.keys(values).length === 0) {
                                return '<em>No data</em>';
                            }

                            let lines = '';
                            for (const [key, val] of Object.entries(values)) {
                                if(key=='cab_self_assessment'){
                                    lines += `<div><strong>CAB Assessment:</strong> ${val ?? '-'}</div>`;
                                }
                                if(key=='compliance'){
                                    lines += `<div><strong>${key.replace(/_/g, ' ')}:</strong> ${val ?? '-'}</div>`;
                                } 
                                if (key === 'other_compliance' && val) {
                                    lines += `<div><strong>File:</strong> <a href="${baseUrl + val}" target="_blank">View File</a></div>`;
                                }
                                if(key=='team_leader_comments'){
                                    lines += `<div><strong>Comment:</strong> ${val ?? '-'}</div>`;
                                } 
                                if(key=='comment_by'){
                                    lines += `<div><strong>${key.replace(/_/g, ' ')}:</strong> ${val ?? '-'}</div>`;
                                } 
                            }
                            return lines;
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
                                <td>${formatValues(item.old_data)}</td>
                                <td>${formatValues(item.new_data)}</td>
                                <td>${item.update_by ?? ''} (${item.update_type ?? '-'})</td>
                                <td>${formatDateTime(item.created_at)}</td>
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
                $('#staticBackdrop .modal-body').html(html);

                // Show the modal only after content is ready
                $('#staticBackdrop').modal('show');
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
