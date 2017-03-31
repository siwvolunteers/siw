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
add_action('wppusher_theme_was_updated', function () {
	//vfb templates
	siw_update_vfb_mail_template('evs');
	siw_update_vfb_mail_template('op_maat');
	siw_update_vfb_mail_template('community_day');

	//cf7 templates
	siw_update_cf7_mail_template('algemeen');
	siw_update_cf7_mail_template('project');
	siw_update_cf7_mail_template('begeleider');

	//mailpoet bevestiging
	siw_update_mailpoet_mail_template();
}, 10, 1);

/**
 * Update VFB Pro e-mailtemplate
 *
 * @param string $form formulierslug
 *
 * @return void
 */
function siw_update_vfb_mail_template( $form ){

	$vfb_form_id = siw_get_vfb_form_id( $form );

	//haal mail-template
	global $wp_filesystem;
	$directory = $wp_filesystem->wp_themes_dir('siw');
	$filename =  $directory . "/siw/assets/html/mail/vfb_{$form}.html";
	$template = $wp_filesystem->get_contents( $filename );

	//ondertekening naam
	$signature_prefixes = array(
		'evs' 			=> 'evs',
		'op_maat' 		=> 'op_maat',
		'community_day'	=> 'info_day',
	);
	$signature_prefix = $signature_prefixes[ $form ];

	$signature_name = siw_get_setting("{$signature_prefix}_application_signature_name");
	$signature_title = siw_get_setting("{$signature_prefix}_application_signature_title");
	$template = str_replace("[_signature_{$form}_name]", $signature_name, $template );
	$template = str_replace("[_signature_{$form}_title]", $signature_title, $template );

	//update template
	global $wpdb;
	if (!isset( $wpdb->vfbp_formmeta )) {
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
	//haal template op
	global $wp_filesystem;
	$directory = $wp_filesystem->wp_themes_dir('siw');
	$filename =  $directory . '/siw/assets/html/mail/mailpoet.html';
	$template = $wp_filesystem->get_contents( $filename );
	$template = str_replace(array("\n\r", "\r", "\n"), '', $template );

	//update template
	global $wpdb;
	if (!isset( $wpdb->wysija_email )) {
		$wpdb->wysija_email = $wpdb->prefix . 'wysija_email';
	}
	$wpdb->query(
		$wpdb->prepare(
			"UPDATE $wpdb->wysija_email
			SET body = %s
				WHERE $wpdb->wysija_email.type  = 0",
			$template
        )
	);
}


/**
 * Update Contact Form 7 e-mailtemplate
 *
 * @param string $form formulierslug
 *
 * @return void
 */
function siw_update_cf7_mail_template( $form ) {

	//haal mail-templates op
	global $wp_filesystem;
	$directory = $wp_filesystem->wp_themes_dir('siw');

	//notificatie
	$filename_confirmation =  $directory."/siw/assets/html/mail/cf7_{$form}_bevestiging.html";
	$template_confirmation = $wp_filesystem->get_contents( $filename_confirmation );

	//bevestiging
	$filename_notification =  $directory."/siw/assets/html/mail/cf7_{$form}_notificatie.html";
	$template_notification = $wp_filesystem->get_contents( $filename_notification );

	//zoek formulier
	$post_id = siw_get_cf7_form_id( $form );

	//update templates
	global $post;
	//notificatie
	$notification_mail = get_post_meta( $post_id, '_mail', false ) ;
	$notification_mail['0']['body'] = $template_notification;
	update_post_meta( $post_id, '_mail', $notification_mail[0]);

	//bevestiging
	$confirmation_mail = get_post_meta( $post_id, '_mail_2', false ) ;
	$confirmation_mail['0']['body'] = $template_confirmation;
	update_post_meta( $post_id, '_mail_2', $confirmation_mail[0]);
}


/*
 * Bepaal ondertekening voor CF7 e-mails
 */
add_filter( 'wpcf7_special_mail_tags', function ( $output, $name, $html ) {
	$name = preg_replace( '/^wpcf7\./', '_', $name );

	$submission = WPCF7_Submission::get_instance();

	if ( ! $submission ) {
		return $output;
	}
	if ('_signature_algemeen' == $name) {
		$signature = siw_get_setting('enquiry_general_signature_name');
		return $signature;
	}
	if ('_signature_algemeen_functie' == $name) {
		$signature = siw_get_setting('enquiry_general_signature_title');
		return $signature;
	}
	if ('_signature_np' == $name) {
		$signature = siw_get_setting('enquiry_camp_leader_signature_name');
		return $signature;
	}
	if ('_signature_np_functie' == $name) {
		$signature = siw_get_setting('enquiry_camp_leader_signature_function');
		return $signature;
	}
	if ('_signature_project' == $name) {
		$signature = siw_get_setting('enquiry_workcamp_signature_name');
		return $signature;
	}
	if ('_signature_project_functie' == $name) {
		$signature = siw_get_setting('enquiry_workcamp_signature_function');
		return $signature;
	}
	return $output;
}, 10, 3 );


/*
 * Vervang site-url in CF7 e-mails
 */
add_filter( 'wpcf7_special_mail_tags', function ( $output, $name, $html ) {
	$name = preg_replace( '/^wpcf7\./', '_', $name );
	$submission = WPCF7_Submission::get_instance();

	if ( ! $submission ) {
		return $output;
	}
	if ('_site_url' == $name) {
		$site_url = site_url('', 'http' );
		return $site_url;
	}
	return $output;
}, 10, 3 );
