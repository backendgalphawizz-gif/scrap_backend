@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Campaign Refund'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
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

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        {{-- Campaign & Brand Info --}}
        <div class="col-lg-5 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">{{ \App\CPU\translate('Campaign Details') }}</h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5 text-muted">{{ \App\CPU\translate('Campaign ID') }}</dt>
                        <dd class="col-sm-7">#{{ $campaign->id }}</dd>

                        <dt class="col-sm-5 text-muted">{{ \App\CPU\translate('Title') }}</dt>
                        <dd class="col-sm-7">{{ $campaign->title }}</dd>

                        <dt class="col-sm-5 text-muted">{{ \App\CPU\translate('Brand') }}</dt>
                        <dd class="col-sm-7">{{ optional($campaign->brand)->username ?? 'N/A' }}</dd>

                        <dt class="col-sm-5 text-muted">{{ \App\CPU\translate('Status') }}</dt>
                        <dd class="col-sm-7">
                            <span class="badge bg-danger">{{ ucfirst($campaign->status) }}</span>
                        </dd>

                        <dt class="col-sm-5 text-muted">{{ \App\CPU\translate('Stopped At') }}</dt>
                        <dd class="col-sm-7">{{ $campaign->stopped_at ? \Carbon\Carbon::parse($campaign->stopped_at)->format('d/m/Y H:i') : 'N/A' }}</dd>

                        <dt class="col-sm-5 text-muted">{{ \App\CPU\translate('Start Date') }}</dt>
                        <dd class="col-sm-7">{{ $campaign->start_date ? \Carbon\Carbon::parse($campaign->start_date)->format('d/m/Y') : 'N/A' }}</dd>

                        <dt class="col-sm-5 text-muted">{{ \App\CPU\translate('End Date') }}</dt>
                        <dd class="col-sm-7">{{ $campaign->end_date ? \Carbon\Carbon::parse($campaign->end_date)->format('d/m/Y') : 'N/A' }}</dd>

                        <dt class="col-sm-5 text-muted">{{ \App\CPU\translate('Total Slots') }}</dt>
                        <dd class="col-sm-7">{{ $campaign->total_user_required }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        {{-- Refund Calculation Breakdown --}}
        <div class="col-lg-7 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ \App\CPU\translate('Refund Calculation') }}</h5>
                    @if($campaign->refund_status === 'processed')
                        <span class="badge bg-success">{{ \App\CPU\translate('Already Processed') }}</span>
                    @endif
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered mb-0">
                        <tbody>
                            <tr>
                                <td class="text-muted">{{ \App\CPU\translate('Total Campaign Budget (with GST)') }}</td>
                                <td class="text-end fw-semibold">₹{{ number_format($refundData['total_budget_gst'], 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">{{ \App\CPU\translate('Utilized Slots') }} <small class="text-info">(pending / active / approved / completed)</small></td>
                                <td class="text-end">{{ $refundData['utilized_slots'] }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">{{ \App\CPU\translate('Reward Per User') }}</td>
                                <td class="text-end">₹{{ number_format($refundData['reward_per_user'], 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">{{ \App\CPU\translate('Utilized Budget (raw)') }} <small class="text-muted">({{ $refundData['utilized_slots'] }} × ₹{{ number_format($refundData['reward_per_user'], 2) }})</small></td>
                                <td class="text-end">₹{{ number_format($refundData['utilized_raw'], 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">{{ \App\CPU\translate('GST') }} ({{ $refundData['gst_percentage'] }}%)</td>
                                <td class="text-end">₹{{ number_format($refundData['utilized_with_gst'] - $refundData['utilized_raw'], 2) }}</td>
                            </tr>
                            <tr class="table-warning">
                                <td class="fw-semibold">{{ \App\CPU\translate('Utilized Budget (with GST)') }}</td>
                                <td class="text-end fw-semibold">₹{{ number_format($refundData['utilized_with_gst'], 2) }}</td>
                            </tr>
                            <tr class="table-success">
                                <td class="fw-bold text-success">{{ \App\CPU\translate('Refundable Amount') }} <small>(Total − Utilized)</small></td>
                                <td class="text-end fw-bold text-success fs-5">₹{{ number_format($refundData['refundable_amount'], 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">{{ \App\CPU\translate("Brand's Current Wallet Balance") }}</small><br>
                            <span class="fs-5 fw-bold text-primary">₹{{ number_format($sellerWallet->wallet_amount, 2) }}</span>
                        </div>
                        @if($campaign->refund_status === 'processed')
                            <div class="text-end">
                                <small class="text-muted">{{ \App\CPU\translate('Refunded Amount') }}</small><br>
                                <span class="fs-5 fw-bold text-success">₹{{ number_format($campaign->refunded_amount, 2) }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Process Refund Form --}}
    @if($campaign->refund_status !== 'processed')
        <div class="row">
            <div class="col-lg-7 offset-lg-5">
                <div class="card border-danger">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">{{ \App\CPU\translate('Process Refund') }}</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.campaign.process-refund', $campaign->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">{{ \App\CPU\translate('Refund Note') }} <small class="text-muted">({{ \App\CPU\translate('optional') }})</small></label>
                                <textarea name="refund_note" class="form-control" rows="3" placeholder="{{ \App\CPU\translate('Internal note about this refund...') }}">{{ old('refund_note') }}</textarea>
                            </div>

                            <div class="alert alert-warning mb-3">
                                <i class="mdi mdi-alert me-1"></i>
                                {{ \App\CPU\translate('This will credit') }} <strong>₹{{ number_format($refundData['refundable_amount'], 2) }}</strong>
                                {{ \App\CPU\translate("to the brand's wallet. This action cannot be undone.") }}
                            </div>

                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ route('admin.campaign.show', $campaign->id) }}" class="btn btn-secondary">
                                    {{ \App\CPU\translate('Cancel') }}
                                </a>
                                <button type="submit" class="btn btn-danger"
                                    @if($refundData['refundable_amount'] <= 0) disabled title="{{ \App\CPU\translate('Nothing to refund') }}" @endif>
                                    <i class="mdi mdi-cash-refund me-1"></i>
                                    {{ \App\CPU\translate('Process Refund') }} (₹{{ number_format($refundData['refundable_amount'], 2) }})
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-lg-7 offset-lg-5">
                <div class="alert alert-success">
                    <i class="mdi mdi-check-circle me-1"></i>
                    {{ \App\CPU\translate('Refund of') }} <strong>₹{{ number_format($campaign->refunded_amount, 2) }}</strong>
                    {{ \App\CPU\translate('has already been processed and credited to the brand wallet.') }}
                    @if($campaign->refund_note)
                        <hr class="my-2">
                        <small><strong>{{ \App\CPU\translate('Note:') }}</strong> {{ $campaign->refund_note }}</small>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <div class="mt-3">
        <a href="{{ route('admin.campaign.show', $campaign->id) }}" class="btn btn-secondary">
            {{ \App\CPU\translate('Back to Campaign') }}
        </a>
    </div>
</div>
@endsection
