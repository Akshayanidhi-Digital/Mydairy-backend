@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <p class="card-title">{{ $title }}</p>
                        @php
                            $date = app('request')->input('date')
                                ? app('request')->input('date')
                                : \Carbon\Carbon::now()->format('Y-m-d');
                        @endphp
                        <a href="{{ route('user.records.milk.sell') }}?date={{ $date }}" type="button"
                            class="btn btn-outline-primary">
                            View All
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>@lang('lang.S.No.')</th>
                                    <th>@lang('lang.Name')</th>
                                    <th>@lang('lang.Milk Type')</th>
                                    <th>@lang('lang.Shift')</th>
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
                                @if ($datas->count() > 0)
                                    @foreach ($datas as $key => $record)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $record->buyer->name ?? '' }} S/o {{ $record->buyer->father_name ?? '' }}
                                            </td>
                                            <td>@lang('lang.' . array_search($record->milk_type, MILK_TYPE))</td>
                                            <td>
                                                {!! getShiftIcon($record->shift) !!}
                                            </td>
                                            <td>{{ $record->quantity }}</td>
                                            <td>
                                                {{ $record->fat != 0 ? $record->fat : 'NA' }}
                                            </td>
                                            <td>
                                                {{ $record->snf != 0 ? $record->snf : 'NA' }}
                                            </td>
                                            <td>
                                                {{ $record->clr != 0 ? $record->clr : 'NA' }}
                                            </td>
                                            <td>
                                                &#8377; {{ number_format($record->price, 2) }}
                                            </td>

                                            <td>
                                                &#8377; {{ number_format($record->total_price, 2) }}
                                            </td>
                                            <td class="text-center">
                                                <button id="actionOnrecord" type="button"
                                                    data-record-id="{{ $record->id }}"
                                                    class="btn btn btn-outline-secondary btn-icon">
                                                    <i class="fa fa-ellipsis-v text-primary"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="11">
                                            <div class="d-flex justify-content-center">
                                                @lang('lang.No Records Found')
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
                    var m = "clicked: " + key;
                    if (key == "delete") {
                        swal.fire({
                            title: '@lang('lang.Are you sure?')',
                            text: "@lang('lang.You want to parmanent delete this record.')",
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
                                    url: "{{ route('user.MilkSell.delete') }}",
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
                    } else if (key == 'restore') {
                        swal.fire({
                            title: '@lang('lang.Are you sure?')',
                            text: "@lang('lang.You want to restore this record.')",
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
                                    url: "{{ route('user.MilkSell.restore') }}",
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
                    "delete": {
                        name: "Delete",
                        icon: "fa-trash"
                    },
                    "restore": {
                        name: "Restore",
                        icon: "fa-repeat"
                    },
                    "quit": {
                        name: "Quit",
                        icon: "fa-sign-out"
                    }
                }
            });
        })(jQuery);
    </script>
@endsection
