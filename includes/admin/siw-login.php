<?php
/*
(c)2015-2016 SIW Internationale Vrijwilligersprojecten
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

add_action('login_head', 'siw_login_error');
function siw_login_error() {
	remove_action('login_head', 'wp_shake_js', 12);
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

add_filter('woocommerce_prevent_admin_access', 'siw_allow_admin_access');
function siw_allow_admin_access( $prevent_access ){
	if ( current_user_can( 'edit_products' ) || current_user_can( 'edit_jobs' ) || current_user_can( 'edit_events' ) ){
		$prevent_access = false;
	}
	return $prevent_access;
}


//logging van laatste login
add_action('wp_login','siw_capture_user_last_login', 10, 2);
function siw_capture_user_last_login( $user_login, $user ){
	update_user_meta($user->ID, 'last_login', current_time('mysql'));
}

//tonen laatste login op adminscherm
add_filter( 'manage_users_columns', 'siw_user_last_login_column_header');
function siw_user_last_login_column_header( $columns ){
	$columns['lastlogin'] = __('Laatste login', 'siw');
	return $columns;
}
 
add_action( 'manage_users_custom_column',  'siw_user_last_login_column_value', 10, 3); 
function siw_user_last_login_column_value($value, $column_name, $user_id ) {
	if ( 'lastlogin' == $column_name ){	
		$last_login = get_user_meta( $user_id, 'last_login', true);
		if( !empty( $last_login ) ){
			$time = mysql2date("H:i", $last_login, false);
			$date = siw_get_date_in_text( mysql2date("Y-m-d", $last_login, false), true);
			$value = $date . ' ' . $time;
		}
		else{
			$value = 'Nog nooit ingelogd';
		}
	}	
	return $value;
}


//password protect
add_action('password_protected_before_login_form', 'siw_password_protected_message');
function siw_password_protected_message(){
	echo"<p class='message'>Welkom op de testsite van SIW.<br/> Voer het wachtwoord in om toegang te krijgen. <br/> <br/> Klik <a href='//www.siw.nl'>hier</a> om naar de echte website van SIW te gaan.</p>";
}

add_action('password_protected_login_head', 'siw_password_protected_error');
function siw_password_protected_error() {
	remove_action('password_protected_login_head', 'wp_shake_js', 12);
}
