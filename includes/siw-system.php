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


//permalink van testimonials aanpassen van 'testimonial' naar 'ervaring'
add_action( 'init', 'siw_change_permalink_structure' );
function siw_change_permalink_structure() {
	add_permastruct( 'wpm-testimonial', "ervaring/%wpm-testimonial%", array( 'slug' => 'ervaring' ) );
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
	$time = strtotime( 'tomorrow '.$db_backup_time);
	return $time;
}

function siw_backup_time_files(){
	$files_backup_time = siw_get_files_backup_time();
	$time = strtotime( 'tomorrow ' . $files_backup_time);
	return $time;
}






/*
VFB-pro aanpassingen
*/

add_action('wp_enqueue_scripts', 'siw_vfb_pro_scripts');
function siw_vfb_pro_scripts(){
	global $wp_scripts;
	if ($wp_scripts->registered['vfbp-js']){
		$wp_scripts->registered['vfbp-js']->src = get_stylesheet_directory_uri() . '/assets/js/vfb-pro/vfb-js.min.js';
	}
	if ($wp_scripts->registered['jquery-intl-tel']){	
		wp_enqueue_script( 'jquery-phone-format', VFB_PLUGIN_URL . "public/assets/js/vendors/phone-format.min.js",array(), null, true);	
	}
}

//CMB meta box url protocol-onafhankelijk maken
add_filter( 'cmb_meta_box_url', 'siw_cmb_meta_box_url', 10, 1 );

function siw_cmb_meta_box_url( $cmb_url ){
	$cmb_url = str_replace("http://", "//", $cmb_url);
    return $cmb_url;
}
     

/*
*/
add_action('admin_enqueue_scripts', 'siw_cmb_admin_scripts');
function siw_cmb_admin_scripts(){
	global $wp_scripts;
	if ($wp_scripts->registered['cmb-scripts']){
		$wp_scripts->registered['cmb-scripts']->src = get_stylesheet_directory_uri() . '/assets/js/kadence-slider/cmb.min.js';
	}
} 
/*dns-prefetch*/
add_action('wp_head','siw_dns_prefetch', 0);

function siw_dns_prefetch(){
	echo '
	<meta http-equiv="x-dns-prefetch-control" content="on">
	<link rel="dns-prefetch" href="//rum-static.pingdom.net"/>
	<link rel="dns-prefetch" href="//rum-collector.pingdom.net"/>
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