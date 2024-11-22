@extends('layouts.transport')
@section('content')
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">@lang('lang.Add :name', ['name' => 'Driver'])</h4>
                    <form id="childDairy" action="{{ route('transport.vehicle.store') }}" method="POST" autocomplete="off">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group @error('driver') has-danger @enderror">
                                    <label for="driver">@lang('lang.Driver')</label>
                                    <select class="driver_select w-100 @error('driver') form-control-danger @enderror"
                                        name="driver" id="driver">
                                        <option value="">@lang('lang.Select :name', ['name' => 'Driver'])</option>
                                        @foreach ($drivers as $driver)
                                            <option @if (old('driver') == $driver->driver_id) selected @endif
                                                value="{{ $driver->driver_id }}">{{ $driver->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('driver')
                                    <label id="driver-error" class="error mt-2 text-danger"
                                        for="driver">{{ $message }}</label>
                                @enderror
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="name">vehicle number</label>
                                    <input type="text" class="form-control" id="vehicle_number"
                                        value="{{ old('vehicle_number') }}" style='text-transform:uppercase' name="vehicle_number"
                                        placeholder="XX XX XX XXXX">
                                    @error('vehicle_number')
                                        <label id="vehicle_number-error" class="error mt-2 text-danger"
                                            for="vehicle_number">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group @error('quantity') has-danger @enderror">
                                    <label for="quantity">Quantity</label>
                                    <input type="number"
                                        class="form-control @error('quantity') form-control-danger @enderror "
                                        id="quantity" placeholder="Quantity" value="{{ old('quantity') }}"
                                        name="quantity">
                                    @error('quantity')
                                        <label id="quantity-error" class="error mt-2 text-danger"
                                            for="quantity">{{ $message }}</label>
                                    @enderror

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group @error('unit_type') has-danger @enderror">
                                    <label for="unit_type">Unit Type</label>
                                    <select class="unit_type_select w-100 @error('unit_type') form-control-danger @enderror"
                                        name="unit_type" id="unit_type">
                                        <option value="">@lang('lang.Select :name', ['name' => 'unit type'])</option>
                                        @foreach (VEHICLE_UNIT as $unit_type)
                                            <option @if (old('unit_type') == $unit_type) selected @endif
                                                value="{{ $unit_type }}">{{ $unit_type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('unit_type')
                                    <label id="unit_type-error" class="error mt-2 text-danger"
                                        for="unit_type">{{ $message }}</label>
                                @enderror
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
                if ($(".driver_select").length) {
                    $(".driver_select").select2();
                }
                if ($(".unit_type_select").length) {
                    $(".unit_type_select").select2();
                }
            }
            setSelect2Width();
            $(window).resize(function() {
                setSelect2Width();
            });

        })(jQuery);
    </script>
@endsection
