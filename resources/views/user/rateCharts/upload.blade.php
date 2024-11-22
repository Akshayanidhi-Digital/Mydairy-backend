@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('user.rateCharts.chart.upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group @error('rate_type') has-danger @enderror">
                                    <label for="rate_type">@lang('constants.Farmers')</label>
                                    <select class="rate_type_select w-100 @error('rate_type') form-control-danger @enderror"
                                        name="rate_type" id="rate_type">
                                        <option value="default">@lang('lang.Select Rate Chart Type')</option>
                                        @foreach (config('constant.RCHART_TYPE') as $key => $value)
                                            <option @if (old('rate_type') && old('rate_type') == $key) selected @endif
                                                value="{{ $key }}">@lang('lang.' . $value)</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('rate_type')
                                    <label id="rate_type-error" class="error mt-2 text-danger"
                                        for="rate_type">{{ $message }}</label>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <div class="form-group @error('milk_type') has-danger @enderror">
                                    <label for="milk_type">@lang('lang.Milk Type')</label>
                                    <select class="milk_type_select w-100 @error('milk_type') form-control-danger @enderror"
                                        name="milk_type" id="milk_type">
                                        <option value="default">@lang('lang.Select Milk Type')</option>
                                        @foreach (MILK_TYPE_LIST as $key => $value)
                                            <option @if (old('milk_type') && old('milk_type') == $key) selected @endif
                                                value="{{ $key }}">
                                                @lang('lang.' . $value)</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('milk_type')
                                    <label id="milk_type-error" class="error mt-2 text-danger"
                                        for="milk_type">{{ $message }}</label>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputUsername1">@lang('lang.Rate Chart File')</label>
                            <input type="file" name="rate_chart_file" class="dropify">
                            @error('rate_chart_file')
                                <label id="rate_chart_file-error" class="error mt-2 text-danger"
                                    for="rate_chart_file">{{ $message }}</label>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary me-2">Submit</button>
                        <a href="{{ route('user.rateCharts.list') }}" class="btn btn-light">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/panel/vendors/dropify/dropify.min.css') }}" />
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
    <script src="{{ asset('assets/panel/vendors/dropify/dropify.min.js') }}"></script>
    <script src="{{ asset('assets/panel/vendors/select2/select2.min.js') }}"></script>
    <script>
        (function($) {
            'use strict';

            function setSelect2Width() {
                if ($(".rate_type_select").length) {
                    $(".rate_type_select").select2();
                }
                if ($(".milk_type_select").length) {
                    $(".milk_type_select").select2();
                }
            }
            setSelect2Width();
            $(window).resize(function() {
                setSelect2Width();
            });
            $('.dropify').dropify({
                messages: {
                    default: '@lang('lang.Drag and Drop or clik here to upload Rate chart excel file.')',
                    remove: '@lang('lang.Remove')',
                    error: '@lang('lang.Sorry, the file is too large')'
                }
            });
        })(jQuery);
    </script>
@endsection
