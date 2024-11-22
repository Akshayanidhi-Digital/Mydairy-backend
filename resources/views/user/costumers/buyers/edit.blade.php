@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">@lang('lang.:name Update', ['name' => __('lang.Buyer')])</h4>
                    <form id="BuyerUpdate" action="{{ route('user.buyers.update', $buyer->buyer_id) }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group @error('name') has-danger @enderror">
                                    <label for="name">@lang('lang.Name')</label>
                                    <input type="text" class="form-control @error('name') form-control-danger @enderror"
                                        id="name" placeholder="@lang('lang.Name')" name="name"
                                        value="{{ old('name') ?? $buyer->name }}">
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
                                        value="{{ old('father_name') ?? $buyer->father_name }}" name="father_name">
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
                                        placeholder="emaple@gmail.com" value="{{ old('email') ?? $buyer->email }}"
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
                                        <option value="+91"
                                            @if (old('country_code') && old('country_code') == '+91') selected
                                        @elseif($buyer->country_code == '+91')
                                            selected @endif>
                                            @lang('lang.India')</option>
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
                                        placeholder="@lang('lang.Mobile No.')" value="{{ old('mobile') ?? $buyer->mobile }}"
                                        name="mobile">
                                    @error('mobile')
                                        <label id="mobile-error" class="error mt-2 text-danger"
                                            for="mobile">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="mobile">@lang('lang.Advance Option')</label>
                                <button type="button" id="advanceOptionBtn" class="form-control btn btn-primary mr-2">Advance Option</button>
                            </div>
                        </div>
                        <div class="row" id="advanceOption" @if(old('is_fixed_rate')) style="" @elseif ($buyer->is_fixed_rate == 1) style=""  @else style="display: none" @endif>
                            <div class="col-md-6">
                                <div class="row my-2">
                                    <label class="col-sm-9 col-form-label">@lang('lang.Fixed Rate')</label>
                                    <label class="col-sm-3 checkbox-inline">
                                        <input type="checkbox" data-style="quick" name="is_fixed_rate" @checked($buyer->is_fixed_rate)
                                        data-on="@lang('lang.on')" data-Off="@lang('lang.off')" data-toggle="toggle">
                                    </label>
                                    @error('is_fixed_rate')
                                    <label id="fat_rate-error" class="error mt-2 text-danger"
                                        for="fat_rate">{{ $message }}</label>
                                @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row my-2">
                                    <label class="col-sm-9 col-form-label">@lang('lang.Fixed Rate Type')</label>
                                    <label class="col-sm-3 checkbox-inline">
                                        <input type="checkbox" data-style="quick" name="fixed_rate_type"   @if(old('fixed_rate_type') == 'on') checked  @elseif($buyer->fixed_rate_type == 1) checked @endif
                                        data-Off="@lang('lang.Fixed Rate')" data-on="@lang('lang.Fat Rate')" data-toggle="toggle">
                                    </label>
                                    @error('fixed_rate_type')
                                    <label id="fat_rate-error" class="error mt-2 text-danger"
                                        for="fat_rate">{{ $message }}</label>
                                @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group @error('fat_rate') has-danger @enderror">
                                    <label for="mobile">@lang('lang.Fat Rate Value')</label>
                                    <input type="number" min="0"
                                        class="form-control @error('fat_rate') form-control-danger @enderror " id="fat_rate"
                                        placeholder="@lang('lang.Fat Rate Value.')" step="0.01" value="{{ old('fat_rate') ?? $buyer->fat_rate }}"
                                        name="fat_rate">
                                    @error('fat_rate')
                                        <label id="fat_rate-error" class="error mt-2 text-danger"
                                            for="fat_rate">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group @error('rate') has-danger @enderror">
                                    <label for="mobile">@lang('lang.Fixed Rate Value')</label>
                                    <input type="number" min="0"
                                        class="form-control @error('rate') form-control-danger @enderror " id="rate"
                                        placeholder="@lang('lang.Fixed Rate Value.')" step="0.01" value="{{ old('rate') ?? $buyer->rate }}"
                                        name="rate">
                                    @error('rate')
                                        <label id="rate-error" class="error mt-2 text-danger"
                                            for="rate">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>

                        </div>
                        <button type="submit" class="btn btn-primary mr-2">@lang('lang.:name Update', ['name' => __('lang.Buyer')])</button>
                        <a href="{{ route('user.buyers.list') }}" class="btn btn-light mr-2">@lang('lang.Back')</a>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/panel/vendors/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/panel/vendors/select2-bootstrap-theme/select2-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/panel/vendors/bootstrap-toggle/bootstrap-toggle.min.css') }}">
    <style>
        .toggle-group .btn-default {
            color: #020202;
            background-color: #e4e4e4;
            border-color: #8d8d8d;
        }
        .toggle.btn {
            width: 100%!important;
            width: -webkit-fill-available!important;
            height: 40px!important;
        }

        .toggle.btn,
        .toggle-handle.btn {
            border-radius: 10px;
        }

        .toggle-handle.btn {
            background: #979797;
        }

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
    <script src="{{ asset('assets/panel/vendors/bootstrap-toggle/bootstrap-toggle.min.js') }}"></script>

    <script src="{{ asset('assets/panel/vendors/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/panel/vendors/jquery-validation/jquery.validate.min.js') }}"></script>
    <script>
        (function($) {
            'use strict';

            $('#advanceOptionBtn').on('click',function(){
                $('#advanceOption').toggle();
            });
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
                $("#BuyerUpdate").validate({
                    rules: {
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
