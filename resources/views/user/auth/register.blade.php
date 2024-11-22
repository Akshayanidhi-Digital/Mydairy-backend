@extends('layouts.auth')
@section('contant')
    <div class="row w-100 mx-0">
        <div class="col-sm-6 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5 rounded">
                <div class="brand-logo">
                    <img src="{{ asset('assets/panel/images/logo.svg') }}" alt="logo">
                </div>
                <h4>let's get started</h4>
                <h6 class="font-weight-light">Signing up is easy. It only takes a few steps</h6>
                <form id="reg" role="form" class="py-2" method="post" action="{{ route('register.post') }}">
                    @csrf
                    <div class="mb-3">
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                            placeholder="Name" aria-label="Name">
                        @error('name')
                            <span id="name-error" class="text-danger text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <div class="form-group @error('country_code') has-danger @enderror">
                            <select class="country_select w-100 @error('country_code') form-control-danger @enderror"
                                name="country_code" id="country_code">
                                <option value="default">@lang('lang.Select :name', ['name' => __('lang.Country')])</option>
                                <option value="+91">@lang('lang.India')</option>
                            </select>
                        </div>
                        @error('country_code')
                            <label id="country_code-error" class="error mt-2 text-danger"
                                for="country_code">{{ $message }}</label>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <input type="text" name="mobile" class="form-control" value="{{ old('mobile') }}"
                            placeholder="Mobile" aria-label="Mobile">
                        @error('mobile')
                            <span class="text-danger text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <div class="form-group">
                            <div class="costum-input-password">
                                <input class="form-control" type="password" id="password" name="password"
                                    placeholder="password">
                                <span id="password-icon" class="password-icon">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </span>
                            </div>
                            @error('password')
                                <span id="password-error" class="error mt-2 text-danger"
                                    for="password">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <div class="costum-input-password">
                                <input class="form-control" type="password" id="confirm_password" name="confirm_password"
                                    placeholder="confirm password">
                                <span id="password-icon" class="password-icon">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </span>
                            </div>
                            @error('confirm_password')
                                <span id="confirm_password-error" class="error mt-2 text-danger"
                                    for="confirm_password">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="my-2 d-flex justify-content-between align-items-center">
                        <div class="form-check">
                            <label class="form-check-label text-muted">
                                <input type="checkbox" id="accept_terms" name="accept_terms"
                                    @if (old('accept_terms')) checked @endif class="form-check-input">
                                By Signing Up, You Agree To The
                                <i class="input-helper"></i>
                                <a>Privacy Policy</a>
                            </label>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit"
                            class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">Sign
                            Up</button>
                    </div>
                    <div class="text-center mt-4 font-weight-light">
                        Already have an account? <a href="{{ route('login') }}" class="text-primary">Login</a>
                    </div>
                    <div class="text-center mt-4 font-weight-light">
                        <a href="{{ route('home') }}"
                            class="text-info text-gradient h4 font-weight-bold text-uppercase">Back To Home</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('styles')
    <style>
        .content-wrapper {
            background: rgb(0, 102, 183);
            background: linear-gradient(90deg, rgba(0, 102, 183, 1) 0%, rgba(26, 149, 233, 1) 47%, rgba(99, 180, 238, 1) 100%);
        }

        .container {
            background: #fff;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('assets/panel/vendors/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/panel/vendors/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/panel/vendors/select2-bootstrap-theme/select2-bootstrap.min.css') }}">
    <style>
        select.form-control-danger {
            outline: 1px solid #FF4747;
        }

        .select2-container {
            display: block;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered,
        .costum-input-password .fa {
            color: var(--gray-light);
        }

        .select2-container .select2-selection--single {
            height: auto;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 1;
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

        .form-check-label.has-dange {}
    </style>
@endsection
@section('scripts')
    <script src="{{ asset('assets/panel/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('assets/panel/vendors/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/panel/vendors/jquery-validation/jquery.validate.min.js') }}"></script>
    <script>
        (function($) {
            'use strict';

            function setSelect2Width() {
                if ($(".country_select").length) {
                    $(".country_select").select2();
                }
            }
            setSelect2Width();
            $(window).resize(function() {
                setSelect2Width();
            });
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
            });
            jQuery.validator.addMethod("valueNotEquals", function(value, element, arg) {
                return arg !== value;
            }, "Value must not equal arg.");
            $(function() {
                $("#reg").validate({
                    rules: {
                        country_code: {
                            required: true,
                            valueNotEquals: 'default'
                        },
                        mobile: {
                            required: true,
                            digits: true,
                        },
                        name: {
                            required: true,
                            lettersonly: true,
                        },
                        password: {
                            required: true,
                        },
                        confirm_password: {
                            required: true,
                            equalTo: "#password"
                        },
                        accept_terms: {
                            required: true,
                        },
                    },
                    messages: {
                        country_code: {
                            required: "Please select a country",
                            valueNotEquals: "Please select a country",
                        },
                        mobile: {
                            required: "Please enter your mobile number",
                            digits: "Please enter a valid mobile number"
                        },
                        name: {
                            required: "Please enter your name",
                            lettersonly: "Name should contain only letters"
                        },
                        password: {
                            required: "Please provide a password",
                        },
                        confirm_password: {
                            required: "Please confirm your password",
                            equalTo: "Passwords do not match"
                        },
                    },
                    errorPlacement: function(label, element) {
                        console.log(element);
                        if (element.name !== 'accept_terms') {
                            label.addClass('mt-2 text-danger');
                            $(element).parent().append(label);
                        }
                    },
                    highlight: function(element, errorClass) {
                        $(element).parent().addClass('has-danger')
                        $(element).addClass('form-control-danger')
                    },
                    unhighlight: function(element, errorClass) {
                        $(element).parent().removeClass('has-danger');
                        $(element).removeClass('form-control-danger');
                    },
                    submitHandler: function(form) {
                        form.submit();
                    }
                });
            });
            $('.country_select').on('change', function() {
                $(this).valid();
            });
        })(jQuery);
    </script>
@endsection
