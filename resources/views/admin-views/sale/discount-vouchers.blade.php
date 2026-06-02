@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Discount Vouchers'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')

<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-ticket-percent"></i>
            </span> {{ \App\CPU\translate('Discount Vouchers') }}
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <span></span>{{ \App\CPU\translate('Discount Vouchers') }} <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-3">
                <div class="card-body border-bottom">
                    <form method="GET" action="{{ route('admin.sale.discount-vouchers') }}" class="d-flex flex-wrap align-items-center gap-2">
                        <input type="text" class="form-control" style="min-width:220px;" name="search" value="{{ request('search') }}" placeholder="Search voucher code">
                        <input type="number" class="form-control" style="min-width:130px;" name="sale_id" value="{{ request('sale_id') }}" placeholder="Sale ID">
                        <select class="form-select" style="min-width:140px;" name="is_active">
                            <option value="">All status</option>
                            <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        <button type="submit" class="btn btn-primary px-4">Filter</button>
                        <a href="{{ route('admin.sale.discount-vouchers') }}" class="btn btn-outline-secondary px-4">Reset</a>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead class="text-capitalize">
                        <tr>
                            <th>#</th>
                            <th>{{ \App\CPU\translate('Code') }}</th>
                            <th>{{ \App\CPU\translate('Sales Person') }}</th>
                            <th>{{ \App\CPU\translate('Discount (₹)') }}</th>
                            <th>{{ \App\CPU\translate('Used / Max') }}</th>
                            <th>{{ \App\CPU\translate('Valid From') }}</th>
                            <th>{{ \App\CPU\translate('Valid To') }}</th>
                            <th>{{ \App\CPU\translate('Status') }}</th>
                            <th>{{ \App\CPU\translate('Created') }}</th>
                            <th>{{ \App\CPU\translate('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vouchers as $key => $v)
                            <tr>
                                <td>{{ $vouchers->firstItem() + $key }}</td>
                                <td><code>{{ $v->code }}</code></td>
                                <td>{{ $v->sale->name ?? 'N/A' }}<br><small class="text-muted">{{ $v->sale->email ?? '' }}</small></td>
                                <td>₹{{ number_format($v->discount_amount, 2) }}</td>
                                <td>{{ $v->used_count }} / {{ $v->max_uses ?? '∞' }}</td>
                                <td>{{ $v->valid_from ? $v->valid_from->format('d/m/Y') : '—' }}</td>
                                <td>{{ $v->valid_to ? $v->valid_to->format('d/m/Y') : '—' }}</td>
                                <td>
                                    @if($v->is_active)
                                        <span class="badge bg-gradient-success">Active</span>
                                    @else
                                        <span class="badge bg-gradient-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>{{ \App\CPU\Helpers::formatAdminDateTime($v->created_at) }}</td>
                                <td>
                                    @if($v->is_active)
                                        <button type="button" class="btn btn-sm btn-gradient-danger toggle-voucher-status"
                                            data-id="{{ $v->id }}" data-status="0">Deactivate</button>
                                    @else
                                        <button type="button" class="btn btn-sm btn-gradient-success toggle-voucher-status"
                                            data-id="{{ $v->id }}" data-status="1">Activate</button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center">{{ \App\CPU\translate('No vouchers found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end">
                {{ $vouchers->links() }}
            </div>
        </div>
    </div>
</div>

@endsection

@push('script')
<script>
    $(document).on('click', '.toggle-voucher-status', function () {
        const btn = $(this);
        const id = btn.data('id');
        const newStatus = btn.data('status');

        $.ajax({
            url: '{{ route("admin.sale.discount-vouchers.status") }}',
            method: 'POST',
            data: { _token: $('meta[name="csrf-token"]').attr('content'), id, is_active: newStatus },
            success: function (res) {
                if (res.status) {
                    location.reload();
                }
            },
            error: function () {
                alert('Failed to update voucher status.');
            }
        });
    });
</script>
@endpush
