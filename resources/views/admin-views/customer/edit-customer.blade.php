@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('Edit User'))

@push('css_or_js')
<link href="{{ asset('public/assets/back-end') }}/css/select2.min.css" rel="stylesheet" />
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')

<div class="content-wrapper">

    <!-- Page Header -->
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-account"></i>
            </span>
            {{ \App\CPU\translate('View_User') }}
        </h3>

        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active">
                    {{ \App\CPU\translate('View_User') }}
                    <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                </li>
            </ul>
        </nav>
    </div>


    <div class="row">

        <div class="col-lg-12">

            <form action="{{ route('admin.user.update',$user->id) }}" method="post" enctype="multipart/form-data">
                @csrf

                <div class="card">

                    <!-- Card Header -->
                    <div class="card-header">
                        <h4>{{ \App\CPU\translate('General_Information') }}</h4>
                    </div>

                    <div class="card-body">

                        <input type="hidden" name="id" value="{{$user->id}}">

                        <div class="row g-3">

                            <!-- Name -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">{{ \App\CPU\translate('Name') }}</label>
                                    <input type="text" name="name"
                                        value="{{ $user->name ?? old('name') }}"
                                        class="form-control"
                                        placeholder="Enter Name" required>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">{{ \App\CPU\translate('Email') }}</label>
                                    <input type="email"
                                        name="email"
                                        value="{{ $user->email ?? old('email') }}"
                                        class="form-control"
                                        placeholder="ex@gmail.com"
                                        required>
                                </div>
                            </div>



                            <!-- Phone -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">{{ \App\CPU\translate('Phone') }}</label>
                                    <input type="number"
                                        name="phone"
                                        value="{{ $user->mobile ?? old('phone') }}"
                                        class="form-control"
                                        id="phone"
                                        placeholder="{{ \App\CPU\translate('Ex') }} : +88017********"
                                        oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength); this.setCustomValidity(this.value.length < 10 ? 'Minimum length is 10 digits.' : ''); this.value = this.value.replace(/[^0-9]/g, '');"
                                        maxlength="10">
                                </div>
                            </div>

                            <!-- DOB -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">{{ \App\CPU\translate('Date_of_Birth') }}</label>
                                    <input type="date"
                                        name="dob"
                                        value="{{ $user->dob ?? old('dob') }}"
                                        class="form-control">
                                </div>
                            </div>

                            <!-- Gender -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">{{ \App\CPU\translate('Gender') }}</label>
                                    <select name="gender" class="form-control form-select">
                                        <option value="male" {{ $user->gender=='male'?'selected':'' }}>Male</option>
                                        <option value="female" {{ $user->gender=='female'?'selected':'' }}>Female</option>
                                        <option value="other" {{ $user->gender=='other'?'selected':'' }}>Other</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Profession -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">{{ \App\CPU\translate('Profession') }}</label>
                                    <input type="text"
                                        name="profession"
                                        value="{{ $user->profession ?? old('profession') }}"
                                        class="form-control"
                                        placeholder="Software Engineer">
                                </div>
                            </div>

                            <!-- Instagram -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Instagram Username</label>
                                    <input type="text"
                                        name="instagram_username"
                                        value="{{ $user->instagram_username ?? old('instagram_username') }}"
                                        class="form-control">
                                </div>
                            </div>

                            <!-- Facebook -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Facebook Username</label>
                                    <input type="text"
                                        name="facebook_username"
                                        value="{{ $user->facebook_username ?? old('facebook_username') }}"
                                        class="form-control">
                                </div>
                            </div>

                            <!-- User Image -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">{{ \App\CPU\translate('User_Image') }}</label>
                                    <input type="file"
                                        name="image"
                                        id="customFileUpload"
                                        class="form-control"
                                        accept=".jpg,.png,.jpeg,.gif,.bmp,.tif,.tiff|image/*">

                                    <img id="viewer"
                                        src="{{ $user->image }}"
                                        class="img-thumbnail mt-2"
                                        style="max-width:100px"
                                        onerror="this.onerror=null; this.src='{{ asset('assets/logo/logo-2.png') }}';">
                                </div>
                            </div>

                        </div>

                        <!-- KYC Section -->
                        <div class="mt-4">
                            <hr>
                            <h5>KYC Detail</h5>
                        </div>

                        @php($panStatus=['Not Submitted','Submitted','Under Verification','Verified','Rejected'])

                        <div class="row g-3 mt-2">

                            <!-- UPI -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">UPI ID</label>
                                    <input type="text" class="form-control" value="{{$user->upi_id}}" disabled>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">UPI Status</label>
                                    <select name="upi_status" class="form-control form-select">
                                        @foreach($panStatus as $pStat)
                                        <option value="{{$pStat}}" {{$user->upi_status==$pStat?'selected':''}}>
                                            {{$pStat}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Bank -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">&nbsp;</label>
                                    @if(isset($user->bank_detail))
                                    @foreach($user->bank_detail as $key=>$value)
                                    <div class="form-control mb-1">
                                        <strong>{{ucwords(str_replace('_',' ',$key))}}:</strong> {{$value}}
                                    </div>
                                    @endforeach
                                    @endif
                                </div>
                            </div>

                            <!-- Bank -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Bank Status</label>
                                    <select name="bank_status" class="form-control form-select">
                                        @foreach($panStatus as $pStat)
                                        <option value="{{$pStat}}" {{$user->bank_status==$pStat?'selected':''}}>
                                            {{$pStat}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- PAN -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">PAN Number</label>
                                    <input type="text" class="form-control" value="{{$user->pan_number}}" disabled>
                                    <img src="{{ $user->pan_image }}" class="img-thumbnail mt-2" style="max-width:150px">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">PAN Status</label>
                                    <select name="pan_status" class="form-control form-select">
                                        @foreach($panStatus as $pStat)
                                        <option value="{{$pStat}}" {{$user->pan_status==$pStat?'selected':''}}>
                                            {{$pStat}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Aadhar -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Aadhar Number</label>
                                    <input type="text" class="form-control" value="{{$user->aadhar_number}}" disabled>
                                    <div class="d-flex gap-2 mt-2">
                                        @foreach($user->aadhar_image as $img)
                                        <img src="{{$img}}" class="img-thumbnail" style="max-width:120px">
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Aadhar Status</label>
                                    <select name="aadhar_status" class="form-control form-select">
                                        @foreach($panStatus as $pStat)
                                        <option value="{{$pStat}}" {{$user->aadhar_status==$pStat?'selected':''}}>
                                            {{$pStat}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>



                        </div>

                        <!-- Buttons -->
                        <div class="d-flex justify-content-end gap-3 mt-4">

                            <button type="reset" class="btn btn-secondary px-4">
                                {{ \App\CPU\translate('reset') }}
                            </button>

                            <button type="submit" class="btn btn-primary px-4">
                                {{ \App\CPU\translate('submit') }}
                            </button>

                        </div>

                    </div>
                </div>

            </form>

        </div>

    </div>

</div>

@endsection