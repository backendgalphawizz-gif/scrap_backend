
<div class="content container-fluid">

    <!-- PAGE TITLE + SEARCH -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h1 mb-0">Assessor Who Attempted</h2>

        <form action="{{ url()->current() }}" method="GET" class="d-flex">
            <input type="text" name="search" value="{{ request('search') }}"
                class="form-control" placeholder="Search by name or email" />
            <button class="btn btn--primary ml-2">Search</button>
        </form>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Attempt Summary</h5>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="thead-light">
                    <tr>
                        <th>SL</th>
                        <th>User</th>
                        <th>Total Questions</th>
                        <th>Quetion Attempted</th>
                        <th>Correct</th>
                        <th>Accuracy %</th>
                        <th>Status</th>
                        <th>Total Attempt</th>
                        <th>Date</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($attempts as $k => $a)

                  

                    <tr>
                        <td>{{ $attempts->firstItem() + $k }}</td>

                        <td>
                            <strong>{{ $a->admin->name ?? 'N/A' }}</strong><br>
                            <small class="text-muted">{{ $a->admin->email ?? '-' }}</small>
                        </td>

                        <td>{{ $totalQuestions }}</td>

                        <td><span class="badge badge-info">{{ $a->attempt_questions ?? 0 }}</span></td>

                        <td><span class="badge badge-success">{{ $a->correct_answers ?? 0 }}</span></td>

                        <td><span class="badge badge-primary">{{ $a->percent ?? 0.00 }}%</span></td>
                        
                        <td>
                            @if($a->is_completed == 1)
                            <span class="badge badge-success">Completed</span>
                            @else
                            <span class="badge badge-warning">In Progress</span>
                            @endif
                        </td>
                        <td><span class="badge badge-warning">{{ $a->total_attempt ?? 0 }}</span></td>

                        <td>{{ $a->created_at->format('d M Y') }}</td>
                    </tr>
                @endforeach
                </tbody>

            </table>
        </div>

        <div class="card-footer">
            <div class="d-flex justify-content-end">
                {!! $attempts->links() !!}
            </div>
        </div>

    </div>

</div>

