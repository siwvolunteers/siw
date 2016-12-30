<?php
/*
(c)2016 SIW Internationale Vrijwilligersprojecten
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
	
	//maanden hernoemen en volgorde aanpassen
	if ( ! wp_next_scheduled( 'siw_reorder_rename_product_attribute_month' ) ) {
		wp_schedule_event( $cron_timestamp + ( 50 * MINUTE_IN_SECONDS ) , 'daily', 'siw_reorder_rename_product_attribute_month' );
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


/*
	Volgorde en naam van attribute pa_month aanpassen
*/
add_action('siw_reorder_rename_product_attribute_month', 'siw_reorder_rename_product_attribute_month');
function siw_reorder_rename_product_attribute_month(){
	$terms = get_terms( 'pa_maand', array(
		'hide_empty' => false,
		)
	);
	$ordered_terms = array();
	foreach ( $terms as $term ){
		$ordered_terms[ $term->term_id ] = $term->slug;
	}
	//oplopend sorteren op slug
	asort( $ordered_terms, SORT_STRING );
	
	$order = 0;
	foreach( $ordered_terms as $term_id => $term_slug ){
		$name = siw_wc_get_month_name_from_slug( $term_slug );
		
		//naam aanpassen
		wp_update_term( $term_id, 'pa_maand', array(
			'name' => $name,
		));
		$order++;
		//Volgorde bijwerken
		update_term_meta( $term_id, 'order_pa_maand', $order );
	}

}

add_action('siw_delete_orphaned_variations', 'siw_delete_orphaned_variations');
function siw_delete_orphaned_variations(){
	$args = array(
		'posts_per_page'		=> -1,
		'post_type'				=> 'product',
		'fields'				=> 'ids',
		'post_status'			=> 'any',
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
	
	$months = siw_wc_get_nr_of_months_after_start_to_delete_project(); //Moet dit echt een instelling zijn? Misschien gewoon 1 jaar van maken.
	$limit = date("Y-m-d", time() - ( $months * MONTH_IN_SECONDS ));

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
