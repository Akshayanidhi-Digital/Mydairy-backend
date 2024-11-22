@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ $title }}</h4>
                    <form id="childDairy" action="{{ route('user.childUser.update', $dairy->user_id) }}" method="POST"
                        autocomplete="off">
                        @csrf
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="name">@lang('lang.Name')</label>
                                    <input type="text" class="form-control" id="name"
                                        value="{{ old('name') ?? $dairy->name }}" name="name"
                                        placeholder="@lang('lang.Name')">
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
                                        value="{{ old('father_name') ?? $dairy->father_name }}" name="father_name">
                                    @error('father_name')
                                        <label id="father_name-error" class="error mt-2 text-danger"
                                            for="father_name">{{ $message }}</label>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="email">@lang('lang.Email ID.')</label>
                                    <input type="email" class="form-control" id="email"
                                        value="{{ old('email') ?? $dairy->email }}" name="email"
                                        placeholder="example@gmail.com">
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
                                        <option value="+91"
                                            @if (old('country_code') && old('country_code') == '+91') selected
                                        @elseif($dairy->country_code == '+91')
                                            selected @endif>
                                            @lang('lang.India')</option>
                                    </select>
                                </div>
                                @error('country_code')
                                    <label id="country_code-error" class="error mt-2 text-danger"
                                        for="country_code">{{ $message }}</label>
                                @enderror
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="mobile">@lang('lang.Mobile No.')</label>
                                    <input type="number" class="form-control" value="{{ old('mobile') ?? $dairy->mobile }}"
                                        id="mobile" name="mobile" placeholder="@lang('lang.Mobile')">
                                    @error('mobile')
                                        <label id="mobile-error" class="error mt-2 text-danger"
                                            for="mobile">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="role">@lang('lang.Role')</label>
                                    <select class="role_select w-100" id="role" name="role">
                                        <option value="default">@lang('lang.Select :name', ['name' => __('lang.Role')])</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->role_id }}"
                                                @if (old('role') == $role->role_id) selected @elseif ($dairy->role_id == $role->role_id)  selected @endif>
                                                {{ $role->short_name . ' - ' . $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('role')
                                    <label id="role-error" class="error mt-2 text-danger"
                                        for="role">{{ $message }}</label>
                                @enderror
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="dairy_name">@lang('lang.Dairy Name')</label>
                                    <input type="text" class="form-control" id="dairy_name"
                                        value="{{ old('dairy_name') ?? $dairy->profile->dairy_name }}" name="dairy_name"
                                        placeholder="@lang('lang.Dairy Name')">
                                    @error('dairy_name')
                                        <label id="dairy_name-error" class="error mt-2 text-danger"
                                            for="dairy_name">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="address">@lang('lang.Dairy Address')</label>
                                    <input type="text" class="form-control"
                                        value="{{ old('address') ?? $dairy->profile->address }}" id="address"
                                        name="address" placeholder="@lang('lang.Dairy Address')">
                                    @error('address')
                                        <label id="address-error" class="error mt-2 text-danger"
                                            for="address">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            {{-- <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="password">@lang('lang.Password')</label>
                                    <div class="costum-input-password">
                                        <input class="form-control" type="password" id="password" name="password"
                                            placeholder="password">
                                        <span id="password-icon" class="password-icon">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                    @error('password')
                                        <label id="password-error" class="error mt-2 text-danger"
                                            for="password">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="confirm_password">@lang('lang.Confirm Password')</label>
                                    <div class="costum-input-password">
                                        <input class="form-control" type="password" id="confirm_password"
                                            name="confirm_password" placeholder="password">
                                        <span id="password-icon" class="password-icon">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                    @error('confirm_password')
                                        <label id="confirm_password-error" class="error mt-2 text-danger"
                                            for="confirm_password">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div> --}}
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="latitude">@lang('lang.Latitude')</label>
                                    <input type="number" class="form-control" value="{{ old('latitude') }}"
                                        id="latitude" name="latitude" placeholder="@lang('lang.Latitude')">
                                    @error('latitude')
                                        <label id="latitude-error" class="error mt-2 text-danger"
                                            for="latitude">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="longitude">@lang('lang.Longitude')</label>
                                    <input type="number" class="form-control" id="longitude" name="longitude"
                                        placeholder="@lang('lang.Longitude')" value="{{ old('longitude') }}">
                                    @error('longitude')
                                        <label id="longitude-error" class="error mt-2 text-danger"
                                            for="longitude">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary me-2">@lang('lang.:name Update', ['name' => ''])</button>

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
            line-height: 1;
        }
    </style>
@endsection
@section('scripts')
    <script src="{{ asset('assets/panel/vendors/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/panel/vendors/jquery-validation/jquery.validate.min.js') }}"></script>
    <script>
        (function($) {
            'use strict';

            function setSelect2Width() {
                if ($(".role_select").length) {
                    $(".role_select").select2();
                }
                if ($(".country_select").length) {
                    $(".country_select").select2();
                }
            }
            setSelect2Width();
            $(window).resize(function() {
                setSelect2Width();
            });
            jQuery.validator.addMethod("lettersonly", function(value, element) {
                return this.optional(element) || /^[a-z\s]+$/i.test(value);
            }, "Letters only please");
            jQuery.validator.addMethod("valueNotEquals", function(value, element, arg) {
                return arg !== value;
            }, "Value must not equal arg.");
            $(function() {
                $("#childDairy").validate({
                    rules: {
                        name: {
                            required: true,
                            lettersonly: true,
                        },
                        email: {
                            email: true,
                        },
                        mobile: {
                            required: true,
                        },
                        role: {
                            required: true,
                            valueNotEquals: 'default'
                        },
                        dairy_name: {
                            required: true,
                            lettersonly: true,
                        },
                        address: {
                            required: true,
                            lettersonly: true,
                        }
                        // latitude: {
                        //     pattern: /^-?([1-8]?[0-9](\.\d+)?|90(\.0+)?)$/
                        // },
                        // longitude: {
                        //     pattern: /^-?((1[0-7][0-9](\.\d+)?|[1-9]?[0-9](\.\d+)?|180(\.0+)?))$/
                        // }
                    },
                    messages: {
                        name: {
                            required: "@lang('validation.required', ['attribute' => __('lang.Name')])",
                            lettersonly: "@lang('lang.name.lettersonly')",
                        },
                        email: {
                            email: "@lang('validation.email', ['attribute' => __('lang.Email ID.')])",
                        },
                        mobile: {
                            required: "@lang('validation.required', ['attribute' => __('lang.Mobile No.')])"
                        },
                        role: {
                            required: "@lang('validation.required', ['attribute' => __('lang.Role')])",
                            valueNotEquals: "@lang('lang.Select :name', ['name' => __('lang.Role')])",
                        },
                        dairy_name: {
                            required: "@lang('validation.required', ['attribute' => __('lang.Dairy Name')])",
                            lettersonly: "@lang('lang.name.lettersonly')",
                        },
                        address: {
                            required: "@lang('validation.required', ['attribute' => __('lang.Dairy Address')])",
                            lettersonly: "@lang('lang.name.lettersonly')",
                        },
                        latitude: {
                            pattern: "@lang('validation.pattern', ['attribute' => __('lang.Latitude')])",
                        },
                        longitude: {
                            pattern: "@lang('validation.pattern', ['attribute' => __('lang.Longitude')])",
                        }
                    },
                    errorPlacement: function(label, element) {
                        label.addClass('mt-2 text-danger');
                        $(element).parent().append(label);
                    },
                    highlight: function(element, errorClass) {
                        $(element).parent().addClass('has-danger')
                        $(element).addClass('form-control-danger')
                    },
                    unhighlight: function(element, errorClass) {
                        $(element).parent().removeClass('has-danger');
                        $(element).removeClass('form-control-danger');
                    },
                    // submitHandler: function(form) {
                    //     form.submit();
                    // }
                });
            });
            $('.role_select').on('change', function() {
                $(this).valid();
            });

        })(jQuery);
    </script>
@endsection
