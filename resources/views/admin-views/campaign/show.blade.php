@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Campaign Edit'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
@php
    $platforms = array_filter(explode(',', (string) $campaign->share_on));
    $statusLists = ['pending', 'active', 'inactive', 'accepted', 'rejected', 'completed', 'paused', 'stopped', 'violated'];
    $guidelines = is_array($campaign->guidelines) ? array_filter($campaign->guidelines) : [];
    $images = is_array($campaign->images) ? $campaign->images : [];
@endphp
<div class="content-wrapper">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="mdi mdi-check-circle me-1"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="mdi mdi-alert me-1"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-home"></i>
            </span> {{\App\CPU\translate('Campaign')}}
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <span></span>{{\App\CPU\translate('Campaign')}} <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                </li>
            </ul>
        </nav>
    </div>
    <div class="row">
        <div class="col-lg-9">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">{{ \App\CPU\translate('view_campaign') }}</h4>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-12">
                            <h5 class="text-primary mb-3">{{ \App\CPU\translate('Campaign Overview') }}</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <small class="text-muted d-block">{{ \App\CPU\translate('Campaign ID') }}</small>
                                    <strong>#{{ $campaign->id }}</strong>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted d-block">{{ \App\CPU\translate('Unique Code') }}</small>
                                    <strong>{{ $campaign->unique_code ?: 'N/A' }}</strong>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted d-block">{{ \App\CPU\translate('Brand') }}</small>
                                    <strong>{{ $campaign->brand->name ?? $campaign->brand->username ?? 'N/A' }}</strong>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted d-block">{{ \App\CPU\translate('Sale Referral Code') }}</small>
                                    <strong>{{ $campaign->sales_referal_code ?: 'N/A' }}</strong>
                                </div>
                                <div class="col-md-12">
                                    <small class="text-muted d-block">{{ \App\CPU\translate('Caption') }}</small>
                                    <p class="mb-0">{{ $campaign->descriptions ?: 'N/A' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted d-block">{{ \App\CPU\translate('Hashtags') }}</small>
                                    <p class="mb-0">{{ $campaign->tags ?: 'N/A' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted d-block">{{ \App\CPU\translate('Platforms') }}</small>
                                    <div>
                                        @forelse($platforms as $platform)
                                            <span class="badge badge-soft-info text-dark me-1">{{ ucfirst($platform) }}</span>
                                        @empty
                                            <span class="text-muted">N/A</span>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <hr class="my-0">
                        </div>

                        <div class="col-12">
                            <h5 class="text-primary mb-3">{{ \App\CPU\translate('Media') }}</h5>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <small class="text-muted d-block mb-2">{{ \App\CPU\translate('Thumbnail') }}</small>
                                    <a href="#" class="campaign-zoom-trigger" data-src="{{ $campaign->thumbnail }}" data-fallback="{{ asset('assets/logo/logo-3.png') }}">
                                        <img
                                            class="img-fluid rounded border w-100"
                                            src="{{ $campaign->thumbnail }}"
                                            onerror='this.src="{{ asset('assets/logo/logo-3.png') }}"'
                                            alt="Campaign Thumbnail"
                                            style="cursor:zoom-in;"
                                        />
                                    </a>
                                </div>
                                <div class="col-md-8">
                                    <small class="text-muted d-block mb-2">{{ \App\CPU\translate('Gallery Images') }}</small>
                                    <div class="row g-2">
                                        @forelse($images as $image)
                                            <div class="col-md-4 col-sm-6">
                                                <a href="#" class="campaign-zoom-trigger" data-src="{{ $image }}">
                                                    <img src="{{ $image }}" class="img-thumbnail campaign-gallery-image" alt="Campaign Image" style="cursor:zoom-in;width:200px;height:130px;" onerror='this.src="{{ asset('assets/logo/logo-3.png') }}"'>
                                                </a>
                                            </div>
                                        @empty
                                            <div class="col-12 text-muted">No additional images.</div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <hr class="my-0">
                        </div>

                        <div class="col-12">
                            <h5 class="text-primary mb-3">{{ \App\CPU\translate('Targeting & Scheduling') }}</h5>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <small class="text-muted d-block">{{ \App\CPU\translate('Start Date') }}</small>
                                    <strong>{{ \App\CPU\Helpers::formatAdminDate($campaign->start_date, 'N/A') }}</strong>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted d-block">{{ \App\CPU\translate('End Date') }}</small>
                                    <strong>{{ \App\CPU\Helpers::formatAdminDate($campaign->end_date, 'N/A') }}</strong>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted d-block">{{ \App\CPU\translate('Left Days') }}</small>
                                    <strong>{{ $campaign->left_days }}</strong>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted d-block">{{ \App\CPU\translate('Gender') }}</small>
                                    <strong>{{ $campaign->gender ? ucfirst($campaign->gender) : 'N/A' }}</strong>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted d-block">{{ \App\CPU\translate('Age Range') }}</small>
                                    <strong>{{ $campaign->age_range ?: 'N/A' }}</strong>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted d-block">{{ \App\CPU\translate('State') }}</small>
                                    <strong>{{ $campaign->state ?: 'N/A' }}</strong>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted d-block">{{ \App\CPU\translate('City') }}</small>
                                    <strong>{{ $campaign->city ?: 'N/A' }}</strong>
                                </div>
                                <div class="col-md-8">
                                    <small class="text-muted d-block">{{ \App\CPU\translate('Guidelines') }}</small>
                                    <div class="d-flex flex-wrap gap-1 mt-1">
                                        @forelse($guidelines as $guideline)
                                            <span class="badge badge-soft-success text-dark me-1 mb-1" style="white-space:normal;word-break:break-word;max-width:100%;">{{ $guideline }}</span>
                                        @empty
                                            <span class="text-muted">N/A</span>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <hr class="my-0">
                        </div>

                        <div class="col-12">
                            <h5 class="text-primary mb-3">{{ \App\CPU\translate('Budget, Rewards & Performance') }}</h5>
                            <div class="row g-3">
                                <div class="col-md-4"><small class="text-muted d-block">{{ \App\CPU\translate('Reward Per User') }}</small><strong>{{ $campaign->reward_per_user ?? '0' }}</strong></div>
                                <div class="col-md-4"><small class="text-muted d-block">{{ \App\CPU\translate('Coins') }}</small><strong>{{ $campaign->coins ?? '0' }}</strong></div>
                                <div class="col-md-4"><small class="text-muted d-block">{{ \App\CPU\translate('Final Reward For User') }}</small><strong>{{ $campaign->final_reward_for_user ?? '0' }}</strong></div>
                                <div class="col-md-4"><small class="text-muted d-block">{{ \App\CPU\translate('Feedback Coin') }}</small><strong>{{ $campaign->feedback_coin ?? '0' }}</strong></div>
                                <div class="col-md-4"><small class="text-muted d-block">{{ \App\CPU\translate('Total Users Required') }}</small><strong>{{ $campaign->total_user_required ?? '0' }}</strong></div>
                                <div class="col-md-4"><small class="text-muted d-block">{{ \App\CPU\translate('Number Of Posts') }}</small><strong>{{ $campaign->number_of_post ?? '0' }}</strong></div>
                                <div class="col-md-4"><small class="text-muted d-block">{{ \App\CPU\translate('Used Posts') }}</small><strong>{{ $campaign->used_post ?? '0' }}</strong></div>
                                <div class="col-md-4"><small class="text-muted d-block">{{ \App\CPU\translate('Daily Budget Cap') }}</small><strong>{{ $campaign->daily_budget_cap ?? '0' }}</strong></div>
                                <div class="col-md-4"><small class="text-muted d-block">{{ \App\CPU\translate('Total Campaign Budget') }}</small><strong>{{ $campaign->total_campaign_budget ?? '0' }}</strong></div>
                                <div class="col-md-4"><small class="text-muted d-block">{{ \App\CPU\translate('Campaign User Budget') }}</small><strong>{{ $campaign->campaign_user_budget ?? '0' }}</strong></div>
                                <div class="col-md-4"><small class="text-muted d-block">{{ \App\CPU\translate('Campaign Budget With GST') }}</small><strong>{{ $campaign->compign_budget_with_gst ?? '0' }}</strong></div>
                                <div class="col-md-4"><small class="text-muted d-block">{{ \App\CPU\translate('Generate GST Invoice') }}</small><strong>{{ $campaign->generate_gst_invoice ? \App\CPU\translate('Yes') : \App\CPU\translate('No') }}</strong></div>
                                <div class="col-md-4"><small class="text-muted d-block">{{ \App\CPU\translate('Total Shared') }}</small><strong>{{ $totalParticipants ?? 0 }}</strong></div>
                                <div class="col-md-4"><small class="text-muted d-block">{{ \App\CPU\translate('Occupied Slots') }}</small><strong>{{ $campaign->occupied_slots }}</strong></div>
                                <div class="col-md-4"><small class="text-muted d-block">{{ \App\CPU\translate('Available Slots') }}</small><strong>{{ $campaign->available_slots }}</strong></div>
                                <div class="col-md-4"><small class="text-muted d-block">{{ \App\CPU\translate('Slot Full') }}</small><strong>{{ $campaign->is_slot_full ? 'Yes' : 'No' }}</strong></div>
                                <div class="col-md-4"><small class="text-muted d-block">{{ \App\CPU\translate('Average Feedback') }}</small><strong>{{ $campaign->avg_feedback }}</strong></div>
                                <div class="col-md-4"><small class="text-muted d-block">{{ \App\CPU\translate('Engagement') }}</small><strong>{{ $campaign->engagement }}</strong></div>
                                <div class="col-md-4"><small class="text-muted d-block">{{ \App\CPU\translate('Cost Per Click') }}</small><strong>{{ $campaign->cost_per_click }}</strong></div>
                            </div>
                        </div>

                        <div class="col-12">
                            <hr class="my-0">
                        </div>

                        <div class="col-12">
                            <h5 class="text-primary mb-3">{{ \App\CPU\translate('Participants') }}</h5>
                            <p class="text-muted small mb-3">
                                {{ \App\CPU\translate('Click a status to show users with that participation status.') }}
                            </p>
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <a href="{{ route('admin.campaign.show', $campaign->id) }}"
                                   class="badge text-decoration-none {{ ($participantStatus ?? 'all') === 'all' ? 'bg-primary' : 'badge-soft-primary text-dark' }} px-3 py-2">
                                    {{ \App\CPU\translate('All') }} ({{ $totalParticipants ?? 0 }})
                                </a>
                                @foreach($participantStatuses ?? [] as $status)
                                    @php($count = ($statusCounts[$status] ?? 0))
                                    <a href="{{ route('admin.campaign.show', ['id' => $campaign->id, 'participant_status' => $status]) }}"
                                       class="badge text-decoration-none px-3 py-2 participant-status-badge participant-status-{{ $status }} {{ ($participantStatus ?? 'all') === $status ? 'active' : '' }}">
                                        {{ ucfirst($status) }} ({{ $count }})
                                    </a>
                                @endforeach
                                @foreach(($statusCounts ?? collect()) as $status => $count)
                                    @if(!in_array($status, $participantStatuses ?? [], true))
                                        <a href="{{ route('admin.campaign.show', ['id' => $campaign->id, 'participant_status' => $status]) }}"
                                           class="badge text-decoration-none px-3 py-2 participant-status-badge participant-status-other {{ ($participantStatus ?? 'all') === $status ? 'active' : '' }}">
                                            {{ ucfirst($status) }} ({{ $count }})
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="text-capitalize">
                                        <tr>
                                            <th>{{ \App\CPU\translate('SL') }}</th>
                                            <th>{{ \App\CPU\translate('User') }}</th>
                                            <th>{{ \App\CPU\translate('Email') }}</th>
                                            <th>{{ \App\CPU\translate('Platform') }}</th>
                                            <th>{{ \App\CPU\translate('Coins') }}</th>
                                            <th>{{ \App\CPU\translate('Post URL') }}</th>
                                            <th>{{ \App\CPU\translate('Start Date') }}</th>
                                            <th>{{ \App\CPU\translate('End Date') }}</th>
                                            <th>{{ \App\CPU\translate('Status') }}</th>
                                            <th>{{ \App\CPU\translate('Joined') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($participants as $key => $participant)
                                            <tr>
                                                <td>{{ $participants->firstItem() + $key }}</td>
                                                <td>
                                                    @if($participant->user)
                                                        <a href="{{ route('admin.user.view', $participant->user_id) }}" class="title-color hover-c1">
                                                            {{ $participant->user->name }}
                                                        </a>
                                                    @else
                                                        <span class="text-muted">{{ \App\CPU\translate('User not found') }} (#{{ $participant->user_id }})</span>
                                                    @endif
                                                </td>
                                                <td>{{ $participant->user->email ?? '—' }}</td>
                                                <td>{{ $participant->shared_on ?: '—' }}</td>
                                                <td>{{ $participant->earning ?? '0' }}</td>
                                                <td>
                                                    @if($participant->post_url)
                                                        <a href="{{ $participant->post_url }}" target="_blank" rel="noopener noreferrer" class="text-truncate d-inline-block" style="max-width:160px;">
                                                            {{ \App\CPU\translate('View') }}
                                                        </a>
                                                    @else
                                                        —
                                                    @endif
                                                </td>
                                                <td>{{ \App\CPU\Helpers::formatAdminDate($participant->start_date) }}</td>
                                                <td>{{ \App\CPU\Helpers::formatAdminDate($participant->end_date) }}</td>
                                                <td>
                                                    <span class="badge participant-row-status participant-status-{{ $participant->status }}">
                                                        {{ ucfirst($participant->status) }}
                                                    </span>
                                                </td>
                                                <td>{{ \App\CPU\Helpers::formatAdminDateTime($participant->created_at) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10" class="text-center text-muted py-4">
                                                    @if(($participantStatus ?? 'all') !== 'all')
                                                        {{ \App\CPU\translate('No participants found for this status.') }}
                                                    @else
                                                        {{ \App\CPU\translate('No participants yet.') }}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @if($participants->hasPages())
                                <div class="d-flex justify-content-end">
                                    {!! $participants->onEachSide(1)->links('vendor.pagination.premium') !!}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-3 d-flex justify-content-between align-items-center">
                <div class="d-flex flex-wrap gap-2">
                    @if($campaign->invoice_available)
                        <a href="{{ route('admin.campaign.invoice', $campaign->id) }}" class="btn btn-primary" target="_blank">
                            <i class="mdi mdi-file-document-outline me-1"></i>
                            @if($campaign->generate_gst_invoice)
                                {{ \App\CPU\translate('Download GST Invoice') }}
                            @else
                                {{ \App\CPU\translate('Download Invoice') }}
                            @endif
                        </a>
                    @endif
                    @if($campaign->creditNote)
                        <a href="{{ route('admin.campaign.credit-note', $campaign->id) }}" class="btn btn-outline-primary" target="_blank">
                            <i class="mdi mdi-file-document-box-multiple-outline me-1"></i>
                            {{ \App\CPU\translate('Download Credit Note') }}
                        </a>
                    @endif
                    @if($campaign->status === 'stopped')
                        @if($campaign->refund_status === 'processed')
                            <span class="badge bg-success fs-6">
                                <i class="mdi mdi-check-circle me-1"></i>
                                {{ \App\CPU\translate('Refund Processed') }}: ₹{{ number_format($campaign->refunded_amount, 2) }}
                            </span>
                        @else
                            <a href="{{ route('admin.campaign.refund-preview', $campaign->id) }}" class="btn btn-danger">
                                <i class="mdi mdi-cash-refund me-1"></i>{{ \App\CPU\translate('Process Refund') }}
                            </a>
                        @endif
                    @endif
                </div>
                <a href="{{ route('admin.campaign.list') }}" class="btn btn-secondary">{{ \App\CPU\translate('back') }}</a>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ \App\CPU\translate('Campaign Controls') }}</h5>
                </div>
                <div class="card-body">
                    <div class="form-group mb-2">
                        <small class="text-muted d-block">{{ \App\CPU\translate('Current Status') }}</small>
                        <span class="badge badge-soft-primary text-dark">{{ ucfirst($campaign->status) }}</span>
                    </div>
                    <div class="form-group mb-2">
                        <small class="text-muted d-block">{{ \App\CPU\translate('Platforms') }}</small>
                        @forelse($platforms as $platform)
                            <span class="badge badge-soft-info text-dark me-1">{{ ucfirst($platform) }}</span>
                        @empty
                            <span class="text-muted">N/A</span>
                        @endforelse
                    </div>
                    <div class="form-group mb-0">
                        <small class="text-muted d-block">{{ \App\CPU\translate('Brand') }}</small>
                        <strong>{{ $campaign->brand->name ?? $campaign->brand->username ?? 'N/A' }}</strong>
                    </div>

                     <div class="col-12 mt-3">
                            <hr class="my-0">
                        </div>

                    <h5 class="text-primary mb-3 mt-3">{{ \App\CPU\translate('Other Details') }}</h5>
                            <div class="row g-3">
                                <div class="col-md-12"><small class="text-muted d-block">{{ \App\CPU\translate('Category') }}</small><strong>{{ $campaign->category->name ?? 'N/A' }}</strong></div>
                                <div class="col-md-12"><small class="text-muted d-block">{{ \App\CPU\translate('Sub Category') }}</small><strong>{{ $campaign->subCategory->name ?? 'N/A' }}</strong></div>
                                <div class="col-md-12"><small class="text-muted d-block">{{ \App\CPU\translate('Sale') }}</small><strong>{{ $campaign->sale ? $campaign->sale->name . ' (' . $campaign->sale_id . ')' : 'N/A' }}</strong></div>
                                <div class="col-md-12"><small class="text-muted d-block">{{ \App\CPU\translate('Post Type') }}</small><strong>{{ $campaign->post_type ?: 'N/A' }}</strong></div>
                                <div class="col-md-12"><small class="text-muted d-block">{{ \App\CPU\translate('Admin Percentage (%)') }}</small><strong>{{ $campaign->admin_percentage ?? '0' }}</strong></div>
                                <div class="col-md-12"><small class="text-muted d-block">{{ \App\CPU\translate('User Percentage (%)') }}</small><strong>{{ $campaign->user_percentage ?? '0' }}</strong></div>
                                <div class="col-md-12"><small class="text-muted d-block">{{ \App\CPU\translate('Sales Percentage (%)') }}</small><strong>{{ $campaign->sales_percentage ?? '0' }}</strong></div>
                                <div class="col-md-12"><small class="text-muted d-block">{{ \App\CPU\translate('Feedback Percentage (%)') }}</small><strong>{{ $campaign->feedback_percentage ?? '0' }}</strong></div>
                                <div class="col-md-12"><small class="text-muted d-block">{{ \App\CPU\translate('Created Date') }}</small><strong>{{ \App\CPU\Helpers::formatAdminDateTime($campaign->created_at, 'N/A') }}</strong></div>
                                <div class="col-md-12"><small class="text-muted d-block">{{ \App\CPU\translate('Updated Date') }}</small><strong>{{ \App\CPU\Helpers::formatAdminDateTime($campaign->updated_at, 'N/A') }}</strong></div>
                            </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Lightbox Modal --}}
<div class="modal fade" id="campaignLightbox" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-header border-0 pb-0" style="position:absolute;top:8px;right:8px;z-index:10;">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img id="lightboxImg" src="" alt="Zoomed Image" style="width:100%;">
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    // Lightbox
    $(document).on('click', '.campaign-zoom-trigger', function(e) {
        e.preventDefault();
        var src = $(this).data('src') || $(this).find('img').attr('src');
        var fallback = $(this).data('fallback') || '';
        var img = document.getElementById('lightboxImg');
        img.src = src;
        if (fallback) {
            img.onerror = function() { this.src = fallback; this.onerror = null; };
        } else {
            img.onerror = null;
        }
        var modal = new bootstrap.Modal(document.getElementById('campaignLightbox'));
        modal.show();
    });

    $(document).on('change', '.change-status', function() {
        var id = $(this).attr('data-id');
        var status = $(this).val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "{{ route('admin.campaign.status') }}",
            method: 'POST',
            data: {
                id: id,
                status: status
            },
            success: function(response) {
                if (response.status) {
                    swal.fire('', '{{ \App\CPU\translate('Status updated successfully!') }}', 'success');
                } else {
                    swal.fire('', '{{ \App\CPU\translate('Something went wrong!') }}', 'error');
                }
            }
        });
    });
</script>
@endpush

@push('css')
<style>
    .campaign-gallery-image {
        height: 120px;
        object-fit: cover;
    }

    /* Prevent text from overflowing its container */
    .card-body strong,
    .card-body p {
        word-break: break-word;
        overflow-wrap: break-word;
        display: block;
    }

    /* Lightbox */
    #campaignLightbox .modal-dialog {
        max-width: 95vw;
        width: 95vw;
    }

    #campaignLightbox .modal-body {
        padding: 0;
        background: #000;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 60vh;
    }

    #campaignLightbox .modal-body img {
        width: 100%;
        max-height: 85vh;
        object-fit: contain;
    }

    .campaign-zoom-trigger {
        display: block;
    }

    .participant-status-badge {
        background: #eef2f6;
        color: #4a5568;
        border: 1px solid #dde3ea;
        cursor: pointer;
    }

    .participant-status-badge.active {
        color: #fff !important;
        border-color: transparent;
    }

    .participant-status-badge.participant-status-pending.active { background: #f59e0b; }
    .participant-status-badge.participant-status-active.active { background: #3b82f6; }
    .participant-status-badge.participant-status-approved.active { background: #10b981; }
    .participant-status-badge.participant-status-completed.active { background: #059669; }
    .participant-status-badge.participant-status-rejected.active { background: #ef4444; }
    .participant-status-badge.participant-status-flagged.active { background: #8b5cf6; }
    .participant-status-badge.participant-status-deleted.active { background: #6b7280; }
    .participant-status-badge.participant-status-other.active { background: #64748b; }

    .participant-row-status.participant-status-pending { background: #fef3c7; color: #92400e; }
    .participant-row-status.participant-status-active { background: #dbeafe; color: #1e40af; }
    .participant-row-status.participant-status-approved { background: #d1fae5; color: #065f46; }
    .participant-row-status.participant-status-completed { background: #a7f3d0; color: #047857; }
    .participant-row-status.participant-status-rejected { background: #fee2e2; color: #991b1b; }
    .participant-row-status.participant-status-flagged { background: #ede9fe; color: #5b21b6; }
    .participant-row-status.participant-status-deleted { background: #f3f4f6; color: #374151; }
</style>
@endpush