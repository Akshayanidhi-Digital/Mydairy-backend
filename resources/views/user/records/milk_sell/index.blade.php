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
                        <a href="{{ route('user.records.milk.sell.trash') }}?date={{ $date }}" type="button"
                            class="btn btn-outline-secondary  position-relative notify_cust "
                            data-count="{{ $trash }}">
                            Trash
                        </a>
                        {{-- <span class="fa-stack fa-5x has-badge" data-count="6">
                            <i class="fa fa-file-text-o fa-stack-1x"></i>
                        </span> --}}
                    </div>
                    <form action="{{ route('user.records.milk.sell') }}" class="mb-4">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <input name="date"
                                        @if (app('request')->input('date')) value="{{ app('request')->input('date') }}" @else value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" @endif
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <select name="shift" class="form-select form-control">
                                        <option value="All">@lang('lang.All')</option>
                                        @foreach (SHIFT_S_VALUES as $key => $value)
                                            <option @if (app('request')->input('shift') && app('request')->input('shift') == $key) selected @endif
                                                value="{{ $key }}">
                                                @lang('lang.' . $value)</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group @error('milk_type') has-danger @enderror">
                                    {{-- <label for="milk_type">@lang('lang.Milk Type')</label> --}}
                                    <select
                                        class="form-select form-control @error('milk_type') form-control-danger @enderror"
                                        name="milk_type" id="milk_type">
                                        <option value="default">@lang('lang.Select Milk Type')</option>
                                        @foreach (MILK_TYPE_LIST as $key => $value)
                                            <option @if (app('request')->input('shift') && app('request')->input('milk_type') == $key) selected @endif
                                                value="{{ $key }}">
                                                @lang('lang.' . $value)</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">@lang('lang.Search')</button>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>@lang('lang.S.No.')</th>
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
                                @if ($datas->count() > 0)
                                    @foreach ($datas as $key => $record)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                {!! getShiftIcon($record->shift) !!}
                                            </td>
                                            <td>{{ $record->time }}</td>
                                            <td>
                                                @if ($record->costumer == null)
                                                    {{ $record->name }}
                                                @else
                                                    {{ $record->costumer->name }} s/o
                                                    {{ $record->costumer->father_name }}
                                                @endif
                                            </td>
                                            <td>@lang('lang.' . array_search($record->milk_type, MILK_TYPE))</td>

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
                                    <tr>
                                        <td colspan="5"></td>
                                        <td>@lang('lang.Total') : {{ number_format($quantity, 2) }}</td>
                                        <td>@lang('lang.Avg.') : {{ number_format($fat / $datas->total(), 2) }}</td>
                                        <td>@lang('lang.Avg.') : {{ number_format($snf / $datas->total(), 2) }}</td>
                                        <td>@lang('lang.Avg.') : {{ number_format($clr / $datas->total(), 2) }}</td>
                                        <td colspan="3">@lang('lang.Total') : &#8377;
                                            {{ number_format($total_price, 2) }}</td>
                                    </tr>
                                    @if ($datas->total() > env('PER_PAGE_RECORDS'))
                                        <tr>
                                            <td colspan="12">
                                                <div class="d-flex justify-content-center align-items-center">
                                                    {{ $datas->appends(['date' => request('date'), 'shift' => request('shift'), 'milk_type' => request('milk_type')])->links('pagination::bootstrap-4') }}
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @else
                                    <tr>
                                        <td colspan="12">
                                            <div class="d-flex justify-content-center">
                                                @lang('lang.No Reports Found')
                                            </div>
                                        </td>
                                    </tr>
                                @endif

                            </tbody>
                        </table>
                        @if ($datas->count() > 0)
                            <div class="mt-2">
                                <a href="{{ route('user.records.milk.sell.print', request()->query()) }}" target="_blank"
                                    class="btn btn-outline-primary me-2 float-right">@lang('lang.Print All')</a>
                            </div>
                        @endif

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
                    if (key == 'print') {
                        var printUrl =
                            '{{ route('user.MilkSell.print', ':id') }}';
                        printUrl = printUrl.replace(':id', id);
                        window.location.href = printUrl;
                    } else if (key == 'delete') {
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
