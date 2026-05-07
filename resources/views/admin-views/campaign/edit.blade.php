@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Campaign Edit'))

@push('css_or_js')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
@php
    $selectedPlatforms = old('social_media', explode(',', (string) $campaign->share_on));
    $statusLists = ['pending', 'active', 'inactive', 'accepted', 'rejected', 'completed', 'paused', 'stopped', 'violated'];
    $selectedCategoryId = old('category_id', $campaign->category_id);
    $selectedSubCategoryId = old('sub_category_id', $campaign->sub_category_id);
    $categoryOptions = $categories->map(function ($category) {
        return [
            'id' => $category->id,
            'children' => $category->childes->map(function ($child) {
                return ['id' => $child->id, 'name' => $child->name];
            })->values(),
        ];
    })->values();
    $stateIdToName = $states->pluck('name', 'state_id');
    $cityDataByState = [];
    foreach ($cities as $city) {
        $stateName = $stateIdToName[$city->state_id] ?? null;
        if ($stateName) {
            $cityDataByState[$stateName][] = $city->name;
        }
    }
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
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>{{\App\CPU\translate('edit_campaign')}}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.campaign.update', $campaign->id) }}" method="post" enctype="multipart/form-data" class="banner_form">
                        @csrf

                        <div class="campaign-edit-section mb-4">
                            <h5 class="mb-3">{{ \App\CPU\translate('Basic Information') }}</h5>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="brand_id">{{ \App\CPU\translate('Brand')}}</label>
                                        <select name="brand_id" id="brand_id" class="form-select form-control @error('brand_id') is-invalid @enderror" required>
                                            <option value="">{{ \App\CPU\translate('Select')}}</option>
                                            @foreach($sellers as $seller)
                                                <option value="{{ $seller->id }}" {{ old('brand_id', $campaign->brand_id) == $seller->id ? 'selected' : '' }}>{{ $seller->username }}</option>
                                            @endforeach
                                        </select>
                                        @error('brand_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="title">{{ \App\CPU\translate('Title')}}</label>
                                        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $campaign->title) }}" required>
                                        @error('title') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="status">{{ \App\CPU\translate('Status')}}</label>
                                        <select name="status" id="status" class="form-select form-control @error('status') is-invalid @enderror" required>
                                            @foreach($statusLists as $status)
                                                <option value="{{ $status }}" {{ old('status', $campaign->status) === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                                            @endforeach
                                        </select>
                                        @error('status') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="category_id">{{ \App\CPU\translate('Category')}}</label>
                                        <select name="category_id" id="category_id" class="form-select form-control @error('category_id') is-invalid @enderror" required>
                                            <option value="">{{ \App\CPU\translate('Select')}}</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ (string)$selectedCategoryId === (string)$category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('category_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="sub_category_id">{{ \App\CPU\translate('Sub Category')}}</label>
                                        <select name="sub_category_id" id="sub_category_id" class="form-select form-control @error('sub_category_id') is-invalid @enderror">
                                            <option value="">{{ \App\CPU\translate('Select')}}</option>
                                        </select>
                                        @error('sub_category_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="caption">{{ \App\CPU\translate('Caption')}}</label>
                                        <textarea name="caption" id="caption" class="form-control @error('caption') is-invalid @enderror" rows="4" required>{{ old('caption', $campaign->descriptions) }}</textarea>
                                        @error('caption') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="hashtags">{{ \App\CPU\translate('Hashtags')}}</label>
                                        <input type="text" name="hashtags" id="hashtags" class="form-control @error('hashtags') is-invalid @enderror" placeholder="#abc #test" value="{{ old('hashtags', $campaign->tags) }}" required>
                                        @error('hashtags') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="social_media">{{ \App\CPU\translate('Social Media')}}</label>
                                        <select name="social_media[]" id="social_media" class="form-control @error('social_media') is-invalid @enderror" multiple size="2" required>
                                            <option value="instagram" {{ in_array('instagram', $selectedPlatforms) ? 'selected' : '' }}>{{ \App\CPU\translate('Instagram')}}</option>
                                            <option value="facebook" {{ in_array('facebook', $selectedPlatforms) ? 'selected' : '' }}>{{ \App\CPU\translate('Facebook')}}</option>
                                        </select>
                                        @error('social_media') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="sales_referal_code">{{ \App\CPU\translate('Sale Referral Code')}}</label>
                                        <input type="text" name="sales_referal_code" id="sales_referal_code" class="form-control @error('sales_referal_code') is-invalid @enderror" value="{{ old('sales_referal_code', $campaign->sales_referal_code) }}">
                                        @error('sales_referal_code') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="campaign-edit-section mb-4">
                            <h5 class="mb-3">{{ \App\CPU\translate('Targeting & Schedule') }}</h5>
                            @php($currentAgeRange = old('age_range', $campaign->age_range))
                            @php($ageParts = explode('-', (string) $currentAgeRange))
                            @php($selectedMinAge = isset($ageParts[0]) ? (int) trim($ageParts[0]) : '')
                            @php($selectedMaxAge = isset($ageParts[1]) ? (int) trim($ageParts[1]) : '')
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="start_date">{{ \App\CPU\translate('Start Date')}}</label>
                                        <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', $campaign->start_date) }}" required>
                                        @error('start_date') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="end_date">{{ \App\CPU\translate('End Date')}}</label>
                                        <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', $campaign->end_date) }}" required>
                                        @error('end_date') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="gender">{{ \App\CPU\translate('Gender')}}</label>
                                        <select name="gender" id="gender" class="form-select form-control @error('gender') is-invalid @enderror" required>
                                            <option value="">{{ \App\CPU\translate('Select')}}</option>
                                            <option value="both" {{ old('gender', $campaign->gender) === 'both' ? 'selected' : '' }}>{{ \App\CPU\translate('All')}}</option>
                                            <option value="male" {{ old('gender', $campaign->gender) === 'male' ? 'selected' : '' }}>{{ \App\CPU\translate('Male')}}</option>
                                            <option value="female" {{ old('gender', $campaign->gender) === 'female' ? 'selected' : '' }}>{{ \App\CPU\translate('Female')}}</option>
                                        </select>
                                        @error('gender') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="age_range">{{ \App\CPU\translate('Age Range')}}</label>
                                        <div class="d-flex align-items-center gap-2">
                                            <select name="age_range_min" id="age_range_min" class="form-select form-control" required>
                                                <option value="">{{ \App\CPU\translate('Min')}}</option>
                                                @for($age = 18; $age <= 65; $age++)
                                                    <option value="{{ $age }}" {{ (string)$selectedMinAge === (string)$age ? 'selected' : '' }}>{{ $age }}</option>
                                                @endfor
                                            </select>
                                            <span>-</span>
                                            <select name="age_range_max" id="age_range_max" class="form-select form-control" required>
                                                <option value="">{{ \App\CPU\translate('Max')}}</option>
                                                @for($age = 18; $age <= 65; $age++)
                                                    <option value="{{ $age }}" {{ (string)$selectedMaxAge === (string)$age ? 'selected' : '' }}>{{ $age }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <input type="hidden" name="age_range" id="age_range" value="{{ $currentAgeRange }}">
                                        @error('age_range') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="state">{{ \App\CPU\translate('State')}}</label>
                                        <select name="state" id="state" class="form-select form-control @error('state') is-invalid @enderror" required>
                                            <option value="">{{ \App\CPU\translate('Select')}}</option>
                                            <option value="Any" {{ old('state', $campaign->state) === 'Any' ? 'selected' : '' }}>Any</option>
                                            @foreach($states as $state)
                                                <option value="{{ $state->name }}" {{ old('state', $campaign->state) === $state->name ? 'selected' : '' }}>{{ $state->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('state') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="city">{{ \App\CPU\translate('City')}}</label>
                                        <select name="city" id="city" class="form-select form-control @error('city') is-invalid @enderror" required>
                                            <option value="">{{ \App\CPU\translate('Select')}}</option>
                                            <option value="Any" {{ old('city', $campaign->city) === 'Any' ? 'selected' : '' }}>Any</option>
                                        </select>
                                        @error('city') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                        <input type="hidden" id="preselected_city" value="{{ old('city', $campaign->city) }}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="guidelines">{{ \App\CPU\translate('Guidelines')}}</label>
                                        @php($selectedGuidelines = old('guidelines', $campaign->guidelines ?? []))
                                        <div id="guidelinesContainer" class="border rounded p-2" style="max-height: 220px; overflow-y: auto;">
                                            @forelse($guidelineOptions as $guideline)
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="checkbox" name="guidelines[]" value="{{ $guideline }}" id="guideline_edit_{{ $loop->index }}" {{ in_array($guideline, $selectedGuidelines) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="guideline_edit_{{ $loop->index }}">{{ $guideline }}</label>
                                                </div>
                                            @empty
                                                <p class="mb-0 text-muted">No campaign guidelines configured. Please add them from Brand Management > Campaign Guideline.</p>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="campaign-edit-section mb-4">
                            <h5 class="mb-3">{{ \App\CPU\translate('Budget & Rewards') }}</h5>
                            <div class="row g-3">
                                <div class="col-md-4"><div class="form-group"><label for="reward_per_user">{{ \App\CPU\translate('Reward Per User')}}</label><input type="number" name="reward_per_user" id="reward_per_user" class="form-control @error('reward_per_user') is-invalid @enderror" value="{{ old('reward_per_user', $campaign->reward_per_user) }}" required>@error('reward_per_user') <span class="invalid-feedback">{{ $message }}</span> @enderror</div></div>
                                <div class="col-md-4"><div class="form-group"><label for="number_of_post">{{ \App\CPU\translate('Number Of Posts')}}</label><input type="number" name="number_of_post" id="number_of_post" class="form-control @error('number_of_post') is-invalid @enderror" value="{{ old('number_of_post', $campaign->number_of_post) }}" required>@error('number_of_post') <span class="invalid-feedback">{{ $message }}</span> @enderror</div></div>
                                <div class="col-md-4"><div class="form-group"><label for="used_post">{{ \App\CPU\translate('Used Posts')}}</label><input type="number" name="used_post" id="used_post" class="form-control @error('used_post') is-invalid @enderror" value="{{ old('used_post', $campaign->used_post) }}">@error('used_post') <span class="invalid-feedback">{{ $message }}</span> @enderror</div></div>
                                <div class="col-md-4"><div class="form-group"><label for="coins">{{ \App\CPU\translate('Coins')}}</label><input type="number" name="coins" id="coins" class="form-control @error('coins') is-invalid @enderror" step="0.01" value="{{ old('coins', $campaign->coins) }}">@error('coins') <span class="invalid-feedback">{{ $message }}</span> @enderror</div></div>
                                <div class="col-md-4"><div class="form-group"><label for="final_reward_for_user">{{ \App\CPU\translate('Final Reward For User')}}</label><input type="number" name="final_reward_for_user" id="final_reward_for_user" class="form-control @error('final_reward_for_user') is-invalid @enderror" step="0.01" value="{{ old('final_reward_for_user', $campaign->final_reward_for_user) }}">@error('final_reward_for_user') <span class="invalid-feedback">{{ $message }}</span> @enderror</div></div>
                                <div class="col-md-4"><div class="form-group"><label for="feedback_coin">{{ \App\CPU\translate('Feedback Coin')}}</label><input type="number" name="feedback_coin" id="feedback_coin" class="form-control @error('feedback_coin') is-invalid @enderror" step="0.01" value="{{ old('feedback_coin', $campaign->feedback_coin) }}">@error('feedback_coin') <span class="invalid-feedback">{{ $message }}</span> @enderror</div></div>
                                <div class="col-md-4"><div class="form-group"><label for="daily_budget_cap">{{ \App\CPU\translate('Daily Budget Cap')}}</label><input type="number" name="daily_budget_cap" id="daily_budget_cap" class="form-control @error('daily_budget_cap') is-invalid @enderror" step="0.01" value="{{ old('daily_budget_cap', $campaign->daily_budget_cap) }}">@error('daily_budget_cap') <span class="invalid-feedback">{{ $message }}</span> @enderror</div></div>
                                <div class="col-md-4"><div class="form-group"><label for="total_campaign_budget">{{ \App\CPU\translate('Total Campaign Budget')}}</label><input type="number" name="total_campaign_budget" id="total_campaign_budget" class="form-control @error('total_campaign_budget') is-invalid @enderror" step="0.01" min="0" value="{{ old('total_campaign_budget', $campaign->total_campaign_budget) }}" required>@error('total_campaign_budget') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    <div id="gst_summary" class="mt-2 p-2 rounded bg-light border d-none" style="font-size:0.85rem; line-height:1.8;">
                                        <span class="text-secondary">Campaign Budget:</span> <strong id="gst_base">₹0</strong>
                                        &nbsp;&nbsp;|&nbsp;&nbsp;
                                        <span class="text-secondary">GST (<span id="gst_rate_label">{{ $campaign_gst_percentage }}</span>%):</span> <strong id="gst_amount" class="text-warning">₹0</strong>
                                        &nbsp;&nbsp;|&nbsp;&nbsp;
                                        <span class="text-secondary">Total Payable:</span> <strong id="gst_total" class="text-success">₹0</strong>
                                    </div>
                                </div></div>
                                <div class="col-md-4"><div class="form-group"><label for="campaign_user_budget">{{ \App\CPU\translate('Campaign User Budget')}} (₹)</label><input type="number" name="campaign_user_budget" id="campaign_user_budget" class="form-control @error('campaign_user_budget') is-invalid @enderror" step="0.01" value="{{ old('campaign_user_budget', $campaign->campaign_user_budget) }}">@error('campaign_user_budget') <span class="invalid-feedback">{{ $message }}</span> @enderror</div></div>
                                <div class="col-md-4"><div class="form-group"><label for="admin_percentage">{{ \App\CPU\translate('Admin Percentage')}} (%)</label><input type="number" name="admin_percentage" id="admin_percentage" class="form-control bg-light" step="0.01" value="{{ old('admin_percentage', $campaign->admin_percentage) }}" readonly></div></div>
                                <div class="col-md-4"><div class="form-group"><label for="user_percentage">{{ \App\CPU\translate('User Percentage')}} (%)</label><input type="number" name="user_percentage" id="user_percentage" class="form-control bg-light" step="0.01" value="{{ old('user_percentage', $campaign->user_percentage) }}" readonly></div></div>
                                <div class="col-md-4"><div class="form-group"><label for="sales_percentage">{{ \App\CPU\translate('Sales Percentage')}} (%)</label><input type="number" name="sales_percentage" id="sales_percentage" class="form-control bg-light" step="0.01" value="{{ old('sales_percentage', $campaign->sales_percentage) }}" readonly></div></div>
                                <div class="col-md-4"><div class="form-group"><label for="feedback_percentage">{{ \App\CPU\translate('Feedback Percentage')}} (%)</label><input type="number" name="feedback_percentage" id="feedback_percentage" class="form-control bg-light" step="0.01" value="{{ old('feedback_percentage', $campaign->feedback_percentage) }}" readonly></div></div>
                            </div>
                        </div>

                        <div class="campaign-edit-section mb-4">
                            <h5 class="mb-3">{{ \App\CPU\translate('Media') }}</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ \App\CPU\translate('Image')}}</label>
                                        <span class="ml-1 text-info">( {{\App\CPU\translate('ratio')}} 4:1 )</span>
                                        <input type="file" name="thumbnail" id="mbimageFileUploader" class="form-control" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        <img id="mbImageviewer" src="{{ $campaign->thumbnail }}" onerror='this.src="{{asset('assets/logo/logo-3.png')}}"' class="img-thumbnail mt-2" style="width:150px;height:auto;object-fit:cover;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ \App\CPU\translate('Multiple Images')}}</label>
                                        <input type="file" name="images[]" id="multipleImageUploader" class="form-control" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" multiple>
                                        <div id="imagePreview" class="mt-2 d-flex flex-wrap gap-2">
                                            @forelse($campaign->images ?? [] as $image)
                                                <img src="{{ $image }}" class="img-thumbnail" style="width:100px;height:100px;object-fit:cover;">
                                            @empty
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-3">
                            <a href="{{ route('admin.campaign.list') }}" class="btn btn-secondary">{{ \App\CPU\translate('cancel') }}</a>
                            <button type="submit" class="btn btn-primary px-4">{{ \App\CPU\translate('update')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    const categoryData = @json($categoryOptions);
    let selectedSubCategoryId = @json((string) $selectedSubCategoryId);

    function syncSubCategoryOptions() {
        const categoryId = String($('#category_id').val() || '');
        const subCategorySelect = $('#sub_category_id');
        subCategorySelect.empty();
        subCategorySelect.append('<option value="">{{ \App\CPU\translate("Select") }}</option>');

        const matchedCategory = categoryData.find(item => String(item.id) === categoryId);
        if (!matchedCategory || !matchedCategory.children) {
            return;
        }

        matchedCategory.children.forEach(function (child) {
            const selectedAttr = String(child.id) === selectedSubCategoryId ? 'selected' : '';
            subCategorySelect.append(`<option value="${child.id}" ${selectedAttr}>${child.name}</option>`);
        });
    }

    $('#category_id').on('change', function() {
        selectedSubCategoryId = '';
        syncSubCategoryOptions();
    });

    function syncAgeRangeValue() {
        const min = $('#age_range_min').val();
        const max = $('#age_range_max').val();

        if (!min || !max) {
            $('#age_range').val('');
            return;
        }

        if (parseInt(min, 10) > parseInt(max, 10)) {
            $('#age_range').val('');
            return;
        }

        $('#age_range').val(`${min}-${max}`);
    }

    $('#age_range_min, #age_range_max').on('change', syncAgeRangeValue);

    $(document).ready(function() {
        syncSubCategoryOptions();
        syncAgeRangeValue();
    });

    $('.banner_form').on('submit', function(e) {
        syncAgeRangeValue();

        const min = $('#age_range_min').val();
        const max = $('#age_range_max').val();
        if (!min || !max || parseInt(min, 10) > parseInt(max, 10)) {
            e.preventDefault();
            alert('Please select a valid age range (min age must be less than or equal to max age).');
        }
    });

    $('#mbimageFileUploader').change(function() {
        readURL(this);
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#mbImageviewer').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<script>
    $('#multipleImageUploader').change(function() {
        previewMultipleImages(this);
    });

    function previewMultipleImages(input) {
        $('#imagePreview').empty();
        if (input.files && input.files.length) {
            $.each(input.files, function(index, file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('<img>').attr('src', e.target.result).addClass('img-thumbnail').css({
                        'width': '100px',
                        'height': '100px',
                        'object-fit': 'cover'
                    }).appendTo('#imagePreview');
                }
                reader.readAsDataURL(file);
            });
        }
    }
</script>
<script>
    const cityData = @json($cityDataByState);

    function syncCityOptions() {
        const state = $('#state').val();
        const selectedCity = @json(old('city', $campaign->city));
        const citySelect = $('#city');
        citySelect.empty();
        citySelect.append(`<option value="">{{ \App\CPU\translate('Select')}}</option>`);
        citySelect.append(`<option value="Any" ${selectedCity === 'Any' ? 'selected' : ''}>Any</option>`);

        if (state && cityData[state]) {
            cityData[state].forEach(function(city) {
                const selectedAttr = city === selectedCity ? 'selected' : '';
                citySelect.append(`<option value="${city}" ${selectedAttr}>${city}</option>`);
            });
        }
    }

    $('#state').on('change', syncCityOptions);

    $(document).ready(function() {
        syncCityOptions();
    });
</script>
<script>
    // GST auto-calculation
    const GST_RATE = {{ $campaign_gst_percentage }};

    function formatINR(amount) {
        return '₹' + parseFloat(amount).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function updateGstSummary() {
        const budget = parseFloat($('#total_campaign_budget').val());
        if (!budget || budget <= 0) {
            $('#gst_summary').addClass('d-none');
            return;
        }
        const gstAmount = budget * GST_RATE / 100;
        const totalPayable = budget + gstAmount;
        $('#gst_base').text(formatINR(budget));
        $('#gst_amount').text(formatINR(gstAmount));
        $('#gst_total').text(formatINR(totalPayable));
        $('#gst_summary').removeClass('d-none');
    }

    $('#total_campaign_budget').on('input change', updateGstSummary);

    $(document).ready(function() {
        updateGstSummary();
    });
</script>
@endpush

<style>
    .campaign-edit-section {
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 16px;
        background: #fff;
    }

    .campaign-edit-section h5 {
        font-size: 1rem;
        font-weight: 600;
        color: #2d3748;
        border-bottom: 1px solid #f1f3f5;
        padding-bottom: 8px;
    }

    #imagePreview img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 6px;
    }
</style>