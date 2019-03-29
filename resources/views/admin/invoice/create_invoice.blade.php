
@extends("layout.adminLayout")
@section("contents")


<script>
    //generate invoice
    function generate_invoice() {
        if($("input[name=bill_amount]").val()==0){
            alert("Ballance is 0. Can not generate the invoice.");
        }else{
            if(confirm("Are you sure to generate new invoice?")){
              if($("#invoice_form").valid()==false){
                $("#invoice_form").valid();return false;
              }else{

                $("#invoice_gen").button("loading")
                $("input[name=special_count]").val($('tr.sp_tr').length);

                console.log($("#invoice_form").serializeArray());
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    dataType:'json',
                    url: "{{ url('admin/create_invoice') }}",
                    data: $("#invoice_form").serializeArray(),
                    success: function (data) {
                      if(data.status=='OK'){
                        if(confirm("Created successfully!\n Could you send the invoice email to customer right now?")){
                          $("#invoice_gen").button("stop")
                          sendInvoice();
                        }else{
                            var url = "{{ url('admin/invoice') }}";
                            location.href=url;
                        }
                      }
                        
                    }
                });
              }
            }
        }        
    }

    //send email invoice
    function sendInvoice(){

      var customer_id = $("input[name=customer_id]").val();
      var due_date = $("input[name=due_date]").val();
      var invoice_no = $("input[name=invoice_no]").val();

      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
      $.ajax({
          type: "POST",
          dataType:'json',
          url: "{{ url('admin/send_invoice') }}",
          data: {customer_id:customer_id, due_date:due_date,invoice_no:invoice_no},
          success: function (data) {
            if(data.status=='success'){
             alert("Sent the invoice successfully!");
             
            }else{
              alert("Failed!");
            }
            var url = "{{ url('admin/invoice') }}";
            location.href=url;
              
          }
      });
    }

    //Case of Chrome and OverDrive
    function add_row() {
      var html = '<tr class="sp_tr" style=";background:#ecefe9">'+
                      '<td >'+
                          '<textarea rows="1" class="form-control" name="activity[]" required ></textarea>'+   
                      '</td>'+
                      '<td class="sp_qty"><input class="form-control" onkeyup="calc($(this))" name="sp_qty[]" placeholder="QTY" type="text" value="1" required></td>'+
                      '<td class="sp_rate"><input class="form-control" onkeyup="calc($(this))" name="sp_rate[]" placeholder="rate" type="text" value="0" required></td>'+
                      '<td class="sp_amount"><input class="form-control" id="to_amount" name="sp_amount[]"  placeholder="" value="0" type="text"></td>'+
                      '<td><a onclick="remove_row($(this))" class="label label-danger">Remove</a></td>'+
                  '</tr>';
      $(".invoice_detail_tbody").append(html);
    }
    

    function remove_row(obj){
      obj.parent().parent().remove();
      total_calc();
    }
    function calc(obj) {
        var tr_obj = obj.parent().parent();
        var sp_qty = tr_obj.children(".sp_qty").find("input").val();

       
        if(sp_qty=="")sp_qty=0;
        var sp_rate = tr_obj.children(".sp_rate").find("input").val();
        if(sp_rate=="")sp_rate=0;
        tr_obj.children(".sp_amount").find("input").val(sp_qty*sp_rate);

        total_calc();

    }
    function total_calc(){
       var special_total = 0;
       var detail_total = 0;
      $('tr.sp_tr').each(function() {
          special_total += parseFloat($(this).find("input[id=to_amount]").val());
        });
        
        $('tr.detail_tr').each(function(tr) {
          detail_total += parseFloat($(this).find(".to_rate").html());
        });
        $("input[name=bill_amount]").val(parseFloat(detail_total) + parseFloat(special_total));
        $(".bill_amount").html(parseFloat(detail_total) + parseFloat(special_total));
    }
</script>

<div class="content-wrapper" style="min-height: 916px;">
    <form action="" id="invoice_form" > 
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Content Header (Page header) -->
    <section class="content-header">
     
    </section>

    <div class="pad margin no-print">
      @if(date('D')!='Sat')
      <div class="callout callout-info" style="margin-bottom: 0!important;">
        <h4><i class="fa fa-info"></i> Note:</h4>
        The invoice will be generated at Saturday per week.
      </div>
     
      @endif
    </div>
        <input type = "hidden" name ="customer_id" value="{{$customer->id}}" />
        <input type = "hidden" name ="due_date" value="{{$info['due_date']}}" />
        <input type = "hidden" name ="invoice_no" value="{{(count($invoice)==0)?'1001':$invoice[0]->invoice_no + 1}}" />
        
    <!-- Main content -->
    <section class="invoice">
        
      <!-- title row -->
      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
            {{--  <i class="fa fa-globe"></i> Merge Transit, LLC.  --}}
            <img src="{{asset('assets/images/logo.png')}}" alt="Merge Transit, LLC" width="100px">
            <small class="pull-right">Date: {{$info['due_date']}}</small>
            
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->
      <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
          From
          <address>
            <strong>Merge Transit, LLC.</strong><br>
            Billing@mergetransit.com<br>
            www.mergetransit.com
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          To
          <address>
            <strong>{{$customer->firstname}} {{$customer->lastname}}</strong><br>
            {{$customer->company}}<br>
            {{$customer->email}}<br>
            {{$customer->phone}}<br>
          </address>

        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          <b>Invoice:  #{{(count($invoice)==0)?'1001':$invoice[0]->invoice_no + 1}}</b><br>
          <b>Date: </b> {{date('Y-m-d')}}<br>
          <b>Due Date: </b> {{$info['due_date']}}<br>
          <b>Terms: </b> Due on receipt
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- Table row -->
      <div class="row">
     
        <div class="col-xs-12 table-responsive">
            @if($customer->manual_invoice==1)
            <div class="box-header with-border" style="padding-left: 0;">
                <a  onclick="add_row()" class="btn btn-success btn-sm aaa">ADD ROW</a>
            </div>
            @endif
          <table class="table table-striped">
            <thead style="background: #46f1f8;">
                <tr>
                    <th>Activity</th>
                    <th>Qty</th>
                    <th>Rate</th>
                    <th>Amount</th>
                    <th></th>
                </tr>
            </thead>
            <tbody class="invoice_detail_tbody">
                <?php
                    $total_amount = 0;
                    $n = 0;
                ?>
                <input name="total_count" value="{{count($dispatch_detail)}}" type="hidden" />
                @foreach($dispatch_detail as $order)
                   
                    <tr class="detail_tr">
                        <td>
                            <span style="font-weight:700;font-size:15px;">
                                {{($order['revenue']>=1000)?"Dispatch Over $1,000":"Dispatch Under $1,000"}}
                            </span><br>
                            {{($order['revenue']>=1000)?"Dispatch Over $1,000":"Dispatch Under $1,000"}} -P/U 
                            {{explode(" ",$order['pu_date'])[0]}}
                            ${{$order['revenue']}} ({{$order['trans_company']}}) 
                            <span style="font-weight:700;font-size:15px;">{{$order['driver_name']}}</span>                           
                        </td>
                        <td>1</td>
                        <td>{{$order['rate']}}</td>
                        <td class="to_rate">{{$order['rate']}}</td>
                        <td></td>

                        <input type="hidden" name = "revenue[]" value="{{$order['revenue']}}" />
                        <input type="hidden" name = "trans_company[]" value="{{$order['trans_company']}}" />
                        <input type="hidden" name = "driver_name[]" value="{{$order['driver_name']}}" />
                        <input type="hidden" name = "pu_date[]" value="{{explode(" ",$order['pu_date'])[0]}}" />
                        <input type="hidden" name = "rate[]" value="{{$order['rate']}}"/>
                    </tr>
                    <?php
                        $total_amount+= $order['rate'];
                        $n++;
                    ?>
                @endforeach
                <input name="special_count" value="0" type="hidden" />
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
                  <td  style="font-size:18px;font-weight:700;color:#e65959">$<span class="bill_amount">{{$total_amount}}</span></td>
                  <input type="hidden" name="bill_amount" value="{{$total_amount}}" />
                </tr>              
              </tbody>
            </table>
          </div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- this row will not appear when printing -->
      <div class="row no-print">
        <div class="col-xs-12">
          <a href="{{url('admin/invoice')}}" class="btn btn-default"><i class="fa fa-back"></i> Back to Invoice List</a>
          
       
            <a onclick="generate_invoice()" id="invoice_gen" class="btn btn-info ">Create Invoice</a>
        
          
          {{--  <a href="invoice-print.html" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print</a>  --}}
          {{--  <button type="button" class="btn btn-success pull-right"><i class="fa fa-credit-card"></i> Submit Payment
          </button>
          <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px;">
            <i class="fa fa-download"></i> Generate PDF
          </button>  --}}
        </div>
      </div>
    </section>
    <!-- /.content -->
    <div class="clearfix"></div>
    </form>
  </div>

  @endsection