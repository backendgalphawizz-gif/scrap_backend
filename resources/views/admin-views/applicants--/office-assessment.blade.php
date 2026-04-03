@php
    use App\Model\ApplicationAssessment;
    use App\CPU\Helpers;
    use App\CPU\translate;
    $checklist = ApplicationAssessment::where('application_id',$application->id)->first();
    $team_leader_assessment_by = $checklist->team_leader_assessment_by ?? null;
    $technical_assessment_by = $checklist->technical_assessment_by ?? null;
    $vertical_assessment_by = $checklist->vertical_assessment_by ?? null;
@endphp

<div class="container-fluid">

    <div class="row">
        <div class="col-lg-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="team-lead-assessment-tab" data-toggle="tab" href="#team-lead-assessment" role="tab" aria-controls="team-lead-assessment" aria-selected="true">Team Lead Assessment</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="technical-assessment-tab" data-toggle="tab" href="#technical-assessment" role="tab" aria-controls="technical-assessment" aria-selected="false">Technical Assessment</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="vertical-assessment-tab" data-toggle="tab" href="#vertical-assessment" role="tab" aria-controls="vertical-assessment" aria-selected="false">Vertical Assessment</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="findings-assessment-tab" data-toggle="tab" href="#findings-assessment" role="tab" aria-controls="findings-assessment" aria-selected="false">Findings</a>
                </li>
                @if($checklist)
                <li class="nav-item" role="presentation">
                   <a class="nav-link btn-btn-primary" role="tab" href="{{ asset($checklist->attendance_form) }}" target="_blank">Attendance</a>     
                </li>
                @endif
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="team-lead-assessment" role="tabpanel" aria-labelledby="team-lead-assessment-tab">
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
                                                id="pills-{{ $clause['clause_no'] }}-random-p-tab" 
                                                data-bs-toggle="pill"
                                                data-bs-target="#pills-{{ $clause['clause_no'] }}-random-p" 
                                                type="button" 
                                                role="tab"
                                                aria-controls="pills-{{ $clause['clause_no'] }}-random-p"
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
                                            $requirements = Helpers::findClause($checklist->team_leader_assessment, $clause['clause_no']);
                                            // $requirements = Helpers::findClause($checklist->team_leader_assessment, 7);
                                            $subClauses = Helpers::findClauseArray($requirements['sub_clauses'] ?? []);
                                        @endphp

                                        <div 
                                            class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" 
                                            id="pills-{{ $clause['clause_no'] }}-random-p" 
                                            role="tabpanel"
                                            aria-labelledby="pills-{{ $clause['clause_no'] }}-random-p-tab"
                                            tabindex="0"
                                        >
                                            <div class="table-responsive">
                                                <table class="table table-hover table-borderless table-thead-bordered table-align-middle card-table w-100 horizontalTable officeTableClause">
                                                    <thead>
                                                        <tr>
                                                            <th>{{ translate('Clause') }}</th>
                                                            <th>{{ translate('Requirements') }}</th>
                                                            <!-- <th>{{ translate('CAB Self-Assessment') }}</th> -->
                                                            <th>{{ translate('C/ NC/ NA') }}</th>
                                                            <th>{{ translate('Team Leader or Assessor Comments') }}</th>
                                                            <!-- <th>{{ translate('Action') }}</th> -->
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
                                                                    {{ $sub['compliance'] ?? '' }}
                                                                </td>
                                                                <td>
                                                                    {{ $sub['team_leader_comments'] ?? '' }}
                                                                    <br>
                                                                    @if ($team_leader_assessment_by)
                                                                        <b>Comment By : </b> {{ $team_leader_assessment_by }}
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
                <div class="tab-pane fade" id="technical-assessment" role="tabpanel" aria-labelledby="technical-assessment-tab">
                    @if(!empty($checklist->technical_assessment))
                       @if(!empty($checklist->technical_assessment['requirements']))
                            <div class="table-responsive">
                                <table class="table table-hover table-borderless table-thead-bordered table-align-middle card-table w-100 horizontalTable officeTableClause">
                                    <thead>
                                        <tr>
                                            <th>{{ translate('Clause') }}</th>
                                            <th>{{ translate('Requirements') }}</th>
                                            <th>{{ translate('C/ NC/ NA') }}</th>
                                            <th>{{ translate('Team Leader or Assessor Comments') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($checklist->technical_assessment['requirements'] as $clause)
                                                                    
                                            <tr>
                                                <td><strong>{{ $clause['clause']??'' }}</strong></td>
                                                <td><strong>{{ $clause['clause_title']??'' }}</strong></td>
                                                <td></td>
                                                <td></td>
                                            </tr>                   
                                            @forelse ($clause['sub_clauses'] as $sub)
                                                @if ($sub['child_array'] == '1')
                                                    <tr>
                                                        <td><strong>{{ $sub['clause_no'] }}</strong></td>
                                                        <td><strong>{{ $sub['clause_text'] }}</strong></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>                        
                                                    @foreach($sub['items'] as $item)
                                                        <tr>
                                                            <td>{{ $item['clause_no']  ?? ''}}</td>
                                                            <td>{{ $item['clause_text'] ?? '' }}</td>
                                                            <td>{{ $item['compliance'] ?? '' }}</td>
                                                            <td>
                                                                {{ $item['team_leader_comments'] ?? '' }}
                                                                @if($technical_assessment_by ?? false)
                                                                    <br><small>By: {{ $technical_assessment_by }}</small>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach 
                                                 @else
                                                <tr>
                                                    <td>{{ $sub['clause_no'] }}</td>
                                                    <td>{{ $sub['clause_text'] }}</td>
                                                    <td>{{ $sub['compliance'] ?? '' }}</td>
                                                    <td>{{ $sub['team_leader_comments'] ?? '' }}
                                                        @if($technical_assessment_by ?? false)
                                                            <br><small>By: {{ $technical_assessment_by }}</small>
                                                        @endif
                                                    </td>
                                                </tr>                          
                                                @endif                            
                                                
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted">No sub-clauses found</td>
                                                </tr>
                                            @endforelse
                                        @endforeach
                                        @if(isset($checklist->technical_assessment['guidlines']))
                                            <tr>
                                                <th></th>
                                                <th>Guidlines</th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                            @foreach ($checklist->technical_assessment['guidlines'] as $index => $clause)
                                                <tr>
                                                    <td><strong>{{ $clause['clause']??'' }}</strong></td>
                                                    <td><strong>{{ $clause['clause_title']??'' }}</strong></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>                   
                                                @forelse ($clause['sub_clauses'] as $sub)
                                                    @if ($sub['child_array'] == '1')
                                                        <tr>
                                                            <td><strong>{{ $sub['clause_no'] }}</strong></td>
                                                            <td><strong>{{ $sub['clause_text'] }}</strong></td>
                                                            <td></td>
                                                            <td></td>
                                                        </tr>                        
                                                        @foreach($sub['items'] as $item)
                                                            <tr>
                                                                <td>{{ $item['clause_no'] ?? '' }}</td>
                                                                <td>{{ $item['clause_text'] ?? ''}}</td>
                                                                <td>{{ $item['compliance'] ?? '' }}</td>
                                                                <td>
                                                                    {{ $item['team_leader_comments'] ?? '' }}
                                                                    @if($technical_assessment_by ?? false)
                                                                        <br><small>By: {{ $technical_assessment_by }}</small>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach 
                                                    @else
                                                    <tr>
                                                        <td>{{ $sub['clause_no'] }}</td>
                                                        <td>{{ $sub['clause_text'] }}</td>
                                                        <td>{{ $sub['compliance'] ?? '' }}</td>
                                                        <td>
                                                            {{ $sub['team_leader_comments'] ?? '' }}
                                                            @if($technical_assessment_by ?? false)
                                                                <br><small>By: {{ $technical_assessment_by }}</small>
                                                            @endif                
                                                        </td>
                                                    </tr>                          
                                                    @endif                            
                                                    
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center text-muted">No sub-clauses found</td>
                                                    </tr>
                                                @endforelse
                                            @endforeach
                                        @endif
                                        @if(isset($checklist->technical_assessment['pac_requirements']))
                                            <tr>
                                                <th></th>
                                                <th>Pac Requirements</th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                            @foreach ($checklist->technical_assessment['pac_requirements'] as $index => $clause)
                                                <tr>
                                                    <td><strong>{{ $clause['clause']??'' }}</strong></td>
                                                    <td><strong>{{ $clause['clause_title']??'' }}</strong></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>                   
                                                @forelse ($clause['sub_clauses'] as $sub)
                                                    @if ($sub['child_array'] == '1')
                                                        <tr>
                                                            <td><strong>{{ $sub['clause_no'] }}</strong></td>
                                                            <td><strong>{{ $sub['clause_text'] }}</strong></td>
                                                            <td></td>
                                                            <td></td>
                                                        </tr>                        
                                                        @foreach($sub['items'] as $item)
                                                            <tr>
                                                                <td>{{ $item['clause_no'] ?? ''}}</td>
                                                                <td>{{ $item['clause_text'] ?? ''}}</td>
                                                                <td>{{ $item['compliance'] ?? '' }}</td>
                                                                <td>
                                                                    {{ $item['team_leader_comments'] ?? '' }}
                                                                    @if($technical_assessment_by ?? false)
                                                                        <br><small>By: {{ $technical_assessment_by }}</small>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach 
                                                    @else
                                                    <tr>
                                                        <td>{{ $sub['clause_no'] }}</td>
                                                        <td>{{ $sub['clause_text'] }}</td>
                                                        <td>{{ $sub['compliance'] ?? '' }}</td>
                                                        <td>
                                                            {{ $sub['team_leader_comments'] ?? '' }}
                                                            @if($technical_assessment_by ?? false)
                                                                <br><small>By: {{ $technical_assessment_by }}</small>
                                                            @endif                
                                                        </td>
                                                    </tr>                          
                                                    @endif                            
                                                    
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center text-muted">No sub-clauses found</td>
                                                    </tr>
                                                @endforelse
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    @else
                        <h4>No Technical Assessment avaiable</h4>
                    @endif
                </div>
                <div class="tab-pane fade" id="vertical-assessment" role="tabpanel" aria-labelledby="vertical-assessment-tab">
                    @if(!empty($checklist->vertical_assessment))
                        @if(!empty($checklist->vertical_assessment['requirements']))
                            <div class="table-responsive">
                                <table class="table table-hover table-borderless table-thead-bordered table-align-middle card-table w-100 horizontalTable officeTableClause">
                                    <thead>
                                        <tr>
                                            <th>{{ translate('Clause') }}</th>
                                            <th>{{ translate('Requirements') }}</th>
                                            <th>{{ translate('C/ NC/ NA') }}</th>
                                            <th>{{ translate('Team Leader or Assessor Comments') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($checklist->vertical_assessment['requirements'] as $clause)
                                                                    
                                            <tr>
                                                <td><strong>{{ $clause['clause']??'' }}</strong></td>
                                                <td><strong>{{ $clause['clause_title']??'' }}</strong></td>
                                                <td></td>
                                                <td></td>
                                            </tr>                   
                                            @forelse ($clause['sub_clauses'] as $sub)
                                                @if ($sub['child_array'] == '1')
                                                    <tr>
                                                        <td><strong>{{ $sub['clause_no'] }}</strong></td>
                                                        <td><strong>{{ $sub['clause_text'] }}</strong></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>                        
                                                    @foreach($sub['items'] as $item)
                                                        <tr>
                                                            <td>{{ $item['clause_no'] ?? '' }}</td>
                                                            <td>{{ $item['clause_text'] ?? ''}}</td>
                                                            <td>{{ $item['compliance'] ?? '' }}</td>
                                                            <td>
                                                                {{ $item['team_leader_comments'] ?? '' }}
                                                                @if($vertical_assessment_by ?? false)
                                                                    <br><small>By: {{ $vertical_assessment_by }}</small>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach 
                                                 @else
                                                <tr>
                                                    <td>{{ $sub['clause_no'] }}</td>
                                                    <td>{{ $sub['clause_text'] }}</td>
                                                    <td>{{ $sub['compliance'] ?? '' }}</td>
                                                    <td>{{ $sub['team_leader_comments'] ?? '' }}
                                                        @if($vertical_assessment_by ?? false)
                                                            <br><small>By: {{ $vertical_assessment_by }}</small>
                                                        @endif
                                                    </td>
                                                </tr>                          
                                                @endif                            
                                                
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted">No sub-clauses found</td>
                                                </tr>
                                            @endforelse
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    @else
                        <h4>No Technical Vertical avaiable</h4>
                    @endif
                </div>
                <div class="tab-pane fade" id="findings-assessment" role="tabpanel" aria-labelledby="findings-assessment-tab">
                    @include('admin-views.applicants.findings',['type' => 'office_assessment'])
                </div>
            </div>
        </div>
    </div>
</div>

<!-- History Modal -->
<!-- <div class="modal fade historyModal" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
</div> -->

@push('script')

@endpush
