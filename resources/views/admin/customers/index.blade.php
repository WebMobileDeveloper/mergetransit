@extends("layout.adminLayout")
@section("contents")

<script>
   function edit_customer(obj) {
       var id = obj.parent().parent().attr("item_id");
       var url = "{{ url('admin/customers') }}";
       location.href=url + "/" + id + "/edit";
   }
   

   function delete_customer(obj) {
        var id = obj.parent().parent().attr("item_id");       
        if(confirm("Are you sure to delete this customer ?")) {
            var url = "{{ url('admin/customers/delete') }}";
            location.href=url + "/" + id ;
        }

    }

    function sel_page(obj){
    // assigned=0&s=d&page=1
        var url = "{{ url('admin/customers') }}";
        var page = obj.val();
        var search = $("input[name=s]").val();
        location.href=url + "?page=" + page + "&s=" + search;
    }

   function change_active(obj,user_id, customer_id){
       
    var isActive = $(obj).parent().find("input#isActive").val();
    
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
        url: "{{ url('admin/customers') }}"+"/setactive",
        data: {
            isActive : isActive, user_id : user_id, customer_id: customer_id
        },
        success: function (data) {
            if(data.status=="success"){
                if(isActive==0){
                    $(obj).removeClass("btn-success");
                    $(obj).addClass("btn-danger");
                    $(obj).html("Block");
                    
                                                
                }else{
                    $(obj).removeClass("btn-danger");
                    $(obj).addClass("btn-success");
                    $(obj).html("Active");

                }
                $(obj).parent().find("input#isActive").val(isActive);
            } else {
                alert(data.err);
                return false;
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
           Customers
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">               
                <!--//Account list-->
                <div class="box account_list">
                  
                    <div class="box-header with-border">
                        <a href="{{url('/admin/customers/create')}}" class="btn btn-info ">Add New</a>
                        <div class="text-right">
                            <form action="{{url('/admin/customers')}}" method="get" class="form-inline">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="s" placeholder="Company or Email" value="{{($s)?$s:''}}" />
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
                                    <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                                        <thead>
                                            <tr role="row">
                                                <th style="width: 50px;"><input type="checkbox" /></th>
                                                <th style="width: 150px;">Company</th>
                                                <th style="width: 150px;">First Name</th>
                                                <th style="width: 150px;">Last Name</th>
                                                <th style="width: 150px;">Email</th>
                                                <th style="width: 150px;">Phone</th>
                                                <th style="width: 150px;">Address</th>
                                                <th style="width: 268px;">Description</th>
                                                <th style="width: 268px;">Attached Files</th>
                                                <th style="width: 150px;">is_active</th>
                                                <th style="width: 150px;">Member Type</th>
                                                <th style="width: 268px;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach ($customers as $tr):
                                                $attach_file_arr = explode("," ,$tr->image_path);

                                                ?>
                                                <tr class="gradeA odd" item_id="<?php echo $tr->id ?>">
                                                    <td class=""><input type="checkbox" /></td>
                                                    <td class=" "><?php echo $tr->company ?></td>
                                                    <td class=" "><?php echo $tr->firstname ?></td>
                                                    <td class=" "><?php echo $tr->lastname ?></td>
                                                    <td class=" "><?php echo $tr->email ?></td>
                                                    <td class=" "><?php echo $tr->phone ?></td>
                                                    <td class=" "><?php echo $tr->street.' '.$tr->city. ' ' . $tr->state. ' '. $tr->zipcode ?></td>
                                                    <td class=" "><?php echo $tr->description ?></td>
                                                    <td class=" ">
                                                        <?php 
                                                            if(count($attach_file_arr)>0):
                                                                foreach($attach_file_arr as $file) :
                                                                $file_arr = str_replace("http://mergetransit.com/files/","", $file);
                                                                ?>
                                                                <a href="{{$file}}" target="_blank">{{$file_arr}}</a><br>
                                                            <?php 
                                                                 endforeach;
                                                            endif;
                                                         ?>
                                                    </td>
                                                   
                                                    <td class=" ">
                                                        <input type="hidden" id="isActive" name="isActive" value="{{$tr->is_active}}" />
                                                        @if($tr->is_active==1)
                                                            <a onclick="change_active(this,{{$tr->u_id}}, {{$tr->id}})" class="btn btn-success btn-xs edit">Active</a>
                                                        @else
                                                            <a onclick="change_active(this,{{$tr->u_id}}, {{$tr->id}})" class="btn btn-danger btn-xs edit">Block</a>
                                                        @endif
                                                    </td>

                                                    <td class="">
                                                        @if ($tr->member_type == 1) 
                                                             <span>Free</span>  
                                                        @elseif($tr->member_type == 2) 
                                                             <span>Organization</span>  
                                                        @else
                                                             <span>Optimization</span>  
                                                        @endif
                                                    </td>

                                                    <td class="center ">
                                                        <a onclick="edit_customer($(this))" class="btn btn-primary btn-xs edit"><i class="fa fa-edit "></i> Edit</a>                                                       
                                                        <a onclick="delete_customer($(this))" class="btn btn-danger btn-xs delete"><i class="fa fa-trash "></i> Delete</a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>

                                    <div class="row">
                                        <div class="col-sm-1">
                                            <div class="dataTables_info" id="dataTables-example_info" role="alert" aria-live="polite" aria-relevant="all">Tatal <?php echo $customers->total() ?> counts</div>

                                        </div>
                                        <div class="col-sm-2 ">
                                            <select class="form-control" name="page_id" onchange="sel_page($(this))">
                                                @for($i=0; $i <$customers->lastPage(); $i++)
                                                    <option value={{$i+1}} {{($customers->currentPage()==($i+1))?'selected':''}}>{{$i+1}}</option>
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