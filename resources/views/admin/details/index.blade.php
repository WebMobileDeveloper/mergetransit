@extends("layout.adminLayout")
@section("contents")

<script>
     $(function(){
         $('#dispatchdetailtable').DataTable( {
             'responsive': true,
             "bPaginate": false,
             "searching": false,
             "bFilter": true, 
             "bInfo": false,
             "order": [[0, "asc"]],
         } );
     })
     function sel_page(obj){
    
        var url = "{{ url('admin/details') }}";
        var page = obj.val();
        var search = $("input[name=s]").val();
        location.href=url + "?page=" + page + "&s=" + search;
    }
    function edit(id) {
        var url = "{{ url('admin/details') }}";
        location.href=url + "/" + id + "/edit";
    }

    function delete_detail(id) {
        if(confirm("Are you sure to delete this detail?")) 
        {
            var url = "{{ url('admin/details/delete') }}";
            location.href=url + "/" + id + '/detail' ;
        }      
    }


</script>

<style>
    #dispatchdetailtable thead th {
        cursor: pointer;
    }

</style>
<div class="content-wrapper" style="min-height: 916px;">
<meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Dispatch Details
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">               
                <!--//Account list-->
                <div class="box account_list">
                  
                    <div class="box-header with-border">
                        <a href="{{url('/admin/details/create')}}" class="btn btn-info ">Add New</a>
                        <div class="text-right">
                            <form action="{{url('/admin/details')}}" method="get" class="form-inline">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="s" placeholder="PO#" value="{{($s)?$s:''}}" />
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
                                    <table id="dispatchdetailtable" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                                        <thead>
                                            <tr role="row">
                                                <th style="width: 10%;">Driver Name</th>
                                                <th style="width: 10%;">Company</th>                                             
                                                <th style="width: 10%;">Address</th>                                             
                                                <th style="width: 6%;">Contact</th>
                                                <th style="width: 6%;">Po#</th>
                                                <th style="width: 6%;">Load#</th>
                                                <th style="width: 8%;">Pu Date</th>
                                                <th style="width: 8%;">Del Date</th>
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
                                         
                                            @foreach ($details as $tr)
                                                <?php
                                                
                                                
                                               
                                                    $driver = App\Driver::find($tr->driver_id);
                                                    $user = App\User::find($tr->user_id); 
                                                    $company = App\Customer::find($driver->company_id);
                                                   // var_dump($company);
                                                    if(count($company ) == 0) {
                                                    	$company_name = "";
                                                    } else {
                                                    	$company_name= $company->company;
                                                    }

                                                    $emps_arr = explode(",",$driver->employee_id);
                                                     
                                                    //shipping detail
                                                    
                                                    $contact = App\Contact_list::find($tr->contact_id);
                                                    if($contact != NULL) {
                                                       
                                                        
                                                    } else {
                                                        $contact = new stdClass();
                                                        $contact->d_company_name = '';
                                                        $contact->address1 = '';
                                                        $contact->address2 = '';
                                                        $contact->state = '';
                                                        $contact->city = '';
                                                        $contact->zipcode = '';
                                                    }
                                                    
                                                   
                                                ?>
                                                
                                                <tr class="gradeA odd" item_id="<?php echo $tr->id ?>">
                                                    <td class=" ">
                                                        {{$company_name}} ({{$user->firstname}})
                                                    </td>
                                                    <td class=" ">{{$contact->d_company_name }}</td>
                                                    <td class=" ">{{($contact->address1=="")?"":$contact->address1.",".$contact->address2." ".$contact->city.",".$contact->state." ".$contact->zipcode }}</td>
                                                    <td class=" ">{{$tr->contact}}</td>
                                                    <td class=" ">{{$tr->po }}</td>
                                                    <td class=" ">{{$tr->load_num }}</td>
                                                    <td class=" ">{{$tr->put_date }}</td>
                                                    <td class=" ">{{$tr->del_date }}</td>
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
                                                    	<a href='{{$url[$i]}}' target="_blank">{{$name[$i]}}</a><br>
                                                    	@endfor
                                                    </td>
                                                   
                                                    <td class="center ">
                                                        <a onclick="edit({{$tr->id}})" class="btn btn-primary btn-xs edit"><i class="fa fa-edit "></i> Edit</a>                                                       
                                                        <a onclick="delete_detail({{$tr->id}})"  class="btn btn-danger btn-xs delete"><i class="fa fa-trash "></i> Delete</a>
                                                    </td>
                                                </tr>
                                               
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="row">
                                        <div class="col-sm-1">
                                            <div class="dataTables_info" id="dataTables-example_info" role="alert" aria-live="polite" aria-relevant="all">Tatal <?php echo $details->total() ?> counts</div>

                                        </div>
                                        <div class="col-sm-2 ">
                                            <select class="form-control" name="page_id" onchange="sel_page($(this))">
                                                @for($i=0; $i <$details->lastPage(); $i++)
                                                    <option value={{$i+1}} {{($details->currentPage()==($i+1))?'selected':''}}>{{$i+1}}</option>
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
