@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Ledger Transactions'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .ledger-filter-scroll {
            overflow-x: auto;
            overflow-y: hidden;
            padding-bottom: 4px;
        }

        .ledger-filter-form {
            flex-wrap: nowrap !important;
            min-width: max-content;
        }

        .ledger-filter-form .form-control,
        .ledger-filter-form .form-select {
            min-width: 140px;
        }

        .ledger-filter-form .search-control {
            min-width: 260px;
        }

        .ledger-filter-form .amount-control,
        .ledger-filter-form .date-control {
            min-width: 150px;
        }

        .ledger-filter-form .btn {
            height: 38px;
            white-space: nowrap;
        }
    </style>
@endpush

@section('content')

<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-cash-multiple"></i>
            </span> {{\App\CPU\translate('Ledger Transactions')}}
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <span></span>{{\App\CPU\translate('Ledger Transactions')}} <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                </li>
            </ul>
        </nav>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-3">
                        <div class="card-body border-bottom">
                            <div class="ledger-filter-scroll">
                                <form method="GET" action="{{ route('admin.sale.ledger-transactions') }}" class="ledger-filter-form d-flex align-items-center gap-2">
                                    <input type="text" class="form-control search-control" name="search" value="{{ request('search') }}" placeholder="Search sale, brand, campaign, reference, ID">

                                    <select class="form-select" name="status">
                                        <option value="">All status</option>
                                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>

                                    <select class="form-select" name="reference_type">
                                        <option value="">All reference</option>
                                        <option value="campaign_budget" {{ request('reference_type') === 'campaign_budget' ? 'selected' : '' }}>Campaign Budget</option>
                                        <option value="brand_budget" {{ request('reference_type') === 'brand_budget' ? 'selected' : '' }}>Brand Budget</option>
                                    </select>

                                    <input type="number" step="0.01" min="0" class="form-control amount-control" name="amount_min" value="{{ request('amount_min') }}" placeholder="Min budget">
                                    <input type="number" step="0.01" min="0" class="form-control amount-control" name="amount_max" value="{{ request('amount_max') }}" placeholder="Max budget">

                                    <input type="number" step="0.01" min="0" class="form-control amount-control" name="commission_min" value="{{ request('commission_min') }}" placeholder="Min commission">
                                    <input type="number" step="0.01" min="0" class="form-control amount-control" name="commission_max" value="{{ request('commission_max') }}" placeholder="Max commission">

                                    <input type="date" class="form-control date-control" name="date_from" value="{{ request('date_from') }}" title="From date">
                                    <input type="date" class="form-control date-control" name="date_to" value="{{ request('date_to') }}" title="To date">

                                    <button type="submit" class="btn btn-primary px-4">Filter</button>
                                    <a href="{{ route('admin.sale.ledger-transactions') }}" class="btn btn-outline-secondary px-4">Reset</a>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <thead class="text-capitalize">
                                <tr>
                                    <th>{{ \App\CPU\translate('SL')}}</th>
                                    <th>{{ \App\CPU\translate('Sale Username')}}</th>
                                    <th>{{ \App\CPU\translate('Brand Name')}}</th>
                                    <th>{{ \App\CPU\translate('Campaign')}}</th>
                                    <th>{{ \App\CPU\translate('Campaign Budget')}}</th>
                                    <th>{{ \App\CPU\translate('Commission Rate')}}</th>
                                    <th>{{ \App\CPU\translate('Commission Amount')}}</th>
                                    <th>{{ \App\CPU\translate('Reference_type')}}</th>
                                    <th>{{ \App\CPU\translate('Status')}}</th>
                                    <th>{{ \App\CPU\translate('Date')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $key => $txn)
                                    <tr>
                                        <td>{{ $transactions->firstItem() + $key }}</td>
                                        <td>{{ ucwords($txn->sale->name ?? '') }}</td>
                                        <td>{{ ucwords($txn->brand->username ?? '') }}</td>
                                        <td>{{ ucwords($txn->campaign->title ?? '') }}</td>
                                        <td>{{ $txn->amount ?? '' }}</td>
                                        <td>{{ $txn->commission_rate ?? '' }}</td>
                                        <td>{{ $txn->commission_amount ?? '' }}</td>
                                        <td>{{ $txn->reference_type ?? '' }}</td>
                                        <td>
                                            @if($txn->status == 'pending')
                                                <button type="button" class="btn btn-gradient-primary btn-sm update-ledger-status" data-id="{{ $txn->id }}" data-status="approved">Approve</button>
                                                <button type="button" class="btn btn-gradient-danger btn-sm update-ledger-status" data-id="{{ $txn->id }}" data-status="rejected">Reject</button>
                                            @endif
                                            @if($txn->status == 'approved')
                                                <span class="badge bg-gradient-success">Approved</span>
                                            @endif
                                            @if($txn->status == 'rejected')
                                                <span class="badge bg-gradient-danger">Rejected</span>
                                            @endif
                                        </td>
                                        
                                        <td>{{ date('d M, Y', strtotime($txn->created_at)) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">
                                            {{ \App\CPU\translate('No transactions found')}}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end">
                        {{ $transactions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
        <script>
            $('#mbimageFileUploader').change(function () {
                readURL(this);
            });
    
            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
    
                    reader.onload = function (e) {
                        $('#mbImageviewer').attr('src', e.target.result);
                    }
    
                    reader.readAsDataURL(input.files[0]);
                }
            }
            $(document).on('click', '.update-ledger-status', function () {
                var id = $(this).attr("data-id");
                var status = $(this).attr("data-status");
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{route('admin.sale.update-ledger-transactions-status')}}",
                    method: 'POST',
                    data: {
                        id: id,
                        status: status
                    },
                    success: function (response) {
                        if (response.status) {
                            swal.fire('', '{{ \App\CPU\translate('Status updated successfully!')}}', 'success');
                        } else {
                            swal.fire('', '{{ \App\CPU\translate('Something went wrong!')}}', 'error');
                        }
                    }
                });
            });
        </script>
@endpush
