/*
(c)2015 SIW Internationale vrijwilligersprojecten
*/

(function($) {
	
	//Api key
	$.papi.setApiKey('2e68fc5a552f49564269b903b3df0f07b33a7246');
	
	//visual form builder pro
	$('.postcode').papi({
		placeholders: {
			street: $('.straat')[0],
			town: $('.plaats')[0]
		}
	})
	
	//woocommerce
	$('#billing_postcode').papi({
		placeholders: {
			street: $('#billing_address_1')[0],
			town: $('#billing_city')[0]
		}
	})
	
})(jQuery);