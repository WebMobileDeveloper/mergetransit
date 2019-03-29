@extends("layout.adminLayout")
@section("contents")

<script>
    $(function(){
        $(".back_button").click(function(){
            location.href = "{{URL::to('admin/customers')}}"
        });

      
    });


    function delete_file(id,no){
    	if(confirm("are you sure?")){
    		var url = "{{ url('admin/customer_delete_file') }}";
        	url = url + "/" + id + "/" + no;
        	
        	$.get(
        		url,
        		function(result){
        			if(result=="ok"){
        				$(".file_index_"+no).remove();
        			}
        		}
        	)
    		
    	}
    }
</script>

<div class="content-wrapper" style="min-height: 916px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
           Edit Customers
        </h1>
       
    </section>
   

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <!-- /.box -->
                <div class="box box-info">
                    <!-- form start -->

                   

                    <form class="form-horizontal" id="customer_form" name="customer_form" action="{{url('admin/customers/'.$customer->id )}}" method = "post">

                        {{ csrf_field() }}
                        {{ method_field('PATCH') }}
                        <div class="box-body">
                            @if($status = Session::get("status"))
                                <div class="alert alert-error form-group">
                                    <span class="help-error">
                                        <strong>{{$status}}</strong>
                                    </span>
                                </div><br>
                            @endif
                            <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">Company<span class="required">*</span></label>
                                <div class="col-xs-4">
                                    <input class="form-control" name="company" type="text" value="{{ $customer->company }}" placeholder="Company" required>
                                </div>
                            </div>
                            <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">First Name<span class="required">*</span></label>
                                <div class="col-xs-4">
                                    <input class="form-control" name="firstname" type="text" value="{{ $customer->firstname }}" placeholder="First Name" required>
                                </div>
                            </div>
                            <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">Last Name<span class="required">*</span></label>
                                <div class="col-xs-4">
                                    <input class="form-control" name="lastname" type="text" value="{{ $customer->lastname }}" placeholder="Last Name" required>
                                </div>
                            </div>

                            <span class="help-block">
                                    <strong>{{ $errors->first('status') }}</strong>
                                </span>

                            <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">Email<span class="required">*</span></label>
                                <div class="col-xs-4">
                                    <input class="form-control" name="email" type="email" value="{{ $customer->email }}" placeholder="Email" required>
                                </div>
                            </div>
                             <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">Phone<span class="required">*</span></label>
                                <div class="col-xs-4">
                                    <input class="form-control" name="phone" type="text" value="{{ $customer->phone }}" placeholder="Phone" required>
                                </div>
                            </div>

                            <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">Address<span class="required"></span></label>
                                 <div class="col-xs-1" >
                                    <input class="form-control" name="street" type="text" value="{{ $customer->street }}" placeholder="Street" required>
                                </div>                          
                                 <div class="col-xs-1">
                                    <input class="form-control" name="city" type="text" value="{{ $customer->city }}" placeholder="City" required>
                                </div>
                            
                                 <div class="col-xs-1">
                                    <input class="form-control" name="state" type="text" value="{{$customer->state }}" placeholder="State" required>
                                </div>                          
                             
                                 <div class="col-xs-1">
                                    <input class="form-control" name="zipcode" type="text" value="{{ $customer->zipcode }}" placeholder="ZipCode" required>
                                </div>
                            </div>


                            <!-- attached file -->
                            <div class="form-group "> 
                                    <label class="col-sm-2 control-label">Attached Files</label>                              
                                <div class="col-xs-4 ">                                  
                                        @if($customer->image_path!="")
                                        <p >
                                            <?php 
                                                
                                                $name_arr = explode(",",$customer->image_path);

                                               
                                            ?>
                                            @for($i=0;$i<count($name_arr);$i++)
                                                <?php
                                                $file_name = str_replace("http://mergetransit.com/files/","", $name_arr[$i]);
                                                ?>
                                                <label class="file_label label-success file_index_{{$i}}" >
                                                    {{$file_name}}
                                                    <a onclick="delete_file('{{$customer->id }}','{{$i}}')" data-href="{{url('admin/customer_delete_file/'.$i)}}" class="close_a">x</a>
                                                </label>	
                                            @endfor		                
                                        </p>
                                        @endif
                                </div>
                            </div>

                            <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">Description<span class="required"></span></label>
                                 <div class="col-xs-4">
                                    <input class="form-control" name="desc" type="text" value="{{ $customer->description }}" placeholder="Description" required>
                                </div>
                            </div>
                             <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">Manual Invoice</span></label>
                                 <div class="col-xs-4">
                                    <input name="manual_invoice" type="checkbox" {{($customer->manual_invoice==0)?'':'checked'}} >
                                </div>
                            </div>
                           
                        </div>
                        <div class="box-footer">
                            <!--<button type="submit" class="btn btn-default">Cancel</button>-->
                            <button type="submit" class="btn btn-info save_button">Save</button>
                            <button type="button" class="btn btn-info back_button">Back</button>
                        </div>
                    </form>
                </div>
                <!-- /.col -->
            </div>
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>

@endsection