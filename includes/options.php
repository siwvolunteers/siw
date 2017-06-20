<?php
/*
(c)2015 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


add_action( 'admin_menu', function () {
	add_menu_page(
		__( 'Instellingen SIW', 'siw' ),
		__( 'Instellingen SIW', 'siw' ),
		'manage_options',
		'siw_settings',
		'siw_settings_page',
		'dashicons-admin-settings',
		110
	);
});


add_action( 'admin_init', function () {
	register_setting( 'siw_forms', 'siw_forms_community_day', 'absint' );
	register_setting( 'siw_forms', 'siw_forms_evs', 'absint' );
	register_setting( 'siw_forms', 'siw_forms_op_maat', 'absint' );
	register_setting( 'siw_forms', 'siw_community_day_vfb_dates_field', 'absint' );

	add_settings_section(
		'siw_forms',
		__( 'Formulieren', 'siw' ),
		'__return_false',
		'siw_forms'
	);
	add_settings_section(
		'siw_form_fields',
		__( 'Formuliervragen', 'siw' ),
		'__return_false',
		'siw_forms'
	);

	add_settings_field(
		'siw_forms_community_day',
		__( 'Community Day', 'siw' ),
		'siw_settings_show_vfb_form_select',
		'siw_forms',
		'siw_forms',
		'siw_forms_community_day'
	);
	add_settings_field(
		'siw_forms_evs',
		__( 'EVS', 'siw' ),
		'siw_settings_show_vfb_form_select',
		'siw_forms',
		'siw_forms',
		'siw_forms_evs'
	);
	add_settings_field(
		'siw_forms_op_maat',
		__( 'Op maat', 'siw' ),
		'siw_settings_show_vfb_form_select',
		'siw_forms',
		'siw_forms',
		'siw_forms_op_maat'
	);
	add_settings_field(
		'siw_community_day_vfb_dates_field',
		__( 'Datums', 'siw' ),
		'siw_settings_show_vfb_field',
		'siw_forms',
		'siw_form_fields',
		'siw_community_day_vfb_dates_field'
	);
});


function siw_settings_show_vfb_form_select( $option ) {
	global $wpdb;
	if ( ! isset( $wpdb->vfbp_forms ) ) {
		$wpdb->vfbp_forms = $wpdb->prefix . 'vfbp_forms';
	}

	$query = "SELECT $wpdb->vfbp_forms.id, $wpdb->vfbp_forms.title FROM $wpdb->vfbp_forms ORDER BY $wpdb->vfbp_forms.title ASC";

	$forms = $wpdb->get_results( $query, ARRAY_A );

	if ( ! empty( $forms ) ) {
		echo '<select name="', esc_attr( $option ), '">';
		foreach ( $forms as $form ) {
			echo '<option value="', esc_attr( $form['id'] ), '"', get_option( $option ) == $form['id'] ? ' selected="selected"' : '', '>', esc_html( $form['title'] ), '</option>';
		}
		echo '</select>';
	}
}




function siw_settings_show_vfb_field( $option ) {
	$form_id = siw_get_vfb_form_id( 'community_day' );

	global $wpdb;
	if ( ! isset( $wpdb->vfbp_fields ) ) {
		$wpdb->vfbp_fields = $wpdb->prefix . 'vfbp_fields';
	}

	$query = "SELECT $wpdb->vfbp_fields.id, $wpdb->vfbp_fields.data FROM $wpdb->vfbp_fields WHERE $wpdb->vfbp_fields.form_id = %d ORDER BY $wpdb->vfbp_fields.field_order ASC";
	$fields = $wpdb->get_results( $wpdb->prepare( $query, $form_id ), ARRAY_A );

	if( ! empty( $fields ) ) {
		echo '<select name="', esc_attr( $option ), '">';
		foreach ( $fields as $field ) {
			$id = $field['id'];
			$label = maybe_unserialize( $field['data'] )['label'];
			echo '<option value="', $id, '"', get_option( $option ) == $id ? ' selected="selected"' : '', '>', esc_html( $label ), '</option>';

		}
		echo '</select>';
	}
}



function siw_settings_page() {?>
	<div class="wrap">
		<h2><?php esc_html_e( 'Instellingen SIW', 'siw' );?></h2>
		<?php settings_errors();?>
		<form method="post" action="options.php">
			<?php
			settings_fields( 'siw_forms' );
			do_settings_sections( 'siw_forms' );
			submit_button();
			?>
		</form>
	</div><?php
}
