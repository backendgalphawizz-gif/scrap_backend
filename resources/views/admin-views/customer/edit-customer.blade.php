@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('Edit User'))

@push('css_or_js')
<link href="{{ asset('public/assets/back-end') }}/css/select2.min.css" rel="stylesheet" />
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')

<div class="content-wrapper">
    @php($userImage = blank($user->image)
    ? asset('public/assets/front-end/img/image-place-holder.png')
    : (\Illuminate\Support\Str::startsWith($user->image, ['http://', 'https://'])
    ? $user->image
    : asset('storage/profile/' . ltrim($user->image, '/'))))

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

    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('admin.user.activity.logs', $user->id) }}" class="btn btn-outline-success">
            <i class="mdi mdi-timeline-text-outline me-1"></i> User Activity Logs
        </a>
    </div>


    <div class="row">

        <div class="col-lg-12">
            @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
            @endif
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
                                        placeholder="Enter Name">
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

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Instagram Verification Status</label>
                                    <select name="instagram_status" class="form-control form-select">
                                        <option value="not_submitted" {{ $user->instagram_status == 'not_submitted' ? 'selected' : '' }}>Not Submitted</option>
                                        <option value="pending" {{ $user->instagram_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="verified" {{ $user->instagram_status == 'verified' ? 'selected' : '' }}>Verified</option>
                                        <option value="not_verified" {{ $user->instagram_status == 'not_verified' ? 'selected' : '' }}>Not Verified</option>
                                    </select>
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

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Facebook Verification Status</label>
                                    <select name="facebook_status" class="form-control form-select">
                                        <option value="not_submitted" {{ $user->facebook_status == 'not_submitted' ? 'selected' : '' }}>Not Submitted</option>
                                        <option value="pending" {{ $user->facebook_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="verified" {{ $user->facebook_status == 'verified' ? 'selected' : '' }}>Verified</option>
                                        <option value="not_verified" {{ $user->facebook_status == 'not_verified' ? 'selected' : '' }}>Not Verified</option>
                                    </select>
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
                                        src="{{ $userImage }}"
                                        class="img-thumbnail mt-2"
                                        style="max-width:100px"
                                        onerror="this.onerror=null;this.src=&quot;{{ asset('public/assets/front-end/img/image-place-holder.png') }}&quot;;">
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
                                    <input type="text" class="form-control" value="{{$user->upi_id}}">
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">UPI Status</label>
                                    <select name="upi_status" class="form-control form-select status-select" data-target="#upi_reason">
                                        @foreach($panStatus as $pStat)
                                        <option value="{{$pStat}}" {{$user->upi_status == $pStat ? 'selected' : ''}}>
                                            {{$pStat}}
                                        </option>
                                        @endforeach
                                    </select>
                                    <input type="text" name="upi_reason" id="upi_reason" class="form-control mt-2 reason-input" placeholder="Enter reason" value="{{$user->upi_rejection_reason ?? ''}}" style="display: {{$user->upi_status == 'Rejected' ? 'block' : 'none'}};">
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
                                    <select name="bank_status" class="form-control form-select status-select" data-target="#bank_reason">
                                        @foreach($panStatus as $pStat)
                                        <option value="{{$pStat}}" {{$user->bank_status == $pStat ? 'selected' : ''}}>
                                            {{$pStat}}
                                        </option>
                                        @endforeach
                                    </select>
                                    <input type="text" name="bank_reason" id="bank_reason" class="form-control mt-2 reason-input" placeholder="Enter reason" value="{{$user->bank_rejection_reason ?? ''}}" style="display: {{$user->bank_status == 'Rejected' ? 'block' : 'none'}};">
                                </div>
                            </div>

                            <!-- PAN -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">PAN Number</label>
                                    <div class="input-group">
                                        <input type="text" id="customerPanNumber" class="form-control" value="{{$user->pan_number}}" placeholder="ABCDE1234F" style="text-transform:uppercase" readonly>
                                        <button type="button" class="btn btn-outline-primary btn-sm" id="btnVerifyCustomerPan"
                                            {{ $user->pan_status === 'Verified' ? 'disabled' : '' }}
                                            title="{{ $user->pan_status === 'Verified' ? 'Already verified' : 'Verify via Nerofy' }}">
                                            <span id="verifyCustPanText">Verify</span>
                                            <span id="verifyCustPanSpinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                                        </button>
                                    </div>
                                    <div id="custPanVerifyResult" class="mt-1 small"></div>
                                    @if($user->pan_image)
                                        <img src="{{ $user->pan_image }}" class="img-thumbnail mt-2" style="max-width:150px">
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">PAN Status</label>
                                    <select name="pan_status" class="form-control form-select status-select" data-target="#pan_reason">
                                        @foreach($panStatus as $pStat)
                                        <option value="{{$pStat}}" {{$user->pan_status == $pStat ? 'selected' : ''}}>
                                            {{$pStat}}
                                        </option>
                                        @endforeach
                                    </select>
                                    <input type="text" name="pan_reason" id="pan_reason" class="form-control mt-2 reason-input" placeholder="Enter reason" value="{{$user->pan_rejection_reason ?? ''}}" style="display: {{$user->pan_status == 'Rejected' ? 'block' : 'none'}};">
                                </div>
                            </div>

                            <!-- Aadhar -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Aadhar Number</label>
                                    <input type="text" class="form-control" value="{{$user->aadhar_number}}">
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
                                    <select name="aadhar_status" class="form-control form-select status-select" data-target="#aadhar_reason">
                                        @foreach($panStatus as $pStat)
                                        <option value="{{$pStat}}" {{$user->aadhar_status == $pStat ? 'selected' : ''}}>
                                            {{$pStat}}
                                        </option>
                                        @endforeach
                                    </select>
                                    <input type="text" name="aadhar_reason" id="aadhar_reason" class="form-control mt-2 reason-input" placeholder="Enter reason" value="{{$user->aadhar_rejection_reason ?? ''}}" style="display: {{$user->aadhar_status == 'Rejected' ? 'block' : 'none'}};">
                                </div>
                            </div>



                        </div>

                        <!-- Buttons -->
                        <div class="d-flex justify-content-end gap-3 mt-4">

                            <button type="button" class="btn btn-secondary px-4" onclick="window.history.back()">
                                Back
                            </button>

                            <button type="submit" class="btn btn-primary px-4">
                                Update
                            </button>

                        </div>

                    </div>
                </div>

            </form>

        </div>

    </div>

</div>
<script>
    document.querySelectorAll('.status-select').forEach(select => {
        select.addEventListener('change', function() {
            const target = document.querySelector(this.dataset.target);
            if (this.value === 'Rejected') {
                target.style.display = 'block';
            } else {
                target.style.display = 'none';
                target.value = '';
            }
        });
    });

    // Prevent 'verified' if the linked username field is empty
    const igStatus = document.querySelector('select[name="instagram_status"]');
    const igUsername = document.querySelector('input[name="instagram_username"]');
    const fbStatus = document.querySelector('select[name="facebook_status"]');
    const fbUsername = document.querySelector('input[name="facebook_username"]');

    function guardVerified(statusSelect, usernameInput, label) {
        if (statusSelect.value === 'verified' && !usernameInput.value.trim()) {
            alert('Cannot set ' + label + ' status to Verified without a ' + label + ' username.');
            statusSelect.value = usernameInput.dataset.prevStatus || 'not_submitted';
        }
        usernameInput.dataset.prevStatus = statusSelect.value;
    }

    igStatus.addEventListener('change', () => guardVerified(igStatus, igUsername, 'Instagram'));
    fbStatus.addEventListener('change', () => guardVerified(fbStatus, fbUsername, 'Facebook'));

    // KYC status guards — disabled inputs hold the current DB value
    const upiStatus   = document.querySelector('select[name="upi_status"]');
    const upiId       = document.querySelector('input[name="upi_id"], .form-control[value="{{ $user->upi_id }}"]') ||
                        { value: '{{ $user->upi_id }}' };
    const bankStatus  = document.querySelector('select[name="bank_status"]');
    const bankHasData = {{ isset($user->bank_detail) && !empty($user->bank_detail) ? 'true' : 'false' }};
    const panStatus   = document.querySelector('select[name="pan_status"]');
    const panNumber   = '{{ $user->pan_number }}';
    const aadharStatus = document.querySelector('select[name="aadhar_status"]');
    const aadharNumber = '{{ $user->aadhar_number }}';

    function guardKycVerified(statusSelect, hasValue, label) {
        if (statusSelect.value === 'Verified' && !hasValue) {
            alert('Cannot set ' + label + ' status to Verified without ' + label + ' data.');
            statusSelect.value = statusSelect.dataset.prevVal || 'Not Submitted';
        }
        statusSelect.dataset.prevVal = statusSelect.value;
    }

    upiStatus.addEventListener('change',    () => guardKycVerified(upiStatus,    !!upiId.value.trim(), 'UPI'));
    bankStatus.addEventListener('change',   () => guardKycVerified(bankStatus,   bankHasData,          'Bank'));
    panStatus.addEventListener('change',    () => guardKycVerified(panStatus,    !!panNumber.trim(),   'PAN'));
    aadharStatus.addEventListener('change', () => guardKycVerified(aadharStatus, !!aadharNumber.trim(),'Aadhar'));

    // Block form submission
    document.querySelector('form').addEventListener('submit', function(e) {
        if (igStatus.value === 'verified' && !igUsername.value.trim()) {
            e.preventDefault();
            alert('Cannot set Instagram status to Verified without an Instagram username.');
            igStatus.focus();
            return;
        }
        if (fbStatus.value === 'verified' && !fbUsername.value.trim()) {
            e.preventDefault();
            alert('Cannot set Facebook status to Verified without a Facebook username.');
            fbStatus.focus();
            return;
        }
        if (upiStatus.value === 'Verified' && !upiId.value.trim()) {
            e.preventDefault();
            alert('Cannot set UPI status to Verified without a UPI ID.');
            upiStatus.focus();
            return;
        }
        if (bankStatus.value === 'Verified' && !bankHasData) {
            e.preventDefault();
            alert('Cannot set Bank status to Verified without bank details.');
            bankStatus.focus();
            return;
        }
        if (panStatus.value === 'Verified' && !panNumber.trim()) {
            e.preventDefault();
            alert('Cannot set PAN status to Verified without a PAN number.');
            panStatus.focus();
            return;
        }
        if (aadharStatus.value === 'Verified' && !aadharNumber.trim()) {
            e.preventDefault();
            alert('Cannot set Aadhar status to Verified without an Aadhar number.');
            aadharStatus.focus();
            return;
        }
    });
</script>
<script>
    @if(session('success'))
    toastr.success("{{ session('success') }}");
    @endif

    @if(session('error'))
    toastr.error("{{ session('error') }}");
    @endif
</script>
<script>
    // Customer PAN Verification
    const custPanRegex = /^[A-Z]{5}[0-9]{4}[A-Z]$/i;

    $('#btnVerifyCustomerPan').on('click', function () {
        const panNumber = $('#customerPanNumber').val().trim().toUpperCase();
        const $result   = $('#custPanVerifyResult');

        if (!panNumber) {
            $result.html('<span class="text-danger">Please enter a PAN number first.</span>');
            return;
        }
        if (!custPanRegex.test(panNumber)) {
            $result.html('<span class="text-danger">Invalid PAN format. Expected: ABCDE1234F</span>');
            return;
        }

        const $btn     = $(this);
        const $text    = $('#verifyCustPanText');
        const $spinner = $('#verifyCustPanSpinner');

        $btn.prop('disabled', true);
        $text.text('Verifying…');
        $spinner.removeClass('d-none');
        $result.html('');

        $.ajax({
            url: '{{ route("admin.verify.pan") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                pan_number: panNumber,
            },
            success: function (res) {
                if (res.valid) {
                    $result.html('<span class="text-success">✔ PAN is valid' + (res.name ? ' — ' + res.name : '') + '</span>');
                } else {
                    $result.html('<span class="text-danger">✘ PAN is invalid (' + (res.pan_status || 'not valid') + ')</span>');
                }
            },
            error: function (xhr) {
                const msg = xhr.responseJSON ? (xhr.responseJSON.message || 'Verification failed.') : 'Server error.';
                $result.html('<span class="text-danger">' + msg + '</span>');
            },
            complete: function () {
                $btn.prop('disabled', false);
                $text.text('Verify');
                $spinner.addClass('d-none');
            }
        });
    });
</script>
@endsection