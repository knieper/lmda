/*
* custom javascript tweaks for LMDA, built on top of jquery library that is set in the main theme Porto
*/
jQuery(document).ready(function ($) {

  "use strict";

/* additional styling for in honor of section on donate form - not needed for basic functionality */
  //hide name fields to start
  $("div.honoree").addClass('hidden');

//value is yes
  $("#CIVICRM_QFID_1_2").on('change',function() {
      if($(this).val() == 1){
        $("div.honoree").removeClass('hidden');
      }

    });
    //value is no
    $("#CIVICRM_QFID_0_4").on('change',function() {
      if($(this).val() == 0){
        $("div.honoree").addClass('hidden');
      }
    });


/*adjust paypal US label to be 'Pay by credit card using PayPal'*/
  $("#priceset").on('keyup', function(){
      $("label[for='CIVICRM_QFID_1_payment_processor_id']").replaceWith('<label for="CIVICRM_QFID_1_payment_processor_id">Credit card via PayPal</label>');
    });

// hide honoree section on confirmation page if values are blank
  console.log($("div.crm-contribution-confirm-form-block").find("#CIVICRM_QFID_0_4").val());

  if($("div.crm-contribution-confirm-form-block").find("#CIVICRM_QFID_0_4").val() == 0){
     var test = $(this).parents('crm-profile-view').addClass('hidden');
     console.log('this was called');
     console.log(test);
  }



});
