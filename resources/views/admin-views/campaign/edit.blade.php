@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Campaign Edit'))

@push('css_or_js')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
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

                                    <select name="social_media[]"
                                        id="social_media"
                                        class="form-control @error('social_media') is-invalid @enderror"
                                        multiple
                                        size="2"
                                        required>

                                        <option value="instagram"
                                            {{ in_array('instagram', old('social_media', explode(',', $campaign->share_on) ?? [])) ? 'selected' : '' }}>
                                            {{ \App\CPU\translate('Instagram')}}
                                        </option>

                                        <option value="facebook"
                                            {{ in_array('facebook', old('social_media', explode(',', $campaign->share_on) ?? [])) ? 'selected' : '' }}>
                                            {{ \App\CPU\translate('Facebook')}}
                                        </option>

                                    </select>

                                    @error('social_media')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="reward_per_user">{{ \App\CPU\translate('Reward Per User')}}</label>
                                    <input type="number" name="reward_per_user" id="reward_per_user" class="form-control @error('reward_per_user') is-invalid @enderror" value="{{ old('reward_per_user', $campaign->reward_per_user) }}" required>
                                    @error('reward_per_user') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="total_user_required">{{ \App\CPU\translate('Total Users Required')}}</label>
                                    <input type="number" name="total_user_required" id="total_user_required" class="form-control @error('total_user_required') is-invalid @enderror" value="{{ old('total_user_required', $campaign->total_user_required) }}" required>
                                    @error('total_user_required') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="number_of_post">{{ \App\CPU\translate('Number Of Posts')}}</label>
                                    <input type="number" name="number_of_post" id="number_of_post" class="form-control @error('number_of_post') is-invalid @enderror" value="{{ old('number_of_post', $campaign->number_of_post) }}" required>
                                    @error('number_of_post') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
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
                                    <label for="daily_budget_cap">{{ \App\CPU\translate('Daily Budget Cap')}}</label>
                                    <input type="number" name="daily_budget_cap" id="daily_budget_cap" class="form-control @error('daily_budget_cap') is-invalid @enderror" step="0.01" value="{{ old('daily_budget_cap', $campaign->daily_budget_cap) }}" required>
                                    @error('daily_budget_cap') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="total_campaign_budget">{{ \App\CPU\translate('Total Campaign Budget')}}</label>
                                    <input type="number" name="total_campaign_budget" id="total_campaign_budget" class="form-control @error('total_campaign_budget') is-invalid @enderror" step="0.01" value="{{ old('total_campaign_budget', $campaign->total_campaign_budget) }}" required>
                                    @error('total_campaign_budget') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="gender">{{ \App\CPU\translate('Gender')}}</label>
                                    <select name="gender" id="gender" class="form-select form-control @error('gender') is-invalid @enderror" required>
                                        <option value="">{{ \App\CPU\translate('Select')}}</option>
                                        <option value="male" {{ old('gender', $campaign->gender) === 'male' ? 'selected' : '' }}>{{ \App\CPU\translate('Male')}}</option>
                                        <option value="female" {{ old('gender', $campaign->gender) === 'female' ? 'selected' : '' }}>{{ \App\CPU\translate('Female')}}</option>
                                    </select>
                                    @error('gender') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="age_range">{{ \App\CPU\translate('Age Range')}}</label>
                                    @php($currentAgeRange = old('age_range', $campaign->age_range))
                                    @php($ageParts = explode('-', (string) $currentAgeRange))
                                    @php($selectedMinAge = isset($ageParts[0]) ? (int) trim($ageParts[0]) : '')
                                    @php($selectedMaxAge = isset($ageParts[1]) ? (int) trim($ageParts[1]) : '')
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
                                        <option value="Andhra Pradesh" {{ old('state', $campaign->state) === 'Andhra Pradesh' ? 'selected' : '' }}>Andhra Pradesh</option>
                                        <option value="Arunachal Pradesh" {{ old('state', $campaign->state) === 'Arunachal Pradesh' ? 'selected' : '' }}>Arunachal Pradesh</option>
                                        <option value="Assam" {{ old('state', $campaign->state) === 'Assam' ? 'selected' : '' }}>Assam</option>
                                        <option value="Bihar" {{ old('state', $campaign->state) === 'Bihar' ? 'selected' : '' }}>Bihar</option>
                                        <option value="Chhattisgarh" {{ old('state', $campaign->state) === 'Chhattisgarh' ? 'selected' : '' }}>Chhattisgarh</option>
                                        <option value="Goa" {{ old('state', $campaign->state) === 'Goa' ? 'selected' : '' }}>Goa</option>
                                        <option value="Gujarat" {{ old('state', $campaign->state) === 'Gujarat' ? 'selected' : '' }}>Gujarat</option>
                                        <option value="Haryana" {{ old('state', $campaign->state) === 'Haryana' ? 'selected' : '' }}>Haryana</option>
                                        <option value="Himachal Pradesh" {{ old('state', $campaign->state) === 'Himachal Pradesh' ? 'selected' : '' }}>Himachal Pradesh</option>
                                        <option value="Jharkhand" {{ old('state', $campaign->state) === 'Jharkhand' ? 'selected' : '' }}>Jharkhand</option>
                                        <option value="Karnataka" {{ old('state', $campaign->state) === 'Karnataka' ? 'selected' : '' }}>Karnataka</option>
                                        <option value="Kerala" {{ old('state', $campaign->state) === 'Kerala' ? 'selected' : '' }}>Kerala</option>
                                        <option value="Madhya Pradesh" {{ old('state', $campaign->state) === 'Madhya Pradesh' ? 'selected' : '' }}>Madhya Pradesh</option>
                                        <option value="Maharashtra" {{ old('state', $campaign->state) === 'Maharashtra' ? 'selected' : '' }}>Maharashtra</option>
                                        <option value="Manipur" {{ old('state', $campaign->state) === 'Manipur' ? 'selected' : '' }}>Manipur</option>
                                        <option value="Meghalaya" {{ old('state', $campaign->state) === 'Meghalaya' ? 'selected' : '' }}>Meghalaya</option>
                                        <option value="Mizoram" {{ old('state', $campaign->state) === 'Mizoram' ? 'selected' : '' }}>Mizoram</option>
                                        <option value="Nagaland" {{ old('state', $campaign->state) === 'Nagaland' ? 'selected' : '' }}>Nagaland</option>
                                        <option value="Odisha" {{ old('state', $campaign->state) === 'Odisha' ? 'selected' : '' }}>Odisha</option>
                                        <option value="Punjab" {{ old('state', $campaign->state) === 'Punjab' ? 'selected' : '' }}>Punjab</option>
                                        <option value="Rajasthan" {{ old('state', $campaign->state) === 'Rajasthan' ? 'selected' : '' }}>Rajasthan</option>
                                        <option value="Sikkim" {{ old('state', $campaign->state) === 'Sikkim' ? 'selected' : '' }}>Sikkim</option>
                                        <option value="Tamil Nadu" {{ old('state', $campaign->state) === 'Tamil Nadu' ? 'selected' : '' }}>Tamil Nadu</option>
                                        <option value="Telangana" {{ old('state', $campaign->state) === 'Telangana' ? 'selected' : '' }}>Telangana</option>
                                        <option value="Tripura" {{ old('state', $campaign->state) === 'Tripura' ? 'selected' : '' }}>Tripura</option>
                                        <option value="Uttar Pradesh" {{ old('state', $campaign->state) === 'Uttar Pradesh' ? 'selected' : '' }}>Uttar Pradesh</option>
                                        <option value="Uttarakhand" {{ old('state', $campaign->state) === 'Uttarakhand' ? 'selected' : '' }}>Uttarakhand</option>
                                        <option value="West Bengal" {{ old('state', $campaign->state) === 'West Bengal' ? 'selected' : '' }}>West Bengal</option>
                                    </select>
                                    @error('state') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <!-- <div class="col-md-4">
                                <div class="form-group">
                                    <label for="city">{{ \App\CPU\translate('City')}}</label>
                                    <div id="city">
                                        <input type="text" name="city" class=" form-control @error('city') is-invalid @enderror" value="{{ old('city', $campaign->city) }}" required>
                                    </div>
                                    @error('city') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div> -->
                           <div class="col-md-4">
    <div class="form-group">
        <label for="city">{{ \App\CPU\translate('City')}}</label>

        <select name="city" class="form-select form-control @error('city') is-invalid @enderror" required>
            <option value="">Select City</option>

            <option value="Delhi" {{ old('city', $campaign->city) == 'Delhi' ? 'selected' : '' }}>Delhi</option>
            <option value="Mumbai" {{ old('city', $campaign->city) == 'Mumbai' ? 'selected' : '' }}>Mumbai</option>
            <option value="Indore" {{ old('city', $campaign->city) == 'Indore' ? 'selected' : '' }}>Indore</option>
            <option value="Bhopal" {{ old('city', $campaign->city) == 'Bhopal' ? 'selected' : '' }}>Bhopal</option>
            <option value="Jaipur" {{ old('city', $campaign->city) == 'Jaipur' ? 'selected' : '' }}>Jaipur</option>

        </select>

        @error('city') <span class="invalid-feedback">{{ $message }}</span> @enderror
    </div>
</div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="guidelines">{{ \App\CPU\translate('Guidelines')}}</label>
                                    @php($selectedGuidelines = old('guidelines', $campaign->guidelines ?? []))
                                    <div id="guidelinesContainer" class="border rounded p-2" style="max-height: 220px; overflow-y: auto;">
                                        @forelse($guidelineOptions as $guideline)
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="guidelines[]" value="{{ $guideline }}" id="guideline_edit_{{ $loop->index }}" {{ in_array($guideline, $selectedGuidelines) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="guideline_edit_{{ $loop->index }}">
                                                    {{ $guideline }}
                                                </label>
                                            </div>
                                        @empty
                                            <p class="mb-0 text-muted">No campaign guidelines configured. Please add them from Brand Management > Campaign Guideline.</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">

                                    <label>{{ \App\CPU\translate('Image')}}</label>
                                    <span class="ml-1 text-info">( {{\App\CPU\translate('ratio')}} 4:1 )</span>

                                    <input type="file"
                                        name="thumbnail"
                                        id="mbimageFileUploader"
                                        class="form-control"
                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">

                                    <img
                                        id="mbImageviewer"
                                        src="{{ $campaign->thumbnail }}"
                                        onerror='this.src="{{asset('assets/logo/logo-3.png')}}"'
                                        class="img-thumbnail mt-2"
                                        style="width:150px;height:auto;object-fit:cover;">
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group">

                                    <label>{{ \App\CPU\translate('Multiple Images')}}</label>

                                    <input type="file"
                                        name="images[]"
                                        id="multipleImageUploader"
                                        class="form-control"
                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*"
                                        multiple>

                                    <div id="imagePreview" class="mt-2 d-flex flex-wrap gap-2">

                                        @forelse($campaign->images ?? [] as $image)
                                        <img src="{{ $image }}"
                                            class="img-thumbnail"
                                            style="width:100px;height:100px;object-fit:cover;">
                                        @empty
                                        @endforelse

                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 d-flex justify-content-end gap-3">
                                <a href="{{ route('admin.campaign.list') }}" class="btn btn-secondary">{{ \App\CPU\translate('cancel') }}</a>
                                <button type="submit" class="btn btn-primary px-4">{{ \App\CPU\translate('update')}}</button>
                            </div>
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
    const cityData = {
        'Andhra Pradesh': ['Hyderabad', 'Visakhapatnam', 'Vijayawada', 'Nellore'],
        'Arunachal Pradesh': ['Itanagar', 'Naharlagun', 'Papumpare'],
        'Assam': ['Guwahati', 'Silchar', 'Dibruganj'],
        'Bihar': ['Patna', 'Gaya', 'Bhagalpur', 'Muzaffarpur'],
        'Chhattisgarh': ['Raipur', 'Bilaspur', 'Durg'],
        'Goa': ['Panaji', 'Margao', 'Vasco da Gama'],
        'Gujarat': ['Ahmedabad', 'Surat', 'Vadodara', 'Rajkot'],
        'Haryana': ['Faridabad', 'Gurgaon', 'Hisar', 'Rohtak'],
        'Himachal Pradesh': ['Shimla', 'Solan', 'Mandi'],
        'Jharkhand': ['Ranchi', 'Dhanbad', 'Giridih'],
        'Karnataka': ['Bangalore', 'Mysore', 'Mangalore', 'Belgaum'],
        'Kerala': ['Kochi', 'Thiruvananthapuram', 'Kozhikode'],
        'Madhya Pradesh': ['Indore', 'Bhopal', 'Gwalior', 'Jabalpur'],
        'Maharashtra': ['Mumbai', 'Pune', 'Nagpur', 'Aurangabad'],
        'Manipur': ['Imphal', 'Bishnupur'],
        'Meghalaya': ['Shillong', 'Tura'],
        'Mizoram': ['Aizawl', 'Lunglei'],
        'Nagaland': ['Kohima', 'Dimapur'],
        'Odisha': ['Bhubaneswar', 'Cuttack', 'Rourkela'],
        'Punjab': ['Chandigarh', 'Amritsar', 'Ludhiana', 'Jalandhar'],
        'Rajasthan': ['Jaipur', 'Jodhpur', 'Udaipur', 'Kota'],
        'Sikkim': ['Gangtok', 'Pelling'],
        'Tamil Nadu': ['Chennai', 'Coimbatore', 'Madurai', 'Salem'],
        'Telangana': ['Hyderabad', 'Warangal', 'Nizamabad'],
        'Tripura': ['Agartala', 'Udaipur'],
        'Uttar Pradesh': ['Lucknow', 'Kanpur', 'Varanasi', 'Agra'],
        'Uttarakhand': ['Dehradun', 'Haridwar', 'Nainital'],
        'West Bengal': ['Kolkata', 'Darjeeling', 'Asansol']
    };

    $('#state').change(function() {
        let state = $(this).val();
        let citySelect = $('#city');

        if (state && cityData[state]) {
            citySelect.replaceWith(`
                    <select name="city" id="city" class="form-control" required>
                        <option value="">{{ \App\CPU\translate('Select')}}</option>
                        ${cityData[state].map(city => `<option value="${city}" ${city === '{{ old('city', $campaign->city) }}' ? 'selected' : ''}>${city}</option>`).join('')}
                    </select>
                `);
        }
    });

    $(document).ready(function() {
        $('#state').trigger('change');
    });
</script>
@endpush

<style>
    #imagePreview img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 6px;
    }
</style>