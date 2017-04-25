<?php
/*
(c)2015-2017 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
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
