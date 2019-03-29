@extends("layout.adminLayout")
@section("contents")

<script>

    $(function(){
        $('#driverUsertable').DataTable( {
            'responsive': true,
            "bPaginate": false,
            "searching": false,
            "bFilter": false, 
            "bInfo": false
        });
    });

    function invoice_view(id){
        var url = "{{ url('admin/invoice') }}";
        location.href=url + "/" + id + "/view_invoice";
    }

    function invoice_delete(id) {
        if(confirm("are you sure ?")) {
            var url = "{{ url('admin/invoice/delete') }}";
            location.href=url + "/" + id ;
        }
    } 

    function invoice_edit(id){
        var url = "{{ url('admin/invoice') }}";
        location.href=url + "/" + id + "/invoice_edit";
    }

    function set_payment(obj,id){
        var td_obj = obj.parent();
        if(confirm("Are you sure to make PAID?")){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                dataType:'json',
                url: "{{ url('admin/set_payment') }}",
                data: {id:id},
                success: function (data) {
                    console.log(data)
                    if(data.status == 'fail'){
                        alert(data.msg);
                    }else{
                        td_obj.children().remove();
                        td_obj.append('<span style="color:red;font-weight:bold;">PAID</span>');
                    }
                }
            });
        }
        
    }
</script>

<div class="content-wrapper" style="min-height: 916px;">
<meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Invoice History
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">               
                <!--//Account list-->
                <div class="box account_list">
                  
                    <div class="box-header with-border">
                        <span>{{$customer->company}} </span><br>
                        <span>{{$customer->firstname}} {{$customer->lastname}} </span><br>
                        <span>{{$customer->email}} </span><br>
                        <span>{{$customer->phone}} </span>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="driverUsertable" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                                        <thead>
                                            <tr role="row">
                                                <th style="width: 8%;">Invoice No.</th>
                                                <th style="width: 8%;">Due Date</th>
                                                <th style="width: 8%;">Bill Amount</th>                                      
                                                <th style="width: 8%;">Email send</th>
                                                <th style="width: 8%;">Paid</th>
                                                <th style="width: 15%;">Action</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                           
                                            @foreach ($invoices as $tr)
                                            
                                              
                                                <tr class="gradeA odd" item_id="<?php echo $tr->id ?>">
                                                    <td class=" ">{{$tr->invoice_no }}</td>
                                                    <td class=" ">{{$tr->due_date}} </td>
                                                    <td class=" ">{{$tr->bill_amount }}</td>
                                                    <td class=" ">
                                                    @if($tr->send_status==1)
                                                        <span class="label label-success">Sent</span>
                                                    @else
                                                        <span class="label label-info">Not send</span>
                                                    @endif
                                                    </td>
                                                    <td class="payment_status" style="text-align:center">
                                                        @if($tr->paid_status==0)
                                                            <a onclick="set_payment($(this),{{$tr->id}})" class="btn btn-primary btn-xs edit"><i class="fa fa-get-pocket "></i> Pay </a>
                                                        @else
                                                            <span style="color:red;font-weight:bold;">PAID</span>
                                                        @endif
                                                    </td>
                                                
                                                    <td class="center ">
                                                        {{-- @if($customer->manual_invoice==1) --}}
                                                        <a onclick="invoice_edit({{$tr->id}})" class="btn btn-default btn-xs edit"><i class="fa fa-edit "></i> Edit</a>  
                                                        {{-- @endif                                                      --}}
                                                        <a onclick="invoice_view({{$tr->id}})" class="btn btn-primary btn-xs edit"><i class="fa fa-edit "></i> View</a>  
                                                        <a onclick="invoice_delete({{$tr->id}})" class="btn btn-danger btn-xs delete"><i class="fa fa-trash "></i> Delete</a>                                                     
                                                    </td>
                                                   
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                   
                                </div>
                            </div>
                        </div>
                    </div>
                     <!-- this row will not appear when printing -->
                   
                    <!-- /.box-body -->
                    <!-- /.box -->
                </div>
                <div class="row no-print">
                    <div class="col-xs-12">
                         <a href="{{url('admin/invoice')}}" class="btn btn-default"><i class="fa fa-back"></i> Back to Invoice List</a>
                    </div>
                </div>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>

@endsection