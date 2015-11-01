<?php
/*
(c)2015 SIW Internationale Vrijwilligersprojecten
*/

/*
AJAX-handler
*/

define('DOING_AJAX', true);
if (!isset( $_REQUEST['action']))
	die('-1');

require_once('../../../wp-load.php');

//zet hedaders
@header( 'Content-Type: text/html; charset=' . get_option( 'blog_charset' ) );
@header( 'X-Robots-Tag: noindex' );
send_nosniff_header();
nocache_headers();


//actie uit request halen
$action = esc_attr(trim($_REQUEST['action']));

//toegestande acties
$allowed_actions = array(
	'postcode_lookup'
);

//uitvoeren toegestane actie
if(in_array($action, $allowed_actions)) {
	if(is_user_logged_in())
		do_action('siw_ajax_'.$action);
	else
		do_action('siw_ajax_nopriv_'.$action);
} else {
	die('-1');
}