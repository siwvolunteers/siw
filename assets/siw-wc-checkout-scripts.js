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
		
		// lightbox met voorwaarden
		var popup = $('#terms-and-conditions').mPopup();

		$('#open-terms-and-conditions').on('click',function(e){
			e.preventDefault();
			popup.mPopup('open');
		});
    
	})

	$(document).on('click', '#accept-terms', function(){
		$('#terms-and-conditions').mPopup('close');
		$("#terms").prop( "checked", true );
		$('.terms .mCheckable').addClass('checked');

	});
	
	$(document).on('click', '#cancel-terms', function(){
		$('#terms-and-conditions').mPopup('close');
		$("#terms").prop( "checked", false );
		$('.terms .mCheckable').removeClass('checked');

	});


	$(document).ready(function() {   
		$("#billing_postcode, #billing_housenumber").change(function(){
		var postcode = $('#billing_postcode').val().replace(/ /g,'').toUpperCase();
		var housenumber = $('#billing_housenumber').val();
		var housenumber = housenumber.replace(/[^0-9]/g,'');
		var site_url = parameters.url;
		
		$.ajax({        
				url: site_url +'/wp-content/themes/pinnacle_child/includes/siw-postcode.php',
				type: 'GET',
				dataType: 'json',
				data: 'postcode=' + postcode + '&housenumber=' + housenumber,
				
			success: function(result) {
				if(result.success == 1) {
					$('#billing_city').val(result.resource.town);
					$('#billing_address_1').val(result.resource.street);
					$('#billing_city').prop('disabled', true);		  
					$('#billing_address_1').prop('disabled', true);		  
				}
				else {
					$('#billing_city').val('');
					$('#billing_address_1').val('');
					$('#billing_city').prop('disabled', false);		  
					$('#billing_address_1').prop('disabled', false);		
				}             
			},
		});
		return false;
		});
	}) 


	
})(jQuery);