/*
(c)2015 SIW Internationale vrijwilligersprojecten
*/

(function($) {
	$(document).ready(function() {
		//vfb
		$('div.vfb-radio :radio').addClass('mCheck');
		$('div.vfb-checkbox :checkbox').addClass('mCheck');
		$('tr.vfb-likert-row :radio').addClass('mCheck');
		//uitvoeren
		$('.mCheck').mCheckable({innerTags: "<div></div>"});
	
		//cart laten verdwijnen als je ergens anders op het scherm klikt    
		$(document).on('click',function(){
			$('.kad-head-cart-popup.in').collapse('hide');
		});

		
	})
})(jQuery);

/*
(function($) {
$("a[href='#formulier']").click(function() {
    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') 
        || location.hostname == this.hostname) {

        var target = $(this.hash);
        target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
           if (target.length) {
             $('html,body').animate({
                 scrollTop: target.offset().top
            }, 1000);
            return false;
        }
    }
});
})(jQuery);
*/



(function($) {
    $(".postcode, .huisnummer").change(function(){
		var postcode = $('.postcode').val().replace(/ /g,'').toUpperCase();
		var housenumber = $('.huisnummer').val();
		var housenumber = housenumber.replace(/[^0-9]/g,'');
		var site_url = parameters.url;
		
		$.ajax({        
			url: site_url +'/wp-content/themes/siw/includes/siw-postcode.php',
			type: 'GET',
			dataType: 'json',
			data: 'postcode=' + postcode + '&housenumber=' + housenumber,	
			success: function(result) {
				if(result.success == 1) {
					$('.plaats').val(result.resource.town);
					$('.straat').val(result.resource.street);
					$('.plaats').prop('disabled', true);		  
					$('.straat').prop('disabled', true);		  
				}
				else {
					$('.plaats').val('');
					$('.straat').val('');
					$('.plaats').prop('disabled', false);		  
					$('.straat').prop('disabled', false);		
				}             
			},
		});
		return false;
    });
	

})(jQuery);
