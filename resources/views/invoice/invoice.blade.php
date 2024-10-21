<!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Invoice</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                    }
                    .invoice-box {
                        width: 90%;
                        margin: auto;
                        padding: 30px;
                        font-size: 16px;
                        line-height: 24px;
                        color: #555;
                    }
                    .invoice-box table {
                        width: 100%;
                        line-height: inherit;
                        text-align: left;
                    }
                    .invoice-box table td {
                        padding: 5px;
                        vertical-align: top;
                    }
                    .invoice-box table tr td:nth-child(3) {
                        text-align: right;
                    }
                    .invoice-box table tr td:nth-child(4) {
                        text-align: right;
                    }

                    .invoice-box table tr td:nth-child(5) {
                        text-align: right;
                    }
                    .invoice-box table tr.top table td {
                        padding-bottom: 20px;
                    }
                    .invoice-box table tr.top table td.title {
                        font-size: 45px;
                        line-height: 45px;
                        color: #333;
                    }
                    .invoice-box table tr.information table td {
                        padding-bottom: 40px;
                    }
                    .invoice-box table tr.heading td {
                        background: #eee;
                        border-bottom: 1px solid #ddd;
                        font-weight: bold;
                    }
                    .invoice-box table tr.details td {
                        padding-bottom: 20px;
                    }
                    .invoice-box table tr.item td {
                        border-bottom: 1px solid #eee;
                    }
                    .invoice-box table tr.item.last td {
                        border-bottom: none;
                    }
                    .invoice-box table tr.total td:nth-child(3) {
                        border-top: 2px solid #eee;
                        font-weight: bold;
                    }
                    .invoice-box table tr.total td:nth-child(2) {
                        text-align: right;
                        border-top: 2px solid #eee;
                        font-weight: bold;
                    }
                </style>
            </head>
            <body onload="window.print()">
                <div class="invoice-box">
                    <table cellpadding="0" cellspacing="0">
                        <tr class="top">
                            <td colspan="5">
                                <table>
                                    <tr>
                                        <td class="title">
                                        <img src="{{ asset('your-logo-here.jpg') }}" style="width:100%; max-width:90px;">
                                        </td>
                                        <td style="text-align: right;">
                                            <span style="font-size: 40px;">INVOICE</span><br>
                                            Invoice #: {{$order->invoice_num}}<br>
                                            Date:  {{$order->order_date}}<br>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr class="information">
                            <td colspan="3">
                                <table>
                                    <tr>
                                        <td>
                                            To : <br>
                                            Business Name : <span style="font-weight: 700;">{{$order->customer->business_name}}</span><br>
                                            Billing Address : <span style="font-weight: 700;">{{$order->customer->billing_address}}</span><br>
                                            Shipping Address : <span style="font-weight: 700;">{{$order->customer->shipping_address}}</span><br>
                                            <br>
                                            Contact Information: <br>
                                            Contact Name : <span style="font-weight: 700;">{{$order->customer->contact_person}}</span><br>
                                            Phone Number : <span style="font-weight: 700;">{{$order->customer->contact_person_contact_number}}</span><br>
                                            Email : <span style="font-weight: 700;">{{$order->customer->contact_person_email}}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr class="heading">
                            <td>Items</td>
                            <td>Description</td>
                            <td>Quantity</td>
                            <td>Unit Price</td>
                            <td>Total</td>
                        </tr>

                        @php
                            $totalAmountItem = 0;
                        @endphp

                        @foreach($order->orderDetails as $item)
                        @php
                            $total = $item->unit_price  * $item->quantity;
                            $totalAmountItem += $total;
                        @endphp
                        <tr class="item">
                            <td>{{$item->product->name}}</td>
                            <td>{{$item->product->description}}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ $item->unit_price }}</td>
                            <td>{{ number_format($total, 2, '.',',') }}</td>
                        </tr>
                        @endforeach
                        <tr class="item">
                            <td colspan="5" style="text-align: center;">*NOTHING FOLLOWS*</td>
                        </tr>
                        <tr class="item">
                            <td colspan="3" style="border: none;"></td>
                            <td style="font-weight: 900;">Subtotal  :</td>
                            <td style="font-weight: 900;">{{ number_format($totalAmountItem, 2, '.',',') }}</td>
                        </tr>
                        @if($order->payments->count() > 0)
                        <tr class="item">
                            <td colspan="3" style="border: none;"></td>
                            <td style="font-weight: 900;">Downpayment  :</td>
                            <td style="font-weight: 900;">{{ number_format($order->payments->sum('amount'), 2, '.',',') }}</td>
                        </tr>
                        @endif
                        <tr class="item">
                            <td colspan="3" style="border: none;"></td>
                            <td style="font-weight: 900;">Balance  :</td>
                            <td style="font-weight: 900;">{{ number_format( $totalAmountItem - $order->payments->sum('amount') , 2, '.',',') }}</td>
                        </tr>
                        <tr class="information">
                            <td colspan="3">
                                <table>
                                    <tr>
                                        <td>
                                            Payment Information: <br>
                                            Payment Type : <span style="font-weight: 700;">Cash on Delivery</span><br>
                                        </td>
                                    </tr>
                                    <tr>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        
                    </table>
                </div>
            </body>
            </html>