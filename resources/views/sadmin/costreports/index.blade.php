@extends("layout.customerLayout")
@section("contents")

<script>
    $(function(){
        $('#reporttable1').DataTable( {
            'responsive': true,
            "bPaginate": true,
            "searching": true,
            "bFilter": false, 
            "bInfo": false,
            "order": [[3, "desc"]],
            "sPaginationType": "listbox"
        } );

         $('#reporttable2').DataTable( {
            'responsive': true,
            "bPaginate": true,
            "searching": true,
            "bFilter": false, 
            "bInfo": false,
            "order": [[3, "desc"]],
            "sPaginationType": "listbox"
        } );

        $("#startdate").datepicker({ format: 'yyyy-mm-dd',autoclose: true });
        $("#enddate").datepicker({ format: 'yyyy-mm-dd',autoclose: true });

        $(".addexpense").on("click",function(){
            var url = "{{ url('sadmin/loadexpense') }}";
            location.href=url;
        })
        $(".addmaintenance").on("click",function(){
            var url = "{{ url('sadmin/maintenance') }}";
            location.href=url;
        })


    })
    function expense_edit(id){
        var url = "{{ url('sadmin/loadexpense') }}";
        location.href=url+"/"+id;
    }

    function maintenance_edit(date, id){
        var url = "{{ url('sadmin/maintenance/') }}";
        location.href=url+"/"+date +"/"+id;
    }


</script>
<style>
.info-box{
    box-shadow: none;
}
.info-box-content {

    padding: 5px 10px;
    margin-left: 0px;
    text-align: center;
    color: #ff5100;

}
</style>
<div class="content-wrapper" style="min-height: 916px;">
<meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Cost Reports Generate
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
       
        <div class="row">
            <div class="col-xs-12">      
                <div class="box account_list">
                  
                    <div class="box-header with-border">
                        <form class="form-horizontal" id="user_form" name="user_form" action="{{url('sadmin/costreports_generate/')}}" method = "post">

                        {{ csrf_field() }}
                            <div class="row " style="margin-bottom:15px;">
                                <div class="col-xs-2">
                                    <!-- select driver -->
                                    <select class = "form-control" name = "driver_id" requried>
                                        <option value = "">All</option>               
                                        @foreach ($drivers as $driver)  
                                                                         
                                                <option value= {{$driver->id}} {{(!empty($res_data) && $driver->id == $res_data['driver_id'])?"selected":""}}>
                                                    @if($driver->company_id == 1)
                                                        {{$driver->firstname}} {{$driver->lastname}}
                                                    @else
                                                        {{$driver->company}} ({{$driver->firstname}})
                                                    @endif
                                                </option>
                                           
                                        @endforeach
                                    </select>                                
                                </div>
                              
                                <div class="col-xs-2">
                                    <input type="text" name="startdate" value = "{{(!empty($res_data))?$res_data['start_date']:""}}" class="form-control" autocomplete="off" placeholder="Date" id="startdate">
                                </div>
                                <div class="col-xs-2">
                                    <input type="text" name="enddate" value = "{{(!empty($res_data))?$res_data['end_date']:""}}" class="form-control" autocomplete="off" placeholder="Date" id="enddate">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-info pull-right">Generate</a>
                        </form>
                    </div>
                    <!-- /.box-header -->


                    <div class="box-body">
                        <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                            @if(!empty($costinfo))
                            <div class="row">
                                <div class="col-md-2 col-sm-6 col-xs-12">
                                    <div class="info-box">                      
                                        <div class="info-box-content">
                                            <span class="info-box-text">Total Revenue</span>
                                            <span class="info-box-number">{{$costinfo['total_revenue']}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-6 col-xs-12">
                                    <div class="info-box">      
                                        <div class="info-box-content">
                                            <span class="info-box-text">Total Cost</span>
                                            <span class="info-box-number">{{$costinfo['total_cost']}}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2 col-sm-6 col-xs-12">
                                    <div class="info-box">
                                        <div class="info-box-content">
                                            <span class="info-box-text">Total Miles</span>
                                            <span class="info-box-number">{{$costinfo['total_mile']}}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2 col-sm-6 col-xs-12">
                                    <div class="info-box">
                                        <div class="info-box-content">
                                            <span class="info-box-text">Total DHO</span>
                                            <span class="info-box-number">{{$costinfo['total_dho']}}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2 col-sm-6 col-xs-12">
                                    <div class="info-box">
                                        <div class="info-box-content">
                                            <span class="info-box-text">Total RPM</span>
                                            <span class="info-box-number">{{$costinfo['total_rpm']}}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2 col-sm-6 col-xs-12">
                                    <div class="info-box">
                                        <div class="info-box-content">
                                            <span class="info-box-text">Total CPM</span>
                                            <span class="info-box-number">{{$costinfo['cost_rpm']}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">Load Expense</a></li>
                                    <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">Maintenance</a></li>                                
                                </ul>
                                <div class="tab-content">

                                    <div class="tab-pane active" id="tab_1">

                                        <div class="row" style="margin: 20px 0;">
                                            <button type="button" class="btn btn-info pull-left addexpense">Add LoadExpense</a>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <table id="reporttable1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                                                    <thead>
                                                        <tr role="row">
                                                            <th style="width: 10%;">Date</th>                                             
                                                            <th style="width: 10%;">Driver Name</th>                                             
                                                            <th style="width: 10%;">Company</th> 
                                                            <th style="width: 6%;">Po</th>                                            
                                                            <th style="width: 6%;">Fuel</th>
                                                            <th style="width: 8%;">Gallons</th>
                                                            <th style="width: 8%;">DEF</th>
                                                            <th style="width: 8%;">Parking</th>
                                                            <th style="width: 8%;">Tolls</th>
                                                            <th style="width: 6%;">Lumper</th>
                                                            <th style="width: 6%;">Hotel</th>
                                                            <th style="width: 6%;">Other</th>
                                                            <th style="width: 6%;">Total</th>
                                                            <th style="width: 6%;">Attach</th>
                                                            <th style="width: 5%;">Edit</th>
                                                            
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    @if(! empty($result_load))                                                       
                                                        @foreach ($result_load as $tr)     
                                                            <?php
                                                            foreach ($drivers as $driver) {
                                                                if ($driver->id == $tr->driver_id) {
                                                                    $driver_name = $driver->firstname . " " . $driver->lastname;
                                                                }
                                                            }
                                                            ?>                                                     
                                                            <tr class="gradeA odd" item_id="<?php echo $tr->id ?>">
                                                                
                                                                <td class=" ">{{$tr->date }}</td>
                                                                <td class=" ">{{$driver_name}}</td>
                                                                <td class=" ">{{$tr->s_Company }}</td>
                                                                <td class=" ">{{$tr->s_PO}}</td>
                                                                <td class=" ">{{$tr->s_Fuel }}</td>
                                                                <td class=" ">{{$tr->s_Gallons }}</td>
                                                                <td class=" ">{{$tr->s_DEF }}</td>
                                                                <td class=" ">{{$tr->s_Parking }}</td>
                                                                <td class=" ">{{$tr->s_Tolls }}</td>
                                                                <td class=" ">{{$tr->s_Lumper }}</td>
                                                                <td class=" ">{{$tr->s_Hotel }}</td>
                                                                <td class=" ">{{$tr->s_Other }}</td>
                                                                <td class=" ">{{$tr->s_Total }}</td>
                                                                <td class=" ">
                                                                    <?php
                                                                    $url = explode(",",$tr->file_path);
                                                                    $name = explode(",",$tr->file_name );
                                                                    
                                                                    ?>
                                                                    @for($i=0;$i<count($url);$i++)
                                                                    <a href='{{$url[$i]}}'>{{$name[$i]}}</a><br>
                                                                    @endfor
                                                                </td>
                                                                <td class="center ">
                                                                    <a onclick="expense_edit({{$tr->detail_id}})" class="btn btn-primary btn-xs edit"><i class="fa fa-edit "></i> Edit</a>                                                       
                                                                </td>
                                                            
                                                            </tr>
                                                        @endforeach

                                                        
                                                    @endif
                                                    </tbody>
                                                </table>

                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <div class="dataTables_info" id="dataTables-example_info" role="alert" aria-live="polite" aria-relevant="all"></div>

                                                    </div>
                                                    <div class="col-sm-9 ">
                                                        <div  id="dataTables-example_paginate" style="float:right">
                                                            
                                                        </div>
                                                    </div> 
                                                </div>
                                            
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab_2">
                                        <div class="row" style="margin: 20px 0;">
                                            <button type="button" class="btn btn-info pull-left addmaintenance">Add Maintenance</a>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <table id="reporttable2" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                                                    <thead>
                                                        <tr role="row">
                                                            <th style="width: 10%;">Date</th>                                             
                                                            <th style="width: 10%;">Driver Name</th>                                             
                                                            <th style="width: 10%;">Purpose</th>                                             
                                                            <th style="width: 6%;">Cost</th>
                                                            <th style="width: 6%;">Description</th>
                                                            <th style="width: 8%;">Attach</th>
                                                            <th style="width: 8%;">Edit</th>                                                            
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    @if(! empty($result_maintenance))
                                                     
                                                        @foreach ($result_maintenance as $tr)

                                                            <?php
                                                            foreach ($drivers as $driver) {
                                                                if ($driver->id == $tr->driver_id) {
                                                                    $driver_name = $driver->firstname . " " . $driver->lastname;
                                                                }
                                                            }
                                                            ?>    
                                                           
                                                            <tr class="gradeA odd" item_id="<?php echo $tr->id ?>">
                                                                
                                                                <td class=" ">{{$tr->date }}</td>
                                                                <td class=" ">{{$driver_name }}</td>
                                                                <td class=" ">{{$tr->s_Purpose}}</td>
                                                                <td class=" ">{{$tr->s_Cost }}</td>
                                                                <td class=" ">{{$tr->s_Description }}</td>
                                                            
                                                                <td class=" ">
                                                                    <?php
                                                                    $url = explode(",",$tr->file_path);
                                                                    $name = explode(",",$tr->file_name );
                                                                    
                                                                    ?>
                                                                    @for($i=0;$i<count($url);$i++)
                                                                    <a href='{{$url[$i]}}'>{{$name[$i]}}</a><br>
                                                                    @endfor
                                                                </td>
                                                                <td class="center ">
                                                                    <a onclick="maintenance_edit('{{$tr->date}}', {{$tr->id}})" class="btn btn-primary btn-xs edit"><i class="fa fa-edit "></i> Edit</a>                                                       
                                                                </td>
                                                            
                                                            </tr>
                                                        @endforeach

                                                        
                                                    @endif
                                                    </tbody>
                                                </table>

                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <div class="dataTables_info" id="dataTables-example_info" role="alert" aria-live="polite" aria-relevant="all"></div>

                                                    </div>
                                                    <div class="col-sm-9 ">
                                                        <div  id="dataTables-example_paginate" style="float:right">
                                                            
                                                        </div>
                                                    </div> 
                                                </div>
                                            
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
