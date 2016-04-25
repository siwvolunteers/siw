<?php
/*
(c)2015 SIW Internationale Vrijwilligersprojecten
*/

if ( $query->have_posts() )
{

global $woocommerce_loop;
$woocommerce_loop['columns']=3;
	?>
	<?php woocommerce_product_loop_start(); ?>
	
	<?php while ( $query->have_posts() ) : $query->the_post(); ?>
		<?php woocommerce_get_template_part( 'content', 'product' ); ?>
	<?php endwhile; // end of the loop. ?>

	<?php woocommerce_product_loop_end(); ?>
	
	
	<?php

	?>

<?php

//tonen knop naar alle projecten

global $wp_query;
$product_cat_slug  = get_query_var('_sft_product_cat');
$cat_obj = get_term_by('slug', $product_cat_slug, 'product_cat', 'ARRAY_A');
$product_cat_name = $cat_obj['name'];
$product_cat_taxonomy = $cat_obj['taxonomy'];

$month = get_query_var('_sft_pa_maand');
$month_obj = get_term_by('slug', $month, 'pa_maand', 'ARRAY_A');
$month_id = $month_obj['term_id'];
$month_name = strtolower( $month_obj['name']);

echo '<div style="text-align:center">';		
if ( $product_cat_taxonomy !='product_cat' and $month =='' ){
 	echo '<a href="/groepsprojecten/" class="kad-btn kad-btn-primary">Bekijk alle projecten</a>';
}
else if( $product_cat_taxonomy !='product_cat' and $month !='' ){
	echo '<a href="/groepsprojecten/?filter_maand=' . $month_id . '" class="kad-btn kad-btn-primary">Bekijk alle projecten in ' . $month_name . '</a>';
} 
else if ( $product_cat_taxonomy =='product_cat' and $month !='' ){
	echo '<a href="/groepsprojecten-in/'.$product_cat_slug.'/?filter_maand=' . $month_id . '" class="kad-btn kad-btn-primary">Bekijk alle projecten in '.$product_cat_name.' in ' . $month_name . '</a>';
	}
else{				
	echo '<a href="/groepsprojecten-in/'.$product_cat_slug.'/" class="kad-btn kad-btn-primary">Bekijk alle projecten in '.$product_cat_name.'</a>';
}
echo '</div>';
}


else{
	woocommerce_get_template( 'loop/no-products-found.php' );	
	echo '<div style="text-align:center">';		
 	echo '<a href="/groepsprojecten/" class="kad-btn kad-btn-primary">Bekijk alle projecten</a>';
	echo '</div>';
}


?>