<?php
/*
(c)2015 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/*
Functies voor export van aanmeldingen naar Plato
*/



//betaalde aanmeldingen exporteren naar PLATO
add_action( 'woocommerce_order_status_processing', 'siw_wc_export_application_to_plato' );

//export van aanmelding naar PLATO
function siw_wc_export_application_to_plato( $order_id){
	//ophalen aanmelding
	$order = new WC_Order( $order_id );
	
	//PLATO webkey en url ophalen
	$organization_webkey = siw_get_setting('plato_organization_webkey');
	$url = siw_get_setting('plato_webservice_url');

	if ('' == $organization_webkey or '' == $url){
		$order->add_order_note('Instellingen voor export naar PLATO ontbreken. Neem contact op met ICT-beheer.');		
		return;
	}
	
	//standaard http POST argumenten
	$args = array(
			'timeout'		=> 60,
			'redirection'	=> 0,
			'httpversion'	=> '1.0',
			'sslverify'		=> true,
			'blocking'		=> true,
			'headers'		=> array(
				'accept'		=> 'application/xml',
				'content-type'	=> 'application/x-www-form-urlencoded'
			),
			'cookies'		=> array(),
			'user-agent'	=> 'siw.nl',
		);
	//velden voor aanmelding
	$application_fields = siw_wc_get_application_fields_for_xml( $order );
	
	
	//elk project per aanmelding apart exporteren.
	$failed_count = 0;
	$success_count = 0;
	
	foreach( $order->get_items() as $item_id => $item_data ) {
		//bepaal projectcode
		$product = $order->get_product_from_item( $item_data );
		$projectcode = $product->get_sku();
		$application_fields['choice1'] = $projectcode;
		
		//xml opbouwen
		$xml = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><vef></vef>");
		foreach ($application_fields as $key => $value){
			$xml->addChild("$key","$value");
		}
		$xml_data = $xml->asXML();

		//bouw bericht voor webservice op
		$args['body']='organizationWebserviceKey=' . $organization_webkey . '&xmlData=' . rawurlencode( $xml_data );
		
		//roep webservice aan
		$response = wp_safe_remote_post($url,$args);
		
		//in het geval van een fout: foutmelding wegschrijven naar log
		if ( is_wp_error( $response ) ) {
			$order->add_order_note('Er is een fout opgetreden bij de export naar PLATO. Neem contact op met ICT-beheer');
			siw_log( $response );
			$failed_count++;
			break;
		}
		//zoek HTML-statuscode en breek af indien ongelijk aan 200
		$status_code = $response['response']['code'];
		if ('200' != $status_code ){
			$order->add_order_note('Verbinding met PLATO mislukt. Neem contact op met ICT-beheer.');
			$failed_count++;
			break;
		}
		
		$body = simplexml_load_string($response['body']);
		$success = (string) $body->Success;
		if ( 'true' == $success ){
			//bug in PLATO: imported_id geeft organizationWebserviceKey terug i.p.v. application_id
			//$imported_id = (string) $body->ImportedIds->string;
			$note = sprintf("Aanmelding voor %s succesvol geëxporteerd naar PLATO.", $projectcode );
			$order->add_order_note( $note );
			$success_count++;		
		}
		else {
			//foutmeldingen tonen bij order notes
			$error_messages = $body->ErrorMessages->string;
			$note = sprintf("Export naar PLATO van aanmelding voor %s mislukt.", $projectcode );
			foreach ($error_messages as $message){
				$note .= '<br />-' . (string)$message;
			}
			$order->add_order_note( $note );
			$failed_count++;			
		}
	}
	
	//resultaat opslaan bij aanmelding
	if (0 != $failed_count){
		update_post_meta( $order->id, '_exported_to_plato', 'failed');			
	}
	elseif (0 != $success_count){
		update_post_meta( $order->id, '_exported_to_plato', 'success');		
	}
	
}




function siw_wc_get_application_fields_for_xml( $order ) {

	//ophalen gegevens  
	$outgoing_placements_officer = siw_get_setting('plato_export_outgoing_placements_name');
	$outgoing_placements_email = siw_get_setting('plato_export_outgoing_placements_email');
	
	$firstname			= $order->billing_first_name;
	$lastname			= $order->billing_last_name;
	$sex				= $order->billing_gender;
	$birthdate			= date( 'Y-m-d', strtotime($order->billing_dob));
	$email				= $order->billing_email;
	$nationality		= $order->billing_nationality;
	$telephone 			= $order->billing_phone;
	$address1 			= $order->billing_address_1 . ' ' . $order->billing_housenumber;
	$zip 				= $order->billing_postcode;
	$city 				= $order->billing_city;
	$country 			= 'NLD'; //TODO
	$occupation 		= 'OTH';
	$emergency_contact	= get_post_meta($order->id, 'emergencyContactName', true) . ' ' . get_post_meta($order->id, 'emergencyContactPhone', true);
	$language1 			= get_post_meta( $order->id, 'language1', true );
	$language2 			= get_post_meta( $order->id, 'language2', true );
	$language3 			= get_post_meta( $order->id, 'language3', true );
	$langlevel1 		= get_post_meta( $order->id, 'language1Skill', true );
	$langlevel2			= get_post_meta( $order->id, 'language2Skill', true );
	$langlevel3 		= get_post_meta( $order->id, 'language3Skill', true );
	$special_needs 		= get_post_meta( $order->id, 'healthIssues', true );
	$experience			= get_post_meta( $order->id, 'volunteerExperience', true );
	$motivation 		= get_post_meta( $order->id, 'motivation', true );
	$together_with 		= get_post_meta( $order->id, 'togetherWith', true );

	return array(
		'firstname'			=> $firstname,
		'lastname'			=> $lastname,
		'sex' 				=> $sex,
		'birthdate'			=> $birthdate,
		'email' 			=> $email,
		'nationality'		=> $nationality,
		'telephone'			=> $telephone,
		'address1'			=> $address1,
		'zip'				=> $zip,
		'city'				=> $city,
		'country'			=> $country,
		'occupation'		=> $occupation,
		'emergency_contact'	=> $emergency_contact,
		'language1'			=> $language1,
		'language2'			=> $language2,
		'language3'			=> $language3,
		'langlevel1'		=> $langlevel1,
		'langlevel2'		=> $langlevel2,
		'langlevel3'		=> $langlevel3,
		'special_needs'		=> $special_needs,
		'experience'		=> $experience,
		'motivation'		=> $motivation,
		'together_with'		=> $together_with,
		'req_sent_by'		=> $outgoing_placements_officer,
		'req_sender_email'	=> $outgoing_placements_email,
		'date_filed'		=> date( 'Y-m-d')
	);
}
