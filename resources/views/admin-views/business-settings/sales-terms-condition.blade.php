@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Sales Terms & Condition'))

@push('css_or_js')

@endpush

@section('content')
<style>
    .cke_notifications_area {
        display: none !important;
    }
</style>
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-home"></i>
                </span> Static Pages  
            </h3>
            <nav aria-label="breadcrumb">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">
                        <span></span>Sales Terms & Conditions <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <!-- Inlile Menu -->
                @include('admin-views.business-settings.pages-inline-menu')
                <!-- End Inlile Menu -->
            </div>
            <div class="col-lg-12">
                <form action="{{route('admin.business-settings.sales-update-terms')}}" method="post">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <textarea class="form-control" id="editor" name="value">{{ $terms_condition->value ?? '' }}</textarea>
                        </div>
                        <div class="form-group termdiv">
                            <input class="form-control btn--primary submitbtn" type="submit" value="{{\App\CPU\translate('submit')}}" name="btn">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('value');
    </script>
@endpush
