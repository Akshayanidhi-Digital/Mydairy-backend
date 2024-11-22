@extends('layouts.transport')
@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">@lang('lang.Add :name', ['name' => 'Driver'])</h4>
                    <form id="childDairy" action="{{ route('transport.driver.store') }}" method="POST" autocomplete="off">
                        @csrf
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="name">@lang('lang.Name')</label>
                                    <input type="text" class="form-control" id="name" value="{{ old('name') }}"
                                        name="name" placeholder="@lang('lang.Name')">
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
                                        id="father_name" placeholder="@lang('lang.Father Name')" value="{{ old('father_name') }}"
                                        name="father_name">
                                    @error('father_name')
                                        <label id="father_name-error" class="error mt-2 text-danger"
                                            for="father_name">{{ $message }}</label>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="email">@lang('lang.Email ID.')</label>
                                    <input type="email" class="form-control" id="email" value="{{ old('email') }}"
                                        name="email" placeholder="example@gmail.com">
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
                                        <option value="">@lang('lang.Select :name', ['name' => __('lang.Country')])</option>
                                        <option @if(old('country_code') == '+91') selected  @endif value="+91">@lang('lang.India')</option>
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
                                    <input type="number" class="form-control" value="{{ old('mobile') }}" id="mobile"
                                        name="mobile" placeholder="@lang('lang.Mobile')">
                                    @error('mobile')
                                        <label id="mobile-error" class="error mt-2 text-danger"
                                            for="mobile">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary me-2">@lang('lang.Add :name', ['name' => 'Driver'])</button>
                        <a href="{{ route('transport.driver.index') }}" class="btn btn-light">@lang('lang.Back')</a>
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

        })(jQuery);
    </script>
@endsection
