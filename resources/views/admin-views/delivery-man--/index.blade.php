<style>
.spartan_remove_row{
    display: flex;
    justify-content: center;
    align-items: center;
}
#coba .spartan_image_placeholder {
    height: 250px !important;  
    object-fit: cover;      
}
</style>


@extends('layouts.back-end.app')

@section('title',\App\CPU\translate('Add new delivery-man'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{asset('/public/assets/back-end/img/add-new-delivery-man.png')}}" alt="">
                {{\App\CPU\translate('Add new delivery-man')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Page Header -->
        <div class="row">
            <div class="col-12">

                <form action="{{route('admin.delivery-man.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <!-- End Page Header -->
                        <div class="card-body">
                            <h5 class="mb-0 page-header-title d-flex align-items-center gap-2 border-bottom pb-3 mb-3">
                                <i class="tio-user"></i>
                                {{\App\CPU\translate('General_Information')}}
                            </h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="title-color d-flex" for="f_name">{{\App\CPU\translate('first_name')}}</label>
                                        <input type="text" name="f_name" value="{{old('f_name')}}" class="form-control" placeholder="{{\App\CPU\translate('first_name')}}"
                                               required>
                                    </div>
                                    <div class="form-group">
                                        <label class="title-color d-flex" for="exampleFormControlInput1">{{\App\CPU\translate('last')}} {{\App\CPU\translate('name')}}</label>
                                        <input value="{{old('l_name')}}"  type="text" name="l_name" class="form-control" placeholder="{{\App\CPU\translate('last')}} {{\App\CPU\translate('name')}}"
                                               required>
                                    </div>
                                    <div class="form-group">
                                        <label class="title-color d-flex" for="exampleFormControlInput1">{{\App\CPU\translate('phone')}}</label>
                                        <div class="input-group mb-3">
                                            <!-- <div class="input-group-prepend">
                                                <select
                                                    class="js-example-basic-multiple js-states js-example-responsive form-control"
                                                    name="country_code" required>
                                                    @foreach ($telephone_codes as $code)
                                                        <option value="{{ $code['code'] }}" {{old($code['code']) == $code['code']? 'selected' : ''}}>{{ $code['name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div> -->
                                            <input value="{{old('phone')}}" type="text" name="phone" class="form-control" placeholder="{{\App\CPU\translate('Ex : 017********')}}"
                                                   required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="title-color" for="exampleFormControlInput1">{{\App\CPU\translate('RC Number')}}</label>
                                        <input value="{{old('registeration_number')}}" type="text" name="registeration_number" class="form-control"
                                            placeholder="Ex : MH-01-AB-1234">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="title-color" for="exampleFormControlInput1">{{\App\CPU\translate('Driver License Issue')}}</label>
                                        <input value="{{old('license_doi')}}" id="license_doi"  max="{{ date('Y-m-d') }}" type="date" name="license_doi" class="form-control"
                                            placeholder="Ex : MH-01-AB-1234">
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="title-color d-flex" for="exampleFormControlInput1">{{\App\CPU\translate('identity')}} {{\App\CPU\translate('type')}}</label>
                                        <select name="identity_type" class="form-control">
                                            <option value="passport">{{\App\CPU\translate('passport')}}</option>
                                            <option value="driving_license">{{\App\CPU\translate('driving')}} {{\App\CPU\translate('license')}}</option>
                                            <option value="nid">{{\App\CPU\translate('nid')}}</option>
                                            <option value="company_id">{{\App\CPU\translate('company')}} {{\App\CPU\translate('id')}}</option>
                                            <option value="aadharcard">{{\App\CPU\translate('aadharcard')}}</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="title-color d-flex" for="exampleFormControlInput1">{{\App\CPU\translate('identity')}} {{\App\CPU\translate('number')}}</label>
                                        <input value="{{ old('identity_number') }}"  type="text" name="identity_number" class="form-control"
                                               placeholder="{{\App\CPU\translate('Ex : DH-23434-LS')}}"
                                               required>
                                    </div>
                                    <div class="form-group">
                                        <label class="title-color d-flex" for="exampleFormControlInput1">{{\App\CPU\translate('address')}}</label>
                                        <div class="input-group mb-3">
                                            <textarea name="address" class="form-control" id="address" rows="1" placeholder="{{\App\CPU\translate('address')}}">{{ old('address') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="title-color" for="exampleFormControlInput1">{{\App\CPU\translate('Driver License Number')}}</label>
                                        <input value="{{old('license_number')}}" type="text" name="license_number" class="form-control"
                                            placeholder="Ex : MH-01-AB-1234">
                                    </div>
                                    <div class="form-group">
                                        <label class="title-color" for="exampleFormControlInput1">{{\App\CPU\translate('Driver License Expiry')}}</label>
                                        <input value="{{old('license_exp_date')}}" id="license_exp_date"  min="{{ old('license_exp_date') ?? '' }}" type="date" name="license_exp_date" class="form-control"
                                            placeholder="Ex : MH-01-AB-1234">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="title-color">{{\App\CPU\translate('rc_image')}}</label>
                                        <span class="text-info">* ( {{\App\CPU\translate('ratio')}} 1:1 )</span>
                                        <div class="custom-file">
                                            <input type="file" name="rc_image" id="customFileRcImage" class="title-color custom-file-input"
                                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required>
                                            <label class="custom-file-label title-color" for="customFileRcImage">
                                                {{\App\CPU\translate('choose')}} {{\App\CPU\translate('file')}}
                                            </label>
                                        </div>
                                        <center class="mt-4">
                                            <img class="upload-img-view" id="viewerRcImage"
                                                src="{{asset('public\assets\back-end\img\400x400\img2.jpg')}}" alt="rc image" />
                                        </center>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="title-color">{{\App\CPU\translate('deliveryman_image')}}</label>
                                        <span class="text-info">* ( {{\App\CPU\translate('ratio')}} 1:1 )</span>
                                        <div class="custom-file">
                                            <input value="{{ old('image') }}" type="file" name="image" id="customFileEg1" class="custom-file-input"
                                                   accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required>
                                            <label class="custom-file-label" for="customFileEg1">{{\App\CPU\translate('choose')}} {{\App\CPU\translate('file')}}</label>
                                        </div>
                                        <center class="mt-4">
                                            <img class="upload-img-view" id="viewer"
                                                 src="{{asset('public\assets\back-end\img\400x400\img2.jpg')}}" alt="delivery-man image"/>
                                        </center>


                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="title-color" for="exampleFormControlInput1">{{\App\CPU\translate('identity')}} {{\App\CPU\translate('image')}}</label>
                                        <div>
                                            <div class="row" id="coba"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                      
                    <!-- <div class="card mt-3">
                        End Page Header 
                        <div class="card-body">
                            <h5 class="mb-0 page-header-title d-flex align-items-center gap-2 border-bottom pb-3 mb-3">
                                <i class="tio-user"></i>
                                {{\App\CPU\translate('Account_Information')}}
                            </h5>

                            <form action="{{route('admin.delivery-man.store')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="title-color d-flex" for="exampleFormControlInput1">{{\App\CPU\translate('email')}}</label>
                                            <input value="{{old('email')}}" type="email" name="email" class="form-control" placeholder="{{\App\CPU\translate('Ex : ex@example.com')}}"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="title-color d-flex" for="exampleFormControlInput1">{{\App\CPU\translate('password')}}</label>
                                            <input type="text" name="password" class="form-control" placeholder="{{\App\CPU\translate('password_minimum_8_characters')}}"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="title-color d-flex" for="exampleFormControlInput1">{{\App\CPU\translate('confirm_password')}}</label>
                                            <input type="text" name="confirm_password" class="form-control" placeholder="{{\App\CPU\translate('password_minimum_8_characters')}}"
                                                required>
                                        </div>
                                    </div>
                                </div> -->

                                <!-- <div class="d-flex gap-3 justify-content-end">
                                    <button type="reset" id="reset" class="btn btn-secondary px-4">{{\App\CPU\translate('reset')}}</button>
                                    <button type="submit" class="btn btn--primary px-4">{{\App\CPU\translate('submit')}}</button>
                                </div> -->
                            <!-- </form>
                        </div>
                    </div> -->
                    <div class="d-flex gap-3 justify-content-end">
                                    <button type="reset" id="reset" class="btn btn-secondary px-4">{{\App\CPU\translate('reset')}}</button>
                                    <button type="submit" class="btn btn--primary px-4">{{\App\CPU\translate('submit')}}</button>
                                </div>
                </form>
            </div>
        </div>
        <div class="row d-none">
            <div class="col-12">
                <!-- Card -->
                <div id="bussinessSection" class="card my-3 mb-lg-5">
                    <div class="card-header" id="bussinessDiv">
                        <h5 class="mb-0">{{\App\CPU\translate('Bussiness')}} {{\App\CPU\translate('Information')}}</h5>
                    </div>

                    <!-- Body -->
                    <div class="card-body">
                        <form id="shopBussinessProfile" action="" method="post" enctype="multipart/form-data" id="seller-profile-form">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6">
                                    <label class="mt-2" for="">{{\App\CPU\translate('Bussiness Email ID')}}</label>
                                    <input type="text" placeholder="{{\App\CPU\translate('bussiness_email')}}" title="{{\App\CPU\translate('Full')}}{{\App\CPU\translate('bussiness_email')}}" value="" name="bussiness_email" class="form-control">
                                </div>
                                <div class="col-lg-6">
                                    <label class="mt-2" for="">{{\App\CPU\translate('Bussiness Phone')}}</label>
                                    <input type="text" placeholder="{{\App\CPU\translate('Bussiness Phone')}}" title="{{\App\CPU\translate('Full')}}{{\App\CPU\translate('Bussiness Phone')}}" value="" name="contact" class="form-control">
                                </div>
                                <div class="col-lg-6">
                                    <label class="mt-2" for="">{{\App\CPU\translate('Bussiness Type')}}</label>
                                    <input type="text" placeholder="{{\App\CPU\translate('bussiness_type')}}" title="{{\App\CPU\translate('Full')}}{{\App\CPU\translate('bussiness_type')}}" value="" name="bussiness_type" class="form-control">
                                </div>
                                <div class="col-lg-6">
                                    <label class="mt-2" for="">{{\App\CPU\translate('Company Name Or Bussiness Name')}}</label>
                                    <input type="text" placeholder="{{\App\CPU\translate('Company_Name_Or_Bussiness_Name')}}" title="{{\App\CPU\translate('Full')}}{{\App\CPU\translate('Company_Name_Or_Bussiness_Name')}}" value="" name="name" class="form-control">
                                </div>
                                <div class="col-lg-6">
                                    <label class="mt-2" for="">{{\App\CPU\translate('Bussiness Registeration Number (If Applicable)')}}</label>
                                    <input type="text" placeholder="{{\App\CPU\translate('Bissiness_registeration_number')}}" title="{{\App\CPU\translate('Full')}}{{\App\CPU\translate('Bissiness_registeration_number')}}" value="" name="bissiness_registeration_number" class="form-control">
                                </div>
                                <div class="col-lg-6">
                                    <label class="mt-2" for="">{{\App\CPU\translate('GSTIN')}}</label>
                                    <input type="text" placeholder="{{\App\CPU\translate('GSTIN')}}" title="{{\App\CPU\translate('Full')}}{{\App\CPU\translate('GSTIN')}}" name="gst_in" value="" class="form-control">
                                </div>
                                <div class="col-lg-6">
                                    <label class="mt-2" for="">{{\App\CPU\translate('Permanent Account Number (PAN)')}}</label>
                                    <input type="text" placeholder="{{\App\CPU\translate('Permanent Account Number (PAN)')}}" title="{{\App\CPU\translate('Full')}}{{\App\CPU\translate('Permanent Account Number (PAN)')}}" value="" name="tax_identification_number" class="form-control">
                                </div>
                                <div class="col-lg-6">
                                    <label class="mt-2" for="">{{\App\CPU\translate('Website')}}</label>
                                    <input type="text" placeholder="{{\App\CPU\translate('Website')}}" title="{{\App\CPU\translate('Full')}}{{\App\CPU\translate('Website')}}" name="website" value="" class="form-control">
                                </div>
                                <div class="col-lg-6 mt-2">
                                    <div class="form-group">
                                        <label for="name" class="title-color">{{\App\CPU\translate('Upload')}} {{\App\CPU\translate('image')}}</label>
                                        <div class="custom-file text-left">
                                            <input type="file" name="image" id="customFileUpload" class="custom-file-input"
                                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                            <label class="custom-file-label" for="customFileUpload">{{\App\CPU\translate('choose')}} {{\App\CPU\translate('file')}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                             <div class="col-12 pt-2 d-flex justify-content-end">
                                <button type="button"
                                        onclick="{{env('APP_MODE')!='demo'?"form_alert('shopBussinessProfile','Want to update Bussiness Information ?')":"call_demo()"}}"
                                        class="btn btn--primary">{{\App\CPU\translate('Save')}} {{\App\CPU\translate('changes')}}</button>
                            </div>
                        </form>
                    </div>
                    <!-- End Body -->
                </div>
                <!-- End Card -->


                <!-- Card -->
                <div id="addressSectionDiv" class="card mb-3 mb-lg-5">
                    <div class="card-header">
                        <h5 class="mb-0">{{\App\CPU\translate('Address')}} {{\App\CPU\translate('Information')}}</h5>
                    </div>

                    <!-- Body -->
                    <div class="card-body">
                        <form id="editShopAddressForm" action="" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6">
                                    <label class="mt-2" for="">{{\App\CPU\translate('Store Address')}}</label>
                                    <input type="text" placeholder="{{\App\CPU\translate('Store Address')}}" title="{{\App\CPU\translate('Full')}}{{\App\CPU\translate('Store Address')}}" name="address" value="" class="form-control">
                                </div>
                                <div class="col-lg-6">
                                    <label class="mt-2" for="">{{\App\CPU\translate('Country')}}</label>
                                    @php($countries=\App\Model\Country::get())
                                   
                                    <select  placeholder="{{\App\CPU\translate('Country')}}" title="{{\App\CPU\translate('Full')}}{{\App\CPU\translate('Country')}}" name="country" class="form-control">
                                        @if(!empty($countries))
                                            @foreach($countries as $country)
                                                <option value="" >{{ $country->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <label class="mt-2" for="">{{\App\CPU\translate('State')}}</label>
                                    <select  placeholder="{{\App\CPU\translate('State')}}}" title="{{\App\CPU\translate('State')}}}" name="state" class="form-control">
                                        @if(!empty($states))
                                            @foreach($states as $state)
                                                <option value="" {{ $state->id == $data->shop->state ? 'selected' : '' }}>{{ $state->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <label class="mt-2" for="">{{\App\CPU\translate('City')}}</label>
                                    <select  placeholder="{{\App\CPU\translate('City')}}" title="{{\App\CPU\translate('Full')}}{{\App\CPU\translate('City')}}" name="city" class="form-control">
                                        @if(!empty($cities))
                                            @foreach($cities as $city)
                                                <option value="" {{ $city->id == $data->shop->city ? 'selected' : '' }}>{{ $city->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <label class="mt-2" for="">{{\App\CPU\translate('Postal/Zip Code')}}</label>
                                    <input type="text" placeholder="{{\App\CPU\translate('Postal/Zip Code')}}" title="{{\App\CPU\translate('Full')}}{{\App\CPU\translate('Postal/Zip Code')}}" name="pin_code" value="" class="form-control">
                                </div>
                                <div class="col-12 pt-2 d-flex justify-content-end">
                                    <button type="button"
                                            onclick="{{env('APP_MODE')!='demo'?"form_alert('editShopAddressForm','Want to update shop profile ?')":"call_demo()"}}"
                                            class="btn btn--primary">{{\App\CPU\translate('Save')}} {{\App\CPU\translate('changes')}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- End Body -->
                </div>
                <!-- End Card -->

                <!-- Card -->
                <div id="bankSectionF" class="card mb-3 mb-lg-5">
                    <div class="card-header">
                        <h5 class="mb-0">{{\App\CPU\translate('Bank')}} {{\App\CPU\translate('Information')}}</h5>
                    </div>
                    
                    <!-- Body -->
                    <div class="card-body">
                        <form id="bankUpdateForm" action="" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6">
                                    <label class="mt-2" for="">{{\App\CPU\translate('Bank Name')}}</label>
                                    <input type="text" placeholder="{{\App\CPU\translate('Bank Name')}}" title="{{\App\CPU\translate('Full')}}{{\App\CPU\translate('Bank Name')}}" name="bank_name" value="" class="form-control">
                                </div>
                                <div class="col-lg-6">
                                    <label class="mt-2" for="">{{\App\CPU\translate('Branch')}}</label>
                                    <input type="text" placeholder="{{\App\CPU\translate('Branch')}}" title="{{\App\CPU\translate('Full')}}{{\App\CPU\translate('Branch')}}" name="branch" value="" class="form-control">
                                </div>
                                <div class="col-lg-6">
                                    <label class="mt-2" for="">{{\App\CPU\translate('Account Type')}}</label>
                                    <select  placeholder="{{\App\CPU\translate('Account Type')}}" title="{{\App\CPU\translate('Full')}}{{\App\CPU\translate('Account Type')}}" name="account_type" class="form-control">
                                    <option value="Current" >{{\App\CPU\translate('Current')}}</option>
                                    <option value="Savings" >{{\App\CPU\translate('Saving')}}</option>
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <label class="mt-2" for="">{{\App\CPU\translate('MICR Code')}}</label>
                                    <input type="text" placeholder="{{\App\CPU\translate('MICR Code')}}" title="{{\App\CPU\translate('Full')}}{{\App\CPU\translate('MICR Code')}}" name="micr_code" value="" class="form-control">
                                </div>
                                <div class="col-lg-6">
                                    <label class="mt-2" for="">{{\App\CPU\translate('Bank Address')}}</label>
                                    <input type="text" placeholder="{{\App\CPU\translate('Bank Address')}}" title="{{\App\CPU\translate('Full')}}{{\App\CPU\translate('Bank Address')}}" value="" name="bank_address" class="form-control">
                                </div>
                                
                                <div class="col-lg-6">
                                    <label class="mt-2" for="">{{\App\CPU\translate('Account No.')}}</label>
                                    <input type="text" placeholder="{{\App\CPU\translate('Account No.')}}" title="{{\App\CPU\translate('Full')}}{{\App\CPU\translate('Account No.')}}" name="account_no" value="" class="form-control">
                                </div>
                                
                                <div class="col-lg-6">
                                    <label class="mt-2" for="">{{\App\CPU\translate('IFSC Code')}}</label>
                                    <input type="text" placeholder="{{\App\CPU\translate('IFSC Code')}}" title="{{\App\CPU\translate('Full')}}{{\App\CPU\translate('IFSC Code')}}" name="ifsc_code" value="" class="form-control">
                                </div>
                                
                                <div class="col-lg-6">
                                    <label class="mt-2" for="">{{\App\CPU\translate('Account Holder Name')}}</label>
                                    <input type="text" placeholder="{{\App\CPU\translate('Account Holder Name')}}" title="{{\App\CPU\translate('Full')}}{{\App\CPU\translate('Account Holder Name')}}" value="" name="holder_name" class="form-control">
                                </div>
                                <div class="col-12 pt-2 d-flex justify-content-end">
                                    <button type="button"
                                            onclick="{{env('APP_MODE')!='demo'?"form_alert('bankUpdateForm','Want to update bank detail ?')":"call_demo()"}}"
                                            class="btn btn--primary">{{\App\CPU\translate('Save')}} {{\App\CPU\translate('changes')}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- End Body -->
                </div>
                <!-- End Card -->

                <!-- Sticky Block End Point -->
                <div id="stickyBlockEndPoint"></div>
            </div>
        </div>
    </div>

@endsection

@push('script_2')
    <script>
        $(".js-example-responsive").select2({
            width: 'resolve'
        });
    </script>
    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function () {
            readURL(this);
        });
    </script>

    <script src="{{asset('public/assets/back-end/js/spartan-multi-image-picker.js')}}"></script>
    <script type="text/javascript">
        $(function () {
            $("#coba").spartanMultiImagePicker({
                fieldName: 'identity_image[]',
                maxCount: 5,
                rowHeight: 'auto',
                groupClassName: 'col-6 col-lg-4',
                maxFileSize: '',
                placeholderImage: {
                    image: '{{asset('public/assets/back-end/img/400x400/img2.jpg')}}',
                    width: '100%'
                },
                dropFileLabel: "Drop Here",
                onAddRow: function (index, file) {

                },
                onRenderedPreview: function (index) {

                },
                onRemoveRow: function (index) {

                },
                onExtensionErr: function (index, file) {
                    toastr.error('Please only input png or jpg type file', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                onSizeErr: function (index, file) {
                    toastr.error('File size too big', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        });
    </script>
    <script>
    document.getElementById('customFileRcImage').addEventListener('change', function(event) {
        if (event.target.files && event.target.files[0]) {
            let reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('viewerRcImage').src = e.target.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    });
</script>
<script>
    const licenseIssueInput = document.getElementById('license_doi');
    const licenseExpiryInput = document.getElementById('license_exp_date');


    const today = new Date().toISOString().split('T')[0];
    licenseIssueInput.setAttribute('max', today);


    licenseIssueInput.addEventListener('change', function () {
        const issueDate = this.value;

        if (issueDate) {
        
            licenseExpiryInput.min = issueDate;

           
            if (licenseExpiryInput.value && licenseExpiryInput.value < issueDate) {
                licenseExpiryInput.value = '';
            }
        } else {
            licenseExpiryInput.min = '';
        }
    });

    
    licenseExpiryInput.addEventListener('change', function () {
        const expiryDate = this.value;
        const issueDate = licenseIssueInput.value;

        if (expiryDate && issueDate && expiryDate <= issueDate) {
            alert('Expiry date must be greater than issue date');
            this.value = '';
        }
    });
</script>

@endpush
