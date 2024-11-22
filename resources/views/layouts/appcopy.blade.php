<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    {{-- <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png"> --}}
    {{-- <link rel="icon" type="image/png" href="../assets/img/favicon.png"> --}}
    <title>
        Soft UI Dashboard by Creative Tim
    </title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    {{-- <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" /> --}}
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    {{-- <link href="../assets/css/nucleo-svg.css" rel="stylesheet" /> --}}
    <!-- CSS Files -->
    <link rel="stylesheet" id="pagestyle" href="{{ asset('assets/admin/css/soft-ui-dashboard.css') }}">

    <style>
        ::-webkit-scrollbar {
            width: 5px;
        }

        /* Track */
        ::-webkit-scrollbar-track {
            box-shadow: inset 0 0 5px grey;
            border-radius: 10px;
        }

        /* Handle */
        ::-webkit-scrollbar-thumb {
            background: #cb0c9f;
            border-radius: 10px;
        }

        /* Handle on hover */
        ::-webkit-scrollbar-thumb:hover {
            background: #89066a;
        }

        .navbar-vertical.navbar-expand-xs .navbar-collapse {
            height: 100%;
        }
        /* @media  */
    </style>
    {{-- <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script> --}}
    @yield('styles')
</head>

<body class="">
    @if (auth()->user()->is_admin())
        @include('partials.adminside')
    @endif
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        @if (auth()->user()->is_admin())
            @include('partials.adminnav')
        @endif
        @yield('contant')
    </main>



    <script src="{{ asset('assets/admin/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/core/bootstrap.min.js') }}"></script>
    {{-- <script src="{{ asset('assets/admin/js/plugins/perfect-scrollbar.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('assets/admin/js/plugins/smooth-scrollbar.min.js') }}"></script> --}}
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    <script src="{{ asset('assets/admin/js/soft-ui-dashboard.js') }}"></script>

    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    @include('global.notification')

</body>

</html>
