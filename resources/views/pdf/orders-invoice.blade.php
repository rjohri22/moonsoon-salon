<!DOCTYPE html>
<html lang="en">
<head>
  <title>Order invoice</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    
      * {
          box-sizing: border-box;
      }
      body {
        padding: 0;
        margin: 0;
        font-family: 'Open Sans', sans-serif;
      }

  </style>
</head>
<body>
<!----- pdf design ----->
<div class="order_info_table" style="max-width: 560px; margin: auto; padding: 10px;">
    <p style="margin: 6px 0; font-size: 13px;"> <strong> Order #: </strong> {{$order["order_id_no"]}}</p>
    <p style="margin: 6px 0; font-size: 13px;"> <strong> Date & Time: </strong> {{$order["order_date_time_display"]}}</p>
    <p style="margin: 6px 0; font-size: 13px;"> <strong> Name: </strong> {{$order["user_name"]}}</p>
    <p style="margin: 6px 0; font-size: 13px;"> <strong> Mobile #: </strong> {{$order["contact_no"]}}</p>
    <br>
    <table class="table" border="0" cellspacing="0" cellpadding="10" width="100%">
    
    <thead>
        <tr>
            <th style="border-top: 1px solid #dee2e6;">Item</th>
            <th style="border-top: 1px solid #dee2e6;text-align:right">Qty</th>
            <th style="border-top: 1px solid #dee2e6;text-align:right">Rate (Rs.)</th>
            <th style="border-top: 1px solid #dee2e6;text-align:right">Amount (Rs.)</th>
        </tr>
    </thead>
    <tbody>
    @if(!empty($order['orderItems']))
        @foreach($order['orderItems'] as $key => $value)
       <tr>
           <td style="border-top: 1px solid #dee2e6;">{!! $value['item_name'] !!} </td>
           <td style="border-top: 1px solid #dee2e6;text-align:right">{!! $value['quantity'] !!}</td>
           <td style="border-top: 1px solid #dee2e6;text-align:right">{!! $value['rate_invoice'] !!}</td>
           <td style="border-top: 1px solid #dee2e6;text-align:right">{!! $value["sub_total_invoice"] !!}</td>
       </tr> 
       @endforeach
        @endif
    </tbody>
</table>
    <hr style="margin-top: 0; border-top: 1px solid #dee2e6;">
            <p style="text-align: right; font-size: 13px;"> <strong> Sub Total: {{$order['sub_total_invoice']}} </strong> </p>
            <p style="text-align: right; font-size: 13px;"> <strong> CGST: {{$order['cgst_amount_invoice']}} </strong> </p>
            <p style="text-align: right; font-size: 13px;"> <strong> SGST: {{$order['sgst_amount_invoice']}} </strong> </p>
            <p style="text-align: right; font-size: 13px;"> <strong> IGST: {{$order['igst_amount_invoice']}} </strong> </p>
            <p style="text-align: right; font-size: 13px;"> <strong> Delivery Charge: {{$order['delivery_charge_invoice']}} </strong> </p>
            <p style="text-align: right; font-size: 13px;"> <strong> Total: {{$order['total_amount_invoice']}} </strong> </p>
            <p style="text-align: right; font-size: 13px;"> <strong> Discount: (-) {{$order['discount_amount_invoice']}} </strong> </p>
        <hr style="border-top: 1px solid #dee2e6;">
        <p style="text-align: center; margin: 6px 0; font-size: 13px;">Thanks for visiting monsoon salon</p>
        <p style="text-align: center; margin: 6px 0; font-size: 13px;">https://monsoonsalon.com</p>
        {{-- <p style="text-align: center; margin: 6px 0; font-size: 13px;">Developed by:</p>
        <p style="text-align:center;"><img id="barcode_image" src="{{$order['barcode_invoice']}}" style="width: 250px; height: 75px"></p> --}}
    
</div>


<!---- end pdf design ----->
</body>
</html>
