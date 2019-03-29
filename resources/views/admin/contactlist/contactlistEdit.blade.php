@extends("layout.adminLayout")
@section("contents")

<script>
    $(function(){
        $(".back_button").click(function(){
            location.href = "{{URL::to('admin/contactlist')}}"
        });

      
    });

</script>

<div class="content-wrapper" style="min-height: 916px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
           Edit Customers Contact information
        </h1>
       
    </section>
   

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <!-- /.box -->
                <div class="box box-info">
                    <!-- form start -->

                   

                    <form class="form-horizontal" id="customer_form" name="customer_form" action="{{url('admin/contactlist/'.$contact->id )}}" method = "post">

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
                                    <input class="form-control" name="d_company_name" type="text" value="{{ $contact->d_company_name }}" placeholder="Company" required>
                                </div>
                            </div>
                            <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">Address1<span class="required">*</span></label>
                                <div class="col-xs-4">
                                    <input class="form-control" name="address1" type="text" value="{{ $contact->address1 }}" placeholder="Address1" required>
                                </div>
                            </div>
                            <div class="form-group custom_input">
                                    <label class="col-sm-2 control-label">Address2</label>
                                    <div class="col-xs-4">
                                        <input class="form-control" name="address2" type="text" value="{{ $contact->address2 }}" placeholder="Address2" >
                                    </div>
                                </div>
                            <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">City<span class="required">*</span></label>
                                <div class="col-xs-4">
                                    <input class="form-control" name="city" type="text" value="{{ $contact->city }}" placeholder="City" required>
                                </div>
                            </div>

                            <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">State<span class="required">*</span></label>
                                <div class="col-xs-4">
                                    <input class="form-control" name="state" type="text" value="{{ $contact->state }}" placeholder="State" required>
                                </div>
                            </div>
                             <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">ZipCode<span class="required">*</span></label>
                                <div class="col-xs-4">
                                    <input class="form-control" name="zipcode" type="text" value="{{ $contact->zipcode }}" placeholder="ZipCode" required>
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