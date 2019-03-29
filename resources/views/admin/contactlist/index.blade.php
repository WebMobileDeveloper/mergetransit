@extends("layout.adminLayout")
@section("contents")

<script>
   function edit_item(obj) {
       var id = obj.parent().parent().attr("item_id");
       var url = "{{ url('admin/contactlist') }}";
       location.href=url + "/" + id + "/edit";
   }
   

   function delete_item(obj) {
        var id = obj.parent().parent().attr("item_id");       
        if(confirm("Are you sure to delete this Company info ?")) {
            var url = "{{ url('admin/contactlist/delete') }}";
            location.href=url + "/" + id ;
        }

    }
    function sel_page(obj){
    
        var url = "{{ url('admin/contactlist') }}";
        var page = obj.val();
        var search = $("input[name=s]").val();
        location.href=url + "?page=" + page + "&s=" + search;
    }


   

</script>
<div class="content-wrapper" style="min-height: 916px;">
   <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
           Customers Contact information
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">               
                <!--//Account list-->
                <div class="box account_list">
                  
                    <div class="box-header with-border">
                        <a href="{{url('/admin/contactlist/create')}}" class="btn btn-info ">Add New</a>
                        <div class="text-right">
                            <form action="{{url('/admin/contactlist')}}" method="get" class="form-inline">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="s" placeholder="Company" value="{{($s)?$s:''}}" />
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
                                               
                                                <th style="width: 150px;">No</th>
                                                <th style="width: 150px;">Company</th>
                                                <th style="width: 150px;">Address1</th>
                                                <th style="width: 150px;">Address2</th>
                                                <th style="width: 150px;">City</th>
                                                <th style="width: 150px;">State</th>
                                                <th style="width: 150px;">Zip Code</th>                                            
                                                <th style="width: 268px;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $n = 0;
                                            foreach ($contactlist as $tr):
                                               $n++;
                                                ?>
                                                <tr class="gradeA odd" item_id="<?php echo $tr->id ?>">
                                                    <td class=""><?php echo $n ?></td>
                                                    <td class=" "><?php echo $tr->d_company_name ?></td>
                                                    <td class=" "><?php echo $tr->address1 ?></td>
                                                    <td class=" "><?php echo $tr->address2 ?></td>
                                                    <td class=" "><?php echo $tr->city ?></td>
                                                    <td class=" "><?php echo $tr->state ?></td>
                                                    <td class=" "><?php echo $tr->zipcode ?></td>
                                                    
                                                    <td class="center ">
                                                        <a onclick="edit_item($(this))" class="btn btn-primary btn-xs edit"><i class="fa fa-edit "></i> Edit</a>                                                       
                                                        <a onclick="delete_item($(this))" class="btn btn-danger btn-xs delete"><i class="fa fa-trash "></i> Delete</a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>

                                    <div class="row">
                                        <div class="col-sm-1">
                                            <div class="dataTables_info" id="dataTables-example_info" role="alert" aria-live="polite" aria-relevant="all">Total <?php echo $contactlist->total() ?> counts</div>

                                        </div>
                                        <div class="col-sm-2 ">
                                            <select class="form-control" name="page_id" onchange="sel_page($(this))">
                                                @for($i=0; $i <$contactlist->lastPage(); $i++)
                                                    <option value={{$i+1}} {{($contactlist->currentPage()==($i+1))?'selected':''}}>{{$i+1}}</option>
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