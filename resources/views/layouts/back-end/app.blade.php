<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="_token" content="{{ csrf_token() }}">
        <title>Rexarix</title>
        <!-- plugins:css -->
        <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/vendors/ti-icons/css/themify-icons.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}">
        <!-- endinject -->
        <!-- Plugin css for this page -->
        <link rel="stylesheet" href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
        <!-- End plugin css for this page -->
        <!-- inject:css -->
        <!-- endinject -->
        <!-- Layout styles -->
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
        <!-- End layout styles -->
        @php($__favicon = \App\CPU\Helpers::get_business_settings('company_favicon'))
        <link rel="shortcut icon" href="{{ $__favicon ? asset('storage/company/'.$__favicon) : asset('assets/images/favicon.png') }}">
    </head>
<body>
<!-- Builder -->
<div class="container-scroller">
    <!-- JS Preview mode only -->
    @include('layouts.back-end.partials._header')

    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
        @include('layouts.back-end.partials._side-bar')
        <!-- partial -->
        <div class="main-panel">
            @yield('content')

            @include('layouts.back-end.partials._footer')
        <!-- partial -->
        </div>
        <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->

</div>
<!-- container-scroller -->





<!-- END ONLY DEV -->

<main id="content" role="main" class="main pointer-event">
    

    

    @include('layouts.back-end.partials._modals')

</main>

<!-- plugins:js -->
<script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
<!-- endinject -->
<!-- Plugin js for this page -->
<script src="{{ asset('assets/vendors/chart.js/chart.umd.js') }}"></script>
<script src="{{ asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<!-- End plugin js for this page -->
<!-- inject:js -->
<script src="{{ asset('assets/js/off-canvas.js') }}"></script>
<script src="{{ asset('assets/js/misc.js') }}"></script>
<script src="{{ asset('assets/js/settings.js') }}"></script>
<script src="{{ asset('assets/js/todolist.js') }}"></script>
<script src="{{ asset('assets/js/jquery.cookie.js') }}"></script>
<!-- endinject -->
<script src="{{ asset('assets/vendors/sweetalert2/sweetalert2.all.min.js') }}"></script>
@stack('script')

@stack('script_2')

@if(session('success'))
<script>
    Swal.fire({ icon: 'success', title: 'Success', text: @json(session('success')), timer: 3000, showConfirmButton: false });
</script>
@endif
@if(session('error'))
<script>
    Swal.fire({ icon: 'error', title: 'Error', text: @json(session('error')) });
</script>
@endif
@if(session('warning'))
<script>
    Swal.fire({ icon: 'warning', title: 'Warning', text: @json(session('warning')) });
</script>
@endif
@if($errors->any())
<script>
    Swal.fire({ icon: 'error', title: 'Validation Error', html: @json(implode('<br>', $errors->all())) });
</script>
@endif
</body>
</html>
