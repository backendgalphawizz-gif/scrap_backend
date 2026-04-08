@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Campaign List'))

@push('css_or_js')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-bullhorn-outline"></i>
            </span> {{\App\CPU\translate('Campaign List')}}
        </h3>
        <div id="banner-btn">
            <a href="{{ route('admin.campaign.add') }}" class="btn btn-primary ">
                <i class="tio-add"></i>
                {{ \App\CPU\translate('add_Campaign')}}
            </a>
        </div>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <span></span>{{\App\CPU\translate('Campaign List')}} <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                </li>
            </ul>
        </nav>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <!-- Content Row -->
            <div class="row pb-4 d-none" id="main-banner"
                style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 text-capitalize">{{ \App\CPU\translate('Campaign')}}</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{route('admin.banner.store')}}" method="post" enctype="multipart/form-data"
                                class="banner_form">
                                @csrf

                                <div class="row g-3 align-items-end">
                                    <div class="col-lg-3">
                                        <label for="">Title</label>
                                        <input type="text" name="title" class="form-control"
                                            placeholder="{{ \App\CPU\translate('title')}}">
                                    </div>
                                    <div class="col-md-12 d-flex flex-column justify-content-end">
                                        <div>
                                            <center class="mb-30 mx-auto">
                                                <img
                                                    class="ratio-4:1"
                                                    id="mbImageviewer"
                                                    src="{{asset('public/assets/front-end/img/placeholder.png')}}"
                                                    alt="banner image" />
                                            </center>
                                            <label for="name"
                                                class="title-color text-capitalize">{{ \App\CPU\translate('Image')}}</label>
                                            <span class="text-info" id="theme_ratio">( {{\App\CPU\translate('ratio')}} 4:1 )</span>
                                            <div class="custom-file text-left">
                                                <input type="file" name="image" id="mbimageFileUploader"
                                                    class="custom-file-input"
                                                    accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                                <label class="custom-file-label title-color"
                                                    for="mbimageFileUploader">{{\App\CPU\translate('choose')}} {{\App\CPU\translate('file')}}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 d-flex justify-content-end flex-wrap gap-10">
                                        <button class="btn btn-secondary cancel px-4" type="reset">{{ \App\CPU\translate('reset')}}</button>
                                        <button id="add" type="submit"
                                            class="btn btn--primary px-4">{{ \App\CPU\translate('save')}}</button>
                                        <button id="update"
                                            class="btn btn--primary d--none text-white">{{ \App\CPU\translate('update')}}</button>
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
                        <div class="px-3 py-4 d-none">
                            <div class="row align-items-center">
                                <div class="col-md-6 col-lg-6 mb-2 mb-md-0">
                                    <h5 class="mb-0 text-capitalize d-flex gap-2">
                                        {{ \App\CPU\translate('Campaign List')}}
                                        <span
                                            class="badge badge-soft-dark radius-50 fz-12">{{ $campaigns->total() }}</span>
                                    </h5>
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
                                        <th>{{\App\CPU\translate('brand_name')}}</th>
                                        <th>{{\App\CPU\translate('title')}}</th>
                                        <th>{{\App\CPU\translate('earning coins')}}</th>
                                        <th>{{\App\CPU\translate('start_date')}}</th>
                                        <th>{{\App\CPU\translate('end_date')}}</th>
                                        <th>{{\App\CPU\translate('city')}}</th>
                                        <th>{{\App\CPU\translate('state')}}</th>
                                        <th>{{\App\CPU\translate('gender')}}</th>
                                        <th>{{\App\CPU\translate('status')}}</th>
                                        <th class="text-center">{{\App\CPU\translate('action')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($campaigns as $key=>$campaign)
                                    <tr id="data-{{$campaign->id}}">
                                        <td class="pl-xl-5">{{$campaigns->firstItem()+$key}}</td>
                                        <td>
                                            <img class="ratio-4:1" width="300" height="100"
                                                onerror="this.src='{{asset('assets/logo/logo-1.png')}}'"
                                                src="{{$campaign->thumbnail}}">
                                        </td>
                                        <td class="pl-xl-5">{{$campaign->brand->username}}</td>
                                        <td class="pl-xl-5">{{$campaign->title}}</td>
                                        <td class="pl-xl-5">{{$campaign->coins}}</td>
                                        <td class="pl-xl-5">{{$campaign->start_date}}</td>
                                        <td class="pl-xl-5">{{$campaign->end_date}}</td>
                                        <td class="pl-xl-5">{{$campaign->city}}</td>
                                        <td class="pl-xl-5">{{$campaign->state}}</td>
                                        <td class="pl-xl-5">{{$campaign->gender}}</td>
                                        <td>
                                            <span class="badge badge-{{ in_array($campaign->status, ['active','completed']) ? 'gradient-success' : 'gradient-danger' }}">
                                                {{ ucwords($campaign->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2 justify-content-center">
                                                <a class="btn btn-outline-info btn-sm cursor-pointer edit"
                                                    title="{{ \App\CPU\translate('Edit')}}"
                                                    href="{{route('admin.campaign.show',[$campaign['id']])}}">
                                                    View
                                                </a>
                                                <a class="btn btn-outline-primary btn-sm cursor-pointer edit"
                                                    title="{{ \App\CPU\translate('Edit')}}"
                                                    href="{{route('admin.campaign.edit',[$campaign['id']])}}">
                                                    Edit
                                                </a>
                                                <a class="btn btn-outline-danger btn-sm cursor-pointer delete"
                                                    title="{{ \App\CPU\translate('Delete')}}"
                                                    id="{{$campaign['id']}}">
                                                    Delete
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="table-responsive mt-4">
                            <div class="px-4 d-flex justify-content-lg-end">
                                <!-- Pagination -->
                                {{$campaigns->links()}}
                            </div>
                        </div>

                        @if(count($campaigns)==0)
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
            url: "{{route('admin.campaign.status')}}",
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
                    url: "{{route('admin.campaign.delete')}}",
                    method: 'POST',
                    data: {
                        id: id
                    },
                    success: function() {
                        $('#data-' + id).remove();
                        // toastr.success('{{ \App\CPU\translate('campaign deleted successfully!')}}');
                    }
                });
            }
        })
    });
</script>
@endpush