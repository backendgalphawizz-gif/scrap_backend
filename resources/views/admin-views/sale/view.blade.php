@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Sale List'))

@push('css_or_js')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-cash-multiple"></i>
            </span> {{\App\CPU\translate('Sales List')}}
        </h3>

        <div id="banner-btn">
            <a href="{{ route('admin.sale.add') }}" class="btn btn-primary text-nowrap">
                <i class="tio-add"></i>
                {{ \App\CPU\translate('add_Sale')}}
            </a>
        </div>



        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <span></span>{{\App\CPU\translate('Sale List')}} <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                </li>
            </ul>
        </nav>
    </div>
    <div class="row">
        <div class="col-lg-12">

            <div class="row" id="banner-table">
                <div class="col-md-12">
                    <div class="card">
                        <div class="table-responsive">
                            <table id="columnSearchDatatable"
                                class="table">
                                <thead class="text-capitalize">
                                    <tr>
                                        <th class="pl-xl-5">{{\App\CPU\translate('SL')}}</th>
                                        <th>{{\App\CPU\translate('image')}}</th>
                                        <th>{{\App\CPU\translate('name')}}</th>
                                        <th>{{\App\CPU\translate('email')}}</th>
                                        <th>{{\App\CPU\translate('mobile')}}</th>
                                        <th>{{\App\CPU\translate('brands')}}</th>
                                        <th>{{\App\CPU\translate('campaigns')}}</th>
                                        <th>{{\App\CPU\translate('published')}}</th>
                                        <th class="text-center">{{\App\CPU\translate('action')}}</th>
                                    </tr>
                                </thead>
                                @foreach($sales as $key=>$sale)
                                <tbody>
                                    <tr id="data-{{$sale->id}}">
                                        <td class="pl-xl-5">{{$sales->firstItem()+$key}}</td>
                                        <td>
                                            <img class="ratio-4:1" width="300" height="100"
                                                onerror="this.src='{{asset('assets/logo/logo-1.png')}}'"
                                                src="{{$sale->image}}">
                                        </td>
                                        <td class="pl-xl-5">{{$sale->name}}</td>
                                        <td class="pl-xl-5">{{$sale->email}}</td>
                                        <td class="pl-xl-5">{{$sale->mobile}}</td>
                                        <td class="pl-xl-5">{{$sale->brand_count}}</td>
                                        <td class="pl-xl-5">{{$sale->campaign_count}}</td>
                                        <td>
                                            <label class="switcher">
                                                <input type="checkbox" class="switcher_input status"
                                                    id="{{$sale->id}}" <?php if ($sale->status == 'active') echo "checked" ?>>
                                                <span class="switcher_control"></span>
                                            </label>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2 justify-content-center">
                                                <a class="btn btn-outline-info btn-sm cursor-pointer edit"
                                                    title="{{ \App\CPU\translate('Edit')}}"
                                                    href="{{route('admin.sale.show',[$sale['id']])}}">
                                                    View
                                                </a>
                                                <a class="btn btn-outline-primary btn-sm cursor-pointer edit"
                                                    title="{{ \App\CPU\translate('Edit')}}"
                                                    href="{{route('admin.sale.edit',[$sale['id']])}}">
                                                    Edit
                                                </a>
                                                <a class="btn btn-outline-danger btn-sm cursor-pointer delete"
                                                    title="{{ \App\CPU\translate('Delete')}}"
                                                    id="{{$sale['id']}}">
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
                                {{$sales->links()}}
                            </div>
                        </div>

                        @if(count($sales)==0)
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
            url: "{{route('admin.sale.status')}}",
            method: 'POST',
            data: {
                id: id,
                status: status
            },
            success: function(data) {
                if (data == 1) {
                    swal.fire('', '{{ \App\CPU\translate('
                        sale user active successfully!')}}', 'success')
                } else {
                    swal.fire('', '{{ \App\CPU\translate('
                        sale user inactive successfully!')}}', 'success')
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
                    url: "{{route('admin.sale.delete')}}",
                    method: 'POST',
                    data: {
                        id: id
                    },
                    success: function() {
                        $('#data-' + id).remove();
                        swal.fire('', '{{ \App\CPU\translate('
                            sale user deleted successfully!')}}', 'success');
                    }
                });
            }
        })
    });
</script>
@endpush