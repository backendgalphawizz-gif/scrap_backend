@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('add_new_seller'))

@push('css_or_js')

@endpush

@section('content')
<div class="content container-fluid main-card {{Session::get('direction')}}">

    <!-- Page Title -->
    <div class="mb-4">
        <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
            <img src="{{asset('/public/assets/back-end/img/add-new-seller.png')}}" class="mb-1" alt="">
            {{\App\CPU\translate('add_new_seller')}}
        </h2>
    </div>
    <!-- End Page Title -->

    <form class="user" action="{{route('shop.apply')}}" method="post" enctype="multipart/form-data" id="vendor-register">
        
      @csrf
        <div class="card">
            <div class="card-body">
                <input type="hidden" name="status" value="approved">
                <h5 class="mb-0 text-capitalize d-flex align-items-center gap-2 border-bottom pb-3 mb-4 pl-4">
                    <img src="{{asset('/public/assets/back-end/img/seller-information.png')}}" class="mb-1" alt="">
                    {{\App\CPU\translate('seller_information')}}
                </h5>
                <div class="row align-items-center">
                    <div class="col-lg-6 mb-4 mb-lg-0">
                        <div class="form-group">
                            <label for="exampleFirstName" class="title-color d-flex gap-1 align-items-center">{{\App\CPU\translate('name')}}</label>
                            <input type="text" class="form-control form-control-user" id="exampleFirstName" name="f_name" value="{{old('f_name')}}" placeholder="{{\App\CPU\translate('Ex')}}: Jhone">
                        </div>
                        <div class="form-group d-none">
                            <label for="exampleLastName" class="title-color d-flex gap-1 align-items-center">{{\App\CPU\translate('last_name')}}</label>
                            <input type="text" class="form-control form-control-user" id="exampleLastName" name="l_name" value="{{old('l_name')}}" placeholder="{{\App\CPU\translate('Ex')}}: Doe">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPhone" class="title-color d-flex gap-1 align-items-center">{{\App\CPU\translate('phone')}}</label>
                            <input type="text" onkeypress="return (event.charCode !=32 && event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" minlength="10" maxlength="12" class="form-control form-control-user" id="exampleInputPhone" name="phone" value="{{old('phone')}}" placeholder="{{\App\CPU\translate('Ex')}}: +09587498">
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail" class="title-color d-flex gap-1 align-items-center">{{\App\CPU\translate('email')}}</label>
                            <input type="email"  onkeypress="return (event.charCode !=32)" class="form-control form-control-user" id="exampleInputEmail" name="email" value="{{old('email')}}" placeholder="{{\App\CPU\translate('Ex')}}: Jhone@company.com">
                        </div>

                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <center>
                                <img class="upload-img-view" id="viewer"
                                    src="{{asset('public\assets\back-end\img\400x400\img2.jpg')}}" alt="banner image"/>
                            </center>
                        </div>

                        <div class="form-group">
                            <div class="title-color mb-2 d-flex gap-1 align-items-center">{{\App\CPU\translate('Seller_Image')}} <span class="text-info">({{\App\CPU\translate('ratio')}} {{\App\CPU\translate('1')}}:{{\App\CPU\translate('1')}})</span></div>
                            <div class="custom-file text-left">
                                <input type="file" name="image" id="customFileUpload" class="custom-file-input"
                                    accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                <label class="custom-file-label" for="customFileUpload">{{\App\CPU\translate('Upload')}} {{\App\CPU\translate('image')}}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- <div class="card mt-3 d-none">
            <div class="card-body">
                <input type="hidden" name="status" value="approved">
                <h5 class="mb-0 text-capitalize d-flex align-items-center gap-2 border-bottom pb-3 mb-4 pl-4">
                    <img src="{{asset('/public/assets/back-end/img/seller-information.png')}}" class="mb-1" alt="">
                    {{\App\CPU\translate('Password')}}
                </h5>
                <div class="row">
                    
                    <div class="col-lg-4 form-group">
                        <label for="exampleInputPassword" class="title-color d-flex gap-1 align-items-center">{{\App\CPU\translate('password')}}</label>
                        <input type="password" class="form-control form-control-user" minlength="8" id="exampleInputPassword" name="password" placeholder="{{\App\CPU\translate('Ex: 8+ Character')}}" >
                    </div>
                    <div class="col-lg-4 form-group">
                        <label for="exampleRepeatPassword" class="title-color d-flex gap-1 align-items-center">{{\App\CPU\translate('confirm_password')}}</label>
                        <input type="password" class="form-control form-control-user" minlength="8" id="exampleRepeatPassword" placeholder="{{\App\CPU\translate('Ex: 8+ Character')}}" >
                        <div class="pass invalid-feedback">{{\App\CPU\translate('Repeat')}}  {{\App\CPU\translate('password')}} {{\App\CPU\translate('not match')}} .</div>
                    </div>
                </div>
            </div>
        </div> -->

        <div class="card mt-3">
            <div class="card-body">
                <h5 class="mb-0 text-capitalize d-flex align-items-center gap-2 border-bottom pb-3 mb-4 pl-4">
                    <img src="{{asset('/public/assets/back-end/img/seller-information.png')}}" class="mb-1" alt="">
                    {{\App\CPU\translate('shop_information')}}
                </h5>

                <div class="row">
                    <div class="col-lg-6 form-group">
                        <label for="shop_name" class="title-color d-flex gap-1 align-items-center">{{\App\CPU\translate('Company Name Or Bussiness Name')}}</label>
                        <input type="text" class="form-control form-control-user" id="shop_name" name="shop_name" placeholder="" value="{{old('shop_name')}}">
                    </div>

                    <div class="col-lg-6 form-group">
                        <label for="bussiness_email" class="title-color d-flex gap-1 align-items-center">{{\App\CPU\translate('Bussiness Email ID')}}</label>
                        <input type="email" class="form-control form-control-user" onkeypress="return (event.charCode !=32)" id="bussiness_email" name="bussiness_email_id" placeholder="" value="{{old('bussiness_email')}}">
                    </div>

                    <div class="col-lg-6 form-group">
                        <label for="bussiness_phone" class="title-color d-flex gap-1 align-items-center">{{\App\CPU\translate('Bussiness Phone')}}</label>
                        <input type="text" class="form-control form-control-user" onkeypress="return (event.charCode !=32 && event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" minlength="10" maxlength="12" id="bussiness_phone" name="bussiness_phone" placeholder="" value="{{old('bussiness_phone')}}">
                    </div>

                    <!-- <div class="col-lg-6 form-group">
                        <label for="bussiness_type" class="title-color d-flex gap-1 align-items-center">{{\App\CPU\translate('Bussiness Type')}}</label>
                        <input type="text" class="form-control form-control-user" id="bussiness_type" name="bussiness_type" placeholder="" value="{{old('bussiness_type')}}">
                    </div>

                    <div class="col-lg-6 form-group">
                        <label for="bussiness_registeration_number" class="title-color d-flex gap-1 align-items-center">{{\App\CPU\translate('Bussiness Registeration Number (If Applicable)')}}</label>
                        <input type="text" class="form-control form-control-user" onkeypress="return (event.charCode !=32)" id="bussiness_registeration_number" name="bussiness_registeration_number" placeholder="" value="{{old('bussiness_registeration_number')}}">
                    </div> -->

                    <div class="col-lg-6 form-group">
                        <label for="gst_in" class="title-color d-flex gap-1 align-items-center">{{\App\CPU\translate('GSTIN')}}</label>
                        <input type="text" class="form-control form-control-user" onkeypress="return (event.charCode !=32)" id="gst_in" name="gst_number" placeholder="" value="{{old('gst_in')}}">
                    </div>

                    <!-- <div class="col-lg-6 form-group">
                        <label for="tin" class="title-color d-flex gap-1 align-items-center">{{\App\CPU\translate('Tax Identification Number (TIN)')}}</label>
                        <input type="text" class="form-control form-control-user" onkeypress="return (event.charCode !=32)" id="tin" name="tin" placeholder="" value="{{old('tin')}}">
                    </div> -->

                    <!-- <div class="col-lg-6 form-group">
                        <label for="website" class="title-color d-flex gap-1 align-items-center">{{\App\CPU\translate('Website')}}</label>
                        <input type="text" class="form-control form-control-user" onkeypress="return (event.charCode !=32)" id="website" name="website" placeholder="" value="{{old('website')}}">
                    </div> -->

                    <div class="col-lg-6 form-group">
                        <center>
                            <img class="upload-img-view" id="viewerLogo"
                                src="{{asset('public\assets\back-end\img\400x400\img2.jpg')}}" alt="banner image"/>
                        </center>

                        <div class="mt-4">
                            <div class="d-flex gap-1 align-items-center title-color mb-2">
                                {{\App\CPU\translate('shop_logo')}}
                                <span class="text-info">({{\App\CPU\translate('ratio')}} {{\App\CPU\translate('1')}}:{{\App\CPU\translate('1')}})</span>
                            </div>

                            <div class="custom-file">
                                <input type="file" name="logo" id="LogoUpload" class="custom-file-input"
                                    accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                <label class="custom-file-label" for="LogoUpload">{{\App\CPU\translate('Upload')}} {{\App\CPU\translate('logo')}}</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 form-group">
                        <center>
                            <img class="upload-img-view upload-img-view__banner" id="viewerBanner"
                                    src="{{asset('public\assets\back-end\img\400x400\img2.jpg')}}" alt="banner image"/>
                        </center>

                        <div class="mt-4">
                            <div class="d-flex gap-1 align-items-center title-color mb-2">
                                {{\App\CPU\translate('shop_banner')}}
                                <span class="text-info">({{\App\CPU\translate('ratio')}} {{\App\CPU\translate('6')}}:{{\App\CPU\translate('1')}})</span>
                            </div>

                            <div class="custom-file">
                                <input type="file" name="banner" id="BannerUpload" class="custom-file-input"
                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                <label class="custom-file-label" for="BannerUpload">{{\App\CPU\translate('Upload')}} {{\App\CPU\translate('Banner')}}</label>
                            </div>
                        </div>
                    </div>

                    @if(theme_root_path() == "theme_aster")
                    <div class="col-lg-6 form-group">
                        <center>
                            <img class="upload-img-view upload-img-view__banner" id="viewerBottomBanner"
                                    src="{{asset('public\assets\back-end\img\400x400\img2.jpg')}}" alt="banner image"/>
                        </center>

                        <div class="mt-4">
                            <div class="d-flex gap-1 align-items-center title-color mb-2">
                                {{translate('shop_secondary_banner')}}
                                <span class="text-info">({{translate('ratio')}} {{translate('6')}}:{{translate('1')}})</span>
                            </div>

                            <div class="custom-file">
                                <input type="file" name="bottom_banner" id="BottomBannerUpload" class="custom-file-input"
                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                <label class="custom-file-label" for="BottomBannerUpload">{{translate('Upload')}} {{translate('Bottom')}} {{translate('Banner')}}</label>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>

                
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <input type="hidden" name="status" value="approved">
                <h5 class="mb-0 text-capitalize d-flex align-items-center gap-2 border-bottom pb-3 mb-4 pl-4">
                    <img src="{{asset('/public/assets/back-end/img/seller-information.png')}}" class="mb-1" alt="">
                    {{\App\CPU\translate('Address Information')}}
                </h5>
                <div class="row">

                    <div class="col-lg-6 form-group">
                        <label for="shop_address" class="title-color d-flex gap-1 align-items-center">{{\App\CPU\translate('shop_address')}}</label>
                        <textarea name="shop_address" class="form-control" id="shop_address"rows="1" placeholder="">{{old('shop_address')}}</textarea>
                    </div>

                    <!-- <div class="col-lg-6 form-group">
                        <label for="country" class="title-color d-flex gap-1 align-items-center">{{\App\CPU\translate('Country')}}</label>
                        <textarea name="country" class="form-control" id="country"rows="1" placeholder="">{{old('country')}}</textarea>
                        <select name="" id=""></select>
                    </div> -->

                    <input type="hidden" name="country" value="India">
                    <div class="col-lg-6 form-group">
                        <label for="state" class="title-color d-flex gap-1 align-items-center">{{\App\CPU\translate('State')}}</label>
                        <select name="state" class="form-control" id="state">
                            @foreach ($states as $key=>$value )
                                <option value="{{$value['id']}}" data-id="{{$value['id']}}">{{$value['name']}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-6 form-group">
                        <label for="city" class="title-color d-flex gap-1 align-items-center">{{\App\CPU\translate('City')}}</label>
                        <select name="city" id="city" class="form-control">
                            <option value="">-- Select City --</option>
                        </select>
                    </div>

                    <div class="col-lg-6 form-group">
                        <label for="area" class="title-color d-flex gap-1 align-items-center">{{\App\CPU\translate('Area')}}</label>
                        <select name="area" id="area" class="form-control">
                            <option value="">-- Select Area --</option>
                        </select>
                    </div>

                    <!-- <div class="col-lg-6 form-group">
                        <label for="pincode" class="title-color d-flex gap-1 align-items-center">{{\App\CPU\translate('Postal/Zip Code')}}</label>
                        <textarea name="pincode" class="form-control" onkeypress="return (event.charCode !=32 && event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" minlength="6" maxlength="6" id="pincode"rows="1" placeholder="">{{old('pincode')}}</textarea>
                    </div> -->

                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <input type="hidden" name="status" value="approved">
                <h5 class="mb-0 text-capitalize d-flex align-items-center gap-2 border-bottom pb-3 mb-4 pl-4">
                    <img src="{{asset('/public/assets/back-end/img/seller-information.png')}}" class="mb-1" alt="">
                    {{\App\CPU\translate('Banking Information')}}
                </h5>
                <div class="row">

                    <div class="col-lg-6 form-group">
                        <label for="bank_name" class="title-color d-flex gap-1 align-items-center">{{\App\CPU\translate('Bank Name')}}</label>
                        <textarea name="bank_name" class="form-control" id="bank_name"rows="1" placeholder="">{{old('bank_name')}}</textarea>
                    </div>

                    <div class="col-lg-6 form-group">
                        <label for="branch_name" class="title-color d-flex gap-1 align-items-center">{{\App\CPU\translate('Bank Branch')}}</label>
                        <textarea name="branch_name" class="form-control" id="branch_name"rows="1" placeholder="">{{old('branch_name')}}</textarea>
                    </div>

                    <div class="col-lg-6 form-group">
                        <label for="account_type" class="title-color d-flex gap-1 align-items-center">{{\App\CPU\translate('Account Type')}}</label>
                        <textarea name="account_type" class="form-control" id="account_type"rows="1" placeholder="">{{old('account_type')}}</textarea>
                    </div>

                    <!-- <div class="col-lg-6 form-group">
                        <label for="micr_code" class="title-color d-flex gap-1 align-items-center">{{\App\CPU\translate('Micr Code')}}</label>
                        <textarea name="micr_code" class="form-control" id="micr_code"rows="1" onkeypress="return (event.charCode !=32)" placeholder="">{{old('micr_code')}}</textarea>
                    </div> -->

                    <div class="col-lg-6 form-group">
                        <label for="bank_address" class="title-color d-flex gap-1 align-items-center">{{\App\CPU\translate('Bank Address')}}</label>
                        <textarea name="bank_address" class="form-control" id="bank_address"rows="1" placeholder="">{{old('bank_address')}}</textarea>
                    </div>

                    <div class="col-lg-6 form-group">
                        <label for="account_number" class="title-color d-flex gap-1 align-items-center">{{\App\CPU\translate('Account Number')}}</label>
                        <textarea name="account_number" class="form-control" onkeypress="return (event.charCode !=32 && event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" id="account_number"rows="1"  placeholder="{{\App\CPU\translate('Ex')}}: Doe">{{old('account_number')}}</textarea>
                    </div>

                    <div class="col-lg-6 form-group">
                        <label for="ifsc_code" class="title-color d-flex gap-1 align-items-center">{{\App\CPU\translate('IFSC CODE')}}</label>
                        <textarea name="ifsc_code" class="form-control" id="ifsc_code"rows="1" onkeypress="return (event.charCode !=32)" placeholder="{{\App\CPU\translate('Ex')}}: Doe">{{old('ifsc_code')}}</textarea>
                    </div>
                    

                </div>
                <div class="d-flex align-items-center justify-content-end gap-10">
                    <input type="hidden" name="from_submit" value="admin">
                    <button type="reset" onclick="resetBtn()" class="btn btn-secondary">{{\App\CPU\translate('reset')}} </button>
                    <button type="submit" class="btn btn--primary btn-user" id="apply">{{\App\CPU\translate('submit')}}</button>
                </div>
            </div>
        </div>


    </form>
</div>
@endsection


<script>
    function resetBtn(){
        let placeholderImg = $("#placeholderImg").data('img');
        $('#viewer').attr('src', placeholderImg);
        $('#viewerBanner').attr('src', placeholderImg);
        $('#viewerBottomBanner').attr('src', placeholderImg);
        $('#viewerLogo').attr('src', placeholderImg);
        $('.spartan_remove_row').click();
    }

    function openInfoWeb()
    {
        var x = document.getElementById("website_info");
        if (x.style.display === "none") {
            x.style.display = "block";
        } else {
            x.style.display = "none";
        }
    }
</script>
@push('script')
@if ($errors->any())
    <script>
        @foreach($errors->all() as $error)
        toastr.error('{{$error}}', Error, {
            CloseButton: true,
            ProgressBar: true
        });
        @endforeach
    </script>
@endif
<script>
    $('#inputCheckd').change(function () {
            // console.log('jell');
            if ($(this).is(':checked')) {
                $('#apply').removeAttr('disabled');
            } else {
                $('#apply').attr('disabled', 'disabled');
            }

        });

    $('#exampleInputPassword ,#exampleRepeatPassword').on('keyup',function () {
        var pass = $("#exampleInputPassword").val();
        var passRepeat = $("#exampleRepeatPassword").val();
        if (pass==passRepeat){
            $('.pass').hide();
        }
        else{
            $('.pass').show();
        }
    });
    $('#apply').on('click',function () {

        var image = $("#image-set").val();
        if (image=="")
        {
            $('.image').show();
            return false;
        }
        var pass = $("#exampleInputPassword").val();
        var passRepeat = $("#exampleRepeatPassword").val();
        if (pass!=passRepeat){
            $('.pass').show();
            return false;
        }


    });
    function Validate(file) {
        var x;
        var le = file.length;
        var poin = file.lastIndexOf(".");
        var accu1 = file.substring(poin, le);
        var accu = accu1.toLowerCase();
        if ((accu != '.png') && (accu != '.jpg') && (accu != '.jpeg')) {
            x = 1;
            return x;
        } else {
            x = 0;
            return x;
        }
    }

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#viewer').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#customFileUpload").change(function () {
        readURL(this);
    });

    function readlogoURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#viewerLogo').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    function readBannerURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#viewerBanner').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    function readBottomBannerURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#viewerBottomBanner').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#LogoUpload").change(function () {
        readlogoURL(this);
    });
    $("#BannerUpload").change(function () {
        readBannerURL(this);
    });
    $("#BottomBannerUpload").change(function () {
        readBottomBannerURL(this);
    });

    $(document).on('submit','#vendor-register', function(e) {
        e.preventDefault()
        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: new FormData(this),
            dataType: "json",
            processData: false,
            contentType: false,
            success: function (response) {
                if(response.status) {
                    toastr.success(`${response.message}`, '', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                    setTimeout(() => {
                        window.location.reload()
                    }, 2000);
                } else {
                    toastr.error(`${response.message}`, '', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            },
            error: function(error) {
                console.log('response ', error, error.responseJSON.errors)
                var errorText = ""
                $.each(error.responseJSON.errors, function(ind, errort) {
                    errorText = `${errort[0]}`
                    toastr.error(`${errorText ?? "Something went wrong"}`, '', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                })
            }
        });
    })

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
