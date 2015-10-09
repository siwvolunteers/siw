<?php
/*
(c)2015 SIW Internationale Vrijwilligersprojecten
*/

/*
Functies voor export van aanmeldingen naar Plato
*/


//definieer root-element (vef)
add_filter( 'wc_customer_order_xml_export_suite_order_export_format', 'siw_wc_define_xml_root_element', 10, 2 );
function siw_wc_define_xml_root_element( $orders_format, $orders ) {
	$orders_format = array(
		'vef' => $orders,
	);
	return $orders_format;
}

//definieer xml-structuur
add_filter( 'wc_customer_order_xml_export_suite_order_export_order_list_format', 'siw_wc_define_xml_structure', 10, 2 );

function siw_wc_define_xml_structure( $order_format, $order ) {

	//ophalen gegevens
	$outgoing_placements_officer = siw_get_outgoing_placements_officer();
	$outgoing_placements_email = siw_get_outgoing_placements_email();
	
	$firstname		= $order->billing_first_name;
	$lastname		= $order->billing_last_name;
	$sex			= $order->billing_gender;
	$birthdate		= date( 'Y-m-d', strtotime($order->billing_dob));
	$email			= $order->billing_email;
	$nationality	= $order->billing_nationality;
	$telephone 		= $order->billing_phone;
	$address1 		= $order->billing_address_1 . ' ' . $order->billing_housenumber;
	$zip 			= $order->billing_postcode;
	$city 			= $order->billing_city;
	$country 		= $order->billing_country;//TODO
	$occupation 	= 'OTH';
	$emergency_contact = get_post_meta($order->id, 'emergencyContactName', true) . ' ' . get_post_meta($order->id, 'emergencyContactPhone', true);
	$language1 		= get_post_meta( $order->id, 'language1', true );
	$language2 		= get_post_meta( $order->id, 'language2', true );
	$language3 		= get_post_meta( $order->id, 'language3', true );
	$langlevel1 	= get_post_meta( $order->id, 'language1Skill', true );	
	$langlevel2		= get_post_meta( $order->id, 'language2Skill', true );	
	$langlevel3 	= get_post_meta( $order->id, 'language3Skill', true );
	$special_needs 	= get_post_meta( $order->id, 'healthIssues', true );	
	$experience_text =get_post_meta( $order->id, 'volunteerExperience', true );
	$motivation 	= get_post_meta( $order->id, 'motivation', true );
	$together_with 	= get_post_meta( $order->id, 'togetherWith', true );	
	

	//bepaal projectcode
	foreach( $order->get_items() as $item_id => $item_data ) {
		$product = $order->get_product_from_item( $item_data );
		$projectcode = $product->get_sku();
	}

	return array(
		'firstname' => $firstname,
		'lastname' => $lastname,
		'sex' => $sex,
		'birthdate' => $birthdate,
		'email' => $email,
		'nationality' => $nationality,
		'telephone' => $telephone,
		'address1' => $address1,
		'zip' => $zip,
		'city' => $city,
		'country' => $country,//TODO
		'occupation' => $occupation,
		'emergency_contact' => $emergency_contact,
		'choice1' => $projectcode,
		'language1' => $language1,
		'language2' => $language2,
		'language3' =>	$language3,	
		'langlevel1' => $langlevel1,	
		'langlevel2' => $langlevel2,	
		'langlevel3' => $langlevel3,
		'special_needs' => $special_needs,	
		'experience_text' =>$experience_text,
		'motivation' => $motivation,
		'together_with' => $together_with,	
		'req_sent_by' => $outgoing_placements_officer,
		'req_sender_email'  => $outgoing_placements_email,
		'date_filed'  => date( 'Y-m-d')
	);
}


//zet http post argumenten voor export van aanmeldingen naar Plato
add_filter( 'wc_customer_order_xml_export_suite_http_post_args', 'siw_wc_set_http_post_arguments_for_application_export' );

function siw_wc_set_http_post_arguments_for_application_export( $args ) {
	$organization_webkey = siw_get_plato_organization_webkey();
	$xml = $args['body'];
	$args['headers']['content-type'] = 'application/x-www-form-urlencoded';
	$args['headers']['accept'] = 'text/html';
	$args['body']='organizationWebserviceKey=' . $organization_webkey . '&xmlData=' . rawurlencode( $xml );
	return $args;
}

//exporteer aanmeldingen zodra de status in processing (betaald) is. TODO: Conditioneel maken als er niet-PLATO-projecten mogelijk zijn
add_action( 'woocommerce_order_status_processing', 'siw_wc_export_paid_applications' );

function siw_wc_export_paid_applications( $order_id ) {
	$export = new WC_Customer_Order_XML_Export_Suite_Handler( $order_id );
	$export->http_post();
}
