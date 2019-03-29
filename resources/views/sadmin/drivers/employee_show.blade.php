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
    
   }



</script>
<div class="content-wrapper" style="min-height: 916px;">
<meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Drivers
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">               
                <!--//Account list-->
                <div class="box account_list">
                  
                   
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="driverUsertable" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                                        <thead>
                                            <tr role="row">
                                                <th style="width: 8%;">Driver Name</th>
                                                <th style="width: 8%;">Email</th>
                                                <th style="width: 8%;">Name</th>                                             
                                                <th style="width: 8%;">Phone</th>
                                                <th style="width: 8%;">MC Number</th>
                                                <th style="width: 8%;">Equipment</th>
                                                <th style="width: 7%;">Max Weight</th>
                                                <th style="width: 7%;">Truck Number</th>
                                                <th style="width: 8%;">Trailer Number</th>
                                                                                             
                                            </tr>
                                        </thead>
                                        <tbody>
                                           
                                            @foreach ($drivers as $tr)

                                                <?php
                                                    $employee_arr = explode(',',$tr->employee_id);

                                                ?>
                                                @if(in_array(Auth::user()->id,$employee_arr))
                                                <tr class="gradeA odd" item_id="<?php echo $tr->id ?>">
                                                    <td class=" ">                                                     
                                                            {{$tr->company}} ({{$tr->firstname}})                                                      
                                                    </td>
                                                    <td class=" ">{{$tr->email }}</td>
                                                    <td class=" ">{{$tr->firstname}} {{$tr->lastname }}</td>
                                                    <td class=" ">{{$tr->phone }}</td>
                                                    <td class=" ">{{$tr->mc_number }}</td>
                                                    <td class=" ">{{$tr->equipment }}</td>
                                                    <td class=" ">{{$tr->max_weight }}</td>
                                                    <td class=" ">{{$tr->truck }}</td>
                                                    <td class=" ">{{$tr->trailer }}</td>
                                                   
                                                   
                                                </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>

                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="dataTables_info" id="dataTables-example_info" role="alert" aria-live="polite" aria-relevant="all">Tatal <?php echo $drivers->total() ?> counts</div>

                                        </div>
                                         <div class="col-sm-9 ">
                                            <div  id="dataTables-example_paginate" style="float:right">
                                                <?php echo $drivers->render(); ?>

                                            </div>
                                        </div> 
                                    </div>
                                   
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
