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
                    </div>
                    <form action="{{ route('user.records.milk.request') }}" class="mb-4">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <input type="date" name="date"
                                        @if (app('request')->input('date')) value="{{ app('request')->input('date') }}" @else value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" @endif
                                        class="form-control" max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
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
                        <button type="submit" class="btn btn-primary me-2">@lang('lang.Search')</button>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered">
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
                                    <th>@lang('lang.Status')</th>
                                    <th>@lang('lang.Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($datas->count() > 0)
                                    @foreach ($datas as $key => $record)
                                        <tr>
                                            <td>{{ $datas->firstItem() + $key }}</td>
                                            <td>
                                                {{ $record->buyer->name }} s/o
                                                {{ $record->buyer->father_name }}
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
                                            <td>
                                                @if ($record->is_accepted == 0)
                                                    <span class="badge badge-danger">Pending</span>
                                                @else
                                                    <span class="badge badge-success">Accepted</span>
                                                @endif
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
                                        <td colspan="3"></td>
                                        <td>@lang('lang.Total') : {{ number_format($quantity, 2) }}</td>
                                        <td>@lang('lang.Avg.') : {{ number_format($fat / $datas->total(), 2) }}</td>
                                        <td>@lang('lang.Avg.') : {{ number_format($snf / $datas->total(), 2) }}</td>
                                        <td>@lang('lang.Avg.') : {{ number_format($clr / $datas->total(), 2) }}</td>
                                        <td colspan="4">@lang('lang.Total') : &#8377;
                                            {{ number_format($total_price, 2) }}</td>
                                    </tr>
                                    @if ($datas->total() > env('PER_PAGE_RECORDS'))
                                        <tr>
                                            <td colspan="11">
                                                <div class="d-flex justify-content-center align-items-center">
                                                    {{ $datas->appends(['date' => request('date'), 'shift' => request('shift'), 'milk_type' => request('milk_type')])->links('pagination::bootstrap-4') }}
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @else
                                    <tr>
                                        <td colspan="11">
                                            <div class="d-flex justify-content-center">
                                                @lang('lang.No Reports Found')
                                            </div>
                                        </td>
                                    </tr>
                                @endif

                            </tbody>
                        </table>
                        {{-- @if ($datas->count() > 0)
                            <div class="mt-3">
                                <a href="{{ route('user.records.milk.buy.print', request()->query()) }}"
                                    class="btn btn-outline-primary me-2 float-right">@lang('lang.Print All')</a>
                            </div>
                        @endif --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="milkRequestViewModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">@lang('lang.Milk Request')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="milviewreqdata">
                    <div id="loadingSpinner" class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <p>Loading data...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
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
                    var record_id = $(this).data('record-id');
                    var m = "clicked: " + key;
                    switch (key) {
                        case 'view':
                            $('#milkRequestViewModel').modal('show');
                            milkViewModel(record_id);
                            break;
                        case 'print':
                            var printUrl =
                                '{{ route('user.records.milk.request.view', ':record_id') }}'; // Assuming 'Milkbuy.print' is the route name
                            printUrl = printUrl.replace(':record_id', record_id);
                            window.open(printUrl, '_blank');
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
                    // "delete": {
                    //     name: "Delete",
                    //     icon: "fa-trash"
                    // },
                }
            });

            function milkViewModel(record_id) {
                let _token = $('meta[name="csrf-token"]').attr('content');
                var viewUrl =
                    '{{ route('user.records.milk.request.view', ':record_id') }}';
                viewUrl = viewUrl.replace(':record_id', record_id);
                $.ajax({
                    type: 'POST',
                    url: viewUrl,
                    data: {
                        _token: _token,
                        record_id: record_id,
                    },
                    success: function(response) {
                        if (response.success) {
                            var html = `
                            <div class="row">
                                <div class="col-6">
                                ${response.data.record_type == 2
                                    ? `<p class="font-weight-bold">Seller Details</p>
                                                                       <p>${response.data.name}</p>
                                                                       <p class="mob">Mobile No. : ${response.data.mobile ? response.data.country_code + ' ' + response.data.mobile : 'NA'}</p>`
                                    : `<p class="font-weight-bold">Seller Details</p>
                                                                       <p>${response.data.costumer.name} s/o ${response.data.costumer.father_name}</p>
                                                                       <p class="mob">Mobile No. : ${response.data.costumer.country_code} ${response.data.costumer.mobile}</p>`
                                }
                                </div>
                            <div class="col-6">
                                <p class="font-weight-bold">Buyer Details</p>
                                <p>${response.data.buyer.name}</p>
                                <p class="mob">Mobile No. : ${response.data.buyer.country_code} ${response.data.buyer.mobile}</p>
                            </div>
                            </div>
                            <table class="table table-bordered">
                                <tbody>
                                    <tr><th>Date</th><td>${response.data.date}</td></tr>
                                    <tr><th>Shift</th><td>${response.data.shift}</td></tr>
                                    <tr><th>Milk Type</th><td>${response.data.milk_type}</td></tr>
                                    <tr><th>Weight</th><td>${response.data.quantity} Ltr</td></tr>
                                    <tr><th>FAT</th><td>${response.data.fat != 0 ? response.data.fat : 'NA'}</td></tr>
                                    <tr><th>SNF</th><td>${response.data.snf != 0 ? response.data.snf : 'NA'}</td></tr>
                                    <tr><th>CLR</th><td>${response.data.clr != 0 ? response.data.clr : 'NA'}</td></tr>
                                    <tr><th>Rate/ltr</th><td>&#8377; ${response.data.price}</td></tr>
                                    <tr><th>Total</th><td>&#8377; ${response.data.total_price}</td></tr>
                                </tbody>
                            </table>
                         `;
                            setTimeout(function() {
                                $('#milviewreqdata').html(html);
                                $('#loadingSpinner')
                                    .hide(); // Hide the spinner after the content is loaded
                            }, 500);
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
            $('#milkRequestViewModel').on('hidden.bs.modal', function() {
                $('#milviewreqdata').html(`
                    <div id="loadingSpinner" class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <p>Loading data...</p>
                    </div>
                `);
            });
        })(jQuery);
    </script>
@endsection
