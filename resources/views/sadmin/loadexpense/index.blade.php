@extends("layout.customerLayout")
@section("contents")

<script>
$(function(){
    var cur_date = new Date();
   
    $("#sel_date").datepicker({ 
        format: 'yyyy-mm-dd',
        autoclose: true
    })
    // .on('changeDate', select_month);;

   
    //Initialize Select2 Elements
    $(".select2").select2();
    $(".select2").on("change", function(e) {
        var url = "{{ url('sadmin/loadexpense') }}";
        location.href=url + "/" + $(this).val() ;
    })



});

// function select_month() {
//     var new_date = $("#sel_date").val(); 
//     var url = "{{ url('sadmin/loadexpense') }}";
//     location.href=url + "/" + new_date ;
    
// }

function add_item(key) {
    var html = '<div class="form-group custom_input">'+        
                    '<label class="col-sm-2 control-label"></label>'+
                    '<div class="col-xs-4">'+
                        '<input class="form-control" name="'+key+'[]" type="text" value="0" placeholder="" >'+
                    '</div>'+
                    '<div class="col-xs-1">'+
                        '<a class="btn btn-danger" onclick="remove_item($(this))"> x </a>'+
                    '</div>'+
                '</div>';
    $("." + key + "_section").append(html);
}

function remove_item(obj) {
    obj.parent().parent().remove()
}
</script>

<style>
    .select2-selection.select2-selection--single {
        height: 34px;
    }
</style>
<div class="content-wrapper" style="min-height: 916px;">
<meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Load Expense
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <!-- /.box -->
                <div class="box box-info">
                    
                    <!-- form start -->

                    <form class="form-horizontal" id="expense_form" name="expense_form" action="{{url('sadmin/loadexpense')}}" method = "post">
                        {{ csrf_field() }}
                        <!-- {{ method_field('PATCH') }} -->
                        <div class="box-body">
                            @if($status = Session::get("status"))
                                <div class="alert alert-error form-group">
                                    <span class="help-error">
                                        <strong>{{$status}}</strong>
                                    </span>
                                </div><br>
                            @endif

                            <input type="hidden" name="expense_id" value="{{ (!empty($load_expense[0]))?$load_expense[0]->id:0 }}" />
                            <input type="hidden" name="detail_id" value="{{ $detail_id }}" />
                            <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">PO#</label>
                                <div class="col-xs-4">

                               
                                <select class = "form-control select2" name = "detail_id" requried>
                                    <option value= "0"></option>
                                       @foreach ($details as $detail)                                           
                                               <option value= {{$detail->id}} {{$detail_id==$detail->id?'selected':''}}>
                                                       {{$detail->po}}, {{$detail->d_company_name}}, {{$detail->put_date}}                                               
                                               </option>
                                          
                                       @endforeach
                                </select>
                                </div>

                            </div>
                            <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">Date<span class="required">*</span></label>
                               
                                <div class="col-xs-4">                                  
                                   <input id="sel_date" class="form-control" name="sel_date" type="text" value="{{(!empty($load_expense[0]))?$load_expense[0]->date:date('Y-m-d')}}" placeholder="" autocomplete="off"  required>                                  
                                </div>
                                
                            </div>
                            
                            <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">Fuel</label>
                                 <div class="col-xs-4">
                                    <input class="form-control" name="fuel" type="text" value="{{ (!empty($load_expense[0]))?$load_expense[0]->fuel:'0' }}" placeholder="" >
                                </div>
                            </div>
                            <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">Gallons<span class="required"></span></label>
                                 <div class="col-xs-4">
                                    <input class="form-control" name="gallons" type="text" value="{{ (!empty($load_expense[0]))?$load_expense[0]->gallons:'0' }}" placeholder="" required>
                                </div>
                            </div>                            
                           
                            <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">DEF</label>
                                 <div class="col-xs-4">
                                    <input class="form-control" name="def" type="text" value="{{(!empty($load_expense[0]))?$load_expense[0]->def:'0'}}" placeholder="" >
                                </div>
                            </div>
                            
                            <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">Parking</label>
                                 <div class="col-xs-4">
                                    <input class="form-control" name="parking" type="text" value="{{(!empty($load_expense[0]))?$load_expense[0]->parking:'0'}}" placeholder="" >
                                </div>
                            </div>

                            <div class="form-group custom_input">
                                <label class="col-sm-2 control-label">Payroll</label>
                                 <div class="col-xs-4">
                                    <input class="form-control" name="payroll" type="text" value="{{(!empty($load_expense[0]))?$load_expense[0]->payroll:'0'}}" placeholder="" >
                                </div>
                            </div>
                            <?php
                                if(!empty($load_expense[0])) {
                                    $tolls = json_decode($load_expense[0]->tolls_txt);
                                    $lumpers = json_decode($load_expense[0]->lumper_txt);
                                    $accomerdations = json_decode($load_expense[0]->accom_txt);
                                    $others = json_decode($load_expense[0]->other_txt);
                                } else {
                                    $tolls = [0];
                                    $lumpers = [0];
                                    $accomerdations = [0];
                                    $others = [0];
                                }
                                
                            ?>
                            <div class="tolls_section">      
                                <?php 
                                $n = 0;
                                foreach ($tolls as $toll) { ?>
                                    <div class="form-group custom_input">                                    
                                        <label class="col-sm-2 control-label">{{$n==0 ?'Tolls':''}}</label>
                                        <div class="col-xs-4">
                                            <input class="form-control" name="tolls[]" type="text" value="{{ $toll}}" placeholder="" >
                                        </div>
                                        <?php if ($n==0) {?>
                                            <div class="col-xs-1">
                                                <a class="btn btn-info add_toll" onclick="add_item('tolls')"> + </a>
                                            </div>
                                        <?php } else {?>
                                            <div class="col-xs-1">
                                                <a class="btn btn-danger" onclick="remove_item($(this))"> x </a>
                                            </div>
                                        <?php } ?>
                                    </div>
                                <?php  $n++; } ?>
                            </div>
                            <div class="lumper_section">                
                                <?php 
                                $n = 0;
                                foreach ($lumpers as $lumper) { ?>
                                
                                    <div class="form-group custom_input">                                    
                                        <label class="col-sm-2 control-label">{{$n==0 ?'Lumper':''}}</label>
                                        <div class="col-xs-4">
                                            <input class="form-control" name="lumper[]" type="text" value="{{ $lumper}}" placeholder="" >
                                        </div>
                                        <?php if ($n==0) {?>
                                            <div class="col-xs-1">
                                                <a class="btn btn-info add_lumper" onclick="add_item('lumper')"> + </a>
                                            </div>
                                        <?php } else {?>
                                            <div class="col-xs-1">
                                                <a class="btn btn-danger" onclick="remove_item($(this))"> x </a>
                                            </div>
                                        <?php } ?>
                                    </div>
                                <?php  $n++; } ?>
                            </div>

                            <div class="accomerdations_section">          
                                <?php 
                                $n = 0;
                                foreach ($accomerdations as $accomerdation) { ?>
                                    <div class="form-group custom_input">                                    
                                        <label class="col-sm-2 control-label">{{$n==0 ?'Hotel':''}}</label>
                                        <div class="col-xs-4">
                                            <input class="form-control" name="accomerdations[]" type="text" value="{{ $accomerdation}}" placeholder="" >
                                        </div>
                                        <?php if ($n==0) {?>
                                            <div class="col-xs-1">
                                                <a class="btn btn-info add_accom" onclick="add_item('accomerdations')"> + </a>
                                            </div>
                                        <?php } else {?>
                                            <div class="col-xs-1">
                                                <a class="btn btn-danger" onclick="remove_item($(this))"> x </a>
                                            </div>
                                        <?php } ?>
                                    </div>
                            <?php  $n++; } ?>
                            </div>
                            <div class="other_section">          
                                <?php 
                                $n = 0;
                                foreach ($others as $other) { ?>
                                    <div class="form-group custom_input">                                    
                                        <label class="col-sm-2 control-label">{{$n==0 ?'Other':''}}</label>
                                        <div class="col-xs-4">
                                            <input class="form-control" name="other[]" type="text" value="{{ $other}}" placeholder="" >
                                        </div>
                                        <?php if ($n==0) {?>
                                            <div class="col-xs-1">
                                                <a class="btn btn-info add_other" onclick="add_item('other')"> + </a>
                                            </div>
                                        <?php } else {?>
                                            <div class="col-xs-1">
                                                <a class="btn btn-danger" onclick="remove_item($(this))"> x </a>
                                            </div>
                                        <?php } ?>
                                    </div>
                                <?php  $n++; } ?>
                            </div>
                            
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-info save_button">{{ (empty($load_expense[0]))?'Add Expense':'Update Expense' }}</button>
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
