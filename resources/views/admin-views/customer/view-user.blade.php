@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('View User'))


<style>
    .staff-ui-wrap {
        background: #f5f7fb;
        border: 1px solid #e3e8ef;
        border-radius: 12px;
        padding: 16px;
    }

    .staff-breadcrumb {
        color: #6f7a87;
        font-size: 12px;
        margin-bottom: 10px;
    }

    .staff-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        background: #eef2f6;
        border: 1px solid #e0e6ee;
        border-radius: 8px;
        padding: 8px;
        margin-bottom: 14px;
    }

    .staff-tab {
        padding: 7px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        color: #586574;
        background: transparent;
    }

    .staff-tab.active {
        background: #d8f0ef;
        color: #007f7d;
    }

    .staff-profile-card {
        background: #fff;
        border: 1px solid #e3e9f1;
        border-radius: 10px;
        padding: 16px;
        display: grid;
        grid-template-columns: 1.2fr 1.8fr;
        gap: 14px;
        margin-bottom: 14px;
    }

    .staff-user-main {
        display: flex;
        align-items: center;
        gap: 12px;
        border-right: 1px solid #edf1f6;
        padding-right: 12px;
    }

    .staff-avatar {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e6edf7;
    }

    .staff-name {
        font-size: 24px;
        font-weight: 700;
        color: #1f2e3c;
    }

    .staff-role {
        color: #0c9ea2;
        font-size: 13px;
        font-weight: 600;
    }

    .staff-meta-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }

    .staff-meta-item {
        font-size: 13px;
        color: #566575;
    }

    .staff-meta-item b {
        color: #1f2e3c;
        font-weight: 700;
        margin-left: 6px;
    }

    .staff-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }

    .staff-card {
        background: #fff;
        border: 1px solid #e3e9f1;
        border-radius: 10px;
        overflow: hidden;
    }

    .staff-card-h {
        border-bottom: 1px solid #edf1f6;
        padding: 12px 14px;
        font-size: 16px;
        font-weight: 600;
        color: #2a3b4d;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .staff-card-h i {
        color: #7f8b99;
        font-size: 17px;
    }

    .staff-card-b {
        padding: 14px;
    }

    .staff-info-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px 14px;
    }

    .staff-info {
        min-height: 46px;
    }

    .staff-info .k {
        color: #7a8797;
        font-size: 12px;
        margin-bottom: 3px;
    }

    .staff-info .v {
        color: #1f2e3c;
        font-weight: 600;
        font-size: 14px;
        word-break: break-word;
    }

    .staff-code {
        background: #f3f7fc;
        border: 1px dashed #d4deea;
        border-radius: 6px;
        padding: 4px 8px;
        display: inline-block;
    }

    .staff-actions {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        margin-bottom: 10px;
    }

    .staff-actions .btn {
        border-radius: 6px;
        font-weight: 600;
    }

    .staff-kyc-images {
        margin-top: 12px;
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 8px;
    }

    .staff-kyc-images img {
        width: 100%;
        height: 86px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #dde5ef;
        cursor: pointer;
    }

    .staff-tab {
        cursor: pointer;
        transition: background 0.15s, color 0.15s;
    }

    .staff-tab-pane {
        scroll-margin-top: 80px;
        margin-bottom: 20px;
    }

    .staff-card.highlighted {
        border: 2px solid #0c9ea2;
        box-shadow: 0 0 0 3px rgba(12, 158, 162, 0.12);
        transition: border 0.2s, box-shadow 0.2s;
    }

    @media (max-width: 1199px) {
        .staff-profile-card { grid-template-columns: 1fr; }
        .staff-user-main { border-right: 0; padding-right: 0; border-bottom: 1px solid #edf1f6; padding-bottom: 12px; }
    }

    @media (max-width: 991px) {
        .staff-grid,
        .staff-info-grid,
        .staff-meta-grid,
        .staff-kyc-images {
            grid-template-columns: 1fr;
        }
    }
</style>


@section('content')
@php($userImage = blank($user->image)
    ? asset('public/assets/front-end/img/image-place-holder.png')
    : (\Illuminate\Support\Str::startsWith($user->image, ['http://', 'https://'])
        ? $user->image
        : asset('storage/profile/' . ltrim($user->image, '/'))))

<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-account-details-outline"></i>
            </span>
            User Profile (View Only)
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    User Details <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                </li>
            </ul>
        </nav>
    </div>

    <div class="staff-ui-wrap">
        <div class="staff-actions">
            <a href="{{ route('admin.user') }}" class="btn btn-outline-secondary btn-sm">
                <i class="mdi mdi-arrow-left"></i> Back
            </a>
            <a href="{{ route('admin.user.edit', $user->id) }}" class="btn btn-outline-primary btn-sm">
                <i class="mdi mdi-pencil-outline"></i> Edit User
            </a>
            <a href="{{ route('admin.user.activity.logs', $user->id) }}" class="btn btn-primary btn-sm">
                <i class="mdi mdi-timeline-text-outline"></i> Activity Logs
            </a>
        </div>

        <div class="staff-breadcrumb">Users / User list / User detail</div>

        <div class="staff-tabs">
            <div class="staff-tab active" data-target="tab-profile">User profile</div>
            <div class="staff-tab" data-target="tab-work">Work information</div>
            <div class="staff-tab" data-target="tab-social">Social details</div>
            <div class="staff-tab" data-target="tab-kyc">KYC & Verification</div>
            <div class="staff-tab" data-target="tab-wallet">Wallet & Campaigns</div>
        </div>

        {{-- Always-visible profile summary --}}
        <div class="staff-profile-card">
            <div class="staff-user-main">
                <img src="{{ $userImage }}" alt="User" class="staff-avatar">
                <div>
                    <div class="staff-name">{{ $user->name ?? 'N/A' }}</div>
                    <div class="staff-role">{{ $user->profession ?? 'User' }}</div>
                </div>
            </div>
            <div class="staff-meta-grid">
                <div class="staff-meta-item">User ID:<b>{{ $user->id }}</b></div>
                <div class="staff-meta-item">Phone number:<b>{{ $user->mobile ?? 'N/A' }}</b></div>
                <div class="staff-meta-item">Account status:<b>{{ (int)$user->status === 1 ? 'Active' : 'Inactive' }}</b></div>
                <div class="staff-meta-item">Email:<b>{{ $user->email ?? 'N/A' }}</b></div>
            </div>
        </div>

        {{-- Tab 1: User Profile --}}
        <div class="staff-tab-pane" id="tab-profile">
            <div class="staff-grid">
                <div class="staff-card">
                    <div class="staff-card-h">Personal information <i class="mdi mdi-account-outline"></i></div>
                    <div class="staff-card-b">
                        <div class="staff-info-grid">
                            <div class="staff-info"><div class="k">Gender</div><div class="v">{{ $user->gender ?? 'N/A' }}</div></div>
                            <div class="staff-info"><div class="k">Date of birth</div><div class="v">{{ $user->dob ?? 'N/A' }}</div></div>
                            <div class="staff-info"><div class="k">Native city</div><div class="v">{{ $user->native_city ?? 'N/A' }}</div></div>
                            <div class="staff-info"><div class="k">Native state</div><div class="v">{{ $user->native_state ?? 'N/A' }}</div></div>
                            <div class="staff-info"><div class="k">City</div><div class="v">{{ $user->city ?? 'N/A' }}</div></div>
                            <div class="staff-info"><div class="k">State</div><div class="v">{{ $user->state ?? 'N/A' }}</div></div>
                        </div>
                    </div>
                </div>
                <div class="staff-card">
                    <div class="staff-card-h">Codes & Provider <i class="mdi mdi-identifier"></i></div>
                    <div class="staff-card-b">
                        <div class="staff-info-grid">
                            <div class="staff-info"><div class="k">Referral code</div><div class="v"><span class="staff-code">{{ $user->referral_code ?? 'N/A' }}</span></div></div>
                            <div class="staff-info"><div class="k">Friends code</div><div class="v"><span class="staff-code">{{ $user->friends_code ?? 'N/A' }}</span></div></div>
                            <div class="staff-info"><div class="k">Unique code</div><div class="v"><span class="staff-code">{{ $user->unique_code ?? 'N/A' }}</span></div></div>
                            <div class="staff-info"><div class="k">Provider</div><div class="v">{{ $user->provider ?? 'N/A' }} {{ $user->provider_id ? '(' . $user->provider_id . ')' : '' }}</div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tab 2: Work Information --}}
        <div class="staff-tab-pane" id="tab-work">
            <div class="staff-grid">
                <div class="staff-card">
                    <div class="staff-card-h">Work details <i class="mdi mdi-briefcase-outline"></i></div>
                    <div class="staff-card-b">
                        <div class="staff-info-grid">
                            <div class="staff-info"><div class="k">Profession</div><div class="v">{{ $user->profession ?? 'N/A' }}</div></div>
                            <div class="staff-info"><div class="k">Account status</div><div class="v">{{ (int)$user->status === 1 ? 'Active' : 'Inactive' }}</div></div>
                        </div>
                    </div>
                </div>
                <div class="staff-card">
                    <div class="staff-card-h">Campaign stats <i class="mdi mdi-chart-bar"></i></div>
                    <div class="staff-card-b">
                        <div class="staff-info-grid">
                            <div class="staff-info"><div class="k">Campaign participations</div><div class="v">{{ $user->campaigns_count ?? 0 }}</div></div>
                            <div class="staff-info"><div class="k">Post slots</div><div class="v">{{ $user->post_slots ?? 0 }}</div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tab 3: Social Details --}}
        <div class="staff-tab-pane" id="tab-social">
            <div class="staff-grid">
                <div class="staff-card">
                    <div class="staff-card-h">Instagram & Facebook <i class="mdi mdi-share-variant-outline"></i></div>
                    <div class="staff-card-b">
                        <div class="staff-info-grid">
                            <div class="staff-info"><div class="k">Instagram username</div><div class="v">{{ $user->instagram_username ?? 'N/A' }}</div></div>
                            <div class="staff-info"><div class="k">Instagram status</div><div class="v">{{ $user->instagram_status ?? 'N/A' }}</div></div>
                            <div class="staff-info"><div class="k">Facebook username</div><div class="v">{{ $user->facebook_username ?? 'N/A' }}</div></div>
                            <div class="staff-info"><div class="k">Facebook status</div><div class="v">{{ $user->facebook_status ?? 'N/A' }}</div></div>
                        </div>
                    </div>
                </div>
                <div class="staff-card">
                    <div class="staff-card-h">Wallet <i class="mdi mdi-wallet-outline"></i></div>
                    <div class="staff-card-b">
                        <div class="staff-info-grid">
                            <div class="staff-info"><div class="k">Wallet coins</div><div class="v">{{ $user->coinWallet?->balance ?? 0 }}</div></div>
                            <div class="staff-info"><div class="k">Wallet status</div><div class="v">{{ (int)($user->coinWallet?->status ?? 0) === 1 ? 'Active' : 'Inactive' }}</div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tab 4: KYC & Verification --}}
        <div class="staff-tab-pane" id="tab-kyc">
            <div class="staff-grid">
                <div class="staff-card">
                    <div class="staff-card-h">PAN details <i class="mdi mdi-card-account-details-outline"></i></div>
                    <div class="staff-card-b">
                        <div class="staff-info-grid">
                            <div class="staff-info"><div class="k">PAN number</div><div class="v">{{ $user->pan_number ?? 'N/A' }}</div></div>
                            <div class="staff-info"><div class="k">PAN status</div><div class="v">{{ $user->pan_status ?? 'N/A' }}</div></div>
                            <div class="staff-info"><div class="k">PAN rejection reason</div><div class="v">{{ $user->pan_rejection_reason ?? 'N/A' }}</div></div>
                        </div>
                    </div>
                </div>
                <div class="staff-card">
                    <div class="staff-card-h">Aadhaar & KYC images <i class="mdi mdi-shield-account-outline"></i></div>
                    <div class="staff-card-b">
                        <div class="staff-info-grid">
                            <div class="staff-info"><div class="k">Aadhaar number</div><div class="v">{{ $user->aadhar_number ?? 'N/A' }}</div></div>
                            <div class="staff-info"><div class="k">Aadhaar status</div><div class="v">{{ $user->aadhar_status ?? 'N/A' }}</div></div>
                            <div class="staff-info"><div class="k">Aadhaar rejection reason</div><div class="v">{{ $user->aadhar_rejection_reason ?? 'N/A' }}</div></div>
                        </div>
                        <div class="staff-kyc-images">
                            @if(!empty($user->pan_image))
                                <img src="{{ $user->pan_image }}" alt="PAN Image"
                                    data-bs-toggle="modal" data-bs-target="#kycImageModal"
                                    onclick="document.getElementById('kycModalImg').src=this.src">
                            @endif
                            @if(is_array($user->aadhar_image ?? null))
                                @foreach($user->aadhar_image as $img)
                                    @if(!empty($img))
                                        <img src="{{ $img }}" alt="Aadhaar Image"
                                            data-bs-toggle="modal" data-bs-target="#kycImageModal"
                                            onclick="document.getElementById('kycModalImg').src=this.src">
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tab 5: Wallet & Campaigns --}}
        <div class="staff-tab-pane" id="tab-wallet">
            <div class="staff-grid">
                <div class="staff-card">
                    <div class="staff-card-h">UPI information <i class="mdi mdi-contactless-payment"></i></div>
                    <div class="staff-card-b">
                        <div class="staff-info-grid">
                            <div class="staff-info"><div class="k">UPI ID</div><div class="v">{{ $user->upi_id ?? 'N/A' }}</div></div>
                            <div class="staff-info"><div class="k">UPI status</div><div class="v">{{ $user->upi_status ?? 'N/A' }}</div></div>
                            <div class="staff-info"><div class="k">UPI rejection reason</div><div class="v">{{ $user->upi_rejection_reason ?? 'N/A' }}</div></div>
                        </div>
                    </div>
                </div>
                <div class="staff-card">
                    <div class="staff-card-h">Bank details <i class="mdi mdi-bank-outline"></i></div>
                    <div class="staff-card-b">
                        <div class="staff-info-grid">
                            <div class="staff-info"><div class="k">Bank status</div><div class="v">{{ $user->bank_status ?? 'N/A' }}</div></div>
                            <div class="staff-info"><div class="k">Bank rejection reason</div><div class="v">{{ $user->bank_rejection_reason ?? 'N/A' }}</div></div>
                            @php($bankDetail = (array)($user->bank_detail ?? []))
                            @foreach($bankDetail as $key => $value)
                                <div class="staff-info">
                                    <div class="k">{{ ucwords(str_replace('_', ' ', $key)) }}</div>
                                    <div class="v">{{ $value ?: 'N/A' }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- KYC image popup modal --}}
<div class="modal fade" id="kycImageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">KYC Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="kycModalImg" src="" class="img-fluid" style="min-width: 450px;" alt="KYC Image">
            </div>
        </div>
    </div>
</div>

@endsection

@push('script')
<script>
    function activateSection(paneId) {
        // Highlight tab
        document.querySelectorAll('.staff-tab').forEach(t => t.classList.remove('active'));
        var activeTab = document.querySelector('.staff-tab[data-target="' + paneId + '"]');
        if (activeTab) activeTab.classList.add('active');

        // Highlight cards in this pane, remove from others
        document.querySelectorAll('.staff-card').forEach(c => c.classList.remove('highlighted'));
        var pane = document.getElementById(paneId);
        if (pane) pane.querySelectorAll('.staff-card').forEach(c => c.classList.add('highlighted'));
    }

    document.querySelectorAll('.staff-tab').forEach(function(tab) {
        tab.addEventListener('click', function() {
            var paneId = this.dataset.target;
            activateSection(paneId);
            var target = document.getElementById(paneId);
            if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });

    // Highlight active tab & cards based on scroll position
    var panes = document.querySelectorAll('.staff-tab-pane');
    window.addEventListener('scroll', function() {
        var scrollY = window.scrollY + 120;
        panes.forEach(function(pane) {
            if (pane.offsetTop <= scrollY && (pane.offsetTop + pane.offsetHeight) > scrollY) {
                activateSection(pane.id);
            }
        });
    });

    // Highlight first section on load
    activateSection('tab-profile');
</script>
@endpush
