var FormInputMask = function () {
    
    var handleInputMasks = function () {
        

        $("#mask_date").inputmask("d/m/y", {
            autoUnmask: true
        }); //direct mask        
        $("#mask_date1").inputmask("d/m/y", {
            "placeholder": "*"
        }); //change the placeholder
        $("#mask_date2").inputmask("d/m/y", {
            "placeholder": "dd/mm/yyyy"
        }); //multi-char placeholder

        $("#LeftSec #birthday").inputmask("m/d/y", {
            "placeholder": "mm/dd/yyyy"
        }); //multi-char placeholder

        $("#updateprofile #birthday").inputmask("m/d/y", {
            "placeholder": "mm/dd/yyyy"
        }); //multi-char placeholder

        
       
        $("#mask_phone").inputmask("mask", {
            "mask": "(999) 999-9999"
        }); //specifying fn & options
        $("#mask_tin").inputmask({
            "mask": "99-9999999",
            placeholder: "" // remove underscores from the input mask
        }); //specifying options only
        $("#mask_number").inputmask({
            "mask": "9",
            "repeat": 10,
            "greedy": false
        }); // ~ mask "9" or mask "99" or ... mask "9999999999"
        $("#mask_decimal").inputmask('decimal', {
            rightAlignNumerics: false
        }); //disables the right alignment of the decimal input
        $("#mask_currency").inputmask('€ 999.999.999,99', {
            numericInput: true
        }); //123456  =>  € ___.__1.234,56

        $("#mask_currency2").inputmask('€ 999,999,999.99', {
            numericInput: true,
            rightAlignNumerics: false,
            greedy: false
        }); //123456  =>  € ___.__1.234,56
        $("#mask_ssn").inputmask("999-99-9999", {
            placeholder: " ",
            clearMaskOnLostFocus: true
        }); //default
        $("#exp_date1").inputmask("m/y", {
            "placeholder": "mm/yyyy"
        }); //multi-char placeholder
        $("#exp_date2").inputmask("m/y", {
            "placeholder": "mm/yyyy"
        }); //multi-char placeholder
        $("#cvv1").inputmask("decimal", {
            prightAlignNumerics: false
        }); //cvv
        $("#cvv2").inputmask("decimal", {
            prightAlignNumerics: false
        }); //cvv

        $("#card_num1").inputmask("9999-9999-9999-9999", {
            
        }); //card number
        $("#card_num2").inputmask("9999-9999-9999-9999", {
           
        }); //card number

    }

   

    return {
        //main function to initiate the module
        init: function () {
            handleInputMasks();
        }
    };

}();


    jQuery(document).ready(function() {
        FormInputMask.init(); // init metronic core componets
    });
