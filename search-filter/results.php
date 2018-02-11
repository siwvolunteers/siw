<?php
/*
(c)2015 SIW Internationale Vrijwilligersprojecten
*/

$shop_url = get_permalink( wc_get_page_id( 'shop' ) );

if ( $query->have_posts() ) {

	global $woocommerce_loop;
	$woocommerce_loop['columns']=3;?>
	<?php woocommerce_product_loop_start(); ?>
	<?php while ( $query->have_posts() ) : $query->the_post(); ?>
		<?php wc_get_template_part( 'content', 'product' ); ?>
	<?php endwhile; // end of the loop. ?>

	<?php woocommerce_product_loop_end(); ?>

	<?php
	global $wp_query;
	$product_cat_slug		= get_query_var( '_sft_product_cat' );
	$cat_obj				= get_term_by( 'slug', $product_cat_slug, 'product_cat', 'ARRAY_A' );
	$product_cat_name		= $cat_obj['name'];
	$product_cat_taxonomy	= $cat_obj['taxonomy'];
	if ( $cat_obj ) {
		$product_cat_permalink = get_term_link( $cat_obj['term_id'] );
	}

	$month_slug				= get_query_var( '_sft_pa_maand' );
	$month_obj				= get_term_by( 'slug', $month_slug, 'pa_maand', 'ARRAY_A' );
	$month_name				= strtolower( $month_obj['name'] );

	if ( '' == $product_cat_slug && '' == $month_slug ) {
		$url = $shop_url;
		$text = __( 'Bekijk alle projecten', 'siw' );
	}
	else if ( '' == $product_cat_slug && '' != $month_slug ) {
		$url = $shop_url . '?filter_maand=' . $month_slug;
		$text = sprintf( __( 'Bekijk alle projecten in %s', 'siw' ), $month_name );
	}
	else if ( '' != $product_cat_slug && '' == $month_slug ) {
		$url = 	$product_cat_permalink;
		$text = sprintf( __( 'Bekijk alle projecten in %s', 'siw' ), $product_cat_name );
	}
	else {
		$url = $product_cat_permalink . '?filter_maand=' . $month_slug;
		$text = sprintf( __( 'Bekijk alle projecten in %s in %s', 'siw' ), $month_name, $product_cat_name );
	}?>

<?php
} else {
	woocommerce_get_template( 'loop/no-products-found.php' );
	$url = $shop_url;
	$text = __( 'Bekijk alle projecten', 'siw' );
}?>
<div style="text-align:center">
	<?php printf( '<a href="%s" class="kad-btn kad-btn-primary">', esc_url( $url ) )?><?php esc_html_e( $text );?></a>
</div>

<?php
