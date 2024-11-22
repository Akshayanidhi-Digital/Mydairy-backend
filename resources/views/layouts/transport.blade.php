<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@lang('constants.Mydairy') - {{ $title ?? '' }}
    </title>
    <link rel="stylesheet" href="{{ asset('assets/panel/vendors/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/panel/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/panel/vendors/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/panel/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/panel/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/panel/css/vertical-layout-light/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/panel/vendors/line-awesome/css/line-awesome.min.css') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" />
    <link rel="stylesheet" href="{{ asset('assets/panel/vendors/sweetalert2/sweetalert2.min.css') }}">

    @laravelPWA
    @if (app()->getLocale() == 'hi')
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Rajdhani:wght@300;400;500;600;700&display=swap');
            @import url('https://fonts.googleapis.com/css2?family=Yantramanav:wght@100;300;400;500;700;900&display=swap');

            body {
                font-family: "Yantramanav", "Rajdhani", "Nunito", sans-serif !important;
            }

            .sidebar {
                font-family: "Yantramanav", "Rajdhani", "Nunito", sans-serif !important;
            }
        </style>
    @endif
    <style>
        .menu-icon.icon_cost {
            font-size: 1.2rem !important;
        }

        a {
            text-decoration: none;
            cursor: pointer;
        }

        a:hover {
            text-decoration: none;
            cursor: pointer;
        }

        .table th,
        .jsgrid .jsgrid-table th,
        .table td,
        .jsgrid .jsgrid-table td {
            white-space: nowrap;
            padding: 1rem;
            text-align: center;
        }

        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .table-fixed {
            table-layout: fixed;
        }


        .costum-input-password {
            border-radius: 4px;
            position: relative;
        }

        .costum-input-password span {
            position: absolute;
            right: .7rem;
            top: 0.875rem;
            font-weight: 400;
            font-size: 0.875rem;
        }

        .notify_cust[data-count]:after {
            position: absolute;
            right: -1rem;
            top: -1rem;
            content: attr(data-count);
            font-size: 1rem;
            padding: .6em;
            border-radius: 50%;
            line-height: .75em;
            color: #fff;
            background: var(--primary);
            text-align: center;
            min-width: 2em;
        }

        .card-border {
            border: 1px solid #007bff;
        }

        @media only screen and (max-width: 768px) {
            .table-fixed {
                table-layout: auto;
            }
        }

        .sidebar .nav .nav-item .nav-link i.menu-icon {
            font-size: 1.5rem;
        }
    </style>

    @yield('styles')

</head>

<body id="app">
    <div class="container-scroller">
        @include('partials.transport.nav')
        <div class="container-fluid page-body-wrapper">
            @include('partials.transport.side')
            <div class="main-panel">
                <div class="content-wrapper">
                    @include('partials.transport.breadcrumb')
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <!-- plugins:js -->
    <script src="{{ asset('assets/panel/vendors/js/vendor.bundle.base.js') }}"></script>

    <script src="{{ asset('assets/panel/js/off-canvas.js') }}"></script>
    <script src="{{ asset('assets/panel/js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('assets/panel/js/template.js') }}"></script>
    <script src="{{ asset('assets/panel/js/settings.js') }}"></script>
    <script src="{{ asset('assets/panel/js/todolist.js') }}"></script>
    @include('global.notification')
    <script src="{{ asset('assets/panel/vendors/sweetalert2/sweetalert2.all.min.js') }}"></script>

    <script>
        (function() {
            window.onpageshow = function(event) {
                if (event.persisted) {
                    window.location.reload();
                }
            };
        })();
    </script>
    <script>
        (function($) {
            $("input[name='mobile']").on('input', function(e) {
                $(this).val($(this).val().replace(/[^0-9]/g, ''));
            });
        })(jQuery);
    </script>
    @yield('scripts')

</body>

</html>
