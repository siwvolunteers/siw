<?php
/*
(c)2015 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/*
Extra functies voor zoeken op sku (projectcode)
*/


add_action('pre_get_posts', 'siw_custom_search_query');
function siw_custom_search_query( $q ){
 
    if ( is_search() ) {
        add_filter( 'posts_join', 'siw_custom_search_join' );
        add_filter( 'posts_where', 'siw_custom_search_where' );
		add_filter( 'posts_distinct', 'siw_custom_search_distinct');
    }
}

function siw_custom_search_join( $join = ''){
 
    global $wp_the_query;
 
    // alleen voor woocommerce query
	if ( empty( $wp_the_query->query_vars['wc_query'] ) || empty( $wp_the_query->query_vars['s'] ) )
            return $join;
 
    $join .= "INNER JOIN wp_postmeta AS postMeta ON (wp_posts.ID = postMeta.post_id)";
    return $join;
}

function siw_custom_search_where( $where = ''){
 
    global $wp_the_query;
 
    // alleen voor woocommerce query
	if ( empty( $wp_the_query->query_vars['wc_query'] ) || empty( $wp_the_query->query_vars['s'] ) )
            return $where;
 
    $where = preg_replace("/post_title LIKE ('%[^%]+%')/", "post_title LIKE $1) 
                OR (post_content LIKE $1)
                OR (postMeta.meta_key = '_sku' AND CAST(postMeta.meta_value AS CHAR) LIKE $1 ", $where);
 
    return $where;
}


function siw_custom_search_distinct() {
    return "DISTINCT";
}
