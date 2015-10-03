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
$cat_obj = $query->get_queried_object();
$product_cat_taxonomy = $cat_obj->taxonomy;
$product_cat_slug  = $cat_obj->slug;
$product_cat_name = $cat_obj->name;
//bepalen maand
global $wp_query;
$month = get_query_var('_sft_pa_maand');
$maanden=get_terms('pa_maand');
//print_r($maanden);
foreach ( $maanden as $maand ){
	if (($maand->slug)==$month){
		$month_id = $maand->woocommerce_term_id;
		$month_name = strtolower($maand->name);
	}
}
echo '<div style="text-align:center">';		
if ( $product_cat_taxonomy !='product_cat' and $month =='' ){
 	echo '<a href="/groepsprojecten" class="kad-btn kad-btn-primary">Bekijk alle projecten</a>';
}
else if( $product_cat_taxonomy !='product_cat' and $month !='' ){
	echo '<a href="/groepsprojecten?filter_maand=' . $month_id . '" class="kad-btn kad-btn-primary">Bekijk alle projecten in ' . $month_name . '</a>';
} 
else if ( $product_cat_taxonomy =='product_cat' and $month !='' ){
	echo '<a href="/groepsprojecten-in/'.$product_cat_slug.'?filter_maand=' . $month_id . '" class="kad-btn kad-btn-primary">Bekijk alle projecten in '.$product_cat_name.' in ' . $month_name . '</a>';
	}
else{				
	echo '<a href="/groepsprojecten-in/'.$product_cat_slug.'" class="kad-btn kad-btn-primary">Bekijk alle projecten in '.$product_cat_name.'</a>';
}
echo '</div>';
}


else{
	woocommerce_get_template( 'loop/no-products-found.php' );	
	echo '<div style="text-align:center">';		
 	echo '<a href="/groepsprojecten" class="kad-btn kad-btn-primary">Bekijk alle projecten</a>';
	echo '</div>';
}


?>