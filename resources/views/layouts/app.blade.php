<!DOCTYPE html>
<html lang="en">
{{-- <html lang="{{ str_replace('_', '-', app()->getLocale()) }}"> --}}


<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- This line includes the CSRF token -->

    <title>
        @if (auth()->user()->is_admin())
            @lang('constants.Management')
        @else
            @lang('constants.Mydairy')
        @endif - {{ $title ?? '' }}
    </title>
    <link rel="stylesheet" href="{{ asset('assets/panel/vendors/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/panel/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/panel/vendors/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/panel/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/panel/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/panel/css/vertical-layout-light/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/panel/vendors/sweetalert2/sweetalert2.min.css') }}">k
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" />
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

        .fl-wrapper {
            z-index: 99999 !important;
        }
    </style>
    @if (auth()->user() && !auth()->user()->is_admin())
        {{-- @vite(['resources/js/app.js']) --}}
    @endif
    @yield('styles')

</head>

<body id="app">
    <div class="container-scroller">
        @if (auth()->user()->is_admin())
            @include('partials.adminnav')
            <div class="container-fluid page-body-wrapper">
                @include('partials.adminside')
                <div class="main-panel">
                    <div class="content-wrapper">
                        @yield('content')
                    </div>
                </div>
            </div>
        @else
            @include('partials.nav')
            <div class="container-fluid page-body-wrapper">
                @include('partials.side')
                <div class="main-panel">
                    <div class="content-wrapper">
                        @include('partials.breadcrumb')
                        @yield('content')
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- plugins:js -->
    <script src="{{ asset('assets/panel/vendors/js/vendor.bundle.base.js') }}"></script>
    <!-- endinject -->
    <!-- inject:js -->
    <script src="{{ asset('assets/panel/js/off-canvas.js') }}"></script>
    <script src="{{ asset('assets/panel/js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('assets/panel/js/template.js') }}"></script>
    <script src="{{ asset('assets/panel/js/settings.js') }}"></script>
    <script src="{{ asset('assets/panel/js/todolist.js') }}"></script>
    @include('global.notification')
    <script src="{{ asset('assets/panel/vendors/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script>
        (function($) {
            $("input[name='mobile']").on('input', function(e) {
                $(this).val($(this).val().replace(/[^0-9]/g, ''));
            });
        })(jQuery);
    </script>
    @if (auth()->user() && !auth()->user()->is_admin())
        <script>
            function hideBadge() {
                $('#countNoti').hide();
            }

            function messagenormal(title, message) {
                return `<a class="dropdown-item preview-item" >
                        <div class="preview-thumbnail">
                            <div class="preview-icon bg-warning">
                                <i class="ti-info-alt mx-0"></i>
                            </div>
                        </div>
                        <div class="preview-item-content">
                            <h6 class="preview-subject font-weight-normal">` + title + `</h6>
                            <p class="font-weight-light small-text mb-0 text-muted">
                                ` + message + `
                            </p>
                        </div>
                    </a>`;
            }

            function messageMilk(title, message) {
                return `<a href="javascript:void(0)" class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <div class="preview-icon bg-success">
                                <i class="ti-announcement mx-0"></i>
                            </div>
                        </div>
                        <div class="preview-item-content">
                            <h6 class="preview-subject font-weight-normal">` + title + `</h6>
                            <p class="font-weight-light small-text mb-0 text-muted">
                                ` + message + `
                            </p>
                        </div>
                    </a> `;
            }
            var messages = @json(auth()->user()->notification());
            messages.forEach(data => {
                if (data.message_type == 2) {
                    $('#messageList').append(messageMilk('Milk Notification', data.message))
                } else {
                    $('#messageList').append(messagenormal('Message ', data.message))
                }
            });

            $(document).ready(function() {
                // Echo.private(`message.{{ auth()->user()->user_id }}`)
                //     .listen("Message", (response) => {
                //         if (response.message.message_type == 2) {
                //             $('#messageList').prepend(messageMilk('Message', response.message.message))
                //         } else {
                //             $('#messageList').prepend(messagenormal('Message', response.message.message))
                //         }
                //         $('#countNoti').show();
                //         var notificationSound = new Audio('{{ asset('assets/sound/sound.mp3') }}');
                //         notificationSound.play();
                //     });
            });
        </script>
    @endif

    <script>
        @if (!auth()->user()->is_admin())
            $('#languageChange').on('change', function() {
                var lang = $(this).val();
                let _token = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: "{{ route('user.lang') }}",
                    type: 'POST',
                    data: {
                        lang: lang,
                        _token: _token
                    },
                    success: function(response) {
                        if (response.success) {
                            location.reload(); // Reload the page to apply the new language settings
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX error:', status, error); // Error log
                    }
                });
            });
            (function($) {
                milkBuyEntry = function() {
                    swal.fire({
                        title: '@lang('lang.Milk Procure')',
                        text: "@lang('lang.Select date and shift for Milk Procure')",
                        html: `
                        <input type="date" id="dateInput"
                            name="date"
                            class="form-control"
                            max="${new Date().toISOString().split('T')[0]}"
                            value="${new Date().toISOString().split('T')[0]}"
                            style="width: 100%; margin-bottom: 1em;"
                        >
                        <div class="form-group">
                            <div class="form-check form-check-primary">
                                <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="shift" checked value="day">
                                Day
                                <i class="input-helper"></i></label>
                            </div>
                            <div class="form-check form-check-primary">
                                <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="shift" value="morning">
                                Morning
                                <i class="input-helper"></i></label>
                            </div>
                            <div class="form-check form-check-primary">
                                <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="shift" value="evening">
                                Evening
                                <i class="input-helper"></i></label>
                            </div>
                        </div>
                        `,
                        preConfirm: () => {
                            const date = document.querySelector('input[name="date"]').value;
                            const shift = document.querySelector('input[name="shift"]:checked')
                                .value;
                            return {
                                date,
                                shift
                            };
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "{{ route('user.Milkbuy.index') }}?date=" +
                                result.value.date + "&shift=" + result.value.shift;
                        }

                    })
                }
                milkSellEntry = function() {
                    Swal.fire({
                        title: '@lang('lang.Milk Sale')',
                        text: "@lang('lang.Select date and shift for milk sale')",
                        html: `
                        <input type="date" id="dateInput"
                            name="date"
                            class="form-control"
                            max="${new Date().toISOString().split('T')[0]}"
                            value="${new Date().toISOString().split('T')[0]}"
                            style="width: 100%; margin-bottom: 1em;"
                        >
                        <div class="form-group">
                            <div class="form-check form-check-primary">
                                <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="shift" checked value="day">
                                Day
                                <i class="input-helper"></i></label>
                            </div>
                            <div class="form-check form-check-primary">
                                <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="shift" value="morning">
                                Morning
                                <i class="input-helper"></i></label>
                            </div>
                            <div class="form-check form-check-primary">
                                <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="shift" value="evening">
                                Evening
                                <i class="input-helper"></i></label>
                            </div>
                        </div>
                        `,
                        preConfirm: () => {
                            const date = document.querySelector('input[name="date"]').value;
                            const shift = document.querySelector('input[name="shift"]:checked')
                                .value;
                            return {
                                date,
                                shift
                            };
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const selectedDate = result.value.date;
                            window.location.href = "{{ route('user.MilkSell.index') }}?date=" +
                                selectedDate +
                                "&shift=" + result.value.shift;
                        }
                    });
                }
                $('.password-icon').on('click', function() {
                    var parentContainer = $(this).parent();
                    var passwordInput = parentContainer.find('input');
                    if (passwordInput.attr('type') === 'password') {
                        passwordInput.attr('type', 'text');
                        $(this).find('i').removeClass('fa-eye').addClass('fa-eye-slash');
                    } else {
                        $(this).find('i').removeClass('fa-eye-slash').addClass('fa-eye');
                        passwordInput.attr('type', 'password');
                    }
                })
            })(jQuery);
        @endif
        (function() {
            window.onpageshow = function(event) {
                if (event.persisted) {
                    window.location.reload();
                }
            };
        })();
    </script>
    @yield('scripts')

</body>

</html>
