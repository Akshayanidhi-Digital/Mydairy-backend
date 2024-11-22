@extends('layouts.app')
@section('content')
    <div class="row  my-2">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title p-3" class="forms-sample">@lang('Lang.Milk Sale')</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <form id="milkAddRecord" autocomplete="off" action="{{ route('user.MilkSell.store') }}"
                                method="POST">
                                @csrf
                                <input type="hidden" name="shift" value="{{ app('request')->input('shift') }}">
                                <input type="hidden" name="date" value="{{ app('request')->input('date') }}">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group @error('buyer') has-danger @enderror">
                                            <label for="buyer">@lang('lang.Buyer')</label>
                                            <select class="buyer_select w-100 @error('buyer') form-control-danger @enderror"
                                                name="buyer" id="buyer">
                                                <option value="default">@lang('lang.Select :name', ['name' => __('lang.Buyer')])</option>
                                                @foreach ($buyers as $buyer)
                                                    <option @if (old('buyer') && old('buyer') == $buyer->id) selected @endif
                                                        value="{{ $buyer->id }}">{{ $buyer->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('buyer')
                                            <label id="buyer-error" class="error mt-2 text-danger"
                                                for="buyer">{{ $message }}</label>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group @error('milk_type') has-danger @enderror">
                                            <label for="milk_type">@lang('lang.Milk Type')</label>
                                            <select
                                                class="milk_type_select w-100 @error('milk_type') form-control-danger @enderror"
                                                name="milk_type" id="milk_type">
                                                <option value="default">@lang('lang.Select :name', ['name' => __('lang.Milk Type')])</option>
                                                <option @if (old('milk_type') && old('milk_type') == 'Cow') selected @endif value="Cow">
                                                    @lang('lang.Cow')
                                                </option>
                                                <option @if (old('milk_type') && old('milk_type') == 'Buffalo') selected @endif value="Buffalo">
                                                    @lang('lang.Buffalo')</option>
                                                <option @if (old('milk_type') && old('milk_type') == 'Mix') selected @endif value="Mix">
                                                    @lang('lang.Mix')
                                                </option>
                                                <option @if (old('milk_type') && old('milk_type') == 'Other') selected @endif value="Other">
                                                    @lang('lang.Other')</option>
                                            </select>
                                        </div>
                                        @error('milk_type')
                                            <label id="milk_type-error" class="error mt-2 text-danger"
                                                for="milk_type">{{ $message }}</label>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group @error('quantity') has-danger @enderror">
                                            <label for="quantity">@lang('lang.Quantity')</label>
                                            <input type="number" min="0" @disabled(old('quantity') ? false : true)
                                                class="form-control @error('quantity') form-control-danger @enderror "
                                                id="quantity" placeholder="0.0" value="{{ old('quantity') }}"
                                                name="quantity">
                                            @error('quantity')
                                                <label id="quantity-error" class="error mt-2 text-danger"
                                                    for="quantity">{{ $message }}</label>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group @error('fat') has-danger @enderror">
                                            <label for="fat">@lang('lang.FAT')</label>
                                            <input type="number" min="0" @disabled(old('fat') ? false : true)
                                                class="form-control @error('fat') form-control-danger @enderror "
                                                id="fat" placeholder="0.0" value="{{ old('fat') }}" name="fat">
                                            @error('fat')
                                                <label id="fat-error" class="error mt-2 text-danger"
                                                    for="fat">{{ $message }}</label>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group @error('snf') has-danger @enderror">
                                            <label for="snf">@lang('lang.SNF')</label>
                                            <input type="number" min="0" @disabled(old('snf') ? false : true)
                                                class="form-control @error('snf') form-control-danger @enderror "
                                                id="snf" placeholder="0.0" value="{{ old('snf') }}"
                                                name="snf">
                                            @error('snf')
                                                <label id="snf-error" class="error mt-2 text-danger"
                                                    for="snf">{{ $message }}</label>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group @error('clr') has-danger @enderror">
                                            <label for="clr">@lang('lang.CLR')</label>
                                            <input type="number" min="0" @disabled(old('clr') ? false : true)
                                                class="form-control @error('clr') form-control-danger @enderror "
                                                id="clr" placeholder="0.0" value="{{ old('clr') }}"
                                                name="clr">
                                            @error('clr')
                                                <label id="clr-error" class="error mt-2 text-danger"
                                                    for="clr">{{ $message }}</label>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="milkdetails my-2">
                                    Rate/Ltr: NA Total Amount: NA
                                </div>
                                <button id="milkAddRecordButton" type="submit" class="btn btn-primary form-control">
                                    @lang('lang.Record Add')
                                </button>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <div id="buyerProfile" style="display: none">
                                <div class="text-center pb-4">
                                    <img src="{{ asset('assets/default.png') }}" alt="profile"
                                        class="img-lg rounded-circle mb-3">
                                    <div class="mb-3">
                                        <h3 id="fp_name">@lang('lang.Unknown')</h3>
                                        <div class="d-flex align-items-center justify-content-center">
                                            <h5 class="mb-0 me-2 text-muted" id="fp_address">NA</h5>
                                        </div>
                                    </div>
                                    <p id="fp_moreDetails" class="w-75 mx-auto mb-3">
                                        NA</p>
                                </div>
                            </div>
                            <div id="nobuyerProfile">
                                <div class="text-center pb-4">
                                    <p class="w-75 mx-auto mb-3">@lang('lang.Select buyer for Milk Procure')</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title">milk Table</h4>
                        @if ($milkrecords->count() > 0)
                            <a href="{{ route('user.MilkSell.print.all', [app()->request->date, app()->request->shift]) }}"
                                class="btn btn-primary">@lang('lang.Print All')</a>
                        @endif
                    </div>
                    <div class="table-responsive pt-3">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>@lang('lang.ID')</th>
                                    <th>@lang('lang.Shift')</th>
                                    <th>@lang('lang.Time')</th>
                                    <th>@lang('lang.Name')</th>
                                    <th>@lang('lang.Milk Type')</th>
                                    <th>@lang('lang.Quantity')</th>
                                    <th>@lang('lang.FAT')</th>
                                    <th>@lang('lang.SNF')</th>
                                    <th>@lang('lang.CLR')</th>
                                    <th>@lang('lang.Amount')</th>
                                    <th>@lang('lang.Total Amount')</th>
                                    <th>@lang('lang.Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($milkrecords->count() > 0)
                                    @foreach ($milkrecords as $index => $milkrecord)
                                        <tr>
                                            <td>
                                                {{ $index + 1 }}
                                            </td>
                                            <td>
                                                {{ $milkrecord->shift }}
                                            </td>
                                            <td>
                                                {{ $milkrecord->time }}
                                            </td>
                                            <td>
                                                {{ $milkrecord->buyer->name }} s/o {{ $milkrecord->buyer->father_name }}
                                            </td>
                                            <td>
                                                {{ array_search($milkrecord->milk_type, MILK_TYPE) }}
                                            </td>
                                            <td>{{ number_format($milkrecord->quantity, 2) }}</td>
                                            <td>
                                                {{ $milkrecord->fat != 0 ? number_format($milkrecord->fat, 2) : 'NA' }}
                                            </td>
                                            <td>
                                                {{ $milkrecord->snf != 0 ? number_format($milkrecord->snf, 2) : 'NA' }}
                                            </td>
                                            <td>
                                                {{ $milkrecord->clr != 0 ? number_format($milkrecord->clr, 2) : 'NA' }}
                                            </td>


                                            <td>
                                                &#8377; {{ number_format($milkrecord->price, 2) }}
                                            </td>

                                            <td>
                                                &#8377; {{ number_format($milkrecord->total_price, 2) }}
                                            </td>
                                            <td class="text-center">
                                                <button id="actionOnMilkRecord" type="button"
                                                    data-record-id="{{ $milkrecord->id }}"
                                                    class="btn btn btn-outline-secondary btn-icon">
                                                    {{-- Action --}}
                                                    {{-- <i class="ti-more btn-icon-append"></i> --}}
                                                    <i class="fa fa-ellipsis-v text-primary"></i>
                                                </button>
                                                {{-- <button
                                                    type="button" class="btn btn-info btn-lg btn-block">Action
                                                    <i class="ti-menu float-right"></i>
                                                </button> --}}
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="3"></td>
                                        <td>@lang('lang.Total') : {{ number_format($quantity, 2) }}</td>
                                        <td>@lang('lang.Avg.') : {{ number_format($fat / $milkrecords->total(), 2) }}</td>
                                        <td>@lang('lang.Avg.') : {{ number_format($snf / $milkrecords->total(), 2) }}</td>
                                        <td>@lang('lang.Avg.') : {{ number_format($clr / $milkrecords->total(), 2) }}</td>
                                        <td colspan="4">@lang('lang.Total') : &#8377;
                                            {{ number_format($total_price, 2) }}</td>
                                    </tr>
                                    @if ($milkrecords->count() > 10)
                                        <tr>
                                            <td colspan="11">
                                                <div class="d-flex justify-content-center align-items-center">
                                                    {{ $milkrecords->links('pagination::bootstrap-4') }}
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @else
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/panel/vendors/jquery-contextmenu/jquery.contextMenu.min.css') }}">
    </link>

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

        .contextmenu-customwidth {
            width: 100px !important;
            width: max-content !important;
            min-width: 80px !important;
        }
    </style>
@endsection

@section('scripts')
    <script src="{{ asset('assets/panel/vendors/jquery-contextmenu/jquery.contextMenu.min.js') }}"></script>
    <script src="{{ asset('assets/panel/vendors/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/panel/vendors/jquery-validation/jquery.validate.min.js') }}"></script>
    <script>
        (function($) {
            'use strict';

            function setSelect2Width() {
                if ($(".buyer_select").length) {
                    $(".buyer_select").select2();
                }
                if ($(".milk_type_select").length) {
                    $(".milk_type_select").select2();
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
            jQuery.validator.addMethod("customPattern", function(value, element) {
                return this.optional(element) || /^\d+(\.\d)?$/.test(value);
            }, "The value must match the specified pattern.");
            $(function() {
                $("#milkAddRecord").validate({
                    rules: {
                        buyer: {
                            required: true,
                            valueNotEquals: 'default'
                        },
                        milk_type: {
                            required: true,
                            valueNotEquals: 'default'
                        },
                        quantity: {
                            required: true,
                            min: 0,
                            customPattern: true
                        },
                        fat: {
                            required: true,
                            min: 0,
                            customPattern: true
                        },
                        snf: {
                            required: true,
                            min: 0,
                            customPattern: true
                        },
                        clr: {
                            required: true,
                            min: 0,
                            customPattern: true
                        }
                    },
                    messages: {
                        buyer: {
                            required: "@lang('lang.Please') @lang('lang.Select :name', ['name' => __('lang.Buyer')])",
                            valueNotEquals: "@lang('lang.Please') @lang('lang.Select :name', ['name' => __('lang.Buyer')])",
                        },
                        milk_type: {
                            required: "@lang('lang.Please') @lang('lang.Select Milk Type')",
                            valueNotEquals: "@lang('lang.Please') @lang('lang.Select Milk Type')",
                        },
                        quantity: {
                            required: "@lang('validation.required', ['attribute' => __('lang.Quantity')])",
                            min: "@lang('validation.gt.numeric', ['attribute' => __('lang.Quantity'), 'value' => 0])",
                            customPattern: "@lang('lang.:name pattern', ['name' => __('lang.Quantity')])",
                        },
                        fat: {
                            required: "@lang('validation.required', ['attribute' => __('lang.FAT')])",
                            min: "@lang('validation.gt.numeric', ['attribute' => __('lang.FAT'), 'value' => 0])",
                            customPattern: "@lang('lang.:name pattern', ['name' => __('lang.FAT')])",
                        },
                        snf: {
                            required: "@lang('validation.required', ['attribute' => __('lang.SNF')])",
                            min: "@lang('validation.gt.numeric', ['attribute' => __('lang.SNF'), 'value' => 0])",
                            customPattern: "@lang('lang.:name pattern', ['name' => __('lang.SNF')])",
                        },
                        clr: {
                            required: "@lang('validation.required', ['attribute' => __('lang.CLR')])",
                            min: "@lang('validation.gt.numeric', ['attribute' => __('lang.CLR'), 'value' => 0])",
                            customPattern: "@lang('lang.:name pattern', ['name' => __('lang.CLR')])",
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
                });
            });
            $('#milk_type').on('change', function() {
                $(this).valid();
            });
            $('#buyer').on('change', function() {
                $(this).valid();
                if ($(this).val() > 0) {
                    $('#quantity, #fat, #snf, #clr').prop('disabled', true);
                    $('#quantity, #fat, #snf, #clr').rules('add', {
                        required: true,
                        min: 0
                    });
                    let _token = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('user.MilkSell.buyer.info') }}",
                        data: {
                            _token: _token,
                            buyer_id: $('#buyer').val()
                        },
                        success: function(response) {
                            if (response.success) {
                                var buyer = response.data.buyer;
                                if (buyer.is_fixed_rate == 1) {
                                    if (buyer.fixed_rate_type == 0) {
                                        $('#quantity').prop('disabled', false);
                                        $('#quantity').rules('add', {
                                            required: true,
                                            min: 0
                                        });
                                    }
                                    if (buyer.fixed_rate_type == 1) {
                                        $('#fat').val(buyer.fate_rate);
                                        $('#quantity, #fat').prop('disabled', false);
                                        $('#quantity, #fat').rules('add', {
                                            required: true,
                                            min: 0
                                        });
                                    }
                                } else {
                                    $('#quantity, #fat, #snf, #clr').prop('disabled', false);
                                    $('#quantity, #fat, #snf, #clr').rules('add', {
                                        required: true,
                                        min: 0
                                    });
                                }
                                $('#buyerProfile').html(response.data.html);
                                $('#nobuyerProfile').hide();
                                $('#buyerProfile').show();
                            } else {
                                iziToast.error({
                                    message: response.message,
                                    position: "topRight",
                                    timeout: 1500,
                                });
                            }
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            iziToast.error({
                                message: xhr.responseJSON.message,
                                position: "topRight",
                                timeout: 1500,
                            });
                        }
                    });
                    $('#quantity, #fat, #snf, #clr').on('input', calculateAndDisplay);

                } else {
                    $('#quantity, #fat, #snf, #clr').prop('disabled', true);
                    $('#quantity, #fat, #snf, #clr').rules('add', {
                        required: true,
                        min: 0
                    });
                }
            });

            function calculateAndDisplay() {
                var html = 'Rate/Ltr: NA Total Amount: NA ';
                $('.milkdetails').html(html);
                var buyerId = $('#buyer').val();
                var milk_type = $('#milk_type').val();
                var quantity = parseFloat($('#quantity').val());
                var fat = parseFloat($('#fat').val());
                var snf = parseFloat($('#snf').val());
                var clr = parseFloat($('#clr').val());
                var isQuantityValid = !$('#quantity').prop('disabled') && !isNaN(quantity);
                var isFatValid = !$('#fat').prop('disabled');
                var isSnfValid = !$('#snf').prop('disabled');
                var isClrValid = !$('#clr').prop('disabled');
                var isAllValid = ((isFatValid) ? !isNaN(fat) : isNaN(fat)) && ((isSnfValid) ? !isNaN(snf) : isNaN(
                    snf)) && ((
                    isClrValid) ? !isNaN(clr) : isNaN(clr));

                console.log('isAllValid', isAllValid, ' isQuantityValid', isQuantityValid, ' isFatValid', isFatValid,
                    ' isSnfValid', isSnfValid, ' isClrValid', isClrValid)
                if (buyerId > 0 && isAllValid) {
                    let _token = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('user.MilkSell.amount.calculate') }}",
                        data: {
                            _token: _token,
                            buyer_id: buyerId,
                            quantity: quantity,
                            milk_type: milk_type,
                            fat: fat,
                            snf: snf,
                            clr: clr
                        },
                        success: function(response) {
                            if (response.success) {
                                var amount = response.data.per_unit;
                                var totalAmount = response.data.total;
                                var html = 'Rate/Ltr: &#8377; ' + amount + ' Total Amount: &#8377; ' +
                                    totalAmount;
                                $('.milkdetails').html(html);
                            } else {
                                iziToast.error({
                                    message: response.message,
                                    position: "topRight",
                                    timeout: 1500,
                                });
                            }
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            iziToast.error({
                                message: xhr.responseJSON.message,
                                position: "topRight",
                                timeout: 1500,
                            });
                        }
                    });
                }
            }
            // Prevent form submission on pressing Enter key
            $('#milkAddRecord').on('keydown', function(event) {
                if (event.keyCode === 13) { // Check if the Enter key is pressed
                    event.preventDefault(); // Prevent the default form submission
                }
            });
            $('#milkAddRecordButton').on('click', function() {
                // Handle form submission here
                $('#milkAddRecord').submit(); // Submit the form
            });
            $.contextMenu({
                selector: '#actionOnMilkRecord',
                className: 'contextmenu-customwidth',
                trigger: 'left',
                delay: 500,
                autoHide: true,
                callback: function(key, options) {
                    var id = $(this).data('record-id');
                    var m = "clicked: " + key;
                    if (key == 'print') {
                        var printUrl =
                            '{{ route('user.MilkSell.print', ':id') }}';
                        printUrl = printUrl.replace(':id', id);
                        window.location.href = printUrl;
                    } else if (key == "delete") {
                        swal.fire({
                            title: '@lang('lang.Are you sure?')',
                            text: "@lang('lang.You want to delete this record.')",
                            icon: 'warning',
                            timer: 2000,
                            confirmButtonText: '@lang('lang.Yes')',
                            showCancelButton: true,
                            cancelButtonText: "@lang('lang.No')",
                        }).then((result) => {
                            if (result.isConfirmed) {
                                let _token = $('meta[name="csrf-token"]').attr('content');
                                $.ajax({
                                    type: 'POST',
                                    url: "{{ route('user.MilkSell.destroy') }}",
                                    data: {
                                        _token: _token,
                                        record_id: id,
                                    },
                                    success: function(response) {
                                        if (response.success) {
                                            iziToast.success({
                                                message: response.message,
                                                position: "topRight",
                                                timeout: 1500,
                                            });
                                            window.location.reload();
                                        } else {
                                            iziToast.error({
                                                message: response.message,
                                                position: "topRight",
                                                timeout: 1500,
                                            });
                                        }
                                    },
                                    error: function(xhr, ajaxOptions, thrownError) {
                                        iziToast.error({
                                            message: xhr.responseJSON.message,
                                            position: "topRight",
                                            timeout: 1500,
                                        });
                                    }
                                });
                            }

                        });
                    }


                },
                items: {
                    "edit": {
                        name: "Edit",
                        icon: "fa-edit"
                    },
                    "print": {
                        name: "Print",
                        icon: "fa-print"
                    },
                    "delete": {
                        name: "Delete",
                        icon: "fa-trash"
                    },
                    "quit": {
                        name: "Quit",
                        icon: "fa-sign-out"
                        // icon: function($element, key, item) {
                        // return 'context-menu-icon context-menu-icon-quit';
                        // }
                    }
                    // "paste": { <i class="fa-solid fa-print"></i>
                    //     name: "Certificate",
                    //     icon: "fa-certificate"
                    // }
                }
            });
        })(jQuery);
    </script>
@endsection
