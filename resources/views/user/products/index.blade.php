@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title">{{ $title }}</h4>
                        <a href="{{ route('user.products.add') }}" type="button" class="btn btn-primary">@lang('lang.Add :name', ['name' => __('lang.Product')])</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>@lang('lang.S.No.')</th>
                                    <th>@lang('lang.Name')</th>
                                    <th>@lang('lang.Image')</th>
                                    <th>@lang('lang.desciption')</th>
                                    <th>@lang('lang.weight')</th>
                                    <th>@lang('lang.stock')</th>
                                    <th>@lang('lang.price')</th>
                                    <th>@lang('lang.tax')</th>
                                    <th>@lang('lang.unit type')</th>
                                    <th>@lang('lang.Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $key => $product)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $product->name }}</td>
                                        <td><img src="{{ asset($product->image_base . '/' . $product->image) }}"
                                                alt="{{ $product->name }}"></td>
                                        <td>{{ $product->desciption }}</td>
                                        <td>
                                            @if ($product->is_weight)
                                                {{ $product->weight }}
                                            @else
                                                NA
                                            @endif
                                        </td>
                                        <td>{{ $product->stock }}</td>
                                        <td>{{ $product->price }}</td>
                                        <td>
                                            @if ($product->is_tax)
                                                {{ $product->tax }}%
                                            @else
                                                NA
                                            @endif
                                        </td>
                                        <td>{{ $product->unitType->name }}</td>
                                        <td>
                                            <button id="actionOnProduct" type="button"
                                                data-record-id="{{ $product->id }}"
                                                class="actionOnProduct btn btn btn-outline-secondary btn-icon">
                                                <i class="fa fa-ellipsis-v text-primary"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
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
    <style>
        .contextmenu-customwidth {
            width: 100px !important;
            width: max-content !important;
            min-width: 80px !important;
        }
    </style>
@endsection
@section('scripts')
    <script src="{{ asset('assets/panel/vendors/jquery-contextmenu/jquery.contextMenu.min.js') }}"></script>
    <script>
        (function($) {
            'use strict';

            function deleteProduct(id) {
                console.log(id);
                swal.fire({
                    title: '@lang('lang.Are you sure?')',
                    text: "@lang('lang.You want to remove this Product.')",
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
                            url: "{{ route('user.products.delete') }}",
                            data: {
                                _token: _token,
                                id: id,
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

            $.contextMenu({
                selector: '.actionOnProduct',
                className: 'contextmenu-customwidth',
                trigger: 'left',
                delay: 500,
                autoHide: true,
                callback: function(key, options) {
                    var id = $(this).data('record-id');
                    var m = "clicked: " + key;
                    console.log(m)
                    switch (key) {
                        case 'edit':
                            var editUrl =
                                '{{ route('user.products.edit', ':id') }}';
                            editUrl = editUrl.replace(':id', id);
                            window.location.href = editUrl;
                            break;
                        case 'delete':
                            deleteProduct(id);
                            break;
                        default:
                            break;
                    }
                },
                items: {
                    "edit": {
                        name: "@lang('lang.Edit')",
                        icon: "fa-edit"
                    },
                    "delete": {
                        name: "@lang('lang.Delete')",
                        icon: "fa-trash"
                    },
                    "quit": {
                        name: "@lang('lang.Quit')",
                        icon: "fa-sign-out"
                    }
                }
            });
        })(jQuery);
    </script>
@endsection
