@extends('layouts.back-end.app')

@section('title', $seller->shop? $seller->shop->name : \App\CPU\translate("Shop Name"))

@push('css_or_js')
@endpush

@section('content')
<div class="content-wrapper">

    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-store menu-icon"></i>
            </span> User Brand
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <span></span>User Brand
                    <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">

        {{-- Seller Account --}}
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header text-capitalize">
                    <h5 class="mb-0">{{\App\CPU\translate('Seller')}} {{\App\CPU\translate('Account')}}</h5>
                </div>

                <div class="card-body" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{\App\CPU\translate('name')}}</label>
                                <input type="text" class="form-control"
                                       value="{{$seller->f_name}} {{$seller->l_name}}" disabled>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{\App\CPU\translate('Email')}}</label>
                                <input type="text" class="form-control"
                                       value="{{$seller->email}}" disabled>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{\App\CPU\translate('Phone')}}</label>
                                <input type="text" class="form-control"
                                       value="{{$seller->phone}}" disabled>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>


        {{-- Seller Information --}}
        <div class="col-md-12">
            <div class="card">
                <div class="card-header text-capitalize">
                    <h5 class="mb-0">{{\App\CPU\translate('Seller')}} {{\App\CPU\translate('Information')}}</h5>
                </div>

                <div class="card-body" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{\App\CPU\translate('Brand Name')}}</label>
                                <input type="text" class="form-control" value="{{$seller->username}}" disabled>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{\App\CPU\translate('Name')}}</label>
                                <input type="text" class="form-control" value="{{$seller->f_name}}" disabled>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{\App\CPU\translate('Phone')}}</label>
                                <input type="text" class="form-control" value="{{$seller->phone}}" disabled>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{\App\CPU\translate('Email')}}</label>
                                <input type="text" class="form-control" value="{{$seller->email}}" disabled>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{\App\CPU\translate('City')}}</label>
                                <input type="text" class="form-control" value="{{$seller->city}}" disabled>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{\App\CPU\translate('State')}}</label>
                                <input type="text" class="form-control" value="{{$seller->state}}" disabled>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{\App\CPU\translate('Business Type')}}</label>
                                <input type="text" class="form-control"
                                       value="{{$seller->business_registeration_type}}" disabled>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{\App\CPU\translate('Referral Code')}}</label>
                                <input type="text" class="form-control"
                                       value="{{$seller->referral_code}}" disabled>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{\App\CPU\translate('Instagram')}}</label>
                                <input type="text" class="form-control"
                                       value="{{$seller->instagram_username ?? 'N/A'}}" disabled>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{\App\CPU\translate('Facebook')}}</label>
                                <input type="text" class="form-control"
                                       value="{{$seller->facebook_username ?? 'N/A'}}" disabled>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{\App\CPU\translate('gst_number')}}</label>
                                <input type="text" class="form-control"
                                       value="{{$seller->gst_number ?? 'N/A'}}" disabled>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{\App\CPU\translate('pan_number')}}</label>
                                <input type="text" class="form-control"
                                       value="{{$seller->pan_number ?? 'N/A'}}" disabled>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{\App\CPU\translate('primary_contact')}}</label>
                                <input type="text" class="form-control"
                                       value="{{$seller->primary_contact ?? 'N/A'}}" disabled>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{\App\CPU\translate('alternate_contact')}}</label>
                                <input type="text" class="form-control"
                                       value="{{$seller->alternate_contact ?? 'N/A'}}" disabled>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{\App\CPU\translate('full_address')}}</label>
                                <input type="text" class="form-control"
                                       value="{{$seller->full_address ?? 'N/A'}}" disabled>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{\App\CPU\translate('google_map_link')}}</label>
                                <input type="text" class="form-control"
                                       value="{{$seller->google_map_link ?? 'N/A'}}" disabled>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{\App\CPU\translate('website_link')}}</label>
                                <input type="text" class="form-control"
                                       value="{{$seller->website_link ?? 'N/A'}}" disabled>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{\App\CPU\translate('Status')}}</label>
                                <input type="text" class="form-control"
                                       value="{{$seller->status=='approved' ? 'Active' : 'In-Active'}}" disabled>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 mt-4">
            <div class="card">
                <div class="card-header text-capitalize">
                    <h5 class="mb-0">Social Media Verification</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form action="{{ route('admin.brand.updateStatus', [$seller->id]) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Instagram Verification Status</label>
                                    <select name="instagram_status" class="form-control form-select">
                                        <option value="not_verified" {{ $seller->instagram_status === 'not_verified' ? 'selected' : '' }}>Not Verified</option>
                                        <option value="pending" {{ $seller->instagram_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="verified" {{ $seller->instagram_status === 'verified' ? 'selected' : '' }}>Verified</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Facebook Verification Status</label>
                                    <select name="facebook_status" class="form-control form-select">
                                        <option value="not_verified" {{ $seller->facebook_status === 'not_verified' ? 'selected' : '' }}>Not Verified</option>
                                        <option value="pending" {{ $seller->facebook_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="verified" {{ $seller->facebook_status === 'verified' ? 'selected' : '' }}>Verified</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Brand Account Status</label>
                                    <select name="status" class="form-control form-select">
                                        <option value="approved" {{ $seller->status === 'approved' ? 'selected' : '' }}>Active</option>
                                        <option value="pending" {{ $seller->status === 'pending' ? 'selected' : '' }}>In-Active</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>GST Verification Status</label>
                                    <select name="gst_status" class="form-control form-select">
                                        <option value="Not Submitted" {{ $seller->gst_status === 'Not Submitted' ? 'selected' : '' }}>Not Submitted</option>
                                        <option value="Submitted" {{ $seller->gst_status === 'Submitted' ? 'selected' : '' }}>Submitted</option>
                                        <option value="Under Verification" {{ $seller->gst_status === 'Under Verification' ? 'selected' : '' }}>Under Verification</option>
                                        <option value="Verified" {{ $seller->gst_status === 'Verified' ? 'selected' : '' }}>Verified</option>
                                        <option value="Rejected" {{ $seller->gst_status === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>PAN Verification Status</label>
                                    <select name="pan_status" class="form-control form-select">
                                        <option value="Not Submitted" {{ $seller->pan_status === 'Not Submitted' ? 'selected' : '' }}>Not Submitted</option>
                                        <option value="Submitted" {{ $seller->pan_status === 'Submitted' ? 'selected' : '' }}>Submitted</option>
                                        <option value="Under Verification" {{ $seller->pan_status === 'Under Verification' ? 'selected' : '' }}>Under Verification</option>
                                        <option value="Verified" {{ $seller->pan_status === 'Verified' ? 'selected' : '' }}>Verified</option>
                                        <option value="Rejected" {{ $seller->pan_status === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Update Verification</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('script')
@endpush