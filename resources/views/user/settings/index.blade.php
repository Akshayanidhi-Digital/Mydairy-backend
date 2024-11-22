@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ $title }}</h4>
                    <form action="{{ route('user.settings.update') }}" method="post">
                        @csrf
                        <div class="form-group @error('language') has-danger @enderror">
                            <label for="language">@lang('lang.Change Language') </label>
                            <select class="language_select w-100 @error('language') form-control-danger @enderror"
                                name="language" id="language">
                                <option value="default">@lang('lang.Select language')</option>
                                <option
                                    @if (old('language') && old('language') == 'en') selected @elseif($settings->lang == 'en') selected @endif
                                    value="en">@lang('lang.English')
                                </option>
                                <option
                                    @if (old('language') && old('language') == 'hi') selected @elseif($settings->lang == 'hi') selected @endif
                                    value="hi">@lang('lang.Hindi')
                                </option>
                            </select>
                        </div>
                        @error('language')
                            <label id="language-error" class="error mt-2 text-danger" for="language">{{ $message }}</label>
                        @enderror
                        <p>@lang("lang.Printer Settings")</p>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">@lang('lang.Font Size')</label>
                            <div class="col-sm-3">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="print_font_size" id="print_font_size1"
                                            value="N" @checked($settings->print_font_size == 'N')>
                                        @lang('lang.Normal')
                                        <i class="input-helper"></i></label>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="print_font_size" id="print_font_size2"
                                            value="M" @checked($settings->print_font_size == 'M')>
                                        @lang('lang.Medium')
                                        <i class="input-helper"></i></label>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="print_font_size" id="print_font_size2"
                                            value="L" @checked($settings->print_font_size == 'L')>
                                        @lang("lang.Large")
                                        <i class="input-helper"></i></label>
                                </div>
                            </div>
                        </div>
                        @error('print_font_size')
                            <label id="language-error" class="error mt-2 text-danger" for="language">{{ $message }}</label>
                        @enderror
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">@lang('lang.Printer Width')</label>
                            <div class="col-sm-4">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="print_size" id="print_size1"
                                            value="2" @checked($settings->print_size == '2')>
                                        2 @lang('lang.INCH')
                                        <i class="input-helper"></i></label>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="print_size" id="print_size2"
                                            value="3" @checked($settings->print_size == '3')>
                                        3 @lang('lang.INCH')
                                        <i class="input-helper"></i></label>
                                </div>
                            </div>
                        </div>
                        @error('print_size')
                            <label id="language-error" class="error mt-2 text-danger" for="language">{{ $message }}</label>
                        @enderror
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">@lang('lang.Weight Setting')</label>
                            <div class="col-sm-3">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="wight" id="wight1"
                                            value="W" @checked($settings->wight == 'W')>
                                        @lang('lang.Weight')
                                        <i class="input-helper"></i></label>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="wight" id="wight2"
                                            value="Q" @checked($settings->wight == 'Q')>
                                        @lang('lang.Quantity')
                                        <i class="input-helper"></i></label>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="wight" id="wight2"
                                            value="L" @checked($settings->wight == 'L')>
                                        @lang('lang.Liter')
                                        <i class="input-helper"></i></label>
                                </div>
                            </div>
                        </div>
                        @error('wight')
                            <label id="language-error" class="error mt-2 text-danger"
                                for="language">{{ $message }}</label>
                        @enderror
                        <div class="form-group row">
                            <label class="col-sm-9 col-form-label">@lang('lang.Print Receipt')</label>
                            <label class="col-sm-3 checkbox-inline">
                                <input type="checkbox" data-style="quick" name="print_recipt"
                                    @checked($settings->print_recipt) data-on="@lang('lang.on')" data-Off="@lang('lang.off')" data-toggle="toggle">
                            </label>
                        </div>
                        @error('print_recipt')
                            <label id="language-error" class="error mt-2 text-danger"
                                for="language">{{ $message }}</label>
                        @enderror
                        <div class="form-group row">
                            <label class="col-sm-9 col-form-label">@lang('lang.Print Receipt All')</label>
                            <label class="col-sm-3 checkbox-inline">
                                <input type="checkbox" data-style="quick" name="print_recipt_all"
                                    @checked($settings->print_recipt_all) data-on="@lang('lang.on')" data-Off="@lang('lang.off')" data-toggle="toggle">
                            </label>
                        </div>
                        @error('print_recipt_all')
                            <label id="language-error" class="error mt-2 text-danger"
                                for="language">{{ $message }}</label>
                        @enderror
                        <div class="form-group row">
                            <label class="col-sm-9 col-form-label">@lang('lang.WhatsApp Message')</label>
                            <label class="col-sm-3 checkbox-inline">
                                <input type="checkbox" data-style="quick" name="whatsapp_message"
                                    @checked($settings->whatsapp_message) data-on="@lang('lang.on')" data-Off="@lang('lang.off')" data-toggle="toggle">
                            </label>
                        </div>
                        @error('whatsapp_message')
                            <label id="language-error" class="error mt-2 text-danger"
                                for="language">{{ $message }}</label>
                        @enderror
                        <div class="form-group row">
                            <label class="col-sm-9 col-form-label">@lang('lang.Auto Fats')</label>
                            <label class="col-sm-3 checkbox-inline">
                                <input type="checkbox" data-style="quick" name="auto_fats" @checked($settings->auto_fats)
                                data-on="@lang('lang.on')" data-Off="@lang('lang.off')" data-toggle="toggle">
                            </label>
                        </div>
                        @error('auto_fats')
                            <label id="language-error" class="error mt-2 text-danger"
                                for="language">{{ $message }}</label>
                        @enderror
                        <button class="btn btn-primary" type="Submit">@lang('lang.Update Setting')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/panel/vendors/bootstrap-toggle/bootstrap-toggle.min.css') }}">
    <style>
        .toggle-group .btn-default {
            color: #020202;
            background-color: #e4e4e4;
            border-color: #8d8d8d;
        }

        .toggle.btn,
        .toggle-handle.btn {
            border-radius: 10px;
        }

        .toggle-handle.btn {
            background: #979797;
        }

        /* btn-default */
    </style>
@endsection
@section('scripts')
    <script src="{{ asset('assets/panel/vendors/bootstrap-toggle/bootstrap-toggle.min.js') }}"></script>
@endsection
