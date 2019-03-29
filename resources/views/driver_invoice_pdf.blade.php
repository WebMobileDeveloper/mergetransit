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
        padding-bottom:20px;
    }

    .invoice-box table tr.total td{
        padding: 40px 0;
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
									<strong>{{$customers[0]->company}}</strong><br>
                                    Phone: {{$customers[0]->phone}}<br>
                                    PO box {{$drivers[0]->po}}<br>
									{{$customers[0]->city. "," .$customers[0]->state. " ". $customers[0]->zipcode}}<br>
									
								</div>
                            </td>
                            <td class="title">
                                <img src="data:image/jpg;base64,<?php echo base64_encode(file_get_contents("https://mergetransit.com/assets/images/invoice_logo.png")) ?>" alt="Logo" class="" height="100" border="0">
                            </td>
                            
                           
                        </tr>
                    </table>
                </td>
            </tr>
            
			<tr>
				<td colspan="4">
					<h1>INVOICE</h1>
				</td>
			</tr>
			
            <tr class="information">
                <td colspan="4">
                    <table>
                        <tr>
                           
							 <td>
                                <div>
									<strong>BILL TO:</strong><br>
									{{$drivers[0]->d_company_name}}<br>
									{{$drivers[0]->address1 . " " .$drivers[0]->address2}}<br>
                                    {{$drivers[0]->city. ",".$drivers[0]->state." ".$drivers[0]->zipcode }}<br>
                                  
								</div>
                            </td>
                            
                            <td>
                               <div>
                                    <strong>Invoice Number</strong>: {{date("ymd")}}<br>
                                    <strong>Terms</strong>: Net 30days<br>
                                    <strong>Date</strong>: {{date("Y-m-d")}}<br>
                                    <strong>Due Date</strong>:{{date('Y-m-d', strtotime(date('Y-m-d') . ' +30 days'))}}
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

            <tr class="information">
                <td colspan="4">
                    <table>
                        <tr>
                           
							 <td>
                                <div>
									<strong>From:</strong>{{$drivers[0]->shipper.",".$drivers[0]->origin}} <br>
									<strong>To:</strong>{{$drivers[0]->consignee.",".$drivers[0]->destination}}
								</div>
                            </td>
                            
                            <td>
                               <div>
                                    <strong>PO #</strong>: {{$drivers[0]->po}}<br>
                                    <strong>Truck #</strong>: {{$drivers[0]->truck}}<br>
                                    <strong>Driver</strong>: {{$drivers[0]->firstname." ".$drivers[0]->lastname}}<br>
								</div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>           
            
           
            
            <tr class="heading">
                <td colspan="2" >ACTIVITY</td>
                <td>RATE</td>
                <td>AMOUNT</td>
            </tr>
            
           
           
            <tr class="item">
                <td colspan="2" style="height:30px;text-align:left;padding: 10px;font-size:15px;">
                    <span style="font-weight:700;font-size:15px;">
                        Load Shipped on {{date('m/d/Y', strtotime($drivers[0]->del_date)) }}
                    </span>                      
                </td>                     
                <td style="height:30px;text-align:right;padding: 10px;">
                    <span style="color: #717171;font:  14px/21px sans-serif;">${{$drivers[0]->revenue}}</span>
                </td>
                <td style="height:30px;text-align:right;padding: 10px;">
                    <span style="color: #717171;font:  14px/21px sans-serif;">${{$drivers[0]->revenue}}</span>
                </td>
            </tr>
            <?php 
                $sum = $drivers[0]->revenue;
            ?>
            @if(count($invoice_details)>0)
                
                @foreach($invoice_details as $detail)

                    <?php
                    $sum += $detail->rate;
                    ?>
                    <tr class="item">
                        <td colspan="2" style="height:30px;text-align:left;padding: 10px;">
                            <span style="font-weight:700;font-size:15px;">{{$detail->text}}</span>
                        </td>                     
                        <td style="height:30px;text-align:right;padding: 10px;">
                            <span style="color: #717171;font:  14px/21px 'Arial',sans-serif;">${{$detail->rate}}</span>
                        </td>
                        <td style="height:30px;text-align:right;padding: 10px;">
                            <span style="color: #717171;font:  14px/21px 'Arial',sans-serif;">${{$detail->rate}}</span>
                        </td>
                    </tr>
                @endforeach
            @endif
            
            
            
            <tr class="total">
             
                <td colspan="2">
                    <strong>Make checks payable to</strong><br>
                    {{$customers[0]->company}} Inc<br>
                    PO box {{$drivers[0]->po}}<br>
                    {{$customers[0]->city. "," .$customers[0]->state. " ". $customers[0]->zipcode}}<br>
									
                </td>
                
                
                <td colspan="2">
                   Sub total: ${{$sum}}<br>
                   Total: ${{$sum}}<br>
                   Balance Due: ${{$sum}}
                </td>
            </tr>
       
            <tr>
                <td colspan="4" style="text-align:center">
                    <span style="font-size:20px;text-shadow: 3px 0px 3px #6ED501, -3px 0px 1px #0aed54;">Thank you for your business!</span>
                </td>
            </tr>
          
        </table>
    </div>
   
           
        

</body>
</html>