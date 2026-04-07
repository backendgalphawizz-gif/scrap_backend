@extends('layouts.back-end.app')
@section('title', 'Test Takers')

@section('content')
<div class="content container-fluid">

    <!-- Back + Title -->
    <div class="mb-3 d-flex align-items-center justify-content-between">
        <h2 class="h1 mb-0 d-flex align-items-center gap-2">
            <a class="textfont-set" href="{{ route('admin.training.training-view', $training->id) }}">
                <i class="tio-chevron-left"></i> Back
            </a>
            Members who gave test — {{ $training->title }}
        </h2>

        <div>
            <a href="{{ route('admin.training.list-training') }}" class="btn btn-outline-secondary btn-sm">
                All Trainings
            </a>
        </div>
    </div>

    <!-- Training context (optional) -->
    <div class="card mb-3">
        <div class="card-body">
            <div class="row">

                <div class="col-md-4">
                    <h6 class="text-muted">Scheme:</h6>
                    <p>{{ $training->scheme->title ?? '-' }}</p>
                </div>

                <div class="col-md-4">
                    <h6 class="text-muted">Area:</h6>
                    <p>{{ $training->area->title ?? '-' }}</p>
                </div>

                <div class="col-md-4">
                    <h6 class="text-muted">Scope:</h6>
                    <p>{{ $training->scopeData->title ?? '-' }}</p>
                </div>

            </div>
        </div>
    </div>

    <!-- Attempts table -->
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Test Takers</h5>

            <form action="{{ url()->current() }}" method="GET" class="d-flex gap-2">
                <div class="input-group input-group-merge input-group-custom">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><i class="tio-search"></i></div>
                    </div>
                    <input type="search" name="search" class="form-control"
                           value="{{ request('search') }}"
                           placeholder="Search by member name/email/phone">
                    <button type="submit" class="btn btn--primary">Search</button>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-borderless table-thead-bordered w-100">
                <thead class="thead-light thead-50 text-capitalize">
                <tr>
                    <th>SL</th>
                    <th>Member</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Total Q</th>
                    <th>Correct</th>
                    <th>Score</th>
                    <th>Status</th>
                    <th>Attempted At</th>
                    <th class="text-center">Action</th>
                </tr>
                </thead>

                <tbody>
                @forelse($attempts as $k => $row)
                    <tr>
                        <td>{{ $attempts->firstItem() + $k }}</td>

                        <td>{{ $row->user->name ?? '—' }}</td>
                        <td>{{ $row->user->email ?? '—' }}</td>
                        <td>{{ $row->user->phone ?? '—' }}</td>

                        <td>{{ $row->total_questions ?? '—' }}</td>
                        <td>{{ $row->correct_answers ?? '—' }}</td>

                        <td>
                            @if(isset($row->score))
                                {{ rtrim(rtrim(number_format($row->score, 2), '0'), '.') }}
                            @else
                                —
                            @endif
                        </td>

                        <td>
                            @php
                                // Expecting "pass"/"fail" or 1/0; tweak if different.
                                $ok = in_array(strtolower((string)$row->status), ['pass','1','passed','true'], true);
                            @endphp
                            <span class="badge {{ $ok ? 'badge-success' : 'badge-soft-danger' }}">
                                {{ $ok ? 'Pass' : 'Fail' }}
                            </span>
                        </td>

                        <td>{{ $row->created_at ? $row->created_at->format('d M Y, h:i A') : '—' }}</td>

                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                {{-- View answers (optional) --}}
                                {{-- <a href="{{ route('admin.training.attempt-show', $row->id) }}" class="btn btn-outline-info btn-sm">
                                    <i class="tio-visible"></i>
                                </a> --}}
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted">No members have attempted this test yet.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($attempts->hasPages())
            <div class="table-responsive mt-3">
                <div class="px-4 d-flex justify-content-lg-end">
                    {{ $attempts->withQueryString()->links() }}
                </div>
            </div>
        @endif

    </div>

</div>
@endsection
