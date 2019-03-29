@extends("layout.adminLayout")
@section("contents")

<script>
    function edit(obj) {
        var id = obj.parent().parent().attr("item_id");
        var url = "{{ url('admin/users') }}";
        location.href=url + "/" + id + "/edit";
    }

    function change_active(obj){
        var user_id = obj.parent().parent().attr("item_id");
        var isActive = obj.parent().attr("isActive");
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
            url: "{{ url('admin/users') }}"+"/setactive",
            data: {
                isActive : isActive, id : user_id
            },
            success: function (data) {
                if(data=="ok"){
                    if(isActive==0){
                            obj.removeClass("btn-success");
                            obj.addClass("btn-danger");
                            obj.html("Block");
                                                    
                    }else{
                            obj.removeClass("btn-danger");
                            obj.addClass("btn-success");
                            obj.html("Active");
                    }
                    obj.parent().attr("isActive",isActive);   
                }
            }
        });
        
    

   }



</script>
<div class="content-wrapper" style="min-height: 916px;">
<meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Users
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">               
                <!--//Account list-->
                <div class="box account_list">
                  
                    <div class="box-header with-border">
                        <a href="{{url('/admin/users/create')}}" class="btn btn-info ">Add New</a>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                                        <thead>
                                            <tr role="row">
                                                <th style="width: 15%;">First Name</th>
                                                <th style="width: 15%;">Last Name</th>
                                                <th style="width: 15%;">Email</th>
                                                <th style="width: 10%;">Phone</th>
                                                <th style="width: 5%;">is_active</th>
                                                <th style="width: 10%;">Role</th>
                                                <th style="width: 10%;">Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                            @foreach ($users as $tr)
                                               
                                                <tr class="gradeA odd" item_id="<?php echo $tr->id ?>">
                                                    <td class=" ">{{$tr->firstname}}</td>
                                                    <td class=" ">{{$tr->lastname }}</td>
                                                    <td class=" ">{{$tr->email }}</td>
                                                    <td class=" ">{{$tr->phone }}</td>
                                                    <td isActive="{{$tr->is_active}}" class=" ">
                                                        @if($tr->is_active==1)
                                                            <a onclick="change_active($(this))" class="btn btn-success btn-xs edit">Active</a>
                                                        @else
                                                            <a onclick="change_active($(this))" class="btn btn-danger btn-xs edit">Block</a>
                                                        @endif
                                                    </td>
                                                    <td class=" ">{{$tr->title }}</td>
                                                    <td class=" ">{{$tr->created_at }}</td>

                                                    <td class="center ">
                                                        <a onclick="edit($(this))" class="btn btn-primary btn-xs edit"><i class="fa fa-edit "></i> Edit</a>                                                       
                                                        <a href="{{ url('admin/users/delete/'.$tr->id)}}" data-method="delete" class="btn btn-danger btn-xs delete"><i class="fa fa-trash "></i> Delete</a>
                                                    </td>
                                                </tr>
                                               
                                            @endforeach
                                        </tbody>
                                    </table>

                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="dataTables_info" id="dataTables-example_info" role="alert" aria-live="polite" aria-relevant="all">Tatal <?php echo $users->total() ?> counts</div>

                                        </div>
                                         <div class="col-sm-9 ">
                                            <div  id="dataTables-example_paginate" style="float:right">
                                                <?php echo $users->render(); ?>

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
