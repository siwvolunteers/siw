<?php
/*
(c)2015-2016 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


//portfolio permalinks aanpassen
add_filter('kadence_portfolio_type_slug', 'siw_portfolio_type_slug');
function siw_portfolio_type_slug(){
	return 'projecten-op-maat-in';
}
add_filter('kadence_portfolio_tag_slug', 'siw_portfolio_tag_slug');
function siw_portfolio_tag_slug(){
	return 'projecten-op-maat-per-tag';
}

//vrijwilligers permalinks aanpassen
add_filter('kadence_staff_post_slug', 'siw_staff_post_slug');
function siw_staff_post_slug(){
	return 'vrijwilligers';
}
add_filter('kadence_staff_group_slug', 'siw_staff_group_slug');
function siw_staff_group_slug(){
	return 'vrijwilligers-per-groep';
}

//capabilites voor op maat projecten
add_filter( 'kadence_portfolio_capability_type', function(){
	return 'op_maat_project';
});
add_filter( 'kadence_portfolio_map_meta_cap', function() {
	return true;
});

//capabilites voor quotes
add_filter( 'kadence_testimonial_capability_type', function(){
	return 'quote';
});
add_filter( 'kadence_testimonial_map_meta_cap', function() {
	return true;
});

//capabilites voor vrijwilligers
add_filter( 'kadence_staff_capability_type', function(){
	return 'volunteer';
});
add_filter( 'kadence_staff_map_meta_cap', function() {
	return true;});


//capabilites voor Contact Form 7
add_filter('wpcf7_map_meta_cap', function( $meta_caps ) {
	$meta_caps['wpcf7_edit_contact_form'] = 'manage_options';
	$meta_caps['wpcf7_edit_contact_forms'] = 'manage_options';
	$meta_caps['wpcf7_read_contact_forms'] = 'manage_options';
	$meta_caps['wpcf7_delete_contact_form'] = 'manage_options';	
	return $meta_caps;
});