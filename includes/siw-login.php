<?php
/*
(c)2015 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


//login

add_action('login_head', 'siw_custom_login');
function siw_custom_login() {
	echo '<link rel="stylesheet" type="text/css" href="' . get_stylesheet_directory_uri()  . '/assets/css/siw-login-styles.css" />';
}

add_filter( 'login_headerurl', 'siw_login_logo_url' );
function siw_login_logo_url() {
	return get_home_url('','','http');
}

add_filter( 'login_headertitle', 'siw_login_logo_url_title' );
function siw_login_logo_url_title() {
	return 'SIW Internationale Vrijwilligersprojecten';
}

add_filter( 'login_message', 'siw_login_message' );
function siw_login_message( $message ) {
	if ( empty($message) ){
		return "<p class='message'>Welkom bij SIW. Log in om verder te gaan.</p>";
    } else {
		return $message;
    }
}

add_filter('login_errors', 'siw_failed_login');
function siw_failed_login() {
    return 'Incorrecte logingegevens.';
}