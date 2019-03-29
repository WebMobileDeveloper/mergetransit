@extends('layout.layout')

@section('contents')

<!-- -->
<link rel="stylesheet" href="{{asset('assets/css/layout-datatables.css" rel="stylesheet" type="text/css')}}" />
{{-- <link rel="stylesheet" href="{{asset('assets\admin\plugins\datatables\dataTables.bootstrap.css')}}"> --}}
<link rel="stylesheet" href="{{asset('assets\admin\plugins\datatables\jquery.dataTables.css')}}">
<link rel="stylesheet" href="{{asset('assets\admin\plugins\datatables\extensions\Responsive\css\dataTables.responsive.css')}}">

<!-- /HERO -->           
        
<style>
    .div_hide{
        display:none;
    }
        
    .search_button{
        position: absolute;top: 0;right: 15px;    z-index: 10000;display:none;
    }
    .generate{
            margin-top: 10px;
            float: right;
    }
    @media only screen and (max-width: 992px) {
        
        .search_button{
            display:block;
        }
    }
</style>
<!-- HERO -->



<section class="hero">
    <div class="container-fluid">	
        <div class="row">
            <div class="background"></div>
        </div>
    </div>
</section>	

<section  class="report section1 ">
    <div class="container">
        <div class="row" style="margin-top:15px;">
        <h3 class="title">Report Data</h3>
        <button type="button" class="btn btn-success search_button" >search</button>
        <div class="row search_div section_area">
            <form action="{{url('report/')}}" id="search_form" method= "post">
            {{ csrf_field() }}
                
                <div class="form-group row">
                    <div class="col-md-2 " >
                        <select class = "form-control" name = "driver_id" requried>
                            <?php // var_dump($res_data,"DDD");exit; ?>
                            @if(!empty($drivers))
                            
                            @foreach ($drivers as $driver)
                                <?php
                                    $emps_arr = explode(",",$driver->employee_id);
                                     
                                ?>
                                @if(Auth::user()->role==3)
                                    @if(in_array(Auth::user()->id,$emps_arr))                                                
                                        <option value= {{$driver->id}} {{(!empty($res_data) && $driver->id == $res_data['driver_id'])?"selected":""}}>
                                            @if($driver->company_id == 1)
                                                {{$driver->firstname}} {{$driver->lastname}}
                                            @else
                                                {{$driver->company}} ({{$driver->firstname}})
                                            @endif
                                        </option>
                                    @endif
                                @else
                                    <option value= {{$driver->id}}  {{(!empty($res_data) && $driver->id == $res_data['driver_id'])?"selected":""}}>
                                        @if($driver->company_id == 1)
                                            {{$driver->firstname}} {{$driver->lastname}}
                                        @else
                                            {{$driver->company}} ({{$driver->firstname}})
                                        @endif
                                    </option>
                                @endif
                            @endforeach
                            @endif
                        </select>        
                    </div>
                    <div class="col-md-2 ">
                        <input name="company" value="" class="form-control required" type="text" placeholder="Company">
                    </div>
                    <div class="col-md-2 ">
                        <input name="origin" value="" class="form-control required" type="text" placeholder="Origin">
                    </div>
                    <div class="col-md-2 ">
                        <input name="destination" value="" class="form-control required" type="text" placeholder="Destination">
                    </div>
                    <div class="col-md-2">
                        <input name="startdate" value="" class="form-control datepicker" autocomplete="off" date-format="yyyy-mm-dd" type="text" placeholder="From" id="startdate">
                    </div>
                    <div class="col-md-2 ">
                        <input name="enddate" value="" class="form-control autocomplete="off" datepicker" date-format="yyyy-mm-dd" type="text" placeholder="To" id="enddate">
                    </div>
                  
                </div>
                <div class="form-group ">
                <button type="submit" class="btn btn-success generate">Generate</button>     
                </div>
            </form>
                               
        </div>
        
        @if(!empty($infor))

        <div class="row margin-top-20 section_area">
            <div class="col-md-3" style="color:#db5151">
                <label for="">{{$infor['driver_name']}}</label>
                <label for="">{{$infor['from']}} ~ {{$infor['to']}}</label>
            </div>
            <div class="col-md-9">
                <div class="table-responsive">
                    <table class="table table-condensed table-vertical-middle" >
                        <thead>
                            <tr class="info">
                                <th> Total Revenue</th>
                                <th> Total Miles</th>
                                <th> Total DHO</th>
                                <th> Total RPM</th>
                                <th> Total DH RPM</th> 
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="success">
                                <td>{{$infor['total_revenue']}}</td>
                                <td>{{$infor['total_mile']}}</td>
                                <td>{{$infor['total_dho']}}</td>
                                <td>{{$infor['total_rpm']}}</td>
                                <td>{{$infor['total_dhrpm']}}</td>
                            </tr>
                            
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- <div class="col-md-2 text-right"> -->
                <!-- <div class="col-md-2  text-right">
                                  
                </div> -->
                <!-- <a href="#" class="btn btn-success btn-featured btn-inverse" style="width: auto;">
                    <i class="fa-file-pdf-o"></i>
                </a>
                <a href="#" class="btn btn-default btn-featured btn-inverse" style="width: auto;">
                    <i class="fa-file-excel-o"></i>
                </a> -->
                <!-- <button type="button" class="btn btn-default"><i class="fa fa-file-pdf-o"></i>PDF</button>
                <button type="button" class="btn btn-default"><i class="fa fa-file-excel-o"></i>EXCEL</button> -->
            <!-- </div> -->
        </div>
        @endif
        <div class="col-md-12 margin-top-10 margin-bottom-80">
            <div class="">
                <table class="table table-striped table-bordered table-hover dataTable no-footer" id="sample_2" style="font-size:14px !important;    width: 100%;">
                    <thead>
                        <tr>
                        <!-- class="hidden-xs" -->
                            <th>Company</th>
                            <th>Contact</th>
                            <th>Po</th>
                            <th>Pu Date</th>
                            <th>Del Date</th>
                            <th>Origin</th>
                            <th>Destination</th>
                            <th>Weight</th>
                            <th>Revenue</th>
                            <th>Miles</th>
                            <th>DHO</th>
                            <th>RPM</th>
                            <th>DH RPM</th>
                            <th>PDF</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(!empty($infor))
                        <?php
                            $total_revenue = 0;
                            $total_mile = 0;
                            $total_dho = 0;
                        ?>
                        @foreach ($reports as $tr)
                            <?php

                                $total_revenue = $total_revenue + $tr->revenue;
                                $total_mile = $total_mile + $tr->mile;
                                $total_dho = $total_dho + $tr->dho;

                            ?>
                            <tr class="gradeA odd" item_id="<?php echo $tr->id ?>">
                                
                                <td class=" ">{{$tr->company }}</td>
                                <td class=" ">{{$tr->contact}}</td>
                                <td class=" ">{{$tr->po }}</td>
                                <td class=" ">{{$tr->put_date }}</td>
                                <td class=" ">{{$tr->del_date }}</td>
                                <td class=" ">{{$tr->origin }}</td>
                                <td class=" ">{{$tr->destination }}</td>
                                <td class=" ">{{$tr->weight }}</td>
                                <td class=" ">{{$tr->revenue }}</td>
                                <td class=" ">{{$tr->mile }}</td>
                                <td class=" ">{{$tr->dho }}</td>
                                <td class=" ">{{$tr->rpm }}</td>
                                <td class=" ">{{$tr->dh_rpm }}</td>
                                <td class=" ">
                                    @if($tr->upload!="")
                                    <?php
                                    	$url = explode(",",$tr->upload);
                                    	$name = explode(",",$tr->filename );
                                    	
                                    	?>
                                    	@for($i=0;$i<count($url);$i++)
                                    	<a href='{{$url[$i]}}'>{{$name[$i]}}</a><br>
                                    	@endfor
                                    @endif
                                </td>
                               
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</section>

<script type="text/javascript" src="assets/plugins/jquery/jquery-2.2.3.min.js"></script>
        <script type="text/javascript" src="assets/plugins/datatables/js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" src="assets/plugins/datatables/js/dataTables.tableTools.min.js"></script>
		<!-- <script type="text/javascript" src="assets/plugins/datatables/js/dataTables.colReorder.min.js"></script> -->
		<script type="text/javascript" src="assets/plugins/datatables/js/dataTables.scroller.min.js"></script>
		<script type="text/javascript" src="assets/plugins/datatables/dataTables.bootstrap.js"></script>
        <script type="text/javascript" src="assets/plugins/select2/js/select2.full.min.js"></script>
        <!-- DataTables -->
        <script src="{{asset('assets/admin/plugins/datatables/jquery.dataTables.js')}}"></script>
        <script src="{{asset('assets/admin/plugins/datatables/dataTables.bootstrap.js')}}"></script>
        <script src="{{asset('assets\admin\plugins\datatables\extensions\Responsive\js\dataTables.responsive.js')}}"></script>
		<script type="text/javascript">
            $(function(){
                $(".search_button").click(function(){
                    if($(".search_div").hasClass('div_hide')){
                        // $(".search_div").slideUp();
                        $(".search_div").removeClass('div_hide')
                    }else{
                        // $(".search_div").slideDown();
                        $(".search_div").addClass('div_hide')
                    }
                })
                $( window ).resize(function() {
                    search_form_view();
                });
            })
            search_form_view();
            function search_form_view(){
                if($(window).width()<992){
                        $(".search_div").addClass('div_hide')
                    }else{
                        $(".search_div").removeClass('div_hide')
                    }
            }
			if (jQuery().dataTable) {
				function initTable2() {
					var table = jQuery('#sample_2');

					var oTable = table.dataTable({
                        'responsive': true,
                        "bPaginate": true,
                        "searching": false,
                        "bFilter": false, 
                        "bInfo": false,
                        "bOrder" :false,
						// "columnDefs": [{
                        //     "orderable": false,
                        //     "targets": [0]
                        // }],
                        
                         "lengthMenu": [
                             [5, 15, 20, -1],
                             [5, 15, 20, "All"] // change per page values here
                         ],
                        // set the initial value
                        "pageLength": 5,   
					});

					var tableWrapper = jQuery('#sample_2_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
					tableWrapper.find('.dataTables_length select').select2(); // initialize select2 dropdown
				}
				initTable2();
			}

		</script>

@endsection
