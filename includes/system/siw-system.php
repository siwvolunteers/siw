<?php
/*
(c)2015 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/*
Extra functies
*/
//aantal toegestane redirects aanpassen
add_filter( 'srm_max_redirects', function() { return 250; } );

//permalink van testimonials aanpassen van 'testimonial' naar 'ervaring'
add_action( 'init', 'siw_change_permalink_structure' );
function siw_change_permalink_structure() {
	add_permastruct( 'wpm-testimonial', "ervaring/%wpm-testimonial%", array( 'slug' => 'ervaring' ) );
}


add_action( 'init', 'my_add_excerpts_to_pages' );

function my_add_excerpts_to_pages() {
	add_post_type_support( 'page', 'excerpt' );
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

//pdf en doc(x) upload toestaan
add_filter('upload_mimes', 'siw_custom_upload_mimes');

function siw_custom_upload_mimes( $existing_mimes=array() ){
	$existing_mimes['doc'] = 'application/msword'; 
	$existing_mimes['docx'] = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'; 
	$existing_mimes['pdf'] = 'application/pdf';	
	return $existing_mimes;
}

//attachment verwijderen nadat deze per mail verstuurd is.
add_action( 'vfbp_after_email', 'siw_delete_attachment_after_mail', 10, 2 );
function siw_delete_attachment_after_mail( $entry_id, $form_id ) {
	global $wpdb;

	$attachments_args = array(
		'post_type' 	=> 'attachment',
		'post_parent'	=> $entry_id,
		'fields' 		=> 'ids'
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


/*disable emojis*/
add_action( 'init', 'siw_disable_wp_emojicons' );
function siw_disable_wp_emojicons() {
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	add_filter( 'tiny_mce_plugins', 'siw_disable_emojicons_tinymce' );
}

function siw_disable_emojicons_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, array( 'wpemoji' ) );
	}
	else {
		return array();
	}
}



/*instellen starttijd Updraft Plus backup*/
add_filter('updraftplus_schedule_firsttime_db', 'siw_backup_time_db');
add_filter('updraftplus_schedule_firsttime_files', 'siw_backup_time_files');

function siw_backup_time_db(){
	$db_backup_time = siw_get_db_backup_time();
	$time = strtotime( 'tomorrow '.$db_backup_time );
	return $time;
}

function siw_backup_time_files(){
	$files_backup_time = siw_get_files_backup_time();
	$time = strtotime( 'tomorrow ' . $files_backup_time );
	return $time;
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

//CMB meta box url protocol-onafhankelijk maken
add_filter( 'cmb_meta_box_url', 'siw_cmb_meta_box_url', 10, 1 );

function siw_cmb_meta_box_url( $cmb_url ){
	$cmb_url = str_replace("http://", "//", $cmb_url );
    return $cmb_url;
}


//afbeelding in admin over ssl i.v.m. mixed content
add_filter('wp_calculate_image_srcset', 'siw_set_image_srcset_to_ssl');
function siw_set_image_srcset_to_ssl($sources) {
	if ( is_ssl() ){
		foreach($sources as $size => &$source){
			$source['url'] = set_url_scheme( $source['url'] ,'https');
		}
	}
	return $sources;
}

/*
*/
add_action('admin_enqueue_scripts', 'siw_cmb_admin_scripts');
function siw_cmb_admin_scripts(){
	global $wp_scripts;
	if ( $wp_scripts->registered['cmb-scripts'] ){
		$wp_scripts->registered['cmb-scripts']->src = get_stylesheet_directory_uri() . '/assets/js/kadence-slider/cmb.min.js';
	}
} 
/*dns-prefetch*/
add_action('wp_head','siw_dns_prefetch', 0);

function siw_dns_prefetch(){
	echo '
	<meta http-equiv="x-dns-prefetch-control" content="on">
	<link rel="dns-prefetch" href="//www.google-analytics.com"/>
	<link rel="dns-prefetch" href="//fonts.googleapis.com"/>
	<link rel="dns-prefetch" href="//fonts.gstatic.com"/>
	<link rel="dns-prefetch" href="//maps.googleapis.com"/>
	<link rel="dns-prefetch" href="//maps.google.com"/>
	<link rel="dns-prefetch" href="//maps.gstatic.com"/>
	<link rel="dns-prefetch" href="//csi.gstatic.com"/>
	<link rel="dns-prefetch" href="//mts1.googleapis.com"/>
	<link rel="dns-prefetch" href="//mts0.googleapis.com"/>
	';
}

//auteurinfo verwijderen uit oembed
add_filter('oembed_response_data', 'siw_oembed_response_data', 10, 1);
function siw_oembed_response_data( $data ){
	if ( isset ( $data['author_name'] ) ){
		unset( $data['author_name'] );
	}
	if ( isset ( $data['author_url'] ) ){
		unset( $data['author_url'] );
	}
	return $data;
}

//cd-opties

function siw_update_community_day_options(){

	//haal cd-datums op
	$community_days[]= get_option('siw_community_day_1');
	$community_days[]= get_option('siw_community_day_2');
	$community_days[]= get_option('siw_community_day_3');
	$community_days[]= get_option('siw_community_day_4');
	$community_days[]= get_option('siw_community_day_5');
	$community_days[]= get_option('siw_community_day_6');
	$community_days[]= get_option('siw_community_day_7');
	$community_days[]= get_option('siw_community_day_8');
	$community_days[]= get_option('siw_community_day_9');
	asort( $community_days );
	
	$today = date("Y-m-d");
	foreach($community_days as $community_day => $community_day_date) {
		if( $community_day_date > $today ){
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
	$data['options'] = $future_community_days;
	$query = "update $wpdb->vfbp_fields set $wpdb->vfbp_fields.data = %s where $wpdb->vfbp_fields.id = %d;";
	$wpdb->query(
		$wpdb->prepare( $query, maybe_serialize( $data ), $field_id )
	);
	
}
