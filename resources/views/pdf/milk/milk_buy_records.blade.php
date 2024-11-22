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
            width: 100%;
            margin-bottom: 5px;
        }

        .head p {
            font-size: 14px;
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
            font-size: 14px
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

        thead {
            background: #e1e1e1;
            font-size: 14PX;
        }

        thead th {
            font-weight: 400;
            text-transform: capitalize;
        }

        tbody {
            font-size: 12PX;
        }

        .border {
            border: 1px solid #515151;

        }

        .headerTable {
            font-size: 14px;
        }
    </style>
</head>

<body>
    <table class="headerTable" border="0" cellspacing="0" align="center">
        <tr>
            <th colspan="2">MYDAIRY</th>
        </tr>
        <tr>
            <td>{{ $user->name }} ( {{ $user->country_code }} {{ $user->mobile }} )</td>
            <td> Milk Buy Records ( Shift: {{ strtoupper($shift) }}, Date: {{ $date }} )</td>
        </tr>
    </table>
    <table border="0" cellspacing="0" align="center">
        <thead>
            <tr class="border">
                <th>S.No.</th>
                <th>ID</th>
                <th>Name</th>
                <th>Milk type</th>
                <th>Quantity</th>
                <th>FAT</th>
                <th>SNF</th>
                <th>CLR</th>
                <th>Shift</th>
                <th>Amount</th>
                <th>Total Amount</th>
            </tr>
        </thead>
        <tbody>
            @if ($datas->count() > 0)
                @foreach ($datas as $index => $milkrecord)
                    <tr class="border">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $milkrecord->seller_id }}</td>
                        <td>
                            @if ($milkrecord->costumer == null)
                                {{ $milkrecord->name }}
                            @else
                                {{ $milkrecord->costumer->name }} s/o {{ $milkrecord->costumer->father_name }}
                            @endif
                        </td>
                        <td>{{ array_search($milkrecord->milk_type, MILK_TYPE) }}</td>
                        <td>{{ number_format($milkrecord->quantity, 2) }}</td>
                        <td>{{ $milkrecord->fat != 0 ? number_format($milkrecord->fat, 2) : 'NA' }}</td>
                        <td>{{ $milkrecord->snf != 0 ? number_format($milkrecord->snf, 2) : 'NA' }}</td>
                        <td>{{ $milkrecord->clr != 0 ? number_format($milkrecord->clr, 2) : 'NA' }}</td>
                        <td>{{ $milkrecord->shift }}</td>
                        <td><span
                                style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>{{ number_format($milkrecord->price, 2) }}
                        </td>
                        <td><span
                                style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>{{ number_format($milkrecord->total_price, 2) }}
                        </td>
                    </tr>
                @endforeach
                <tr style="background: #e1e1e1;font-size:12px" class="border">
                    <td colspan="4"></td>
                    <td>Total : {{ number_format($datas->sum('quantity'), 2) }}</td>
                    <td>Avg : {{ number_format($datas->sum('fat') / $datas->count(), 2) }}</td>
                    <td>Avg : {{ number_format($datas->sum('snf') / $datas->count(), 2) }}</td>
                    <td>Avg : {{ number_format($datas->sum('clr') / $datas->count(), 2) }}</td>
                    <td colspan="3">Total : <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>
                        {{ number_format($datas->sum('total_price'), 2) }}</td>
                </tr>
            @endif
        </tbody>
    </table>

</body>

</html>
