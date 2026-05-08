@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Campaign Refund'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
@php
    $brand = $campaign->brand;
    $hasBankDetails = $brand && $brand->bank_account_number;
@endphp
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-danger text-white me-2">
                <i class="mdi mdi-cash-refund"></i>
            </span> {{ \App\CPU\translate('Campaign Refund') }}
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.campaign.list') }}">{{ \App\CPU\translate('Campaigns') }}</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.campaign.show', $campaign->id) }}">#{{ $campaign->id }}</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">{{ \App\CPU\translate('Refund') }}</li>
            </ul>
        </nav>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="mdi mdi-check-circle me-1"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        {{-- Campaign Summary --}}
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">{{ \App\CPU\translate('Campaign Details') }}</h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5 text-muted">{{ \App\CPU\translate('ID') }}</dt>
                        <dd class="col-sm-7">#{{ $campaign->id }}</dd>

                        <dt class="col-sm-5 text-muted">{{ \App\CPU\translate('Title') }}</dt>
                        <dd class="col-sm-7">{{ $campaign->title }}</dd>

                        <dt class="col-sm-5 text-muted">{{ \App\CPU\translate('Brand') }}</dt>
                        <dd class="col-sm-7">{{ optional($brand)->username ?? 'N/A' }}</dd>

                        <dt class="col-sm-5 text-muted">{{ \App\CPU\translate('Status') }}</dt>
                        <dd class="col-sm-7"><span class="badge bg-danger">{{ ucfirst($campaign->status) }}</span></dd>

                        <dt class="col-sm-5 text-muted">{{ \App\CPU\translate('Stopped At') }}</dt>
                        <dd class="col-sm-7">{{ $campaign->stopped_at ? \Carbon\Carbon::parse($campaign->stopped_at)->format('d/m/Y H:i') : 'N/A' }}</dd>

                        <dt class="col-sm-5 text-muted">{{ \App\CPU\translate('Total Slots') }}</dt>
                        <dd class="col-sm-7">{{ $campaign->total_user_required }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        {{-- Brand Bank Details --}}
        <div class="col-lg-4 mb-4">
            <div class="card h-100 {{ $hasBankDetails ? 'border-primary' : 'border-warning' }}">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ \App\CPU\translate('Brand Bank Details') }}</h5>
                    @if(!$hasBankDetails)
                        <span class="badge bg-warning text-dark">{{ \App\CPU\translate('Not Added') }}</span>
                    @elseif(optional($brand)->bank_status === 'Verified')
                        <span class="badge bg-success">{{ \App\CPU\translate('Verified') }}</span>
                    @else
                        <span class="badge bg-secondary">{{ ucfirst(optional($brand)->bank_status ?? '') }}</span>
                    @endif
                </div>
                <div class="card-body">
                    @if($hasBankDetails)
                        <dl class="row mb-0">
                            <dt class="col-sm-6 text-muted">{{ \App\CPU\translate('Account Holder') }}</dt>
                            <dd class="col-sm-6">{{ $brand->bank_account_holder_name ?? 'N/A' }}</dd>

                            <dt class="col-sm-6 text-muted">{{ \App\CPU\translate('Account Number') }}</dt>
                            <dd class="col-sm-6"><strong>{{ $brand->bank_account_number }}</strong></dd>

                            <dt class="col-sm-6 text-muted">{{ \App\CPU\translate('IFSC Code') }}</dt>
                            <dd class="col-sm-6"><strong>{{ $brand->bank_ifsc_code ?? 'N/A' }}</strong></dd>

                            <dt class="col-sm-6 text-muted">{{ \App\CPU\translate('Account Type') }}</dt>
                            <dd class="col-sm-6">{{ ucfirst($brand->bank_account_type ?? 'N/A') }}</dd>
                        </dl>
                    @else
                        <div class="alert alert-warning mb-0">
                            <i class="mdi mdi-alert me-1"></i>
                            {{ \App\CPU\translate('Brand has not added bank details yet. Ask the brand to add bank details via the app before processing the refund.') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Refund Calculation --}}
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ \App\CPU\translate('Refund Calculation') }}</h5>
                    @if($refundEntry)
                        @if($refundEntry->status === 'completed')
                            <span class="badge bg-success">{{ \App\CPU\translate('Completed') }}</span>
                        @else
                            <span class="badge bg-warning text-dark">{{ \App\CPU\translate('Pending') }}</span>
                        @endif
                    @endif
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-bordered mb-0">
                        <tbody>
                            <tr>
                                <td class="text-muted small">{{ \App\CPU\translate('Total Budget (with GST)') }}</td>
                                <td class="text-end fw-semibold">₹{{ number_format($refundData['total_budget_gst'], 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted small">{{ \App\CPU\translate('Utilized Slots') }} <span class="text-info">({{ $refundData['utilized_slots'] }})</span></td>
                                <td class="text-end">₹{{ number_format($refundData['utilized_raw'], 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted small">{{ \App\CPU\translate('GST on Utilized') }} ({{ $refundData['gst_percentage'] }}%)</td>
                                <td class="text-end">₹{{ number_format($refundData['utilized_with_gst'] - $refundData['utilized_raw'], 2) }}</td>
                            </tr>
                            <tr class="table-warning">
                                <td class="small fw-semibold">{{ \App\CPU\translate('Utilized (with GST)') }}</td>
                                <td class="text-end fw-semibold">₹{{ number_format($refundData['utilized_with_gst'], 2) }}</td>
                            </tr>
                            <tr class="table-success">
                                <td class="fw-bold text-success">{{ \App\CPU\translate('Refundable Amount') }}</td>
                                <td class="text-end fw-bold text-success fs-6">₹{{ number_format($refundData['refundable_amount'], 2) }}</td>
                            </tr>
                            @if($refundEntry && $refundEntry->status === 'completed')
                            <tr class="table-primary">
                                <td class="fw-bold">{{ \App\CPU\translate('Actually Refunded') }}</td>
                                <td class="text-end fw-bold text-primary fs-6">₹{{ number_format($refundEntry->refunded_amount, 2) }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Action Section --}}
    <div class="row">
        <div class="col-12">

            {{-- No refund entry yet: show Initiate Refund form --}}
            @if(!$refundEntry)
                <div class="card border-danger">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">{{ \App\CPU\translate('Initiate Refund') }}</h5>
                    </div>
                    <div class="card-body">
                        @if(!$hasBankDetails)
                            <div class="alert alert-warning">
                                <i class="mdi mdi-alert me-1"></i>
                                {{ \App\CPU\translate('Cannot initiate refund: brand has no bank details on file.') }}
                            </div>
                        @else
                            <form action="{{ route('admin.campaign.process-refund', $campaign->id) }}" method="POST">
                                @csrf
                                <p class="text-muted mb-3">
                                    {{ \App\CPU\translate('This will create a refund entry and snapshot the brand\'s current bank details. You must manually transfer') }}
                                    <strong>₹{{ number_format($refundData['refundable_amount'], 2) }}</strong>
                                    {{ \App\CPU\translate('to the account shown above, then mark it complete.') }}
                                </p>
                                <div class="mb-3">
                                    <label class="form-label">{{ \App\CPU\translate('Admin Note') }} <small class="text-muted">({{ \App\CPU\translate('optional') }})</small></label>
                                    <textarea name="admin_note" class="form-control" rows="2" placeholder="{{ \App\CPU\translate('Internal note...') }}">{{ old('admin_note') }}</textarea>
                                </div>
                                <div class="d-flex gap-2 justify-content-end">
                                    <a href="{{ route('admin.campaign.show', $campaign->id) }}" class="btn btn-secondary">{{ \App\CPU\translate('Cancel') }}</a>
                                    <button type="submit" class="btn btn-danger"
                                        @if($refundData['refundable_amount'] <= 0) disabled title="{{ \App\CPU\translate('Nothing to refund') }}" @endif>
                                        <i class="mdi mdi-cash-refund me-1"></i>
                                        {{ \App\CPU\translate('Initiate Refund') }} (₹{{ number_format($refundData['refundable_amount'], 2) }})
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>

            {{-- Refund entry is pending: show bank snapshot + Mark Complete form --}}
            @elseif($refundEntry->status === 'pending')
                <div class="card border-warning">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">
                            <i class="mdi mdi-clock-outline me-1"></i>
                            {{ \App\CPU\translate('Refund Pending — Transfer & Mark Complete') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info mb-4">
                            <strong>{{ \App\CPU\translate('Transfer') }} ₹{{ number_format($refundEntry->calculated_amount, 2) }} {{ \App\CPU\translate('to:') }}</strong>
                            <ul class="mb-0 mt-2">
                                <li>{{ \App\CPU\translate('Account Holder') }}: <strong>{{ $refundEntry->bank_account_holder_name ?? 'N/A' }}</strong></li>
                                <li>{{ \App\CPU\translate('Account Number') }}: <strong>{{ $refundEntry->bank_account_number ?? 'N/A' }}</strong></li>
                                <li>{{ \App\CPU\translate('IFSC') }}: <strong>{{ $refundEntry->bank_ifsc_code ?? 'N/A' }}</strong></li>
                                <li>{{ \App\CPU\translate('Account Type') }}: <strong>{{ ucfirst($refundEntry->bank_account_type ?? 'N/A') }}</strong></li>
                            </ul>
                        </div>
                        <form action="{{ route('admin.campaign.complete-refund', $campaign->id) }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">{{ \App\CPU\translate('Confirmed Refund Amount') }} <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">₹</span>
                                        <input type="number" step="0.01" min="0" name="confirmed_amount"
                                            class="form-control"
                                            value="{{ old('confirmed_amount', $refundEntry->calculated_amount) }}"
                                            required>
                                    </div>
                                    <small class="text-muted">{{ \App\CPU\translate('Pre-filled with calculated amount. Adjust if actual transfer differs.') }}</small>
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label">{{ \App\CPU\translate('Admin Note') }} <small class="text-muted">({{ \App\CPU\translate('optional') }})</small></label>
                                    <textarea name="admin_note" class="form-control" rows="2">{{ old('admin_note', $refundEntry->admin_note) }}</textarea>
                                </div>
                            </div>
                            <div class="mt-3 d-flex gap-2 justify-content-end">
                                <a href="{{ route('admin.campaign.show', $campaign->id) }}" class="btn btn-secondary">{{ \App\CPU\translate('Back') }}</a>
                                <button type="submit" class="btn btn-success">
                                    <i class="mdi mdi-check-circle me-1"></i>
                                    {{ \App\CPU\translate('Mark as Completed') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            {{-- Refund is completed --}}
            @else
                <div class="alert alert-success">
                    <i class="mdi mdi-check-circle me-1"></i>
                    {{ \App\CPU\translate('Refund of') }} <strong>₹{{ number_format($refundEntry->refunded_amount, 2) }}</strong>
                    {{ \App\CPU\translate('was completed on') }}
                    <strong>{{ $refundEntry->completed_at ? $refundEntry->completed_at->format('d/m/Y H:i') : 'N/A' }}</strong>.
                    @if($refundEntry->admin_note)
                        <hr class="my-2">
                        <small><strong>{{ \App\CPU\translate('Note:') }}</strong> {{ $refundEntry->admin_note }}</small>
                    @endif
                </div>
            @endif

        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('admin.campaign.show', $campaign->id) }}" class="btn btn-secondary">
            {{ \App\CPU\translate('Back to Campaign') }}
        </a>
    </div>
</div>
@endsection
