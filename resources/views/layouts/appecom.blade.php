<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- This line includes the CSRF token -->

    <title>
        @if (auth()->user()->is_admin())
            Management
        @else
            MYdairy
        @endif {{ $title ?? '' }}
    </title>
    <link rel="stylesheet" href="{{asset('assets/ecom/css/style-prefix.css')}}">
    <link rel="shortcut icon" href="{{ asset('assets/panel/images/favicon.png') }}" />
    <link rel="stylesheet" href="{{ asset('assets/panel/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{asset('assets/panel/vendors/font-awesome/css/font-awesome.min.css')}}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap"
      rel="stylesheet">
    {{-- <style>
        a{
            text-decoration: none;
            cursor: pointer;
        }
        a:hover{
            text-decoration: none;
            cursor: pointer;
        }
        .table th,
        .jsgrid .jsgrid-table th,
        .table td,
        .jsgrid .jsgrid-table td {
            white-space: normal;
        }

        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .table-fixed {
            table-layout: fixed;
        }

        @media only screen and (max-width: 768px) {
            .table-fixed {
                table-layout: auto;
            }
        }
    </style> --}}

    @yield('styles')
</head>

<body>
    @include('partials.ecomnav')
    <main>
        @yield('content')
    </main>
    <script src="{{ asset('assets/panel/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{asset('assets/ecom/js/script.js')}}"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    @include('global.notification')
    @yield('scripts')
</body>

</html>
