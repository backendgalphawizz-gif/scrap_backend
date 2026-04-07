@php
    use App\Model\ApplicationAssessment;
    use App\CPU\Helpers;
    use App\CPU\translate;
    $checklist = ApplicationAssessment::where('application_id', $application->id)->first();
    $witness_assessment_by = $checklist->witness_assessment_by ?? null;
@endphp

<div class="container-fluid">

    <div class="row">
        <div class="col-lg-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="witness-assessment-tab" data-toggle="tab" href="#witness-assessment" role="tab" aria-controls="witness-assessment" aria-selected="false">witness Assessment</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="witness-findings-assessment-tab" data-toggle="tab" href="#witness-findings-assessment" role="tab" aria-controls="witness-findings-assessment" aria-selected="false">Findings</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="witness-assessment" role="tabpanel" aria-labelledby="witness-assessment-tab">
                    <div class="px-3 py-4">
                        <div class="row align-items-center">
                            <div class="col-sm-4 col-md-6 col-lg-8 mb-2 mb-sm-0">
                                <h6 class="customHeading mb-0">Witness Assessment Checklist</h6>
                            </div>
                        </div>
                    </div>
                    @if(!empty($checklist->witness_assessment))
                       @if(!empty($checklist->witness_assessment['requirements']))
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
                                        @foreach ($checklist->witness_assessment['requirements'] as $clause)
                                                                    
                                            <tr>
                                                <td><strong>{{ $clause['clause']??'' }}</strong></td>
                                                <td><strong>{{ $clause['clause_title']??'' }}</strong></td>
                                                <td></td>
                                                <td></td>
                                            </tr>                   
                                            @forelse ($clause['sub_clauses'] as $sub)
                                                @if ($sub['child_array'] == '1')
                                                    <tr>
                                                        <td><strong>{{ $sub['clause_no'] ?? '' }}</strong></td>
                                                        <td><strong>{{ $sub['clause_text'] ?? ''}}</strong></td>
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
                                                                @if($witness_assessment_by ?? false)
                                                                    <br><small>By: {{ $witness_assessment_by }}</small>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach 
                                                 @else
                                                <tr>
                                                    <td>{{ $sub['clause_no'] ?? '' }}</td>
                                                    <td>{{ $sub['clause_text'] ?? ''}}</td>
                                                    <td>{{ $sub['compliance'] ?? '' }}</td>
                                                    <td>{{ $sub['team_leader_comments'] ?? '' }}
                                                        @if($witness_assessment_by ?? false)
                                                            <br><small>By: {{ $witness_assessment_by }}</small>
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
                                        @if(isset($checklist->witness_assessment['guidlines']))
                                            <tr>
                                                <th></th>
                                                <th>Guidlines</th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                            @foreach ($checklist->witness_assessment['guidlines'] as $index => $clause)
                                                <tr>
                                                    <td><strong>{{ $clause['clause']??'' }}</strong></td>
                                                    <td><strong>{{ $clause['clause_title']??'' }}</strong></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>                   
                                                @forelse ($clause['sub_clauses'] as $sub)
                                                    @if ($sub['child_array'] == '1')
                                                        <tr>
                                                            <td><strong>{{ $sub['clause_no'] ?? ''}}</strong></td>
                                                            <td><strong>{{ $sub['clause_text'] ?? ''}}</strong></td>
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
                                                                    @if($witness_assessment_by ?? false)
                                                                        <br><small>By: {{ $witness_assessment_by }}</small>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach 
                                                    @else
                                                    <tr>
                                                        <td>{{ $sub['clause_no'] ?? '' }}</td>
                                                        <td>{{ $sub['clause_text'] ?? ''}}</td>
                                                        <td>{{ $sub['compliance'] ?? '' }}</td>
                                                        <td>
                                                            {{ $sub['team_leader_comments'] ?? '' }}
                                                            @if($witness_assessment_by ?? false)
                                                                <br><small>By: {{ $witness_assessment_by }}</small>
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
                                        @if(isset($checklist->witness_assessment['pac_requirements']))
                                            <tr>
                                                <th></th>
                                                <th>Pac Requirements</th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                            @foreach ($checklist->witness_assessment['pac_requirements'] as $index => $clause)
                                                <tr>
                                                    <td><strong>{{ $clause['clause']??'' }}</strong></td>
                                                    <td><strong>{{ $clause['clause_title']??'' }}</strong></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>                   
                                                @forelse ($clause['sub_clauses'] as $sub)
                                                    @if ($sub['child_array'] == '1')
                                                        <tr>
                                                            <td><strong>{{ $sub['clause_no'] ?? ''}}</strong></td>
                                                            <td><strong>{{ $sub['clause_text'] ?? ''}}</strong></td>
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
                                                                    @if($witness_assessment_by ?? false)
                                                                        <br><small>By: {{ $witness_assessment_by }}</small>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach 
                                                    @else
                                                    <tr>
                                                        <td>{{ $sub['clause_no'] ?? ''}}</td>
                                                        <td>{{ $sub['clause_text'] ?? ''}}</td>
                                                        <td>{{ $sub['compliance'] ?? '' }}</td>
                                                        <td>
                                                            {{ $sub['team_leader_comments'] ?? '' }}
                                                            @if($witness_assessment_by ?? false)
                                                                <br><small>By: {{ $witness_assessment_by }}</small>
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
                        <h4>No Witness Assessment avaiable</h4>
                    @endif
                </div>
                <div class="tab-pane fade" id="witness-findings-assessment" role="tabpanel" aria-labelledby="witness-findings-assessment-tab">
                    @include('admin-views.applicants.findings',['type' => 'witness_assessment'])
                </div>
            </div>
        </div>
    </div>
</div>


@push('script')

@endpush
