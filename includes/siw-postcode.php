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

	$headers = array();
	$headers[] = 'X-Api-Key: '.$api_key;
	$url = 'https://postcode-api.apiwise.nl/v2/addresses/?postcode=' . str_replace(' ', '', $postcode) . '&number=' . $houseNumber;
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
	curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

	$json_response = curl_exec($curl);
	$response = json_decode($json_response);
	curl_close($curl);

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