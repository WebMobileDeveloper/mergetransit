@extends("layout.customerLayout")
@section("contents")

<script>
$(function(){
    var cur_date = new Date();
    $(".back_button").click(function(){
        location.href = "{{URL::to('sadmin/fixedcost')}}"
    });

    $(".beforemonth").click(function(e){
        e.preventDefault();
        var seldate_arr = $("#sel_date").val().split("-");
        if (parseInt(seldate_arr[1]) == 1 ) {
            return false;
        } else {
            var new_month = parseInt(seldate_arr[1]) - 1;
            new_month = new_month > 9 ? new_month : "0" + new_month;
            var new_date = cur_date.getFullYear() + '-' + new_month
            select_month(new_date)
        }
    })

    $(".aftermonth").click(function(e){
        e.preventDefault();
        var seldate_arr = $("#sel_date").val().split("-");
        if (parseInt(seldate_arr[1]) == cur_date.getMonth()+ 1 ) {
            return false;
        } else {
            var new_month = parseInt(seldate_arr[1]) + 1;
            new_month = new_month > 9 ? new_month : "0" + new_month;
            var new_date = cur_date.getFullYear() + '-' + new_month
            select_month(new_date)
        }
    })
    //Initialize Select2 Elements
    $(".select2").select2();

    // $("#sel_date").datepicker({ format: 'yyyy-mm',autoclose: true });

    $("input.input_val").each(function(){
        $(this).keyup(function(){
            var value= $(this).val();  
            $(this).val(value.replace(/[^\d.-]/g,''))

            calculate_total();
        })
    })
});

function calculate_total() {   
    var total = 0;
    $("input.input_val").each(function(){
        if($(this).val()==''){
            var value = 0;
        } else {
            var value = parseFloat($(this).val())
        }
        total+=value;
    })

    $("input[name=CostSum").val(total)
  
}

function select_month(new_date) {
    var url = "{{ url('sadmin/fixedcost') }}";
    location.href=url + "/" + new_date ;
    
}

</script>
<div class="content-wrapper" style="min-height: 916px;">
<meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Fixed Cost
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <!-- /.box -->
                <div class="box box-info">
                    
                    <!-- form start -->

                    <form class="form-horizontal" id="fixecost_form" name="fixecost_form" action="{{url('sadmin/fixedcost')}}" method = "post">
                        {{ csrf_field() }}
                        <!-- {{ method_field('PATCH') }} -->
                        <div class="box-body">
                            @if($error = Session::get("error"))
                                <div class="alert alert-info form-group">
                                    <span class="help-error">
                                        <strong>{{$error}}</strong>
                                    </span>
                                </div><br>
                            @endif

                            @if($success = Session::get("success"))
                                <div class="alert alert-success form-group">
                                    <span class="help-error">
                                        <strong>{{$success}}</strong>
                                    </span>
                                </div><br>
                            @endif

                            <input type="hidden" name="fixed_id" value="{{ (!empty($fixedcost[0]))?$fixedcost[0]->id:0 }}" />
                            <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">Date<span class="required">*</span></label>
                                <div class="col-xs-1"> <button class="btn btn-info beforemonth">Before</button> </div>
                                <div class="col-xs-2">                                  
                                   <input id="sel_date" class="form-control" name="sel_date" type="text" value="{{$seldate}}" placeholder="" autocomplete="off" readonly required>                                  
                                  
                                </div>
                                <div class="col-xs-1"> <button class="btn btn-info aftermonth">After</button>  </div>
                            </div>
                            <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">Truck</label>
                                <div class="col-xs-4">
                                    <input class="form-control input_val" name="truck" type="text" value="{{ (!empty($fixedcost[0]))?$fixedcost[0]->truck_payment:0 }}" 
                                    placeholder="" required>
                                </div>
                            </div>
                            <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">Trailer</label>
                                <div class="col-xs-4">
                                    <input class="form-control input_val" name="trailer" type="text" value="{{ (!empty($fixedcost[0]))?$fixedcost[0]->trailer_payment:0 }}" placeholder="" required>
                                </div>
                            </div>
                            <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">Insurance</label>
                                 <div class="col-xs-4">
                                    <input class="form-control input_val" name="insurance" type="text" value="{{ (!empty($fixedcost[0]))?$fixedcost[0]->insurance:0 }}"  placeholder="" >
                                </div>
                            </div>
                            <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">Communication<span class="required"></span></label>
                                 <div class="col-xs-4">
                                    <input class="form-control input_val" name="communication" type="text" value="{{ (!empty($fixedcost[0]))?$fixedcost[0]->communication:0 }}"  placeholder="" required>
                                </div>
                            </div>                            
                           
                            <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">Office</label>
                                 <div class="col-xs-4">
                                    <input class="form-control input_val" name="office" type="text" value="{{(!empty($fixedcost[0]))?$fixedcost[0]->office:0}}" placeholder="" >
                                </div>
                            </div>

                            <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">Payroll</label>
                                 <div class="col-xs-4">
                                    <input class="form-control input_val" name="payroll" type="text" value="{{(!empty($fixedcost[0]))?$fixedcost[0]->payroll:0}}" placeholder="" >
                                </div>
                            </div>
                            
                            <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">Total Cost</label>
                                 <div class="col-xs-4">
                                    <input class="form-control" name="CostSum" type="text" value="{{(!empty($fixedcost[0]))?$fixedcost[0]->total:0}}" placeholder="" readonly>
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
<style>
    .form-group.custom_input{
        margin-right: 20px
    }
    .beforemonth , .aftermonth {
        width:100%;
    }
</style>
@endsection
