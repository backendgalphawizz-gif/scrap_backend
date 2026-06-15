@extends('layouts.back-end.app')

@section('title', 'Social Account Verifications')

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
    .status-pending      { background: #fef3c7; color: #92400e; }
    .status-verified     { background: #d1fae5; color: #065f46; }
    .status-not_verified { background: #fee2e2; color: #991b1b; }
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
            <span class="page-title-icon bg-gradient-info text-white me-2">
                <i class="mdi mdi-account-check"></i>
            </span>
            Social Account Verifications
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active">Social Verifications</li>
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

    <div class="alert alert-info d-flex align-items-start mb-3" role="alert">
        <i class="mdi mdi-information-outline me-2 mt-1 fs-5"></i>
        <div>
            <strong>Scraper Fallback Mode</strong> — The user must have posted the <em>Unique Code</em> in their social bio or a post.
            Verify by checking their account manually, then click <strong>Mark Verified</strong>.
            This updates their platform status to <em>verified</em> immediately.
        </div>
    </div>

    {{-- Filters --}}
    <div class="card filter-card mb-4">
        <div class="card-body py-3">
            <form method="GET" action="{{ route('admin.social-verifications') }}" class="row g-2 align-items-end">
                <div class="col-sm-6 col-md-3">
                    <label class="form-label mb-1 small fw-semibold">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="pending" {{ request('status', 'pending') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="" {{ request('status') === '' ? 'selected' : '' }}>All</option>
                        <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>Verified</option>
                        <option value="not_verified" {{ request('status') === 'not_verified' ? 'selected' : '' }}>Not Verified</option>
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
                <div class="col-sm-6 col-md-2">
                    <label class="form-label mb-1 small fw-semibold">Account Type</label>
                    <select name="account_type" class="form-select form-select-sm">
                        <option value="">All</option>
                        <option value="user"  {{ request('account_type') === 'user'  ? 'selected' : '' }}>User</option>
                        <option value="brand" {{ request('account_type') === 'brand' ? 'selected' : '' }}>Brand</option>
                    </select>
                </div>
                <div class="col-sm-6 col-md-2">
                    <label class="form-label mb-1 small fw-semibold">Unique Code</label>
                    <input type="text" name="unique_code" value="{{ request('unique_code') }}"
                           class="form-control form-control-sm" placeholder="Search…">
                </div>
                <div class="col-sm-6 col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm flex-fill">
                        <i class="mdi mdi-filter me-1"></i>Filter
                    </button>
                    <a href="{{ route('admin.social-verifications') }}" class="btn btn-outline-secondary btn-sm flex-fill">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between py-2">
                    <span class="fw-semibold">Social Verification Requests</span>
                    <span class="badge bg-secondary">{{ $transactions->total() }} record(s)</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light text-capitalize">
                                <tr>
                                    <th style="width:50px">#</th>
                                    <th>Account</th>
                                    <th>Type</th>
                                    <th>Platform</th>
                                    <th>Username</th>
                                    <th>Unique Code</th>
                                    <th>Status</th>
                                    <th>Submitted</th>
                                    <th style="width:130px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $key => $txn)
                                @php
                                    $name = $txn->user->name ?? ($txn->seller ? trim(($txn->seller->f_name ?? '') . ' ' . ($txn->seller->l_name ?? '')) : null);
                                    $viewLink = $txn->user_id ? route('admin.user.view', $txn->user_id) : null;
                                @endphp
                                <tr>
                                    <td class="text-muted small">{{ $transactions->firstItem() + $key }}</td>
                                    <td>
                                        @if($name)
                                            @if($viewLink)
                                                <a href="{{ $viewLink }}" target="_blank" class="text-dark fw-semibold small">{{ $name }}</a>
                                            @else
                                                <span class="fw-semibold small">{{ $name }}</span>
                                            @endif
                                            <div class="text-muted" style="font-size:0.72rem">
                                                {{ $txn->user_id ? 'User #'.$txn->user_id : 'Brand #'.$txn->seller_id }}
                                            </div>
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ $txn->user_id ? 'bg-primary' : 'bg-warning text-dark' }} small">
                                            {{ $txn->user_id ? 'User' : 'Brand' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="small text-capitalize">
                                            @if($txn->platform === 'instagram') <i class="mdi mdi-instagram text-danger"></i>
                                            @elseif($txn->platform === 'facebook') <i class="mdi mdi-facebook text-primary"></i>
                                            @else <i class="mdi mdi-at text-dark"></i>
                                            @endif
                                            {{ $txn->platform }}
                                        </span>
                                    </td>
                                    <td class="small">{{ $txn->username }}</td>
                                    <td>
                                        <span class="code-badge" title="Click to copy" onclick="copyCode(this)">{{ $txn->unique_code }}</span>
                                    </td>
                                    <td>
                                        <span class="status-badge status-{{ $txn->status }}">{{ str_replace('_', ' ', $txn->status) }}</span>
                                        @if($txn->manually_verified)
                                            <span class="manual-badge">manual</span>
                                        @endif
                                        @if($txn->failure_reason)
                                            <div class="text-danger small mt-1" style="max-width:160px; white-space:normal">{{ $txn->failure_reason }}</div>
                                        @endif
                                    </td>
                                    <td class="small text-muted">{{ \App\CPU\Helpers::formatAdminDateTime($txn->submitted_at) }}</td>
                                    <td>
                                        @if($txn->status !== 'verified')
                                            <button type="button"
                                                class="btn btn-info btn-sm text-white"
                                                onclick="openVerifyModal({{ $txn->id }}, '{{ addslashes($txn->unique_code) }}', '{{ addslashes($name ?? 'Account #'.$txn->id) }}', '{{ $txn->platform }}', '{{ addslashes($txn->username) }}')">
                                                <i class="mdi mdi-check"></i> Verify
                                            </button>
                                        @else
                                            <span class="text-success small"><i class="mdi mdi-check-circle"></i> Verified</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">
                                        <i class="mdi mdi-account-check-outline fs-3 d-block mb-2"></i>
                                        No pending social verifications found.
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
                    <i class="mdi mdi-account-check text-info me-2"></i>Confirm Manual Verification
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-light border mb-3">
                    <div class="small text-muted mb-1">Verifying <span id="modal-platform" class="fw-semibold text-capitalize"></span> for:</div>
                    <strong id="modal-user-name"></strong>
                    <div class="small text-muted">@<span id="modal-username"></span></div>
                </div>
                <p class="mb-1 small text-muted">Confirm the user's post/bio contains this unique code:</p>
                <div class="text-center my-3">
                    <span class="code-badge fs-6 px-3 py-2" id="modal-unique-code" onclick="copyCode(this)"></span>
                    <div class="text-muted small mt-1"><i class="mdi mdi-content-copy"></i> click to copy</div>
                </div>
                <p class="text-muted small mb-0">
                    This will set the account's <strong><span id="modal-platform2" class="text-capitalize"></span> status</strong> to <em>verified</em>
                    and allow the user to participate in campaigns on that platform.
                </p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="verifyForm" method="POST" action="">
                    @csrf
                    <button type="submit" class="btn btn-info text-white">
                        <i class="mdi mdi-check me-1"></i> Yes, Mark Verified
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    function openVerifyModal(id, code, userName, platform, username) {
        document.getElementById('modal-unique-code').textContent = code;
        document.getElementById('modal-user-name').textContent   = userName;
        document.getElementById('modal-username').textContent    = username;
        document.getElementById('modal-platform').textContent    = platform;
        document.getElementById('modal-platform2').textContent   = platform;
        document.getElementById('verifyForm').action = '/admin/social-verifications/' + id + '/manual-verify';
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
