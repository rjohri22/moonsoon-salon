<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <style>
    * { font-family: DejaVu Sans, sans-serif; }
  </style>
</head>
<body>
<div style="font-size: 14px; font-family: Arial, Helvetica, sans-serif !important;">
    
    <hr />
    <table width="100%">
        <tr>
            <td width="50%" valign="top">
                <b style="font-size: 18px;">Name: {{$order["user_name"]}}</b><br />
                <b>Mobile No: </b> {{$order["contact_no"]}}<br />
                
            </td>
            <td width="50%" valign="top" align="right">
                <b style="font-size: 18px;">Order #: {{$order["order_id_no"]}}</b><br />
                <b>Order date:</b> {{$order["order_date_time_display"]}}
            </td>
        </tr>
    </table>
    <hr />
    <?php
            $currencySymbol = "Rs ";
    ?>
    <table width="100%">
        <tr style='background: #e3e3e3;'>
            <td align="left"><b>Item</b></td>
            <td align="right"><b>Rate(Rs.)</b></td>
            <td align="right"><b>Qty</b></td>
            <td align="right"><b>Amount(Rs.)</b></td>
        </tr>
        @if(!empty($order['orderItems']))
        @foreach($order['orderItems'] as $key => $value)
        <tr style="border-bottom: 1px solid #e3e3e3;">
            <td align="left">{!! $value['item_name'] !!} </td>
            <td align="right">{!! $value['rate_invoice'] !!} </td>
            <td align="right">{!! $value['quantity'] !!} </td>
            <td align="right">{!! $value["sub_total_invoice"] !!} </td>
        </tr>
        @endforeach
        @endif
        <tr style='background: #e3e3e3;'>
            <td colspan="3" align="right">
                Sub Total
            </td>
            <td  align="right" valign="top">
             {{$order['sub_total_invoice']}}
            </td>
            
        </tr>
        <tr style='background: #e3e3e3;'>
            <td colspan="3" align="right">
                Discount
                
            </td>
            <td align="right" valign="top">
                <b> {{$order['discount_amount_invoice']}}</b>
            </td>
        </tr>
        <tr style='background: #e3e3e3;'>
            <td colspan="3" align="right">
            Delivery
            </td>
            <td align="right" valign="top">
                <b> {{$order['delivery_charge_invoice']}}</b>
            </td>
        </tr>
        <tr style='background: #e3e3e3;'>
            <td colspan="3" align="right">
                Total
            </td>
            <td align="right" valign="top">
                <b> {{$order['total_amount_invoice']}}</b>
            </td>
        </tr>
    </table>
    <!-- <table width="100%">
        <tr style='background: #e3e3e3;'>   
            <td align="left"><img src="{{$order['barcode_invoice']}}"></td>
            
        </tr>
    </table> -->
</div>
</body>
</html>