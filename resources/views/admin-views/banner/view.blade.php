@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Banner'))

@push('css_or_js')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    .banner-action-btn {
        width: 36px;
        height: 36px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
    }

    .banner-action-btn i {
        font-size: 18px;
        line-height: 1;
    }

    .banner-status-switch .form-check-input {
        width: 2.75em;
        height: 1.4em;
        cursor: pointer;
        margin: 0;
    }

    .banner-status-switch .form-check-input:focus {
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.2);
    }
</style>
@endpush

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-image menu-icon"></i>
            </span> {{\App\CPU\translate('banner')}}
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <span></span>{{\App\CPU\translate('banner')}} <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                </li>
            </ul>
        </nav>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <!-- Content Row -->
            <div class="row pb-4 d--none" id="main-banner"
                style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">

                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ \App\CPU\translate('banner_form') }}</h4>
                        </div>

                        <div class="card-body">
                            <form action="{{route('admin.banner.store')}}" method="post" enctype="multipart/form-data"
                                class="banner_form">
                                @csrf

                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <label class="form-label">{{ \App\CPU\translate('title') }}</label>
                                        <input type="text" name="title" class="form-control"
                                            placeholder="{{ \App\CPU\translate('title')}}">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">{{ \App\CPU\translate('image') }} <span class="text-danger">*</span></label>
                                        <input required type="file" name="image" class="form-control"
                                            id="mbimageFileUploader"
                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                         <small class="text-muted d-block mt-1">
                                            <i class="tio-info-outined"></i>
                                            Accepted types: JPG, JPEG, PNG, GIF, BMP, TIF &nbsp;|&nbsp; Max size: 2MB &nbsp;|&nbsp; Recommended size: 600×300 px
                                        </small>
                                        <img id="mbImageviewer"
                                            src="{{asset('assets/front-end/img/placeholder.png')}}"
                                            onerror="this.src='{{asset('assets/logo/logo-icon.png')}}'"
                                            alt="Image Preview"
                                            class="img-thumbnail mt-2"
                                            style="max-width: 200px;">
                                       
                                    </div>

                                </div>

                                <div class="row g-3 mt-2">
                                    <div class="col-md-12 d-flex justify-content-end gap-3 flex-wrap">
                                        <button class="btn btn-secondary cancel px-4" type="reset">
                                            {{ \App\CPU\translate('reset')}}
                                        </button>

                                        <button id="add" type="submit"
                                            class="btn btn-primary px-4">
                                            {{ \App\CPU\translate('save')}}
                                        </button>

                                        <!-- <button id="update"
                                            class="btn btn-primary d--none text-white px-4">
                                            {{ \App\CPU\translate('update')}}
                                        </button> -->
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row" id="banner-table">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-md-6 col-lg-6 mb-2 mb-md-0">
                                    <h5 class="mb-0 text-capitalize d-flex gap-2">
                                        {{ \App\CPU\translate('banner_table')}}
                                        <span
                                            class="badge badge-soft-dark radius-50 fz-12">{{ $banners->total() }}</span>
                                    </h5>
                                </div>
                                <div class="col-md-6 col-lg-6 text-end">
                                    <div id="banner-btn">
                                        <button id="main-banner-add" class="btn btn-primary text-nowrap">
                                            <i class="tio-add"></i>
                                            {{ \App\CPU\translate('add_banner')}}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="columnSearchDatatable"
                                class="table">
                                <thead class="text-capitalize">
                                    <tr>
                                        <th class="pl-xl-5">{{\App\CPU\translate('SL')}}</th>
                                        <th>{{\App\CPU\translate('image')}}</th>
                                       
                                        <th class="text-center">{{\App\CPU\translate('published')}}</th>
                                        <th class="text-center">{{\App\CPU\translate('action')}}</th>
                                    </tr>
                                </thead>
                                @foreach($banners as $key=>$banner)
                                <tbody>
                                    <tr id="data-{{$banner->id}}">
                                        <td class="pl-xl-5">{{$banners->firstItem()+$key}}</td>
                                        <td>
                                            <img class="ratio-4:1" width="250" height="100"
                                                onerror="this.src='{{asset('assets/logo/logo-1.png')}}'"
                                                src="{{$banner->image}}">
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check form-switch banner-status-switch d-inline-flex justify-content-center mb-0">
                                                <input class="form-check-input banner-status"
                                                    type="checkbox"
                                                    role="switch"
                                                    aria-label="{{ \App\CPU\translate('Activate or deactivate') }}"
                                                    data-id="{{ $banner->id }}"
                                                    {{ (int) $banner->status === 1 ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2 justify-content-center">
                                                <a class="btn btn-outline-primary btn-sm cursor-pointer banner-action-btn"
                                                    title="{{ \App\CPU\translate('Edit') }}"
                                                    href="{{ route('admin.banner.edit', $banner->id) }}">
                                                    <i class="mdi mdi-pencil-outline"></i>
                                                </a>
                                                <a class="btn btn-outline-danger btn-sm cursor-pointer delete banner-action-btn"
                                                    title="{{ \App\CPU\translate('Delete') }}"
                                                    id="{{ $banner->id }}">
                                                    <i class="mdi mdi-delete-outline"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                                @endforeach
                            </table>
                        </div>

                        <div class="table-responsive mt-4">
                            <div class="px-4 d-flex justify-content-lg-end">
                                <!-- Pagination -->
                                {{$banners->links()}}
                            </div>
                        </div>

                        @if(count($banners)==0)
                        <div class="text-center p-4">
                            <img class="mb-3 w-160"
                                src="{{ asset('assets/back-end/svg/illustrations/sorry.svg') }}"
                                alt="Image Description">
                            <p class="mb-0">{{ \App\CPU\translate('No_data_to_show')}}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    function notifySuccess(message) {
        if (typeof toastr !== 'undefined' && toastr.success) {
            toastr.success(message);
            return;
        }
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: message,
            showConfirmButton: false,
            timer: 2000
        });
    }

    function notifyError(message) {
        if (typeof toastr !== 'undefined' && toastr.error) {
            toastr.error(message);
            return;
        }
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: message,
            showConfirmButton: false,
            timer: 2500
        });
    }

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

    $(document).on('change', '.banner-status', function() {
        var id = $(this).data('id');
        var status = $(this).prop('checked') ? 1 : 0;
        var $toggle = $(this);

        $.ajax({
            url: "{{ route('admin.banner.status') }}",
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            data: { id: id, status: status },
            success: function(data) {
                if (parseInt(data, 10) === 1) {
                    notifySuccess('{{ \App\CPU\translate('Banner published successfully!') }}');
                } else {
                    notifySuccess('{{ \App\CPU\translate('Banner unpublished successfully!') }}');
                }
            },
            error: function() {
                notifyError('{{ \App\CPU\translate('Failed to update status') }}');
                $toggle.prop('checked', !$toggle.prop('checked'));
            }
        });
    });

    $(document).on('click', '.delete', function() {
        var id = $(this).attr('id');
        Swal.fire({
            title: '{{ \App\CPU\translate('Are you sure ?') }}',
            text: "{{ \App\CPU\translate('You wont be able to revert this!') }}",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '{{ \App\CPU\translate('Yes, delete it!') }}'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('admin.banner.delete') }}",
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    data: { id: id },
                    success: function() {
                        $('#data-' + id).remove();
                        notifySuccess('{{ \App\CPU\translate('Banner deleted successfully!') }}');
                    },
                    error: function() {
                        notifyError('{{ \App\CPU\translate('Failed to delete banner') }}');
                    }
                });
            }
        });
    });
</script>
@endpush