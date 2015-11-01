<?php 
/*
(c)2015 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_action( 'wp_ajax_nopriv_postcode_lookup', 'siw_postcode_lookup' );
add_action( 'wp_ajax_postcode_lookup', 'siw_postcode_lookup' );

function siw_postcode_lookup() {

	$api_key = siw_get_postcode_api_key();
	define('APIKEY', $api_key);

	$postcode = strtoupper(siw_strip_url($_GET['postcode']));
	$houseNumber = siw_strip_url($_GET['housenumber']);
   
    $url = 'http://api.postcodeapi.nu/' . str_replace(' ', '', $postcode) . '/' . $houseNumber . '/';
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Api-Key: ' . APIKEY));
        
    $return_data = curl_exec($ch);
    curl_close($ch);
        
	//$json = json_decode($return_data, true);
	echo $return_data;	
	die();

}
   
function siw_strip_url( $title , $seperator = '-' ){
    $title = preg_replace( '/[^a-z0-9\s]/i' , '' , $title );

    if (!empty($title) && strlen($title) <= 6)    
        return $title;
    else
        return false;
}