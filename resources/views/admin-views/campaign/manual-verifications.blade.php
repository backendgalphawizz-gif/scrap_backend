@extends('layouts.back-end.app')

@section('title', 'Manual Post Verification')

@push('css_or_js')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    .code-badge {
        font-family: 'Courier New', monospace;
        font-size: 0.82rem;
        background: #f3f4f6;
        border: 1px solid #d1d5db;
        border-radius: 4px;
        padding: 2px 7px;
        color: #1f2937;
        cursor: pointer;
        user-select: all;
    }
    .code-badge:hover { background: #e5e7eb; }
    .status-pending   { background: #fef3c7; color: #92400e; }
    .status-active    { background: #dbeafe; color: #1e40af; }
    .status-flagged   { background: #fee2e2; color: #991b1b; }
    .status-approved  { background: #d1fae5; color: #065f46; }
    .status-badge {
        display: inline-block;
        padding: 2px 10px;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: capitalize;
    }
    .manual-badge {
        font-size: 0.7rem;
        background: #ede9fe;
        color: #5b21b6;
        border-radius: 4px;
        padding: 1px 6px;
        vertical-align: middle;
        margin-left: 4px;
    }
    .filter-card { border: 1px solid #e2e8f0; border-radius: 8px; }
    .premium-pagination-wrap {
        border-top: 1px solid #e8ebef;
        margin-top: 22px;
        padding: 12px 18px 16px;
    }
    .premium-pagination-shell {
        display: flex;
        justify-content: flex-end;
        align-items: center;
    }
    .premium-pagination-inline {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        overflow-x: auto;
    }
    .premium-pagination-nav { float: none; margin: 0; flex: 0 0 auto; }
    .premium-pagination-shell .pagination { margin: 0; }
</style>
@endpush

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-warning text-white me-2">
                <i class="mdi mdi-shield-check"></i>
            </span>
            Manual Post Verification
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.campaign.list') }}">Campaigns</a></li>
                <li class="breadcrumb-item active">Manual Verification</li>
            </ul>
        </nav>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="mdi mdi-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="mdi mdi-alert-circle me-1"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Alert banner --}}
    <div class="alert alert-warning d-flex align-items-start mb-3" role="alert">
        <i class="mdi mdi-alert-outline me-2 mt-1 fs-5"></i>
        <div>
            <strong>Scraper Fallback Mode</strong> — Use this page when the Python scraper is down.
            Copy the <em>Unique Code</em> shown below to confirm the user's post contains it, then click <strong>Manually Verify</strong>.
            Coins are released immediately if the campaign end date has passed; otherwise they are held until the campaign completes.
        </div>
    </div>

    {{-- Filters --}}
    <div class="card filter-card mb-4">
        <div class="card-body py-3">
            <form method="GET" action="{{ route('admin.manual-verifications') }}" class="row g-2 align-items-end">
                <div class="col-sm-6 col-md-3">
                    <label class="form-label mb-1 small fw-semibold">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All pending</option>
                        @foreach(['pending','active','flagged','approved'] as $s)
                            <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-6 col-md-3">
                    <label class="form-label mb-1 small fw-semibold">Platform</label>
                    <select name="platform" class="form-select form-select-sm">
                        <option value="">All platforms</option>
                        <option value="instagram" {{ request('platform') === 'instagram' ? 'selected' : '' }}>Instagram</option>
                        <option value="facebook"  {{ request('platform') === 'facebook'  ? 'selected' : '' }}>Facebook</option>
                        <option value="threads"   {{ request('platform') === 'threads'   ? 'selected' : '' }}>Threads</option>
                    </select>
                </div>
                <div class="col-sm-6 col-md-3">
                    <label class="form-label mb-1 small fw-semibold">Unique Code</label>
                    <input type="text" name="unique_code" value="{{ request('unique_code') }}"
                           class="form-control form-control-sm" placeholder="Search code…">
                </div>
                <div class="col-sm-6 col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm flex-fill">
                        <i class="mdi mdi-filter me-1"></i>Filter
                    </button>
                    <a href="{{ route('admin.manual-verifications') }}" class="btn btn-outline-secondary btn-sm flex-fill">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between py-2">
                    <span class="fw-semibold">Pending / Flagged Posts</span>
                    <span class="badge bg-secondary">{{ $transactions->total() }} record(s)</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light text-capitalize">
                                <tr>
                                    <th style="width:50px">#</th>
                                    <th>User</th>
                                    <th>Campaign</th>
                                    <th>Platform</th>
                                    <th>Unique Code</th>
                                    <th>Status</th>
                                    <th>Days</th>
                                    <th>Post URL</th>
                                    <th>Joined</th>
                                    <th style="width:120px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $key => $txn)
                                <tr id="row-{{ $txn->id }}">
                                    <td class="text-muted small">{{ $transactions->firstItem() + $key }}</td>
                                    <td>
                                        @if($txn->user)
                                            <a href="{{ route('admin.user.view', $txn->user_id) }}" target="_blank" class="text-dark fw-semibold small">
                                                {{ $txn->user->name }}
                                            </a>
                                            <div class="text-muted" style="font-size:0.72rem">ID #{{ $txn->user_id }}</div>
                                        @else
                                            <span class="text-muted small">User #{{ $txn->user_id }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($txn->campaign)
                                            <a href="{{ route('admin.campaign.show', $txn->campaign_id) }}" target="_blank" class="small text-dark fw-semibold">
                                                {{ Str::limit($txn->campaign->title ?? '', 30) }}
                                            </a>
                                            @if($txn->campaign->brand)
                                                <div class="text-muted" style="font-size:0.72rem">{{ $txn->campaign->brand->username ?? '' }}</div>
                                            @endif
                                        @else
                                            <span class="text-muted small">#{{ $txn->campaign_id }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="small text-capitalize">
                                            @if($txn->shared_on === 'instagram') <i class="mdi mdi-instagram text-danger"></i>
                                            @elseif($txn->shared_on === 'facebook') <i class="mdi mdi-facebook text-primary"></i>
                                            @else <i class="mdi mdi-at text-dark"></i>
                                            @endif
                                            {{ $txn->shared_on }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="code-badge" title="Click to copy" onclick="copyCode(this)">{{ $txn->unique_code }}</span>
                                    </td>
                                    <td>
                                        <span class="status-badge status-{{ $txn->status }}">{{ $txn->status }}</span>
                                        @if($txn->manually_verified)
                                            <span class="manual-badge">manual</span>
                                        @endif
                                        @if($txn->violation_reason)
                                            <div class="text-danger small mt-1" style="max-width:160px; white-space:normal">{{ $txn->violation_reason }}</div>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="fw-semibold">{{ $txn->day_status }}</span>
                                        <span class="text-muted small"> / {{ env('CAMPAIGN_VERIFICATION_DAYS', 2) }}</span>
                                    </td>
                                    <td>
                                        @if($txn->post_url)
                                            <a href="{{ $txn->post_url }}" target="_blank" class="btn btn-outline-secondary btn-sm py-0 px-1" title="Open post">
                                                <i class="mdi mdi-open-in-new"></i>
                                            </a>
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>
                                    <td class="small text-muted">{{ \App\CPU\Helpers::formatAdminDate($txn->created_at) }}</td>
                                    <td>
                                        @if(!in_array($txn->status, ['completed','deleted','rejected']))
                                            <button type="button"
                                                class="btn btn-success btn-sm"
                                                onclick="openVerifyModal({{ $txn->id }}, '{{ addslashes($txn->unique_code) }}', '{{ addslashes($txn->user->name ?? 'User #'.$txn->user_id) }}')">
                                                <i class="mdi mdi-check"></i> Verify
                                            </button>
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center text-muted py-4">
                                        <i class="mdi mdi-check-circle-outline fs-3 d-block mb-2"></i>
                                        No pending transactions found.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($transactions->hasPages())
                    <div class="premium-pagination-wrap">
                        <div class="premium-pagination-shell">
                            <div class="premium-pagination-inline">
                                {!! $transactions->onEachSide(1)->links('vendor.pagination.premium') !!}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Confirm Verify Modal --}}
<div class="modal fade" id="verifyModal" tabindex="-1" aria-labelledby="verifyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="verifyModalLabel">
                    <i class="mdi mdi-shield-check text-success me-2"></i>Confirm Manual Verification
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-light border mb-3">
                    <div class="small text-muted mb-1">Verifying post for:</div>
                    <strong id="modal-user-name"></strong>
                </div>
                <p class="mb-1 small text-muted">The post must contain this unique code:</p>
                <div class="text-center my-3">
                    <span class="code-badge fs-6 px-3 py-2" id="modal-unique-code" onclick="copyCode(this)"></span>
                    <div class="text-muted small mt-1"><i class="mdi mdi-content-copy"></i> click to copy</div>
                </div>
                <p class="text-muted small mb-0">
                    Confirming this action will mark the post as <strong>approved</strong> and release coins if the campaign end date has passed.
                    This action is logged and cannot be automatically undone.
                </p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="verifyForm" method="POST" action="">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="mdi mdi-check me-1"></i> Yes, Manually Verify
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    function openVerifyModal(id, code, userName) {
        document.getElementById('modal-unique-code').textContent = code;
        document.getElementById('modal-user-name').textContent   = userName;
        document.getElementById('verifyForm').action = '/admin/campaign-transaction/' + id + '/manual-verify';
        var modal = new bootstrap.Modal(document.getElementById('verifyModal'));
        modal.show();
    }

    function copyCode(el) {
        const text = el.textContent.trim();
        if (navigator.clipboard) {
            navigator.clipboard.writeText(text).then(function () {
                const original = el.style.background;
                el.style.background = '#bbf7d0';
                setTimeout(() => { el.style.background = original; }, 800);
            });
        } else {
            const ta = document.createElement('textarea');
            ta.value = text;
            document.body.appendChild(ta);
            ta.select();
            document.execCommand('copy');
            document.body.removeChild(ta);
        }
    }
</script>
@endpush
