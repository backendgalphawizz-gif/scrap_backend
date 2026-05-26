@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('All Transactions'))

@push('css_or_js')
<style>
    .transaction-filter-scroll {
        overflow-x: auto;
        padding-bottom: 4px;
    }

    .transaction-filter-form {
        flex-wrap: nowrap !important;
        min-width: max-content;
    }

    .transaction-filter-form .form-control,
    .transaction-filter-form .form-select {
        min-width: 150px;
    }

    .transaction-filter-form .search-control {
        min-width: 220px;
    }

    .premium-pagination-wrap {
        border-top: 1px solid #e8ebef;
        margin-top: 22px;
        padding: 12px 18px 16px;
    }

    .premium-pagination-shell {
        display: flex;
        justify-content: flex-end;
    }
</style>
@endpush

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-swap-horizontal"></i>
            </span>
            {{ \App\CPU\translate('All Transactions') }}
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <span>{{ \App\CPU\translate('Transactions') }}</span>
                </li>
            </ul>
        </nav>
    </div>

    <div class="d-flex justify-content-end mb-3">
        <div class="transaction-filter-scroll w-100">
            <form method="GET" action="{{ route('admin.transactions.index') }}" class="transaction-filter-form d-flex align-items-center justify-content-end gap-2">
                <select name="type" class="form-select" style="min-width: 200px;">
                    <option value="all" {{ $type === 'all' ? 'selected' : '' }}>{{ \App\CPU\translate('All Types') }}</option>
                    <option value="user_wallet" {{ $type === 'user_wallet' ? 'selected' : '' }}>{{ \App\CPU\translate('User Wallet') }}</option>
                    <option value="sale_wallet" {{ $type === 'sale_wallet' ? 'selected' : '' }}>{{ \App\CPU\translate('Sale Wallet') }}</option>
                    <option value="commission_ledger" {{ $type === 'commission_ledger' ? 'selected' : '' }}>{{ \App\CPU\translate('Commission Ledger') }}</option>
                    <option value="campaign" {{ $type === 'campaign' ? 'selected' : '' }}>{{ \App\CPU\translate('Campaign Participation') }}</option>
                </select>
                <input type="text" class="form-control search-control" name="search" value="{{ request('search') }}" placeholder="{{ \App\CPU\translate('Search reference, party, details') }}">
                <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}" title="{{ \App\CPU\translate('From date') }}">
                <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}" title="{{ \App\CPU\translate('To date') }}">
                @if($type !== 'all')
                <input type="text" class="form-control" name="status" value="{{ request('status') }}" placeholder="{{ \App\CPU\translate('Status') }}">
                @endif
                <button type="submit" class="btn btn-primary">{{ \App\CPU\translate('Filter') }}</button>
                <a href="{{ route('admin.transactions.index', ['type' => $type]) }}" class="btn btn-outline-secondary">{{ \App\CPU\translate('Reset') }}</a>
            </form>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <a href="{{ route('admin.transactions.index', ['type' => 'user_wallet']) }}" class="card text-decoration-none h-100 {{ $type === 'user_wallet' ? 'border-primary' : '' }}">
                <div class="card-body py-3">
                    <h6 class="mb-1 text-dark">{{ \App\CPU\translate('User Wallet') }}</h6>
                    <small class="text-muted">{{ \App\CPU\translate('Coin credits, debits & withdrawals') }}</small>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.transactions.index', ['type' => 'sale_wallet']) }}" class="card text-decoration-none h-100 {{ $type === 'sale_wallet' ? 'border-primary' : '' }}">
                <div class="card-body py-3">
                    <h6 class="mb-1 text-dark">{{ \App\CPU\translate('Sale Wallet') }}</h6>
                    <small class="text-muted">{{ \App\CPU\translate('Sale user wallet movements') }}</small>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.transactions.index', ['type' => 'commission_ledger']) }}" class="card text-decoration-none h-100 {{ $type === 'commission_ledger' ? 'border-primary' : '' }}">
                <div class="card-body py-3">
                    <h6 class="mb-1 text-dark">{{ \App\CPU\translate('Commission Ledger') }}</h6>
                    <small class="text-muted">{{ \App\CPU\translate('Sale commission entries') }}</small>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.transactions.index', ['type' => 'campaign']) }}" class="card text-decoration-none h-100 {{ $type === 'campaign' ? 'border-primary' : '' }}">
                <div class="card-body py-3">
                    <h6 class="mb-1 text-dark">{{ \App\CPU\translate('Campaign Participation') }}</h6>
                    <small class="text-muted">{{ \App\CPU\translate('User campaign participations') }}</small>
                </div>
            </a>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table">
                <thead class="text-capitalize">
                    <tr>
                        <th>{{ \App\CPU\translate('SL') }}</th>
                        <th>{{ \App\CPU\translate('Category') }}</th>
                        <th>{{ \App\CPU\translate('Reference') }}</th>
                        <th>{{ \App\CPU\translate('Party') }}</th>
                        <th>{{ \App\CPU\translate('Amount') }}</th>
                        <th>{{ \App\CPU\translate('Type') }}</th>
                        <th>{{ \App\CPU\translate('Status') }}</th>
                        <th>{{ \App\CPU\translate('Details') }}</th>
                        <th>{{ \App\CPU\translate('Date') }}</th>
                        <th class="text-center">{{ \App\CPU\translate('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $key => $txn)
                        <tr>
                            <td>{{ $transactions->firstItem() + $key }}</td>
                            <td><span class="badge badge-info">{{ $txn['category'] }}</span></td>
                            <td>{{ $txn['reference'] }}</td>
                            <td>{{ $txn['party'] }}</td>
                            <td>{{ $txn['amount'] }} <small class="text-muted">{{ $txn['amount_label'] }}</small></td>
                            <td>{{ ucfirst($txn['flow']) }}</td>
                            <td>
                                <span class="badge badge-{{ in_array(strtolower($txn['status']), ['completed', 'active', 'approved', '1']) ? 'success' : 'danger' }}">
                                    {{ ucfirst($txn['status']) }}
                                </span>
                            </td>
                            <td>{{ \Illuminate\Support\Str::limit($txn['details'], 40) }}</td>
                            <td>{{ $txn['date'] }}</td>
                            <td class="text-center">
                                <a href="{{ $txn['link'] }}" class="btn btn-outline-info btn-sm" title="{{ \App\CPU\translate('View in section') }}">
                                    <i class="mdi mdi-open-in-new"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-4">{{ \App\CPU\translate('No transactions found') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
            <div class="premium-pagination-wrap">
                <div class="premium-pagination-shell">
                    {!! $transactions->onEachSide(1)->links('vendor.pagination.premium') !!}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
