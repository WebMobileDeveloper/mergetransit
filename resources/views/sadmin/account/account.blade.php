@extends("layout.customerLayout")
@section("contents")

<script>
    $(function(){
        $(".back_button").click(function(){
            location.href = "{{URL::to('sadmin/drivers')}}"
        });
        //Initialize Select2 Elements
        $(".select2").select2();
    });
</script>

<div class="content-wrapper" style="min-height: 916px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
           My Account
        </h1>
       
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <!-- /.box -->
                <div class="box box-info">
                    
                    <!-- form start -->
                    
                    <form class="form-horizontal" id="user_form" name="user_form" action="{{url('sadmin/account' )}}" method = "post">

                        {{ csrf_field() }}
                       
                        <div class="box-body">
                            @if($status = Session::get("status"))
                                <div class="alert alert-error form-group">
                                    <span class="help-error">
                                        <strong>{{$status}}</strong>
                                    </span>
                                </div><br>
                            @endif
                            <input name="customerID" type="hidden" value="{{ $accont['customerID'] }}">
                            <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">First Name<span class="required">*</span></label>
                                <div class="col-xs-4">
                                    <input class="form-control" name="firstname" type="text" value="{{ $accont['firstname'] }}" placeholder="First Name" required>
                                </div>
                            </div>
                            <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">Last<span class="required">*</span></label>
                                <div class="col-xs-4">
                                    <input class="form-control" name="lastname" type="text" value="{{ $accont['lastname'] }}" placeholder="Last Name" required>
                                </div>
                            </div>
                            <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">Email</label>
                                 <div class="col-xs-4">
                                    <input class="form-control" name="email" type="text" value="{{ $accont['email'] }}" placeholder="Email" disabled>
                                </div>
                            </div>
                            <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">Phone<span class="required"></span></label>
                                 <div class="col-xs-4">
                                    <input class="form-control" name="phone" type="text" value="{{ $accont['phone'] }}" placeholder="Phone" required>
                                </div>
                            </div>                            
                            
                            <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">Company Name</label>
                                 <div class="col-xs-4">
                                    <input class="form-control" name="company" type="text" value="{{ $accont['company'] }}" placeholder="Company" required>
                                </div>
                            </div>
                            <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">Address</label>
                                 <div class="col-xs-4">
                                    <input class="form-control" name="street" type="text" value="{{ $accont['street'] }}" placeholder="Address" required>
                                </div>
                            </div>
                            <div class="form-group custom_input">
                                <label class="col-sm-2 control-label"> </label>
                                 <div class="col-xs-2">
                                    <input class="form-control" name="city" type="text"  value="{{ $accont['city'] }}" placeholder="City" required>
                                </div>
                                <div class="col-xs-1">
                                    <input class="form-control" name="state" type="text" value="{{ $accont['state'] }}" placeholder="State" required>
                                </div>
                                <div class="col-xs-1">
                                    <input class="form-control" name="zipcode" type="text" value="{{ $accont['zipcode'] }}" placeholder="Zip Code" required>
                                </div>
                            </div>
                           
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-info save_button">Update</button>
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