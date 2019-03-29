@extends("layout.customerLayout")
@section("contents")

<script src="https://js.stripe.com/v3/"></script>
<style>
    .hide_card{display:none;}
    .StripeElement {
        background-color: white;
        height: 40px;
        padding: 10px 12px;
        border-radius: 4px;
        border: 1px solid transparent;
        box-shadow: 0 1px 3px 0 #e6ebf1;
        -webkit-transition: box-shadow 150ms ease;
        transition: box-shadow 150ms ease;
    }

    .StripeElement--focus {
        box-shadow: 0 1px 3px 0 #cfd7df;
    }

    .StripeElement--invalid {
        border-color: #fa755a;
    }

    .StripeElement--webkit-autofill {
        background-color: #fefde5 !important;
    }
    #card-errors{
        color:red;
    }
</style>
<script>
    
 
    var stripe = Stripe('<?php echo env("STRIPE_PUBLIC_KEY")?>');
    $(function(){
        $(".card_section").addClass('hide_card')
        $(".type_btn.active").trigger("click")
        console.log($("input[name=service]").val())

         $(".card_info .add_card").click(function(e){
            e.preventDefault();
            $(".card_section").removeClass("hide_card");
            $(".card_info").addClass("hide_card");
            $("input[name=card_change]").val(1);
        })

        $(".cancel_card").click(function(e){
            e.preventDefault();
            $(".card_section").addClass("hide_card");
            $(".card_info").removeClass("hide_card");
            $("input[name=card_change]").val(0);
        })
        ///////////
        // Create an instance of Elements.
        var elements = stripe.elements();

        // Custom styling can be passed to options when creating an Element.
        // (Note that this demo uses a wider set of styles than the guide below.)
        var style = {
            base: {
                color: '#32325d',
                lineHeight: '18px',
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: 'antialiased',
                fontSize: '16px',
                '::placeholder': {
                color: '#aab7c4'
                }
            },
            invalid: {
                color: '#fa755a',
                iconColor: '#fa755a'
            }
        };

        // Create an instance of the card Element.
        var card = elements.create('card', {style: style});

        // Add an instance of the card Element into the `card-element` <div>.
        card.mount('#card-element');

        // Handle real-time validation errors from the card Element.
        card.addEventListener('change', function(event) {
            var displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });

        // Handle form submission.
        var form = document.getElementById('service_form');

        $("#upgrade_btn").click(function(event){
            
            if ($("input[name=service]").val() == 1 ) {
                $("#service_form").submit();
            } else {
                if ($("input[name=card_change]").val() == 0) {
                    $("#service_form").submit();
                } else {
                    stripe.createToken(card).then(function(result) {
                        if (result.error) {
                        // Inform the user if there was an error.
                        var errorElement = document.getElementById('card-errors');
                        errorElement.textContent = result.error.message;
                        } else {
                        // Send the token to your server.
                        stripeTokenHandler(result.token);
                        }
                    });
                }
                
            }
            
        });

        // Submit the form with the token ID.
        function stripeTokenHandler(token) {
            // Insert the token ID into the form so it gets submitted to the server
            console.log(token)
            $("input[name=stripe_token]").val(token.id);
            $("#service_form").submit();
        }
        /////////////

       
    });

    function click_btn(btn) {
        /////service  

        $("input[name=card_change]").val(0);

        var serviceId = btn.data("serviceid");
        $("input[name=service]").val(serviceId)

        $(".type_btn").each(function(){
            $(this).removeClass("active")
        });
        btn.addClass("active")

        if (serviceId !=1) {
            if ($("input[name=stripe_token]").val() == "Free" || $("input[name=stripe_token]").val() =='') {
                $("input[name=card_change]").val(1);
                $(".card_info").addClass('hide_card')
                $(".card_section").removeClass('hide_card')
            } else {
                $(".card_info").removeClass('hide_card')
                $(".card_section").addClass('hide_card')
            }
            
        } else {          
            
            $(".card_info").addClass('hide_card')
            $(".card_section").addClass('hide_card')
        }

    }
</script>

<div class="content-wrapper" style="min-height: 916px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
                Choose Service
        </h1>
       
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <!-- /.box -->
                <div class="box box-info">
                    
                    <!-- form start -->
                    
                    <form class="form-horizontal" id="service_form" name="service_form" action="{{url('sadmin/service' )}}" method = "post">
                        <input type="hidden" name="service" value="{{$service['member_type']}}" />
                        <input type="hidden" name="stripe_token" value="{{$service['card_token']}}"/>
                        <input type="hidden" name="card_change" value="0" />
                        {{ csrf_field() }}
                       
                        <div class="box-body">
                            @if($status = Session::get("status"))
                                <div class="alert alert-error form-group">
                                    <span class="help-error">
                                        <strong>{{$status}}</strong>
                                    </span>
                                </div><br>
                            @endif

                            <div class="col-xs-12">

                                <Label>Please select services</label>
                               
                                <div class="nav-tabs-custom">
                                  
                                    <div class=" ">
                                        <div class="row">
                                            <div class="col-xs-2">
                                            <a href="#free" data-toggle="tab" data-serviceid="1" class="btn btn-block btn-info type_btn {{$service['member_type'] == null || $service['member_type']=='1'?'active':''}}" aria-expanded="true" onclick="click_btn($(this))">Free</a>
                                            </div>
                                            <div class="col-xs-2">
                                                <a href="#org" data-toggle="tab" data-serviceid="2" class="btn btn-block btn-info type_btn {{$service['member_type']=='2'?'active':''}}" aria-expanded="false" onclick="click_btn($(this))">Organization</a>
                                            </div>
                                            <div class="col-xs-2">
                                                <a href="#opt" data-toggle="tab" data-serviceid="3" class="btn btn-block btn-info type_btn {{$service['member_type']=='3'?'active':''}}" aria-expanded="false" onclick="click_btn($(this))">Optimization</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-content ">
                                            
                                        <div class="tab-pane active" id="free">
                                            <div class="row">
                                                    <div class="col-sm-6">
                                                            <h3 class="text-center">FREE ACCOUNT</h3>
                                                    </div>
                                            </div>                                           
                                        </div>
                                        <!-- /.tab-pane -->
                                        <div class="tab-pane" id="org">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <h3  class="text-center">ORGANIZATION PROGRAM ($99 / MONTH)</h3>
                                                    <ul>
                                                        <li>
                                                            Personal admin + Mobile app
                                                        </li>
                                                        <li>
                                                            Admin enters all orders and details into System
                                                        </li>
                                                        <li>
                                                            Custom business reports 
                                                        </li>
                                                        <li>
                                                            Organize shipment documents via Mobile App
                                                        </li>
                                                        <li>
                                                            Create invoices with one touch
                                                        </li>
                                                        <li>
                                                            Email all shipments docs direct from App.
                                                        </li>
                                                    </ul>                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.tab-pane -->
                                        <div class="tab-pane" id="opt">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <h3 class="text-center">OPTIMIZATION PROGRAM  ($125 / WEEKLY)</h3>
                                                    <ul>
                                                        <li>
                                                            Organization program +
                                                        </li>
                                                        <li>
                                                            Dedicated carrier agent 
                                                        </li>
                                                        <li>
                                                            Routing service  
                                                        </li>
                                                        <li>
                                                            All loads booked
                                                        </li>
                                                        <li>
                                                            All communication with brokers
                                                        </li>
                                                        <li>
                                                            Improved All Mile RPM
                                                        </li>
                                                    </ul>                                                   
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.tab-pane -->
                                    </div>

                                    <div class="form-row  card_section">
                                        <div class="col-xs-6">
                                            <label for="card-element">
                                                Credit or debit card
                                            </label>
                                            <div id="card-element">
                                                <!-- A Stripe Element will be inserted here. -->
                                            </div>
                                        
                                            <!-- Used to display form errors. -->
                                            <div id="card-errors" role="alert"></div>
                                        </div>
                                        <div class="col-xs-1">
                                            <button type="button" class="cancel_card btn btn-block btn-info btn-sm ">Cancel</button>
                                        </div>
                                    </div>

                                    <div class="form-row col-xs-6 card_info">
                                        <label for="card-element">
                                            Credit or debit card
                                        </label>
                                       
                                        <div class="row">
                                                @if($service['last4'] != '')
                                            <p class="card_name col-xs-2">{{$service['brand']}}</p>
                                            <p class="last4 col-xs-2">****{{$service['last4']}}</p>                                          
                                            @endif
                                            <p class="col-xs-2">
                                                <button type="button" class="add_card btn btn-block btn-info btn-sm ">Add New</button>
                                            </div>
                                        </div>
                                      
                                    </div>
                                    <!-- /.tab-content -->
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <div class="col-sm-2">
                                <button type="button" id="upgrade_btn" class="btn btn-info save_button">Update</button>
                            </div>
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