<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xml:lang="en" xmlns="http://www.w3.org/1999/xhtml" lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Print Milk Recipt</title>

    <style type="text/css">
        body {
            font-family: 'Noto Sans', 'Yantramanav', sans-serif;
            margin: 0;
            padding: 10px;
            margin: 18pt 18pt 24pt 18pt;
        }


        @page {
            margin: 4cm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            /* Added for better box sizing */
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
            padding: 0;
            margin: 0;
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
            border: 1px solid #7c7c7c;
        }

        .headerTable {
            margin-top: 10px font-size: 14px;
        }

        .border-table tr {
            border: 1px solid #7c7c7c;
        }

        table img {
            height: 20px !important;
            width: 20px !important;
        }

        #header,
        #footer {
            position: fixed;
            left: 0;
            right: 0;
            color: #7c7c7c;
            font-size: 0.9em;
            padding-left: 10px;
            padding-right: 10px;
        }

        #header {
            top: 0;
            margin-bottom: 10px;
        }

        #header table,
        {
        width: 100%;
        border-collapse: collapse;
        border: none;
        }

        #header td {
            padding: 0;
            width: 50%;
        }
    </style>
</head>

<body>
    <div id="header">
        <table>
            <tr>
                <td style="text-align: left;">{{route('home')}}</td>
                <td style="text-align: right;">{{ now() }}</td>
            </tr>
        </table>
    </div>
    <table class="headerTable" border="0" cellspacing="0" align="center">
        <tr>
            <th colspan="2">MYDAIRY</th>
        </tr>
        <tr>
            <td>{{ $buyer->name }} Mobile No. : {{ $buyer->country_code }} {{ $buyer->mobile }}</td>
            <td>Milk Slip ( Start Date: {{ strtoupper($start_date) }}, End Date: {{ $end_date }} )</td>
        </tr>
    </table>
    <table class="border-table" border="0" cellspacing="0" align="center">
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
                        <tr>
                            <td>{{ $buyer->name }}</td>
                            <td>{{ $record->date }}</td>
                            <td>@lang('lang.' . array_search($record->milk_type, MILK_TYPE))</td>
                            <td>{!! getShiftIcon($record->shift) !!}</td>
                            <td>{{ $record->quantity }}</td>
                            <td>{{ $record->fat != 0 ? $record->fat : 'NA' }}</td>
                            <td>{{ $record->snf != 0 ? $record->snf : 'NA' }}</td>
                            <td>{{ $record->clr != 0 ? $record->clr : 'NA' }}</td>
                            <td>&#8377; {{ number_format($record->price, 2) }}</td>
                            <td>&#8377; {{ number_format($record->total_price, 2) }}</td>
                        </tr>
                    @else
                        <tr>
                            <td>{{ $buyer->name }}</td>
                            <td>{{ $date }}</td>
                            <td></td>
                            <td>{!! getShiftIcon('M') !!}</td>
                            <td colspan="6"></td>
                        </tr>
                    @endif
                    @if ($shift['E'])
                        @php $record = $shift['E']; @endphp
                        <tr>
                            <td>{{ $buyer->name }}</td>
                            <td>{{ $record->date }}</td>
                            <td>@lang('lang.' . array_search($record->milk_type, MILK_TYPE))</td>
                            <td>{!! getShiftIcon($record->shift) !!}</td>
                            <td>{{ $record->quantity }}</td>
                            <td>{{ $record->fat != 0 ? $record->fat : 'NA' }}</td>
                            <td>{{ $record->snf != 0 ? $record->snf : 'NA' }}</td>
                            <td>{{ $record->clr != 0 ? $record->clr : 'NA' }}</td>
                            <td>&#8377; {{ number_format($record->price, 2) }}</td>
                            <td>&#8377; {{ number_format($record->total_price, 2) }}</td>
                        </tr>
                    @else
                        <tr>
                            <td>{{ $buyer->name }}</td>
                            <td>{{ $date }}</td>
                            <td></td>
                            <td>{!! getShiftIcon('E') !!}</td>
                            <td colspan="6"></td>
                        </tr>
                    @endif
                @endforeach
                <tr>
                    <td colspan="5" class="border text-right">Total Milk:
                        {{ number_format($records->sum('quantity'), 2) }} Ltr</td>
                    <td colspan="5" class="border text-right">Total Amount: &#8377;
                        {{ number_format($records->sum('total_price'), 2) }}</td>
                </tr>
            @endif
        </tbody>
    </table>
    <script type="text/php">

        if ( isset($pdf) ) {

          $size = 6;
          $color = array(0,0,0);
          if (class_exists('Font_Metrics')) {
            $font = Font_Metrics::get_font("helvetica");
            $text_height = Font_Metrics::get_font_height($font, $size);
            $width = Font_Metrics::get_text_width("Page 1 of 2", $font, $size);
          } elseif (class_exists('Dompdf\\FontMetrics')) {
            $font = $fontMetrics->getFont("helvetica");
            $text_height = $fontMetrics->getFontHeight($font, $size);
            $width = $fontMetrics->getTextWidth("Page 1 of 2", $font, $size);
          }

          $foot = $pdf->open_object();

          $w = $pdf->get_width();
          $h = $pdf->get_height();

          // Draw a line along the bottom
          $y = $h - $text_height - 24;

          $pdf->close_object();
          $pdf->add_object($foot, "all");

          $text = "Page {PAGE_NUM} of {PAGE_COUNT}";

          // Center the text
          $pdf->page_text($w / 2 - $width / 2, $y, $text, $font, $size, $color);

        }
        </script>
</body>

</html>
