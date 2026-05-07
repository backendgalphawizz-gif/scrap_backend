@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('Employee Edit'))
@push('css_or_js')
<link href="{{asset('public/assets/back-end')}}/css/select2.min.css" rel="stylesheet" />
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="content-wrapper">
    <!-- Page Title -->
    <div class="mb-3">
        <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
            <img src="{{asset('/public/assets/back-end/img/add-new-employee.png')}}" alt="">
            {{\App\CPU\translate('Employee_Update')}}
        </h2>
    </div>
    <!-- End Page Title -->

    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{\App\CPU\translate('Employee')}} {{\App\CPU\translate('Update')}} {{\App\CPU\translate('form')}}</h5>
                </div>
                <div class="card-body">
                    <form action="{{route('admin.employee.update',[$e['id']])}}" method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="row g-3">

                            {{-- Profile Image --}}
                            <div class="col-12 d-flex align-items-center gap-3 mb-2">
                                <img id="viewer"
                                    src="{{ $e['image'] ?? asset('public/assets/front-end/img/image-place-holder.png') }}"
                                    onerror="this.onerror=null; this.src='{{ asset('public/assets/front-end/img/image-place-holder.png') }}'"
                                    alt="Profile"
                                    style="width:90px;height:90px;border-radius:50%;object-fit:cover;border:3px solid #0c9ea2;">
                                <div>
                                    <div class="fw-bold fs-6">{{ $e['name'] }}</div>
                                    <div class="text-muted small">{{ $e['email'] }}</div>
                                </div>
                            </div>

                            {{-- Name --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="title-color">{{\App\CPU\translate('Full Name')}} <span class="text-danger">*</span></label>
                                    <input type="text"
                                        name="name"
                                        class="form-control"
                                        id="name"
                                        value="{{ $e['name'] }}"
                                        placeholder="Ex: John Doe"
                                        oninput="this.value = this.value.replace(/[^A-Za-z\s]/g, '')"
                                        pattern="[A-Za-z\s]+"
                                        title="Only letters and spaces allowed"
                                        minlength="3"
                                        maxlength="25"
                                        required>
                                </div>
                            </div>

                            {{-- Phone (read-only) --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="title-color">{{\App\CPU\translate('Phone')}}</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="mdi mdi-phone-lock"></i></span>
                                        <input type="tel"
                                            class="form-control bg-light"
                                            value="{{ $e['phone'] }}"
                                            readonly
                                            title="Phone cannot be changed">
                                    </div>
                                    <small class="text-muted">Phone number cannot be changed.</small>
                                </div>
                            </div>

                            {{-- Email (read-only) --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="title-color">{{\App\CPU\translate('Email')}}</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="mdi mdi-email-lock"></i></span>
                                        <input type="email"
                                            class="form-control bg-light"
                                            value="{{ $e['email'] }}"
                                            readonly
                                            title="Email cannot be changed">
                                    </div>
                                    <small class="text-muted">Email address cannot be changed.</small>
                                </div>
                            </div>

                            {{-- Role --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="title-color">{{\App\CPU\translate('Role')}} <span class="text-danger">*</span></label>
                                    <select class="form-control form-select" name="role_id" required>
                                        <option value="" disabled>---{{\App\CPU\translate('select')}}---</option>
                                        @foreach($rls as $r)
                                            @if($r->id != 3)
                                                <option value="{{ $r->id }}" {{ $r->id == $e['admin_role_id'] ? 'selected' : '' }}>
                                                    {{ $r->name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Password with eye toggle --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="title-color">{{\App\CPU\translate('Password')}}</label>
                                    <small class="text-muted"> (leave blank to keep current)</small>
                                    <div class="input-group">
                                        <input type="password"
                                            name="password"
                                            class="form-control"
                                            id="edit_password"
                                            placeholder="Min 8 characters"
                                            minlength="8"
                                            maxlength="20">
                                        <button type="button" class="btn btn-outline-secondary toggle-password" data-target="edit_password">
                                            <i class="mdi mdi-eye-off"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            {{-- Profile Image upload --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="title-color">{{\App\CPU\translate('employee_image')}}</label>
                                    <span class="text-muted small"> (ratio 1:1, optional)</span>
                                    <input type="file"
                                        name="image"
                                        id="customFileUpload"
                                        class="form-control"
                                        accept=".jpg,.png,.jpeg,.gif,.bmp,.tif,.tiff">
                                </div>
                            </div>

                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-3">
                            <a href="{{ route('admin.employee.list') }}" class="btn btn-secondary px-4">{{\App\CPU\translate('Cancel')}}</a>
                            <button type="submit" class="btn btn-primary px-4">{{\App\CPU\translate('Update')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- content-wrapper --}}
@endsection

@push('script')
<script src="{{asset('public/assets/back-end')}}/js/select2.min.js"></script>
<script>
    // Image preview on file select
    $("#customFileUpload").on('change', function () {
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#viewer').attr('src', e.target.result);
            };
            reader.readAsDataURL(this.files[0]);
        }
    });

    // Password eye toggle
    $(document).on('click', '.toggle-password', function () {
        var targetId = $(this).data('target');
        var input = $('#' + targetId);
        var icon = $(this).find('i');
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('mdi-eye-off').addClass('mdi-eye');
        } else {
            input.attr('type', 'password');
            icon.removeClass('mdi-eye').addClass('mdi-eye-off');
        }
    });
</script>
@endpush