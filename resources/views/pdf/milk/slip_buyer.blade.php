<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print Milk Recipt</title>

    <style>
        /* @import url('https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap'); */
        body {
            font-family: "Roboto", sans-serif;
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

        .row {
            width: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
            /* Clearfix for floated elements */
        }

        .row p {
            margin-top: 3px;
            margin-bottom: 3px;
            font-size: 12px;
        }

        .col-4 {
            width: 40%;
            float: left;
            /* Use float for layout */
            box-sizing: border-box;
            /* Ensure padding/border are included in width */
        }

        .col-2 {
            width: 20%;
            float: left;
            /* Use float for layout */
            box-sizing: border-box;
            /* Ensure padding/border are included in width */
            text-align: center;
            /* Center text inside this column */
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
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }

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
    </style>
</head>

<body>
    <div class="head">
        <h2>MYDAIRY</h2>
        <p>Milk Slip</p>
        @if ($record->record_type == 2)
            <p>
                {{ $record->name }}
            </p>
            <p class="mob">@lang('lang.Mobile No.') :
                {{ $record->mobile ? $record->country_code . ' ' . $record->mobile : 'NA' }}</p>
        @else
            <p>
                {{ $record->costumer->name }} s/o {{ $record->costumer->father_name }}
            </p>
            <p class="mob">@lang('lang.Mobile No.') : {{ $record->costumer->country_code }} {{ $record->costumer->mobile }}
            </p>
        @endif
    </div>
    <div class="row clearfix">
        <div class="col-4 float-left">
            <p class="text-left">Date</p>
            <p class="text-left">Shift</p>
            <p class="text-left">Milk Type</p>
            <p class="text-left">Weight</p>
            <p class="text-left">Fat</p>
            <p class="text-left">Snf</p>
            <p class="text-left">Clr</p>
            <p class="text-left">Rate/ltr</p>
            <p class="text-left">Total</p>

        </div>
        <div class="col-2 float-center">
            <p class="text-center">:</p>
            <p class="text-center">:</p>
            <p class="text-center">:</p>
            <p class="text-center">:</p>
            <p class="text-center">:</p>
            <p class="text-center">:</p>
            <p class="text-center">:</p>
            <p class="text-center">:</p>
            <p class="text-center">:</p>
        </div>
        <div class="col-4 float-right">
            <p class="text-right">{{ $record->date }}</p>
            <p class="text-right">{{ $record->shift }}</p>
            <p class="text-right">{{ array_search($record->milk_type, MILK_TYPE) }}</p>
            <p class="text-right">{{ number_format($record->quantity, 2) }} Ltr</p>
            <p class="text-right"> {{ $record->fat != 0 ? number_format($record->fat, 2) : 'NA' }}</p>
            <p class="text-right"> {{ $record->snf != 0 ? number_format($record->snf, 2) : 'NA' }}</p>
            <p class="text-right">{{ $record->clr != 0 ? number_format($record->clr, 2) : 'NA' }}</p>
            <p class="text-right"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>
                {{ number_format($record->price, 2) }}</p>
            <p class="text-right"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>
                {{ number_format($record->total_price, 2) }} </p>
        </div>
    </div>
    <hr class="hr-def">
    <div class="bottem">
        {{-- <p>Dairy</p> --}}
        <p>{{ $user->name }}</p>
        <p class="mob">@lang('lang.Mobile No.') : {{ $user->country_code }} {{ $user->mobile }}</p>
    </div>
</body>

</html>
