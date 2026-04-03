@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Coin Transactions'))

@push('css_or_js')

<meta name="_token" content="{{ csrf_token() }}">
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