@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('brand_profile'))

@push('css_or_js')
    <style>
        :root {
            --brand-bg-soft: #f5f7ff;
            --brand-text-muted: #64748b;
            --brand-border: #e7ecf3;
            --brand-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
            --brand-title: #0f172a;
        }

        .brand-cover-card {
            border: 0;
            border-radius: 18px;
            background: linear-gradient(145deg, #4f46e5 0%, #7c3aed 45%, #8b5cf6 100%);
            color: #fff;
            overflow: hidden;
            position: relative;
        }

        .brand-cover-card::after {
            content: "";
            position: absolute;
            width: 220px;
            height: 220px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 999px;
            right: -70px;
            top: -70px;
        }

        .brand-avatar-lg {
            width: 92px;
            height: 92px;
            object-fit: cover;
            border: 3px solid rgba(255, 255, 255, 0.65);
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.25);
        }

        .brand-cover-card .card-body {
            z-index: 1;
        }

        .brand-mini-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.75rem;
            color: #fff;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 999px;
            padding: 5px 10px;
        }

        .brand-section-title {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--brand-title);
            font-size: 1rem;
            font-weight: 700;
            margin: 0;
        }

        .brand-section-title i {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            background: #e7ebff;
            color: #4f46e5;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
        }

        .brand-stat-card {
            border: 1px solid #e9eef5;
            border-radius: 14px;
            height: 100%;
            transition: 0.2s ease-in-out;
            background: #fff;
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.04);
        }

        .brand-stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(15, 23, 42, 0.08);
        }

        .brand-stat-label {
            font-size: 0.8rem;
            color: var(--brand-text-muted);
            margin-bottom: 4px;
        }

        .brand-stat-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: #eef2ff;
            color: #4f46e5;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            margin-bottom: 10px;
        }

        .brand-stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
            line-height: 1.1;
        }

        .brand-info-card {
            border-radius: 14px;
            border: 1px solid var(--brand-border);
            overflow: hidden;
            height: 100%;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.04);
        }

        .brand-info-card .card-header {
            background: var(--brand-bg-soft);
            border-bottom: 1px solid #edf2f7;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px dashed #edf2f7;
        }

        .detail-row:last-child {
            border-bottom: 0;
        }

        .detail-key {
            font-size: 0.85rem;
            color: #64748b;
            margin: 0;
        }

        .detail-value {
            font-size: 0.9rem;
            font-weight: 600;
            color: #0f172a;
            margin: 0;
            text-align: right;
            word-break: break-word;
        }

        .business-details-list .detail-row {
            display: grid;
            grid-template-columns: 44% 56%;
            align-items: center;
            gap: 0;
            border: 1px solid #eaf0f6;
            border-radius: 10px;
            padding: 0;
            margin-bottom: 10px;
            background: #fff;
        }

        .business-details-list .detail-row:last-child {
            margin-bottom: 0;
        }

        .business-details-list .detail-key,
        .business-details-list .detail-value {
            margin: 0;
            padding: 11px 14px;
        }

        .business-details-list .detail-key {
            border-right: 1px solid #eaf0f6;
            background: #f8fafc;
            color: #334155;
            font-weight: 600;
            position: relative;
        }

        .business-details-list .detail-key::after {
            content: ":";
            position: absolute;
            right: 10px;
            color: #64748b;
            font-weight: 700;
        }

        .business-details-list .detail-value {
            text-align: left;
            color: #0f172a;
            font-weight: 600;
        }

        .status-pill {
            border-radius: 999px;
            padding: 0.28rem 0.75rem;
            font-size: 0.76rem;
            font-weight: 600;
            display: inline-block;
        }

        .status-success {
            background: rgba(22, 163, 74, 0.12);
            color: #15803d;
        }

        .status-warning {
            background: rgba(245, 158, 11, 0.14);
            color: #b45309;
        }

        .status-danger {
            background: rgba(239, 68, 68, 0.12);
            color: #b91c1c;
        }

        .link-clean {
            color: #2563eb;
            text-decoration: none;
            font-weight: 600;
        }

        .link-clean:hover {
            text-decoration: underline;
        }

        .verification-form .form-group label {
            font-size: 0.82rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
        }

        .verification-form .form-control,
        .verification-form .form-select {
            border-color: var(--brand-border);
            min-height: 42px;
            border-radius: 10px;
            box-shadow: none !important;
        }

        .verification-form .form-select:focus {
            border-color: #7c3aed;
        }

        .verification-form .form-group {
            background: #fff;
            border: 1px solid var(--brand-border);
            border-radius: 12px;
            padding: 12px;
        }

        .verification-summary {
            background: #f8fafc;
            border: 1px solid var(--brand-border);
            border-radius: 10px;
            padding: 10px 12px;
        }

        .verification-current {
            margin-top: 8px;
            font-size: 0.74rem;
            color: #64748b;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
        }

        .badge-soft {
            border-radius: 999px;
            padding: 2px 9px;
            font-weight: 600;
            font-size: 0.72rem;
            border: 1px solid transparent;
        }

        .badge-soft-success {
            background: rgba(22, 163, 74, 0.1);
            color: #15803d;
            border-color: rgba(22, 163, 74, 0.2);
        }

        .badge-soft-warning {
            background: rgba(245, 158, 11, 0.12);
            color: #b45309;
            border-color: rgba(245, 158, 11, 0.24);
        }

        .badge-soft-danger {
            background: rgba(239, 68, 68, 0.1);
            color: #b91c1c;
            border-color: rgba(239, 68, 68, 0.22);
        }

        .badge-soft-neutral {
            background: rgba(100, 116, 139, 0.12);
            color: #475569;
            border-color: rgba(100, 116, 139, 0.22);
        }

        .brand-action-bar {
            border: 1px solid var(--brand-border);
            background: #fff;
            border-radius: 12px;
            padding: 12px;
        }

        @media (max-width: 767px) {
            .detail-row {
                flex-direction: column;
                gap: 4px;
            }

            .detail-value {
                text-align: left;
            }

            .brand-stat-value {
                font-size: 1.25rem;
            }

            .brand-action-bar {
                justify-content: center !important;
            }

            .business-details-list .detail-row {
                grid-template-columns: 1fr;
            }

            .business-details-list .detail-key {
                border-right: 0;
                border-bottom: 1px solid #eaf0f6;
            }

            .business-details-list .detail-key::after {
                right: 14px;
            }
        }
    </style>
@endpush

@section('content')
<div class="content-wrapper">

    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-store menu-icon"></i>
            </span> Brand User Profile
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.brand') }}">Brand Users</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <span></span>Brand Profile
                    <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-12 mb-3">
            <div class="card brand-cover-card">
                <div class="card-body p-4 p-md-5 position-relative">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <img class="rounded-circle brand-avatar-lg"
                                 src="{{ $seller->image }}"
                                 alt="Brand avatar"
                                 onerror="this.onerror=null;this.src='{{ asset('public/assets/front-end/img/image-place-holder.png') }}';" style="width: 60px; height: 60px;">
                            <div>
                                <h4 class="mb-1 text-white">{{ $seller->username ?: '-' }}</h4>
                                <div class="small text-white-50 mb-1">{{ $seller->f_name }} {{ $seller->l_name }}</div>
                                <div class="small text-white-50">{{ $seller->email ?: '-' }} | {{ $seller->phone ?: '-' }}</div>
                                <div class="mt-2 d-flex flex-wrap gap-2">
                                    <span class="brand-mini-chip"><i class="mdi mdi-clock-outline"></i> Joined {{ optional($seller->created_at)->format('d/m/Y') ?: '-' }}</span>
                                    <span class="brand-mini-chip"><i class="mdi mdi-bullseye-arrow"></i> Campaigns {{ $seller->campaigns_count ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="text-md-end">
                            <div class="mb-2">
                                @php
                                    $accountStatusClass = $seller->status === 'approved' ? 'status-success' : ($seller->status === 'pending' ? 'status-warning' : 'status-danger');
                                @endphp
                                <span class="status-pill {{ $accountStatusClass }}">
                                    Account: {{ ucfirst($seller->status ?? 'pending') }}
                                </span>
                            </div>
                            <div class="small text-white-50">Unique Code: {{ $seller->unique_code ?: '-' }}</div>
                            <div class="small text-white-50">Referral Code: {{ $seller->referral_code ?: '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card brand-stat-card">
                <div class="card-body business-details-list">
                    <span class="brand-stat-icon"><i class="mdi mdi-rocket-launch-outline"></i></span>
                    <p class="brand-stat-label">Total Campaigns</p>
                    <h3 class="brand-stat-value">{{ $seller->campaigns_count ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card brand-stat-card">
                <div class="card-body">
                    <span class="brand-stat-icon"><i class="mdi mdi-wallet-outline"></i></span>
                    <p class="brand-stat-label">Campaign Budget</p>
                    <h3 class="brand-stat-value">{{ number_format((float) ($seller->total_campaign_budget ?? 0), 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card brand-stat-card">
                <div class="card-body">
                    <span class="brand-stat-icon"><i class="mdi mdi-cash-multiple"></i></span>
                    <p class="brand-stat-label">Budget Spent</p>
                    <h3 class="brand-stat-value">{{ number_format((float) ($seller->total_campaign_budget_spent ?? 0), 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card brand-stat-card">
                <div class="card-body">
                    <span class="brand-stat-icon"><i class="mdi mdi-account-group-outline"></i></span>
                    <p class="brand-stat-label">Total Participants</p>
                    <h3 class="brand-stat-value">{{ $seller->total_campaign_participant ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-3">
            <div class="card brand-info-card">
                <div class="card-header">
                    <h5 class="brand-section-title"><i class="mdi mdi-domain"></i>Business Details</h5>
                </div>
                <div class="card-body">
                    <div class="detail-row d-flex justify-content-between align-items-center gap-2">
                        <p class="detail-key"><st</p>
                        <p class="detail-value">{{ $seller->username ?: '-' }}</p>
                    </div>
                    <div class="detail-row d-flex justify-content-between align-items-center gap-2"><p class="detail-key"><strong>Owner Name</strong></p><p class="detail-value">{{ trim($seller->f_name.' '.$seller->l_name) ?: '-' }}</p></div>
                    <div class="detail-row d-flex justify-content-between align-items-center gap-2"><p class="detail-key"><strong>Business Type</strong></p><p class="detail-value">{{ $seller->business_registeration_type ?: '-' }}</p></div>
                    <div class="detail-row d-flex justify-content-between align-items-center gap-2"><p class="detail-key"><strong>Email</strong></p><p class="detail-value">{{ $seller->email ?: '-' }}</p></div>
                    <div class="detail-row d-flex justify-content-between align-items-center gap-2"><p class="detail-key"><strong>Phone</strong></p><p class="detail-value">{{ $seller->phone ?: '-' }}</p></div>
                    <div class="detail-row d-flex justify-content-between align-items-center gap-2"><p class="detail-key"><strong>City / State</strong></p><p class="detail-value">{{ ($seller->city ?: '-') . ' / ' . ($seller->state ?: '-') }}</p></div>
                    <div class="detail-row d-flex justify-content-between align-items-center gap-2"><p class="detail-key"><strong>Primary Contact</strong></p><p class="detail-value">{{ $seller->primary_contact ?: '-' }}</p></div>
                    <div class="detail-row d-flex justify-content-between align-items-center gap-2"><p class="detail-key"><strong>Alternate Contact</strong>    </p><p class="detail-value">{{ $seller->alternate_contact ?: '-' }}</p></div>
                    <div class="detail-row d-flex justify-content-between align-items-center gap-2"><p class="detail-key"><strong>Address</strong></p><p class="detail-value">{{ $seller->full_address ?: '-' }}</p></div>
                    <div class="detail-row d-flex justify-content-between align-items-center gap-2">
                        <p class="detail-key"><strong>Google Map</strong></p>
                        <p class="detail-value">
                            @if($seller->google_map_link)
                                <a class="link-clean" href="{{ $seller->google_map_link }}" target="_blank" rel="noopener">Open Map</a>
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    <div class="detail-row d-flex justify-content-between align-items-center gap-2">
                        <p class="detail-key"><strong>Website</strong></p>
                        <p class="detail-value">
                            @if($seller->website_link)
                                <a class="link-clean" href="{{ $seller->website_link }}" target="_blank" rel="noopener">{{ $seller->website_link }}</a>
                            @else
                                -
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-3">
            <div class="card brand-info-card">
                <div class="card-header">
                    <h5 class="brand-section-title"><i class="mdi mdi-shield-check-outline"></i>Verification & Status Management</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form action="{{ route('admin.brand.updateStatus', [$seller->id]) }}" method="POST" class="mt-2 verification-form">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Instagram Verification Status</label>
                                    <select name="instagram_status" class="form-control form-select">
                                        <option value="not_verified" {{ $seller->instagram_status === 'not_verified' ? 'selected' : '' }}>Not Verified</option>
                                        <option value="pending" {{ $seller->instagram_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="verified" {{ $seller->instagram_status === 'verified' ? 'selected' : '' }}>Verified</option>
                                    </select>
                                    <div class="verification-current">
                                        <span>Current</span>
                                        <span class="badge-soft {{ $seller->instagram_status === 'verified' ? 'badge-soft-success' : ($seller->instagram_status === 'pending' ? 'badge-soft-warning' : 'badge-soft-neutral') }}">{{ ucfirst(str_replace('_', ' ', $seller->instagram_status ?? 'not verified')) }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Facebook Verification Status</label>
                                    <select name="facebook_status" class="form-control form-select">
                                        <option value="not_verified" {{ $seller->facebook_status === 'not_verified' ? 'selected' : '' }}>Not Verified</option>
                                        <option value="pending" {{ $seller->facebook_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="verified" {{ $seller->facebook_status === 'verified' ? 'selected' : '' }}>Verified</option>
                                    </select>
                                    <div class="verification-current">
                                        <span>Current</span>
                                        <span class="badge-soft {{ $seller->facebook_status === 'verified' ? 'badge-soft-success' : ($seller->facebook_status === 'pending' ? 'badge-soft-warning' : 'badge-soft-neutral') }}">{{ ucfirst(str_replace('_', ' ', $seller->facebook_status ?? 'not verified')) }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Brand Account Status</label>
                                    <select name="status" class="form-control form-select">
                                        <option value="approved" {{ $seller->status === 'approved' ? 'selected' : '' }}>Active</option>
                                        <option value="pending" {{ $seller->status === 'pending' ? 'selected' : '' }}>In-Active</option>
                                        <option value="banned" {{ $seller->status === 'banned' ? 'selected' : '' }}>Banned</option>
                                    </select>
                                    <div class="verification-current">
                                        <span>Current</span>
                                        <span class="badge-soft {{ $seller->status === 'approved' ? 'badge-soft-success' : ($seller->status === 'banned' ? 'badge-soft-danger' : 'badge-soft-warning') }}">{{ $seller->status === 'approved' ? 'Active' : ($seller->status === 'banned' ? 'Banned' : 'In-Active') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>GST Verification Status</label>
                                    <select name="gst_status" class="form-control form-select">
                                        <option value="Not Submitted" {{ $seller->gst_status === 'Not Submitted' ? 'selected' : '' }}>Not Submitted</option>
                                        <option value="Submitted" {{ $seller->gst_status === 'Submitted' ? 'selected' : '' }}>Submitted</option>
                                        <option value="Under Verification" {{ $seller->gst_status === 'Under Verification' ? 'selected' : '' }}>Under Verification</option>
                                        <option value="Verified" {{ $seller->gst_status === 'Verified' ? 'selected' : '' }}>Verified</option>
                                        <option value="Rejected" {{ $seller->gst_status === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                    <div class="verification-current">
                                        <span>Current</span>
                                        <span class="badge-soft {{ $seller->gst_status === 'Verified' ? 'badge-soft-success' : ($seller->gst_status === 'Rejected' ? 'badge-soft-danger' : 'badge-soft-warning') }}">{{ $seller->gst_status ?? 'Not Submitted' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>PAN Verification Status</label>
                                    <select name="pan_status" class="form-control form-select">
                                        <option value="Not Submitted" {{ $seller->pan_status === 'Not Submitted' ? 'selected' : '' }}>Not Submitted</option>
                                        <option value="Submitted" {{ $seller->pan_status === 'Submitted' ? 'selected' : '' }}>Submitted</option>
                                        <option value="Under Verification" {{ $seller->pan_status === 'Under Verification' ? 'selected' : '' }}>Under Verification</option>
                                        <option value="Verified" {{ $seller->pan_status === 'Verified' ? 'selected' : '' }}>Verified</option>
                                        <option value="Rejected" {{ $seller->pan_status === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                    <div class="verification-current">
                                        <span>Current</span>
                                        <span class="badge-soft {{ $seller->pan_status === 'Verified' ? 'badge-soft-success' : ($seller->pan_status === 'Rejected' ? 'badge-soft-danger' : 'badge-soft-warning') }}">{{ $seller->pan_status ?? 'Not Submitted' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div class="small text-muted verification-summary">
                                GST Number: <strong>{{ $seller->gst_number ?: 'N/A' }}</strong> | PAN Number: <strong>{{ $seller->pan_number ?: 'N/A' }}</strong>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Verification</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="d-flex justify-content-end brand-action-bar">
                <a href="{{ route('admin.brand') }}" class="btn btn-outline-secondary">Back to Brand Users</a>
            </div>
        </div>

    </div>
</div>
@endsection

@push('script')
@endpush