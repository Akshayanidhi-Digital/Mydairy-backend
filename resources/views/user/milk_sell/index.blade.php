@extends('layouts.app')
@section('content')
    <div class="row  my-2">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title p-3" class="forms-sample">@lang('Lang.Milk Sale') :
                        {{ strtoupper(app('request')->input('shift')) . ', ' . app('request')->input('date') }}</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <form id="milkAddRecord" autocomplete="off" action="{{ route('user.MilkSell.store') }}"
                                method="POST">
                                @csrf
                                <input type="hidden" name="shift" value="{{ app('request')->input('shift') }}">
                                <input type="hidden" name="date" value="{{ app('request')->input('date') }}">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group @error('buyer_type') has-danger @enderror">
                                            <label for="buyer_type">@lang('constants.Buyer Type')</label>
                                            <select
                                                class="buyer_type_select w-100 @error('buyer_type') form-control-danger @enderror"
                                                name="buyer_type" id="buyer_type">
                                                <option value="default">@lang('lang.Select :name', ['name' => __('constants.Buyer Type')])</option>
                                                @foreach ($user_types as $user_type)
                                                    <option @if (old('buyer_type') && old('buyer_type') == $user_type['user_type']) selected @endif
                                                        value="{{ $user_type['user_type'] }}">{{ $user_type['name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('buyer_type')
                                            <label id="buyer_type-error" class="error mt-2 text-danger"
                                                for="buyer_type">{{ $message }}</label>
                                        @enderror
                                    </div>
                                </div>
                                <div id="formElement">

                                    @include('user.milk_sell.form', [
                                        'type' => old('buyer_type') == 'EXT' ? 'another' : 'default',
                                    ])
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
                                <h4 class="mb-3">@lang('constants.customers')</h4>
                                <div class="row">
                                    @foreach ($user_types as $data)
                                        @if ($data['user_type'] != 'EXT')
                                            <div class="col-sm-6">
                                                <div class="card d-flex align-items-center card-border grid-margin">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center justify-content-around">
                                                            <i class="fa fa-user-plus icon-md text-primary"
                                                                aria-hidden="true"></i>
                                                            <div class="text-center m-0 p-0 align-content-center">
                                                                <h6 class="text-primary">{{ $data['name'] }}</h6>
                                                            </div>
                                                        </div>
                                                        <a @if ($data['user_type'] == 'BYR') href="{{ route('user.buyers.create') }}" @else href="{{ route('user.childUser.add', $data['name']) }}" @endif
                                                            class="mt-2 btn btn-sm btn-primary">@lang('lang.Add :name', ['name' => $data['name']])</a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('user.milk_sell.table', [
        'milkrecords' => $milkrecords,
        'clr' => $clr,
        'fat' => $fat,
        'quantity' => $quantity,
        'snf' => $snf,
        'total_price' => $total_price,
    ])
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

        .card-border {
            border: 1px solid #007bff;
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
                    $('#buyer').on('change', function() {
                        $(this).valid();
                        if ($(this).valid()) {
                            // console.log($(this).val());
                            if ($('#buyer_select').val() == 'EXT') {
                                $('#name').rules('add', {
                                    required: true,
                                    lettersonly: true
                                });
                                $('#buyer').rules('add', {
                                    required: false,
                                });
                            } else {
                                $('#name').rules('add', {
                                    required: false,
                                });
                                $('#buyer').rules('add', {
                                    required: true,
                                });
                            }
                            $('#quantity, #fat, #snf, #clr').prop('disabled', true);
                            $('#quantity, #fat, #snf, #clr').rules('add', {
                                required: true,
                                min: 0
                            });
                            let _token = $('meta[name="csrf-token"]').attr('content');
                            // console.log('_token...')
                            $.ajax({
                                type: 'POST',
                                url: "{{ route('user.MilkSell.buyer.info') }}",
                                data: {
                                    _token: _token,
                                    buyer: $('#buyer').val(),
                                    buyer_type: $('#buyer_type').val()
                                },
                                success: function(response) {
                                    if (response.success) {
                                        if ($('#buyer_type').val() == 'BYR') {
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
                                                $('#quantity, #fat, #snf, #clr').prop('disabled',
                                                    false);
                                                $('#quantity, #fat, #snf, #clr').rules('add', {
                                                    required: true,
                                                    min: 0
                                                });
                                            }
                                        } else {
                                            $('#quantity, #fat, #snf, #clr').prop('disabled',
                                                false);
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
                            $('#buyerProfile').hide();
                            $('#nobuyerProfile').show();
                            $('#quantity, #fat, #snf, #clr').prop('disabled', true);
                            $('#quantity, #fat, #snf, #clr').rules('add', {
                                required: true,
                                min: 0
                            });
                        }
                    });
                }
                if ($(".milk_type_select").length) {
                    $(".milk_type_select").select2();
                    $('#milk_type').on('change', function() {
                        $(this).valid();
                        // console.log("i am milk");
                    });

                }
                if ($(".buyer_type_select").length) {
                    $(".buyer_type_select").select2();
                }
                if ($(".country_select").length) {
                    $(".country_select").select2();
                }
            }
            setSelect2Width();
            $(window).resize(function() {
                setSelect2Width();
            });

            function getBuyerList(value) {
                let _token = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    type: 'POST',
                    url: "{{ route('user.MilkSell.buyerList.info') }}",
                    data: {
                        _token: _token,
                        buyer_type: value
                    },
                    success: function(response) {
                        if (response.success) {
                            if (value == 'EXT') {
                                $('#formElement').html(response.data.form);
                                setSelect2Width();
                                $('#buyerProfile').html('');
                                $('#buyerProfile').hide();
                                $('#nobuyerProfile').show();
                            } else {
                                $('#formElement').html(response.data.form);
                                let selectInput = $('#buyer');
                                selectInput.empty();
                                selectInput.append(
                                    $('<option>', {
                                        value: 'default',
                                        text: '@lang('lang.Select :name', ['name' => __('constants.Buyer Type')])'
                                    })
                                );
                                response.data.buyer.forEach(function(buyer) {
                                    selectInput.append(
                                        $('<option>', {
                                            value: buyer.buyer_id,
                                            text: buyer.name + ' S/o ' +
                                                buyer
                                                .father_name
                                        })
                                    );
                                });
                                setSelect2Width();
                            }
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
            @if (old('buyer_type'))
                getBuyerList("{{ old('buyer_type') }}")
            @endif
            $('#buyer_type').on('change', function() {
                var buyer_type = $(this).val();
                if ($(this).valid()) {
                    getBuyerList(buyer_type)
                } else {
                    $('#buyerProfile').hide();
                    $('#nobuyerProfile').show();
                    let selectInput = $('#buyer');
                    selectInput.empty();
                    selectInput.append(
                        $('<option>', {
                            value: 'default',
                            text: '@lang('lang.Select :name', ['name' => __('constants.Buyer Type')])'
                        })
                    );
                    $('#quantity, #fat, #snf, #clr').prop('disabled', true);

                }
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
                        buyer_type: {
                            required: true,
                            valueNotEquals: 'default'
                        },
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
                        },
                        name: {
                            required: true,
                            lettersonly: true
                        },
                    },
                    messages: {
                        buyer_type: {
                            required: "@lang('lang.Please') @lang('lang.Select :name', ['name' => __('constants.Buyer Type')])",
                            valueNotEquals: "@lang('lang.Please') @lang('lang.Select :name', ['name' => __('constants.Buyer Type')])",
                        },
                        buyer: {
                            required: "@lang('lang.Please') @lang('lang.Select :name', ['name' => __('lang.Buyer')])",
                            valueNotEquals: "@lang('lang.Please') @lang('lang.Select :name', ['name' => __('lang.Buyer')])",
                        },
                        name: {
                            required: "@lang('lang.name.required')",
                            lettersonly: "@lang('lang.name.lettersonly')",
                        },
                        country_code: {
                            valueNotEquals: "@lang('lang.country_code.valueNotEquals')",
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

                // console.log('isAllValid', isAllValid, ' isQuantityValid', isQuantityValid, ' isFatValid', isFatValid,
                //     ' isSnfValid', isSnfValid, ' isClrValid', isClrValid)
                if ($('#buyer').valid() && $('#buyer_type').valid() && isAllValid) {
                    let _token = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('user.MilkSell.amount.calculate') }}",
                        data: {
                            _token: _token,
                            buyer_type: $('#buyer_type').val(),
                            buyer: buyerId,
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
            $('#milkAddRecord').on('keydown', function(event) {
                if (event.keyCode === 13) { // Check if the Enter key is pressed
                    event.preventDefault(); // Prevent the default form submission
                }
            });
            $('#milkAddRecordButton').on('click', function() {
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
                                let _token = $('meta[name="csrf-token"]').attr(
                                    'content');
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
                                                message: response
                                                    .message,
                                                position: "topRight",
                                                timeout: 1500,
                                            });
                                            window.location.reload();
                                        } else {
                                            iziToast.error({
                                                message: response
                                                    .message,
                                                position: "topRight",
                                                timeout: 1500,
                                            });
                                        }
                                    },
                                    error: function(xhr, ajaxOptions,
                                        thrownError) {
                                        iziToast.error({
                                            message: xhr
                                                .responseJSON
                                                .message,
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
