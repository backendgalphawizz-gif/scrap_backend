@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Scheme_Area'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 d-flex gap-10">
                <img src="{{asset('/public/assets/back-end/img/brand-setup.png')}}" alt="">
                {{\App\CPU\translate('Scheme_Area')}} {{\App\CPU\translate('Setup')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Content Row -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                        <form action="{{route('admin.scheme.area.store')}}" method="POST" >
                            @csrf
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="title-color" for="priority">{{ \App\CPU\translate('Select Scheme') }}</label>
                                        <select class="form-control" name="scheme_id" required>
                                            <option>Select Scheme</option>
                                            @foreach ($scheme as $key=>$value)
                                                <option value="{{ $value['id'] }}">{{ $value['title'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="title-color" for="priority">{{ \App\CPU\translate('Scheme area Title') }}</label>
                                        <input type="text" name="title" class="form-control" placeholder="{{\App\CPU\translate('enter Scheme Area Title')}}" required>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="title-color" for="priority">{{ \App\CPU\translate('Code') }}</label>
                                        <input type="text" name="code" class="form-control" placeholder="{{\App\CPU\translate('enter_area_code')}}">
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="d-flex flex-wrap gap-2 mt-4 justify">
                                        <button type="reset" id="reset" class="btn btn-secondary">{{\App\CPU\translate('reset')}}</button>
                                        <button type="submit" class="btn btn--primary">{{\App\CPU\translate('submit')}}</button>
                                    </div>
                                </div>
                                
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-20" id="cate-table">
            <div class="col-md-12">
                <div class="card">
                    <div class="px-3 py-4">
                        <div class="row align-items-center">
                            <div class="col-sm-4 col-md-6 col-lg-8 mb-2 mb-sm-0">
                                <h5 class="text-capitalize d-flex gap-1">
                                    {{ \App\CPU\translate('Scheme_Area_list')}}
                                    <span class="badge badge-soft-dark radius-50 fz-12">{{ $categories->total() }}</span>
                                </h5>
                            </div>
                            <div class="col-sm-8 col-md-6 col-lg-4">
                                <!-- Search -->
                                <form action="{{ url()->current() }}" method="GET">
                                    <div class="input-group input-group-custom input-group-merge">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="" type="search" name="search" class="form-control"
                                            placeholder="{{ \App\CPU\translate('search_here')}}" value="{{ $search }}" required>
                                        <button type="submit" class="btn btn--primary">{{\App\CPU\translate('search')}}</button>
                                    </div>
                                </form>
                                <!-- End Search -->
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                            class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                            <thead class="thead-light thead-50 text-capitalize">
                                <tr>
                                    <th>{{ \App\CPU\translate('ID')}}</th>
                                    <th>{{ \App\CPU\translate('Area')}}</th>
                                    <th>{{ \App\CPU\translate('Scheme_Area')}}</th>
                                    <th>{{ \App\CPU\translate('Code')}}</th>
                                    <!-- <th>{{ \App\CPU\translate('Status')}}</th> -->
                                    <th>{{ \App\CPU\translate('action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($categories as $key=>$category)
                                <tr>
                                    <td >{{1+$key}}</td>
                                   
                                    <td>{{$category['title']}}</td>
                                    <td>{{$category->scheme['title'] ?? '--'}}</td>
                                    <td>{{$category['code'] ?? '--'}}</td>
                                    
                                    <td>
                                        <div class="d-flex justify-content-center gap-10">
                                            <a class="btn btn-outline-info btn-sm square-btn"
                                                title="{{ \App\CPU\translate('Edit')}}" data-toggle="modal" data-target="#exampleModal{{$category['id']}}">
                                                <i class="tio-edit"></i>
                                            </a>

                                            <div class="modal fade" id="exampleModal{{$category['id']}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <form action="{{route('admin.scheme.area.update')}}" method="POST" >
                                                    <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Edit Area</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                            @csrf
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <input type="hidden" name="id" value="{{$category['id']}}">
                                                                    <div class="form-group">
                                                                        <label class="title-color" for="priority">{{\App\CPU\translate('Scheme')}}
                                                                        
                                                                        </label>
                                    
                                                                        <select class="form-control" name="scheme_id" required>
                                                                            <option>Select Scheme</option>
                                                                            @foreach ($scheme as $key=>$value)
                                                                                <option value="{{ $value['id'] }}" {{ ($value['id'] == $category['scheme_id']) ? 'selected' : '' }}>{{ $value['title'] }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                   
                                                                </div>
                                                                <div class="col-lg-12">
                                                                    <div class="form-group">
                                                                        <label class="title-color" for="priority">{{\App\CPU\translate('Area title')}}
                                                                        
                                                                        </label>
                                    
                                                                        <input type="text" name="title" class="form-control" value="{{$category['title']}}" required>
                                                                    </div>
                                                                   
                                                                </div>
                                                                <div class="col-lg-12">
                                                                    <div class="form-group">
                                                                        <label class="title-color" for="priority">{{\App\CPU\translate('code')}}
                                                                        
                                                                        </label>
                                    
                                                                        <input type="text" name="code" class="form-control" value="{{$category['code']}}">
                                                                    </div>
                                                                   
                                                                </div>
                                                                
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn--secondary" data-dismiss="modal">Close</button>

                                                            <button type="submit" class="btn btn--primary">Update Changes</button>
                                                        </div>
                                                    </div>
                                                </form>
                                                </div>
                                            </div>

                                            <a class="d-none btn btn-outline-danger btn-sm delete square-btn"
                                                title="{{ \App\CPU\translate('Delete')}}"
                                                id="{{$category['id']}}">
                                                <i class="tio-delete"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>


                    <div class="table-responsive mt-4">
                        <div class="d-flex justify-content-lg-end">
                            <!-- Pagination -->
                            {{$categories->links()}}
                        </div>
                    </div>
                    @if(count($categories)==0)
                        <div class="text-center p-4">
                            <img class="mb-3 w-160" src="{{asset('public/assets/back-end')}}/svg/illustrations/sorry.svg" alt="Image Description">
                            <p class="mb-0">{{\App\CPU\translate('no_data_found')}}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
<script>
    $(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
</script>
    
    <script>
        $(document).on('change', '.category-status', function () {
            var id = $(this).attr("id");
            if ($(this).prop("checked") == true) {
                var status = 1;
            } else if ($(this).prop("checked") == false) {
                var status = 0;
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.category.status')}}",
                method: 'POST',
                data: {
                    id: id,
                    home_status: status
                },
                success: function (data) {
                    if(data.success == true) {
                        toastr.success('{{\App\CPU\translate('Status updated successfully')}}');
                    }
                }
            });
        });
    </script>
    <script>
        $(document).on('click', '.delete', function () {
            var id = $(this).attr("id");
            Swal.fire({
                title: '{{\App\CPU\translate('Are_you_sure')}}?',
                text: "{{\App\CPU\translate('You_will_not_be_able_to_revert_this')}}!",
                showCancelButton: true,
                type: 'warning',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{\App\CPU\translate('Yes')}}, {{\App\CPU\translate('delete_it')}}!',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{route('admin.scheme.area.delete')}}",
                        method: 'POST',
                        data: {id: id},
                        success: function () {
                            toastr.success('{{\App\CPU\translate('Scheme_area_deleted_Successfully.')}}');
                            location.reload();
                        }
                    });
                }
            })
        });
    </script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function () {
    $('#city_id').select2({
        placeholder: "-- Select City --",
        minimumInputLength: 2, // start searching after 2 characters
        ajax: {
            url: "{{ route('admin.area.getCity') }}", 
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    search: params.term // search term
                };
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function (city) {
                        return {
                            id: city.id,
                            text: city.name
                        }
                    })
                };
            },
            cache: true
        }
    });
});
</script>
    
@endpush
