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
        } );
    })
   
    function create_invoice(id) {
        var url = "{{ url('admin/invoice') }}";
        location.href=url + "/" + id + "/create_invoice";
    }

    function view_detail(id) {
        var url = "{{ url('admin/invoice') }}";
        location.href=url + "/" + id + "/view_detail";
    }

</script>
<div class="content-wrapper" style="min-height: 916px;">
<meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Invoice [{{$date_array['weekstart']}}  ~  {{$date_array['weekend']}}]
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">               
                <!--//Account list-->
                <div class="box account_list">
{{--                    
                    <div class="box-header with-border">
                        <a href="{{url('/admin/drivers/create')}}" class="btn btn-info ">Add New</a>
                    </div>  --}}
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="driverUsertable" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                                        <thead>
                                            <tr role="row">
                                                <th style="width: 8%;">Company</th>
                                                <th style="width: 8%;">Customer Name</th>
                                                <th style="width: 8%;">Email</th>                                      
                                                <th style="width: 8%;">Phone</th>
                                                <th style="width: 15%;">Action</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                           
                                            @foreach ($customers as $tr)

                                            <?php
                                                $customer_id = $tr->id;
                                                $block_user = App\User::where('email',$tr->email)->where('is_active',1)->get();


                                                $drivers = App\Driver::where("company_id",$customer_id)->get();
                                                $dispatch_detail = array();
                                                $details_count = 0;
                                                foreach($drivers as $driver){
                                                    $driver_detail =  App\User::where('id',$driver->user_id)->get();
                                                    $details =  App\Detail::where('driver_id',$driver->id)->get();

                                                    $new_details =  App\Detail::where('driver_id',$driver->id)->whereBetween('put_date', [$date_array['weekstart'], $date_array['weekend']." 23:59:59"])->get();
                                                    foreach($details as $detail){
                                                        $dispatch_detail[]=array(
                                                            'trans_company'=>$detail->company,
                                                            'driver_id'=>$detail->driver_id,
                                                        );
                                                    }
                                                    foreach($new_details as $new){
                                                        $details_count++;
                                                    }
                                                }
                                            ?>
                                            @if(count($block_user)>0 && (count($dispatch_detail)>0 || $tr->manual_invoice==1))
                                                <?php
                                                    //get invoice create status
                                                    $due_date = $date_array['weekend'];
                                                    $create_id = $customer_id."_".$due_date;
                                                    $invoice = App\Invoice::where('create_id',$create_id)->where('customer_id',$customer_id)->get();
                                                   
                                                    $create_status =0;
                                                    $send_status = 0;
                                                    if(count($invoice)>0){
                                                        $create_status = 1;
                                                        $send_status = $invoice[0]->send_status;
                                                    }
                                                    //get sent status
                                                   
                                                ?>
                                              
                                                <tr class="gradeA odd" item_id="<?php echo $tr->id ?>" style="background:{{($send_status==1)?'#a4e3dc':''}}">
                                                    <td class=" ">{{$tr->company }} </td>
                                                    <td class=" ">{{$tr->firstname}} {{$tr->lastname }}</td>
                                                    <td class=" ">{{$tr->email }}</td>
                                                    <td class=" ">{{$tr->phone }}</td>
                                                
                                                    <td class="center ">
                                                        @if($create_status==1)
                                                            <a class="btn btn-success btn-xs "><i class="fa fa-edit "></i> Created</a> 
                                                        @elseif($details_count>0 || $tr->manual_invoice==1)
                                                            <a onclick="create_invoice({{$tr->id}})" class="btn btn-primary btn-xs edit"><i class="fa fa-edit "></i> Invoice Create</a>                                                       
                                                        @elseif($details_count==0)
                                                            <a  onclick="create_invoice({{$tr->id}})" class="btn btn-info btn-xs "><i class="fa fa-edit "></i> No Details Data</a> 
                                                        @endif
                                                        <a onclick="view_detail({{$tr->id}})" class="btn btn-default btn-xs edit"><i class="fa fa-edit "></i> View details</a>                                                       
                                                        {{--  <a onclick="edit({{$tr->id}})" class="btn btn-primary btn-xs edit"><i class="fa fa-edit "></i> Edit</a>                                                         --}}
                                                    </td>
                                                   
                                                </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>

                                   
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <!-- /.box -->
                </div>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<style>
    .form-group.custom_input{
        margin-right: 20px
    }
</style>
@endsection
