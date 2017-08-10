<?php
/*
(c)2015-2017 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * Update alle mailtemplates na theme-update
 */
add_action( 'wppusher_theme_was_updated', function () {
	//vfb templates
	siw_update_vfb_mail_template( 'evs' );
	siw_update_vfb_mail_template( 'op_maat' );
	siw_update_vfb_mail_template( 'community_day' );

}, 10, 1);

/**
 * Update VFB Pro e-mailtemplate
 *
 * @param string $form formulierslug
 *
 * @return void
 */
function siw_update_vfb_mail_template( $form ) {

	$vfb_form_id = siw_get_vfb_form_id( $form );

	//haal mail-template
	global $wp_filesystem;
	$directory = $wp_filesystem->wp_themes_dir( 'siw' );
	$filename =  $directory . "/siw/assets/html/mail/vfb_{$form}.html";
	$template = $wp_filesystem->get_contents( $filename );

	//ondertekening naam
	$signature_prefixes = array(
		'evs' 			=> 'evs',
		'op_maat' 		=> 'op_maat',
		'community_day'	=> 'info_day',
	);
	$signature_prefix = $signature_prefixes[ $form ];

	$signature_name = SIW_PLUGIN::siw_get_setting( "{$signature_prefix}_application_signature_name" );
	$signature_title = SIW_PLUGIN::siw_get_setting( "{$signature_prefix}_application_signature_title" );
	$template = str_replace( "[_signature_{$form}_name]", $signature_name, $template );
	$template = str_replace( "[_signature_{$form}_title]", $signature_title, $template );

	//update template
	global $wpdb;
	if ( ! isset( $wpdb->vfbp_formmeta ) ) {
		$wpdb->vfbp_formmeta = $wpdb->prefix . 'vfbp_formmeta';
	}
	$wpdb->query(
		$wpdb->prepare(
			"UPDATE $wpdb->vfbp_formmeta
			SET meta_value = %s
				WHERE $wpdb->vfbp_formmeta.meta_key  = 'email-template'
				AND $wpdb->vfbp_formmeta.form_id = %d",
			$template,
			$vfb_form_id
		)
	);
}


/**
 * Update Mailpoet e-mailtemplate bevestingsmail
 *
 * @return void
 */
function siw_update_mailpoet_mail_template() {
	return;
}


/**
 * Update Contact Form 7 e-mailtemplate
 *
 * @param string $form formulierslug
 *
 * @return void
 */
function siw_update_cf7_mail_template( $form ) {
	return;
}
