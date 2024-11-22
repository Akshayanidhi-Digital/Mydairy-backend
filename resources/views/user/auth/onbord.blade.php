@extends('layouts.auth')
@section('contant')
    <div class="row w-100 mx-0">
        <div class="col-sm-6 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5 rounded">
                <div class="brand-logo">
                    <img src="{{ asset('assets/panel/images/logo.svg') }}" alt="logo">
                </div>
                <h4>Profile details</h4>
                <h6 class="font-weight-light">Provide following info for complete your pofile.</h6>
                <form id="reg" role="form" class="py-2" method="post" action="{{ route('user.onboard') }}">
                    @csrf

                    <div class="mb-3">
                        <input type="text" name="father_name" class="form-control" value="{{ old('father_name') }}"
                            placeholder="father name" aria-label="father name">
                        @error('father_name')
                            <span id="father_name-error" class="text-danger text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <input type="text" name="dairy_name" class="form-control" value="{{ old('dairy_name') }}"
                            placeholder="dairy name" aria-label="dairy name">
                        @error('dairy_name')
                            <span id="dairy_name-error" class="text-danger text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <textarea class="form-control" name="address" placeholder="Dairy Address" id="address" rows="5">{{ old('address') }}</textarea>
                        @error('address')
                            <label id="address-error" class="error mt-2 text-danger" for="address">{{ $message }}</label>
                        @enderror
                    </div>

                    <input type="hidden" name="latitude" id="latitude" value="">
                    <input type="hidden" name="longitude" id="longitude" value="">

                    <div class="text-center">
                        <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                            Submit
                        </button>
                    </div>

                    <div class="text-center mt-4 font-weight-light">
                        <a href="{{ route('logout') }}"
                            class="text-info text-gradient h4 font-weight-bold text-uppercase">Logout</a>
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
            jQuery.validator.addMethod("address", function(value, element) {
                return this.optional(element) ||
                    /((\d{1,2}\/\d{1,2} [A-Za-z0-9\s]+)?|([A-Za-z0-9\s]+)?)(, [A-Za-z]+)?(, [A-Z]{2})?(, \d{5,6})?$/i
                    .test(value);
            }, "please add valid address");
            $(function() {
                $("#reg").validate({
                    rules: {
                        father_name: {
                            required: true,
                            lettersonly: true,
                        },
                        dairy_name: {
                            required: true,
                            lettersonly: true,
                        },
                        address: {
                            required: true,
                            address: true,
                        },
                    },
                    messages: {
                        dairy_name: {
                            required: "Please enter your dairy name",
                            lettersonly: "dairy name should contain only letters"
                        },
                        father_name: {
                            required: "Please enter your father name",
                            lettersonly: "dairy name should contain only letters"
                        },
                        address: {
                            required: "Please enter your address",
                            address: "Please enter valid address"
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
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    $('#latitude').val(position.coords.latitude.toFixed(6));
                    $('#longitude').val(position.coords.longitude.toFixed(6));
                }, function(error) {
                    console.error("Error Code = " + error.code + " - " + error.message);
                });
            } else {
                alert("Geolocation is not supported by this browser.");
            }

        })(jQuery);
    </script>
@endsection
