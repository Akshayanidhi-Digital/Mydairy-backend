@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <p class="card-title">{{ $title }}</p>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>@lang('lang.S.No.')</th>
                                    <th>@lang('lang.Order id')</th>
                                    <th>@lang('lang.Product')</th>
                                    <th>@lang('lang.Quantity')</th>
                                    <th>@lang('lang.Status')</th>
                                    <th>@lang('lang.price')</th>
                                    <th>@lang('lang.Total :name', ['name' => __('lang.price')])</th>
                                    <th>@lang('lang.Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($datas->count() > 0)
                                    @foreach ($datas as $key => $record)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $record->order_id }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <img src="{{ asset($record->order_items->image) }}" alt=""
                                                            srcset="">
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        {{ $record->order_items->name }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $record->order_items->quantity }}</td>
                                            <td>{!! orderStatus($record->status) !!}</td>
                                            <td>&#8377; {{ number_format($record->order_items->price, 2) }}</td>
                                            <td>&#8377; {{ number_format($record->order_items->total, 2) }}</td>
                                            <td class="text-center">
                                                <button id="actionOnrecord" type="button"
                                                    data-record-id="{{ $record->_id }}"
                                                    data-record-status="{{ $record->status }}"
                                                    class="btn btn btn-outline-secondary btn-icon">
                                                    <i class="fa fa-ellipsis-v text-primary"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if ($datas->total() > env('PER_PAGE_RECORDS'))
                                        <tr>
                                            <td colspan="7">
                                                <div class="d-flex justify-content-center align-items-center">
                                                    {{ $datas->appends(['date' => request('date'), 'shift' => request('shift'), 'milk_type' => request('milk_type')])->links('pagination::bootstrap-4') }}
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @else
                                    <tr>
                                        <td colspan="7">
                                            <div class="d-flex justify-content-center">
                                                @lang('lang.No Reports Found')
                                            </div>
                                        </td>
                                    </tr>
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
            $.contextMenu({
                selector: '#actionOnrecord',
                className: 'contextmenu-customwidth',
                trigger: 'left',
                delay: 500,
                autoHide: true,
                callback: function(key, options) {
                    var id = $(this).data('record-id');

                    var status = $(this).data('record-status');
                    var m = "clicked: " + key;
                    switch (key) {
                        case 'view':
                            var view =
                                '{{ route('user.shopping.order.view', ['order_id' => ':order_id']) }}';
                            view = view.replace(':order_id', id);
                            window.location.href = view;
                            break;
                        case 'print':
                            var printUrl =
                                '{{ route('user.shopping.order.print', ['order_id' => ':order_id']) }}';
                            printUrl = printUrl.replace(':order_id', id);
                            // window.open(printUrl, '_blank');
                            break;
                        default:
                            break;
                    }
                },
                items: {
                    "view": {
                        name: "View",
                        icon: "fa-eye"
                    },
                    "print": {
                        name: "Print",
                        icon: "fa-print"
                    },
                    "repeat": {
                        name: "Repeat",
                        icon: "fa-repeat"
                    },
                    "cancel": {
                        name: "Cancel",
                        icon: "fa-ban",
                        visible: function(key, options) {
                            var status = $(this).data('record-status');
                            var cancelAllowed = [1, 2, 3];
                            return cancelAllowed.includes(status)
                        }
                    }
                }
            });
        })(jQuery);
    </script>
@endsection
