@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title">{{ $title }}</h4>
                        <a href="{{ route('admin.products.list') }}" type="button"
                            class="btn btn-primary">@lang('lang.Back')</a>
                    </div>
                    <form action="{{ route('admin.products.add') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="name">@lang('lang.Name')</label>
                            <input type="text" class="form-control" id="name" value="{{ old('name') }}"
                                name="name" placeholder="@lang('lang.Name')">
                            @error('name')
                                <label id="name-error" class="error mt-2 text-danger" for="name">{{ $message }}</label>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4">{{ old('description') }}</textarea>
                            @error('description')
                                <label id="description-error" class="error mt-2 text-danger" for="name">{{ $message }}</label>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group @error('group') has-danger @enderror">
                                    <label for="group">Product Group</label>
                                    <select class="group_select w-100 @error('group') form-control-danger @enderror"
                                        name="group" id="group">
                                        <option value="">@lang('lang.Select :name', ['name' => 'group'])</option>
                                        @foreach ($groups as $group)
                                            <option @if (old('group') == $group->id)  @endif value="{{ $group->id }}">
                                                {{ $group->group }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('group')
                                    <label id="group-error" class="error mt-2 text-danger"
                                        for="group">{{ $message }}</label>
                                @enderror
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group @error('brand') has-danger @enderror">
                                    <label for="brand">Product Brand</label>
                                    <select class="brand_select w-100 @error('brand') form-control-danger @enderror"
                                        name="brand" id="brand">
                                        <option value="">@lang('lang.Select :name', ['name' => 'Brand'])</option>
                                    </select>
                                </div>
                                @error('brand')
                                    <label id="brand-error" class="error mt-2 text-danger"
                                        for="brand">{{ $message }}</label>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group @error('unit_type') has-danger @enderror">
                                    <label for="group">Unit type</label>
                                    <select class="unit_type_select w-100 @error('unit_type') form-control-danger @enderror"
                                        name="unit_type" id="unit_type">
                                        <option value="">@lang('lang.Select :name', ['name' => 'unit type'])</option>
                                        @foreach ($units as $unit)
                                            <option @if (old('unit_type') == $unit->id)  @endif value="{{ $unit->id }}">
                                                {{ $unit->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('unit_type')
                                    <label id="unit_type-error" class="error mt-2 text-danger"
                                        for="unit_type">{{ $message }}</label>
                                @enderror
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="weight">Weight / Quantity</label>
                                    <input type="number" class="form-control" id="weight" value="{{ old('weight') }}"
                                        name="weight" placeholder="weight">
                                    @error('weight')
                                        <label id="weight-error" class="error mt-2 text-danger"
                                            for="weight">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="price">Price</label>
                                    <input type="number" class="form-control" id="price" value="{{ old('price') }}"
                                        name="price" placeholder="price">
                                    @error('price')
                                        <label id="price-error" class="error mt-2 text-danger"
                                            for="price">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="tax">Tax</label>
                                    <input type="number" class="form-control" id="tax" value="{{ old('tax') }}"
                                        name="tax" placeholder="tax">
                                    @error('tax')
                                        <label id="tax-error" class="error mt-2 text-danger"
                                            for="tax">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="stock">Stock</label>
                                    <input type="number" class="form-control" id="stock" value="{{ old('stock') }}"
                                        name="stock" placeholder="stock">
                                    @error('stock')
                                        <label id="stock-error" class="error mt-2 text-danger"
                                            for="stock">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            {{-- <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="discount">Discount</label>
                                    <input type="number" class="form-control" id="discount" value="{{ old('discount') }}"
                                        name="discount" placeholder="discount">
                                    @error('discount')
                                        <label id="discount-error" class="error mt-2 text-danger"
                                            for="discount">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div> --}}
                        </div>
                        <div class="mb-2">
                            <input type="file" name="image" class="dropify" data-max-file-size="2024kb" />
                            @error('image')
                                <label id="image-error" class="error mt-2 text-danger"
                                    for="image">{{ $message }}</label>
                            @enderror
                        </div>
                        <button class="btn mt-3 btn-sm form-control btn-primary">Add Product</button>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/panel/vendors/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/panel/vendors/select2-bootstrap-theme/select2-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/panel/vendors/dropify/dropify.min.css') }}">
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
    <script src="{{ asset('assets/panel/vendors/dropify/dropify.min.js') }}"></script>
    <script>
        (function($) {
            'use strict';
            $('.dropify').dropify();

            function setSelect2Width() {
                if ($(".group_select").length) {
                    $(".group_select").select2();
                }
                if ($(".brand_select").length) {
                    $(".brand_select").select2();
                }
                if ($(".unit_type_select").length) {
                    $(".unit_type_select").select2();
                }
            }
            setSelect2Width();
            $(window).resize(function() {
                setSelect2Width();
            });
            $('#group').on('change', function() {
                var value = $('#group').val();
                if (value.length != 0) {
                    console.log(value);
                    getBrand(value)
                }
            });

            function getBrand(group) {
                let _token = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    type: 'POST',
                    url: "{{ route('admin.brands.list') }}",
                    data: {
                        _token: _token,
                        group: group
                    },
                    success: function(response) {
                        if (response.success) {
                            let selectInput = $('#brand');
                            selectInput.empty();
                            selectInput.append(
                                $('<option>', {
                                    value: '',
                                    text: '@lang('lang.Select :name', ['name' => 'Brand'])'
                                })
                            );
                            response.data.forEach(function(brand) {
                                selectInput.append(
                                    $('<option>', {
                                        value: brand.id,
                                        text: brand.brand
                                    })
                                );
                            });
                            setSelect2Width();
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
        })(jQuery);
    </script>
@endsection
