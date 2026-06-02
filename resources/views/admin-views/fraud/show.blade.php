@extends('layouts.back-end.app')

@section('title', 'Fraud Detail — ' . $user->name)

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
    .signal-type-pill { font-family: monospace; font-size: 0.82rem; }
</style>
@endpush

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-sm-12">

            <div class="page-header d-flex align-items-center justify-content-between">
                <h3 class="page-title mb-0">
                    <i class="mdi mdi-shield-alert text-danger"></i>
                    Fraud Detail
                </h3>
                <a href="{{ route('admin.fraud.index') }}" class="btn btn-secondary btn-sm">
                    <i class="mdi mdi-arrow-left"></i> Back to List
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                </div>
            @endif

            <div class="row">

                {{-- User Info Card --}}
                <div class="col-md-4">
                    <div class="card mb-3">
                        <div class="card-header"><strong>User Info</strong></div>
                        <div class="card-body">
                            <p class="mb-1"><strong>Name:</strong> {{ $user->name }}</p>
                            <p class="mb-1"><strong>Mobile:</strong> {{ $user->mobile }}</p>
                            <p class="mb-1"><strong>Email:</strong> {{ $user->email ?? '—' }}</p>
                            <p class="mb-1"><strong>Device ID:</strong>
                                <span class="text-muted" style="font-family:monospace;font-size:0.8rem;">
                                    {{ $user->device_id ?? '—' }}
                                </span>
                            </p>
                            <p class="mb-1"><strong>Instagram:</strong> {{ $user->instagram_username ?? '—' }}
                                @if($user->instagram_status === 'verified')
                                    <span class="badge badge-success">verified</span>
                                @endif
                            </p>
                            <p class="mb-1"><strong>Facebook:</strong> {{ $user->facebook_username ?? '—' }}
                                @if($user->facebook_status === 'verified')
                                    <span class="badge badge-success">verified</span>
                                @endif
                            </p>
                            <hr>
                            <p class="mb-1">
                                <strong>Fraud Score:</strong>
                                <span class="font-weight-bold
                                    {{ $user->fraud_score >= 80 ? 'text-danger' :
                                      ($user->fraud_score >= 50 ? 'text-warning' :
                                      ($user->fraud_score >= 20 ? 'text-info' : 'text-success')) }}">
                                    {{ $user->fraud_score }} / 100
                                </span>
                            </p>
                            <p class="mb-0">
                                <strong>Status:</strong>
                                <span class="badge badge-{{ $user->fraud_status }}">{{ ucfirst($user->fraud_status) }}</span>
                            </p>
                        </div>
                    </div>

                    {{-- Wallet Card --}}
                    <div class="card mb-3">
                        <div class="card-header"><strong>Wallet</strong></div>
                        <div class="card-body">
                            @if($user->coinWallet)
                                <p class="mb-1"><strong>Balance:</strong> {{ $user->coinWallet->balance }} coins</p>
                                <p class="mb-2">
                                    <strong>Withdrawals:</strong>
                                    @if($user->coinWallet->withdrawal_frozen)
                                        <span class="badge badge-danger">Frozen</span>
                                    @else
                                        <span class="badge badge-success">Active</span>
                                    @endif
                                </p>
                            @else
                                <p class="text-muted">No wallet found.</p>
                            @endif
                        </div>
                    </div>

                    {{-- Danger Zone --}}
                    <div class="card border-danger mb-3">
                        <div class="card-header bg-danger text-white"><strong>Actions</strong></div>
                        <div class="card-body">
                            @if($user->fraud_status !== 'blocked')
                            <form method="POST" action="{{ route('admin.fraud.user.block', $user->id) }}"
                                  onsubmit="return confirm('Block this user and freeze their wallet?')">
                                @csrf
                                <div class="form-group">
                                    <input type="text" name="reason" class="form-control form-control-sm mb-2"
                                           placeholder="Reason (optional)">
                                </div>
                                <button type="submit" class="btn btn-danger btn-sm btn-block">
                                    <i class="mdi mdi-block-helper"></i> Block User
                                </button>
                            </form>
                            <hr>
                            @endif

                            <form method="POST" action="{{ route('admin.fraud.user.clear', $user->id) }}"
                                  onsubmit="return confirm('Clear ALL signals and restore this user to clean status?')">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm btn-block">
                                    <i class="mdi mdi-check-circle"></i> Clear All & Mark Clean
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Signals Timeline --}}
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <strong>Fraud Signals ({{ $user->fraudSignals->count() }} total)</strong>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Type</th>
                                            <th>Value</th>
                                            <th>Severity</th>
                                            <th>Meta</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($user->fraudSignals as $signal)
                                        <tr class="{{ $signal->resolved ? 'table-light text-muted' : '' }}">
                                            <td>
                                                <span class="signal-type-pill badge badge-secondary">
                                                    {{ str_replace('_', ' ', $signal->signal_type) }}
                                                </span>
                                            </td>
                                            <td>
                                                <small style="font-family:monospace;word-break:break-all;">
                                                    {{ $signal->signal_value ?? '—' }}
                                                </small>
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $signal->severity }}">
                                                    {{ ucfirst($signal->severity) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($signal->meta)
                                                    @foreach($signal->meta as $k => $v)
                                                        <small><strong>{{ $k }}:</strong> {{ $v }}</small><br>
                                                    @endforeach
                                                @else
                                                    <small class="text-muted">—</small>
                                                @endif
                                            </td>
                                            <td>
                                                <small>{{ $signal->created_at->format('d M Y H:i') }}</small>
                                            </td>
                                            <td>
                                                @if($signal->resolved)
                                                    <span class="badge badge-success">Resolved</span>
                                                    <br><small class="text-muted">{{ $signal->resolved_at?->format('d M Y') }}</small>
                                                @else
                                                    <span class="badge badge-warning">Open</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if(!$signal->resolved)
                                                <form method="POST"
                                                      action="{{ route('admin.fraud.signal.resolve', $signal->id) }}">
                                                    @csrf
                                                    <button type="submit" class="btn btn-xs btn-outline-success">
                                                        <i class="mdi mdi-check"></i> Resolve
                                                    </button>
                                                </form>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-3">No signals recorded.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
