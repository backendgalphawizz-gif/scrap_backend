@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('User Add'))
@push('css_or_js')
    <link href="{{ asset('public/assets/back-end') }}/css/select2.min.css" rel="stylesheet" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-3 customBtnDiv backbtndiv">
            <a href="{{ route('admin.customer.list') }}"><button class="btn btn--primary px-4"> <i class="tio-chevron-left"></i>Back</button></a> <!-- customBtnDiv-->
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{ asset('/public/assets/back-end/img/add-new-employee.png') }}" alt="">
                {{ \App\CPU\translate('Add_New_User') }}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Content Row -->
        <div class="row">
            <div class="col-md-12">
                <form action="{{ route('admin.customer.store') }}" method="post" enctype="multipart/form-data"style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <h5 class="mb-0 page-header-title d-flex align-items-center gap-2 border-bottom pb-3 mb-3">
                                <i class="tio-user"></i>
                                {{ \App\CPU\translate('General_Information') }}
                            </h5>
                            <div class="row">
                                <div class="col-md-6">
                                <div class="form-group">
                                        <label for="name"
                                            class="title-color">{{ \App\CPU\translate('First Name') }}</label>
                                        <input type="text" name="f_name" class="form-control" id="f_name"
                                            placeholder="{{ \App\CPU\translate('first_name')}}"
                                            value="{{ old('f_name') }}" required>
                                    </div>


                                    <!-- <div class="d-none form-group">
                                        <label for="name" class="title-color">{{ \App\CPU\translate('Age') }}</label>
                                        <input type="number" name="age" value="{{ old('age') }}"
                                            class="form-control" id="age"
                                            placeholder="{{ \App\CPU\translate('Ex') }} : 20"
                                            maxlength="3" required>
                                    </div> -->
                                    <!-- <div class="form-group">
                                        <label for="name" class="title-color">{{ \App\CPU\translate('City') }}</label>
                                        <input type="text" name="city" value="{{ old('city') }}"
                                            class="form-control" id="city"
                                            placeholder="{{ \App\CPU\translate('Ex') }} : Mumbai" required>
                                    </div> -->
                                    <div class="form-group">
                                            <label for="name"
                                                class="title-color">{{ \App\CPU\translate('Email') }}</label>
                                            <input type="email" name="email" value="{{ old('email') }}"
                                                class="form-control" id="email"
                                                placeholder="{{ \App\CPU\translate('Ex') }} : ex@gmail.com" required>
                                    </div>
                                       <div class="form-group">
                                        <label for="name"
                                            class="title-color">{{ \App\CPU\translate('user_image') }}</label>
                                        <span class="text-info">( {{ \App\CPU\translate('ratio') }} 1:1 )</span>
                                        <div class="form-group">
                                            <div class="custom-file text-left">
                                                <input type="file" name="image" id="customFileUpload"
                                                    class="custom-file-input"
                                                    accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                                <label class="custom-file-label"
                                                    for="customFileUpload">{{ \App\CPU\translate('choose') }}
                                                    {{ \App\CPU\translate('file') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <img class="upload-img-view" id="viewer"
                                            src="{{ asset('public\assets\back-end\img\400x400\img2.jpg') }}"
                                            alt="Product thumbnail" />
                                    </div>
                                    <!-- <div class="d-none form-group">
                                        <label for="name"
                                            class="title-color">{{ \App\CPU\translate('Address') }}</label>
                                        <input type="text" name="address" value="{{ old('address') }}"
                                            class="form-control" id="address"
                                            placeholder="{{ \App\CPU\translate('Ex') }} : 123, Green Park Avenue, Near City Center Mall, Sector 12, Gurugram, Haryana, 122018, India"
                                            required>
                                    </div> -->

                                    <!-- <div class="d-none form-group">
                                        <label for="gender"
                                            class="title-color">{{ \App\CPU\translate('Gender') }}</label>
                                        <select name="gender" class="form-control" id="gender" required>
                                            <option value="" disabled selected>
                                                {{ \App\CPU\translate('Select Gender') }}</option>
                                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>
                                                {{ \App\CPU\translate('Male') }}</option>
                                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>
                                                {{ \App\CPU\translate('Female') }}</option>
                                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>
                                                {{ \App\CPU\translate('Other') }}</option>
                                        </select>
                                    </div> -->


                                    <!-- <div class="d-none form-group">
                                        <label for="name"
                                            class="title-color">{{ \App\CPU\translate('Zipcode') }}</label>
                                        <select class="form-control" name="zipcode">
                                            <option value="0" selected disabled>
                                                ---{{ \App\CPU\translate('select') }}---
                                            </option>
                                            @if (isset($zipcode))
                                                @foreach ($zipcode as $key => $value)
                                                    <option value="{{ $value->zipcode }}">{{ $value->zipcode }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div> -->
                                </div>
                                <div class=" col-lg-6">
                                    
                                    <div class="  form-group">
                                        <label for="name"
                                            class="title-color">{{ \App\CPU\translate('Last Name') }}</label>
                                        <input type="text" name="l_name" class="form-control" id="l_name"
                                            placeholder="{{ \App\CPU\translate('last_name')}}"
                                            value="{{ old('l_name') }}" required>
                                    </div>
                                   
                                        
                                    
                                   
                                    <div class="form-group">
                                        <label for="name" class="title-color">{{ \App\CPU\translate('Phone') }}</label>
                                        <input type="number" name="phone" value="{{ old('phone') }}"
                                            class="form-control" id="phone"
                                            placeholder="{{ \App\CPU\translate('Ex') }} : +88017********"
                                            oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength); this.setCustomValidity(this.value.length < 10 ? 'Minimum length is 10 digits.' : ''); this.value = this.value.replace(/[^0-9]/g, '');"
                                            maxlength="10" required>
                                    </div>
                                 
                                   
                                </div>
                                
                            </div>
                             <div class="d-flex justify-content-end gap-3">
                                <button type="reset" id="reset"
                                    class="btn btn-secondary px-4">{{ \App\CPU\translate('reset') }}</button>
                                <button type="submit"
                                    class="btn btn--primary px-4">{{ \App\CPU\translate('submit') }}</button>
                            </div>
                        </div>
                    </div>
<!-- 
                    <div class="d-none card mt-3">
                        <div class="card-body">
                            <h5 class="mb-0 page-header-title d-flex align-items-center gap-2 border-bottom pb-3 mb-3">
                                <i class="tio-user"></i>
                                {{ \App\CPU\translate('Personal Information') }}
                            </h5>
                            <div class="row">
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="name"
                                            class="title-color">{{ \App\CPU\translate('Email') }}</label>
                                        <input type="email" name="email" value="{{ old('email') }}"
                                            class="form-control" id="email"
                                            placeholder="{{ \App\CPU\translate('Ex') }} : ex@gmail.com" required>
                                    </div>
                                </div>
                                <div class=" col-md-4">
                                    <div class="form-group">
                                        <label for="password"
                                            class="title-color">{{ \App\CPU\translate('password') }}</label>
                                        <input type="text" name="password" class="form-control" id="password"
                                            placeholder="{{ \App\CPU\translate('Password') }}" required>
                                    </div>
                                </div>
                                <div class="d-none col-md-4">
                                    <div class="form-group">
                                        <label for="confirm_password"
                                            class="title-color">{{ \App\CPU\translate('confirm_password') }}</label>
                                        <input type="text" name="confirm_password" class="form-control"
                                            id="confirm_password"
                                            placeholder="{{ \App\CPU\translate('Confirm Password') }}" required>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>         -->

                    <!-- <div class=" d-none card mt-3">
                        <div class="card-body">
                            <h5 class="mb-0 page-header-title d-flex align-items-center gap-2 border-bottom pb-3 mb-3">
                                <i class="tio-user"></i>
                                {{ \App\CPU\translate('Address Information') }}
                            </h5>
                            <div class="row">


                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="shop_name"
                                            class="title-color">{{ \App\CPU\translate('shop_name') }}</label>
                                        <input type="shop_name" name="shop_name" value="{{ old('shop_name') }}"
                                            class="form-control" id="shop_name"
                                            placeholder="{{ \App\CPU\translate('shop name') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="shop_address"
                                            class="title-color">{{ \App\CPU\translate('shop_address') }}</label>
                                        <input type="shop_address" name="shop_address" value="{{ old('shop_address') }}"
                                            class="form-control" id="shop_address"
                                            placeholder="{{ \App\CPU\translate('shop address') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="shop_lat"
                                            class="title-color">{{ \App\CPU\translate('shop_latitude') }}</label>
                                        <input type="shop_lat" name="shop_lat" value="{{ old('shop_lat') }}"
                                            class="form-control" id="shop_lat"
                                            placeholder="{{ \App\CPU\translate('shop lat') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="shop_lng"
                                            class="title-color">{{ \App\CPU\translate('shop_longitude') }}</label>
                                        <input type="shop_lng" name="shop_lng" value="{{ old('shop_lng') }}"
                                            class="form-control" id="shop_lng"
                                            placeholder="{{ \App\CPU\translate('shop lng') }}" required>
                                    </div>
                                </div>
                            
                                <input type="hidden" name="country" value="India">
                                <div class="col-md-4">
                                    <div class=" form-group">
                                        <label for="state" class="title-color d-flex gap-1 align-items-center">{{\App\CPU\translate('State')}}</label>
                                        <select name="state" class="form-control" id="state">
                                            @foreach ($states as $key=>$value )
                                                <option value="{{$value['id']}}" data-id="{{$value['id']}}">{{$value['name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class=" form-group">
                                        <label for="city" class="title-color d-flex gap-1 align-items-center">{{\App\CPU\translate('City')}}</label>
                                        <select name="city" id="city" class="form-control">
                                            <option value="">-- Select City --</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class=" form-group">
                                        <label for="area" class="title-color">{{\App\CPU\translate('Area')}}</label>
                                        <select name="area" id="area" class="form-control">
                                            <option value="">-- Select Area --</option>
                                        </select>
                                    </div>
                                </div>

                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="name"
                                            class="title-color">{{ \App\CPU\translate('Email') }}</label>
                                        <input type="email" name="email" value="{{ old('email') }}"
                                            class="form-control" id="email"
                                            placeholder="{{ \App\CPU\translate('Ex') }} : ex@gmail.com" required>
                                    </div>
                                </div>
                                <div class="col-md-4 d-none">
                                    <div class="form-group">
                                        <label for="password"
                                            class="title-color">{{ \App\CPU\translate('password') }}</label>
                                        <input type="text" name="password" class="form-control" id="password"
                                            placeholder="{{ \App\CPU\translate('Password') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-4 d-none">
                                    <div class="form-group">
                                        <label for="confirm_password"
                                            class="title-color">{{ \App\CPU\translate('confirm_password') }}</label>
                                        <input type="text" name="confirm_password" class="form-control"
                                            id="confirm_password"
                                            placeholder="{{ \App\CPU\translate('Confirm Password') }}" required>
                                    </div>
                                </div>
                            </div>

                           
                        </div>
                    </div> -->
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('public/assets/back-end') }}/js/select2.min.js"></script>
    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileUpload").change(function() {
            readURL(this);
        });

        $(".js-example-theme-single").select2({
            theme: "classic"
        });

        $(".js-example-responsive").select2({
            width: 'resolve'
        });
    </script>

    <script src="{{ asset('public/assets/back-end/js/spartan-multi-image-picker.js') }}"></script>
    <script type="text/javascript">
        $(function() {
            $("#coba").spartanMultiImagePicker({
                fieldName: 'identity_image[]',
                maxCount: 5,
                rowHeight: 'auto',
                groupClassName: 'col-6 col-lg-4',
                maxFileSize: '',
                placeholderImage: {
                    image: '{{ asset('public/assets/back-end/img/400x400/img2.jpg') }}',
                    width: '100%'
                },
                dropFileLabel: "Drop Here",
                onAddRow: function(index, file) {

                },
                onRenderedPreview: function(index) {

                },
                onRemoveRow: function(index) {

                },
                onExtensionErr: function(index, file) {
                    toastr.error('Please only input png or jpg type file', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                onSizeErr: function(index, file) {
                    toastr.error('File size too big', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        });
    </script>
    <script> 
      
    $(document).ready(function () {
    function loadCities(stateID, selectedCity = null) {
        if (stateID) {
            $.ajax({
                type: "POST",
                url: "{{ route('admin.area.getCityState') }}",
                data: {
                    state_id: stateID,
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    $('#city').empty().append('<option value="">-- Select City --</option>');
                    
                    $.each(response, function (key, city) {
                        let selected = (selectedCity && selectedCity == city.id) ? 'selected' : '';
                        $('#city').append('<option value="'+ city.id +'" data-id="'+ city.id +'" '+selected+'>'+ city.name +'</option>');
                    });
                }
            });
        } else {
            $('#city').empty().append('<option value="">-- Select City --</option>');
        }
    }

    function loadAreas(cityId, selectedArea = null) {
        if (cityId) {
            $.ajax({
                type: "POST",
                url: "{{ route('admin.area.getAreaCity') }}",
                data: {
                    city_id: cityId,
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    $('#area').empty().append('<option value="">-- Select Area --</option>');
                    
                    $.each(response, function (key, area) {
                        let selected = (selectedArea && selectedArea == area.id) ? 'selected' : '';
                        $('#area').append('<option value="'+ area.id +'" data-id="'+ area.id +'" '+selected+'>'+ area.name +'</option>');
                    });
                }
            });
        } else {
            $('#area').empty().append('<option value="">-- Select Area --</option>');
        }
    }

    // State change -> load cities
    $(document).on('change', '#state', function () {
        let stateID = $(this).find(':selected').data('id');
        loadCities(stateID);
    });

    // City change -> load areas
    $(document).on('change', '#city', function () {
        let cityId = $(this).find(':selected').data('id');
        loadAreas(cityId);
    });

    let preSelectedState = $('#state').find(':selected').data('id'); 
    let preSelectedCity  = "{{ old('city_id', $selectedCityId ?? '') }}"; 
    let preSelectedArea  = "{{ old('area_id', $selectedAreaId ?? '') }}"; 

    if (preSelectedState) {
        loadCities(preSelectedState, preSelectedCity);
    }
    if (preSelectedCity) {
        loadAreas(preSelectedCity, preSelectedArea);
    }
});
</script>
    
@endpush
