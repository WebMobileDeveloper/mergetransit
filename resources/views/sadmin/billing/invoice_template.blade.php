
@extends("layout.customerLayout")
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
               
                var detail_id = $("input[name=detail_id]").val();
                
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    dataType:'json',
                    url: "{{ url('sadmin/generate_invoice') }}",
                    data: $("#invoice_form").serializeArray(),
                    success: function (data) {
                        $("#invoice_gen").button("stop")
                      if(data.status=='ok'){
                        if(confirm("Created successfully!")){
                            var url = "{{ url('sadmin/billing') }}";
                            location.href=url;
                        }else{
                           alert("Something Error!")
                        }
                      }
                        
                    }
                });
              }
            }
        }        
    }

    

    //Case of Chrome and OverDrive
    function add_row() {
      var html = '<tr class="sp_tr" style=";background:#ecefe9">'+
                      '<td >'+
                          '<input class="form-control" name="activity[]" required />'+   
                      '</td>'+
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
        var sp_rate = tr_obj.children(".sp_rate").find("input").val();
        if(sp_rate=="")sp_rate=0;
        tr_obj.children(".sp_amount").find("input").val(sp_rate);
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
    <input type="hidden" name="detail_id" value="{{$detail[0]->id}}" />
    <section class="content-header">
        <h1>Create Invoice</h1>
    </section>

    <!-- Main content -->
    <section class="invoice">
        
      <!-- title row -->
      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header" style="display: flex;">
            <div class="col-xs-6">
                   <b>{{$customer->company}}</b><br>
                   Phone: {{$customer->phone}}<br>
                   PO box {{$detail[0]->po}}<br>

                   {{$customer->street}}<br>
                   {{$customer->city.",".$customer->state." ".$customer->zipcode}}<br>
            </div>
            
            <div class="col-xs-6"><img class="pull-right" src="{{asset('assets/images/invoice_logo.png')}}" alt="Merge Transit, LLC" width="100px"></div>
            
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->

      <h1 >Invoice</h1>
      <div class="row invoice-info" style="border-bottom: solid 2px gray;      margin-bottom: 15px;">
        
        <div class="col-sm-4 invoice-col">
          Bill To:
          <address>
            <strong>{{$detail[0]->d_company_name}}</strong><br>
            {{$detail[0]->address1}}<br>
            {{$detail[0]->city.",".$detail[0]->state." ".$detail[0]->zipcode}}<br>
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
            <b>Invoice num:  #{{date('ymd')}}</b><br>
            <b>Terms: </b> Net 30 days<br>
            <b>Date: </b> {{date('Y-m-d')}}<br>
            <b>Due Date: </b> {{Date('Y-m-d', strtotime("+30 days"))}}<br>
            
        </div>
      </div>


      <div class="row invoice-info">        
            <div class="col-sm-4 invoice-col">
              <strong>From:</strong>
              <p>               
                {{$detail[0]->origin}},{{$detail[0]->origin_zip}}               
              </p>
              <strong>To:</strong>
              <p>               
                {{$detail[0]->destination}},{{$detail[0]->destination_zip}}   
              </p>
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
                <b>PO #   :  {{$detail[0]->po}}</b><br>
                <b>Truck #: </b>{{$driver->truck}}<br>
                <b>Driver : </b> {{$driver->firstname." ".$driver->lastname}}<br>
                
            </div>
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
            <thead style="background: #2187b6;">
                <tr>
                    <th>Activity</th>
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
                <input name="total_count" value="{{count($detail)}}" type="hidden" />
                @foreach($detail as $order)
                <?php $pu_date = new DateTime($order['put_date']);?>
                   
                    <tr class="detail_tr">
                        <td>                            
                            <span style="font-weight:700;font-size:15px;">Load Shipped On  {{$pu_date->format('Y-m-d')}}</span>                           
                        </td>
                        <td>{{$order['revenue']}}</td>
                        <td class="to_rate">{{$order['revenue']}}</td>
                        <td></td>
                    </tr>
                    <?php
                        $total_amount+= $order['revenue'];
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
          <a href="{{url('sadmin/billing')}}" class="btn btn-default"><i class="fa fa-back"></i> Back to Invoice List</a>
          
       
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