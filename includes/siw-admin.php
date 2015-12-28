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
    remove_meta_box( 'tribe_dashboard_widget', 'dashboard', 'normal' ); 
	remove_meta_box( 'woocommerce_dashboard_recent_reviews', 'dashboard', 'normal' ); 
	remove_meta_box( 'wpseo-dashboard-overview', 'dashboard', 'normal' ); 	
}


//footer van admin
add_filter('admin_footer_text', 'siw_change_admin_footer');
function siw_change_admin_footer () { 
	echo '&copy;' . date("Y") . ' SIW Internationale Vrijwilligersprojecten'; 
} 