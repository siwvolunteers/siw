<?php 

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

global $pinnacle;

?>
<div class="sliderclass ktslider_home_hidetop">
<?php echo do_shortcode( '[kadence_slider_pro id="'.$pinnacle['ksp_slider'].'"]' ); ?>
<?php siw_show_quick_search_widget();?>
<?php if(isset($pinnacle['header_slider_arrow']) && $pinnacle['header_slider_arrow'] == 1) {
        echo '<div class="kad_fullslider_arrow"><a href="#kt-slideto"><i class="kt-icon-arrow-down"></i></a></div>';
      }?>
</div><!--sliderclass-->
<div id="kt-slideto"></div>