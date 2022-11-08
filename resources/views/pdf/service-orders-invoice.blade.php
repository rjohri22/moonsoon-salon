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
    <p style="margin: 6px 0; font-size: 13px;"> <strong> Order #: </strong> {{$serviceOrder["service_order_no"]}}</p>
    <p style="margin: 6px 0; font-size: 13px;"> <strong> Date & Time: </strong> {{$serviceOrder["booking_order_date_time_display"]}}</p>
    <p style="margin: 6px 0; font-size: 13px;"> <strong> Name: </strong> {{$serviceOrder["user_name"]}}</p>
    <p style="margin: 6px 0; font-size: 13px;"> <strong> Mobile #: </strong> {{$serviceOrder["contact_no"]}}</p>
    <br>
    <table class="table" border="0" cellspacing="0" cellpadding="10" width="100%">
    
    <thead>
        <tr>
            <th style="border-top: 1px solid #dee2e6;">Item</th>
            <th style="border-top: 1px solid #dee2e6;text-align:right">Service time</th>
            <th style="border-top: 1px solid #dee2e6;text-align:right">Rate (Rs.)</th>
            <th style="border-top: 1px solid #dee2e6;text-align:right">Amount (Rs.)</th>
        </tr>
    </thead>
    <tbody>
    @if(!empty($serviceOrder['service_order_items']))
        @foreach($serviceOrder['service_order_items'] as $key => $value)
       <tr>
           <td style="border-top: 1px solid #dee2e6;">{!! $value['service_name'] !!} </td>
           <td style="border-top: 1px solid #dee2e6;text-align:right">{!! $value['service_time'] !!}</td>
           <td style="border-top: 1px solid #dee2e6;text-align:right">{!! $value['rate'] !!}</td>
           <td style="border-top: 1px solid #dee2e6;text-align:right">{!! $value["total_display"] !!}</td>
       </tr> 
       @endforeach
        @endif
    </tbody>
</table>
    <hr style="margin-top: 0; border-top: 1px solid #dee2e6;">
            <p style="text-align: right; font-size: 13px;"> <strong> Sub Total: {{$serviceOrder['sub_total_invoice']}} </strong> </p>
            <p style="text-align: right; font-size: 13px;"> <strong> CGST: {{$serviceOrder['cgst_amount_invoice']}} </strong> </p>
            <p style="text-align: right; font-size: 13px;"> <strong> SGST: {{$serviceOrder['sgst_amount_invoice']}} </strong> </p>
            <p style="text-align: right; font-size: 13px;"> <strong> IGST: {{$serviceOrder['igst_amount_invoice']}} </strong> </p>
            <p style="text-align: right; font-size: 13px;"> <strong> Total: {{$serviceOrder['total_amount_invoice']}} </strong> </p>
            <p style="text-align: right; font-size: 13px;"> <strong> Discount: (-) {{$serviceOrder['discount_amount_invoice']}} </strong> </p>
        <hr style="border-top: 1px solid #dee2e6;">
        <p style="text-align: center; margin: 6px 0; font-size: 13px;">Thanks for visiting monsoon salon</p>
        <p style="text-align: center; margin: 6px 0; font-size: 13px;">https://monsoonsalon.com</p>
        {{-- <p style="text-align: center; margin: 6px 0; font-size: 13px;">Developed by:</p>
        <p style="text-align:center;"><img id="barcode_image" src="{{$serviceOrder['barcode_invoice']}}" style="width: 250px; height: 75px"></p> --}}
    
</div>


<!---- end pdf design ----->
</body>
</html>
