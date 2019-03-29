@extends("layout.customerLayout")
@section("contents")

<script>
    $(function(){
      
        $("#startdate").datepicker({ format: 'yyyy-mm-dd',autoclose: true });
        $("#enddate").datepicker({ format: 'yyyy-mm-dd',autoclose: true });

    })   
       
    function create_profit_report() {
       
    }
</script>


<div class="content-wrapper" style="min-height: 916px;">
<meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Profit/loss Reports Generate
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
       
        <div class="row">
            <div class="col-xs-12">      
                <div class="box account_list">
                    <div class="box-header with-border">
                        <form class="form-horizontal" id="user_form" name="user_form" method = "post">

                        {{ csrf_field() }}
                            <div class="row " style="margin-bottom:15px;">                               
                                <div class="col-xs-2">
                                </div>
                                <div class="col-xs-2">
                                </div>
                                <div class="col-xs-2">
                                </div>
                                <div class="col-xs-2">
                                    <input type="text" name="startdate" value = "{{(!empty($date_range))?$date_range['start_date']:""}}" class="form-control" autocomplete="off" placeholder="Date" id="startdate">
                                </div>
                                <div class="col-xs-2">
                                    <input type="text" name="enddate" value = "{{(!empty($date_range))?$date_range['end_date']:""}}" class="form-control" autocomplete="off" placeholder="Date" id="enddate">
                                </div>
                               
                                <div class="col-xs-2">
                                    <button type="button" class="btn btn-info pull-right">Generate</a>
                                </div>
                            </div>
                            
                        </form>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                           
                            <div class="row">
                                <div class="col-sm-12">
                                    <h2>Income</h2>
                                    <table id="reporttable" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                                        <thead>
                                            <tr role="row">
                                                <th style="width: 10%;">Company</th>                                            
                                                <th style="width: 10%;">Driver</th>                                             
                                                <th style="width: 6%;">Contact</th>
                                                <th style="width: 6%;">Po</th>
                                                <th style="width: 8%;">Pu Date</th>
                                                <th style="width: 8%;">Del Date</th>
                                                <th style="width: 8%;">Origin</th>
                                                <th style="width: 8%;">Destination</th>
                                                <th style="width: 6%;">Weight</th>
                                                <th style="width: 6%;">Revenue</th>
                                                <th style="width: 6%;">Miles</th>
                                                <th style="width: 6%;">DH-O</th>
                                                <th style="width: 6%;">RPM</th>
                                                <th style="width: 5%;">DH RPM</th>
                                                <th style="width: 5%;">Invoice Created</th>
                                                <th style="width: 5%;">Attach</th>
                                                <th style="width: 5%;">Edit</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                       
                                        </tbody>
                                    </table>

                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="dataTables_info" id="dataTables-example_info" role="alert" aria-live="polite" aria-relevant="all"></div>

                                        </div>
                                         <div class="col-sm-9 ">
                                            <div  id="dataTables-example_paginate" style="float:right">
                                                
                                            </div>
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
