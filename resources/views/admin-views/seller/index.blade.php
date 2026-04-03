@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Seller List'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-store menu-icon"></i>
                </span> Brands
            </h3>
            <nav aria-label="breadcrumb">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">
                        <span></span>Brands <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <table class="table">
                        <thead class="text-capitalize">
                        <tr>
                            <th>{{\App\CPU\translate('SL')}}</th>
                            <th>{{\App\CPU\translate('Brand Name')}}</th>
                            <th>{{\App\CPU\translate('Name')}}</th>
                            <th>{{\App\CPU\translate('contact_info')}}</th>
                            <th>{{\App\CPU\translate('Registered date')}}</th>
                            <th>{{\App\CPU\translate('status')}}</th>
                            <th class="text-center">{{\App\CPU\translate('total_campaigns')}}</th>
                            <th class="text-center">{{\App\CPU\translate('action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($sellers as $key => $seller)
                            <tr>
                                <td>{{$sellers->firstItem()+$key}}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-10 w-max-content">
                                        <!-- onerror="this.src='{{asset('public/assets/back-end/img/400x400/img2.jpg')}}'" -->
                                        <img width="50"
                                        class="avatar rounded-circle"
                                            
                                            src="{{$seller->image}}"
                                            alt="">
                                        <div>
                                            <a class="title-color" href="#">{{ \Str::limit($seller->username, 20)}}</a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a title="{{\App\CPU\translate('View')}}"
                                        class="title-color"
                                        href="#">
                                        {{$seller->f_name}} {{$seller->l_name}}
                                    </a>
                                </td>
                                
                                
                                <td>
                                    <div class="mb-1">
                                        <strong><a class="title-color hover-c1" href="mailto:{{$seller->email}}">{{$seller->email}}</a></strong>
                                    </div>
                                    <a class="title-color hover-c1" href="tel:{{$seller->phone}}">{{$seller->phone}}</a>
                                </td>
                                <td>
                                    <a href="javascript:void(0)" class="title-color hover-c1">{{date('d M, Y', strtotime($seller->created_at))}}</a>
                                </td>
                                <td>
                                    <span class="update-account-status" data-id="{{ $seller->id }}">{!! $seller->status=='approved'?'<label class="badge badge-gradient-success">'.\App\CPU\translate('Active').'</label>':'<label class="badge badge-gradient-danger">'.\App\CPU\translate('In-Active').'</label>' !!}</span>
                                </td>
                                <td class="text-center">
                                    <a href="#"
                                        class="btn text--primary bg-soft--primary font-weight-bold px-3 py-1 mb-0 fz-12">
                                        {{ $seller->campaign?->count() ?? 0 }}
                                    </a>
                                </td>
                                <td class="actiondiv">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a title="{{\App\CPU\translate('View')}}"
                                            class="btn btn-outline-info btn-sm square-btn"
                                            href="{{ route('admin.brand.view', $seller->id) }}">
                                            View
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive mt-4">
                    <div class="px-4 d-flex justify-content-center justify-content-md-end">
                        <!-- Pagination -->
                        {!! $sellers->links() !!}
                    </div>
                </div>

                @if(count($sellers)==0)
                    <div class="text-center p-4">
                        <img class="mb-3 w-160" src="{{asset('public/assets/back-end')}}/svg/illustrations/sorry.svg" alt="Image Description">
                        <p class="mb-0">{{\App\CPU\translate('No data to show')}}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('script_2')

    <script>
        $(document).on('click', '.update-account-status', function () {
            let id = $(this).attr("data-id");

            let status = 0;
            if (jQuery(this).prop("checked") === true) {
                status = 1;
            }

            Swal.fire({
                title: '{{\App\CPU\translate('Are you sure')}}?',
                text: '{{\App\CPU\translate('want_to_change_status')}}',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'No',
                confirmButtonText: 'Yes',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{ route('admin.seller.update-account-status') }}",
                        method: 'POST',
                        data: {
                            id: id,
                            status: status
                        },
                        dataType:'json',
                        success: function (response) {
                            if(response.status){
                                swal.fire('', '{{\App\CPU\translate('Status updated successfully')}}', 'success').then((result) => {
                                    location.reload();
                                });
                            } else {
                                swal.fire('', response.message, 'error');
                            }
                        }
                    });
                }
            })
        });
    </script>

@endpush


