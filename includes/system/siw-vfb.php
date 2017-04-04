<?php
/*
(c)2015-2016 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/* VFB-pro aanpassingen */
add_action('wp_enqueue_scripts', function() {
	global $wp_scripts;
	if ( $wp_scripts->registered['vfbp-js'] ){
		$wp_scripts->registered['vfbp-js']->src = get_stylesheet_directory_uri() . '/assets/js/vfb-pro/vfb-js.min.js';
	}
	if ( $wp_scripts->registered['jquery-intl-tel'] ){
		wp_register_script( 'jquery-phone-format', VFB_PLUGIN_URL . "public/assets/js/vendors/phone-format.min.js",array(), null, true);
		$wp_scripts->registered['jquery-intl-tel']->deps[] = 'jquery-phone-format';
	}
});

/* attachment verwijderen nadat deze per mail verstuurd is. */
add_action( 'vfbp_after_email', function( $entry_id, $form_id ) {
	global $wpdb;

	$attachments_args = array(
		'post_type'		=> 'attachment',
		'post_parent'	=> $entry_id,
		'fields'		=> 'ids'
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
}, 10, 2 );


add_action('siw_update_community_day_options', 'siw_update_community_day_options');
/**
 * Werk datum-opties in CD-formulier bij
 *
 * @return void
 */
function siw_update_community_day_options() {
	//haal cd-datums op
	for ($x = 1 ; $x <= SIW_NUMBER_OF_INFO_DAYS; $x++) {
		$community_days[]= siw_get_setting("info_day_{$x}");
	}
	asort( $community_days );
	$hide_form_days_before_cd = siw_get_setting('hide_application_form_days_before_info_day');
	$limit_date = date("Y-m-d", time() + ( $hide_form_days_before_cd * DAY_IN_SECONDS ));


	foreach ( $community_days as $community_day => $community_day_date ) {
		if ( $community_day_date >= $limit_date ) {
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

	$data = $wpdb->get_var( $wpdb->prepare( $query, $field_id ));
	$data = maybe_unserialize( $data );

	//update formuliervraag
	$data['options'] = array();
	if ( isset( $future_community_days ) ){
		$data['options'] = $future_community_days;
	}
	$query = "UPDATE $wpdb->vfbp_fields SET $wpdb->vfbp_fields.data = %s WHERE $wpdb->vfbp_fields.id = %d;";
	$wpdb->query(
		$wpdb->prepare( $query, maybe_serialize( $data ), $field_id )
	);

}
