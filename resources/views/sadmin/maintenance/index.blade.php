@extends("layout.customerLayout")
@section("contents")

<script>
$(function(){

        $('#reporttable2').DataTable( {
            'responsive': true,
            "bPaginate": true,
            "searching": false,
            "bFilter": false, 
            "bInfo": false,
            "order": [[3, "desc"]],
            "sPaginationType": "listbox"
        } );

    var cur_date = new Date();
   

    $("#sel_date").datepicker({ 
        format: 'yyyy-mm-dd',
        autoclose: true

    }).on('changeDate', select_month);;

   
    //Initialize Select2 Elements
    $(".select2").select2();

    $(".reset_button").on("click",function(){
        $(".save_button").text("Add Maintenance");
        $("input[name=maintenance_id]").val(0);
        $(".sel_tr").removeClass("sel_tr");
        $("input[name=cost]").val(0);
        $("textarea[name=description]").val("");
        $(this).remove()
    })
});

function select_month() {
    var new_date = $("#sel_date").val(); 
    var url = "{{ url('sadmin/maintenance') }}";
    location.href=url + "/" + new_date + "/" + 0;
    
}
function maintenance_edit(id, sel_date) {
    var url = "{{ url('sadmin/maintenance') }}"; 
    location.href=url + "/" + sel_date + "/" + id;
}
</script>
<style>
    .table-striped > tbody > tr.sel_tr{
        background-color: #dff8df;
    }
    .padding-top-100 {
        padding-top:100px;
    }
    </style>
<div class="content-wrapper" style="min-height: 916px;">
<meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Maintenace
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12 box box-info padding-top-100" >
                <!-- /.box -->
                <div class="col-md-4">
                <div class="">
                    
                    <!-- form start -->

                    <form class="form-horizontal" id="expense_form" name="expense_form" action="{{url('sadmin/maintenance')}}" method = "post">
                        {{ csrf_field() }}
                        <!-- {{ method_field('PATCH') }} -->
                        <div class="box-body">
                            @if($status = Session::get("status"))
                                <div class="alert alert-error form-group">
                                    <span class="help-error">
                                        <strong>{{$status}}</strong>
                                    </span>
                                </div><br>
                            @endif

                            <input type="hidden" name="editflag" value="{{ $editflag }}" />
                            <input type="hidden" name="maintenance_id" value="{{ $editflag==1 ?$main_edit[0]->id : '0' }}" />
                            <div class="form-group custom_input">
                                <label class="col-sm-4 control-label">Date<span class="required">*</span></label>
                               
                                <div class="col-xs-8">                                  
                                   <input id="sel_date" class="form-control" name="sel_date" type="text" value="{{$seldate}}" placeholder="" autocomplete="off"  required>                                  
                                </div>
                                
                            </div>
                            <div class="form-group custom_input">
                                <label class="col-sm-4 control-label">Drivers</label>
                                <div class="col-xs-8">
                                    <!-- select driver -->
                                    <select class = "form-control" name = "driver_id" requried>
                                       @foreach ($drivers as $driver)                                           
                                               <option value= {{$driver->id}} {{ ($editflag==1 && $driver->id == $main_edit[0]->driver_id)?"selected":""}}>
                                                   @if($driver->company_id == 1)
                                                       {{$driver->firstname}} {{$driver->lastname}}
                                                   @else
                                                       {{$driver->company}} ({{$driver->firstname}})
                                                   @endif
                                               </option>
                                          
                                       @endforeach
                                   </select>

                                </div>
                            </div>
                            <div class="form-group custom_input">
                                <label class="col-sm-4 control-label">Cost</label>
                                 <div class="col-xs-8">
                                    <input class="form-control" name="cost" type="text" value="{{ ($editflag==1)?$main_edit[0]->cost:'0' }}" placeholder="" >
                                </div>
                            </div>    
                            <div class="form-group custom_input">
                                <label class="col-sm-4 control-label">Purpose</label>
                                <div class="col-xs-8">
                                    <select class="form-control" name="purpose">
                                        <option  value= "Maintenance" {{ ($editflag==1 && "Maintenance" == $main_edit[0]->purpose)?"selected":""}}>Maintenance</option>
                                        <option  value= "Repair" {{ ($editflag==1 && "Repair" == $main_edit[0]->purpose)?"selected":""}}>Repair</option>
                                        <option  value= "Licensing/Testing" {{ ($editflag==1 && "Licensing/Testing" == $main_edit[0]->purpose)?"selected":""}}>Licensing/Testing</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group custom_input">
                                <label class="col-sm-4 control-label">Description</label>
                                <div class="col-xs-8">
                                    <textarea class="form-control" name="description" rows="3" placeholder="Enter ...">{{ ($editflag==1)?$main_edit[0]->description:'' }}</textarea>
                                </div>
                            </div>
                            
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-info save_button">{{ ($editflag==0)?'Add Expense':'Update Expense' }}</button>
                            @if($editflag==1)
                            <button type="button" class="btn btn-info reset_button">Reset</button>
                            @endif
                            
                            
                        </div>
                    </form>
                </div>
                </div>
                <div class="col-md-8">
                <table id="reporttable2" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                        <thead>
                            <tr role="row">
                                <th style="width: 10%;">Date</th>                                             
                                <th style="width: 10%;">Driver Name</th>                                             
                                <th style="width: 10%;">Purpose</th>                                             
                                <th style="width: 6%;">Cost</th>
                                <th style="width: 6%;">Description</th>
                                <!-- <th style="width: 8%;">Attach</th> -->
                                <th style="width: 8%;">Edit</th>                                                            
                            </tr>
                        </thead>
                        <tbody>
                        @if(! empty($maintenance))
                            
                            @foreach ($maintenance as $tr)

                                <?php
                                foreach ($drivers as $driver) {
                                    if ($driver->id == $tr->driver_id) {
                                        $driver_name = $driver->firstname . " " . $driver->lastname;
                                    }
                                }
                                ?>    
                                
                                <tr class="gradeA odd {{ ($editflag==1 && $main_edit[0]->id == $tr->id)?'sel_tr':'' }}" item_id="<?php echo $tr->id ?>">
                                    
                                    <td class=" ">{{$tr->date }}</td>
                                    <td class=" ">{{$driver_name }}</td>
                                    <td class=" ">{{$tr->purpose}}</td>
                                    <td class=" ">{{$tr->cost }}</td>
                                    <td class=" ">{{$tr->description }}</td>
                                
                                    <!-- <td class=" ">
                                        <?php
                                        $url = explode(",",$tr->file_path);
                                        $name = explode(",",$tr->file_name );
                                        
                                        ?>
                                        @for($i=0;$i<count($url);$i++)
                                        <a href='{{$url[$i]}}'>{{$name[$i]}}</a><br>
                                        @endfor
                                    </td> -->
                                    <td class="center ">
                                        <a onclick="maintenance_edit({{$tr->id}},'{{$seldate}}')" class="btn btn-primary btn-xs edit"><i class="fa fa-edit "></i> Edit</a>                                                       
                                    </td>
                                
                                </tr>
                            @endforeach

                            
                        @endif
                        </tbody>
                    </table>
                </div>
               
                <!-- /.col -->
            </div>
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<style>
    .form-group.custom_input{
        margin-right: 20px
    }
    .beforemonth , .aftermonth {
        width:100%;
    }
</style>
@endsection
