<div class="card">
                <div class="card-body">

              
                    <h5 class="mb-3">Application Information</h5>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <strong>Application Number</strong>
                            <div>{{ $witness->application_number ?? '-' }}</div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <strong>Application Type</strong>
                            <div>{{ $witness->application_type ?? '-' }}</div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <strong>Scheme</strong>
                            <div>
                                {{ $witness->scheme->title ?? '-' }}
                                ({{ $witness->scheme->code ?? '-' }})
                            </div>
                        </div>
                    </div>

                    <hr>

                  
                    <h5 class="mb-3">Client Information</h5>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <strong>Client Name</strong>
                            <div>{{ $witness->client->name ?? '-' }}</div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <strong>Client Phone</strong>
                            <div>{{ $witness->client->phone ?? '-' }}</div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <strong>Client Status</strong>
                            <div>
                                @if($witness->client_status == 0)
                                    <span class="badge badge-warning">Pending</span>
                                @elseif($witness->client_status == 1)
                                    <span class="badge badge-success">Accepted</span>
                                @else
                                    <span class="badge badge-danger">Rejected</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <hr>

                    <h5 class="mb-3">Inspection / Audit Details</h5>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <strong>Inspection Date</strong>
                            <div>{{ $witness->inspection_date ?? '-' }}</div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <strong>Inspection Duration</strong>
                            <div>{{ $witness->inspection_duration ?? '-' }}</div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <strong>Audit Stage</strong>
                            <div>{{ $witness->audit_stage ?? '-' }}</div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <strong>Standards</strong>
                            <div>{{ $witness->standards ?? '-' }}</div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <strong>Scope</strong>
                            <div>{{ $witness->scope ?? '-' }}</div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <strong>Area</strong>
                            <div>{{ $witness->area ?? '-' }}</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Remark</strong>
                            <div>{{ $witness->remark ?? '-' }}</div>
                        </div>
                    </div>

                    <hr>

            
                    <h5 class="mb-3">Witness Assessment Team</h5>

                    <div class="mb-3">
                        <strong>Team Lead</strong>
                        <div>
                            {{ $witness->auditor->name ?? '-' }}
                            ({{ $witness->auditor->phone ?? '-' }})
                        </div>
                    </div>

                    <div class="mb-3">
                        <strong>Team Members</strong>

                        @if($auditorTeam->count())
                            <ul class="mt-2">
                                @foreach($auditorTeam as $member)
                                    <li>{{ $member->name ?? '-' }} ({{ $member->phone ?? '-' }})</li>
                                @endforeach
                            </ul>
                        @else
                            <div class="text-muted">No team members assigned</div>
                        @endif
                    </div>

                    <hr>

                    <h5 class="mb-3">Team Status</h5>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <strong>Auditor Team Status</strong>
                            <div>
                                @if($witness->auditor_team_status == 0)
                                    <span class="badge badge-warning">Pending</span>
                                @elseif($witness->auditor_team_status == 1)
                                    <span class="badge badge-success">Start</span>
                                @else
                                    <span class="badge badge-danger">Complete</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <strong>Client Auditor Team Status</strong>
                            <div>
                                @if($witness->client_auditor_team_status == 0)
                                    <span class="badge badge-warning">Pending</span>
                                @elseif($witness->client_auditor_team_status == 1)
                                    <span class="badge badge-success">Accepted</span>
                                @else
                                    <span class="badge badge-danger">Rejected</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <strong>Witness Status</strong>
                            <div>
                                @if($witness->witness_status == 0)
                                    <span class="badge badge-warning">Pending</span>
                                @elseif($witness->witness_status == 1)
                                    <span class="badge badge-info">Started</span>
                                @else
                                    <span class="badge badge-success">Completed</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <hr>

                 
                    <h5 class="mb-3">Attachments</h5>

                    @if(!empty($witness->attachments) && is_array($witness->attachments))
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Document</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($witness->attachments as $file)
                                    <tr>
                                        <td>{{ $file['title'] ?? '-' }}</td>
                                        <td>
                                            @if(!empty($file['value']))
                                                <span class="badge badge-success">Uploaded</span>
                                            @else
                                                <span class="badge badge-danger">Not Uploaded</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-muted">No attachments available</div>
                    @endif

                    

                    

                </div>
            </div>