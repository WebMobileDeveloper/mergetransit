@extends("layout.adminLayout")
@section("contents")
<script>
    function previous_back()
    {
        location.href = "{{URL::previous()}}";
    }
</script>

<div class="content-wrapper" style="min-height: 916px;">
<meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Invoice #{{$invoice->invoice_no}} [{{$invoice->due_date}}]
        </h1>
        <div class="box-header with-border" style="padding-left: 0;">
         <a onclick="previous_back()" class="btn btn-success btn-sm pull-right">Back</a>
         </div>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">       
            <div id="output" style="display:block;border:none;padding: 50px;
                    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
                    background-color: #fff;">
                <table style="width: 95% !important;">
                    <tbody>
                        <tr>
                            <td>
                                <table style="width: 100% !important;padding:25px;" align="center">
                                    <tbody>
                                        <tr>
                                            <td colspan="" style="width:50%;/*! background:#eef8fb; */padding:0.5cm 0cm 0.5cm 0cm">
                                                <p style="text-align:left">
                                                    <table style="width:100%;color: #717171;font: 14px/21px ˜Arial˜,sans-serif;">
                                                        <tbody>
                                                        <tr><td style="font: bold 14px/21px Arial,sans-serif;">Merge Transit, LLC</td></tr>
                                                        <tr><td>13911 Bluffoak</td></tr>
                                                        <tr><td>San Antonio, TX 78216 US</td></tr>
                                                        <tr><td>Billing@mergetransit.com</td></tr>
                                                        <tr><td>www.mergetransit.com</td></tr>
                                                    </tbody></table>
                                                
                                                </p>
                                            </td>
                                            <td colspan="" style="width:50%;/*! background:#eef8fb; */padding:0.5cm 0cm 0.5cm 0cm">
                                                <p style="text-align:right;margin-right: 25px;" align="center">
                                                <a href="http://mergetransit.com/" target="_blank">
                                                <span style="text-decoration:none">
                                                <img src="http://mergetransit.com/assets/images/logo.png" alt="Logo" class="" height="100" border="0">
                                                </span>
                                                </a>
                                                
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 5px 0 0 10px"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="color: #c58585;font: bold 31px ËœArialËœ,sans-serif;text-align: center;">INVOICE</td>
                                        </tr>
                                        
                                        <tr><td>&nbsp;</td></tr>
                                        <tr>
                                            <td style="padding: 0 0 10px 0px;width:50%;">
                                                <table style="width:90%;margin-left:3px" cellspacing="0" cellpadding="0" border="0">
                                                    <tbody>
                                                        <tr>
                                                            <td style="height:25.0pt; width:123.0pt;">
                                                            <span style="color: #717171;font: bold 16px/21px ˜Arial˜,sans-serif;">BILL TO  </span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="height:25.0pt; width:348.0pt;">
                                                            <span style="color: #717171;font: 14px/21px ˜Arial˜,sans-serif;">{{$customer->firstname." ".$customer->lastname}}</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="height:25.0pt; width:348.0pt;">
                                                            <span style="color: #717171;font: 14px/21px ˜Arial˜,sans-serif;">{{$customer->company}}</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="height:25.0pt; width:348.0pt;">
                                                            <span style="color: #717171;font: 14px/21px ˜Arial˜,sans-serif;">{{$customer->email}}</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="height:25.0pt; width:348.0pt;">
                                                            <span style="color: #717171;font: 14px/21px ˜Arial˜,sans-serif;">{{$customer->phone}}</span>
                                                            </td>
                                                        </tr>
                                                        
                                                        <tr><td>&nbsp;</td></tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td style="padding: 0 0 15px 0px;width:50%;">
                                                <table style="width:90%;margin-left:3px" cellspacing="0" cellpadding="0" border="0">
                                                    <tbody>
                                                        <tr>
                                                            <td style="height:25.0pt; width:123.0pt;">
                                                            <span style="color: #717171;font: bold 14px/21px ˜Arial˜,sans-serif;">INVOICE NO.:  </span>
                                                            </td>
                                                            <td style="height:25.0pt; width:348.0pt;">
                                                            <span style="color: #717171;font: bold 14px/21px ˜Arial˜, sans-serif;">#&nbsp;{{$invoice->invoice_no}}</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="height:25.0pt; width:123.0pt;">
                                                            <span style="color: #717171;font: bold 14px/21px ˜Arial˜,sans-serif;">DATE:</span>
                                                            </td>
                                                            <td style="height:25.0pt; width:348.0pt;">
                                                            <span style="color: #717171;font: 14px/21px ˜Arial˜,sans-serif;">{{$invoice->due_date}}</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="height:25.0pt; width:123.0pt;">
                                                            <span style="color: #717171;font: bold 14px/21px ˜Arial˜,sans-serif;">DUE DATE:</span>
                                                            </td>
                                                            <td style="height:25.0pt; width:348.0pt;">
                                                            <span style="color: #717171;font: 14px/21px ˜Arial˜,sans-serif;">{{$invoice->due_date}}</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="height:25.0pt; width:123.0pt;">
                                                            <span style="color: #717171;font: bold 14px/21px ˜Arial˜,sans-serif;">TERMS:</span>
                                                            </td>
                                                            <td style="height:25.0pt; width:348.0pt;">
                                                            <span style="color: #717171;font: 14px/21px ˜Arial˜,sans-serif;">Due on receipt</span>
                                                            </td>
                                                        </tr>
                                                        
                                                        <tr><td>&nbsp;</td></tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="padding: 0 0 15px 0px;width:100%;">
                                                <table style="width:100%" cellspacing="0" cellpadding="0" border="0">
                                            
                                                    <tbody>
                                                        <tr style="background-color:#CAD7F0;">
                                                            <th style="height:40px;background-color:#eff6ff;text-align:left;padding: 10px;">
                                                                <span style="color: #717171;font: bold 14px/21px Arialâ,sans-serif;text-transform: uppercase;">Activity</span>
                                                            </th>
                                                            <th style="height:40px;background-color:#eff6ff;text-align:right;padding: 10px;">
                                                                <span style="color: #717171;font: bold 14px/21px Arialâ,sans-serif;text-transform: uppercase;">qty</span>
                                                            </th>
                                                            <th style="height:40px;background-color:#eff6ff;text-align:right;padding: 10px;">
                                                                <span style="color: #717171;font: bold 14px/21px Arialâ,sans-serif;text-transform: uppercase;">rate</span>
                                                            </th>
                                                            <th style="height:40px;background-color:#eff6ff;text-align:right;padding: 10px;">
                                                                <span style="color: #717171;font: bold 14px/21px Arialâ,sans-serif;text-transform: uppercase;">amount</span>
                                                            </th>
                                                            
                                                            
                                                        </tr>
                                                        @if(count($invoice_details)>0)
                                                            @foreach($invoice_details as $detail)
                                                                <tr>
                                                                    <td style="height:30px;text-align:left;padding: 10px;">
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
                                                                <tr>
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
                                                        
                                                        <tr><td>&nbsp;</td></tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td colspan="2" style="padding: 0 0 15px 0px;width:100%;">
                                                <table style="width:100%" cellspacing="0" cellpadding="0" border="0">
                                            
                                                    <tbody>
                                                        <tr style="background-color:#CAD7F0;">
                                                            <th style="height:40px;width:70%;background-color:#eff6ff;text-align:right;padding: 10px;">
                                                                <span style="color: #ce1313;font: bold 14px/21px Arialâ,sans-serif;text-transform: uppercase;">Ballance</span>
                                                            </th>
                                                            <th style="height:40px;width:30%;background-color:#eff6ff;text-align:right;padding: 10px;">
                                                                <span style="color: #ce1313;font: bold 21px Arialâ,sans-serif;text-transform: uppercase;">${{$invoice->bill_amount}}</span>
                                                            </th>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>
            </div>
        </div>

    </section>
</div>
@endsection