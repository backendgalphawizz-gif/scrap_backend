@extends('layouts.back-end.app')

@section('title', 'User Activity Logs')

@section('content')
<div class="content-wrapper">
    <div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h3 class="page-title mb-0">
            <span class="page-title-icon bg-gradient-success text-white me-2">
                <i class="mdi mdi-timeline-text-outline"></i>
            </span>
            User Activity Logs
        </h3>
        <a href="{{ route('admin.user.edit', $user->id) }}" class="btn btn-outline-primary">
            <i class="mdi mdi-account-edit-outline me-1"></i> Back to User
        </a>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div>
                    <h5 class="mb-1">{{ $user->name ?? 'N/A' }}</h5>
                    <div class="text-muted">User ID: {{ $user->id }} | Mobile: {{ $user->mobile ?? 'N/A' }}</div>
                </div>
                <span class="badge badge-gradient-success p-2">Total Logs: {{ $logs->total() }}</span>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.user.activity.logs', $user->id) }}" class="row g-2 mb-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="name" value="{{ request('name') }}" placeholder="Filter by event name">
                </div>
                <div class="col-md-3">
                    <input type="number" class="form-control" name="campaigns_id" value="{{ request('campaigns_id') }}" placeholder="Campaign ID">
                </div>
                <div class="col-md-5 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-filter-variant me-1"></i> Filter
                    </button>
                    <a href="{{ route('admin.user.activity.logs', $user->id) }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Time</th>
                            <th>Campaign</th>
                            <th>Event Name</th>
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $key => $log)
                            <tr>
                                <td>{{ $logs->firstItem() + $key }}</td>
                                <td>
                                    <div>{{ optional($log->created_at)->format('d M Y') }}</div>
                                    <small class="text-muted">{{ optional($log->created_at)->format('h:i A') }}</small>
                                </td>
                                <td>
                                    <div class="fw-bold">#{{ $log->campaigns_id }}</div>
                                    <small class="text-muted">{{ optional($log->campaign)->title ?? 'Campaign not found' }}</small>
                                </td>
                                <td>
                                    <span class="badge badge-info p-2">{{ $log->name }}</span>
                                </td>
                                <td style="min-width: 260px;">
                                    <pre class="mb-0 p-2 bg-light border rounded" style="white-space: pre-wrap; word-break: break-word; font-size: 12px;">{{ json_encode($log->data ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <img class="mb-3" src="{{ asset('public/assets/back-end/svg/illustrations/sorry.svg') }}" alt="No logs" width="120">
                                    <div class="text-muted">No activity logs found for this user.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($logs->count() > 0)
                <div class="d-flex justify-content-end mt-3">
                    {!! $logs->onEachSide(1)->links('vendor.pagination.premium') !!}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
