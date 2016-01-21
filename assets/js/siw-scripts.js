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
		
		if( $(window).width() < 790 ) {
			$('.accordion-toggle').each(function(){
				$(this).removeAttr('data-parent');
			});
		}
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
						$('.plaats').prop('readonly', true);		  
						$('.straat').prop('readonly', true);		  
					}
					else {
						$('.plaats').val('');
						$('.straat').val('');
						$('.plaats').prop('readonly', false);		  
						$('.straat').prop('readonly', false);		
					}             
				},
			});
		}
		return false;
    });
	
	$("#siw_newsletter_subscription").submit(function(event){
		event.preventDefault();
		
		var name = $("#newsletter_name").val();
		var email = $("#newsletter_email").val();
		var list = $("#newsletter_list_id").val();

		if ((name != '') && (email != '') && (list != '')){
			$( "#siw_newsletter_subscription" ).addClass( "hidden" );
			$( "#newsletter_message" ).removeClass( "hidden" );
			$( "#newsletter_message" ).text(parameters.sending);
			$.ajax({
				url : parameters.ajax_url,
				type : 'post',
				dataType: 'json',
				data : {
					action : 'newsletter_subscription',
					name : name,
					email : email,
					list : list
				},
				success: function(result) {
					if(result.success == 1) {
						$( "#newsletter_message" ).text(result.message);
						ga('send', 'event', 'Nieuwsbrief', 'Aanmelden');
					}
					else {
						$( "#newsletter_message" ).text(result.message);
						$( "#siw_newsletter_subscription" ).removeClass( "hidden" );
					}             
				},
			});		
		
		}
	});

	//E-mailvalidatie
	var validations ={
		email: [/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/, parameters.invalid_email]
	};

	$(document).ready(function(){
		$("#newsletter_email").change( function(){
			validation = new RegExp(validations['email'][0]);
			if (!validation.test(this.value)){
				this.setCustomValidity(validations['email'][1]);
				return false;
			} else {
				this.setCustomValidity('');
			}
		});
	})	
})(jQuery);





/*! mCheckable - v1.0.2 - 2015-03-01
* https://github.com/mIRUmd/mCheckable/
* Copyright (c) 2015 Balan Miroslav; */

!function(a){function b(b,d,e){e.on("click",function(e){e.preventDefault(),d.trigger("click"),"radio"==d.attr("type")&&c(a('input[type="radio"]')),b.onClick&&b.onClick()})}function c(b){b.each(function(b,c){a(c).next().toggleClass("checked",a(c).is(":checked"))})}function d(b,d){b.change(function(){"radio"==b.attr("type")&&c(a('input[type="radio"]')),d.toggleClass("checked",b.is(":checked"))})}var e={init:function(c){return this.each(function(){var e=a(this),f=e.data("mCheckable");if(!e.data("checkable")){if("undefined"==typeof f){var g={className:"mCheckable",classNameRadioButton:"radiobutton",classNameCheckbox:"checkbox",addClassName:!1,baseTags:"<span></span>",innerTags:"<em></em>"};f=a.extend({},g,c),e.data("mCheckable",f)}else f=a.extend({},f,c);var h=a(f.baseTags).prepend(f.innerTags).addClass(f.className).toggleClass("checked",e.is(":checked"));f.addClassName&&h.addClass(f.addClassName),h.addClass("checkbox"==e.attr("type")?f.classNameCheckbox:f.classNameRadioButton),e.hide().after(h),b(f,e,h),d(e,h),e.data("checkable","checkable")}})},check:function(){return this.each(function(){var b=a(this),c=b.next();b.prop("checked",!0),c.addClass("checked")})},unCheck:function(){return this.each(function(){var b=a(this),c=b.next();b.prop("checked",!1),c.removeClass("checked")})}};a.fn.mCheckable=function(){var b=arguments[0];if(e[b])b=e[b],arguments=Array.prototype.slice.call(arguments,1);else{if("object"!=typeof b&&b)return a.error("Method "+b+" does not exist on jQuery.mCheckable"),this;b=e.init}return b.apply(this,arguments)}}(jQuery);