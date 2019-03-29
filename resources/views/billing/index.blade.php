@extends('layout.layout')

@section('contents')

<style>
    .div_hide{
        display:none;
    }
    
    
    .search_button{
        position: absolute;top: 0;right: 15px;    z-index: 10000;display:none;
    }
    .generate{
            margin-top: 10px;
            margin-right: 15px;
            float: right;
        }
    @media only screen and (max-width: 992px) {
        .search_button{
            display:block;
        }
    }
</style>
 <link rel="stylesheet" href="{{asset('assets/css/layout-datatables.css" rel="stylesheet" type="text/css')}}" />
 {{-- <link rel="stylesheet" href="{{asset('assets\admin\plugins\datatables\dataTables.bootstrap.css')}}"> --}}
 <link rel="stylesheet" href="{{asset('assets\admin\plugins\datatables\jquery.dataTables.css')}}">
 <link rel="stylesheet" href="{{asset('assets\admin\plugins\datatables\extensions\Responsive\css\dataTables.responsive.css')}}">
<!-- HERO -->
<section class="hero">
    <div class="container-fluid">	
        <div class="row">
            <div class="background"></div>
        </div>
    </div>
</section>	
<!-- /HERO -->
<section class="billing section1">
    <div class="container">
        <div class="row">
            <h3 class="title">Billing History</h3>
        
        <div class="row margin-top-20">
            
        </div>
       
        <div class="row margin-top-10 margin-bottom-80 ">
            <div class="col-md-12">
           
                <table class="table table-striped table-bordered table-hover dataTable no-footer" id="sample_2" style="font-size:14px !important;    width: 100%;">
                    <thead>
                        <tr>                       
                            <th> Invoice No</th>
                            <th> Due Date</th>
                            <th> Billable Amount</th>
                            <th> Status</th>
                            <th > action</th>                           
                        </tr>
                    </thead>
                    <tbody>
                    
                        @foreach($invoices as $invoice)
                        <tr class="{{($invoice->paid_status==0)?'':'success'}}" >
                            <td>{{$invoice->invoice_no}}</td>
                            <td>{{$invoice->due_date}}</td>
                            <td>${{$invoice->bill_amount}}</td>
                            <td>{{($invoice->paid_status==0)?'Pending':'Paid'}}</td>
                            <td>
                                <a onclick="show_invoice('{{$invoice->id}}')" type="button"  class="btn btn-3d btn-xs btn-reveal billing_show">Show</a>
                                <a href="{{url('/download-PDF/'.$invoice->id)}}"  class="btn btn-3d btn-xs btn-reveal btn-yellow btn-light">PDF DOWNLOAD</a>
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
           
            </div>
        </div>
    </div>
</div>
{{--  modal
  --}}

  
<div class="modal  bs-example-modal-lg" id="invoice_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">

			<!-- header modal -->
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myLargeModalLabel">Invoice</h4>
			</div>

			<!-- body modal -->
			<div class="modal-body" style="height:600px;overflow-y:auto">
				<div class="invoice" >
        
                    <!-- title row -->
                    <div class="row">
                        <div class="col-xs-12">
                        <h2 class="page-header">
                            
                            <img src="http://mergetransit.com/assets/images/logo.png" alt="Merge Transit, LLC" width="100px">
                            <small class="pull-right">Date: <span id="in_due_date">2017-10-07</span></small>
                            
                        </h2>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- info row -->
                    <div class="row invoice-info">
                        <div class="col-sm-4 invoice-col">
                        From
                        <address >
                            <strong>Merge Transit, LLC.</strong><br>
                            Billing@mergetransit.com<br>
                            www.mergetransit.com
                        </address>
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4 invoice-col">
                        To
                        <address>
                            <strong><span id="customer_name">Hugo Bracamontes</span></strong><br>
                            <span id="company_name">Bracamontes Trucking</span><br>
                            <span id="customer_email">bracamontestrucking17@gmail.com</span><br>
                           <span id="customer_phone"> 915-282-3251</span><br>
                        </address>

                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4 invoice-col">
                        <b>Invoice:  #<span id="invoice_no">1005</span></b><br>
                        <b>Date: </b> <span id="invoice_date">2017-10-04</span><br>
                        <b>Due Date: </b> <span id="due_date">2017-10-07</span><br>
                        <b>Terms: </b> Due on receipt
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->

                    <!-- Table row -->
                    <div class="row">
                    
                        <div class="col-xs-12 table-responsive">
                           <table class="table table-striped">
                            <thead style="background: #46f1f8;">
                                <tr>
                                    <th>Activity</th>
                                    <th>Qty</th>
                                    <th>Rate</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody class="invoice_detail_tbody">
                                                                    
                                    {{--  <tr class="detail_tr">
                                        <td>
                                            <span style="font-weight:700;font-size:15px;">
                                                Dispatch Over $1,000
                                            </span><br>
                                            Dispatch Over $1,000 -P/U 
                                            2017-10-04
                                            $2300.00 (COVENANT) 
                                            <span style="font-weight:700;font-size:15px;">Gustavo</span>                           
                                        </td>
                                        <td>1</td>
                                        <td>50</td>
                                        <td class="to_rate">50</td>
                                    </tr>  --}}
                                   
                            </tbody>
                        </table>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->

                    <div class="row">
                        <!-- accepted payments column -->
                        <div class="col-xs-6">
                        
                        </div>
                        <!-- /.col -->
                        <div class="col-xs-6">
                        <div class="table-responsive">
                            <table class="table">
                            <tbody>
                                <tr>
                                <th style="width:50%">Balance Due:</th>
                                <td style="font-size:18px;font-weight:700;color:#e65959">$<span id="bill_amount">150</span></td>
                               
                                </tr> 
                                <tr>
                                    <th colspan="2" style="text-align: center;">
                                    <span id="paid_status" style="font-size:24px;color:red;font-weight:bold"></span>
                                    </th>
                                <tr>             
                            </tbody>
                            </table>
                        </div>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->

                </div>
			</div>

		</div>
	</div>
</div>

</section>
<link rel="stylesheet" href="{{asset('assets\admin\plugins\datatables\extensions\Responsive\css\dataTables.responsive.css')}}">

        <script type="text/javascript" src="assets/plugins/jquery/jquery-2.2.3.min.js"></script>
        <script type="text/javascript" src="assets/plugins/datatables/js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" src="assets/plugins/datatables/js/dataTables.tableTools.min.js"></script>
		<!-- <script type="text/javascript" src="assets/plugins/datatables/js/dataTables.colReorder.min.js"></script> -->
		<script type="text/javascript" src="assets/plugins/datatables/js/dataTables.scroller.min.js"></script>
		<script type="text/javascript" src="assets/plugins/datatables/dataTables.bootstrap.js"></script>
        <script type="text/javascript" src="assets/plugins/select2/js/select2.full.min.js"></script>
        <!-- DataTables -->
        <script src="{{asset('assets/admin/plugins/datatables/jquery.dataTables.js')}}"></script>
        <script src="{{asset('assets/admin/plugins/datatables/dataTables.bootstrap.js')}}"></script>
        <script src="{{asset('assets\admin\plugins\datatables\extensions\Responsive\js\dataTables.responsive.js')}}"></script>
		<script type="text/javascript">
            //download pdf
            function pdf_download(id){

            }   
            
            //show invoice detail
            function show_invoice(id){
                $.get(
                    "{{ url('/show_invoice/') }}"+"/"+id,
                    function(result){
                      
                        var customer = result.customer;
                        var invoice = result.invoice;
                        var invoice_details = result.invoice_details;
                        var invoice_specials = result.invoice_specials;

                        $("#in_date").html(invoice.due_date)
                        $("#invoice_date").html(invoice.due_date)
                        $("#due_date").html(invoice.due_date)
                        $("#invoice_no").html(invoice.invoice_no)
                        $("#bill_amount").html(invoice.bill_amount)
                        $("#paid_status").html((invoice.paid_status==1)?"PAID":"");

                        

                        $("#customer_name").html(customer.firstname+" "+customer.lastname)
                        $("#company_name").html(customer.company)
                        $("#customer_email").html(customer.email)
                        $("#customer_phone").html(customer.phone)

                        var table_html = "";
                        if(invoice_details.length>0){
                            for(i=0;i<invoice_details.length;i++){
                                table_html+='<tr class="detail_tr">'+
                                        '<td>'+
                                            '<span style="font-weight:700;font-size:15px;">'+
                                                ((invoice_details[i].revenue>1000)?"Dispatch Over $1,000":"Dispatch Under $1,000")+
                                            '</span><br>'+
                                            ((invoice_details[i].revenue>1000)?"Dispatch Over $1,000":"Dispatch Under $1,000")+ '-P/U '+ 
                                            invoice_details[i].pu_date+' ' +
                                            '$'+invoice_details[i].revenue+' ('+invoice_details[i].tra_company+')'+ 
                                            '<span style="font-weight:700;font-size:15px;">'+invoice_details[i].driver_name+'</span>' +                    
                                        '</td>'+
                                        '<td>1</td>'+
                                        '<td>'+invoice_details[i].billing_amount+'</td>'+
                                        '<td class="to_rate">'+invoice_details[i].billing_amount+'</td>'+
                                    '</tr>';
                            }
                        }
                        
                        if(invoice_specials.length>0){
                            for(i=0;i<invoice_specials.length;i++){
                                table_html+='<tr>'+
                                                '<td style="height:30px;text-align:left;padding: 10px;">'+
                                                    '<span style="font-weight:700;font-size:15px;">'+invoice_specials[i].activity+'</span>'+
                                                '</td>'+
                                                '<td style="height:30px;padding: 10px;">'+
                                                    '<span style="color: #717171;font:  14px/21px â??Arialâ??,sans-serif;">'+invoice_specials[i].qty+'</span>'+
                                                '</td>'+
                                                '<td style="height:30px;padding: 10px;">'+
                                                    '<span style="color: #717171;font:  14px/21px â??Arialâ??,sans-serif;">'+invoice_specials[i].rate+'</span>'+
                                                '</td>'+
                                                '<td style="height:30px;padding: 10px;">'+
                                                    '<span style="color: #717171;font:  14px/21px â??Arialâ??,sans-serif;">'+invoice_specials[i].amount+'</span>'+
                                                '</td>'+
                                            '</tr>';    
                            }
                        }
                        $(".invoice_detail_tbody").append(table_html);
                        $("#invoice_modal").show();

                    },'json'
                );

            }
           $(function(){
               $(".close").click(function(){
                    $(".invoice_detail_tbody tr").remove();
                   $("#invoice_modal").hide();
               })
           })
			if (jQuery().dataTable) {
				function initTable2() {
					var table = jQuery('#sample_2');

					var oTable = table.dataTable({
                        'responsive': true,
                        "bPaginate": true,
                        "searching": false,
                        "bFilter": false, 
                        "bInfo": false,
                        "order":[[1, "desc"]],
						/* "columnDefs": [{
                             "orderable": false,
                             "targets": [0]
                         }],*/
                        
                         "lengthMenu": [
                             [5, 15, 20, -1],
                             [5, 15, 20, "All"] // change per page values here
                         ],
                        // set the initial value
                        "pageLength": 5,   
					});

					var tableWrapper = jQuery('#sample_2_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
					tableWrapper.find('.dataTables_length select').select2(); // initialize select2 dropdown
				}
				initTable2();
			}

		</script>
@endsection
