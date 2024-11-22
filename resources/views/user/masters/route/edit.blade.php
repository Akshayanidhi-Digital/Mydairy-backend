@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('user.masters.routes.update',$route->route_id) }}" method="post">
                        @csrf
                        <div class="form-group @error('transporter') has-danger @enderror">
                            <label for="transporter">@lang('lang.Transporter')</label>
                            <select class="transporter_select w-100 @error('transporter') form-control-danger @enderror"
                                name="transporter" id="transporter">
                                <option value="default">@lang('lang.Select :name', ['name' => __('lang.Transporter')])</option>
                                @foreach ($transporter as $data)
                                    <option
                                        @if (old('transporter') && old('transporter') == $data->transporter_id) selected @elseif($route->transporter_id == $data->transporter_id) selected @endif
                                        value="{{ $data->transporter_id }}">{{ $data->transporter_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('transporter')
                            <label id="transporter-error" class="error mt-2 text-danger"
                                for="transporter">{{ $message }}</label>
                        @enderror
                        <div class="form-group @error('route_name') has-danger @enderror">
                            <label for="route_name">@lang('lang.route_name')</label>
                            <input type="text" min="0"
                                class="form-control @error('route_name') form-control-danger @enderror " id="route_name"
                                placeholder="@lang('lang.Name')" value="{{ old('route_name', $route->route_name) }}"
                                name="route_name">
                            @error('route_name')
                                <label id="route_name-error" class="error mt-2 text-danger"
                                    for="route_name">{{ $message }}</label>
                            @enderror
                        </div>
                        <div id="dairy-list-container">
                            @php
                                $old = $route->dairies->pluck('dairy_id');
                                $oldDairyList = old('dairy_list', $old);
                                $countOldDairyList = old('dairy_list') ? count($oldDairyList) : count($old);
                                $dairyListJson = json_encode($dairy_list);
                            @endphp

                            @if ($countOldDairyList > 0)
                                @foreach ($oldDairyList as $index => $oldDairy)
                                    <div class="form-group row" id="group_dairy_list_{{ $index }}">
                                        <label for="dairy_list_{{ $index }}"
                                            class="col-md-3 col-form-label">@lang('lang.Select :name', ['name' => __('lang.Dairy')])</label>
                                        <div class="col-sm-6 mt-1">
                                            <select class="dairy_select w-100" name="dairy_list[]"
                                                id="dairy_list_{{ $index }}">
                                                <option value="">@lang('lang.Select :name', ['name' => __('lang.Dairy')])</option>
                                                @foreach ($dairy_list as $dairy)
                                                    <option value="{{ $dairy->user_id }}"
                                                        {{ $dairy->user_id == $oldDairy ? 'selected' : '' }}>
                                                        {{ $dairy->name . ' - ' . $dairy->role_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-3 mt-1">
                                            @if ($index == $countOldDairyList - 1)
                                                <button type="button"
                                                    data-target="#group_dairy_list_{{ $index }}"class="btn btn-primary  actionbtn form-control">Add
                                                    More</button>
                                            @else
                                                <button type="button" class="btn btn-danger  actionbtn form-control"
                                                    data-target="#group_dairy_list_{{ $index }}">Remove</button>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="form-group row" id="group_dairy_list_0">
                                    <label for="dairy_list_0" class="col-md-3 col-form-label">@lang('lang.Select :name', ['name' => __('lang.Dairy')])</label>
                                    <div class="col-sm-6 mt-1">
                                        <select class="dairy_select w-100" name="dairy_list[]" id="dairy_list_0">
                                            <option value="">@lang('lang.Select :name', ['name' => __('lang.Dairy')])</option>
                                            @foreach ($dairy_list as $dairy)
                                                <option value="{{ $dairy->user_id }}">
                                                    {{ $dairy->name . ' - ' . $dairy->role_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-3 mt-1">
                                        <button type="button"
                                            data-target="#group_dairy_list_0"class="btn btn-primary  actionbtn form-control">Add
                                            More</button>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <button type="submit" class="btn btn-primary me-2">@lang('lang.:name Update', ['name' => __('lang.Route')])</button>
                        <a href="{{ route('user.masters.routes.list') }}" class="btn btn-light">@lang('lang.Back')</a>
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
    <script src="{{ asset('assets/panel/vendors/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/panel/vendors/jquery-validation/jquery.validate.min.js') }}"></script>
    <script>
        (function($) {
            'use strict';

            function setSelect2Width() {
                if ($(".dairy_select").length) {
                    $(".dairy_select").select2();
                }
                if ($(".transporter_select").length) {
                    $(".transporter_select").select2();
                }
            }
            setSelect2Width();
            $(window).resize(function() {
                setSelect2Width();
            });


        })(jQuery);
    </script>
    <script>
        'use strict';

        $(document).ready(function() {
            let counter = {{ $countOldDairyList }};
            let dairyList = @json($dairy_list); // This is used in the getDairyOptions function

            $('#add-more').click(function() {
                addDairySelect();
                updateButtons();
                if ($(".dairy_select").length) {
                    $(".dairy_select").select2();
                }
            });

            function addDairySelect() {
                counter++;
                let selectId = `dairy_list_${counter}`;
                let options = getDairyOptions();
                let newSelect = `
                    <div class="form-group row" id="group_${selectId}">
                        <label for="${selectId}" class="col-md-3 col-form-label">@lang('lang.Select :name', ['name' => __('lang.Dairy')])</label>
                        <div class="col-sm-6 mt-1">
                            <select class="dairy_select w-100" name="dairy_list[]" id="${selectId}">
                                ${options}
                            </select>
                        </div>
                        <div class="col-sm-3 mt-1">
                            <button type="button" class="btn btn-danger  actionbtn form-control" data-target="#group_${selectId}">Remove</button>
                        </div>
                    </div>`;
                $('#dairy-list-container').append(newSelect);
            }

            function getDairyOptions() {
                let selectedDairies = getSelectedDairies();
                let options = '<option value="">@lang('lang.Select :name', ['name' => __('lang.Dairy')])</option>';
                dairyList.forEach(function(dairy) {
                    if (!selectedDairies.includes(dairy.user_id)) {
                        options +=
                            `<option value="${dairy.user_id}">${dairy.name} - ${dairy.role_name}</option>`;
                    }
                });
                return options;
            }

            function getSelectedDairies() {
                let selectedDairies = [];
                $('.dairy_select').each(function() {
                    let value = $(this).val();
                    if (value) {
                        selectedDairies.push(value);
                    }
                });
                return selectedDairies;
            }

            $(document).on('change', '.dairy_select', function() {
                updateOptions();
            });

            function updateOptions() {
                let selectedDairies = getSelectedDairies();
                $('.dairy_select').each(function() {
                    let currentSelect = $(this);
                    let currentValue = currentSelect.val();
                    let options = '<option value="">@lang('lang.Select :name', ['name' => __('lang.Dairy')])</option>';
                    dairyList.forEach(function(dairy) {
                        if (!selectedDairies.includes(dairy.user_id) || dairy.user_id ===
                            currentValue) {
                            let selectedAttribute = (dairy.user_id === currentValue) ? 'selected' :
                                '';
                            options +=
                                `<option value="${dairy.user_id}" ${selectedAttribute}>${dairy.name} - ${dairy.role_name}</option>`;
                        }
                    });
                    currentSelect.html(options);
                });
            }

            function updateButtons() {
                let totalSelects = $('.dairy_select').length;
                $('.form-group.row').each(function(index) {
                    if (index === totalSelects - 1) {
                        $(this).find('.actionbtn').removeClass('btn-danger').addClass('btn-primary').text(
                            'Add More').off('click').on('click', function() {
                            addDairySelect();
                            updateButtons();
                            if ($(".dairy_select").length) {
                                $(".dairy_select").select2();
                            }
                        });
                    } else {
                        $(this).find('.actionbtn').removeClass('btn-primary').addClass('btn-danger').text(
                            'Remove').off('click').on('click', function() {
                            let target = $(this).data('target');
                            $(target).remove();
                            updateButtons();
                            updateOptions();
                        });
                    }
                });
            }

            updateButtons();
        });
    </script>
@endsection
