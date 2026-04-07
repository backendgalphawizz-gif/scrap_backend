@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Tax Add'))

@push('css_or_js')
    <link href="{{ asset('public/assets/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{ asset('public/assets/back-end/css/custom.css')}}" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{asset('/public/assets/back-end/img/coupon_setup.png')}}" alt="">
                {{\App\CPU\translate('tax_setup')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Content Row -->
        <div class="row">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="card-body">
                        <form action="{{route('admin.taxes.store')}}" method="post">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 col-lg-4 form-group">
                                    <label for="name" class="title-color font-weight-medium d-flex">{{\App\CPU\translate('tax_title')}}</label>
                                    <input type="text" name="title" class="form-control" value="{{ old('title') }}" id="title"
                                           placeholder="{{\App\CPU\translate('Title')}}" required>
                                </div>
                                <div class="col-md-6 col-lg-4 form-group">
                                    <div class="d-flex justify-content-between">
                                        <label for="name" class="title-color font-weight-medium text-capitalize">{{\App\CPU\translate('tax_percent')}}</label>
                                    </div>
                                    <input type="number" min="1" max="100" name="percent" class="form-control" id="percent" placeholder="{{\App\CPU\translate('tax_percent')}}" required>
                                </div>

                                <div class="col-md-6 col-lg-4 form-group">
                                    <div class="d-flex justify-content-between">
                                        <label for="name" class="title-color font-weight-medium text-capitalize">{{\App\CPU\translate('status')}}</label>
                                    </div>
                                    <select name="status" class="form-control">
                                        <option value="">-- Status --</option>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                               
                            </div>

                            <div class="d-flex align-items-center justify-content-end flex-wrap gap-10">
                                <button type="reset" class="btn btn-secondary px-4">{{\App\CPU\translate('reset')}}</button>
                                <button type="submit" class="btn btn--primary px-4">{{\App\CPU\translate('Submit')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-20">
            <div class="col-md-12">
                <div class="card">
                    <div class="px-3 py-4">
                        <div class="row justify-content-between align-items-center flex-grow-1">
                            <div class="col-sm-4 col-md-6 col-lg-8 mb-2 mb-sm-0">
                                <h5 class="mb-0 text-capitalize d-flex gap-2">
                                    {{\App\CPU\translate('tax_list')}}
                                    <span class="badge badge-soft-dark radius-50 fz-12 ml-1">{{ $cou->total() }}</span>
                                </h5>
                            </div>
                            <div class="col-sm-8 col-md-6 col-lg-4">
                                <!-- Search -->
                                <form action="{{ url()->current() }}" method="GET">
                                    <div class="input-group input-group-merge input-group-custom">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="datatableSearch_" type="search" name="search" class="form-control"
                                               placeholder="{{\App\CPU\translate('Search by Title or Code or Discount Type')}}"
                                               value="{{ $search }}" aria-label="Search orders" required>
                                        <button type="submit" class="btn btn--primary">{{\App\CPU\translate('search')}}</button>
                                    </div>
                                </form>
                                <!-- End Search -->
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="datatable"
                                class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table {{ Session::get('direction') === 'rtl' ? 'text-right' : 'text-left' }}">
                            <thead class="thead-light thead-50 text-capitalize">
                            <tr>
                                <th>{{\App\CPU\translate('SL')}}</th>
                                <th>{{\App\CPU\translate('name')}}</th>
                                <th>{{\App\CPU\translate('Status')}}</th>
                                <th class="text-center">{{\App\CPU\translate('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($cou as $k=>$c)
                                <tr>
                                    <td >{{$cou->firstItem() + $k}}</td>
                                    <td>
                                        <div>{{substr($c['name'],0,20)}}</div>
                                        <strong>{{\App\CPU\translate('percent')}}: {{$c['percent']}}</strong>
                                    </td>
                                    <td>
                                        <label class="switcher">
                                            <input type="checkbox" class="switcher_input"
                                                    onclick="location.href='{{route('admin.taxes.status',[$c['id'],$c->status?0:1])}}'"
                                                    class="toggle-switch-input" {{$c->status?'checked':''}}>
                                            <span class="switcher_control"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-10 justify-content-center">
                                            <button class="btn btn-outline--primary square-btn btn-sm mr-1" onclick="get_details(this)" data-id="{{ $c['id'] }}" data-toggle="modal" data-target="#exampleModalCenter">
                                                <img src="{{asset('/public/assets/back-end/img/eye.svg')}}" class="svg" alt="">
                                            </button>
                                            <a class="btn btn-outline--primary btn-sm edit"
                                            href="{{route('admin.taxes.update',[$c['id']])}}"
                                            title="{{ \App\CPU\translate('Edit')}}"
                                            >
                                                <i class="tio-edit"></i>
                                            </a>
                                            <a class="btn btn-outline-danger btn-sm delete"
                                                href="javascript:"
                                                onclick="form_alert('coupon-{{$c['id']}}','Want to delete this coupon ?')"
                                                title="{{\App\CPU\translate('delete')}}"
                                                >
                                                <i class="tio-delete"></i>
                                            </a>
                                            <form action="{{route('admin.taxes.delete',[$c['id']])}}"
                                                method="post" id="coupon-{{$c['id']}}">
                                                @csrf @method('delete')
                                            </form>
                                        </div>

                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="modal fade" id="quick-view" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered coupon-details" role="document">
                                <div class="modal-content" id="quick-view-modal">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive mt-4">
                        <div class="px-4 d-flex justify-content-lg-end">
                            <!-- Pagination -->
                            {{$cou->links()}}
                        </div>
                    </div>

                    @if(count($cou)==0)
                        <div class="text-center p-4">
                            <img class="mb-3 w-160" src="{{asset('public/assets/back-end')}}/svg/illustrations/sorry.svg"
                                 alt="Image Description">
                            <p class="mb-0">{{\App\CPU\translate('No data to show')}}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
<script>

    $(document).ready(function() {
            generateCode();

            $('#discount_percent').hide();
            let discount_type = $('#discount_type').val();
            if (discount_type == 'amount') {
                $('#max-discount').hide()
            } else if (discount_type == 'percentage') {
                $('#max-discount').show()
            }

            $('#start_date').attr('min',(new Date()).toISOString().split('T')[0]);
            $('#expire_date').attr('min',(new Date()).toISOString().split('T')[0]);
        });

        $("#start_date").on("change", function () {
            $('#expire_date').attr('min',$(this).val());
        });

        $("#expire_date").on("change", function () {
            $('#start_date').attr('max',$(this).val());
        });

        function get_details(t){
            let id = $(t).data('id')

            $.ajax({
                type: 'GET',
                url: '{{route('admin.taxes.quick-view-details')}}',
                data: {
                    id: id
                },
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    $('#loading').hide();
                    $('#quick-view').modal('show');
                    $('#quick-view-modal').empty().html(data.view);
                }
            });
        }

        function checkDiscountType(val) {
            if (val == 'amount') {
                $('#max-discount').hide()
            } else if (val == 'percentage') {
                $('#max-discount').show()
            }
        }

        function  generateCode(){
            let code = Math.random().toString(36).substring(2,12);
            $('#code').val(code)
        }


</script>

    <script src="{{asset('public/assets/back-end')}}/js/select2.min.js"></script>
    <script>
        $(".js-example-theme-single").select2({
            theme: "classic"
        });

        $(".js-example-responsive").select2({
            width: 'resolve'
        });
    </script>

    <!-- Page level plugins -->
    <script src="{{asset('public/assets/back-end')}}/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="{{asset('public/assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="{{asset('public/assets/back-end')}}/js/demo/datatables-demo.js"></script>

<script>
    $('#discount_type').on('change', function (){
        let type = $('#discount_type').val();
        if(type === 'amount'){
            $('#discount').attr({
                'placeholder': 'Ex: 500',
                "max":"1000000"
            });
            $('#discount_percent').hide();
        }else if(type === 'percentage'){
            $('#discount').attr({
                "max":"100",
                "placeholder":"Ex: 10%"
            });
            $('#discount_percent').show();
        }
    });
    $('#coupon_bearer').on('change', function (){
        let coupon_bearer = $('#coupon_bearer').val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $.ajax({
            type: 'POST',
            url: '{{route('admin.taxes.ajax-get-seller')}}',
            data: {
                coupon_bearer: coupon_bearer
            },
            success: function (result) {
                $("#seller_wise_coupon").html(result);
            }
        });
    });

    $('#coupon_type').on('change', function (){
        let discount_type = $('#discount_type').val();
        let type = $('#coupon_type').val();

        if(type === 'free_delivery'){
            if (discount_type === 'amount') {
                $('.first_order').show();
                $('.free_delivery').hide();
            } else if (discount_type === 'percentage') {
                $('.first_order').show();
                $('.free_delivery').hide();
            }
        }else if(type === 'first_order'){
            if (discount_type === 'amount') {
                $('.free_delivery').show();
                $('.first_order').hide();
                $('#max-discount').hide()
            } else if (discount_type === 'percentage') {
                $('.free_delivery').show();
                $('.first_order').hide();
                $('#max-discount').show()
            }
        }else{
            if (discount_type === 'amount') {
                $('.first_order').show();
                $('.free_delivery').show();
                $('#max-discount').hide()
            } else if (discount_type === 'percentage') {
                $('.first_order').show();
                $('.free_delivery').show();
                $('#max-discount').show()
            }
        }
    });
</script>
@endpush
