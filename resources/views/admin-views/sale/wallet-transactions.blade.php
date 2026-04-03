@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Wallet Transactions'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="text-capitalize">
                                <tr>
                                    <th>{{ \App\CPU\translate('SL')}}</th>
                                    <th>{{ \App\CPU\translate('Name')}}</th>
                                    <th>{{ \App\CPU\translate('Amount')}}</th>
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
                                        <td>{{ $txn->remarks ?? '' }}</td>
                                        <td>
                                            <span class="badge badge-{{ $txn->status == 'success' ? 'gradient-success' : 'gradient-danger' }}">
                                            {{ $txn->status }}</span>
                                        </td>
                                        <td>{{ date('d M, Y', strtotime($txn->created_at)) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">
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
