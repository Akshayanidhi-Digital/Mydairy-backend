@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('user.profile.update') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="avatar-upload">
                            <div class="avatar-edit">
                                <input type='file' name="profile_image" id="imageUpload" accept=".png, .jpg" />
                                <label for="imageUpload"></label>
                            </div>
                            <div class="avatar-preview">
                                <div id="imagePreview"
                                    style="background-image: url('{{ asset($user->profile->image_path) }}');">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="name">@lang('lang.Name')</label>
                                    <input type="text" class="form-control" id="name"
                                        value="{{ old('name', $user->name) }}" name="name"
                                        placeholder="@lang('lang.Name')">
                                    @error('name')
                                        <label id="name-error" class="error mt-2 text-danger"
                                            for="name">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="father_name">@lang('lang.Father Name')</label>
                                    <input type="text" class="form-control" id="father_name"
                                        value="{{ old('father_name', $user->father_name) }}" name="father_name"
                                        placeholder="@lang('lang.Father Name')">
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
                                        value="{{ old('email', $user->email) }}" name="email"
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
                                        class="country_code_select w-100 @error('country_code') form-control-danger @enderror"
                                        name="country_code" id="country_code">
                                        <option value="default">@lang('lang.Select :name', ['name' => __('lang.Country')])</option>
                                        <option value="+91"@if (old('country_code', $user->country_code) == '+91') selected @endif>
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
                                    <input type="number" class="form-control" value="{{ old('mobile', $user->mobile) }}"
                                        id="mobile" name="mobile" placeholder="@lang('lang.Mobile')">
                                    @error('mobile')
                                        <label id="mobile-error" class="error mt-2 text-danger"
                                            for="mobile">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="dairy_name">@lang('lang.Dairy Name')</label>
                                    <input type="text" class="form-control" id="dairy_name"
                                        value="{{ old('dairy_name', $user->profile->dairy_name) }}" name="dairy_name"
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
                                        value="{{ old('address', $user->profile->address) }}" id="address" name="address"
                                        placeholder="@lang('lang.Dairy Address')">
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
                            <input type="hidden" name="latitude" id="latitude"
                                value="{{ $user->profile->latitude }}">
                            <input type="hidden" name="longitude" id="longitude"
                                value="{{ $user->profile->longitude }}">
                        </div>
                        <button type="submit" class="btn btn-primary mr-2">@lang('lang.:name Update', ['name' => __('constants.Profile')])</button>
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
        .avatar-upload {
            position: relative;
            max-width: 205px;
            margin: 50px auto;
        }

        .avatar-upload .avatar-edit {
            position: absolute;
            right: 12px;
            z-index: 1;
            top: 10px;
        }

        .avatar-upload .avatar-edit input {
            display: none;
        }

        .avatar-upload .avatar-edit input+label {
            display: inline-block;
            width: 34px;
            height: 34px;
            margin-bottom: 0;
            border-radius: 100%;
            background: #FFFFFF;
            border: 1px solid transparent;
            box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.12);
            cursor: pointer;
            font-weight: normal;
            transition: all 0.2s ease-in-out;
        }

        .avatar-upload .avatar-edit input+label:hover {
            background: #f1f1f1;
            border-color: #d6d6d6;
        }

        .avatar-upload .avatar-edit input+label:after {
            content: "\f040";
            font-family: 'FontAwesome';
            color: #757575;
            position: absolute;
            top: 10px;
            left: 0;
            right: 0;
            text-align: center;
            margin: auto;
        }

        .avatar-upload .avatar-preview {
            width: 192px;
            height: 192px;
            position: relative;
            border-radius: 100%;
            border: 6px solid #F8F8F8;
            box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1);
        }

        .avatar-upload .avatar-preview>div {
            width: 100%;
            height: 100%;
            border-radius: 100%;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
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
    <script src="{{ asset('assets/panel/vendors/select2/select2.min.js') }}"></script>
    <script>
        (function($) {
            'use strict';

            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#imagePreview').css('background-image', 'url(' + e.target.result + ')');
                        $('#imagePreview').hide();
                        $('#imagePreview').fadeIn(650);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
            $("#imageUpload").change(function() {
                readURL(this);
            });

            function setSelect2Width() {
                if ($(".country_code_select").length) {
                    $(".country_code_select").select2();
                }
            }
            setSelect2Width();
            $(window).resize(function() {
                setSelect2Width();
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
