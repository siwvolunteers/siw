<?php
/*
(c)2015 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/*
VFB-pro aanpassingen
*/

add_action('wp_enqueue_scripts', 'siw_vfb_pro_scripts');
function siw_vfb_pro_scripts(){
	global $wp_scripts;
	if ( $wp_scripts->registered['vfbp-js'] ){
		$wp_scripts->registered['vfbp-js']->src = get_stylesheet_directory_uri() . '/assets/js/vfb-pro/vfb-js.min.js';
	}
	if ( $wp_scripts->registered['jquery-intl-tel'] ){
		wp_register_script( 'jquery-phone-format', VFB_PLUGIN_URL . "public/assets/js/vendors/phone-format.min.js",array(), null, true);	
		$wp_scripts->registered['jquery-intl-tel']->deps[] = 'jquery-phone-format';
	}
}

//attachment verwijderen nadat deze per mail verstuurd is.
add_action( 'vfbp_after_email', 'siw_delete_attachment_after_mail', 10, 2 );
function siw_delete_attachment_after_mail( $entry_id, $form_id ) {
	global $wpdb;

	$attachments_args = array(
		'post_type'		=> 'attachment',
		'post_parent'	=> $entry_id,
		'fields'			=> 'ids'
	);
	$attachments = get_posts( $attachments_args ); 
	foreach ( $attachments as $attachment ) {
		$attachment_url = wp_get_attachment_url( $attachment );
		wp_delete_attachment( $attachment );
		
		$wpdb->query(
			$wpdb->prepare(
				"UPDATE $wpdb->postmeta
				SET meta_value = 'gemaild'
					WHERE post_id = %d
					AND meta_value = %s;",
				$entry_id,
				$attachment_url
			)
		);
	}
}

//bijwerken van opties in aanmeldformulier CD
function siw_update_community_day_options(){

	//haal cd-datums op
	for ($x = 1 ; $x <= 9; $x++) {
		$community_days[]= get_option("siw_community_day_{$x}");
	}
	asort( $community_days );
	$hide_form_days_before_cd = siw_get_hide_form_days_before_cd();
	$limit_date = date("Y-m-d", strtotime( date("Y-m-d")."+" . $hide_form_days_before_cd . " days") );
	
	
	foreach($community_days as $community_day => $community_day_date) {
		if( $community_day_date >= $limit_date ){
			$future_community_days[]['label']= siw_get_date_in_text( $community_day_date, false);
		}
	}

	//zoek cd-formuliervraag
	$field_id = siw_get_vfb_field_id('community_day_datums');
	
	global $wpdb;
	if ( !isset($wpdb->vfbp_fields) ) {
		$wpdb->vfbp_fields = $wpdb->prefix . 'vfbp_fields';
	}
	
	$query = "SELECT $wpdb->vfbp_fields.data
				FROM $wpdb->vfbp_fields
				WHERE $wpdb->vfbp_fields.id = %d";
	
	$data = $wpdb->get_var( $wpdb->prepare( $query, $field_id));
	$data = maybe_unserialize( $data );
	
	//update formuliervraag
	$data['options'] = array();
	if ( isset( $future_community_days ) ){
		$data['options'] = $future_community_days;
	}
	$query = "update $wpdb->vfbp_fields set $wpdb->vfbp_fields.data = %s where $wpdb->vfbp_fields.id = %d;";
	$wpdb->query(
		$wpdb->prepare( $query, maybe_serialize( $data ), $field_id )
	);
	
}
