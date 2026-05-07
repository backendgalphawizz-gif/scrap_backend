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
            @php $d = $data['landing_hero'] ?? [] @endphp
            <h5 class="card-title mb-4"><i class="mdi mdi-home-variant me-2 text-primary"></i>Hero Section</h5>
            <form action="{{ route('admin.landing-page.update', 'landing_hero') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="title-color">Headline <span class="text-danger">*</span></label>
                        <input type="text" name="headline" class="form-control" value="{{ $d['headline'] ?? '' }}" placeholder="Run Smarter Ads. Grow Faster." required>
                        <small class="text-muted">Use <code>**word**</code> to highlight a word in a different color.</small>
                    </div>
                    <div class="col-md-6">
                        <label class="title-color">Sub Headline</label>
                        <input type="text" name="sub_headline" class="form-control" value="{{ $d['sub_headline'] ?? '' }}" placeholder="Manage and launch your social media campaigns…">
                    </div>
                    <div class="col-md-4">
                        <label class="title-color">CTA Button Text</label>
                        <input type="text" name="cta_text" class="form-control" value="{{ $d['cta_text'] ?? '' }}" placeholder="Start Advertising">
                    </div>
                    <div class="col-md-8">
                        <label class="title-color">CTA Button Link</label>
                        <input type="text" name="cta_link" class="form-control" value="{{ $d['cta_link'] ?? '' }}" placeholder="https://...">
                    </div>
                </div>
                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary px-5">Save Hero Section</button>
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
            @php $d = $data['landing_advertise'] ?? []; $features = $d['features'] ?? [['icon'=>'','title'=>'','desc'=>''],['icon'=>'','title'=>'','desc'=>''],['icon'=>'','title'=>'','desc'=>'']]; $stats = $d['stats'] ?? [['value'=>'','label'=>''],['value'=>'','label'=>''],['value'=>'','label'=>''],['value'=>'','label'=>'']] @endphp
            <h5 class="card-title mb-4"><i class="mdi mdi-bullhorn me-2 text-primary"></i>Advertise Section</h5>
            <form action="{{ route('admin.landing-page.update', 'landing_advertise') }}" method="POST">
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
            <form action="{{ route('admin.landing-page.update', 'landing_mobile') }}" method="POST">
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
                </div>
                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary px-5">Save Mobile App Section</button>
                </div>
            </form>
            @endif

            {{-- ============== FAQ ============== --}}
            @if($activeTab === 'landing_faq')
            @php
                $d = $data['landing_faq'] ?? [];
                $faqItems = $d['items'] ?? [];
                while(count($faqItems) < 4) { $faqItems[] = ['question'=>'','answer'=>'']; }
            @endphp
            <h5 class="card-title mb-4"><i class="mdi mdi-help-circle me-2 text-primary"></i>FAQ Section</h5>
            <form action="{{ route('admin.landing-page.update', 'landing_faq') }}" method="POST">
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

                <div class="text-end mt-2">
                    <button type="submit" class="btn btn-primary px-5">Save FAQ Section</button>
                </div>
            </form>
            @endif

        </div>
    </div>
</div>
@endsection

@push('script')
<script>
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
