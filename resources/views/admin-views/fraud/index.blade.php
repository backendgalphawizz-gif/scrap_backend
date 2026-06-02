@extends('layouts.back-end.app')

@section('title', 'Fraud Monitor')

@push('css_or_js')
<style>
    .badge-clean    { background-color: #28a745; }
    .badge-watch    { background-color: #ffc107; color: #212529; }
    .badge-flagged  { background-color: #fd7e14; }
    .badge-blocked  { background-color: #dc3545; }
    .badge-critical { background-color: #dc3545; }
    .badge-high     { background-color: #fd7e14; }
    .badge-medium   { background-color: #ffc107; color: #212529; }
    .badge-low      { background-color: #6c757d; }
    .score-bar { height: 8px; border-radius: 4px; background: #e9ecef; }
    .score-fill { height: 100%; border-radius: 4px; }
</style>
@endpush

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-header">
                <h3 class="page-title">
                    <i class="mdi mdi-shield-alert text-danger"></i>
                    Fraud Monitor
                    <span class="badge badge-danger ml-2">{{ $users->total() }}</span>
                </h3>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                </div>
            @endif

            {{-- Filters --}}
            <div class="card mb-3">
                <div class="card-body py-3">
                    <form method="GET" action="{{ route('admin.fraud.index') }}" class="form-inline flex-wrap gap-2">
                        <input type="text" name="search" class="form-control mr-2 mb-2"
                               placeholder="Name / Mobile / Email"
                               value="{{ request('search') }}">

                        <select name="fraud_status" class="form-control mr-2 mb-2">
                            <option value="">All Statuses</option>
                            <option value="watch"   {{ request('fraud_status') === 'watch'   ? 'selected' : '' }}>Watch</option>
                            <option value="flagged" {{ request('fraud_status') === 'flagged' ? 'selected' : '' }}>Flagged</option>
                            <option value="blocked" {{ request('fraud_status') === 'blocked' ? 'selected' : '' }}>Blocked</option>
                        </select>

                        <input type="date" name="date_from" class="form-control mr-2 mb-2"
                               value="{{ request('date_from') }}" placeholder="From">
                        <input type="date" name="date_to" class="form-control mr-2 mb-2"
                               value="{{ request('date_to') }}" placeholder="To">

                        <button type="submit" class="btn btn-primary mb-2">Filter</button>
                        <a href="{{ route('admin.fraud.index') }}" class="btn btn-secondary mb-2 ml-1">Reset</a>
                    </form>
                </div>
            </div>

            {{-- Table --}}
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>User</th>
                                    <th>Fraud Score</th>
                                    <th>Status</th>
                                    <th>Active Signals</th>
                                    <th>Wallet Frozen</th>
                                    <th>Last Checked</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $key => $user)
                                <tr>
                                    <td>{{ $users->firstItem() + $key }}</td>
                                    <td>
                                        <strong>{{ $user->name }}</strong><br>
                                        <small class="text-muted">{{ $user->mobile }}</small>
                                        @if($user->email)
                                            <br><small class="text-muted">{{ $user->email }}</small>
                                        @endif
                                    </td>
                                    <td style="min-width:120px;">
                                        <div class="d-flex align-items-center">
                                            <span class="mr-2 font-weight-bold
                                                {{ $user->fraud_score >= 80 ? 'text-danger' :
                                                  ($user->fraud_score >= 50 ? 'text-warning' :
                                                  ($user->fraud_score >= 20 ? 'text-info' : 'text-success')) }}">
                                                {{ $user->fraud_score }}
                                            </span>
                                            <div class="score-bar flex-grow-1">
                                                <div class="score-fill"
                                                     style="width:{{ $user->fraud_score }}%;
                                                            background:{{ $user->fraud_score >= 80 ? '#dc3545' :
                                                                         ($user->fraud_score >= 50 ? '#fd7e14' :
                                                                         ($user->fraud_score >= 20 ? '#ffc107' : '#28a745')) }}">
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $user->fraud_status }} text-capitalize px-2 py-1">
                                            {{ ucfirst($user->fraud_status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-secondary">{{ $user->active_signals_count }}</span>
                                    </td>
                                    <td>
                                        @if($user->coinWallet && $user->coinWallet->withdrawal_frozen)
                                            <span class="badge badge-danger">Frozen</span>
                                        @else
                                            <span class="badge badge-success">Active</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $user->last_fraud_check_at ? $user->last_fraud_check_at->diffForHumans() : '—' }}</small>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.fraud.show', $user->id) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="mdi mdi-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        No flagged users found.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($users->hasPages())
                    <div class="px-3 py-2">
                        {{ $users->links() }}
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
