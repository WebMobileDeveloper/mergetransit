@extends("layout.adminLayout")
@section("contents")

<script>
   
    $(function(){
        
        $(".back_button").click(function(){
            location.href = "{{URL::to('admin/details')}}"
        });

        $( "input[name=revenue]" ).keyup(function() {
            calculator();
        });
        $( "input[name=mile]" ).keyup(function() {
            calculator();
        });
        $( "input[name=dho]" ).keyup(function() {
            calculator();
        });

        $("#calcu_distance").click(function(e){
            e.preventDefault()
            calcu_miles();
        })

        $("#company").change(function(){
            
            var contact_id = $(this).val();
            if(contact_id != 0) {
                var contact_data = JSON.parse($(this).find(':selected').attr('data-contactlist'));
              
                $("input[name=address1]").val(contact_data.address1)
                $("input[name=address2]").val(contact_data.address2)
                $("input[name=city]").val(contact_data.city)
                $("input[name=state]").val(contact_data.state)
                $("input[name=zipcode]").val(contact_data.zipcode)
            }

        })

        $(".add_company_button").click(function(e){
            e.preventDefault();
            location.href = "{{URL::to('/admin/contactlist/create')}}"
        })

    });

    function em_remove(obj){     
        $(obj).parent().parent().remove();       
    }

    
    function calculator(){
        console.log($( "input[name=revenue]" ).val());
        if( $( "input[name=mile]" ).val()==0){
            $( "input[name=dh_rpm]" ).val(0);
            $( "input[name=rpm]" ).val(0);
        }else{
            var rpm = parseFloat($( "input[name=revenue]" ).val())/parseFloat($( "input[name=mile]" ).val());
            var new_rpm = rpm.toFixed(2);
            $( "input[name=rpm]" ).val(new_rpm);

            var dh_rpm = parseFloat($( "input[name=revenue]" ).val())/(parseFloat($( "input[name=mile]" ).val())+parseFloat($( "input[name=dho]" ).val()));
            var new_dh_rpm = dh_rpm.toFixed(2);
            $( "input[name=dh_rpm]" ).val(new_dh_rpm);
            
        }
    }

    function calcu_miles () {   

        var zip_flag = true;
        var origin_lat = [];
        var origin_lng = [];
        var origin_zip = [];
        var origin_city = [];
        var origin_province=[];
        var destination_lat = [];
        var destination_lng = [];
        var destination_zip = [];
        var destination_city=[];
        var destination_province=[];

        $("div.shipper").each (function(){
            if ($(this).find(".zipcode").val() == '') {
                zip_flag = false;
            }
            origin_lat.push($(this).find(".lat").val())
            origin_lng.push($(this).find(".lng").val())
            origin_zip.push($(this).find(".zipcode").val())
            origin_city.push($(this).find(".origin_city").val())
            origin_province.push($(this).find(".origin_province").val())
        })

        $("div.consignee").each (function(){
            if ($(this).find(".zipcode").val() == '') {
                zip_flag = false;
            }
            destination_lat.push($(this).find(".lat").val())
            destination_lng.push($(this).find(".lng").val())
            destination_zip.push($(this).find(".zipcode").val())
            destination_city.push($(this).find(".destination_city").val())
            destination_province.push($(this).find(".destination_province").val())
            
        })

        if (!zip_flag) {
            alert("Please enter correct zipcode"); return false;
        }

        // get Shipper and Consignee address array
        
        if( $("#driver_id").val()=='' || $("#driver_id").val()==0) {
            alert("Please select Driver.");
            return false;
        }

        $("#calcu_distance").button('loading');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "POST",
            dataType:'json',
            url: "{{ url('admin/get_miles') }}",
            data: {
                detail_id: $("input[name=detail_id]").val(),
                driver_id : $("#driver_id").val(),
                origin_lat: origin_lat,
                origin_lng: origin_lng,
                origin_zip: origin_zip,
                origin_city: origin_city,
                origin_province: origin_province,
                destination_lat: destination_lat,
                destination_lng: destination_lng,
                destination_zip: destination_zip,
                destination_city: destination_city,
                destination_province: destination_province,
            },
            success: function (data) {
              $("#calcu_distance").button('reset');
              if(data.status=='success'){
                console.log(data.total_dist_arr)
                console.log(data.zip_array)
                $("#mile").val(Math.ceil(data.distance));
                $("#dho").val(Math.ceil(data.dho));
                calculator();

              } else {
                  alert("Can not get distance. please check original and destination address again.")
                  return false;
              }
                
            }
        });
    }
     //add Extra amount
     function add_amount (obj) {       
        var html = '<div class="form-group custom_input">'+
                        '<label class="col-sm-2 control-label"></label>'+
                        '<div class="col-xs-4" style="padding-left:0;padding-right:10px">'+
                            '<select class="form-control" name="extra_reason[]">'+
                                '<option value="accessorials">Accessorials</option>' +
                                '<option value="detention">Detention</option>' +
                            '</select>'+
                            //'<input class="form-control" name="extra_reason[]" type="text" value="" placeholder="Reason" >'+
                        '</div>'+
                        '<div class="col-xs-3" style="padding-left:0;padding-right:0px">'+
                            '<input class="form-control e_amount" name="extra_amount[]" type="number" value="" onkeyup="calculate_revenue()" placeholder="Amount" >'+
                        '</div>'+
                        '<div class="col-xs-1" style="padding-left:0;padding-right:0">'+
                            '<a class="btn btn-info remove_amount"  onclick="remove_amount(this)">'+
                                '<i class="fa fa-remove"></i>'+
                            '</a>'+
                        '</div>'+
                    '</div>';
                    
        $(".revenue_area").append(html);
    }

    //remove Extar amount

    function remove_amount (obj) {       
        $(obj).parent().parent().remove();
        calculate_revenue()
    }
  
    function calculate_revenue () {
        var amount = parseFloat($("input[name=amount]").val());
        var extra_amount = 0;
        if($(".e_amount").length > 0) {
            $(".e_amount").each(function(index){
                extra_amount+=parseFloat($(this).val())
            });
        }
        $("input[name=revenue]").val(amount + extra_amount)
        calculator()
    }
</script>
<style>
    .select2-selection.select2-selection--single {
        height: 34px;
    }
    .has-error {
        border: solid 1px red;
    }
</style>

<div class="content-wrapper" style="min-height: 916px;">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Input Dispatch Details
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <!-- /.box -->

                <div class="box box-info">
                    <!-- form start -->

                    <form class="form-horizontal" id="role_form" name="role_form" action="{{url('/admin/details')}}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="hidden" name="detail_id" value="0" />
                        <div class="box-body">
                          
                            @if(count($drivers)>0)
                            <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">Driver Name<span class="required"></span></label>
                                <div class="col-xs-7 input-group">
                                    <select class = "form-control" name = "driver_id" id="driver_id" requried>
                                        <!-- <option value="0">Own Name(No Company)</option> -->
                                        @foreach ($drivers as $driver)
                                            <?php
                                            $emps_arr = explode(",",$driver->employee_id);
     
                                            ?>
                                            @if(in_array(Auth::user()->id,$emps_arr))
                                            <option value= {{$driver->id}}>
                                                @if($driver->company_id == 1)
                                                    {{$driver->firstname}} {{$driver->lastname}}
                                                @else
                                                    {{$driver->company}} ({{$driver->firstname}})
                                                @endif
                                            </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">Broker Company<span class="required">*</span></label>
                                <div class="col-xs-6 input-group" style="float:left">
                                    {{-- <input class="form-control" name="company" type="text" value="" placeholder="Company" required> --}}
                                    <select class="form-control select2"  name="contact_id" id="company" data-placeholder="Select Company" style="width: 100%;" required>
                                        <option value="0">Select Company</option>
                                        @foreach ($contacts as $contact)                                        
                                            <option  value= {{$contact->id}} data-contactlist='<?php echo json_encode($contact)?>'>{{$contact->d_company_name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xs-1 input-group">
                                        <button type="submit" class="btn btn-info add_company_button" style="float:right">Add New</button>
                                </div>
                            </div>
                            <div class="form-group custom_input">                                
                                <label class="col-sm-2 control-label">Shipping Address<span class="required">*</span></label>
                                <div class="col-xs-2"  style="padding-left:0;padding-right:10px">
                                    <input class="form-control" name="address1" type="text" value="" placeholder="Address1"  required>
                                </div>
                                <div class="col-xs-2"  style="padding-left:0;padding-right:10px">
                                    <input class="form-control" name="address2" type="text" value="" placeholder="Address2"  >
                                </div>
                                <div class="col-xs-1"  style="padding-left:0;padding-right:10px">
                                    <input class="form-control" name="city" type="text" value="" placeholder="City" required>
                                </div>
                                <div class="col-xs-1"  style="padding-left:0;padding-right:10px">
                                    <input class="form-control" name="state" type="text" value="" placeholder="State" required>
                                </div>
                                <div class="col-xs-1 "  style="padding-left:0;padding-right:0px">
                                    <input class="form-control" name="zipcode" type="text" value="" placeholder="ZipCode" required>
                                </div>

                            </div>
                            <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">Contact<span class="required"></span></label>
                                 <div class="col-xs-7 input-group">
                                    <input class="form-control" name="contact" type="text" value="" placeholder="Contact" required>
                                </div>
                            </div>
                            <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">Po</label>
                                 <div class="col-xs-7 input-group">
                                    <input class="form-control" name="po" type="text" value="" placeholder="Po" >
                                </div>
                            </div>

                            <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">Load Number</label>
                                    <div class="col-xs-7 input-group">
                                    <input class="form-control" name="load_num" type="text" value="" placeholder="Load Number(Optional)" >
                                </div>
                            </div>

                            <!-- attached file -->
                            <div class="form-group ">
                                <label class="col-sm-2 control-label">Upload File</label>
                                 <div class="col-xs-7 input-group">
                                    <input class="" name="upload_file[]" type="file" value="" placeholder="" multiple>
                                </div>
                            </div>

                            <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">Pu Date<span class="required"></span></label>
                                <div class="col-xs-7 input-group">
                                    <input class="form-control" name="put_date" id="put_date" type="text" value="" placeholder="" >
                                </div>
                            </div>
                            <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">Del Date<span class="required"></span></label>
                                <div class="col-xs-7 input-group">
                                    <input class="form-control" name="del_date" id="del_date" type="text" value="" placeholder="" >
                                </div>
                            </div>
                            <div class="form-group custom_input" style="margin-bottom:10px">
                                <div class="col-sm-2 " style="padding-left:0;padding-right:10px">
                                    
                                </div>
                                <div class="col-xs-1 " style="padding-left:0;padding-right:10px">
                                    <a id="add_shipper" class="btn btn-info">
                                         <i class="fa fa-plus"></i>Add Shipper
                                    </a>
                                </div>
                            </div>
                            <div class="shipper_area">
                                <div class="form-group custom_input shipper" >
                                    <input type="hidden" name="origin_detail_addr_id[]" value="0" />
                                    <input type="hidden" name="origin_order_index[]" />
                                    <input type="hidden" class="lat" name="origin_lat[]" value=""/>
                                    <input type="hidden" class="lng" name="origin_lng[]" value=""/>
                                    <label class="col-sm-2 control-label">Shipper Name</label>
                                    <div class="col-xs-1 " style="padding-left:0;padding-right:10px">
                                        <input class="form-control" name="shipper[]" type="text" value="" placeholder="Shipper Name" required>
                                    </div>
                                    <label class="col-sm-1 control-label">Origin</label>
                                    
                                    <div class="col-xs-1 " style="padding-left:0;padding-right:10px">
                                        <input class="form-control zipcode postal_code" name="origin_zipcode[]" type="text" value="" placeholder="ZipCode" minlength="5" maxlength="5" pattern="[0-9]*" 
                                        onchange="fillAddressByZip($(this))" autocomplete="off" required/>
                                    </div>
                                    <div class="col-xs-2 " style="padding-left:0;padding-right:10px">
                                        <input class="form-control " name="origin_street[]" type="text" value="" placeholder="Street"
                                        onblur="getFullLocation($(this))" autocomplete="off" required/>
                                    </div>
                                    <div class="col-xs-1 " style="padding-left:0;padding-right:10px">
                                        <input class="form-control origin_city locality neighborhood" name="origin_city[]" type="text" value="" placeholder="City" required />
                                    </div>
                                    <div class="col-xs-1 " style="padding-left:10px;padding-right:0">
                                        <input class="form-control origin_province administrative_area_level_1" name="origin_province[]" type="text" value="" placeholder="State" required />
                                    </div>
                                   
                                </div>
                               
                            </div>
                            <div class="form-group custom_input" style="margin-bottom:10px">
                                <div class="col-sm-2 " style="padding-left:0;padding-right:10px">
                                    
                                </div>
                                <div class="col-xs-1 " style="padding-left:0;padding-right:10px">
                                    <a id="add_consignee" class="btn btn-info">
                                         <i class="fa fa-plus"></i>Add Consignee
                                    </a>
                                </div>
                            </div>
                            <div class="consignee_area">
                                <div class="form-group custom_input consignee">
                                    <input type="hidden" name="dest_detail_addr_id[]" value="0" />
                                    <input type="hidden" name="dest_order_index[]" />
                                    <input type="hidden" class="lat" name="destination_lat[]" value=""/>
                                    <input type="hidden" class="lng" name="destination_lng[]" value=""/>
                                    <label class="col-sm-2 control-label">Consignee Name</label>
                                
                                    <div class="col-xs-1 " style="padding-left:0;padding-right:10px">
                                        <input class="form-control" name="consignee[]" type="text" value="" placeholder="Consignee Name" required>
                                    </div>
                                    <label class="col-sm-1 control-label">Destination</label>
                                   
                                    <div class="col-xs-1 " style="padding-left:0;padding-right:10px">
                                        <input class="form-control zipcode postal_code" name="destination_zipcode[]" type="text" value="" placeholder="ZipCode" minlength="5" maxlength="5" pattern="[0-9]*" 
                                        onchange="fillAddressByZip($(this))" autocomplete="off" required/>
                                    </div>
                                    <div class="col-xs-2 " style="padding-left:0;padding-right:10px">
                                        <input class="form-control " name="destination_street[]" type="text" value="" placeholder="Street"
                                        onblur="getFullLocation($(this))" autocomplete="off" required/>
                                    </div>
                                    <div class="col-xs-1 " style="padding-left:0;padding-right:10px">
                                        <input class="form-control destination_city locality neighborhood" name="destination_city[]" type="text" value="" placeholder="City" required />
                                    </div>
                                    <div class="col-xs-1 " style="padding-left:10px;padding-right:0">
                                        <input class="form-control destination_province administrative_area_level_1" name="destination_province[]" type="text" value="" placeholder="State" required />
                                    </div>
                                </div>
                            </div>  
                            <div class="text-error"></div>                
                            <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">Weight(LB)</label>
                                 <div class="col-xs-7 input-group">
                                    <input class="form-control" name="weight" type="number" value="0" placeholder="Weight" >
                                </div>
                            </div>
                            <div class="revenue_area">
                                <div class="form-group custom_input">
                                    <label class="col-sm-2 control-label">Revenue</label>
                                    <div class="col-xs-7" style="padding-left:0;padding-right:0">
                                        <input class="form-control " name="amount" type="number" value="" onkeyup="calculate_revenue()" placeholder="Revenue" >
                                    </div>
                                    <div class="col-xs-1" style="padding-left:0;padding-right:0">
                                        <a class="btn btn-info add_amount"  onclick="add_amount(this)">
                                            <i class="fa fa-plus"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!--Total Revenue-->
                            <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">Total Revenue</label>
                                <div class="col-xs-7" style="padding-left:0;padding-right:0">
                                    <input class="form-control" name="revenue" type="number" value="0" readonly >
                                </div>                               
                            </div>
                            <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">Miles & DHO</label>
                                <div class="col-xs-1 " style="padding-left:0;padding-right:10px">
                                    <input class="form-control" name="mile" type="number" id="mile"  step="0.01" value="" placeholder="Miles" required>                                   
                                </div> 
                                <div class="col-xs-1" style="padding-left:0;padding-right:10px">
                                    <input class="form-control" name="dho" id="dho" type="number" step="0.01" value="0" placeholder="DH-O" >
                                </div>
                                <a class="btn btn-info" id="calcu_distance" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Calculating...">Calculate Distance</a>
                            </div>
                           
                            <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">RPM</label>
                                 <div class="col-xs-7 input-group">
                                    <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                                    <input class="form-control" name="rpm" type="number" step="0.01" value="0" readonly placeholder="RPM" >
                                </div>
                            </div>
                            <div class="form-group custom_input ">
                                <label class="col-sm-2 control-label">DH RPM</label>
                                 <div class="col-xs-7 input-group">
                                    <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                                    <input class="form-control" name="dh_rpm" type="number" step="0.01" value="0" readonly placeholder="DH RPM" >
                                </div>
                            </div>
                            @else
                            <div>No Assigned Drivers</div>
                            @endif
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