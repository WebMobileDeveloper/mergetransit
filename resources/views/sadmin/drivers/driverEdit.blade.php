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
           Edit Drivers
        </h1>
       
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <!-- /.box -->
                <div class="box box-info">
                    
                    <!-- form start -->

                    <form class="form-horizontal" id="user_form" name="user_form" action="{{url('sadmin/drivers/'.$driver[0]->id )}}" method = "post">

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
                                <label class="col-sm-2 control-label">First Name<span class="required">*</span></label>
                                <div class="col-xs-4">
                                    <input class="form-control" name="firstname" type="text" value="{{ $driver[0]->firstname }}" placeholder="First Name" required>
                                </div>
                            </div>
                            <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">Last<span class="required">*</span></label>
                                <div class="col-xs-4">
                                    <input class="form-control" name="lastname" type="text" value="{{ $driver[0]->lastname }}" placeholder="Last Name" required>
                                </div>
                            </div>
                            <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">Email</label>
                                 <div class="col-xs-4">
                                    <input class="form-control" name="email" type="text" value="{{ $driver[0]->email }}" placeholder="Email" >
                                </div>
                            </div>
                            <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">Phone<span class="required"></span></label>
                                 <div class="col-xs-4">
                                    <input class="form-control" name="phone" type="text" value="{{ $driver[0]->phone }}" placeholder="Phone" required>
                                </div>
                            </div>                            
                            <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">Company<span class="required"></span></label>
                                 <div class="col-xs-4">
                                    <select class = "form-control" name = "customer" requried>
                                        @foreach ($customers as $customer)
                                            <option {{($driver[0]->company_id == $customer->id)?"selected":""}} value= {{$customer->id}}>{{$customer->company}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">MC Number</label>
                                 <div class="col-xs-4">
                                    <input class="form-control" name="mc_number" type="text" value="{{$driver[0]->mc_number}}" placeholder="MC Number" >
                                </div>
                            </div>
                            <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">Equipment</label>
                                 <div class="col-xs-4">
                                    <input class="form-control" name="equipment" type="text" value="{{$driver[0]->equipment}}" placeholder="Equipment" >
                                </div>
                            </div>
                            <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">Max Weight(LB)</label>
                                 <div class="col-xs-4">
                                    <input class="form-control" name="max_weight" type="number" step="1"  value="{{$driver[0]->max_weight}}" placeholder="Max Weight" >
                                </div>
                            </div>
                            <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">Truck Number</label>
                                 <div class="col-xs-4">
                                    <input class="form-control" name="truck" type="text" value="{{$driver[0]->truck}}" placeholder="Truck Number" >
                                </div>
                            </div>
                            <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">Trailer Number</label>
                                 <div class="col-xs-4">
                                    <input class="form-control" name="trailer" type="text" value="{{$driver[0]->trailer}}" placeholder="Trailer Number" >
                                </div>
                            </div>
                           
                        </div>
                        <div class="box-footer">
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