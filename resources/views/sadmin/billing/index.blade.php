@extends("layout.customerLayout")
@section("contents")

<script>
     $(function(){
         $('#dispatchdetailtable').DataTable( {
             'responsive': true,
             "bPaginate": false,
             "searching": false,
             "bFilter": false, 
             "bInfo": false
         } );


         //
         $(".reset_email").click(function(){
           cancel_email();
         })

     })
   
    

    function sel_page(obj) {
        var url = "{{ url('sadmin/billing') }}";
        var page = obj.val();
        var search = $("input[name=s]").val();
        location.href=url + "?page=" + page + "&s=" + search;
    }


    function create_invoice(id) {
        var url = "{{ url('sadmin/billing/create') }}";
        location.href=url + "/" + id;
    }

    function mark_payment(obj, id){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "POST",
            dataType:'json',
            url: "{{ url('sadmin/set_paymentmark') }}",
            data: {
                 id : id
            },
            success: function (data) {
                if(data.status=="success"){
                    var paid_obj = obj.parent().parent().children("td.paid_status");
                    var dates_obj = paid_obj.prev();
                    paid_obj.html("<span style='color:red'>Paid</span>");
                    dates_obj.html("<span>&#x2705</span>");
                    
                }
            }
        });
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
            Dispatch Details
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">               
                <!--//Account list-->
                <div class="box account_list">
                  
                    <div class="box-header with-border">
                       
                        <div class="text-right">
                            <form action="{{url('/sadmin/billing')}}" method="get" class="form-inline">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="s" placeholder="PO#" value="{{($s)?$s:''}}" />
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary" type="submit">Search</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="dispatchdetailtable" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                                        <thead>
                                            <tr role="row">
                                                <th style="width: 10%;">Driver Name</th>
                                                <th style="width: 10%;">Company</th>                                             
                                                <th style="width: 10%;">Address</th>                                             
                                                <th style="width: 6%;">Po</th>
                                                <th style="width: 8%;">Pu Date</th>
                                                <th style="width: 8%;">Del Date</th>
                                                <th style="width: 6%;">Revenue</th>
                                                <th style="width: 6%;">Miles</th>
                                                <th style="width: 6%;">DH-O</th>
                                                <th style="width: 6%;">RPM</th>
                                                <th style="width: 5%;">DH RPM</th>
                                                <th style="width: 5%;">Days to Pay</th>
                                                <th style="width: 5%;">Invoice Dates</th>
                                                <th style="width: 5%;">Paid</th>
                                                <th style="width: 5%;">Send Email</th>
                                                <th style="width: 5%;">Attach</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                         
                                            @foreach ($billings as $tr)
                                                <?php
                                               
                                                    $driver = App\Driver::find($tr->driver_id);
                                                    $user = App\User::find($tr->user_id); 
                                                    $company = App\Customer::find($driver->company_id);
                                                   // var_dump($company);
                                                    if(count($company ) == 0) {
                                                    	$company_name = "";
                                                    } else {
                                                    	$company_name= $company->company;
                                                    }

                                                    $emps_arr = explode(",",$driver->employee_id);
                                                     
                                                    //shipping detail
                                                    
                                                    $contact = App\Contact_list::find($tr->contact_id);
                                                    if($contact != NULL) {
                                                       
                                                        
                                                    } else {
                                                        $contact = new stdClass();
                                                        $contact->d_company_name = '';
                                                        $contact->address1 = '';
                                                        $contact->address2 = '';
                                                        $contact->state = '';
                                                        $contact->city = '';
                                                        $contact->zipcode = '';
                                                    }

                                                    //Invoice 
                                                    if($tr->paid_status == 1) {
                                                      
                                                    }                                                    
                                                   
                                                ?>
                                              
                                                <tr class="gradeA odd" item_id="<?php echo $tr->id ?>">
                                                    <td class=" ">
                                                        {{$company_name}} ({{$user->firstname}})
                                                    </td>
                                                    <td class="company ">{{$contact->d_company_name }}</td>
                                                    <td class=" ">{{($contact->address1=="")?"":$contact->address1.",".$contact->address2." ".$contact->city.",".$contact->state." ".$contact->zipcode }}</td>
                                                    <td class="po_num ">{{$tr->po }}</td>
                                                    <td class=" ">{{$tr->put_date }}</td>
                                                    <td class=" ">{{$tr->del_date }}</td>
                                                    <td class=" ">{{$tr->revenue }}</td>
                                                    <td class=" ">{{$tr->mile }}</td>
                                                    <td class=" ">{{$tr->dho }}</td>
                                                    <td class=" ">{{$tr->rpm }}</td>
                                                    <td class=" ">{{$tr->dh_rpm }}</td>
                                                    <td class=" "> {{$tr->invoice_created}}    </td>                                                  
                                                    <td class="paid_dates"> 
                                                            {{-- &#x274C --}}
                                                            @if($tr->paid_status)  
                                                                &#x2705
                                                            @else
                                                                <?php
                                                                    $today=date_create(date('Y-m-d'));
                                                                    $created=date_create($tr->invoice_created);
                                                                    $diff=date_diff($created,$today);
                                                                    echo $diff->format("%a days");
                                                                ?>

                                                            @endif
                                                    </td>                                                  
                                                    <td class="paid_status">
                                                        @if($tr->paid_status)    
                                                            <span style="color:red">Paid</span>
                                                        @else
                                                            <a onclick="mark_payment($(this),{{ $tr->id}})" class="btn btn-primary btn-xs edit"> Paid</a>  
                                                        @endif
                                                    </td>  
                                                    
                                                    <td  class="send_invoice">
                                                        @if($tr->invoice_created != '')
                                                        <a onclick="open_email_fome($(this),{{ $tr->id}})" data-attachesurl="{{$tr->upload}}" data-attachesname="{{$tr->filename}}" class="btn btn-primary btn-xs edit"> Send Email</a>  
                                                        @endif
                                                    </td>
                                                    
                                                    <td class=" attached_file">
                                                    	<?php
                                                    	$url = explode(",",$tr->upload);
                                                    	$name = explode(",",$tr->filename );
                                                    	$n = 0;
                                                    	?>
                                                        @for($i=0;$i<count($url);$i++)
                                                            
                                                            <a target='_blank' href='{{$url[$i]}}' target="_blank">{{$name[$i]}}</a>
                                                        @endfor
                                                        
                                                        
                                                    </td>
                                                   
                                                </tr>
                                              
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="row">
                                        <div class="col-sm-1">
                                            <div class="dataTables_info" id="dataTables-example_info" role="alert" aria-live="polite" aria-relevant="all">Tatal <?php echo $billings->total() ?> counts</div>

                                        </div>
                                         <div class="col-sm-2 ">
                                            <select class="form-control" name="page_id" onchange="sel_page($(this))">
                                                @for($i=0; $i <$billings->lastPage(); $i++)
                                                    <option value={{$i+1}} {{($billings->currentPage()==($i+1))?'selected':''}}>{{$i+1}}</option>
                                                @endfor
                                            </select>
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

    {{-- Email box customize --}}

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
</div>
<style>
    .form-group.custom_input{
        margin-right: 20px
    }
</style>
@endsection
