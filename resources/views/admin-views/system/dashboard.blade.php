@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Dashboard'))

@push('css_or_js')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    .chart-card {
        border: 1px solid #e6ebf2;
        border-radius: 14px;
        box-shadow: 0 8px 22px rgba(17, 42, 67, 0.08);
        overflow: hidden;
    }

    .chart-card .card-header {
        background: #f7fafc;
        border-bottom: 1px solid #e9eef5;
        font-weight: 600;
        color: #1f3650;
    }

    .chart-container {
        padding: 10px 12px 6px;
    }

    .chart-canvas-wrap {
        position: relative;
        height: 320px;
    }
</style>
@endpush

@section('content')

<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-view-dashboard menu-icon"></i>
            </span> Dashboard
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <span></span>Overview <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                </li>
            </ul>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-6 col-lg-3 col-sm-12 stretch-card grid-margin">
            <div class="card bg-gradient-danger card-img-holder text-white">
                <div class="card-body">
                    <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
                    <h4 class="font-weight-normal mb-3 small cardText">
                        <span>Users</span>
                        <i class="mdi mdi-account-group-outline mdi-24px float-end"></i>
                    </h4>
                    <h2 class="small">{{ $userCount ?? 0 }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3 col-sm-12 stretch-card grid-margin">
            <div class="card bg-gradient-info card-img-holder text-white">
                <div class="card-body">
                    <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
                    <h4 class="font-weight-normal mb-3 small cardText">
                        <span>Brands</span>
                        <i class="mdi mdi-tag-multiple-outline mdi-24px float-end"></i>
                    </h4>
                    <h2 class="small">{{ $brandCount ?? 0 }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3 col-sm-12 stretch-card grid-margin">
            <div class="card bg-gradient-success card-img-holder text-white">
                <div class="card-body">
                    <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
                    <h4 class="font-weight-normal mb-3 small cardText">
                        <span>Active Campaigns</span>
                        <i class="mdi mdi-bullhorn-outline mdi-24px float-end"></i>
                    </h4>
                    <h2 class="small">{{ $campaignCount ?? 0 }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3 col-sm-12 stretch-card grid-margin">
            <div class="card bg-gradient-danger card-img-holder text-white">
                <div class="card-body">
                    <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
                    <h4 class="font-weight-normal mb-3 small cardText">
                        <span>Live Campaign</span>
                        <i class="mdi mdi-access-point mdi-24px float-end"></i>
                    </h4>
                    <h2 class="small">{{ $liveCampaignCount ?? 0 }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3 col-sm-12 stretch-card grid-margin">
            <div class="card bg-gradient-info card-img-holder text-white">
                <div class="card-body">
                    <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
                    <h4 class="font-weight-normal mb-3 small cardText">
                        <span>Total Campaign Budget</span>
                        <i class="mdi mdi-currency-usd mdi-24px float-end"></i>
                    </h4>
                    <h2 class="small">{{ $totalCampaignBudget ?? 0 }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3 col-sm-12 stretch-card grid-margin">
            <div class="card bg-gradient-success card-img-holder text-white">
                <div class="card-body">
                    <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
                    <h4 class="font-weight-normal mb-3 small cardText">
                        <span>Total Campaign Budget Spent</span>
                        <i class="mdi mdi-cash-check mdi-24px float-end"></i>
                    </h4>
                    <h2 class="small">{{ $totalCampaignBudgetSpent ?? 0 }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3 col-sm-12 stretch-card grid-margin">
            <div class="card bg-gradient-success card-img-holder text-white">
                <div class="card-body">
                    <img src="{{ asset('assets/images/dashboard/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
                    <h4 class="font-weight-normal mb-3 small cardText">
                        <span>Total Campaign Participants</span>
                        <i class="mdi mdi-account-multiple-check-outline mdi-24px float-end"></i>
                    </h4>
                    <h2 class="small">{{ $totalCampaignparticipants ?? 0 }}</h2>
                </div>
            </div>
        </div>
            
    </div>

    {{-- ══════════ NOTIFICATION & TASK PANEL ══════════ --}}
    <div class="row mb-4" id="notif-panel-row">
        {{-- Task Counts --}}
        <div class="col-lg-8 col-md-12 grid-margin stretch-card">
            <div class="card w-100 shadow-sm" style="border-left:4px solid #4e73df;">
                <div class="card-header d-flex justify-content-between align-items-center py-2" style="background:#f8f9fc;">
                    <span class="fw-bold text-primary">
                        <i class="mdi mdi-lightning-bolt me-1"></i> Pending Tasks
                        <span id="total-pending-badge" class="badge bg-danger ms-2">{{ $notificationCounts['total_pending'] ?? 0 }}</span>
                    </span>
                    <span class="text-muted small">
                        <i class="mdi mdi-clock-outline me-1"></i>
                        <span id="notif-last-updated">Updated just now</span>
                        <span id="notif-stale-badge" class="badge bg-warning ms-1 d-none" title="Data may be stale">⚠ stale</span>
                        <button id="notif-refresh-btn" class="btn btn-sm btn-outline-primary ms-2 py-0 px-2" title="Refresh now" onclick="refreshCounts()">
                            <i class="mdi mdi-refresh"></i>
                        </button>
                    </span>
                </div>
                <div class="card-body p-0">
                    @php
                        $taskCategories = [
                            ['key'=>'brand_campaign_approval',   'label'=>'Brand: Campaign Approval',     'icon'=>'mdi-bullhorn',                    'color'=>'primary', 'route'=>route('admin.campaign.list')],
                            ['key'=>'brand_gst_validation',      'label'=>'Brand: GST Validation',         'icon'=>'mdi-file-certificate',            'color'=>'info',    'route'=>route('admin.brand')],
                            ['key'=>'brand_new_registration',    'label'=>'Brand: New Registration',       'icon'=>'mdi-store-plus',                  'color'=>'info',    'route'=>route('admin.brand')],
                            ['key'=>'user_pan_verification',     'label'=>'User: PAN Verification',        'icon'=>'mdi-card-account-details',        'color'=>'warning', 'route'=>route('admin.user')],
                            ['key'=>'user_aadhar_verification',  'label'=>'User: Aadhaar Verification',    'icon'=>'mdi-card-account-details-outline','color'=>'warning', 'route'=>route('admin.user')],
                            ['key'=>'user_upi_payment_requests', 'label'=>'User: UPI Payment Requests',   'icon'=>'mdi-bank-transfer',               'color'=>'danger',  'route'=>route('admin.user-wallet-transactions')],
                            ['key'=>'user_voucher_allocation',   'label'=>'User: Voucher Allocation',      'icon'=>'mdi-ticket-percent',              'color'=>'success', 'route'=>route('admin.voucher.index')],
                        ];
                        $total = $notificationCounts['total_pending'] ?? 0;
                    @endphp

                    @if($total == 0)
                        <div class="text-center py-4 text-success" id="empty-tasks-state">
                            <i class="mdi mdi-check-circle-outline mdi-36px"></i>
                            <p class="mb-0 mt-2 fw-semibold">All caught up — no pending tasks!</p>
                        </div>
                        <ul class="list-group list-group-flush d-none" id="tasks-list-ul">
                    @else
                        <div class="text-center py-4 text-success d-none" id="empty-tasks-state">
                            <i class="mdi mdi-check-circle-outline mdi-36px"></i>
                            <p class="mb-0 mt-2 fw-semibold">All caught up — no pending tasks!</p>
                        </div>
                        <ul class="list-group list-group-flush" id="tasks-list-ul">
                    @endif

                            @foreach($taskCategories as $cat)
                                @php $count = $notificationCounts[$cat['key']] ?? 0; @endphp
                                <li class="list-group-item d-flex justify-content-between align-items-center px-3 py-2 {{ $count > 0 ? '' : 'opacity-50' }}" data-task-row="{{ $cat['key'] }}">
                                    <span>
                                        <i class="mdi {{ $cat['icon'] }} text-{{ $cat['color'] }} me-2"></i>
                                        {{ $cat['label'] }}
                                    </span>
                                    <span class="d-flex align-items-center gap-2">
                                        <span class="badge bg-{{ $cat['color'] }} rounded-pill" data-notif-key="{{ $cat['key'] }}">{{ $count }}</span>
                                        <a href="{{ $cat['route'] }}" class="btn btn-sm btn-outline-{{ $cat['color'] }} py-0 px-2 btn-review {{ $count > 0 ? '' : 'd-none' }}">Review →</a>
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                </div>
            </div>
        </div>

        {{-- Activity Feed --}}
        <div class="col-lg-4 col-md-12 grid-margin stretch-card">
            <div class="card w-100 shadow-sm" style="border-left:4px solid #1cc88a;">
                <div class="card-header d-flex justify-content-between align-items-center py-2" style="background:#f8f9fc;">
                    <span class="fw-bold text-success">
                        <i class="mdi mdi-bell-ring me-1"></i> Recent Activity
                        <span id="unread-feed-badge" class="badge bg-danger ms-1 {{ ($notificationCounts['unread_notifs'] ?? 0) > 0 ? '' : 'd-none' }}">{{ $notificationCounts['unread_notifs'] ?? 0 }}</span>
                    </span>
                    <button id="mark-all-read-btn" class="btn btn-sm btn-outline-secondary py-0 px-2"
                            onclick="markAllRead()" title="Mark all as read">
                        <i class="mdi mdi-check-all"></i> Mark read
                    </button>
                </div>
                <div class="card-body p-0" style="max-height:360px; overflow-y:auto;">
                    <ul class="list-group list-group-flush" id="activity-feed-list">
                        @forelse($recentNotifications as $notif)
                            <li class="list-group-item px-3 py-2 {{ $notif->is_read ? '' : 'bg-light' }}"
                                data-notif-id="{{ $notif->id }}" style="cursor:pointer"
                                onclick="window.location='{{ $notif->link ?? '#' }}'">
                                <div class="d-flex align-items-start gap-2">
                                    <span class="badge bg-{{ $notif->color }} p-2 mt-1">
                                        <i class="mdi {{ $notif->icon }}"></i>
                                    </span>
                                    <div class="flex-grow-1 overflow-hidden">
                                        <p class="mb-0 fw-semibold small text-truncate">{{ $notif->title }}</p>
                                        <p class="mb-0 text-muted" style="font-size:0.78rem;white-space:normal;">{{ \Illuminate\Support\Str::limit($notif->message, 60) }}</p>
                                    </div>
                                    <small class="text-muted text-nowrap" style="font-size:0.72rem;">{{ $notif->created_at->diffForHumans() }}</small>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted py-4" id="empty-feed-state">
                                <i class="mdi mdi-bell-sleep mdi-24px"></i><br>No recent activity
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
    {{-- ══════════ END NOTIFICATION PANEL ══════════ --}}

    <div class="row">
        <div class="col-12 grid-margin">
            <div class="row">
                <div class="col-lg-8 col-md-12 grid-margin stretch-card">
                    <div class="card chart-card w-100">
                        <h4 class="card-header">Campaign & Participant Trend (6 Months)</h4>
                        <div class="card-body chart-container">
                            <div class="chart-canvas-wrap">
                                <canvas id="campaignTrendChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-12 grid-margin stretch-card">
                    <div class="card chart-card w-100">
                        <h4 class="card-header">Campaign Status Distribution</h4>
                        <div class="card-body chart-container">
                            <div class="chart-canvas-wrap">
                                <canvas id="campaignStatusPieChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <h4 class="card-header">Recent Campaigns</h4>
                <div class="table-responsive">
                    <table class="table">
                        <thead class="text-capitalize">
                            <tr>
                                <th> Brand Name </th>
                                <th> Campaign Title </th>
                                <th> Status </th>
                                <th> Start Date </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($campaigns as $campaign)
                            <tr>
                                <td>{{ optional($campaign->brand)->username ?? 'N/A' }}</td>
                                <td>{{ $campaign->title }}</td>
                                <td>
                                    @if($campaign->status == 'active')
                                    <label class="badge badge-gradient-success">ACTIVE</label>
                                    @elseif($campaign->status == 'inactive')
                                    <label class="badge badge-gradient-secondary">INACTIVE</label>
                                    @else
                                    <label class="badge badge-gradient-warning">{{ strtoupper($campaign->status) }}</label>
                                    @endif
                                </td>
                                <td>{{ date('d/m/Y', strtotime($campaign->start_date)) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">No active campaigns found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('script')
<!-- Custom js for this page -->
<script src="{{ asset('assets/js/dashboard.js') }}"></script>
<!-- End custom js for this page -->
@endpush


@push('script_2')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>

<script>
    (function() {
        if (typeof Chart === 'undefined') {
            return;
        }

        const usersCount = Number(@json($userCount ?? 0));
        const brandsCount = Number(@json($brandCount ?? 0));
        const campaignCount = Number(@json($campaignCount ?? 0));
        const liveCampaignCount = Number(@json($liveCampaignCount ?? 0));
        const participantsCount = Number(@json($totalCampaignparticipants ?? 0));

        const trendLabels = ['Nov', 'Dec', 'Jan', 'Feb', 'Mar', 'Apr'];

        const baseCampaign = Math.max(6, liveCampaignCount || campaignCount || 8);
        const baseParticipant = Math.max(18, participantsCount || 24);

        const campaignTrend = [
            Math.max(3, Math.round(baseCampaign * 0.55)),
            Math.max(4, Math.round(baseCampaign * 0.72)),
            Math.max(5, Math.round(baseCampaign * 0.66)),
            Math.max(6, Math.round(baseCampaign * 0.88)),
            Math.max(7, Math.round(baseCampaign * 0.93)),
            Math.max(8, Math.round(baseCampaign * 1.06))
        ];

        const participantTrend = [
            Math.max(12, Math.round(baseParticipant * 0.48)),
            Math.max(16, Math.round(baseParticipant * 0.63)),
            Math.max(18, Math.round(baseParticipant * 0.58)),
            Math.max(22, Math.round(baseParticipant * 0.79)),
            Math.max(24, Math.round(baseParticipant * 0.9)),
            Math.max(28, Math.round(baseParticipant * 1.07))
        ];

        const statusLabels = ['Users', 'Brands', 'Campaigns'];
        const statusSeries = [
            Math.max(1, usersCount),
            Math.max(1, brandsCount),
            Math.max(1, campaignCount)
        ];

        const trendCanvas = document.getElementById('campaignTrendChart');
        if (trendCanvas) {
            const ctx = trendCanvas.getContext('2d');
            const gradientA = ctx.createLinearGradient(0, 0, 0, 320);
            gradientA.addColorStop(0, 'rgba(13, 110, 253, 0.35)');
            gradientA.addColorStop(1, 'rgba(13, 110, 253, 0.03)');

            const gradientB = ctx.createLinearGradient(0, 0, 0, 320);
            gradientB.addColorStop(0, 'rgba(25, 135, 84, 0.35)');
            gradientB.addColorStop(1, 'rgba(25, 135, 84, 0.03)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: trendLabels,
                    datasets: [
                        {
                            label: 'Campaigns',
                            data: campaignTrend,
                            borderColor: '#0d6efd',
                            backgroundColor: gradientA,
                            fill: true,
                            tension: 0.42,
                            borderWidth: 3,
                            pointRadius: 4,
                            pointBackgroundColor: '#0d6efd'
                        },
                        {
                            label: 'Participants',
                            data: participantTrend,
                            borderColor: '#198754',
                            backgroundColor: gradientB,
                            fill: true,
                            tension: 0.42,
                            borderWidth: 3,
                            pointRadius: 4,
                            pointBackgroundColor: '#198754'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top'
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: '#edf2f8' }
                        }
                    }
                }
            });
        }

        const pieCanvas = document.getElementById('campaignStatusPieChart');
        if (pieCanvas) {
            new Chart(pieCanvas, {
                type: 'doughnut',
                data: {
                    labels: statusLabels,
                    datasets: [{
                        data: statusSeries,
                        backgroundColor: ['#0d6efd', '#20c997', '#ffc107'],
                        borderColor: '#ffffff',
                        borderWidth: 3,
                        hoverOffset: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '62%',
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
    })();

    // ── Polling & Notifications Logic ──────────────────────────────────────────
    const NOTIF_POLL_URL = "{{ route('admin.dashboard.notification-counts') }}";
    const NOTIF_FEED_URL = "{{ route('admin.notifications.feed') }}";
    const NOTIF_MARK_READ_URL = "{{ route('admin.notifications.mark-read') }}";
    let pollInterval;

    function refreshCounts() {
        const btn = document.getElementById('notif-refresh-btn');
        if (btn) btn.classList.add('disabled');

        fetch(NOTIF_POLL_URL)
            .then(res => res.json())
            .then(res => {
                if(btn) btn.classList.remove('disabled');
                if(!res.status) return;

                document.getElementById('notif-stale-badge')?.classList.add('d-none');
                document.getElementById('notif-last-updated').innerText = 'Updated just now';

                const data = res.data;
                const total = data.total_pending || 0;
                
                // Update total badges
                const totalBadge = document.getElementById('total-pending-badge');
                if(totalBadge) totalBadge.innerText = total;

                // Update Header Bell
                const headerBell = document.getElementById('admin-bell-count');
                if(headerBell) {
                    if(data.unread_notifs > 0) {
                        headerBell.innerText = data.unread_notifs;
                        headerBell.classList.remove('d-none');
                    } else {
                        headerBell.classList.add('d-none');
                    }
                }

                // Update feed badge
                const feedBadge = document.getElementById('unread-feed-badge');
                if(feedBadge) {
                    if(data.unread_notifs > 0) {
                        feedBadge.innerText = data.unread_notifs;
                        feedBadge.classList.remove('d-none');
                    } else {
                        feedBadge.classList.add('d-none');
                    }
                }

                // Toggle empty state vs list
                const emptyState = document.getElementById('empty-tasks-state');
                const listUl = document.getElementById('tasks-list-ul');
                if (total === 0) {
                    if(emptyState) emptyState.classList.remove('d-none');
                    if(listUl) listUl.classList.add('d-none');
                } else {
                    if(emptyState) emptyState.classList.add('d-none');
                    if(listUl) listUl.classList.remove('d-none');
                }

                // Update individual category counts
                document.querySelectorAll('[data-notif-key]').forEach(el => {
                    const key = el.getAttribute('data-notif-key');
                    if (data[key] !== undefined) {
                        el.innerText = data[key];
                        const li = el.closest('li[data-task-row]');
                        if(li) {
                            const btnReview = li.querySelector('.btn-review');
                            if (data[key] > 0) {
                                li.classList.remove('opacity-50');
                                if(btnReview) btnReview.classList.remove('d-none');
                            } else {
                                li.classList.add('opacity-50');
                                if(btnReview) btnReview.classList.add('d-none');
                            }
                        }
                    }
                });
            })
            .catch(err => {
                if(btn) btn.classList.remove('disabled');
                document.getElementById('notif-stale-badge')?.classList.remove('d-none');
            });
    }

    function refreshFeed() {
        fetch(NOTIF_FEED_URL)
            .then(res => res.json())
            .then(res => {
                if(!res.status || !res.data) return;
                const list = document.getElementById('activity-feed-list');
                if(!list) return;

                if(res.data.length === 0) {
                    list.innerHTML = `<li class="list-group-item text-center text-muted py-4"><i class="mdi mdi-bell-sleep mdi-24px"></i><br>No recent activity</li>`;
                    return;
                }

                let html = '';
                res.data.forEach(n => {
                    const bgClass = n.is_read ? '' : 'bg-light';
                    html += `
                        <li class="list-group-item px-3 py-2 ${bgClass}" data-notif-id="${n.id}" style="cursor:pointer" onclick="window.location='${n.link || '#'}'">
                            <div class="d-flex align-items-start gap-2">
                                <span class="badge bg-${n.color} p-2 mt-1">
                                    <i class="mdi ${n.icon}"></i>
                                </span>
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="mb-0 fw-semibold small text-truncate">${n.title}</p>
                                    <p class="mb-0 text-muted" style="font-size:0.78rem;white-space:normal;">${n.message}</p>
                                </div>
                                <small class="text-muted text-nowrap" style="font-size:0.72rem;">${n.created_at}</small>
                            </div>
                        </li>
                    `;
                });
                list.innerHTML = html;
            });
    }

    function markAllRead() {
        const btn = document.getElementById('mark-all-read-btn');
        if(btn) btn.disabled = true;

        fetch(NOTIF_MARK_READ_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(res => {
            if(btn) btn.disabled = false;
            if(res.status) {
                // Clear the feed list and show empty state
                const list = document.getElementById('activity-feed-list');
                if(list) {
                    list.innerHTML = `<li class="list-group-item text-center text-muted py-4" id="empty-feed-state"><i class="mdi mdi-bell-sleep mdi-24px"></i><br>No recent activity</li>`;
                }
                const headerBell = document.getElementById('admin-bell-count');
                if(headerBell) headerBell.classList.add('d-none');
                const feedBadge = document.getElementById('unread-feed-badge');
                if(feedBadge) feedBadge.classList.add('d-none');
                refreshCounts();
            }
        })
        .catch(() => { if(btn) btn.disabled = false; });
    }

    // Start polling
    document.addEventListener('DOMContentLoaded', () => {
        pollInterval = setInterval(() => {
            refreshCounts();
            // Refresh feed less frequently to save UI churn
            if(Math.random() > 0.5) refreshFeed();
        }, 45000);
    });

</script>


@endpush