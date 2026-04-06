@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Campaign Add'))

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
                    <h4>{{\App\CPU\translate('create_campaign')}}</h4>
                </div>
                <div class="card-body">
                    <form action="{{route('admin.campaign.store')}}" method="post" enctype="multipart/form-data"
                        class="banner_form">
                        @csrf

                        <!-- caption: long text
                        hashtags:#abc #test
                        social_media:instagram,facebook
                        reward_per_user:1
                        total_user_required:1
                        number_of_post:1
                        start_date:2026-02-27
                        end_date:2026-03-15
                        daily_budget_cap:1
                        total_campaign_budget:1
                        gender:male
                        age_range:18-24
                        state:Madhya Pradesh
                        city:Indore
                        guidelines[]:GD 1
                        guidelines[]:GD 2 -->

                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="brand_id">{{ \App\CPU\translate('Brand')}}</label>
                                    <select name="brand_id" id="brand_id" class="form-select form-control" required>
                                        <option value="">{{ \App\CPU\translate('Select')}}</option>
                                        @foreach($sellers as $seller)
                                        <option value="{{ $seller->id }}">{{ $seller->username }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="caption">{{ \App\CPU\translate('Caption')}}</label>
                                    <textarea name="caption" id="caption" class="form-control" rows="4" required></textarea>
                                </div>
                            </div>
                            <div class="col-md-4">

                                <div class="form-group">
                                    <label for="hashtags">{{ \App\CPU\translate('Hashtags')}}</label>
                                    <input type="text" name="hashtags" id="hashtags" class="form-control" placeholder="#abc #test" required>
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
                                            {{ in_array('instagram', old('social_media', [])) ? 'selected' : '' }}>
                                            {{ \App\CPU\translate('Instagram')}}
                                        </option>

                                        <option value="facebook"
                                            {{ in_array('facebook', old('social_media', [])) ? 'selected' : '' }}>
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
                                    <input type="number" name="reward_per_user" id="reward_per_user" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-4">

                                <div class="form-group">
                                    <label for="total_user_required">{{ \App\CPU\translate('Total Users Required')}}</label>
                                    <input type="number" name="total_user_required" id="total_user_required" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-4">

                                <div class="form-group">
                                    <label for="number_of_post">{{ \App\CPU\translate('Number Of Posts')}}</label>
                                    <input type="number" name="number_of_post" id="number_of_post" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="start_date">{{ \App\CPU\translate('Start Date')}}</label>
                                    <input type="date" name="start_date" id="start_date" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-4">

                                <div class="form-group">
                                    <label for="end_date">{{ \App\CPU\translate('End Date')}}</label>
                                    <input type="date" name="end_date" id="end_date" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-4">

                                <div class="form-group">
                                    <label for="daily_budget_cap">{{ \App\CPU\translate('Daily Budget Cap')}}</label>
                                    <input type="number" name="daily_budget_cap" id="daily_budget_cap" class="form-control" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-4">

                                <div class="form-group">
                                    <label for="total_campaign_budget">{{ \App\CPU\translate('Total Campaign Budget')}}</label>
                                    <input type="number" name="total_campaign_budget" id="total_campaign_budget" class="form-control" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-4">

                                <div class="form-group">
                                    <label for="gender">{{ \App\CPU\translate('Gender')}}</label>
                                    <select name="gender" id="gender" class="form-select form-control" required>
                                        <option value="">{{ \App\CPU\translate('Select')}}</option>
                                        <option value="male">{{ \App\CPU\translate('Male')}}</option>
                                        <option value="female">{{ \App\CPU\translate('Female')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="age_range">{{ \App\CPU\translate('Age Range')}}</label>
                                    <select name="age_range" id="age_range" class="form-select form-control" required>
                                        <option value="">{{ \App\CPU\translate('Select')}}</option>
                                        <option value="18-24">18-24</option>
                                        <option value="25-34">25-34</option>
                                        <option value="35-44">35-44</option>
                                        <option value="45-54">45-54</option>
                                        <option value="55-64">55-64</option>
                                        <option value="65+">65+</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="state">{{ \App\CPU\translate('State')}}</label>
                                    <select name="state" id="state" class="form-select form-control" required>
                                        <option value="">{{ \App\CPU\translate('Select')}}</option>
                                        <option value="Andhra Pradesh">Andhra Pradesh</option>
                                        <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                                        <option value="Assam">Assam</option>
                                        <option value="Bihar">Bihar</option>
                                        <option value="Chhattisgarh">Chhattisgarh</option>
                                        <option value="Goa">Goa</option>
                                        <option value="Gujarat">Gujarat</option>
                                        <option value="Haryana">Haryana</option>
                                        <option value="Himachal Pradesh">Himachal Pradesh</option>
                                        <option value="Jharkhand">Jharkhand</option>
                                        <option value="Karnataka">Karnataka</option>
                                        <option value="Kerala">Kerala</option>
                                        <option value="Madhya Pradesh">Madhya Pradesh</option>
                                        <option value="Maharashtra">Maharashtra</option>
                                        <option value="Manipur">Manipur</option>
                                        <option value="Meghalaya">Meghalaya</option>
                                        <option value="Mizoram">Mizoram</option>
                                        <option value="Nagaland">Nagaland</option>
                                        <option value="Odisha">Odisha</option>
                                        <option value="Punjab">Punjab</option>
                                        <option value="Rajasthan">Rajasthan</option>
                                        <option value="Sikkim">Sikkim</option>
                                        <option value="Tamil Nadu">Tamil Nadu</option>
                                        <option value="Telangana">Telangana</option>
                                        <option value="Tripura">Tripura</option>
                                        <option value="Uttar Pradesh">Uttar Pradesh</option>
                                        <option value="Uttarakhand">Uttarakhand</option>
                                        <option value="West Bengal">West Bengal</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="city">{{ \App\CPU\translate('City')}}</label>
                                    <div id="city">
                                        <input type="text" name="city" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="guidelines">{{ \App\CPU\translate('Guidelines')}}</label>
                                    <div id="guidelinesContainer">
                                        <div class="guideline-item d-flex gap-2 mb-2">
                                            <input type="text" name="guidelines[]" class="form-control" placeholder="GD 1">
                                            <button type="button" class="btn btn-danger btn-sm removeGuideline" style="display:none;">Remove</button>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-secondary btn-sm mt-2" id="addGuideline">{{ \App\CPU\translate('Add Guidelines')}}</button>
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
                                        src="{{asset('assets/logo/logo-3.png')}}"
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

                                    <div id="imagePreview" class="mt-2 d-flex flex-wrap gap-2"></div>

                                </div>
                            </div>


                            <div class="col-md-12 d-flex justify-content-end gap-3">


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
    $(document).ready(function() {
        $('#addGuideline').click(function() {
            let newItem = `
                    <div class="guideline-item d-flex gap-2 mb-2">
                        <input type="text" name="guidelines[]" class="form-control" placeholder="GD">
                        <button type="button" class="btn btn-danger btn-sm removeGuideline">Remove</button>
                    </div>
                `;
            $('#guidelinesContainer').append(newItem);
            updateRemoveButtons();
        });

        $(document).on('click', '.removeGuideline', function(e) {
            e.preventDefault();
            $(this).closest('.guideline-item').remove();
            updateRemoveButtons();
        });

        function updateRemoveButtons() {
            let count = $('#guidelinesContainer .guideline-item').length;
            $('#guidelinesContainer .removeGuideline').toggle(count > 1);
        }

        updateRemoveButtons();
    });
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
                    $('<img>')
                        .attr('src', e.target.result)
                        .addClass('img-thumbnail')
                        .css({
                            'width': '100px',
                            'height': '100px',
                            'object-fit': 'cover'
                        })
                        .appendTo('#imagePreview');
                }
                reader.readAsDataURL(file);
            });
        }
    }
</script>
<!-- Comment to track state selection for city dropdown -->
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
                        ${cityData[state].map(city => `<option value="${city}">${city}</option>`).join('')}
                    </select>
                `);
        }
    });
</script>
@endpush

<style>
    #imagePreview img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 6px;
    }
</style>