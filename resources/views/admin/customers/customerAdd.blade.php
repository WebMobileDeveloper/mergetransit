@extends("layout.adminLayout")
@section("contents")

<script>
    $(function(){
        $(".back_button").click(function(){
            location.href = "{{URL::to('admin/customers')}}"
        });
    });
</script>

<div class="content-wrapper" style="min-height: 916px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Add Customers
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <!-- /.box -->

                <div class="box box-info">
                    <!-- form start -->

                    <form class="form-horizontal" id="role_form" name="role_form" action="{{url('/admin/customers')}}" method="post">
                        {{ csrf_field() }}
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
                                    <input class="form-control" name="company" type="text" value="{{ old('company') }}" placeholder="Company" required>
                                </div>
                            </div>
                            <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">First Name<span class="required"></span></label>
                                 <div class="col-xs-4">
                                    <input class="form-control" name="firstname" type="text" value="{{ old('firstname') }}" placeholder="First Name" required>
                                </div>
                            </div>
                            <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">Last Name<span class="required"></span></label>
                                 <div class="col-xs-4">
                                    <input class="form-control" name="lastname" type="text" value="{{ old('lastname') }}" placeholder="Last Name" required>
                                </div>
                            </div>
                            <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">Phone<span class="required"></span></label>
                                 <div class="col-xs-4">
                                    <input class="form-control" name="phone" type="text" value="{{ old('phone') }}" placeholder="Phone" required>
                                </div>
                            </div>
                            <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">Email<span class="required"></span></label>
                                 <div class="col-xs-4">
                                    <input class="form-control" name="email" type="email" value="{{ old('email') }}" placeholder="Email" required>
                                </div>
                            </div>

                            <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">Address<span class="required"></span></label>
                                <div class="col-xs-1">
                                    <input class="form-control" id="address" name="street" type="text" value="{{ old('street') }}" placeholder="Street" required>
                                    
                                </div>                          
                                 <div class="col-xs-1">
                                    <input class="form-control" name="city" type="text" required value="{{ old('city') }}" placeholder="City" >
                                </div>
                            
                                 <div class="col-xs-1">
                                    <input class="form-control" name="state" type="text" required value="{{ old('state') }}" placeholder="State" >
                                </div>                          
                             
                                 <div class="col-xs-1">
                                    <input class="form-control" name="zipcode" type="text" required value="{{ old('zipcode') }}" placeholder="ZipCode" >
                                </div>
                            </div>


                            <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">Description<span class="required"></span></label>
                                 <div class="col-xs-4">
                                    <input class="form-control" name="desc" type="text" value="{{ old('desc') }}" placeholder="Description" required>
                                </div>
                            </div>
                            <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">Manual Invoice</span></label>
                                 <div class="col-xs-4">
                                    <input name="manual_invoice" type="checkbox" >
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