<div class="sliderclass ktslider_home_hidetop">
<?php global $pinnacle; echo do_shortcode( '[kadence_slider id="'.$pinnacle['kt_slider'].'"]' ); ?>
<?php
$detect = new Mobile_Detect_pinnacle; if(!($detect->isMobile() && !$detect->isTablet())) {
?>
<div class="snelzoeken kad-icon-box">
<h4>Snel zoeken</h4>
<?php echo do_shortcode( '[searchandfilter id="57"]');?>
</div>
<?php } ?>
<?php if(isset($pinnacle['header_slider_arrow']) && $pinnacle['header_slider_arrow'] == 1) {
        echo '<div class="kad_fullslider_arrow"><a href="#kt-slideto"><i class="kt-icon-arrow-down"></i></a></div>';
      }?>
</div><!--sliderclass-->
<div id="kt-slideto"></div>