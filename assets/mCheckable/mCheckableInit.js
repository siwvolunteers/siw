/*
(c)2015 SIW Internationale vrijwilligersprojecten
*/

(function($) {
	$(document).ready(function() {
		//vfb
		$('div.vfb-radio :radio').addClass('mCheck');
		$('div.vfb-checkbox :checkbox').addClass('mCheck');
		$('tr.vfb-likert-row :radio').addClass('mCheck');
		
		//woocommerce checkout page
		$('.woocommerce-billing-fields :radio').addClass('mCheck');	
		$('.woocommerce-shipping-fields :radio').addClass('mCheck');		 
	
		//woocommerce mailpoet opt-in
		$('#mailpoet_checkout_subscribe').addClass('mCheck');
	
		//search-and-filter
		$('form.searchandfilter :checkbox').addClass('mCheck');
		
		//uitvoeren
		$('.mCheck').mCheckable();
	
	
		//cart laten verdwijnen als je ergens anders op het scherm klikt    
		$(document).on('click',function(){
			$('.kad-head-cart-popup.in').collapse('hide');
		});
	
	})
})(jQuery);


//betaalmethodes en voorwaarden worden met AJAX op het scherm gezet, om deze te stylen is onderstaande functie nodig
(function($) {
	$( document ).ajaxComplete(function() {
		//woocommerce betaalmethodes
		$('.woocommerce-checkout-payment :radio').addClass('mCheck');	
		$('#terms').addClass('mCheck');	
		
		//$('div.payment_method_ideal :input').select2({minimumResultsForSearch: Infinity});
		//uitvoeren
		$('.mCheck').mCheckable();
		
	})
})(jQuery);


//workaround om de betaalmethode te selecteren als op de gestylde radiobuttons geklikt wordt.
(function($) {
	$(document).on('click', 'li.payment_method_ideal em', function(){
		$( "#payment_method_ideal" ).click();
	})
	
	$(document).on('click', 'li.payment_method_ideal .mCheckable', function(){
		$( "#payment_method_ideal" ).click();
	})
	
	$(document).on('click', 'li.payment_method_bacs em', function(){
		$( "#payment_method_bacs" ).click();
	})
	
	$(document).on('click', 'li.payment_method_bacs .mCheckable', function(){
		$( "#payment_method_bacs" ).click();
	})
})(jQuery);

