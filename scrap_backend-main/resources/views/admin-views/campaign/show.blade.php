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
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4>{{\App\CPU\translate('view_campaign')}}</h4>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-lg-4 col-md-4">
                            <img class="ratio-4:1 w-100" src="{{ $campaign->thumbnail }}" onerror='this.src="{{asset('assets/logo/logo-3.png')}}"' alt="Campaign"/>
                        </div>
                        @foreach($campaign->images ?? [] as $image)
                            <div class="col-lg-4 col-md-4">
                                <img src="{{ $image }}" class="img-thumbnail w-100">
                            </div>
                        @endforeach
                        <div class="col-lg-12"></div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ \App\CPU\translate('Caption')}}</label>
                                <p class="form-control-plaintext">{{ $campaign->descriptions }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ \App\CPU\translate('Hashtags')}}</label>
                                <p class="form-control-plaintext">{{ $campaign->tags }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ \App\CPU\translate('Reward Per User')}}</label>
                                <p class="form-control-plaintext">{{ $campaign->reward_per_user }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ \App\CPU\translate('Start Date')}}</label>
                                <p class="form-control-plaintext">{{ $campaign->start_date }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ \App\CPU\translate('End Date')}}</label>
                                <p class="form-control-plaintext">{{ $campaign->end_date }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ \App\CPU\translate('Gender')}}</label>
                                <p class="form-control-plaintext">{{ ucfirst($campaign->gender) }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ \App\CPU\translate('Age Range')}}</label>
                                <p class="form-control-plaintext">{{ $campaign->age_range }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ \App\CPU\translate('State')}}</label>
                                <p class="form-control-plaintext">{{ $campaign->state }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ \App\CPU\translate('City')}}</label>
                                <p class="form-control-plaintext">{{ $campaign->city }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{ \App\CPU\translate('Guidelines')}}</label>
                                <p class="form-control-plaintext">
                                    @foreach($campaign->guidelines ?? [] as $guideline)
                                        <span class="badge bg-info me-1">{{ $guideline }}</span>
                                    @endforeach
                                </p>
                            </div>
                        </div>
                        <div class="col-md-12 d-flex justify-content-end gap-3">
                            <a href="{{ route('admin.campaign.list') }}" class="btn btn-secondary">{{ \App\CPU\translate('back') }}</a>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <span>{!! in_array('instagram', explode(',', $campaign->share_on)) ? '<i class="fa fa-instagram"></i>': '' !!}
                    {!! in_array('facebook', explode(',', $campaign->share_on)) ? '<i class="fa fa-facebook"></i>':'' !!}</span>
                    <i class="fa fa-facebook"></i>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control change-status" data-id="{{ $campaign->id }}">
                            @php($statusLists=['pending','active','inactive','accepted','rejected','completed','paused','stopped','violated'])
                            @foreach($statusLists as $status)
                                <option value="{{$status}}" {{ $status == $campaign->status ? 'selected' : '' }}>{{ucwords($status)}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Total Shared: {{ $campaign->campaign_transactions->count( ) }}</label>
                    </div>
                    <div class="form-group">
                        <label for="">Post Type: {{ $campaign->post_type }}</label>
                    </div>
                    <div class="form-group">
                        <label for="">Total user required: {{ $campaign->total_user_required }}</label>
                    </div>
                    <div class="form-group">
                        <label for="">Number of post: {{ $campaign->number_of_post }}</label>
                    </div>
                    <div class="form-group">
                        <label for="">Daily budget cap: {{ $campaign->daily_budget_cap }}</label>
                    </div>
                    <div class="form-group">
                        <label for="">Total Budget: {{ $campaign->total_campaign_budget }}</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
    <script>
        $('#mbimageFileUploader').change(function () {
            readURL(this);
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
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
        $('#multipleImageUploader').change(function () {
            previewMultipleImages(this);
        });

        function previewMultipleImages(input) {
            $('#imagePreview').empty();
            if (input.files && input.files.length) {
                $.each(input.files, function (index, file) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('<img>').attr('src', e.target.result).addClass('img-thumbnail').css({'width': '100px', 'height': '100px', 'object-fit': 'cover'}).appendTo('#imagePreview');
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

        $(document).on('change','.change-status', function() {
            var id = $(this).attr("data-id")
            var status = $(this).val()
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });

            $.ajax({
                url: "{{route('admin.campaign.status')}}",
                method: 'POST',
                data: {
                    id: id,
                    status: status
                },
                success: function (response) {
                    if (response.status) {
                        swal.fire('', '{{ \App\CPU\translate('Status updated successfully!')}}', 'success');
                    } else {
                        swal.fire('', '{{ \App\CPU\translate('Something went wrong!')}}', 'error');
                    }
                }
            });
        })

    </script>
@endpush