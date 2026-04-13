@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Wallet Transactions'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

        .wallet-filter-form .form-control,
        .wallet-filter-form .form-select {
            min-width: 135px;
        }

        .wallet-filter-form .search-control {
            min-width: 240px;
        }

        .wallet-filter-form .amount-control,
        .wallet-filter-form .date-control {
            min-width: 155px;
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
            </span> {{\App\CPU\translate('Wallet Transactions')}}
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <span></span>{{\App\CPU\translate('Wallet Transactions')}} <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                </li>
            </ul>
        </nav>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="row ">
                <div class="col-12">
                    <div class="card mb-3">
                        <div class="card-body border-bottom">
                            <div class="wallet-filter-scroll">
                                <form method="GET" action="{{ route('admin.sale.wallet-transactions') }}" class="wallet-filter-form d-flex align-items-center gap-2">
                                    <input type="text" class="form-control search-control" name="search" value="{{ request('search') }}" placeholder="Search sale, email, mobile, remarks, ID">

                                    <select class="form-select" name="status">
                                        <option value="">All status</option>
                                        <option value="success" {{ request('status') === 'success' ? 'selected' : '' }}>Success</option>
                                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                                    </select>

                                    <select class="form-select" name="type">
                                        <option value="">All type</option>
                                        <option value="credit" {{ request('type') === 'credit' ? 'selected' : '' }}>Credit</option>
                                        <option value="debit" {{ request('type') === 'debit' ? 'selected' : '' }}>Debit</option>
                                    </select>

                                    <input type="number" step="0.01" min="0" class="form-control amount-control" name="amount_min" value="{{ request('amount_min') }}" placeholder="Min amount">
                                    <input type="number" step="0.01" min="0" class="form-control amount-control" name="amount_max" value="{{ request('amount_max') }}" placeholder="Max amount">

                                    <input type="date" class="form-control date-control" name="date_from" value="{{ request('date_from') }}" title="From date">
                                    <input type="date" class="form-control date-control" name="date_to" value="{{ request('date_to') }}" title="To date">

                                    <button type="submit" class="btn btn-primary px-4">Filter</button>
                                    <a href="{{ route('admin.sale.wallet-transactions') }}" class="btn btn-outline-secondary px-4">Reset</a>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <thead class="text-capitalize">
                                <tr>
                                    <th>{{ \App\CPU\translate('SL')}}</th>
                                    <th>{{ \App\CPU\translate('Name')}}</th>
                                    <th>{{ \App\CPU\translate('Amount')}}</th>
                                    <th>{{ \App\CPU\translate('Type')}}</th>
                                    <th>{{ \App\CPU\translate('Remarks')}}</th>
                                    <th>{{ \App\CPU\translate('Status')}}</th>
                                    <th>{{ \App\CPU\translate('Date')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $key => $txn)
                                    <tr>
                                        <td>{{ $transactions->firstItem() + $key }}</td>
                                        <td>{{ $txn->sale->name ?? '' }}</td>
                                        <td>{{ $txn->amount ?? '' }}</td>
                                        <td>{{ ucfirst($txn->type ?? '-') }}</td>
                                        <td>{{ $txn->remarks ?? '' }}</td>
                                        <td>
                                            <span class="badge badge-{{ $txn->status == 'success' ? 'gradient-success' : 'gradient-danger' }}">
                                            {{ $txn->status }}</span>
                                        </td>
                                        <td>{{ date('d M, Y', strtotime($txn->created_at)) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">
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
            $(document).on('change', '.status', function () {
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
                    success: function (data) {
                        if (data == 1) {
                            toastr.success('{{ \App\CPU\translate('Banner published successfully!')}}');
                        } else {
                            toastr.success('{{ \App\CPU\translate('Banner unpublished successfully!')}}');
                        }
                    }
                });
            });
            $(document).on('click', '.delete', function () {
                var id = $(this).attr("id");
                Swal.fire({
                    title: '{{ \App\CPU\translate('Are you sure?')}}',
                    text: "{{ \App\CPU\translate('You won\'t be able to revert this!')}}",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '{{ \App\CPU\translate('Yes, delete it!')}}'
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
                            data: {id: id},
                            success: function () {
                                $('#data-' + id).remove();
                                // toastr.success('{{ \App\CPU\translate('campaign deleted successfully!')}}');
                            }
                        });
                    }
                })
            });
        </script>
@endpush
