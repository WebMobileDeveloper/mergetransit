@extends("layout.customerLayout")
@section("contents")

<script>
    $(function(){
        $('#reporttable').DataTable( {
            'responsive': true,
            "bPaginate": true,
            "searching": false,
            "bFilter": false, 
            "bInfo": false,
            "order": [[0, "asc"]],
             "sPaginationType": "listbox"
        } );

        $("#startdate").datepicker({ format: 'yyyy-mm-dd',autoclose: true });
        $("#enddate").datepicker({ format: 'yyyy-mm-dd',autoclose: true });

     
        $(".reset_email").click(function(){
           cancel_email();
        })

    })
   
    function edit(id) {
        var url = "{{ url('sadmin/details') }}";
        location.href=url + "/" + id + "/edit";
    }

    
    function create_invoice(id) {
        var url = "{{ url('sadmin/billing/create') }}";
        location.href=url + "/" + id;
    }

    function open_email_fome(obj, id) {
        var detail_id = id;
        $("input[name=sel_detail_id]").val(detail_id);
        var company = obj.parent().parent().children("td.company").text();
        var po_num = obj.parent().parent().children("td.po_num").text();
        var attach_url_arr = obj.data('attachesurl').split(",");
        var attach_name_arr = obj.data('attachesname').split(",");
        var attachHtml = '';
        for (i=0; i<attach_url_arr.length; i++) {
            attachHtml+= '<a href="'+attach_url_arr[i]+'">'+attach_name_arr[i]+'</a><span onclick="delete_file($(this))"></span>';
        }
       

        $(".invoice_email_box .username a").text(company)
        $(".invoice_email_box .username span.po").text(po_num)
        $(".invoice_email_box .attached_file").children().remove();
        $(".invoice_email_box .attached_file").append($(attachHtml));
       
        $(".invoice_email_box").slideDown()

    }

    function delete_file(obj) {
        obj.prev().remove()
        obj.remove()
    }

    function cancel_email(){
        $("input[name=to_email]").val(''),
        $("input[name=subject]").val(''),
        $("textarea[name=message]").val('')
        $(".invoice_email_box").slideUp()
    }

    //send email invoice
    function sendInvoice(button){
        // console.log($("textarea[name=message]").val());return false;
        var message_arr = $("textarea[name=message]").val().split('\n');
        
        var detail_id = $("input[name=sel_detail_id]").val()

        var attach_array = [];
        $("#send_invoice_fome .attached_file a").each(function(){
            attach_array.push( $(this).attr('href') )
        })

        button.button("loading")
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "POST",
            dataType:'json',
            url: "{{ url('sadmin/send_invoice') }}",
            data: {
                detail_id:detail_id, 
                to:$("input[name=to_email]").val(),
                subject:$("input[name=subject]").val(),
                message:JSON.stringify(message_arr),
                attach: JSON.stringify(attach_array)
                
            },
            success: function (data) {
                button.button("stop")
                if(data.status=='success'){
                    alert("Sent the invoice successfully!");
                    $("input[name=to_email]").val(''),
                    $("input[name=subject]").val(''),
                    $("textarea[name=message]").val('')
                    $(".invoice_email_box").slideUp()
                
                }else{
                    alert("Failed! Please contact to support team.");
                }
            // var url = "{{ url('admin/invoice') }}";
            // location.href=url;
                
            }
        });
    }

</script>
<style>
    #send_invoice_fome .attached_file a {
        padding: 10px;
        background: white;
        border-radius: 5px;
        line-height: 50px;
    }
    #send_invoice_fome .attached_file span{
       
        background:url('../../../public/assets/images/Close-512.png');       
        background-size: cover;
        padding-right: 18px;
        cursor: pointer;
    }
</style>

<div class="content-wrapper" style="min-height: 916px;">
<meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Reports Generate
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">

       
        <div class="row">
            <div class="col-xs-12">      
                <div class="box account_list">
                  
                    <div class="box-header with-border">
                        <form class="form-horizontal" id="user_form" name="user_form" action="{{url('sadmin/reports/')}}" method = "post">

                        {{ csrf_field() }}
                            <div class="row " style="margin-bottom:15px;">
                                <div class="col-xs-2">
                                    <!-- select driver -->
                                    <select class = "form-control" name = "driver_id" requried>
                                        <option value= "">All</option>
                                        @foreach ($drivers as $driver)                                           
                                                <option value= {{$driver->id}} {{(!empty($res_data) && $driver->id == $res_data['driver_id'])?"selected":""}}>
                                                    @if($driver->company_id == 1)
                                                        {{$driver->firstname}} {{$driver->lastname}}
                                                    @else
                                                        {{$driver->company}} ({{$driver->firstname}})
                                                    @endif
                                                </option>
                                           
                                        @endforeach
                                    </select>                                
                                </div>
                                <div class="col-xs-2">
                                    <input type="text" name="company" value = "{{(!empty($res_data))?$res_data['company']:""}}" class="form-control" placeholder="Input Company">
                                </div>
                                <div class="col-xs-2">
                                    <input type="text" name="po" value = "{{(!empty($res_data))?$res_data['po']:""}}" class="form-control" placeholder="PO#">
                                </div>
                                <div class="col-xs-2">
                                    <input type="text" name="origin" value = "{{(!empty($res_data))?$res_data['origin']:""}}" class="form-control" placeholder="Input Origin">
                                </div>
                                <div class="col-xs-2">
                                    <input type="text" name="destination" value = "{{(!empty($res_data))?$res_data['destination']:""}}"  class="form-control" placeholder="Input Destination">
                                </div>
                                <div class="col-xs-1">
                                    <input type="text" name="startdate" value = "{{(!empty($res_data))?$res_data['start_date']:""}}" class="form-control" autocomplete="off" placeholder="Date" id="startdate">
                                </div>
                                <div class="col-xs-1">
                                    <input type="text" name="enddate" value = "{{(!empty($res_data))?$res_data['end_date']:""}}" class="form-control" autocomplete="off" placeholder="Date" id="enddate">
                                </div>
                                
                            </div>
                            <button type="submit" class="btn btn-info pull-right">Generate</a>
                        </form>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                            @if(!empty($infor))
                            <div class="row">
                                <div class="col-sm-12 report_details" style="color: #e33030;">
                                    <div class="col-xs-3">
                                        <label class="col-sm-12 control-label">{{$infor['driver_name']}}<br>{{$infor['from']}} ~ {{$infor['to']}}</label>
                                    </div>
                                    <div class="col-xs-6">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th>Total Revenue</th>
                                                <th>Total Miles</th>
                                                <th>Total DHO</th>
                                                <th>RPM</th>
                                                <th>DH RPM</th>
                                            </tr> 
                                            <tr>
                                                <td>{{$infor['total_revenue']}}</td>
                                                <td>{{$infor['total_mile']}}</td>
                                                <td>{{$infor['total_dho']}}</td>
                                                <td>{{$infor['total_rpm']}}</td>
                                                <td>{{$infor['total_dhrpm']}}</td>
                                            </tr> 
                                        </table>
                                    </div>
                                    <div class="col-xs-3 ">
                                        <a class="btn btn-app pull-right">
                                            <i class="fa fa-file-excel-o"></i> Excel
                                        </a>
                                        <a class="btn btn-app pull-right">
                                            <i class="fa fa-file-pdf-o"></i> PDF
                                        </a>
                                    </div>
                                    
                                </div>
                            </div>
                            @endif

                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="reporttable" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                                        <thead>
                                            <tr role="row">
                                                <th style="width: 10%;">Company</th>                                            
                                                <th style="width: 10%;">Driver</th>                                             
                                                <th style="width: 6%;">Contact</th>
                                                <th style="width: 6%;">Po</th>
                                                <th style="width: 6%;">Load#</th>
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
                                        @if(! empty($reports))
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
                                                
                                                     
                                                    <td class="company ">{{$tr->company }}</td>
                                                    <td class=" ">{{$tr->firstname }}</td>
                                                    <td class=" ">{{$tr->contact}}</td>
                                                    <td class=" po_num">{{$tr->po }}</td>
                                                    <td class=" ">{{$tr->load_num }}</td>
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
                                                            @if($tr->invoice_created == '')
                                                              <a onclick="create_invoice({{$tr->id}})" class="btn btn-info btn-xs"><i class="fa fa-edit "></i>Create Invoice</a>  
                                                            @else
                                                              <span style="background: #21e921;" >{{$tr->invoice_created}} </span>
                                                            @endif

                                                    <a onclick="open_email_fome($(this),{{$tr->id}})" data-attachesurl="{{$tr->upload}}" data-attachesname="{{$tr->filename}}" class="btn btn-info btn-xs"><i class="fa fa-edit "></i>Send Email</a>  
                                                        </td> 
                                                    <td class="attached_file ">
                                                    	<?php
                                                    	$url = explode(",",$tr->upload);
                                                    	$name = explode(",",$tr->filename );
                                                    	
                                                    	?>
                                                    	@for($i=0;$i<count($url);$i++)
                                                    	    <a target='_blank' href='{{$url[$i]}}'>{{$name[$i]}}</a>
                                                    	@endfor
                                                    </td>
                                                    <td class=" ">
                                                        <a onclick="edit({{$tr->id}})" class="btn btn-primary btn-xs edit"><i class="fa fa-edit "></i>Edit</a>
                                                    </td>
                                                   
                                                </tr>
                                            @endforeach

                                            
                                        @endif
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

<div class="box box-widget invoice_email_box" style="
        position: fixed;
        right: 0;
        width: 600px;
        z-index: 10000;
        bottom: 0;
        box-shadow: 0px 0px 1px 2px #047a86;
        background: #ecf0f5;
        display:none;
    ">
        <input type="hidden" name="sel_detail_id" />
            <div class="box-header with-border">
              <div class="user-block">
                <i class="fa fa-envelope-o" style="font-size: 24px;  color: #fa5b11;position:absolute"></i>
                <span class="username"><a href="#">Send Invoice Email</a>(<span class="po">Company Name PO#</span>)</span>
              </div>
              <!-- /.user-block -->
              <div class="box-tools">               
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool reset_email" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <form action="" id="send_invoice_fome">
                      
                    <!-- text input -->
                    <div class="form-group">
                        <label>FROM</label>
                         <input type="text" class="form-control" placeholder="" value="{{$customer_email}}" readonly>
                    </div>

                    <div class="form-group">
                        <label>TO</label>
                        <input type="text" class="form-control" placeholder="" name="to_email">
                    </div>

                    <div class="form-group">
                        <label>SUBJECT</label>
                            <input type="text" class="form-control" placeholder="" name="subject">
                    </div>
                    
                    <!-- textarea -->
                    <div class="form-group">
                        <label>Message</label>
                        <textarea class="form-control" rows="5" placeholder="" name="message"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Attached Invoice</label>
                        <p class="help-block attached_file">
                            <a href="#">invoice.pdf</a>
                        </p>
                    </div>
                               
                
                             
                </form>
            </div>

            <div class="box-footer">
                <a class="send_email btn bg-olive " onclick="sendInvoice($(this))" >Send </a>
                <a class="remove_email btn bg-navy " onclick="cancel_email()">Cancel</a>
            </div>
            <!-- /.box-footer -->
          </div>
<style>
    .form-group.custom_input{
        margin-right: 20px
    }
</style>
@endsection
