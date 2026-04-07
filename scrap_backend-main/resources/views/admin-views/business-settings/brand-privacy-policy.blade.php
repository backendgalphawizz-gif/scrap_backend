@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Brand Privacy policy'))

@push('css_or_js')

@endpush

@section('content')
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
                        <span></span>Brand Privacy Policy <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
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
                <form action="{{route('admin.business-settings.brand-privacy-policy-update')}}" method="post">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <textarea class="form-control" id="editor" name="value">{{$privacy_policy->value}}</textarea>
                        </div>
                        <div class="form-group termdiv">
                            <input class="form-control btn--primary submitbtn" type="submit" name="btn">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    {{--ck editor--}}
    {{-- <script src="{{asset('/')}}vendor/ckeditor/ckeditor/ckeditor.js"></script>
    <script src="{{asset('/')}}vendor/ckeditor/ckeditor/adapters/jquery.js"></script>
    <script>
        $('#editor').ckeditor({
            contentsLangDirection : '{{Session::get('direction')}}',
        });
    </script>
    --}}
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('value');
    </script>

    {{--ck editor--}}
@endpush

