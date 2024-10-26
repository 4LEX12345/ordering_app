<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Final Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .invoice-box {
            max-width: 800px;
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
            border-collapse: collapse;
        }
        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }
        .invoice-box table tr td:nth-child(2) {
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
        .invoice-box table tr.total td:nth-child(2) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }
        .invoice-box .paid {
            font-size: 18px;
            color: green;
            text-align: right;
            font-weight: bold;
        }
    </style>
</head>
<body onload="window.print()">
    <div class="invoice-box">
        <table>
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">
                                <img src="{{ asset('your-logo-here.jpg') }}" style="width:100%; max-width:90px;">
                            </td>
                            <td>
                                Invoice #: 004<br>
                                Date: 2024-10-25<br>
                                Status: <span class="paid">Paid in Full</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr>
                            <td>
                                <strong>To :</strong><br>
                                Business Name : ads<br>
                                Billing Address : asd<br>
                                Shipping Address : asd<br>
                            </td>
                        
                        </tr>
                        <tr>
                        <td>
                                <strong>Contact Information :</strong><br>
                                Name : asd<br>
                                Phone Number : 9054339606<br>
                                Email : alex@gmail.com<br>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="heading">
                <td>Items</td>
                <td>Total</td>
            </tr>

            <tr class="item">
                <td>FLAVA STRAWBERRY (Test) - 2 pcs @ 500.00</td>
                <td>1,000.00</td>
            </tr>

            <tr class="item">
                <td>FLAVA RED VELVET (This is Red Velvet) - 22 pcs @ 65.00</td>
                <td>1,430.00</td>
            </tr>

            <tr class="item last">
                <td><em>*NOTHING FOLLOWS*</em></td>
                <td></td>
            </tr>

            <tr class="total">
                <td></td>
                <td>Subtotal: 2,430.00</td>
            </tr>

            <tr class="total">
                <td></td>
                <td>Balance: 0.00</td>
            </tr>
        </table>

        <br><br>
        <strong>Payment Information:</strong><br>
        Payment Type: Cash on Delivery<br>
        <br>
        <span class="paid">Thank you for your payment!</span>
    </div>
</body>
</html>
