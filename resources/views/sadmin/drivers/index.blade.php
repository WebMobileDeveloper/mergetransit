@extends("layout.customerLayout")
@section("contents")

<script>
    $(function(){
        $('#driverUsertable').DataTable( {
            'responsive': true,
            "bPaginate": false,
            "searching": false,
            "bFilter": false, 
            "bInfo": false,
            "order": [[0, "asc"]],
        } );

        $("input[name=assigned]").on("click", function(){
            $("#searchForm").submit();
        })
    })
   
    function edit(id) {
        var url = "{{ url('sadmin/drivers') }}";
        location.href=url + "/" + id + "/edit";
    }

    function delete_driver(id) {
        if(confirm("Are you sure to delete this driver really ?")) {
            var url = "{{ url('sadmin/drivers/delete') }}";
            location.href=url + "/" + id ;
        }      
    }

    function change_active(obj,driver_id){

        alert("This driver can be activated by admin or employee.")
       
        /*
        var isActive = document.getElementById("isActive").value;
        
        if(isActive==1){
           isActive = 0;
        }else{
            isActive = 1;
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "POST",
            dataType:'json',
            url: "{{ url('sadmin/drivers') }}"+"/setactive",
            data: {
                isActive : isActive, id : driver_id
            },
            success: function (data) {
                if(data=="ok"){
                    if(isActive==0){
                            $(obj).removeClass("btn-success");
                            $(obj).addClass("btn-danger");
                            $(obj).html("Block");
                                                    
                    }else{
                        $(obj).removeClass("btn-danger");
                        $(obj).addClass("btn-success");
                        $(obj).html("Active");
                    }
                    document.getElementById("isActive").value = isActive;
                }
            }
        });
        */
   }

   function sel_page(obj){
    // assigned=0&s=d&page=1
        var url = "{{ url('sadmin/details') }}";
        var page = obj.val();
        var search = $("input[name=s]").val();
        var assigned = $("input[name=assigned]").val();
        location.href=url + "?page=" + page + "&s=" + search + "&assigned="+assigned;
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
                  
                    <div class="box-header with-border">
                        {{-- <a href="{{url('/sadmin/drivers/create')}}" class="btn btn-info ">Add New</a> --}}
                        <div class="text-right">
                            <form action="{{url('/sadmin/drivers')}}" method="get" class="form-inline" id="searchForm">
                                <div class="form-group">
                               
                                   <label>Assigned</label> <input type="radio"  name="assigned" {{( $assigned == "1" || $assigned==NULL)?'checked':''}} value="1" />
                                   <label>No Assigned</label> <input type="radio"  name="assigned" {{( $assigned == "0")?'checked':''}} value="0" />
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="s" placeholder="Company/Driver " value="{{($s)?$s:''}}" />
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary" type="submit">Search</button>
                                </div>
                            </form>
                        </div>
                    </div>
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
                                               
                                                <th style="width: 5%;">is_active</th>
                                                <th style="width: 8%;">Employee</th>
                                                <th style="width: 15%;">Action</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                           
                                            @foreach ($drivers as $tr)
                                                <?php
                                                    $employee = App\User::find(explode(',',$tr->employee_id));
                                                ?>
                                                <tr class="gradeA odd {{(count($employee)==0)?'noassign':''}}" item_id="<?php echo $tr->id ?>">
                                                    <td class=" ">
                                                        @if($tr->company_id ==1)
                                                            {{$tr->firstname}} {{$tr->lastname}}(Own)
                                                        @else
                                                            {{$tr->company}} ({{$tr->firstname}})
                                                        @endif
                                                    </td>
                                                    <td class=" ">{{$tr->email }}</td>
                                                    <td class=" ">{{$tr->firstname}} {{$tr->lastname }}</td>
                                                    <td class=" ">{{$tr->phone }}</td>
                                                    <td class=" ">{{$tr->mc_number }}</td>
                                                    <td class=" ">{{$tr->equipment }}</td>
                                                    <td class=" ">{{$tr->max_weight }}</td>
                                                    <td class=" ">{{$tr->truck }}</td>
                                                    <td class=" ">{{$tr->trailer }}</td>
                                                   
                                                    <td class=" ">
                                                        <input type="hidden" id="isActive" name="isActive" value="{{$tr->is_active}}" />
                                                        @if($tr->is_active==1)
                                                            <a onclick="change_active(this,{{$tr->id}})" class="btn btn-success btn-xs edit">Active</a>
                                                        @else
                                                            <a onclick="change_active(this,{{$tr->id}})" class="btn btn-danger btn-xs edit">Block</a>
                                                        @endif
                                                    </td>
                                                    <td class=" ">
                                                        @foreach($employee as $elm)
                                                            {{$elm->firstname}} {{$elm->lastname}} <br>
                                                        @endforeach
                                                    </td>

                                                    <td class="center ">
                                                        <a onclick="edit({{$tr->id}})" class="btn btn-primary btn-xs edit"><i class="fa fa-edit "></i> Edit</a>                                                       
                                                        <a onclick = "delete_driver({{$tr->id}})"  class="btn btn-danger btn-xs delete"><i class="fa fa-trash "></i> Delete</a>
                                                    </td>
                                                   
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                    <div class="row">
                                        <div class="col-sm-1">
                                            <div class="dataTables_info" id="dataTables-example_info" role="alert" aria-live="polite" aria-relevant="all">Tatal <?php echo $drivers->total() ?> counts</div>

                                        </div>
                                         <div class="col-sm-2 ">
                                            <select class="form-control" name="page_id" onchange="sel_page($(this))">
                                                @for($i=0; $i <$drivers->lastPage(); $i++)
                                                    <option value={{$i+1}} {{($drivers->currentPage()==($i+1))?'selected':''}}>{{$i+1}}</option>
                                                @endfor
                                            </select>
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
