<?php
/*
(c)2015 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//login
add_action( 'login_enqueue_scripts', 'siw_login_css');
function siw_login_css(){
	wp_enqueue_style( 'siw-login-css', get_stylesheet_directory_uri() . '/assets/css/siw-login-styles.css', array(), wp_get_theme()->version );
}

add_filter( 'login_headerurl', 'siw_login_headerurl' );
function siw_login_headerurl() {
	return get_home_url('','','http');
}

add_filter( 'login_headertitle', 'siw_login_headertitle' );
function siw_login_headertitle() {
	return 'SIW Internationale Vrijwilligersprojecten';
}

add_filter( 'login_message', 'siw_login_message' );
function siw_login_message( $message ) {
	if ( empty( $message ) ){
		return "<p class='message'>Welkom bij SIW. Log in om verder te gaan.</p>";
	} else {
		return $message;
	}
}

//whitelisten ip's
add_filter('limit_login_whitelist_ip', 'siw_login_ip_whitelist', 10, 2);
function siw_login_ip_whitelist( $allow, $ip ) {
	$ip_whitelist = siw_get_ip_whitelist();
	if ( in_array( $ip, $ip_whitelist ) ){
		$allow = true;
	}
	return $allow;
}