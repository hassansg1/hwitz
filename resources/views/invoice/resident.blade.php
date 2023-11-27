<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice</title>

    @php
        $rowColor = [
                  'additional_rent' => '#dcedf2',
                  'rent' => '#dcedf2',
                'amenities' => '#f4faee',
                'laundry' => '#484848'
        ];
    @endphp
    <style type="text/css">
        @page {
            margin: 0px;
        }

        body {
            margin: 0px;
        }

        * {
            font-family: Verdana, Arial, sans-serif;
            font-size: 13px;
        }

        a {
            color: #fff;
            text-decoration: none;
        }

        table {
            font-size: x-small;
        }

        tfoot tr td {
            font-weight: bold;
            font-size: x-small;
        }

        .tl {
            text-align: left;
        }

        #items {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #items td, #items th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        #items tr:nth-child(odd) {
            background-color: {{ $rowColor[$cartType] }};
        }

        #items tr:hover {
            background-color: #ddd;
        }

        #items th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: {{ $color }};
            color: white;
        }

        body {
            margin-left: 60px;
            margin-right: 60px;
        }

        .color {
            color: {{ $color }};
        }
    </style>

</head>
<body>
<table width="100%">
    <tr>
        <td align="left" width="80%">
            <p class="color" style="font-size: 30px !important;">
                {{ $invoiceTitle ?? 'Resident Invoice' }}
                ({{ \App\Models\TransactionLog::$cartLabelByCartType[$cartType] ?? '' }}
                Cart) {{ $invoiceSubTitle  ?? 'Recurring Service Charge'}}</p>
        </td>
        <td>
<pre>Service Date : {{date('m/d/Y')}}
Invoice Date : {{date('m/d/Y')}}
Invoice No: {{ $token }}
</pre>
        </td>
    </tr>
</table>
@if(isset($subject))
    <div class="row">
        <div class="pull-left">
            <div class="col-md-4 panel panel-default" style="border: none;">
                <div class="panel-body">
                    <strong>{{ $subject }}</strong>
                </div>
            </div>
        </div>
    </div>
@endif
<table width="100%">
    <tr>
        <td>
            <h3>From:</h3>
            @if(isset($senderUser))

                <pre>{{ $senderUser->firstname.' '.$senderUser->lastname }}
                    @if(isset($senderunit))
                        {{ $unit->unit_no .' , '. $building->name }}
                    @endif
                    {{ ltrim($senderUser->city.','.$senderUser->state.','.$senderUser->zipcode,',' ) }}
</pre>
            @else
                <pre>Urban Sky
PO Box 336
Elmhurst, IL 60126
</pre>

            @endif
        </td>
        <td>
            <h3>Bill To:</h3>
<pre>{{ $user->firstname.' '.$user->lastname }}
@if(isset($unit))
{{ $unit->unit_no .' , '. $building->name }}
@endif
{{ ltrim($user->city.','.$user->state.','.$user->zipcode,',' ) }}
</pre>
        </td>
    </tr>
</table>
@php
    $total = 0;
    $grandTotal = 0;
@endphp
@if(isset($electionThree))
    @foreach($electionThree as $item)
        @php
            $total +=  $item->actual_amount;
            $grandTotal +=  $item->amount;
        @endphp
    @endforeach
@endif
@if(isset($electionFour))
    @foreach($electionFour as $item)
        @php
            $total +=  $item->actual_amount;
            $grandTotal +=  $item->amount;
        @endphp
    @endforeach
@endif
@include('invoice.row',['transactions' => $electionThree ?? [],'heading' => 'Property Services'])
@include('invoice.row',['transactions' => $electionFour ?? [],'heading' => 'Add-ons'])
<table width="100%">
    <tr>
        <td style="font-size: 15px !important;font-weight: 500" width="70%" align="left">
            <h3>Payment Options:</h3>

            1) For instant pay , <a target="_blank" style="color: black" href="{{ $link }}">CLICK HERE</a><br>
            2) Login at <a target="_blank" style="color: black"
                           href="https://portal.myurbansky.us">portal.myurbansky.us</a><br>
            3) pay via mobile phone <a target="_blank" style="color: black"
                                       href="{{ config('app.ATTENDANT_URL') . '/send/' . $token }}"> (SEND LINK NOW) </a>
            then pay using
            your cart/ewallet
        </td>
        <td align="right">
            <pre>
                    GRAND TOTAL : {{ formatMoneyWithCommas(abs($total)) }}
DISCOUNT : ({{ formatMoneyWithCommas(abs($total) - abs($grandTotal)) }})
                @if(isset($taxAmount))
                    TAXES AND FEES{{ $taxAmount>0?'*':'' }} : {{formatMoneyWithCommas($taxAmount ?? 0)}}
                @endif

</pre>
            <pre style="font-size: 9px">
                @if(isset($taxDescription) && is_array($taxDescription))
                    {{ $taxAmount>0?'*':'' }}@foreach($taxDescription as $taxLine)
                        {{ $taxLine }}
                    @endforeach
                @endif
            </pre>
        </td>
    </tr>
</table>
<br>
<div style="text-align: right;"><p
            style="float: right;width: 35%;padding: 10px;color: white;background-color: {{ $color }};font-size: 15px;font-weight: bold">
        Total Amount Due : {{ formatMoneyWithCommas(abs($grandTotal+ ($taxAmount ?? 0))) }}</p></div>
<br>
<div style="clear: both"></div>
<div style="text-align: right;"><p style="float: right;width: 35%;">Due Date
        : {{date('m/d/Y', strtotime(date('m/d/Y h:i:s a'). ' + 15 days'))}}</p></div>
<br>
<table width="100%">
    <tr>
        <td align="left">
            Term Dates
            <pre>Late fee:	${{$late_fee ?? 0}} (day 5 after due date)
Reinstatement fee: ${{$shut_off_fee ?? 0}} (day 15 after due date)
</pre>
        </td>
    </tr>
</table>

<div style="position: absolute; bottom: 0;">
    <table width="100%">
        <tr>
            <td align="left" style="width: 50%;color: #6c757d!important">
               <pre>Powered By
{{--<img width="150px" src="{{ public_path('images/logo.png') }}" alt="">--}}
               </pre>
            </td>
        </tr>

    </table>
</div>
</body>
</html>