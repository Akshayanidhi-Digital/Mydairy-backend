<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print Milk Recipt</title>

    <style>
        body {
            font-family: 'Noto Sans', 'Yantramanav', sans-serif;
            margin: 0;
            padding: 10px;
        }

        @page {
            margin: 0;
        }

        * {
            margin: 0;
            padding: 0;
        }


        .head {
            padding: 20px 0;
            text-align: center;
        }

        .head h2 {
            padding: 0;
            margin: 0;
            font-size: 18px;
            width: 100%;
            margin-bottom: 5px;
        }

        .head p {
            font-size: 12px;
            width: 100%;
            padding: 0;
            margin: 0;
            margin-bottom: 5px;
        }

        .text-left {
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        /* Clearfix to clear floats */
        .hr-def {
            margin-top: 5px;
            border: none;
            border-top: 1px dashed black;
            margin-bottom: 5px;
        }

        .bottem {
            text-align: center;
        }

        .bottem p {
            font-size: 12px
        }

        .mob {
            font-size: 10px !important;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            text-align: center;
            padding: 10px 5px;
        }

        table tr {
            border: 1px solid #ddd;
        }

        .border {
            border: 1px solid #ddd;

        }

        thead {
            background: #e1e1e1;
            font-size: 13px;
        }

        thead th {
            font-weight: 400;
            text-transform: capitalize;
        }

        tbody {
            font-size: 10px;
        }
        table img{
            height: 20px!important;
            width: 20px!important;
        }
    </style>
</head>

<body>
    <div class="head">
        <h2>MYDAIRY</h2>
        <p>{{ $farmer->name . ' S/o ' . $farmer->father_name }} Milk Slip ( Start Date : {{ strtoupper($start_date) }}, End Date : {{ $end_date }} )</p>
    </div>
    <table border="0" cellspacing="0" align="center">
        <thead>
            <tr>
                <th>Name</th>
                <th>Date</th>
                <th>Milk Type</th>
                <th>Shift</th>
                <th>Quantity</th>
                <th>FAT</th>
                <th>SNF</th>
                <th>CLR</th>
                <th>Amount</th>
                <th>Total Amount</th>
            </tr>
        </thead>
        <tbody>
            @if ($records->count() > 0)
                @foreach ($datas as $date => $shift)
                    @if ($shift['M'])
                        @php $record = $shift['M']; @endphp
                        <tr  >
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
                        </tr>
                    @else
                        <tr>
                            <td>{{ $farmer->name }}</td>
                            <td>{{ $date }}</td>
                            <td></td>
                            <td>
                                {!! getShiftIcon('M') !!}
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
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
                        </tr>
                    @else
                        <tr>
                            <td>{{ $farmer->name }}</td>
                            <td>{{ $date }}</td>
                            <td></td>
                            <td>
                                {!! getShiftIcon('E') !!}
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endif
                @endforeach
                <tr class="">
                    <td colspan="5" class="border text-right">Total Milk :
                        {{ number_format($records->sum('quantity'), 2) }} Ltr</td>
                    <td colspan="5" class="border text-right">Total Amount : &#8377;
                        {{ number_format($records->sum('total_price'), 2) }}</td>
                </tr>
            @endif
        </tbody>
    </table>

</body>

</html>
