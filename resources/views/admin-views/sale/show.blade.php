@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('view_sale_profile'))

@push('css_or_js')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="content-wrapper">

    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-home"></i>
            </span>
            {{\App\CPU\translate('view_sale_profile')}}
        </h3>

        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    {{\App\CPU\translate('view_sale_profile')}}
                    <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <img class="img-fluid img-thumbnail rounded-circle"
                                style="width: 96px; height: 96px; object-fit: cover;"
                                src="{{ $sale->image }}"
                                alt="{{ $sale->name }}"
                                onerror='this.src="{{ asset('assets/logo/logo-3.png') }}"'>
                            <div>
                                <h4 class="mb-1">{{ $sale->name ?? '-' }}</h4>
                                <p class="mb-1 text-muted">{{ $sale->email ?? '-' }}</p>
                                <p class="mb-0 text-muted">{{ $sale->mobile ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="text-md-end">
                            <span class="badge badge-info text-capitalize">{{ $sale->status ?? 'pending' }}</span>
                            <div class="small text-muted mt-2">{{ \App\CPU\translate('referral_code') }}: {{ $sale->referral_code ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="text-muted mb-2">{{ \App\CPU\translate('balance') }}</h6>
                            <h4 class="mb-0">{{ number_format((float) ($sale->balance ?? 0), 2) }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="text-muted mb-2">{{ \App\CPU\translate('brands') }}</h6>
                            <h4 class="mb-0">{{ $sale->brands_count ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="text-muted mb-2">{{ \App\CPU\translate('campaigns') }}</h6>
                            <h4 class="mb-0">{{ $sale->campaigns_count ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="text-muted mb-2">{{ \App\CPU\translate('kyc_status') }}</h6>
                            <h4 class="mb-0 text-capitalize">{{ $sale->kyc_status ?? '-' }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-2">
                <div class="card-header">
                    <h4 class="mb-0">{{ \App\CPU\translate('Profile Detail') }}</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mb-0">
                            <tbody>
                                <tr>
                                    <th width="25%">{{ \App\CPU\translate('id') }}</th>
                                    <td>{{ $sale->id ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ \App\CPU\translate('name') }}</th>
                                    <td>{{ $sale->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ \App\CPU\translate('email') }}</th>
                                    <td>{{ $sale->email ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ \App\CPU\translate('mobile') }}</th>
                                    <td>{{ $sale->mobile ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ \App\CPU\translate('status') }}</th>
                                    <td class="text-capitalize">{{ $sale->status ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ \App\CPU\translate('referral_code') }}</th>
                                    <td>{{ $sale->referral_code ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ \App\CPU\translate('created_at') }}</th>
                                    <td>{{ $sale->created_at ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ \App\CPU\translate('updated_at') }}</th>
                                    <td>{{ $sale->updated_at ?? '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h4 class="mb-0">{{ \App\CPU\translate('Bank Detail') }}</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mb-0">
                            <tbody>
                                <tr>
                                    <th width="25%">{{ \App\CPU\translate('bank_name') }}</th>
                                    <td>{{ $sale->bank_detail->bank_name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ \App\CPU\translate('account_number') }}</th>
                                    <td>{{ $sale->bank_detail->account_number ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ \App\CPU\translate('ifsc_code') }}</th>
                                    <td>{{ $sale->bank_detail->ifsc_code ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ \App\CPU\translate('branch_name') }}</th>
                                    <td>{{ $sale->bank_detail->branch_name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ \App\CPU\translate('bank_status') }}</th>
                                    <td>{{ $sale->bank_status ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ \App\CPU\translate('bank_rejection_reason') }}</th>
                                    <td>{{ $sale->bank_rejection_reason ?? '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h4 class="mb-0">{{ \App\CPU\translate('PAN Detail') }}</h4>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-lg-8">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped mb-0">
                                    <tbody>
                                        <tr>
                                            <th width="35%">{{ \App\CPU\translate('pan_number') }}</th>
                                            <td>{{ $sale->pan_number ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ \App\CPU\translate('pan_status') }}</th>
                                            <td>{{ $sale->pan_status ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ \App\CPU\translate('pan_rejection_reason') }}</th>
                                            <td>{{ $sale->pan_rejection_reason ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ \App\CPU\translate('kyc_status') }}</th>
                                            <td>{{ $sale->kyc_status ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ \App\CPU\translate('kyc_rejection_reason') }}</th>
                                            <td>{{ $sale->kyc_rejection_reason ?? '-' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <label class="d-block mb-2">{{ \App\CPU\translate('pan_image') }}</label>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#panImageModal">
                                <img class="img-fluid img-thumbnail"
                                    style="max-width: 240px; cursor: pointer;"
                                    src="{{ $sale->pan_image }}"
                                    alt="PAN Image"
                                    onerror='this.src="{{ asset('assets/logo/logo-3.png') }}"'>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-3">
                <a href="{{ route('admin.sale.list') }}" class="btn btn-primary px-4">
                    {{ \App\CPU\translate('back') }}
                </a>
            </div>
        </div>
    </div>
</div>

{{-- PAN Image Modal --}}
<div class="modal fade" id="panImageModal" tabindex="-1" role="dialog" aria-labelledby="panImageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="panImageModalLabel">{{ \App\CPU\translate('pan_image') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img class="img-fluid" style="min-width: 450px;"
                    src="{{ $sale->pan_image }}"
                    alt="PAN Image"
                    onerror='this.src="{{ asset('assets/logo/logo-3.png') }}"'>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
@endpush