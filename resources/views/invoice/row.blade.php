<br>
<h3>{{ $heading ?? 'Heading' }}</h3>
<table id="items" style="width: 100%;" class="">

    <tr>
        <th class="tl">
            Item Name
        </th>
        <th class="tl">
            Item Description
        </th>
        <th class="tl">
            Discount %
        </th>
        <th class="tl">
            Price
        </th>
    </tr>

    @php
        $tableTotal = 0;
    @endphp
    @foreach($transactions as $item)
        @if($item->is_tax !=1 || $taxSetting == 1)
            <tr @if($item->is_tax == 1)style="font-size: 9px !important;text-align: right"@endif>
                <td>{{$item->comment}}</td>
                <td>{{$item->description}}</td>
                <td>{{ $item->term_discount > 0 ? $item->term_discount.'%' : '' }}</td>
                <td>
                    {{  formatMoneyWithCommas(abs($item->actual_amount)) }}
                </td>
            </tr>
        @endif
        @php
            $tableTotal +=  $item->actual_amount;
        @endphp
    @endforeach
</table>
<br>
<div style="text-align: right">
    SUBTOTAL : {{ formatMoneyWithCommas(abs($tableTotal)) }}
</div>
<br>