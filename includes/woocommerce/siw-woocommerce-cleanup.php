<?php
/*
(c)2015 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//cron jobs registreren
add_action( 'wp', 'siw_schedule_woocommerce_cleanup_jobs' );
function siw_schedule_woocommerce_cleanup_jobs() {
	$cron_time = siw_get_cron_time();	
	$cron_timestamp = strtotime( 'tomorrow ' . $cron_time);
	
	
	//verweesde variaties verwijderen
	if ( ! wp_next_scheduled( 'siw_delete_orphaned_variations' ) ) {
		wp_schedule_event( $cron_timestamp + ( 15 * MINUTE_IN_SECONDS ) , 'daily', 'siw_delete_orphaned_variations' );
	}
	
	//oude projecten verwijderen
	if ( ! wp_next_scheduled( 'siw_delete_projects' ) ) {
		wp_schedule_event( $cron_timestamp + ( 30 * MINUTE_IN_SECONDS ) , 'daily', 'siw_delete_projects' );
	}	
	
	//ongebruikte terms verwijderen
	if ( ! wp_next_scheduled( 'siw_cleanup_terms' ) ) {
		wp_schedule_event( $cron_timestamp + ( 45 * MINUTE_IN_SECONDS ) , 'daily', 'siw_cleanup_terms' );
	}
}

add_action('siw_cleanup_terms', 'siw_cleanup_terms');
function siw_cleanup_terms(){
	//ongebruikte terms verwijderen
	$taxonomies[] = 'pa_maand';
	$taxonomies[] = 'pa_aantal-vrijwilligers';
	$taxonomies[] = 'pa_beschikbare-plaatsen';
	$taxonomies[] = 'pa_leeftijd';
	$taxonomies[] = 'pa_lokale-bijdrage';
	$taxonomies[] = 'pa_projectcode';
	$taxonomies[] = 'pa_projectnaam';
	$taxonomies[] = 'pa_startdatum';
	$taxonomies[] = 'pa_einddatum';
	
	foreach ( $taxonomies as $taxonomy){
		$terms = get_terms( $taxonomy, array(
			'hide_empty' => false,
			)
		);	
		foreach ( $terms as $term ){
			if (0 == $term->count){
				wp_delete_term( $term->term_id, $taxonomy );
			}
		}
	}
}

add_action('siw_delete_orphaned_variations', 'siw_delete_orphaned_variations');
function siw_delete_orphaned_variations(){
	$args = array(
		'posts_per_page'		=> -1,
		'post_type'				=> 'product',
		'fields' 				=> 'ids',
	);
	$products = get_posts( $args );
	
	//zoek alle product_variations zonder parent.
	$args = array(
		'posts_per_page'		=> 10,
		'post_type'				=> 'product_variation',
		'post_parent__not_in'	=> $products,
		'fields' 				=> 'ids',
	);
	$variations = get_posts( $args ); 
	
	//wp all import tabel bijwerken
	global $wpdb;
	if (!isset( $wpdb->pmxi_posts )) {
		$wpdb->pmxi_posts = $wpdb->prefix . 'pmxi_posts';
	}

	$variation_ids = implode(",", $variations);	
	$wpdb->query( 
		$wpdb->prepare("
			DELETE FROM $wpdb->pmxi_posts
			WHERE post_id IN (%s)",
			$variation_ids
		)
	);	
	
	//variaties verwijderen
	foreach ( $variations as $variation_id ) {
		wp_delete_post( $variation_id, true );
	}
}


add_action('siw_delete_projects', 'siw_delete_projects');
function siw_delete_projects(){
	
	$months = siw_wc_get_nr_of_months_after_start_to_delete_project();
	$limit = date("Y-m-d", strtotime( date("Y-m-d") . "-" . $months . " months"));
	
	$meta_query = array(
		'relation'	=> 'AND',
			array(
				'key'		=> 'startdatum',
				'value'		=> $limit,
				'compare'	=> '<',
			)
	);
	$args = array(
		'posts_per_page'	=> 25,
		'post_type'			=> 'product',
		'meta_query'		=> $meta_query,
		'fields' 			=> 'ids'
	);	
	$products = get_posts( $args ); 

	//variaties van geselecteerde projecten opzoeken
	$args = array(
		'posts_per_page'	=> -1,
		'post_type'			=> 'product_variation',
		'post_parent__in'	=> $products,
		'fields' 			=> 'ids',
	);
	$variations = get_posts( $args ); 

	//variaties en producten samenvoegen tot 1 array voor DELETE-query
	$posts = array_merge( $variations, $products );
	$post_ids = implode(",", $posts);
	
	//wp all import tabel bijwerken
	global $wpdb;
	if (!isset( $wpdb->pmxi_posts )) {
		$wpdb->pmxi_posts = $wpdb->prefix . 'pmxi_posts';
	}
	
	$wpdb->query( 
		$wpdb->prepare("
			DELETE FROM $wpdb->pmxi_posts
			WHERE post_id IN (%s)",
			$post_ids
		)
	);	
	
	//project verwijderen
	foreach ( $products as $product_id ) {
		wp_delete_post( $product_id, true );
		//do_action('search_filter_update_post_cache', $product_id );
	}
}
