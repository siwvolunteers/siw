<?php
/*
(c)2015-2016 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


//breadcrumbs voor custom post types
add_action('kadence_breadcrumbs_after_home', 'siw_cpt_breadcrumbs');
function siw_cpt_breadcrumbs(){
	$delimiter = '/';
	if (is_singular('vacatures')){
		$vacature_parent = siw_get_setting('vacatures_parent_page');
		if( !empty($vacature_parent) ) { 
			$parentpagelink = get_page_link($vacature_parent); $parenttitle = get_the_title($vacature_parent);
			echo '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="'.$parentpagelink. '"><span itemprop="title">' . $parenttitle . '</span></a></span> ' . $delimiter . ' ';
		} 
	}
	if (is_singular('agenda')){
		$agenda_parent = siw_get_setting('agenda_parent_page');
		if( !empty($agenda_parent) ) { 
			$parentpagelink = get_page_link($agenda_parent); $parenttitle = get_the_title($agenda_parent);
			echo '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="'.$parentpagelink. '"><span itemprop="title">' . $parenttitle . '</span></a></span> ' . $delimiter . ' ';
		} 
	}
}


//shortcodes mogelijk maken in text widget
add_filter( 'widget_text', 'do_shortcode' );


//verwijderen sidebar voor agenda en ervaringen
add_filter('kadence_display_sidebar', 'siw_remove_sidebar');

function siw_remove_sidebar( $sidebar ) {
  if ( 'wpm-testimonial' == get_post_type()){
	return false;
  } 
  return $sidebar;
}

/* functie om pagina titel aan te passen  * /
add_filter('kadence_page_title', 'siw_set_page_title');

function siw_set_page_title($title){
	if(is_404()){
		return "New page title";
	} else {
		return $title;
	}
}

*/

function siw_show_quick_search_widget(){?>
<div class="snelzoeken">
<h4><?php esc_html_e('Snel zoeken','siw');?></h4>
<?php echo do_shortcode( '[searchandfilter id="57"]');//TODO: vervangen door slug of optie?>
</div>
<?php
}

//knop naar infopagina onder elk op maat project
add_action('kadence_single_portfolio_value_after','siw_show_op_maat_button');

function siw_show_op_maat_button(){?>
<a href="/zo-werkt-het/projecten-op-maat/" class="kad-btn kad-btn-primary"><?php esc_html_e('Alles over projecten op maat','siw');?></a>
<?php
}	
//functie om categorie header te tonen op productpagina TODO:herschrijven conform naamgevingsconventies
	
add_action('kt_header_overlay', 'kt_category_image_onproduct'); 
function kt_category_image_onproduct() {
	if(class_exists('woocommerce') and is_product()) {
		global $post;
		if ( $terms = wp_get_post_terms( $post->ID, 'product_cat', array( 'orderby' => 'parent', 'order' => 'DESC' ) ) ) {
			$main_term = $terms[0];
			$meta = get_option('product_cat_pageheader');
			if (empty($meta)) $meta = array();
			if (!is_array($meta)) $meta = (array) $meta;
			$meta = isset($meta[$main_term->term_id]) ? $meta[$main_term->term_id] : array();
			if(isset($meta['kad_pagetitle_bg_image'])) { 
				$bg_image_array = $meta['kad_pagetitle_bg_image']; $src = wp_get_attachment_image_src($bg_image_array[0], 'full'); $bg_image = $src[0];
				echo '<div class="kt_woo_single_override" style="background:url('.$bg_image.');"></div>';
			}
		}
	}
}

//evs kaart niet tonen op mobiel
add_filter( 'siteorigin_panels_data', 'siw_hide_mapplic_on_mobile', 1 );

function siw_hide_mapplic_on_mobile( $panels_data ){
	$detect = new Mobile_Detect_pinnacle;
	$index = '';
	if($detect->isMobile()) {
		if( !empty($panels_data['widgets']) && is_array($panels_data['widgets']) ) {
			foreach( $panels_data['widgets'] as &$widget ) {
				if ( isset( $widget['panels_info']['style']['class']) && 'mapplic' == $widget['panels_info']['style']['class'] ){
				$index = $widget['panels_info']['id'];
				}
			}
		}
		if ( '' != $index ){
			array_splice($panels_data['widgets'], $index, 1);
		}
	}
	return $panels_data;
}
