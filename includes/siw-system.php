<?php
/*
(c)2015 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/*
Extra functies
*/

//admin-bar opschonen
add_action( 'wp_before_admin_bar_render', 'siw_remove_admin_bar_items', 999 );

function siw_remove_admin_bar_items( $wp_admin_bar ) {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu( 'wp-logo' );
	$wp_admin_bar->remove_menu( 'comments' );
	$wp_admin_bar->remove_menu( 'wpseo-menu' );
	$wp_admin_bar->remove_menu( 'tribe-events' );
	$wp_admin_bar->remove_menu( 'vfbp-admin-toolbar' );	
	
}

//welcome panel verwijderen
remove_action( 'welcome_panel', 'wp_welcome_panel' );

//ongebruikte menu-items verwijderen	
add_action( 'admin_menu', 'siw_remove_admin_menu_items' );
function siw_remove_admin_menu_items(){
	remove_menu_page( 'edit-comments.php' );       
}

//admin bar niet tonen voor ingelogde gebruikers
add_filter('show_admin_bar', '__return_false');

//standaard dashboard widgets verwijderen
add_action('wp_dashboard_setup', 'siw_remove_dashboard_widgets' );

function siw_remove_dashboard_widgets(){
	remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
	remove_meta_box( 'dashboard_activity', 'dashboard', 'normal');
	remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
}

//overbodige metaboxes van plugins verwijderen
add_action( 'do_meta_boxes', 'siw_remove_plugin_metaboxes' );

function siw_remove_plugin_metaboxes(){
    remove_meta_box( 'tribe_dashboard_widget', 'dashboard', 'normal' ); 
	remove_meta_box( 'woocommerce_dashboard_recent_reviews', 'dashboard', 'normal' ); 
}



add_action('login_head', 'siw_custom_login');
function siw_custom_login() {
	echo '<link rel="stylesheet" type="text/css" href="' . get_bloginfo('stylesheet_directory') . '/assets/css/siw-login-styles.css" />';
}

add_filter( 'login_headerurl', 'siw_login_logo_url' );
function siw_login_logo_url() {
	return get_bloginfo( 'url' );
}

add_filter( 'login_headertitle', 'siw_login_logo_url_title' );
function siw_login_logo_url_title() {
	return 'SIW Internationale Vrijwilligersprojecten';
}


 
/* custom dashboard widget
add_action('wp_dashboard_setup', 'siw_custom_dashboard_widget' );
function siw_custom_dashboard_widget() {
	wp_add_dashboard_widget('wp_dashboard_widget', 'Theme Details', 'siw_theme_info_widget');
}

 
function siw_theme_info_widget() {
	echo "<ul>
	<li><strong>Ontwikkeld door:</strong> Maarten Bruna</li>
	<li><strong>Contact:</strong> <a href='ictbeheer@siw.nl'>ictbeheer@siw.nl</a></li>
	</ul>";
}
*/

//permalink van testimonials aanpassen van 'testimonial' naar 'ervaring'
add_action( 'init', 'siw_change_permalink_structure' );
function siw_change_permalink_structure() {
	add_permastruct( 'wpm-testimonial', "ervaring/%wpm-testimonial%", array( 'slug' => 'ervaring' ) );
}


//pdf en doc(x) upload toestaan
add_filter('upload_mimes', 'siw_custom_upload_mimes');

function siw_custom_upload_mimes( $existingMimes=array() ){
	$existingMimes['doc'] = 'application/msword'; 
	$existingMimes['docx'] = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'; 
	$existingMimes['pdf'] = 'application/pdf';	
	return $existingMimes;
}

//attachment verwijderen nadat deze per mail verstuurd is.
add_action( 'vfbp_after_email', 'siw_delete_attachment_after_mail', 10, 2 );
function siw_delete_attachment_after_mail( $entry_id, $form_id ) {
	global $wpdb;

	$attachments_args = array(
		'post_type' 	=> 'attachment',
		'post_parent'	=> $entry_id,
		'fields' 		=> 'ids'
	);
	$attachments = get_posts( $attachments_args ); 
	foreach ( $attachments as $attachment ) {
		$attachment_url = wp_get_attachment_url( $attachment );
		wp_delete_attachment( $attachment );
	}
	$wpdb->query(
		$wpdb->prepare(
			"UPDATE $wpdb->postmeta
			SET meta_value = 'gemaild'
				WHERE post_id = %d
				AND meta_value = %s;",
			$entry_id,
			$attachment_url
        )
	);
}


/*disable emojis*/
add_action( 'init', 'siw_disable_wp_emojicons' );
function siw_disable_wp_emojicons() {

	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	add_filter( 'tiny_mce_plugins', 'siw_disable_emojicons_tinymce' );
}


function siw_disable_emojicons_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, array( 'wpemoji' ) );
	}
	else {
		return array();
	}
}


//footer van admin
add_filter('admin_footer_text', 'siw_change_admin_footer');
function siw_change_admin_footer () { 
	echo '&copy;2015 SIW Internationale Vrijwilligersprojecten'; 
} 
 
///vervang Google Analytics functie door custom functie
add_action( 'after_setup_theme', 'siw_after_theme_setup' );
function siw_after_theme_setup() {
	global $pinnacle; if(isset($pinnacle['google_analytics'])) { $g_analytics = $pinnacle['google_analytics'];} else {$g_analytics = '';}
	define('GOOGLE_ANALYTICS_ID', $g_analytics); // UA-XXXXX-Y
	if (GOOGLE_ANALYTICS_ID) {
		remove_action('wp_footer', 'kadence_google_analytics', 20);
		add_action( 'wp_footer', 'siw_google_analytics', 20 );
	}
}

function siw_google_analytics() {?>
<script>
  (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
  function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
  e=o.createElement(i);r=o.getElementsByTagName(i)[0];
  e.src='//www.google-analytics.com/analytics.js';
  r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
  ga('create','<?php echo GOOGLE_ANALYTICS_ID; ?>',{'siteSpeedSampleRate': 100});
  ga('set', 'anonymizeIp', true);
  ga('send','pageview');
</script>
<?php }



/*instellen starttijd Updraft Plus backup*/
add_filter('updraftplus_schedule_firsttime_db', 'siw_backup_time_db');
add_filter('updraftplus_schedule_firsttime_files', 'siw_backup_time_files');

function siw_backup_time_db(){
	$time = strtotime( 'tomorrow 03:00');
	return $time;
}

function siw_backup_time_files(){
	$time = strtotime( 'tomorrow 04:00');
	return $time;
}

/*
Cart-fragments script alleen laden als er iets in het winkelmandje zit.
TODO: Uitgebreid testen of dit inderdaad werkt/waarschijnlijk opgelost in Woocommerce 2.4

add_action( 'wp_enqueue_scripts', 'child_manage_woocommerce_styles', 99 );
 
function child_manage_woocommerce_styles() {

	if (!isset($_COOKIE['wp_woocommerce_session_'])){
		wp_dequeue_script( 'wc-cart-fragments' );
	}
}
*/


/*upload-directory van VFB-pro aanpassen
add_filter( 'vfbp_upload_directory', 'vfbp_filter_upload_directory', 10, 2 );
  
function vfbp_filter_upload_directory( $upload, $form_id ){    
    $dir = 'my-awesome-directory';
 
    $upload['subdir'] = "/$dir" . $upload['subdir'];
    $upload['path']    = $upload['basedir'] . $upload['subdir'];
    $upload['url']       = $upload['baseurl'] . $upload['subdir'];
     
    return $upload;
}
*/


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
