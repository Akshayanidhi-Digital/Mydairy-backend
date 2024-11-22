<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Mydairy Login</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ asset('assets/panel/vendors/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/panel/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/panel/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/panel/css/vertical-layout-light/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" />
    <link rel="stylesheet" href="{{ asset('assets/panel/vendors/sweetalert2/sweetalert2.min.css') }}">
    @laravelPWA

    @yield('styles')
</head>

<body class="">
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth px-0">
                @yield('contant')
            </div>
        </div>
    </div>

    <!-- plugins:js -->
    <script src="{{ asset('assets/panel/vendors/js/vendor.bundle.base.js') }}"></script>
    <!-- endinject -->
    @include('global.notification')
    <script src="{{ asset('assets/panel/vendors/sweetalert2/sweetalert2.all.min.js') }}"></script>

    @yield('scripts')
    <script>
        (function($) {
         $("input[name='mobile']").on('input', function (e) {
                $(this).val($(this).val().replace(/[^0-9]/g, ''));
            });
        })(jQuery);
    </script>
</body>

</html>
