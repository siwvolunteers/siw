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
		//winkelwagen verbergen indien er geen projecten in zitten
		$( "li.menu-cart-icon-kt" ).has( "span.kt-cart-total:contains('0')" ).css( "display", "none" );

	})
	
	$(document).ajaxComplete(function() {
		//winkelwagen verbergen indien er geen projecten in zitten
		$( "li.menu-cart-icon-kt" ).has( "span.kt-cart-total:contains('0')" ).css( "display", "none" );
	})

    $(".postcode, .huisnummer").change(function(){
		var postcode = $('.postcode').val().replace(/ /g,'').toUpperCase();
		var housenumber = $('.huisnummer').val();
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
		}
		return false;
    });
	

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