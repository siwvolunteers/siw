<?php
/*
(c)2015 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//SIW shortcodes toevoegen aan pinnacle shortcodes
add_filter('kadence_shortcodes', 'siw_shortcodes');

function siw_shortcodes( $pinnacle_shortcodes ){

	$pinnacle_shortcodes['siw_algemene_informatie'] = array(
		'title'	=>	__('SIW: Algemene informatie', 'siw'), 
		'attr'	=>	array(
			'type' => array(
				'type'=>'select', 
				'title'=>__('Type', 'siw'),
				'values' => array(
					"email"		=> __('E-mail','siw'),
					"iban"		=> __('IBAN','siw'),
					"kvk"		=> __('KvK-nummer','siw'),
					"telefoon"	=> __('Telefoonnummer','siw'),
				),
			),
		),
	);

	$pinnacle_shortcodes['siw_evs_volgende_deadline'] = array(
		'title' =>	__('SIW: Volgende EVS deadline', 'siw'), 
	);
	$pinnacle_shortcodes['siw_evs_volgende_vertrekmoment'] = array(
		'title' =>	__('SIW: Volgende EVS-vertrekmoment', 'siw'), 
	);	
	$pinnacle_shortcodes['siw_evs_borg'] = array( 
		'title'=>__('SIW: EVS borg', 'siw'), 
	);
	$pinnacle_shortcodes['siw_volgende_community_day'] = array( 
		'title'=>__('SIW: Volgende Community Day', 'siw'), 
	);
	$pinnacle_shortcodes['siw_inschrijfgeld_op_maat'] = array(
		'title'	=>	__('SIW: Inschrijfgeld project op maat', 'siw'), 
		'attr'	=>	array(
			'tarief' => array(
				'type'=>'select', 
				'title'=>__('Tarief', 'siw'),
				'values' => array(
					"student" => __('Student','siw'),
					"regulier" => __('Regulier','siw'),

				),
			),
		),
	);
	$pinnacle_shortcodes['siw_inschrijfgeld_groepsproject'] = array(
		'title'	=>	__('SIW: Inschrijfgeld groepsproject', 'siw'), 
		'attr'	=>	array(
			'tarief' => array(
				'type'=>'select', 
				'title'=>__('Tarief', 'siw'),
				'values' => array(
					"student" => __('Student','siw'),
					"regulier" => __('Regulier','siw'),

				),
			),
		),
	);
	$pinnacle_shortcodes['siw_korting_groepsproject'] = array(
		'title'	=>	__('SIW: Korting groepsproject', 'siw'), 
		'attr'	=>	array(
			'aantal' => array(
				'type'=>'select', 
				'title'=>__('Aantal', 'siw'),
				'values' => array(
					"tweede" => __('2e','siw'),
					"derde" => __('3e','siw'),

				),
			),
		),
	);	
	return $pinnacle_shortcodes;
}


//volgende community day
add_shortcode('siw_volgende_community_day', 'siw_shortcode_next_community_day');
function siw_shortcode_next_community_day() {
	$next_community_day = siw_get_date_in_text( siw_get_next_community_day(), false );
	return $next_community_day;
}

//Volgende EVS deadline
add_shortcode('siw_evs_volgende_deadline', 'siw_shortcode_evs_next_deadline');
function siw_shortcode_evs_next_deadline() {
	$evs_next_deadline = siw_get_date_in_text( siw_get_evs_next_deadline(), true );	
	return $evs_next_deadline;
}

//Volgende EVS vertrekmoment
add_shortcode('siw_evs_volgende_vertrekmoment', 'siw_shortcode_evs_next_start');
function siw_shortcode_evs_next_start() {
	$evs_next_deadline = siw_get_evs_next_deadline();
	$month_array = siw_get_array('month_to_text');
	$months = 3;
	$weeks = 1;
	$evs_next_start = date("Y-m-d",strtotime($evs_next_deadline."+".$months." months " . $weeks . " weeks"));	
	$evs_next_start = date_parse($evs_next_start);
	$month = $month_array[$evs_next_start['month']];
	$year = $evs_next_start['year'];
	$evs_next_start = $month . ' ' . $year;	

	return $evs_next_start;
}

add_shortcode('siw_evs_borg', 'siw_shortcode_evs_deposit');
function siw_shortcode_evs_deposit(){
	$evs_deposit = siw_get_evs_deposit();
	return $evs_deposit;
}

add_shortcode('siw_inschrijfgeld_op_maat', 'siw_shortcode_fee_op_maat');
function siw_shortcode_fee_op_maat( $args ){
	$attributes = shortcode_atts(
		array(
			'tarief' => 'regulier',
		),
	$args);
	$tariff = $attributes ['tarief'];

	$fee = siw_get_fee_op_maat( $tariff );
	return $fee;
}

add_shortcode('siw_inschrijfgeld_groepsproject', 'siw_shortcode_fee_workcamp');
function siw_shortcode_fee_workcamp( $args ){
    $attributes = shortcode_atts(
		array(
			'tarief' => 'regulier',
		),
	$args);
	$tariff = $attributes ['tarief'];

	$fee = siw_get_fee_workcamp( $tariff );
	return $fee;
}


//korting 
add_shortcode('siw_korting_groepsproject', 'siw_shortcode_discount_workcamp');
function siw_shortcode_discount_workcamp( $args ){
	$attributes = shortcode_atts(
		array(
			'aantal' => 'tweede',
		),
	$args);	
	$soort = $attributes ['aantal'];
	
	$type = array(
		'tweede'	=> 'second',
		'derde'		=> 'third',
	);
	$discount = siw_get_discount_workcamp( $type[ $soort ] );
	return $discount;
}

//algemene informatie

add_shortcode('siw_algemene_informatie', 'siw_shortcode_general_information');
function siw_shortcode_general_information( $args ){
	$attributes = shortcode_atts(
		array(
			'type' => '',
		),
	$args);
	$type = $attributes ['type'];

	$information = siw_get_general_information( $type );
	return $information;
}

/*break*/
function siw_shortcode_br() {
	return '<br>';
}
add_shortcode( 'br', 'siw_shortcode_br' );