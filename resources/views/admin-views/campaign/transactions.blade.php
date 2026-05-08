@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Campaign List'))

@push('css_or_js')
<meta name="csrf-token" content="{{ csrf_token() }}">
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
                <i class="mdi mdi-cash-multiple"></i>
            </span> {{\App\CPU\translate('Campaign Transactions')}}
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <span></span>{{\App\CPU\translate('Campaign Transactions')}} <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                </li>
            </ul>
        </nav>
    </div>
    <div class="row">
        <div class="col-lg-12">
            @if(request()->filled('user_id'))
            @php($filteredUser = \App\Models\User::find(request('user_id')))
            <div class="alert alert-info d-flex align-items-center justify-content-between mb-3 py-2 px-3" role="alert">
                <span>
                    <i class="mdi mdi-filter-outline me-1"></i>
                    Showing campaigns for: <strong>{{ $filteredUser->name ?? 'User #'.request('user_id') }}</strong>
                </span>
                <a href="{{ route('admin.campaigns-transactions.list') }}" class="btn btn-sm btn-outline-secondary ms-3">Clear filter</a>
            </div>
            @endif
            <div class="row mb-4">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="text-capitalize">
                                <tr>
                                    <th>{{ \App\CPU\translate('SL')}}</th>
                                    <th>{{ \App\CPU\translate('Campaign')}}</th>
                                    <th>{{ \App\CPU\translate('Brand Name')}}</th>
                                    <th>{{ \App\CPU\translate('User')}}</th>
                                    <th>{{ \App\CPU\translate('shared_on')}}</th>
                                    <th>{{ \App\CPU\translate('Coins')}}</th>
                                    <th>{{ \App\CPU\translate('Start Date')}}</th>
                                    <th>{{ \App\CPU\translate('End Date')}}</th>
                                    <th>{{ \App\CPU\translate('Status')}}</th>
                                    <th>{{ \App\CPU\translate('Date')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $key => $txn)
                                <tr>
                                    <td>{{ $transactions->firstItem() + $key }}</td>
                                    <td>{{ $txn->campaign->title ?? '' }}</td>
                                    <td>{{ $txn->campaign->brand->username ?? '' }}</td>
                                    <td>{{ $txn->user->name ?? '' }}</td>
                                    <td>{{ $txn->shared_on }}</td>
                                    <td>{{ $txn->earning }}</td>
                                    <td>{{ $txn->start_date }}</td>
                                    <td>{{ $txn->end_date }}</td>
                                    <td>
                                        <span class="badge badge-{{ ($txn->status == 'active' || $txn->status == 'completed') ? 'gradient-success' : 'gradient-danger' }}">
                                            {{ ucwords($txn->status) }}
                                        </span>
                                    </td>
                                    <td>{{ date('d/m/Y', strtotime($txn->created_at)) }}</td>
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
                    @if($transactions->hasPages())
                        <div class="premium-pagination-wrap">
                            <div class="premium-pagination-shell">
                                <div class="premium-pagination-inline">
                                    {!! $transactions->onEachSide(1)->links('vendor.pagination.premium') !!}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    $('#mbimageFileUploader').change(function() {
        readURL(this);
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#mbImageviewer').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
    $(document).on('change', '.status', function() {
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
            success: function(data) {
                if (data == 1) {
                    toastr.success('{{ \App\CPU\translate('
                        Banner published successfully!')}}');
                } else {
                    toastr.success('{{ \App\CPU\translate('
                        Banner unpublished successfully!')}}');
                }
            }
        });
    });
    $(document).on('click', '.delete', function() {
        var id = $(this).attr("id");
        Swal.fire({
            title: '{{ \App\CPU\translate('
            Are you sure ? ')}}',
            text : "{{ \App\CPU\translate('You won\'t be able to revert this!')}}",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '{{ \App\CPU\translate('
            Yes,
            delete it!')}}'
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
                    data: {
                        id: id
                    },
                    success: function() {
                        $('#data-' + id).remove();
                        // toastr.success('{{ \App\CPU\translate('campaign deleted successfully!')}}');
                    }
                });
            }
        })
    });
</script>
@endpush