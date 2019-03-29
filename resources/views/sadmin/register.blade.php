<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin_Mergetransit</title>
  
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="icon" type="image/png" href="{{asset('assets/images/fav_ico.png')}}">
  <link rel="stylesheet" href="{{asset('assets/admin/bootstrap/css/bootstrap.min.css')}}">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">

  <link rel="stylesheet" href="{{asset('assets/admin/dist/css/AdminLTE.min.css')}}">
  
  <link rel="stylesheet" href="{{asset('assets/admin/plugins/iCheck/square/blue.css')}}">
   
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <style>
    .register-box {
        width: 760px;
        margin: 35px auto;
    }
    .btn.type_btn{
        padding-left: 2px;
        padding-right: 2px;
    }
    .nav-tabs-custom > .tab-content{
        padding: 15px !important
    }
    .tab-content > .active {
        padding-top: 30px !important;
        }
    #register_form input {
        border: none;
        border-bottom: solid 1px #ccc;
    }
    #register_form input:focus {
        border-bottom: solid 1px #31b0d5;
    }
    #register_form input.has-error{
        border-bottom: solid 1px #f00;
    }
    .help-block {
        padding: 0 10px;

    color: red;
    }
    .ziperror {
        color:red
    }
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
</head>
<body class="hold-transition register-page">
<div class="register-box">
  <div class="register-logo">
    <a href="/"><b>MERGE</b>TRANSIT</a>
  </div>
  <!-- /.login-logo -->
  <div class="register-box-body">
    <p class="register-box-msg">Register</p>

     @if($status = Session::get("status"))
        <div class="alert alert-info form-group">
            <span class="help-block">
                <strong>{{$status}}</strong>
            </span>
        </div><br>
    @endif

    <form action="{{url('sadmin/register')}}" method="post" id="register_form" class="reg-form">
        {{ csrf_field() }}
        <div class="form-group has-feedback ">
            <input type="text" class="form-control {{ $errors->has('company_name') ? ' has-error' : '' }}" 
            name="company_name" placeholder="Company" value="{{ old('company_name') }}" autocomplete="off" required>
            @if ($errors->has('company_name'))
                <span class="help-block">{{ $errors->first('company_name') }} </span>
            @endif
        </div>
        <div class="row">
            <div class="col-xs-2">
                <div class="form-group has-feedback">
                    <input type="text" class="form-control {{ $errors->has('zip_code') ? ' has-error' : '' }}" value="{{ old('zip_code') }}" 
                    name="zip_code" placeholder="Zip" autocomplete="off" minlength="5" maxlength="5" pattern="[0-9]*" 
                    onchange="fillAddressByZip($(this))" required>
                    @if ($errors->has('zip_code'))
                        <span class="help-block">{{ $errors->first('zip_code') }} </span>
                    @endif
                </div>
            </div>
            <div class="col-xs-5">
                <div class="form-group has-feedback">
                    <input type="text" class="form-control {{ $errors->has('street') ? ' has-error' : '' }}" value="{{ old('street') }}" 
                    name="street" placeholder="Street" autocomplete="off"  required>
                    @if ($errors->has('street'))
                        <span class="help-block">{{ $errors->first('street') }} </span>
                    @endif
                </div>
            </div>
            <div class="col-xs-3">
                <div class="form-group has-feedback">
                    <input type="text" class="form-control {{ $errors->has('city') ? ' has-error' : '' }}" value="{{ old('city') }}" 
                    name="city" placeholder="City" autocomplete="off"  required>
                    @if ($errors->has('city'))
                        <span class="help-block">{{ $errors->first('city') }} </span>
                    @endif
                </div>
            </div>
            <div class="col-xs-2">
                <div class="form-group has-feedback">
                    <input type="text" class="form-control {{ $errors->has('state') ? ' has-error' : '' }}" value="{{ old('state') }}" 
                    name="state" placeholder="State" autocomplete="off"  required>
                    @if ($errors->has('state'))
                        <span class="help-block">{{ $errors->first('state') }} </span>
                    @endif
                </div>
            </div>
            
        </div>
        <div class="row">
            <div class="col-xs-6">
                <div class="form-group has-feedback">
                    <input type="text" class="form-control {{ $errors->has('firstname') ? ' has-error' : '' }}" value="{{ old('firstname') }}" 
                    name="firstname" placeholder="First name" autocomplete="off"  required>
                    @if ($errors->has('firstname'))
                        <span class="help-block">{{ $errors->first('firstname') }} </span>
                    @endif
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group has-feedback">
                    <input type="text" class="form-control {{ $errors->has('lastname') ? ' has-error' : '' }}" value="{{ old('lastname') }}" 
                    name="lastname" placeholder="Last name" autocomplete="off"  required>
                    @if ($errors->has('lastname'))
                        <span class="help-block">{{ $errors->first('lastname') }} </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="form-group has-feedback">
            <input type="text" class="form-control {{ $errors->has('mc_num') ? ' has-error' : '' }}" value="{{ old('mc_num') }}" 
            name="mc_num" placeholder="MC Number" autocomplete="off"  required>
            @if ($errors->has('mc_num'))
                <span class="help-block">{{ $errors->first('mc_num') }} </span>
            @endif
        </div>

        <div class="form-group has-feedback">
            <input type="text" class="form-control {{ $errors->has('phone') ? ' has-error' : '' }}" value="{{ old('phone') }}" 
            name="phone" placeholder="Phone" autocomplete="off"  required>
            @if ($errors->has('phone'))
                <span class="help-block">{{ $errors->first('phone') }} </span>
            @endif
        </div>
        
        <div class="form-group has-feedback">
            <input type="email" class="form-control {{ $errors->has('email') ? ' has-error' : '' }}" value="{{ old('email') }}" 
            name="email" placeholder="Email" autocomplete="off"  required>
            @if ($errors->has('email'))
                <span class="help-block">{{ $errors->first('email') }} </span>
            @endif
        </div>

        <div class="form-group has-feedback">
            <input type="password" class="form-control {{ $errors->has('password') ? ' has-error' : '' }}"
             name="password" placeholder="Password" autocomplete="off"  required>
            @if ($errors->has('password'))
                <span class="help-block">{{ $errors->first('password') }} </span>
            @endif
        </div>

        <div class="form-group has-feedback">
            <input type="password" class="form-control" name="password_confirmation" placeholder="Retype password" autocomplete="off"  required>
            
        </div>

        <h4 class="text-center">Choose Service</h4>
        <div class="row">
                <span class="help-block stripe_msg" >
                </span>
            <div class="nav-tabs-custom">
                {{-- <ul class="nav nav-tabs"> --}}
                    {{-- <li class="active "><a href="#free" data-toggle="tab" aria-expanded="true">Free</a></li>
                    <li class=""><a href="#org" data-toggle="tab" aria-expanded="false">Organization</a></li>
                    <li class=""><a href="#opt" data-toggle="tab" aria-expanded="false">Optimization</a></li>    --}}
                    
                      
                {{-- </ul> --}}
                <input type="hidden" name="service" value="1" />
                <input type="hidden" name="stripe_token" value="Free"/>
                <div class="col-xs-12">
                    <div class="row">
                        <div class="col-xs-4">
                        <a href="#free" data-toggle="tab" data-serviceid="1" class="btn btn-block btn-info type_btn {{old('service') == null || old('service')=='1'?'active':''}}" aria-expanded="true" onclick="click_btn($(this))">Free</a>
                        </div>
                        <div class="col-xs-4">
                            <a href="#org" data-toggle="tab" data-serviceid="2" class="btn btn-block btn-info type_btn {{old('service')=='2'?'active':''}}" aria-expanded="false" onclick="click_btn($(this))">Organization</a>
                        </div>
                        <div class="col-xs-4">
                            <a href="#opt" data-toggle="tab" data-serviceid="3" class="btn btn-block btn-info type_btn {{old('service')=='3'?'active':''}}" aria-expanded="false" onclick="click_btn($(this))">Optimization</a>
                        </div>
                    </div>
                </div>
                <div class="tab-content">
                    <div class="tab-pane active" id="free">
                        <h3 class="text-center">FREE ACCOUNT</h3>
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="org">
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
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="opt">
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
                    <div class="form-row card_section">
                        <label for="card-element">
                            Credit or debit card
                        </label>
                        <div id="card-element">
                            <!-- A Stripe Element will be inserted here. -->
                        </div>
                    
                        <!-- Used to display form errors. -->
                        <div id="card-errors" role="alert"></div>
                    </div>
                    <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
            </div>
        </div>

        <div class="row">
            <div class="col-xs-3">
               
            </div>
            <!-- /.col -->
            <div class="col-xs-6">
                <button type="button" id="reg_btn" class="btn btn-primary btn-block btn-flat">Register</button>
            </div>
            <div class="col-xs-3">
               
                </div>
        <!-- /.col -->
        </div>
    </form>

    <div class="row text-center" style="padding-top:20px">
        <a href="/sadmin" >I already have an account</a>
    </div>

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 2.2.3 -->
<script src="{{asset('assets/admin/plugins/jQuery/jquery-2.2.3.min.js')}}"></script>
<!-- Bootstrap 3.3.6 -->
<script src="{{asset('assets/admin/bootstrap/js/bootstrap.min.js')}}"></script>
<!-- iCheck -->
<script src="{{asset('assets/admin/plugins/iCheck/icheck.min.js')}}"></script>
<script src="{{asset('assets/admin/js/jquery.validate.min.js')}}"></script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAr1HliRAne44OuG55a6FOOornx_dHgBjA&libraries=places"></script>
<script src="https://js.stripe.com/v3/"></script>
<script>

    // Stripe.setPublishableKey('<?php echo env("STRIPE_PUBLIC_KEY")?>');
    var stripe = Stripe('<?php echo env("STRIPE_PUBLIC_KEY")?>');
    $(function(){


        $(".card_section").addClass('hide_card')
        $(".type_btn.active").trigger("click")
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
        var form = document.getElementById('register_form');
        $("#register_form #reg_btn").click(function(event){
            if ($("input[name=service]").val() == 1) {
                $("#register_form").submit();
            }
            event.preventDefault();

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
        });

        // Submit the form with the token ID.
        function stripeTokenHandler(token) {
            // Insert the token ID into the form so it gets submitted to the server
            console.log(token)
            $("input[name=stripe_token]").val(token.id);          
            
            //   // Submit the form
            $("#register_form").submit();
        }
        /////////////
    });

    function click_btn(btn) {
        /////service  

        var serviceId = btn.data("serviceid");
        $("input[name=service]").val(serviceId)

        $(".type_btn").each(function(){
            $(this).removeClass("active")
        });
        btn.addClass("active")

        if (serviceId !=1) {
            $(".card_section").removeClass('hide_card')
        } else {
            $(".card_section").addClass('hide_card')
        }

    }


    
    /*----------------------*/
  
      
    var geocoder = new google.maps.Geocoder();
   

    // IMPORTANT: Fill in your client key
    var clientKey = "js-ucsOAGZ5J2JpKsm6QpUlGsU4rVcmEVMBgTndkGdI79dW3Qn4BpdqYYsvSxulrz7V";
            
    var cache = {};  
    
    /** Handle successful response */
    function handleResp(obj, data)
    {
       
        // Check for error
        if (data.error_msg){
            //errorDiv.text(data.error_msg);
            obj.after( "<p class='ziperror'>"+data.error_msg+"</p>" );
            $("input[name=city]").val('')
            $("input[name=state]").val('')

        }else if ("city" in data) {
            // Set city and state
            // container.find("input[name='city']").val(data.city);
            // container.find("input[name='state']").val(data.state);
           
            $("input[name=city]").val(data.city)
            $("input[name=state]").val(data.state)
        }
    }

    function fillAddressByZip(obj) { 
        // Get zip code
        obj[0].value = obj[0].value.replace(/[^0-9]/g, '');
        var zipcode = obj.val();
       
        if (zipcode.length == 5)
        {
           
            // Clear error
            $(".ziperror").remove()
            
            // Check cache
            if (zipcode in cache)
            {
                handleResp(obj,cache[zipcode]);
            }
            else
            {
                // Build url
                var url = "https://www.zipcodeapi.com/rest/"+clientKey+"/info.json/" + zipcode + "/degrees";
                
                // Make AJAX request
                $.ajax({
                    "url": url,
                    "dataType": "json"
                }).done(function(data) {
                    handleResp(obj,data);
                    
                    // Store in cache
                    cache[zipcode] = data;
                }).fail(function(data) {
                    if (data.responseText && (json = $.parseJSON(data.responseText)))
                    {
                        // Store in cache
                        cache[zipcode] = json;
                        
                        // Check for error
                        if (json.error_msg)
                            obj.after( "<p class='ziperror'>"+json.error_msg+"</p>" );
                    }
                    else
                        //errorDiv.text('Request failed.');
                        obj.after( "<p class='ziperror'>Request failed.</p>" );
                });
            }
        }

    }

</script>
</body>
</html>
