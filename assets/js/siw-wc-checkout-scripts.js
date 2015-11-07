/*
(c)2015 SIW Internationale vrijwilligersprojecten
*/


(function($) {
	/*datepicker toevoegen*/
	$(document).ready(function() {
		$('#billing_dob').datepicker({
		format: "dd-mm-yyyy",
		endDate: "-14y",
		startView: 2,
		language: "nl",
		autoclose: true
		});
	})

	/*workaround om de betaalmethode te selecteren als op de gestylde radiobuttons geklikt wordt.*/
	$(document).on('click', 'li.payment_method_mollie_wc_gateway_ideal div', function(){
		$( "#payment_method_mollie_wc_gateway_ideal" ).click();
	})
	$(document).on('click', 'li.payment_method_mollie_wc_gateway_ideal .mCheckable', function(){
		$( "#payment_method_mollie_wc_gateway_ideal" ).click();
	})
	$(document).on('click', 'li.payment_method_bacs div', function(){
		$( "#payment_method_bacs" ).click();
	})	
	$(document).on('click', 'li.payment_method_bacs .mCheckable', function(){
		$( "#payment_method_bacs" ).click();
	})
	
	//styling van checkboxes en radiobuttons
	$(document).ready(function() {
		//woocommerce checkout page
		$('.woocommerce-billing-fields :radio').addClass('mCheck');	
		$('.woocommerce-shipping-fields :radio').addClass('mCheck');		 
	
		//woocommerce mailpoet opt-in
		$('#mailpoet_checkout_subscribe').addClass('mCheck');
	
		//uitvoeren
		$('.mCheck').mCheckable({innerTags: "<div></div>"});	
	})

	$(document).ajaxComplete(function() {
		//woocommerce betaalmethodes
		$('.woocommerce-checkout-payment :radio').addClass('mCheck');	
		$('#terms').addClass('mCheck');	
		
		//uitvoeren
		$('.mCheck').mCheckable({innerTags: "<div></div>"});
   
	})

	$(document).on('change', '#billing_postcode, #billing_housenumber', function() {
		var postcode = $('#billing_postcode').val().replace(/ /g,'').toUpperCase();
		var housenumber = $('#billing_housenumber').val();
		var housenumber = housenumber.replace(/[^0-9]/g,'');
		if ((postcode != '') && (housenumber != '')){
			$.ajax({
				url : parameters.ajax_url,
				type : 'get',
				dataType: 'json',
				data : {
					action : 'postcode_lookup',
					postcode : postcode,
					housenumber : housenumber
				},
				success: function(result) {
					if(result.success == 1) {
						$('#billing_city').val(result.resource.town);
						$('#billing_address_1').val(result.resource.street);
						//$('#billing_city').prop('disabled', true);		  
						//$('#billing_address_1').prop('disabled', true);		  
					}
					else {
						$('#billing_city').val('');
						$('#billing_address_1').val('');
						//$('#billing_city').prop('disabled', false);		  
						//$('#billing_address_1').prop('disabled', false);		
					}             
				},
			});
		}
		return false;
    });
	
})(jQuery);