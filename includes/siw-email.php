<?php 
/*
(c)2015 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//update alle mailtemplates na theme-update
add_action('wppusher_theme_was_updated', 'siw_update_all_mail_templates', 10, 1);

function siw_update_all_mail_templates(){
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
}

//update vfb mailtemplates

function siw_update_vfb_mail_template($form){

	$vfb_form_titles = array(
		'evs' 			=>	'EVS',
		'op_maat' 		=>	'Vrijwilligerswerk op maat',
		'community_day'	=>	'Aanmelden SIW Community Day',
	);
	//haal mail-template
	global $wp_filesystem;
	$directory = $wp_filesystem->wp_themes_dir('siw');
	$filename =  $directory . "/siw/assets/html/mail/vfb_{$form}.html";
	$template = $wp_filesystem->get_contents( $filename );
		
	//ondertekening naam
	$signature = siw_get_mail_signature_name("aanmelding_{$form}");
	$template = str_replace("[_signature_{$form}]", $signature, $template);

	//update template
	global $wpdb;
	if (!isset($wpdb->vfbp_forms)) {
		$wpdb->vfbp_forms = $wpdb->prefix . 'vfbp_forms';
	}
	if (!isset($wpdb->vfbp_formmeta)) {
		$wpdb->vfbp_formmeta = $wpdb->prefix . 'vfbp_formmeta';
	}
	$wpdb->query(
		$wpdb->prepare(
			"UPDATE $wpdb->vfbp_formmeta
			SET meta_value = %s
				WHERE $wpdb->vfbp_formmeta.meta_key  = 'email-template'
				AND $wpdb->vfbp_formmeta.form_id = (SELECT $wpdb->vfbp_forms.id 
														FROM   $wpdb->vfbp_forms 
														WHERE  $wpdb->vfbp_forms.title = %s)",
			$template,
			$vfb_form_titles[ $form ]
        )
	);
}


//update mailpoet template

function siw_update_mailpoet_mail_template(){
	//haal template op
	global $wp_filesystem;
	$directory = $wp_filesystem->wp_themes_dir('siw');
	$filename =  $directory . '/siw/assets/html/mail/mailpoet.html';
	$template = $wp_filesystem->get_contents($filename);
	
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


//update cf7 emails

function siw_update_cf7_mail_template($form){

	$cf7_form_titles = array(
		'algemeen' 			=>	'Contactformulier algemeen',
		'project' 		=>	'Contactformulier product',
		'begeleider'	=>	'Projectbegeleider',
	);

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
	global $wpdb;
	$post_id_query = "SELECT ID from $wpdb->posts WHERE $wpdb->posts.post_title = %s AND $wpdb->posts.post_type = 'wpcf7_contact_form'";
	$post_id = $wpdb->get_var($wpdb->prepare( $post_id_query, $cf7_form_titles[$form]));
	
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




/*Bepaal ondertekening voor contact-mails*/
add_filter( 'wpcf7_special_mail_tags', 'siw_cf7_set_mail_signature', 10, 3 );

function siw_cf7_set_mail_signature( $output, $name, $html ) {
	$name = preg_replace( '/^wpcf7\./', '_', $name );

	$submission = WPCF7_Submission::get_instance();

	if ( ! $submission ) {
		return $output;
	}
	if ('_signature_algemeen' == $name) {
		$signature = siw_get_mail_signature_name('contact_algemeen');
		return $signature;
	}
	if ('_signature_np' == $name) {
		$signature = siw_get_mail_signature_name('contact_np');
		return $signature;
	}
	if ('_signature_project' == $name) {
		$signature = siw_get_mail_signature_name('contact_project');
		return $signature;
	}
	
	return $output;
}

//siteurl voor gebruik in mailtemplates
add_filter( 'wpcf7_special_mail_tags', 'siw_cf7_set_site_url', 10, 3 );

function siw_cf7_set_site_url( $output, $name, $html ) {
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
}