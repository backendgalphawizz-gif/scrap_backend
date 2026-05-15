@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Campaign List'))

@push('css_or_js')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{asset('public/assets/back-end')}}/css/toastr.css"/>
<style>
    .campaign-filter-scroll {
        overflow-x: auto;
        overflow-y: hidden;
        padding-bottom: 4px;
        width: 100%;
    }

    .campaign-filter-form {
        flex-wrap: nowrap !important;
        min-width: max-content;
    }

    .campaign-filter-form .form-control,
    .campaign-filter-form .form-select {
        min-width: 150px;
    }

    .campaign-filter-form .brand-control,
    .campaign-filter-form .title-control {
        min-width: 200px;
    }

    .campaign-filter-form .date-control {
        min-width: 165px;
    }

    .campaign-filter-form .btn {
        white-space: nowrap;
    }

    .premium-pagination-wrap {
        border-top: 1px solid #e8ebef;
        margin-top: 22px;
        padding: 12px 18px 16px;
    }

    .premium-pagination-shell {
        display: flex;
        justify-content: flex-end;
        align-items: center;
    }

    .premium-pagination-inline {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        overflow-x: auto;
    }

    .premium-pagination-nav {
        float: none;
        margin: 0;
        flex: 0 0 auto;
    }

    .premium-pagination-shell .pagination {
        margin: 0;
    }

    @media (max-width: 767px) {
        .premium-pagination-wrap {
            padding: 12px;
        }

        .premium-pagination-inline {
            justify-content: flex-end;
        }
    }
</style>
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

            <div class="d-flex justify-content-end mb-3">
                <div class="campaign-filter-scroll">
                    <form method="GET" action="{{ route('admin.campaign.list') }}" class="campaign-filter-form d-flex align-items-center justify-content-end gap-2">
                        <input type="text" class="form-control brand-control" name="brand_name" value="{{ request('brand_name') }}" placeholder="Brand name">
                        <input type="text" class="form-control title-control" name="title" value="{{ request('title') }}" placeholder="Title">
                        <input type="text" class="form-control" name="city" value="{{ request('city') }}" placeholder="City">
                        <input type="text" class="form-control" name="state" value="{{ request('state') }}" placeholder="State">
                        <input type="date" class="form-control date-control" name="date_from" value="{{ request('date_from') }}" title="Date from">
                        <input type="date" class="form-control date-control" name="date_to" value="{{ request('date_to') }}" title="Date to">
                        <select class="form-select" name="status" style="min-width: 150px;">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('admin.campaign.list') }}" class="btn btn-outline-secondary">Reset</a>
                    </form>
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
                                        <th>{{\App\CPU\translate('earning amount')}}</th>
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
                                    @php($upiValue = (float) (\App\CPU\Helpers::get_business_settings('upi_value') ?? 0))
                                    @foreach($campaigns as $key=>$campaign)
                                    <tr id="data-{{$campaign->id}}">
                                        <td class="pl-xl-5">{{$campaigns->firstItem()+$key}}</td>
                                        <td>
                                            <img class="ratio-4:1" width="300" height="100"
                                                onerror="this.src='{{asset('assets/logo/logo-1.png')}}'"
                                                src="{{$campaign->thumbnail}}">
                                        </td>
                                        <td class="pl-xl-5">{{ optional($campaign->brand)->username ?? 'N/A' }}</td>
                                        <td class="pl-xl-5">{{$campaign->title}}</td>
                                        <td class="pl-xl-5">
                                            ₹ {{  @$campaign->reward_per_user }}
                                        </td>
                                        <td class="pl-xl-5">{{$campaign->start_date}}</td>
                                        <td class="pl-xl-5">{{$campaign->end_date}}</td>
                                        <td class="pl-xl-5">{{$campaign->city}}</td>
                                        <td class="pl-xl-5">{{$campaign->state}}</td>
                                        <td class="pl-xl-5">{{$campaign->gender}}</td>
                                        <td>
                                            <select class="form-select form-select-sm campaign-status-select" data-campaign-id="{{$campaign->id}}" style="min-width: 130px;">
                                                <option value="pending" {{ $campaign->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="active" {{ $campaign->status === 'active' ? 'selected' : '' }}>Active</option>
                                                <option value="live" {{ $campaign->status === 'live' ? 'selected' : '' }}>Live</option>
                                                <option value="accepted" {{ $campaign->status === 'accepted' ? 'selected' : '' }}>Accepted</option>
                                                <option value="paused" {{ $campaign->status === 'paused' ? 'selected' : '' }}>Paused</option>
                                                <option value="completed" {{ $campaign->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                                <option value="stopped" {{ $campaign->status === 'stopped' ? 'selected' : '' }}>Stopped</option>
                                                <option value="rejected" {{ $campaign->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                            </select>
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

                        @if($campaigns->hasPages())
                            <div class="premium-pagination-wrap">
                                <div class="premium-pagination-shell">
                                    <div class="premium-pagination-inline">
                                        {!! $campaigns->onEachSide(1)->links('vendor.pagination.premium') !!}
                                    </div>
                                </div>
                            </div>
                        @endif

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
<script src="{{asset('public/assets/back-end/js/toastr.js')}}"></script>
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

    // Handle campaign status dropdown change
    $(document).on('change', '.campaign-status-select', function() {
        var id = $(this).data('campaign-id');
        var status = $(this).val();
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        $.ajax({
            url: "{{route('admin.campaign.status')}}",
            method: 'POST',
            data: {
                id: id,
                status: status
            },
            success: function(response) {
                if (response.status) {
                    toastr.success(response.message);
                    // Reload the page to reflect changes
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    toastr.error(response.message || 'Failed to update campaign status');
                }
            },
            error: function(xhr) {
                toastr.error('Error updating campaign status');
                // Revert the select to previous value
                location.reload();
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