@extends("layout.adminLayout")
@section("contents")

<script>
    $(function(){
        $('#reporttable').DataTable( {
            'responsive': true,
            "bPaginate": true,
            "searching": false,
            "bFilter": false, 
            "bInfo": false,
            "order": [[3, "desc"]],
            "sPaginationType": "listbox"
        } );

        $("#startdate").datepicker({ format: 'yyyy-mm-dd',autoclose: true });
        $("#enddate").datepicker({ format: 'yyyy-mm-dd',autoclose: true });
    })

    function edit(id) {
        var url = "{{ url('admin/details') }}";
        location.href=url + "/" + id + "/edit";
    }
   
   function delete_detail(id) {
   
        if(confirm("Are you sure to delete this detail?")) 
        {
            var url = "{{ url('admin/details/delete') }}";
            location.href=url + "/" + id + "/report";
        }      
  
   }


</script>
<div class="content-wrapper" style="min-height: 916px;">
<meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Reports Generate
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">

       
        <div class="row">
            <div class="col-xs-12">      
                <div class="box account_list">
                  
                    <div class="box-header with-border">
                        <form class="form-horizontal" id="user_form" name="user_form" action="{{url('admin/reports/')}}" method = "post">

                        {{ csrf_field() }}
                            <div class="row " style="margin-bottom:15px;">
                                <div class="col-xs-2">
                                    <!-- select driver -->
                                    <select class = "form-control" name = "driver_id" requried>
                                       
                                        @foreach ($drivers as $driver)
                                            <?php
                                                $emps_arr = explode(",",$driver->employee_id);
                                            ?>
                                            @if(Auth::user()->role==3)
                                                @if(in_array(Auth::user()->id,$emps_arr))                                                
                                                    <option value= {{$driver->id}} {{(!empty($res_data) && $driver->id == $res_data['driver_id'])?"selected":""}}>
                                                        @if($driver->company_id == 1)
                                                            {{$driver->firstname}} {{$driver->lastname}}
                                                        @else
                                                            {{$driver->company}} ({{$driver->firstname}})
                                                        @endif
                                                    </option>
                                                @endif
                                            @else
                                                <option value= {{$driver->id}} {{(!empty($res_data) && $driver->id == $res_data['driver_id'])?"selected":""}}>
                                                    @if($driver->company_id == 1)
                                                        {{$driver->firstname}} {{$driver->lastname}}
                                                    @else
                                                        {{$driver->company}} ({{$driver->firstname}})
                                                    @endif
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>                                
                                </div>
                                <div class="col-xs-2">
                                    <input type="text" name="company" value = "{{(!empty($res_data))?$res_data['company']:""}}" class="form-control" placeholder="Input Company">
                                </div>
                                <div class="col-xs-2">
                                    <input type="text" name="po" value = "{{(!empty($res_data))?$res_data['po']:""}}" class="form-control" placeholder="PO#">
                                </div>
                                <div class="col-xs-2">
                                    <input type="text" name="origin" value = "{{(!empty($res_data))?$res_data['origin']:""}}" class="form-control" placeholder="Input Origin">
                                </div>
                                <div class="col-xs-2">
                                    <input type="text" name="destination" value = "{{(!empty($res_data))?$res_data['destination']:""}}"  class="form-control" placeholder="Input Destination">
                                </div>
                                <div class="col-xs-1">
                                    <input type="text" name="startdate" value = "{{(!empty($res_data))?$res_data['start_date']:""}}" class="form-control" autocomplete="off" placeholder="Date" id="startdate">
                                </div>
                                <div class="col-xs-1">
                                    <input type="text" name="enddate" value = "{{(!empty($res_data))?$res_data['end_date']:""}}" class="form-control" autocomplete="off" placeholder="Date" id="enddate">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-info pull-right">Generate</a>
                        </form>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                            @if(!empty($infor))
                            <div class="row">
                                <div class="col-sm-12 report_details" style="color: #e33030;">
                                    <div class="col-xs-3">
                                        <label class="col-sm-12 control-label">{{$infor['driver_name']}}<br>{{$infor['from']}} ~ {{$infor['to']}}</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th>Total Revenue</th>
                                                <th>Total Miles</th>
                                                <th>Total DHO</th>
                                                <th>RPM</th>
                                                <th>DH RPM</th>
                                            </tr> 
                                            <tr>
                                                <td>{{$infor['total_revenue']}}</td>
                                                <td>{{$infor['total_mile']}}</td>
                                                <td>{{$infor['total_dho']}}</td>
                                                <td>{{$infor['total_rpm']}}</td>
                                                <td>{{$infor['total_dhrpm']}}</td>
                                            </tr> 
                                        </table>
                                    </div>
                                    <div class="col-xs-3 ">
                                        <a class="btn btn-app pull-right">
                                            <i class="fa fa-file-excel-o"></i> Excel
                                        </a>
                                        <a class="btn btn-app pull-right">
                                            <i class="fa fa-file-pdf-o"></i> PDF
                                        </a>
                                    </div>
                                    
                                </div>
                            </div>
                            @endif

                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="reporttable" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                                        <thead>
                                            <tr role="row">
                                                <th style="width: 10%;">Company</th>                                             
                                                <th style="width: 6%;">Contact</th>
                                                <th style="width: 6%;">Po</th>
                                                <th style="width: 6%;">Load#</th>
                                                <th style="width: 8%;">Pu Date</th>
                                                <th style="width: 8%;">Del Date</th>
                                                <th style="width: 8%;">Origin</th>
                                                <th style="width: 8%;">Destination</th>
                                                <th style="width: 6%;">Weight</th>
                                                <th style="width: 6%;">Revenue</th>
                                                <th style="width: 6%;">Miles</th>
                                                <th style="width: 6%;">DH-O</th>
                                                <th style="width: 6%;">RPM</th>
                                                <th style="width: 5%;">DH RPM</th>
                                                <th style="width: 5%;">Attach</th>
                                                <th style="width: 5%;">Action</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @if(! empty($reports))
                                            <?php
                                                $total_revenue = 0;
                                                $total_mile = 0;
                                                $total_dho = 0;
                                            ?>
                                            @foreach ($reports as $tr)
                                                <?php

                                                    $total_revenue = $total_revenue + $tr->revenue;
                                                    $total_mile = $total_mile + $tr->mile;
                                                    $total_dho = $total_dho + $tr->dho;

                                                ?>
                                                <tr class="gradeA odd" item_id="<?php echo $tr->id ?>">
                                                    
                                                    <td class=" ">{{$tr->company }}</td>
                                                    <td class=" ">{{$tr->contact}}</td>
                                                    <td class=" ">{{$tr->po }}</td>
                                                    <td class=" ">{{$tr->load_num }}</td>
                                                    <td class=" ">{{$tr->put_date }}</td>
                                                    <td class=" ">{{$tr->del_date }}</td>
                                                    <td class=" ">{{$tr->origin }}</td>
                                                    <td class=" ">{{$tr->destination }}</td>
                                                    <td class=" ">{{$tr->weight }}</td>
                                                    <td class=" ">{{$tr->revenue }}</td>
                                                    <td class=" ">{{$tr->mile }}</td>
                                                    <td class=" ">{{$tr->dho }}</td>
                                                    <td class=" ">{{$tr->rpm }}</td>
                                                    <td class=" ">{{$tr->dh_rpm }}</td>
                                                    <td class=" ">
                                                    	<?php
                                                    	$url = explode(",",$tr->upload);
                                                    	$name = explode(",",$tr->filename );
                                                    	
                                                    	?>
                                                    	@for($i=0;$i<count($url);$i++)
                                                    	<a href='{{$url[$i]}}'>{{$name[$i]}}</a><br>
                                                    	@endfor
                                                    </td>
                                                    <td class=" ">
                                                        <a onclick="edit({{$tr->id}})" class="btn btn-primary btn-xs edit"><i class="fa fa-edit "></i>Edit</a>
                                                        <a onclick="delete_detail({{$tr->id}})" class="btn btn-danger btn-xs trash"><i class="fa fa-trash "></i>Delete</a>
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
