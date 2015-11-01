<?php
/*
(c)2015 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//Volgende EVS deadline
add_shortcode('siw_evs_volgende_deadline', 'siw_shortcode_evs_next_deadline');
function siw_shortcode_evs_next_deadline() {
	$evs_next_deadline = date_parse(siw_get_evs_next_deadline());	
	$month_array = siw_get_array('month_to_text');
	$day = $evs_next_deadline['day'];
	$month = $month_array[$evs_next_deadline['month']];
	$year = $evs_next_deadline['year'];
	$evs_next_deadline = $day . ' ' . $month . ' ' . $year;
	
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