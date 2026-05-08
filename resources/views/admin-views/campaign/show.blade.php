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
                                    <strong>{{ $campaign->start_date ?: 'N/A' }}</strong>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-muted d-block">{{ \App\CPU\translate('End Date') }}</small>
                                    <strong>{{ $campaign->end_date ?: 'N/A' }}</strong>
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
                                    <div>
                                        @forelse($guidelines as $guideline)
                                            <span class="badge badge-soft-success text-dark me-1 mb-1">{{ $guideline }}</span>
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
                                <div class="col-md-4"><small class="text-muted d-block">{{ \App\CPU\translate('Total Shared') }}</small><strong>{{ $campaign->campaign_transactions->count() }}</strong></div>
                                <div class="col-md-4"><small class="text-muted d-block">{{ \App\CPU\translate('Occupied Slots') }}</small><strong>{{ $campaign->occupied_slots }}</strong></div>
                                <div class="col-md-4"><small class="text-muted d-block">{{ \App\CPU\translate('Available Slots') }}</small><strong>{{ $campaign->available_slots }}</strong></div>
                                <div class="col-md-4"><small class="text-muted d-block">{{ \App\CPU\translate('Slot Full') }}</small><strong>{{ $campaign->is_slot_full ? 'Yes' : 'No' }}</strong></div>
                                <div class="col-md-4"><small class="text-muted d-block">{{ \App\CPU\translate('Average Feedback') }}</small><strong>{{ $campaign->avg_feedback }}</strong></div>
                                <div class="col-md-4"><small class="text-muted d-block">{{ \App\CPU\translate('Engagement') }}</small><strong>{{ $campaign->engagement }}</strong></div>
                                <div class="col-md-4"><small class="text-muted d-block">{{ \App\CPU\translate('Cost Per Click') }}</small><strong>{{ $campaign->cost_per_click }}</strong></div>
                            </div>
                        </div>

                       

                        
                    </div>
                </div>
            </div>
            <div class="mt-3 d-flex justify-content-end">
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
                                <div class="col-md-12"><small class="text-muted d-block">{{ \App\CPU\translate('Created Date') }}</small><strong>{{ $campaign->created_at ? $campaign->created_at->format('d M Y \a\t g:i A') : 'N/A' }}</strong></div>
                                <div class="col-md-12"><small class="text-muted d-block">{{ \App\CPU\translate('Updated Date') }}</small><strong>{{ $campaign->updated_at ? $campaign->updated_at->format('d M Y \a\t g:i A') : 'N/A' }}</strong></div>
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
</style>
@endpush