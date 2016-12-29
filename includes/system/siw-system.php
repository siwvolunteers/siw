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

//nonce-lifetime verdubbelen ivm cache
add_filter( 'nonce_life', function () { return 2 * DAY_IN_SECONDS; } );

//permalink van testimonials aanpassen van 'testimonial' naar 'ervaring'
add_action( 'init', 'siw_change_permalink_structure' );
function siw_change_permalink_structure() {
	add_permastruct( 'wpm-testimonial', "ervaring/%wpm-testimonial%", array( 'slug' => 'ervaring' ) );
}

//samenvatting toevoegen aan pagina's i.v.m. zoekfunctie
add_action( 'init', 'siw_add_excerpts_to_pages' );
function siw_add_excerpts_to_pages() {
	add_post_type_support( 'page', 'excerpt' );
}

//pdf en doc(x) upload toestaan
add_filter('upload_mimes', 'siw_custom_upload_mimes');

function siw_custom_upload_mimes( $existing_mimes=array() ){
	$existing_mimes['doc'] = 'application/msword'; 
	$existing_mimes['docx'] = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'; 
	$existing_mimes['pdf'] = 'application/pdf';	
	return $existing_mimes;
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
add_filter( 'emoji_svg_url', '__return_false' );


/*
	DNS-prefetch
*/
add_filter( 'wp_resource_hints', 'makewp_example_resource_hints', 10, 2 );
function makewp_example_resource_hints( $hints, $relation_type ) {
	if ( 'dns-prefetch' === $relation_type ) {
		//Google Analytics
		$hints[] = '//www.google-analytics.com';
		
		//Google Map
		$hints[] = '//maps.googleapis.com';
		$hints[] = '//maps.google.com';
		$hints[] = '//maps.gstatic.com';
		$hints[] = '//csi.gstatic.com';
		
		//Google Fonts
		$hints[] = '//fonts.googleapis.com';
		$hints[] = '//fonts.gstatic.com';	
	}
 
	return $hints;
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



//hulpfunctie t.b.v. logging
function siw_log( $content ){
	error_log(print_r($content,true),0);
}