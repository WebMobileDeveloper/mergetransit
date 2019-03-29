<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>A simple, clean, and responsive HTML invoice template</title>
    
    <style>
    .invoice-box{
        max-width:1300px;
        margin:auto;
        padding:30px;
        border:1px solid #eee;
        box-shadow:0 0 10px rgba(0, 0, 0, .15);
        font-size:16px;
        line-height:24px;
        font-family:'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        color:#555;
    }
    
    .invoice-box table{
        width:100%;
        text-align:left;
    }
    
    .invoice-box table td{
        padding:5px;
        vertical-align:top;
    }
    
    .invoice-box table tr td:nth-child(2){
        text-align:right;
    }
    
    .invoice-box table tr.top table td{
        padding-bottom:20px;
    }
    
    .invoice-box table tr.top table td.title{
        font-size:45px;
        color:#333;
    }
    
    .invoice-box table tr.information table td{
        padding-bottom:40px;
    }
    
    .invoice-box table tr.heading td{
        background:#eee;
        border-bottom:1px solid #ddd;
        font-weight:bold;
    }
    
    .invoice-box table tr.details td{
        padding-bottom:20px;
    }
    
    .invoice-box table tr.item td{
        border-bottom:1px solid #eee;
    }
    
    .invoice-box table tr.item.last td{
        border-bottom:none;
    }
    
    .invoice-box table tr.total td:nth-child(2){
        border-top:2px solid #eee;
        font-weight:bold;
    }
    

    </style>
</head>

<body>
    <div class="invoice-box">
	
		
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="4">
                    <table>
                        <tr>
							<td>
                             
								<div>
									<strong>Merge Transit, LLC.</strong><br>
                                    13911 Bluffoak<br>
                                    San Antonio, TX 78216 US<br>
									Billing@mergetransit.com<br>
									www.mergetransit.com
								</div>
                            </td>
                            <td class="title">
                                <img src="http://mergetransit.com/assets/images/logo.png" alt="Logo" class="" height="100" border="0">
                            </td>
                            
                           
                        </tr>
                    </table>
                </td>
            </tr>
            
			<tr>
				<td colspan="4">
					<h1>Invoice</h1>
				</td>
			</tr>
			
            <tr class="information">
                <td colspan="4">
                    <table>
                        <tr>
                           
							 <td>
                                <div>
									<strong>BILL TO</strong><br>
									{{$customer->firstname}} {{$customer->lastname}}<br>
									{{$customer->company}}<br>
                                    {{$customer->email}}<br>
                                    {{$customer->phone}}
								</div>
                            </td>
                            
                            <td>
                               <div>
                                    <strong>Invoice #</strong>: {{$invoice->invoice_no}}<br>
                                    <strong>Created</strong>: {{$invoice->due_date}}<br>
                                    <strong>Due</strong>: {{$invoice->due_date}}<br>
                                    <strong>Terms</strong>:Due on receipt
								</div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            
            <tr class="details">
                <td colspan="4">
                   <hr>				  
                </td>
            </tr>
            
            <tr class="heading">
                <td>ACTIVITY</td>
                <td>QTY</td>
                <td>RATE</td>
                <td>AMOUNT</td>
            </tr>
            
           
             @if(count($invoice_details)>0)
                @foreach($invoice_details as $detail)
                    <tr class="item">
                        <td style="height:30px;text-align:left;padding: 10px;font-size:15px;">
                            {{--  <span style="color: #717171;font:  14px/21px â??Arialâ??,sans-serif;">POSB/DBS</span>  --}}

                            <span style="font-weight:700;font-size:15px;">
                                {{($detail->revenue>=1000)?"Dispatch Over $1,000":"Dispatch Under $1,000"}}
                            </span><br>
                            {{($detail->revenue>=1000)?"Dispatch Over $1,000":"Dispatch Under $1,000"}} -P/U 
                            {{explode(" ",$detail->pu_date)[0]}}
                            ${{$detail->revenue}} ({{$detail->tra_company}}) 
                            <span style="font-weight:700;font-size:15px;">{{$detail->driver_name}}</span>    
                        </td>
                        <td style="height:30px;text-align:right;padding: 10px;">
                            <span style="color: #717171;font:  14px/21px â??Arialâ??,sans-serif;">1</span>
                        </td>
                        <td style="height:30px;text-align:right;padding: 10px;">
                            <span style="color: #717171;font:  14px/21px â??Arialâ??,sans-serif;">{{$detail->billing_amount}}</span>
                        </td>
                        <td style="height:30px;text-align:right;padding: 10px;">
                            <span style="color: #717171;font:  14px/21px â??Arialâ??,sans-serif;">{{$detail->billing_amount}}</span>
                        </td>
                    </tr>
                @endforeach
            @endif

            @if(count($invoice_specials)>0)
                @foreach($invoice_specials as $special)
                    <tr class="item">
                        <td style="height:30px;text-align:left;padding: 10px;">
                            <span style="font-weight:700;font-size:15px;">{{$special->activity}}</span>
                        </td>
                        <td style="height:30px;text-align:right;padding: 10px;">
                            <span style="color: #717171;font:  14px/21px â??Arialâ??,sans-serif;">{{$special->qty}}</span>
                        </td>
                        <td style="height:30px;text-align:right;padding: 10px;">
                            <span style="color: #717171;font:  14px/21px â??Arialâ??,sans-serif;">{{$special->rate}}</span>
                        </td>
                        <td style="height:30px;text-align:right;padding: 10px;">
                            <span style="color: #717171;font:  14px/21px â??Arialâ??,sans-serif;">{{$special->amount}}</span>
                        </td>
                    </tr>
                @endforeach
            @endif
            
            
            
            <tr class="total">
                <td></td>
                <td></td>
                
                
                <td colspan="2">
                   BALANCE: ${{$invoice->bill_amount}}
                </td>
            </tr>
            @if($invoice->paid_status==1)
       
            <tr>
                <td colspan="4" style="text-align:center">
                    <span style="color:red;font-size:40px;text-shadow: 3px 0px 3px #6ED501, -3px 0px 1px #0aed54;">PAID</span>
                </td>
            </tr>
            @endif
        </table>
    </div>
   
           
        

</body>
</html>