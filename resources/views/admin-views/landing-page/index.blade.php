@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('Landing Page'))

@section('content')
<div class="content-wrapper">
    <div class="mb-3 d-flex align-items-center justify-content-between">
        <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
            <i class="mdi mdi-web fs-4 text-primary"></i>
            {{ \App\CPU\translate('Landing Page Management') }}
        </h2>
    </div>

    @php
        $activeTab = request('tab', 'landing_hero');
        $tabs = [
            'landing_hero'       => ['label' => 'Hero',      'icon' => 'mdi-home-variant'],
            'landing_tagline'    => ['label' => 'Tagline',   'icon' => 'mdi-format-quote-open'],
            'landing_advertise'  => ['label' => 'Advertise', 'icon' => 'mdi-bullhorn'],
            'landing_services'   => ['label' => 'Services',  'icon' => 'mdi-briefcase'],
            'landing_about'      => ['label' => 'About',     'icon' => 'mdi-information'],
            'landing_mobile'     => ['label' => 'Mobile App','icon' => 'mdi-cellphone'],
            'landing_faq'        => ['label' => 'FAQ',       'icon' => 'mdi-help-circle'],
        ];
    @endphp

    <ul class="nav nav-tabs mb-4" id="landingTabs">
        @foreach($tabs as $key => $tab)
        <li class="nav-item">
            <a class="nav-link {{ $activeTab === $key ? 'active' : '' }}"
               href="{{ route('admin.landing-page.index', ['tab' => $key]) }}">
                <i class="mdi {{ $tab['icon'] }} me-1"></i>{{ $tab['label'] }}
            </a>
        </li>
        @endforeach
    </ul>

    <div class="card">
        <div class="card-body">

            {{-- ============== HERO ============== --}}
            @if($activeTab === 'landing_hero')
            @php
                $d = $data['landing_hero'] ?? [];
                $slides = $d['slides'] ?? [];
            @endphp
            <h5 class="card-title mb-4"><i class="mdi mdi-home-variant me-2 text-primary"></i>Hero Slider</h5>
            <form action="{{ route('admin.landing-page.update', 'landing_hero') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Existing slides --}}
                @if(count($slides))
                <h6 class="fw-bold mb-3">Current Slides</h6>
                <div class="row g-3 mb-3" id="existingSlidesContainer">
                    @foreach($slides as $si => $slide)
                    <div class="col-md-4" id="existing-slide-{{ $si }}">
                        <div class="card border h-100">
                            <div class="text-center" style="height:140px;overflow:hidden;background:#f1f3f5;">
                            <img src="{{ asset('storage/landing/hero/' . $slide['image']) }}"
                                     alt="Slide {{ $si + 1 }}"
                                     style="max-height:140px;max-width:100%;object-fit:cover;width:100%;">
                            </div>
                            <div class="card-body">
                                <input type="hidden" name="slides[{{ $si }}][image]" value="{{ $slide['image'] }}">
                                <label class="title-color small">Title <small class="text-muted">(optional)</small></label>
                                <input type="text" name="slides[{{ $si }}][title]" class="form-control form-control-sm mb-2"
                                       value="{{ $slide['title'] ?? '' }}" placeholder="Slide title">
                                <label class="title-color small">Link <small class="text-muted">(optional)</small></label>
                                <input type="text" name="slides[{{ $si }}][link]" class="form-control form-control-sm mb-2"
                                       value="{{ $slide['link'] ?? '' }}" placeholder="https://...">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remove_slides[]" value="{{ $si }}" id="rm-slide-{{ $si }}">
                                    <label class="form-check-label text-danger small" for="rm-slide-{{ $si }}">Remove this slide</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-muted small mb-3" id="noSlidesMsg">No slides added yet.</p>
                @endif

                {{-- New slides --}}
                <div id="newSlidesContainer" class="row g-3 mb-3"></div>
                <button type="button" class="btn btn-outline-secondary btn-sm mb-4" id="addSlideBtn">
                    <i class="mdi mdi-plus me-1"></i>Add Slide
                </button>

                <div class="text-end mt-2">
                    <button type="submit" class="btn btn-primary px-5">Save Hero Slider</button>
                </div>
            </form>
            @endif

            {{-- ============== TAGLINE ============== --}}
            @if($activeTab === 'landing_tagline')
            @php $d = $data['landing_tagline'] ?? [] @endphp
            <h5 class="card-title mb-4"><i class="mdi mdi-format-quote-open me-2 text-primary"></i>Tagline Section</h5>
            <form action="{{ route('admin.landing-page.update', 'landing_tagline') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="title-color">Badge Text</label>
                        <input type="text" name="badge_text" class="form-control" value="{{ $d['badge_text'] ?? '' }}" placeholder="TRUSTED BRAND & CLEAN DATA">
                    </div>
                    <div class="col-md-6">
                        <label class="title-color">Headline <span class="text-danger">*</span></label>
                        <input type="text" name="headline" class="form-control" value="{{ $d['headline'] ?? '' }}" placeholder="Turn every post into measurable growth" required>
                    </div>
                    <div class="col-md-12">
                        <label class="title-color">Subtitle</label>
                        <textarea name="subtitle" class="form-control" rows="2" placeholder="Rexarix helps brands and creators…">{{ $d['subtitle'] ?? '' }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="title-color">App Store Link</label>
                        <input type="text" name="app_store_link" class="form-control" value="{{ $d['app_store_link'] ?? '' }}" placeholder="https://apps.apple.com/...">
                    </div>
                    <div class="col-md-6">
                        <label class="title-color">Google Play Link</label>
                        <input type="text" name="play_store_link" class="form-control" value="{{ $d['play_store_link'] ?? '' }}" placeholder="https://play.google.com/...">
                    </div>
                </div>
                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary px-5">Save Tagline Section</button>
                </div>
            </form>
            @endif

            {{-- ============== ADVERTISE ============== --}}
            @if($activeTab === 'landing_advertise')
            @php
                $d = $data['landing_advertise'] ?? [];
                $features = $d['features'] ?? [['icon'=>'','title'=>'','desc'=>''],['icon'=>'','title'=>'','desc'=>''],['icon'=>'','title'=>'','desc'=>'']];
                $stats = $d['stats'] ?? [['value'=>'','label'=>''],['value'=>'','label'=>''],['value'=>'','label'=>''],['value'=>'','label'=>'']];
                $banners = $d['banners'] ?? [];
            @endphp
            <h5 class="card-title mb-4"><i class="mdi mdi-bullhorn me-2 text-primary"></i>Advertise Section</h5>
            <form action="{{ route('admin.landing-page.update', 'landing_advertise') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="title-color">Headline <span class="text-danger">*</span></label>
                        <input type="text" name="headline" class="form-control" value="{{ $d['headline'] ?? '' }}" placeholder="Reach the right audience" required>
                    </div>
                    <div class="col-md-6">
                        <label class="title-color">Subtitle</label>
                        <input type="text" name="subtitle" class="form-control" value="{{ $d['subtitle'] ?? '' }}" placeholder="Promote your brand across high-intent placements…">
                    </div>
                </div>

                <h6 class="fw-bold mb-3">Feature Cards (3)</h6>
                <div class="row g-3 mb-4">
                    @foreach($features as $i => $f)
                    <div class="col-md-4">
                        <div class="card border h-100">
                            <div class="card-body">
                                <label class="title-color">Feature {{ $i + 1 }} Title</label>
                                <input type="text" name="features[{{ $i }}][title]" class="form-control mb-2" value="{{ $f['title'] ?? '' }}" placeholder="Precision targeting">
                                <label class="title-color">MDI Icon class</label>
                                <input type="text" name="features[{{ $i }}][icon]" class="form-control mb-2" value="{{ $f['icon'] ?? '' }}" placeholder="mdi-target">
                                <label class="title-color">Description</label>
                                <textarea name="features[{{ $i }}][desc]" class="form-control" rows="2" placeholder="Layer demographics…">{{ $f['desc'] ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <h6 class="fw-bold mb-3">Stats (4)</h6>
                <div class="row g-3">
                    @foreach($stats as $i => $s)
                    <div class="col-md-3">
                        <div class="card border">
                            <div class="card-body">
                                <label class="title-color">Stat {{ $i + 1 }} Value</label>
                                <input type="text" name="stats[{{ $i }}][value]" class="form-control mb-2" value="{{ $s['value'] ?? '' }}" placeholder="27M+">
                                <label class="title-color">Label</label>
                                <input type="text" name="stats[{{ $i }}][label]" class="form-control" value="{{ $s['label'] ?? '' }}" placeholder="Monthly Impressions">
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Banners --}}
                <hr class="my-4">
                <h6 class="fw-bold mb-3">Banners</h6>

                {{-- Existing banners --}}
                <div id="existingBannersContainer" class="row g-3 mb-3">
                    @forelse($banners as $bi => $banner)
                    <div class="col-md-4 existing-banner-item" id="existing-banner-{{ $bi }}">
                        <div class="card border h-100">
                            <div class="card-body">
                                <div class="mb-2 text-center" style="height:120px;overflow:hidden;background:#f8f9fa;border-radius:4px;">
                                    <img src="{{ asset('storage/landing/banners/' . $banner['image']) }}" alt="Banner" style="max-height:120px;max-width:100%;object-fit:contain;">
                                </div>
                                <input type="hidden" name="banners[{{ $bi }}][image]" value="{{ $banner['image'] }}">
                                <label class="title-color small">Title <small class="text-muted">(optional)</small></label>
                                <input type="text" name="banners[{{ $bi }}][title]" class="form-control form-control-sm mb-2" value="{{ $banner['title'] ?? '' }}" placeholder="Banner title">
                                <label class="title-color small">Link <small class="text-muted">(optional)</small></label>
                                <input type="text" name="banners[{{ $bi }}][link]" class="form-control form-control-sm mb-2" value="{{ $banner['link'] ?? '' }}" placeholder="https://...">
                                <div class="form-check">
                                    <input class="form-check-input banner-remove-check" type="checkbox" name="remove_banners[]" value="{{ $bi }}" id="rm-banner-{{ $bi }}">
                                    <label class="form-check-label text-danger small" for="rm-banner-{{ $bi }}">Remove this banner</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12" id="noBannersMsg">
                        <p class="text-muted small">No banners added yet.</p>
                    </div>
                    @endforelse
                </div>

                {{-- Add new banners --}}
                <div id="newBannersContainer" class="row g-3 mb-3"></div>
                <button type="button" class="btn btn-outline-secondary btn-sm mb-3" id="addBannerBtn">
                    <i class="mdi mdi-plus me-1"></i>Add Banner
                </button>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary px-5">Save Advertise Section</button>
                </div>
            </form>
            @endif

            {{-- ============== SERVICES ============== --}}
            @if($activeTab === 'landing_services')
            @php
                $d = $data['landing_services'] ?? [];
                $items = $d['items'] ?? [];
                while(count($items) < 6) { $items[] = ['icon'=>'','title'=>'','desc'=>'']; }
            @endphp
            <h5 class="card-title mb-4"><i class="mdi mdi-briefcase me-2 text-primary"></i>Services Section</h5>
            <form action="{{ route('admin.landing-page.update', 'landing_services') }}" method="POST">
                @csrf
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="title-color">Headline <span class="text-danger">*</span></label>
                        <input type="text" name="headline" class="form-control" value="{{ $d['headline'] ?? '' }}" placeholder="Everything you need to scale" required>
                    </div>
                    <div class="col-md-6">
                        <label class="title-color">Subtitle</label>
                        <input type="text" name="subtitle" class="form-control" value="{{ $d['subtitle'] ?? '' }}" placeholder="From campaign setup to ongoing optimization…">
                    </div>
                </div>

                <h6 class="fw-bold mb-3">Service Cards (6)</h6>
                <div class="row g-3">
                    @foreach($items as $i => $item)
                    <div class="col-md-4">
                        <div class="card border h-100">
                            <div class="card-body">
                                <label class="title-color">Service {{ $i + 1 }} Title</label>
                                <input type="text" name="items[{{ $i }}][title]" class="form-control mb-2" value="{{ $item['title'] ?? '' }}" placeholder="Campaign management">
                                <label class="title-color">MDI Icon class</label>
                                <input type="text" name="items[{{ $i }}][icon]" class="form-control mb-2" value="{{ $item['icon'] ?? '' }}" placeholder="mdi-chart-line">
                                <label class="title-color">Description</label>
                                <textarea name="items[{{ $i }}][desc]" class="form-control" rows="2" placeholder="Unified workflows…">{{ $item['desc'] ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary px-5">Save Services Section</button>
                </div>
            </form>
            @endif

            {{-- ============== ABOUT ============== --}}
            @if($activeTab === 'landing_about')
            @php $d = $data['landing_about'] ?? []; $bullets = $d['bullets'] ?? [] @endphp
            <h5 class="card-title mb-4"><i class="mdi mdi-information me-2 text-primary"></i>About Section</h5>
            <form action="{{ route('admin.landing-page.update', 'landing_about') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="title-color">Headline <span class="text-danger">*</span></label>
                        <input type="text" name="headline" class="form-control" value="{{ $d['headline'] ?? '' }}" placeholder="Built for clarity in a noisy feed" required>
                    </div>
                    <div class="col-md-6">
                        <label class="title-color">Main Content</label>
                        <textarea name="content" class="form-control" rows="4" placeholder="We believe social and digital campaigns…">{{ $d['content'] ?? '' }}</textarea>
                    </div>
                    <div class="col-md-12">
                        <label class="title-color">Bullet Points <small class="text-muted">(one per line)</small></label>
                        <textarea name="bullets" class="form-control" rows="5" placeholder="Transparent pricing and placement controls&#10;Privacy-forward data handling and regional compliance&#10;Collaboration tools for marketing, sales, and agencies">{{ implode("\n", $bullets) }}</textarea>
                    </div>
                </div>
                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary px-5">Save About Section</button>
                </div>
            </form>
            @endif

            {{-- ============== MOBILE APP ============== --}}
            @if($activeTab === 'landing_mobile')
            @php $d = $data['landing_mobile'] ?? [] @endphp
            <h5 class="card-title mb-4"><i class="mdi mdi-cellphone me-2 text-primary"></i>Mobile App Section</h5>
            <form action="{{ route('admin.landing-page.update', 'landing_mobile') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="title-color">Headline <span class="text-danger">*</span></label>
                        <input type="text" name="headline" class="form-control" value="{{ $d['headline'] ?? '' }}" placeholder="Manage campaigns on the go" required>
                    </div>
                    <div class="col-md-6">
                        <label class="title-color">Subtitle</label>
                        <textarea name="subtitle" class="form-control" rows="2" placeholder="Approve creatives, adjust budgets…">{{ $d['subtitle'] ?? '' }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="title-color">App Store Link</label>
                        <input type="text" name="app_store_link" class="form-control" value="{{ $d['app_store_link'] ?? '' }}" placeholder="https://apps.apple.com/...">
                    </div>
                    <div class="col-md-6">
                        <label class="title-color">Google Play Link</label>
                        <input type="text" name="play_store_link" class="form-control" value="{{ $d['play_store_link'] ?? '' }}" placeholder="https://play.google.com/...">
                    </div>

                    {{-- Banner Image --}}
                    <div class="col-md-12"><hr class="my-2"><h6 class="fw-bold">Banner Image</h6></div>
                    @if(!empty($d['banner_image']))
                    <div class="col-md-12">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <img src="{{ asset('storage/landing/mobile/' . $d['banner_image']) }}" alt="Mobile Banner" style="max-height:120px;border-radius:4px;">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remove_banner_image" value="1" id="removeMobileBanner">
                                <label class="form-check-label text-danger" for="removeMobileBanner">Remove image</label>
                            </div>
                        </div>
                        <input type="hidden" name="existing_banner_image" value="{{ $d['banner_image'] }}">
                    </div>
                    @endif
                    <div class="col-md-6">
                        <label class="title-color">{{ !empty($d['banner_image']) ? 'Replace Banner Image' : 'Upload Banner Image' }}</label>
                        <input type="file" name="banner_image" class="form-control" accept="image/*" id="mobileBannerInput">
                        <div id="mobileBannerPreview" class="mt-2"></div>
                    </div>
                </div>
                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary px-5">Save Mobile App Section</button>
                </div>
            </form>
            <script>
            document.getElementById('mobileBannerInput')?.addEventListener('change', function() {
                const preview = document.getElementById('mobileBannerPreview');
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = e => { preview.innerHTML = '<img src="' + e.target.result + '" style="max-height:120px;border-radius:4px;">'; };
                    reader.readAsDataURL(this.files[0]);
                } else {
                    preview.innerHTML = '';
                }
            });
            </script>
            @endif

            {{-- ============== FAQ ============== --}}
            @if($activeTab === 'landing_faq')
            @php
                $d = $data['landing_faq'] ?? [];
                $faqItems = $d['items'] ?? [];
                while(count($faqItems) < 4) { $faqItems[] = ['question'=>'','answer'=>'']; }
            @endphp
            <h5 class="card-title mb-4"><i class="mdi mdi-help-circle me-2 text-primary"></i>FAQ Section</h5>
            <form action="{{ route('admin.landing-page.update', 'landing_faq') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="title-color">Headline <span class="text-danger">*</span></label>
                        <input type="text" name="headline" class="form-control" value="{{ $d['headline'] ?? '' }}" placeholder="Common questions" required>
                    </div>
                    <div class="col-md-6">
                        <label class="title-color">Subtitle</label>
                        <input type="text" name="subtitle" class="form-control" value="{{ $d['subtitle'] ?? '' }}" placeholder="Quick answers about advertising, billing…">
                    </div>
                </div>

                <h6 class="fw-bold mb-3">FAQ Items</h6>
                <div id="faqContainer">
                    @foreach($faqItems as $i => $item)
                    <div class="card border mb-3 faq-item">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-bold text-muted">Q{{ $i + 1 }}</span>
                                @if($i >= 4)
                                <button type="button" class="btn btn-sm btn-outline-danger remove-faq">Remove</button>
                                @endif
                            </div>
                            <input type="text" name="items[{{ $i }}][question]" class="form-control mb-2" value="{{ $item['question'] ?? '' }}" placeholder="How do I start advertising on Rexarix?">
                            <textarea name="items[{{ $i }}][answer]" class="form-control" rows="3" placeholder="Answer…">{{ $item['answer'] ?? '' }}</textarea>
                        </div>
                    </div>
                    @endforeach
                </div>

                <button type="button" class="btn btn-outline-secondary btn-sm mb-3" id="addFaqBtn">
                    <i class="mdi mdi-plus me-1"></i>Add FAQ
                </button>

                {{-- Banner Image --}}
                <hr class="my-3">
                <h6 class="fw-bold mb-3">Banner Image</h6>
                <div class="row g-3">
                    @if(!empty($d['banner_image']))
                    <div class="col-md-12">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <img src="{{ asset('storage/landing/faq/' . $d['banner_image']) }}" alt="FAQ Banner" style="max-height:120px;border-radius:4px;">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remove_banner_image" value="1" id="removeFaqBanner">
                                <label class="form-check-label text-danger" for="removeFaqBanner">Remove image</label>
                            </div>
                        </div>
                        <input type="hidden" name="existing_banner_image" value="{{ $d['banner_image'] }}">
                    </div>
                    @endif
                    <div class="col-md-6">
                        <label class="title-color">{{ !empty($d['banner_image']) ? 'Replace Banner Image' : 'Upload Banner Image' }}</label>
                        <input type="file" name="banner_image" class="form-control" accept="image/*" id="faqBannerInput">
                        <div id="faqBannerPreview" class="mt-2"></div>
                    </div>
                </div>

                <div class="text-end mt-2">
                    <button type="submit" class="btn btn-primary px-5">Save FAQ Section</button>
                </div>
            </form>
            <script>
            document.getElementById('faqBannerInput')?.addEventListener('change', function() {
                const preview = document.getElementById('faqBannerPreview');
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = e => { preview.innerHTML = '<img src="' + e.target.result + '" style="max-height:120px;border-radius:4px;">'; };
                    reader.readAsDataURL(this.files[0]);
                } else {
                    preview.innerHTML = '';
                }
            });
            </script>
            @endif

        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    // Hero slider: dynamic add slide
    let slideIndex = 0;

    $('#addSlideBtn').on('click', function () {
        const html = `
            <div class="col-md-4 new-slide-item" id="new-slide-${slideIndex}">
                <div class="card border h-100">
                    <div class="new-slide-preview text-center" style="height:140px;background:#f1f3f5;display:flex;align-items:center;justify-content:center;">
                        <span class="text-muted small">Preview</span>
                    </div>
                    <div class="card-body">
                        <label class="title-color small">Slide Image <span class="text-danger">*</span></label>
                        <input type="file" name="new_slides[]" class="form-control form-control-sm mb-2 slide-file-input" accept="image/*">
                        <label class="title-color small">Title <small class="text-muted">(optional)</small></label>
                        <input type="text" name="new_slide_titles[]" class="form-control form-control-sm mb-2" placeholder="Slide title">
                        <label class="title-color small">Link <small class="text-muted">(optional)</small></label>
                        <input type="text" name="new_slide_links[]" class="form-control form-control-sm mb-2" placeholder="https://...">
                        <button type="button" class="btn btn-sm btn-outline-danger w-100 remove-new-slide" data-target="new-slide-${slideIndex}">Remove</button>
                    </div>
                </div>
            </div>`;
        $('#newSlidesContainer').append(html);
        $('#noSlidesMsg').hide();
        slideIndex++;
    });

    $(document).on('change', '.slide-file-input', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        const preview = $(this).closest('.card').find('.new-slide-preview');
        reader.onload = e => {
            preview.html(`<img src="${e.target.result}" style="max-height:140px;max-width:100%;object-fit:cover;width:100%;">`);
        };
        reader.readAsDataURL(file);
    });

    $(document).on('click', '.remove-new-slide', function () {
        $(`#${$(this).data('target')}`).remove();
    });

    // Banner dynamic add
    let bannerIndex = 0;

    $('#addBannerBtn').on('click', function () {
        const html = `
            <div class="col-md-4 new-banner-item" id="new-banner-${bannerIndex}">
                <div class="card border h-100">
                    <div class="card-body">
                        <div class="mb-2 text-center new-banner-preview" style="height:120px;background:#f8f9fa;border-radius:4px;display:flex;align-items:center;justify-content:center;">
                            <span class="text-muted small">Preview</span>
                        </div>
                        <label class="title-color small">Banner Image <span class="text-danger">*</span></label>
                        <input type="file" name="new_banners[]" class="form-control form-control-sm mb-2 banner-file-input" accept="image/*" data-preview="${bannerIndex}">
                        <label class="title-color small">Title <small class="text-muted">(optional)</small></label>
                        <input type="text" name="new_banner_titles[]" class="form-control form-control-sm mb-2" placeholder="Banner title">
                        <label class="title-color small">Link <small class="text-muted">(optional)</small></label>
                        <input type="text" name="new_banner_links[]" class="form-control form-control-sm mb-2" placeholder="https://...">
                        <button type="button" class="btn btn-sm btn-outline-danger w-100 remove-new-banner" data-target="new-banner-${bannerIndex}">Remove</button>
                    </div>
                </div>
            </div>`;
        $('#newBannersContainer').append(html);
        $('#noBannersMsg').hide();
        bannerIndex++;
    });

    $(document).on('change', '.banner-file-input', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        const preview = $(this).closest('.card-body').find('.new-banner-preview');
        reader.onload = e => {
            preview.html(`<img src="${e.target.result}" style="max-height:120px;max-width:100%;object-fit:contain;">`);
        };
        reader.readAsDataURL(file);
    });

    $(document).on('click', '.remove-new-banner', function () {
        const target = $(this).data('target');
        $(`#${target}`).remove();
    });

    // FAQ dynamic add/remove
    let faqIndex = {{ isset($faqItems) ? count($faqItems) : 4 }};

    $('#addFaqBtn').on('click', function () {
        const html = `
            <div class="card border mb-3 faq-item">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold text-muted">Q${faqIndex + 1}</span>
                        <button type="button" class="btn btn-sm btn-outline-danger remove-faq">Remove</button>
                    </div>
                    <input type="text" name="items[${faqIndex}][question]" class="form-control mb-2" placeholder="Question…">
                    <textarea name="items[${faqIndex}][answer]" class="form-control" rows="3" placeholder="Answer…"></textarea>
                </div>
            </div>`;
        $('#faqContainer').append(html);
        faqIndex++;
    });

    $(document).on('click', '.remove-faq', function () {
        $(this).closest('.faq-item').remove();
    });
</script>
@endpush
