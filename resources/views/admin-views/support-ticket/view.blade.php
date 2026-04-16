@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('Support Ticket'))

@push('css_or_js')
<!-- Custom styles for this page -->
<link href="{{asset('public/assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
<link href="{{asset('public/assets/back-end/css/croppie.css')}}" rel="stylesheet">
<style>
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
                <i class="mdi mdi-chat"></i>
            </span> {{\App\CPU\translate('support_ticket')}} ({{ $tickets->total() }})
        </h3>
    </div>

    <div class="row mt-20">
        <div class="col-md-12">
            <div class="">
                <div class="mb-2 border-bottom">
                    <div class="d-flex flex-wrap justify-content-between gap-3 align-items-center">
                        <div class="w-100">
                            <!-- Search Form -->
                            <form action="{{ url()->current() }}" method="GET" id="ticketFilterForm">
                                <div class="input-group input-group-merge input-group-custom">

                                    <!-- Search Input -->
                                    <input type="search" name="search" class="form-control"
                                        value="{{ request('search') }}"
                                        placeholder="Search Ticket by Subject or status..."
                                        oninput="if(this.value===''){document.getElementById('ticketFilterForm').submit();}">

                                    <!-- Status Dropdown -->
                                    <select class="form-control border-color-c1 w-160 form-select" name="status"
                                        onchange="document.getElementById('ticketFilterForm').submit()">
                                        <option value="all" {{ request('status','all')=='all'?'selected':'' }}>All Status</option>
                                        <option value="open" {{ request('status')=='open'?'selected':'' }}>Open</option>
                                        <option value="closed" {{ request('status')=='closed'?'selected':'' }}>Close</option>
                                    </select>

                                    <!-- Submit Button -->
                                    <button type="submit" class="btn btn--primary">Search</button>
                                </div>
                            </form>
                            <!-- End Search Form -->
                        </div>

                        <!-- <div class="">
                            <div class="d-flex flex-wrap flex-sm-nowrap gap-3 justify-content-end">
                                @php($status = request()->get('status','all'))
                                <select class="form-control border-color-c1 w-160 form-select"
                                    onchange="filter_tickets(this.value)">
                                    <option value="all" {{$status=='all'?'selected':''}}>{{\App\CPU\translate('All_Status')}}</option>
                                    <option value="open" {{$status=='open'?'selected':''}}>{{\App\CPU\translate('Open')}}</option>
                                    <option value="close" {{$status=='close'?'selected':''}}>{{\App\CPU\translate('Close')}}</option>
                                </select>
                            </div>
                        </div> -->
                    </div>
                </div>

                <!-- Tickets Listing -->
                @foreach($tickets as $key =>$ticket)
                <div class="border-bottom mb-2 pb-2">
                    <div class="card">
                        <div class="card-body align-items-center d-flex flex-wrap justify-content-between gap-3 border-bottom">
                            <div class="media gap-3">
                                <img class="rounded-circle avatar" style="width:50px; height:50px; object-fit:cover;"
                                    src="{{ 
        $ticket->user && $ticket->user->image
            ? asset($ticket->user->image)
            : ($ticket->seller && $ticket->seller->image
                ? asset($ticket->seller->image)
                : 'https://media.istockphoto.com/id/2171382633/vector/user-profile-icon-anonymous-person-symbol-blank-avatar-graphic-vector-illustration.jpg?s=612x612&w=0&k=20&c=ZwOF6NfOR0zhYC44xOX06ryIPAUhDvAajrPsaZ6v1-w='
            )
    }}"
                                    onerror="this.src='https://media.istockphoto.com/id/2171382633/vector/user-profile-icon-anonymous-person-symbol-blank-avatar-graphic-vector-illustration.jpg?s=612x612&w=0&k=20&c=ZwOF6NfOR0zhYC44xOX06ryIPAUhDvAajrPsaZ6v1-w='"
                                    alt="User Avatar">
                                <div class="media-body">
                                    <h6 class="mb-0 {{Session::get('direction') === "rtl" ? 'text-right' : 'text-left'}}">{{$ticket->user->name ?? $ticket->seller->f_name}}</h6>
                                    <div class="mb-2 fz-12 {{Session::get('direction') === "rtl" ? 'text-right' : 'text-left'}}">{{$ticket->user->mobile ?? $ticket->seller->phone}}</div>
                                    <div class="d-flex flex-wrap gap-2 align-items-center">
                                        <span class="badge-soft-info fz-12 font-weight-bold radius-50">{{\App\CPU\translate(str_replace('_',' ',$ticket->status))}}</span>
                                        <h6 class="mb-0">{{\App\CPU\translate(str_replace('_',' ',$ticket->type))}}</h6>
                                        <div class="text-nowrap {{Session::get('direction') === "rtl" ? 'pr-9' : 'pl-9'}}">
                                            <!-- {{date('d/M/Y H:i a',strtotime($ticket->created_at))}} -->
                                            {{ $ticket->created_at->timezone('Asia/Kolkata')->format('d/M/Y h:i A') }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">

                            <!-- Subject -->
                            <div class="text-truncate w-75">
                                {{$ticket->subject}}
                            </div>

                            <!-- Button -->
                            <div class="w-auto text-end d-flex gap-2 justify-content-end align-items-center">

                                <a class="btn btn--primary btn-sm py-2 px-3"
                                    href="{{ route('admin.support-ticket.singleTicket', $ticket->id) }}">
                                    <i class="tio-open-in-new"></i> View
                                </a>


                                @if($ticket->status != 'closed')
                                <form action="{{ route('admin.support-ticket.close', $ticket->id) }}" method="POST" class="m-0 ticket-close-form">
                                    @csrf
                                    <button type="button" class="btn btn-danger btn-sm py-2 px-3 close-btn">
                                        Close Ticket
                                    </button>
                                </form>
                                @else
                                <span class="btn btn-success btn-sm py-2 px-3 border-0">Closed</span>
                                @endif

                            </div>

                        </div>
                    </div>
                </div>
                @endforeach

                @if($tickets->hasPages())
                <div class="premium-pagination-wrap">
                    <div class="premium-pagination-shell">
                        <div class="premium-pagination-inline">
                            {!! $tickets->onEachSide(1)->links('vendor.pagination.premium') !!}
                        </div>
                    </div>
                </div>
                @endif

                <!-- No Data -->
                @if(count($tickets) == 0)
                <div class="text-center p-4">
                    <p class="mb-0">{{\App\CPU\translate('No data found')}}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<!-- Page level plugins -->
<script src="{{asset('public/assets/back-end')}}/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="{{asset('public/assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.js"></script>

<script>
    // Status Filter with search preservation
    function filter_tickets(status) {
        let url = new URL(window.location.href);
        let searchParams = url.searchParams;

        // Preserve the current search term
        let searchValue = document.querySelector('input[name="search"]').value;
        searchParams.set('status', status);
        searchParams.set('search', searchValue);

        // Redirect
        window.location.href = url.pathname + '?' + searchParams.toString();
    }
</script>


<!-- Croppie -->
<script src="{{asset('public/assets/back-end/js/croppie.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {

        document.querySelectorAll(".close-btn").forEach(function(button) {

            button.addEventListener("click", function() {

                let form = this.closest("form");

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to close this ticket!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: 'rgba(5, 44, 78, 1)',
                    cancelButtonColor: '#c82333',
                    confirmButtonText: 'Yes, close it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });

            });

        });

    });
</script>
@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: "{{ session('success') }}",
        timer: 2000,
        showConfirmButton: false
    });
</script>
@endif
@endpush


<style>
    @media (max-width: 768px) {
        #ticketFilterForm .input-group {
            /* flex-direction: column; */
            gap: 0.5rem;
        }

        #ticketFilterForm .form-control,
        #ticketFilterForm .form-select,
        #ticketFilterForm .btn {
            width: 100% !important;
        }
    }

    @media (max-width: 767px) {
        .ticket-filter-mobile {
            width: 100%;
        }

        .ticket-filter-mobile .input-group {
            /* flex-direction: column; */
            width: 100%;
        }

        .ticket-filter-mobile input,
        .ticket-filter-mobile select,
        .ticket-filter-mobile button {
            width: 100% !important;
            margin-top: 8px;
        }

        .ticket-filter-mobile select:first-of-type,
        .ticket-filter-mobile button:first-of-type {
            margin-top: 8px;
        }
    }
</style>