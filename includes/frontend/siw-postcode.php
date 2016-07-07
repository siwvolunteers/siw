<?php 
/*
(c)2015 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_filter( 'siw_ajax_allowed_actions', function($actions){
	$actions[]='postcode_lookup';
	return $actions;
});

add_action( 'siw_ajax_postcode_lookup', 'siw_postcode_lookup' );
function siw_postcode_lookup() {

	$api_key = siw_get_postcode_api_key();
	$postcode = strtoupper(siw_strip_url($_GET['postcode']));
	$houseNumber = siw_strip_url($_GET['housenumber']);

	$url = 'https://postcode-api.apiwise.nl/v2/addresses/?postcode=' . str_replace(' ', '', $postcode) . '&number=' . $houseNumber;
	$args = array(
		'timeout'		=> 10,
		'redirection'	=> 0,
		'headers'		=> array(
			'X-Api-Key'	=> $api_key,
			),
	);
	$response = json_decode( wp_safe_remote_get( $url, $args )['body'] );

	if ($response->_embedded->addresses){
		$street = $response->_embedded->addresses[0]->street;
		$town = $response->_embedded->addresses[0]->city->label;
		$data =  array('success' => 1, 'resource'=>array( 'street' => $street, 'town' => $town));
	}
	else{
		$data = array('success' => 0);
	}
	$result = json_encode($data);	
	echo $result;	
	die();
}
   
function siw_strip_url( $title , $seperator = '-' ){
    $title = preg_replace( '/[^a-z0-9\s]/i' , '' , $title );

    if (!empty($title) && strlen($title) <= 6)    
        return $title;
    else
        return false;
}