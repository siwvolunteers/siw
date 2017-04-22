<?php
/*
(c)2015-2017 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! function_exists('siw_get_setting') ) {
	function siw_get_setting( $setting ){
		global $siw;
		$value = '';
		if ( isset( $siw[ $setting ] ) ){
			$value = $siw[ $setting ];
		}
		return $value;
	}
}


//formulieren
function siw_get_vfb_field_id( $type ) {
	switch ($type) {
		case 'community_day_datums':
		$field_id = get_option( 'siw_community_day_vfb_dates_field' );
		return $field_id;
	}
}


function siw_get_vfb_form_id( $type ) {
	switch ($type) {
		case 'community_day':
			$form_id = get_option( 'siw_forms_community_day' );
			return $form_id;
		case 'evs':
			$form_id = get_option( 'siw_forms_evs' );
			return $form_id;
		case 'op_maat':
			$form_id = get_option( 'siw_forms_op_maat' );
			return $form_id;
	}
}

function siw_get_cf7_form_id( $type ) {
	switch ($type) {
		case 'algemeen':
			$form_id = get_option( 'siw_forms_algemeen' );
			return $form_id;
		case 'project':
			$form_id = get_option( 'siw_forms_project' );
			return $form_id;
		case 'begeleider':
			$form_id = get_option( 'siw_forms_begeleider' );
			return $form_id;
	}
}


//Dummy functies
if ( ! function_exists( 'siw_get_job_data' ) ) {
	function siw_get_job_data () {
		return;
	}
}
if ( ! function_exists( 'siw_get_event_data' ) ) {
	function siw_get_event_data () {
		return;
	}
}

//datum
if ( ! function_exists( 'siw_get_date_in_text' ) ) {
	function siw_get_date_in_text( $date, $year = true ) {
		$date_array = date_parse( $date );
		$month_array = siw_get_array( 'month_to_text' );
		$day = $date_array['day'];
		$month = $month_array[ $date_array['month'] ];
		$date_in_text = $day . ' ' . $month;
		if ( $year ) {
			$year = $date_array['year'];
			$date_in_text .=  ' ' . $year;
		}
		return $date_in_text;

	}
}

if ( ! function_exists( 'siw_get_date_range_in_text' ) ) {
	function siw_get_date_range_in_text ( $date_start, $date_end, $year = true ) {
		//als beide datums gelijk zijn gebruik dan siw_get_date_in_text
		if ( $date_start == $date_end){
			$date_range_in_text = siw_get_date_in_text( $date_start, $year );
		}
		else{
			$date_start_array = date_parse( $date_start );
			$date_end_array = date_parse( $date_end );
			$month_array = siw_get_array( 'month_to_text' );

			$date_range_in_text = $date_start_array['day'];
			if ( $date_start_array['month'] != $date_end_array['month']){
				$date_range_in_text .= ' ' . $month_array[ $date_start_array['month'] ];
			}
			if ( ($date_start_array['year'] != $date_end_array['year'] ) && $year ) {
				$date_range_in_text .= ' ' . $date_start_array['year'];
			}
			$date_range_in_text .= ' t/m ';
			$date_range_in_text .= $date_end_array['day'];
			$date_range_in_text .= ' ' . $month_array[ $date_end_array['month'] ];
			if ( $year ) {
				$date_range_in_text .= ' ' . $date_end_array['year'];
			}

		}
		return $date_range_in_text;

	}
}


function siw_get_array( $array ) {

	switch ( $array ) {

		case 'month_to_text':
			$month_to_text = array(
				'1'		=> 'januari',
				'2'		=> 'februari',
				'3'		=> 'maart',
				'4'		=> 'april',
				'5'		=> 'mei',
				'6'		=> 'juni',
				'7'		=> 'juli',
				'8'		=> 'augustus',
				'9'		=> 'september',
				'10'	=> 'oktober',
				'11'	=> 'november',
				'12'	=> 'december'
			);
			return $month_to_text;
	}
}
