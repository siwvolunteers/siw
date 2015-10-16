<?php 
/*
(c)2015 SIW Internationale Vrijwilligersprojecten
*/
require_once('siw-get-information.php');


$api_key = siw_get_postcode_api_key();
define('APIKEY', $api_key);

   
function strip4url( $title , $seperator = '-' ){
    $title = preg_replace( '/[^a-z0-9\s]/i' , '' , $title );

    if (!empty($title) && strlen($title) <= 6)    
        return $title;
    else
        return false;
}

if(basename($_SERVER['PHP_SELF']) == basename(__FILE__)):
    
    if($_SERVER['REQUEST_METHOD'] == 'GET'):
	
	$postcode = strtoupper(strip4url($_GET['postcode']));
	$houseNumber = strip4url($_GET['housenumber']);
   
    $url = 'http://api.postcodeapi.nu/' . str_replace(' ', '', $postcode) . '/' . $houseNumber . '/';
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Api-Key: ' . APIKEY));
        
    $return_data = curl_exec($ch);
    curl_close($ch);
        
	$json = json_decode($return_data, true);
	print $return_data;

    else:
        header("Location: /");

    endif;

else:
    header("Location: /");

endif;


