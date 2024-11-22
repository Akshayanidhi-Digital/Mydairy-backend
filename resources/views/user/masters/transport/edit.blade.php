@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <form id="transporteradd" action="{{ route('user.masters.transport.update',$data->transporter_id) }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group @error('transport_name') has-danger @enderror">
                                    <label for="transport_name">@lang('lang.transport_name')</label>
                                    <input type="text"
                                        class="form-control @error('transport_name') form-control-danger @enderror"
                                        id="transport_name" placeholder="@lang('lang.transport_name')" name="transport_name"
                                        value="{{ old('transport_name', $data->transporter_name) }}">
                                    @error('transport_name')
                                        <label id="transport_name-error" class="error mt-2 text-danger"
                                            for="transport_name">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group @error('name') has-danger @enderror">
                                    <label for="name">@lang('lang.Name')</label>
                                    <input type="text" class="form-control @error('name') form-control-danger @enderror"
                                        id="name" placeholder="@lang('lang.Name')" name="name"
                                        value="{{ old('name', $data->name) }}">
                                    @error('name')
                                        <label id="name-error" class="error mt-2 text-danger"
                                            for="name">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group @error('father_name') has-danger @enderror">
                                    <label for="father_name">@lang('lang.Father Name')</label>
                                    <input type="text"
                                        class="form-control @error('father_name') form-control-danger @enderror "
                                        id="father_name" placeholder="@lang('lang.Father Name')"
                                        value="{{ old('father_name', $data->father_name) }}" name="father_name">
                                    @error('father_name')
                                        <label id="father_name-error" class="error mt-2 text-danger"
                                            for="father_name">{{ $message }}</label>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group @error('email') has-danger @enderror">
                                    <label for="email">@lang('lang.Email ID.')</label>
                                    <input type="email"
                                        class="form-control @error('email') form-control-danger @enderror " id="email"
                                        placeholder="emaple@gmail.com" value="{{ old('email', $data->email) }}"
                                        name="email">
                                    @error('email')
                                        <label id="email-error" class="error mt-2 text-danger"
                                            for="email">{{ $message }}</label>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group @error('country_code') has-danger @enderror">
                                    <label for="country_code">@lang('lang.Country')</label>
                                    <select
                                        class="country_select w-100 @error('country_code') form-control-danger @enderror"
                                        name="country_code" id="country_code">
                                        <option value="default">@lang('lang.Select :name', ['name' => __('lang.Country')])</option>
                                        <option value="+91" @if(old('country_code',$data->country_code) == '+91') selected @endif >@lang('lang.India')</option>
                                    </select>
                                </div>
                                @error('country_code')
                                    <label id="country_code-error" class="error mt-2 text-danger"
                                        for="country_code">{{ $message }}</label>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <div class="form-group @error('mobile') has-danger @enderror">
                                    <label for="mobile">@lang('lang.Mobile No.')</label>
                                    <input type="number" min="0"
                                        class="form-control @error('mobile') form-control-danger @enderror " id="mobile"
                                        placeholder="@lang('lang.Mobile No.')" value="{{ old('mobile',$data->mobile) }}" name="mobile">
                                    @error('mobile')
                                        <label id="mobile-error" class="error mt-2 text-danger"
                                            for="mobile">{{ $message }}</label>
                                    @enderror

                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary me-2">@lang('lang.:name Update', ['name' => __('lang.Transporter')])</button>
                        <a href="{{ route('user.masters.transport.list') }}" class="btn btn-light">@lang('lang.Back')</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/panel/vendors/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/panel/vendors/select2-bootstrap-theme/select2-bootstrap.min.css') }}">
    <style>
        select.form-control-danger {
            outline: 1px solid #FF4747;
        }

        .select2-container .select2-selection--single {
            height: auto;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 20px;
        }
    </style>
@endsection

@section('scripts')
    {{-- jquery-validation/ --}}
    <script src="{{ asset('assets/panel/vendors/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/panel/vendors/jquery-validation/jquery.validate.min.js') }}"></script>
    <script>
        (function($) {
            'use strict';

            if ($(".country_select").length) {
                $(".country_select").select2();
            }

            if ($(".js-example-basic-single").length) {
                $(".js-example-basic-single").select2();
            }

            jQuery.validator.addMethod("lettersonly", function(value, element) {
                return this.optional(element) || /^[a-zA-Z\u0900-\u097F\s]+$/i.test(value);
            }, "Letters only please");

            jQuery.validator.addMethod("valueNotEquals", function(value, element, arg) {
                return arg !== value;
            }, "Value must not equal arg.");

            $(function() {
                $("#transporteradd").validate({
                    rules: {
                        transport_name: {
                            required: true,
                            lettersonly: true
                        },
                        name: {
                            required: true,
                            lettersonly: true
                        },
                        father_name: {
                            required: true,
                            lettersonly: true
                        },
                        email: {
                            email: true
                        },
                        country_code: {
                            required: true,
                            valueNotEquals: 'default'
                        },
                        mobile: {
                            required: true,
                            // minlength: 0
                        },
                        confirm_password: {
                            required: true,
                            minlength: 5,
                            equalTo: "#password"
                        },

                    },
                    messages: {
                        transport_name: {
                            required: "@lang('lang.transportname.required')",
                            lettersonly: "@lang('lang.transportname.lettersonly')",
                        },
                        name: {
                            required: "@lang('lang.name.required')",
                            lettersonly: "@lang('lang.name.lettersonly')",
                        },
                        father_name: {
                            required: "@lang('lang.father_name.required')",
                            lettersonly: "@lang('lang.father_name.lettersonly')",
                        },
                        email: {
                            email: "@lang('lang.email.email')"
                        },
                        country_code: {
                            required: "@lang('lang.country_code.required')",
                            valueNotEquals: "@lang('lang.country_code.valueNotEquals')",
                        },
                        mobile: {
                            required: "@lang('lang.mobile.required')",
                            valueNotEquals: "@lang('lang.mobile.valueNotEquals')",
                        },
                    },
                    errorPlacement: function(label, element) {
                        label.addClass('mt-2 text-danger');
                        // console.log(element.name);
                        $(element).parent().append(label);
                    },
                    highlight: function(element, errorClass) {
                        $(element).parent().addClass('has-danger')
                        $(element).addClass('form-control-danger')
                    }
                });
            });
        })(jQuery);
    </script>
@endsection
