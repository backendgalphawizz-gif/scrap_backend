@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Banner'))

@push('css_or_js')
<meta name="csrf-token" content="{{ csrf_token() }}">
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

                                        <img id="mbImageviewer"
                                            src="{{asset('public/assets/front-end/img/placeholder.png')}}"
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
                                        <!-- <th>{{\App\CPU\translate('resource_type')}}</th> -->
                                        <!-- <th>{{\App\CPU\translate('banner_type')}}</th> -->
                                        <th>{{\App\CPU\translate('published')}}</th>
                                        <th class="text-center">{{\App\CPU\translate('action')}}</th>
                                    </tr>
                                </thead>
                                @foreach($banners as $key=>$banner)
                                <tbody>
                                    <tr id="data-{{$banner->id}}">
                                        <td class="pl-xl-5">{{$banners->firstItem()+$key}}</td>
                                        <td>
                                            <img class="ratio-4:1" width="300" height="100"
                                                onerror="this.src='{{asset('assets/logo/logo-1.png')}}'"
                                                src="{{$banner->image}}">
                                        </td>
                                        <td>
                                            <label class="switcher">
                                                <input type="checkbox" class="switcher_input status"
                                                    id="{{$banner->id}}" <?php if ($banner->status == 1) echo "checked" ?>>
                                                <span class="switcher_control"></span>
                                            </label>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2 justify-content-center">
                                                <a class="btn btn-outline-info btn-sm cursor-pointer edit"
                                                    title="{{ \App\CPU\translate('Edit')}}"
                                                    href="{{route('admin.banner.edit',[$banner['id']])}}">
                                                    Edit
                                                </a>
                                                <a class="btn btn-outline-danger btn-sm cursor-pointer delete"
                                                    title="{{ \App\CPU\translate('Delete')}}"
                                                    id="{{$banner['id']}}">
                                                    Delete
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
                                src="{{asset('public/assets/back-end')}}/svg/illustrations/sorry.svg"
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
    $(document).on('change', '.status', function() {
        var id = $(this).attr("id");
        var status = $(this).prop("checked") == true ? 1 : 0;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{route('admin.banner.status')}}",
            method: 'POST',
            data: {
                id: id,
                status: status
            },
            success: function(data) {
                if (data == 1) {
                    toastr.success('{{ \App\CPU\translate('
                        Banner published successfully!')}}');
                } else {
                    toastr.success('{{ \App\CPU\translate('
                        Banner unpublished successfully!')}}');
                }
            }
        });
    });
    $(document).on('click', '.delete', function() {
        var id = $(this).attr("id");
        Swal.fire({
            title: '{{ \App\CPU\translate('
            Are you sure ? ')}}',
            text : "{{ \App\CPU\translate('You won\'t be able to revert this!')}}",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '{{ \App\CPU\translate('
            Yes,
            delete it!')}}'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{route('admin.banner.delete')}}",
                    method: 'POST',
                    data: {
                        id: id
                    },
                    success: function() {
                        $('#data-' + id).remove();
                        toastr.success('{{ \App\CPU\translate('
                            Banner deleted successfully!')}}');
                    }
                });
            }
        })
    });
</script>
@endpush