/*
(c)2015 SIW Internationale vrijwilligersprojecten
*/

(function($) {
	$(document).ajaxComplete(function() {
	
    // generate popup
    var popup = $('#terms-and-conditions').mPopup();

    // on click on button open generated popup
    $('#open-terms-and-conditions').on('click',function(e){
        e.preventDefault();
        popup.mPopup('open');
    });
    
	
	})
})(jQuery);


(function($) {
	$(document).on('click', '#accept-terms', function(){
		$('#terms-and-conditions').mPopup('close');
		$("#terms").prop( "checked", true );
		$('.terms .mCheckable').addClass('checked');

	})
	
	$(document).on('click', '#cancel-terms', function(){
		$('#terms-and-conditions').mPopup('close');
		$("#terms").prop( "checked", false );
		$('.terms .mCheckable').removeClass('checked');

	})
})(jQuery);
