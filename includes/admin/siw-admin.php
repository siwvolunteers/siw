<?php
/*
(c)2015 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


//admin-bar opschonen
add_action( 'wp_before_admin_bar_render', 'siw_remove_admin_bar_items', 999 );

function siw_remove_admin_bar_items( $wp_admin_bar ) {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu( 'wp-logo' );
	$wp_admin_bar->remove_menu( 'comments' );
	$wp_admin_bar->remove_menu( 'wpseo-menu' );
	$wp_admin_bar->remove_menu( 'vfbp-admin-toolbar' );
	$wp_admin_bar->remove_menu('new-content');

}

//welcome panel verwijderen
remove_action( 'welcome_panel', 'wp_welcome_panel' );

//ongebruikte menu-items verwijderen
add_action( 'admin_menu', 'siw_remove_admin_menu_items' );
function siw_remove_admin_menu_items(){
	remove_menu_page( 'edit-comments.php' );     
	remove_menu_page( 'edit.php' );
	remove_menu_page( 'link-manager.php' );
}


//yoast box onderaan pagina
add_filter( 'wpseo_metabox_prio', function() { return 'low';});



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
	remove_meta_box( 'woocommerce_dashboard_recent_reviews', 'dashboard', 'normal' ); 
	remove_meta_box( 'woocommerce_dashboard_status', 'dashboard', 'normal');
	remove_meta_box( 'wpseo-dashboard-overview', 'dashboard', 'normal' ); 	
	remove_meta_box( 'vfbp-dashboard', 'dashboard', 'normal');
}

add_action( 'admin_notices', 'siw_admin_notice_show_site_url' );
function siw_admin_notice_show_site_url() {
	echo ' <div class="updated">
		<h1>Je bent ingelogd op: '. site_url('', '' ) . '</h1>
	</div>';
}

//woothemes update nag verwijderen
remove_action( 'admin_notices', 'woothemes_updater_notice' );


//footer van admin
add_filter('admin_footer_text', 'siw_admin_footer_text');
function siw_admin_footer_text() { 
	echo '&copy;' . date("Y") . ' SIW Internationale Vrijwilligersprojecten'; 
} 

//overbodige gebruikersvelden verwijderen
add_filter('user_contactmethods','siw_remove_user_contactmethods',10,1);
function siw_remove_user_contactmethods( $contactmethods ) {
	unset( $contactmethods['aim']);
	unset( $contactmethods['jabber']);
	unset( $contactmethods['yim']);
	unset( $contactmethods['googleplus']);
	unset( $contactmethods['twitter']);
	unset( $contactmethods['facebook']);	

	return $contactmethods;
}

add_action ('admin_init','siw_remove_extra_profile_fields');
function siw_remove_extra_profile_fields(){
	remove_action( 'show_user_profile', 'kt_show_extra_profile_fields' );
	remove_action( 'edit_user_profile', 'kt_show_extra_profile_fields' );
	remove_action( 'personal_options_update', 'kt_save_extra_profile_fields' );
	remove_action( 'edit_user_profile_update', 'kt_save_extra_profile_fields' );
	
	//woocommerce meta-velden
	add_filter( 'woocommerce_customer_meta_fields',function( $show_fields ){
		return array();
	});
}

