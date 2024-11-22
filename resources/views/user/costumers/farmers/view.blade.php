@extends('layouts.app')
@section('content')
    <div class="row" id="printarea">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <form method="get" class="mt-4">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <input type="date" max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" name="start_date" value="{{ $start_date }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <input type="date" max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" name="end_date" value="{{ $end_date }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <button type="submit" class="btn btn-primary">@lang('lang.Search')</button>
                                <a target="_blank" href="{{route('user.farmers.print',[$farmer->farmer_id,$start_date,$end_date])}}" class="btn btn-primary">@lang('lang.Print')</a>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    {{-- <th>@lang('lang.S.No.')</th> --}}
                                    <th>@lang('lang.Name')</th>
                                    <th>@lang('lang.Date')</th>
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
                                @if ($records->count() > 0)
                                    @foreach ($datas as $date => $shift)
                                        @if ($shift['M'])
                                            @php $record = $shift['M']; @endphp
                                            <tr>
                                                <td>{{ $farmer->name }}</td>
                                                <td>{{ $record->date }}</td>
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
                                        @else
                                            <tr>
                                                <td>{{ $farmer->name }}</td>
                                                <td>{{ $date }}</td>
                                                <td>-</td>
                                                <td>
                                                    {!! getShiftIcon('M') !!}
                                                </td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>-</td>
                                            </tr>
                                        @endif
                                        @if ($shift['E'])
                                            @php $record = $shift['E']; @endphp

                                            <tr>
                                                <td>{{ $farmer->name }}</td>
                                                <td>{{ $record->date }}</td>
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
                                        @else
                                            <tr>
                                                <td>{{ $farmer->name }}</td>
                                                <td>{{ $date }}</td>
                                                <td>-</td>
                                                <td>
                                                    {!! getShiftIcon('E') !!}
                                                </td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>-</td>
                                                <td>-</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    <tr class="">
                                        <td colspan="5" class="text-right">Total Milk :
                                            {{ number_format($records->sum('quantity'), 2) }}</td>
                                        <td colspan="6" class="text-right">Total Amount : &#8377;
                                            {{ number_format($records->sum('total_price'), 2) }}</td>
                                    </tr>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
