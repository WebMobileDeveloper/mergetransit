$(function(){
    $(".select2").select2();
    $('#put_date').datetimepicker({
        format: 'YYYY-MM-DD HH:mm:ss',
        // inline: true,
                sideBySide: true
    });

    $('#del_date').datetimepicker({
        format: 'YYYY-MM-DD HH:mm:ss',
        sideBySide: true
    });

    //add shippper and remover shipper

    $("#add_shipper").on("click", function(){
           
        var html = '<div class="form-group custom_input shipper" >'+
                        '<input type="hidden" name="origin_order_index[]" value="0" />'+
                        '<input type="hidden" name="origin_detail_addr_id[]" value="0"/>'+
                        '<input type="hidden" class="lat" name="origin_lat[]" value=""/>'+
                        '<input type="hidden" class="lng" name="origin_lng[]" value=""/>'+
                        '<label class="col-sm-2 control-label"></label>'+
                        '<div class="col-xs-1 " style="padding-left:0;padding-right:10px">'+
                            '<input class="form-control" name="shipper[]" type="text" value="" placeholder="Shipper Name" required>'+
                        '</div>'+
                        '<label class="col-sm-1 control-label">Origin</label>'+
                        
                        '<div class="col-xs-1 " style="padding-left:0;padding-right:10px">'+
                            '<input class="form-control zipcode postal_code" name="origin_zipcode[]" type="text" value=""'+
                            ' placeholder="ZipCode" minlength="5" maxlength="5" pattern="[0-9]*"  onchange="fillAddressByZip($(this))" autocomplete="off" required/>'+
                        '</div>'+
                        '<div class="col-xs-2 " style="padding-left:0;padding-right:10px">'+
                            '<input class="form-control" name="origin_street[]" type="text" value="" placeholder="Street"'+
                            'onblur="getFullLocation($(this))" autocomplete="off"/>'+
                        '</div>'+
                        '<div class="col-xs-1 " style="padding-left:0;padding-right:10px">'+
                            '<input class="form-control origin_city locality neighborhood" name="origin_city[]" type="text" value="" placeholder="City"  required/>'+
                        '</div>'+
                        '<div class="col-xs-1 " style="padding-left:10px;padding-right:0">'+
                            '<input class="form-control origin_province administrative_area_level_1" name="origin_province[]" type="text" value="" placeholder="State" required />'+
                        '</div>'+
                        '<div class="col-xs-1 " style="padding-left:0;padding-right:10px">'+
                            '<a class="btn btn-info remove_shipper" onclick="em_remove(this)">'+
                                '<i class="fa fa-remove"></i>'+
                            '</a>'+
                        '</div>'+
                    '</div>';
        $(".shipper_area").append(html);
    })
 

    //add consignee and remover consignee

    $("#add_consignee").on("click", function(){
      
        var html = '<div class="form-group custom_input consignee" >'+
                    '<input type="hidden" name="dest_order_index[]" value="0" />'+
                    '<input type="hidden" name="dest_detail_addr_id[]" value="0"/>'+
                    '<input type="hidden" class="lat" name="destination_lat[]" value=""/>'+
                    '<input type="hidden" class="lng" name="destination_lng[]" value=""/>'+
                    '<label class="col-sm-2 control-label"></label>'+                        
                    '<div class="col-xs-1 " style="padding-left:0;padding-right:10px">'+
                        '<input class="form-control" name="consignee[]" type="text" value="" placeholder="Consignee Name" required>'+
                    '</div>'+
                    '<label class="col-sm-1 control-label">Destination</label>'+
                    
                    '<div class="col-xs-1 " style="padding-left:0;padding-right:10px">'+
                        '<input class="form-control zipcode postal_code" name="destination_zipcode[]" type="text" value="" '+
                        'placeholder="ZipCode" minlength="5" maxlength="5" pattern="[0-9]*"  onchange="fillAddressByZip($(this))" autocomplete="off"  required/>'+
                    '</div>'+
                    '<div class="col-xs-2 " style="padding-left:0;padding-right:10px">'+
                        '<input class="form-control " name="destination_street[]" type="text" value="" placeholder="Street"'+
                        'onblur="getFullLocation($(this))" autocomplete="off"/>'+
                    '</div>'+
                    '<div class="col-xs-1 " style="padding-left:0;padding-right:10px">'+
                        '<input class="form-control destination_city locality neighborhood" name="destination_city[]" type="text" value="" placeholder="City" required />'+
                    '</div>'+
                    '<div class="col-xs-1 " style="padding-left:10px;padding-right:0">'+
                        '<input class="form-control destination_province administrative_area_level_1" name="destination_province[]" type="text" value="" placeholder="State" required />'+
                    '</div>'+
                    '<div class="col-xs-1 " style="padding-left:0;padding-right:10px">'+
                        '<a class="btn btn-info remove_consignee" onclick="em_remove(this)">'+
                            '<i class="fa fa-remove"></i>'+
                        '</a>'+
                    '</div>'+
                '</div>';
        $(".consignee_area").append(html);
    })
})


    /*----------------------*/
    var componentForm = {      
        locality: 'long_name',
        neighborhood: 'long_name',
        administrative_area_level_1: 'short_name',
        postal_code: 'short_name'
    };
      
    var geocoder = new google.maps.Geocoder();
   
    // function fillAddressByZip1(obj) {
    //     obj[0].value = obj[0].value.replace(/[^0-9]/g, '');
    //     if (obj.val().length == 5) {
    //         geocoder.geocode({ 'address': obj.val() }, function (result, status) {
    //             console.log(obj.val(), result, status)
    //             var state = "N/A";
    //             var city = "N/A";
    //             //start loop to get state from zip
    //             for (var component in componentForm) {
    //                 obj.parent().parent().find("."+component).val('')
    //                 obj.parent().parent().find("."+component).prop('disabled', false);
    //             }

    //             if(status == "OK") {                    
    //                 // Get each component of the address from the place details
    //                 // and fill the corresponding field on the form.
    //                 for (var i = 0; i < result[0].address_components.length; i++) {
    //                     var addressType = result[0].address_components[i].types[0]; 
                    
    //                     if (componentForm[addressType]) {                  
    //                         var val = result[0].address_components[i][componentForm[addressType]];
    //                         //document.getElementById(addressType).value = val;
    //                         obj.parent().parent().find("."+addressType).val(val)
    //                     }
    //                 }
    //                 getLatLong(obj, result)
    //             } else {
    //                 alert("Please enter Correct Zip Code.")
    //             }
    //         });
    //     }

    // }

    // IMPORTANT: Fill in your client key
    var clientKey = "js-ucsOAGZ5J2JpKsm6QpUlGsU4rVcmEVMBgTndkGdI79dW3Qn4BpdqYYsvSxulrz7V";
            
    var cache = {};  
    
    /** Handle successful response */
    function handleResp(obj, data)
    {
       
        // Check for error
        if (data.error_msg)
            //errorDiv.text(data.error_msg);
            obj.after( "<p class='ziperror'>"+data.error_msg+"</p>" );
        else if ("city" in data)
        {
            // Set city and state
            // container.find("input[name='city']").val(data.city);
            // container.find("input[name='state']").val(data.state);
           
            obj.parent().parent().find(".locality").val(data.city)
            obj.parent().parent().find(".administrative_area_level_1").val(data.state)
          
            obj.parent().parent().find(".lat").val(data.lat);
            obj.parent().parent().find(".lng").val(data.lng);
            
        }
    }

    function fillAddressByZip(obj) { 
        // Get zip code
        obj[0].value = obj[0].value.replace(/[^0-9]/g, '');
        var zipcode = obj.val();
       
        if (zipcode.length == 5)
        {
           
            // Clear error
            obj.parent().parent().find(".ziperror").remove()
            
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

    function getFullLocation(obj) {
        var street = obj.val()
        var city = obj.parent().parent().find(".locality").val();
        var state = obj.parent().parent().find(".administrative_area_level_1").val();
        var zip = obj.parent().parent().find(".postal_code").val();
        var address = street + "," + city + "," + state + "," + zip;
        if (obj.val().length > 0) {
            geocoder.geocode({ 'address': address }, function (result, status) {              
                if(status == "OK") {  
                    getLatLong(obj, result)
                } else {
                   // alert("Please enter Correct Zip Code.")
                }
            });
        }
    }

    function getLatLong(obj, result) {
        var lat = result[0].geometry.location.lat();
        var lng = result[0].geometry.location.lng();
        obj.parent().parent().find(".lat").val(lat);
        obj.parent().parent().find(".lng").val(lng);
    }
    /////////////////////

