<?php
/*
(c)2015 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/*
functies t.b.v styling van radiobuttons, checkboxes en lightboxes
*/

add_action('wp_enqueue_scripts', 'siw_mcheckable_js');
function siw_mcheckable_js(){
	wp_register_script('siw_mcheckable_js', get_stylesheet_directory_uri() . '/assets/js/mCheckable.jquery.min.js', array('jquery'), '', TRUE);
	wp_enqueue_script('siw_mcheckable_js');
}


/*custom javascript/jQuery functies*/
function siw_custom_js(){
	wp_register_script('siw_custom_js', get_stylesheet_directory_uri() . '/assets/js/siw-scripts.js', array('jquery'), '', TRUE);
	$site_url = site_url();
	$parameters = array(
		'url' => $site_url,
	);
	wp_localize_script( 'siw_custom_js', 'parameters', $parameters );
	wp_enqueue_script('siw_custom_js');
	
}
add_action('wp_enqueue_scripts', 'siw_custom_js');

function siw_wc_checkout_scripts_js(){
	wp_register_script('siw-wc-checkout-scripts', get_stylesheet_directory_uri() . '/assets/js/siw-wc-checkout-scripts.js', array('jquery'), '', TRUE);
	$site_url = site_url();
	$parameters = array(
		'url' => $site_url,
	);
	wp_localize_script( 'siw-wc-checkout-scripts', 'parameters', $parameters );
}
add_action('wp_enqueue_scripts', 'siw_wc_checkout_scripts_js');


/*
Functies om scripts alleen te laden indien nodig
*/

add_action( 'wp_enqueue_scripts', 'siw_remove_unnecessary_scripts', 999 );
function siw_remove_unnecessary_scripts(){

	//variatie als radiobuttons
	if (! is_product() ){
		wp_dequeue_script('wc-add-to-cart-variation');
	}
	//woocommerce ajax filter
	if ((! is_shop()) && (! is_product_category()) && (! is_product_tag()) ){
		wp_dequeue_script('yith-wcan-script');
		wp_dequeue_style('yith-wcan-frontend');
	}

	//search&filter
	wp_dequeue_style('search-filter-chosen-styles');
	wp_dequeue_style('search-filter-plugin-styles');
	wp_deregister_script('search-filter-chosen-script' );
	wp_deregister_script( 'jquery-ui-datepicker' ); 
	
	wp_deregister_script('search-filter-plugin-build');
	wp_register_script( 'search-filter-plugin-build', plugins_url( 'search-filter-pro/public/assets/js/search-filter-build.min.js'), array('jquery'),'', TRUE);
	
	//kadence slider wordt alleen gebruikt op de homepage.
	if (! is_front_page() ){
		wp_dequeue_script('kadence_slider_js');
		wp_dequeue_style('kadence_slider_css');
	}
	
	//events calendar
	if ( (! tribe_is_event_query()) && (! tribe_is_event()) ){
		wp_dequeue_style('tribe-events-full-calendar-style');
		wp_dequeue_style('tribe-events-calendar-style');		
		wp_dequeue_style('tribe-events-calendar-full-mobile-style');	
		wp_dequeue_style('tribe-events-calendar-mobile-style');	
	}
	
	//ncf font
	wp_dequeue_style( 'ncf_lato_font' );
	
	//styling van mailpoet widget
	wp_deregister_style('validate-engine-css');
}

