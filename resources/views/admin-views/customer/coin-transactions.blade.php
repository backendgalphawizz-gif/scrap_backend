@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Coin Transactions'))

@push('css_or_js')

<meta name="_token" content="{{ csrf_token() }}">
<style>
    .wallet-filter-scroll {
        overflow-x: auto;
        overflow-y: hidden;
        padding-bottom: 4px;
    }

    .wallet-filter-form {
        flex-wrap: nowrap !important;
        min-width: max-content;
    }

    .wallet-filter-form .form-control {
        min-width: 135px;
    }

    .wallet-filter-form .search-control {
        min-width: 260px;
    }

    .wallet-filter-form .transaction-type-control {
        min-width: 180px;
    }

    .wallet-filter-form .date-control {
        min-width: 160px;
    }

    .wallet-filter-form .btn {
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
            </span> Coin Transactions
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <span></span>Coin Transactions <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                </li>
            </ul>
        </nav>
    </div>

    <div class="d-flex justify-content-end mb-3">
        <div class="wallet-filter-scroll">
            <form method="GET" action="{{ route('admin.user.wallet') }}" class="wallet-filter-form d-flex align-items-center gap-2">
                <input type="text" class="form-control search-control" name="search" value="{{ request('search') }}" placeholder="Search user, transaction ID, remark">

                <select class="form-select" name="status">
                    <option value="">All status</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                </select>

                <select class="form-select" name="type">
                    <option value="">All type</option>
                    <option value="debit" {{ request('type') === 'debit' ? 'selected' : '' }}>Debit</option>
                    <option value="credit" {{ request('type') === 'credit' ? 'selected' : '' }}>Credit</option>
                </select>

                <input type="text" class="form-control transaction-type-control" name="transaction_type" value="{{ request('transaction_type') }}" placeholder="Transaction type">
                <input type="date" class="form-control date-control" name="date_from" value="{{ request('date_from') }}" title="From date">
                <input type="date" class="form-control date-control" name="date_to" value="{{ request('date_to') }}" title="To date">

                <button type="submit" class="btn btn-primary px-4">Filter</button>
                <a href="{{ route('admin.user.wallet') }}" class="btn btn-outline-secondary px-4">Reset</a>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="table-responsive">
                    <table class="table">
                        <thead class="text-capitalize">
                            <tr>
                                <th>{{\App\CPU\translate('Transaction ID')}}</th>
                                <th>{{\App\CPU\translate('User')}}</th>
                                <th>{{\App\CPU\translate('Coins')}}</th>
                                <th>{{\App\CPU\translate('Type')}}</th>
                                <th>{{\App\CPU\translate('Remarks')}}</th>
                                <th>{{\App\CPU\translate('Transaction Type')}}</th>
                                <th>{{\App\CPU\translate('Value')}}</th>
                                <th>{{\App\CPU\translate('Date')}}</th>
                                <th>{{\App\CPU\translate('Status')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $transaction)
                            <tr>
                                <td>{{ $transaction->transaction_id }}</td>
                                <td>{{ $transaction->wallet->user->name ?? 'N/A' }}</td>
                                <td>{{ $transaction->coin }}</td>
                                <td>{{ $transaction->type }}</td>
                                <td>{{ $transaction->description }}</td>
                                <td>{{ $transaction->transaction_type ?? '-' }}</td>
                                <td>{{ $transaction->value ?? '-' }}</td>
                                <td>{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <span class="badge badge-{{ $transaction->status == 'completed' ? 'gradient-success' : 'gradient-danger' }}">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">{{\App\CPU\translate('No transactions found')}}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($transactions->count() > 0)
                <nav>
                    {{ $transactions->links() }}
                </nav>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('script_2')

<script>
    $(document).on('click', '.update-wallet-status', function() {
        let id = $(this).attr("data-id");

        let status = 0;
        if (jQuery(this).prop("checked") === true) {
            status = 1;
        }

        Swal.fire({
            title: '{{\App\CPU\translate('
            Are you sure ')}}?',
            text: '{{\App\CPU\translate('
            want_to_change_status ')}}',
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
                    url: "{{ route('admin.user.update-wallet-status') }}",
                    method: 'POST',
                    data: {
                        id: id,
                        status: status
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status) {
                            swal.fire('', '{{\App\CPU\translate('
                                Status updated successfully ')}}', 'success').then((result) => {
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

    $(document).on('click', '.update-account-status', function() {
        let id = $(this).attr("data-id");

        let status = 0;
        if (jQuery(this).prop("checked") === true) {
            status = 1;
        }

        Swal.fire({
            title: '{{\App\CPU\translate('
            Are you sure ')}}?',
            text: '{{\App\CPU\translate('
            want_to_change_status ')}}',
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
                    url: "{{ route('admin.user.update-user-status') }}",
                    method: 'POST',
                    data: {
                        id: id,
                        status: status
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status) {
                            swal.fire('', '{{\App\CPU\translate('
                                Status updated successfully ')}}', 'success').then((result) => {
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