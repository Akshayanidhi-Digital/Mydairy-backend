<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="card-title">milk Table</h4>
                    @if ($milkrecords->count() > 0)
                        <a href="{{ route('user.MilkSell.print.all', [app()->request->date]) }}" target="_blank"
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
                                            {{-- {{ $milkrecord->shift }} --}}
                                            {!! getShiftIcon($milkrecord->shift) !!}

                                        </td>
                                        <td>
                                            {{ $milkrecord->time }}
                                        </td>
                                        <td>
                                            @if ($milkrecord->costumer == null)
                                                {{ $milkrecord->name }}
                                            @else
                                                {{ $milkrecord->costumer->name }} s/o
                                                {{ $milkrecord->costumer->father_name }}
                                            @endif
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
                                    <td colspan="5"></td>
                                    <td>@lang('lang.Total') : {{ number_format($quantity, 2) }}</td>
                                    <td>@lang('lang.Avg.') : {{ number_format($fat / $milkrecords->total(), 2) }}</td>
                                    <td>@lang('lang.Avg.') : {{ number_format($snf / $milkrecords->total(), 2) }}</td>
                                    <td>@lang('lang.Avg.') : {{ number_format($clr / $milkrecords->total(), 2) }}</td>
                                    <td colspan="3">@lang('lang.Total') : &#8377;
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
