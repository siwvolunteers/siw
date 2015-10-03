<?php 

add_filter('wp_nav_menu_items', 'kt_add_search_form_to_menu', 10, 2);
function kt_add_search_form_to_menu($items, $args) {
 
    if( !($args->theme_location == 'primary_navigation') )
        return $items;

      global $pinnacle, $woocommerce;
      ob_start();
      ?>
    <?php if (class_exists('woocommerce'))  {?>
        <?php if(isset($pinnacle['menu_cart']) && $pinnacle['menu_cart'] == '1' && WC()->cart->get_cart_contents_count() > 0 ) { ?>
    <li class="menu-cart-icon-kt panel"><a class="menu-cart-btn collapsed" data-toggle="collapse" data-parent="#kad-shrinkheader" data-target=".kad-head-cart-popup">
      <span class="kt-cart-text"><?php echo __('Your Cart', 'pinnacle');?></span><i class="kt-icon-basket2"></i><span class="kt-cart-total"><?php echo $woocommerce->cart->cart_contents_count; ?></span>
    </a>
    <div id="kad-head-cart-popup" class="collapse kad-head-cart-popup"><div class="kt-header-mini-cart-refreash"><?php woocommerce_mini_cart(); ?></div></div></li>
    <?php } }?>
    <?php if(isset($pinnacle['menu_search']) && $pinnacle['menu_search'] == '1') { ?>
      <li class="menu-search-icon-kt panel"><a class="menu-search-btn collapsed" data-toggle="collapse" data-parent="#menu-main-menu" data-target="#kad-menu-search-popup"><i class="kt-icon-search4"></i></a>
        <div id="kad-menu-search-popup" class="collapse">
          <?php if(isset($pinnacle['menu_search_products']) && $pinnacle['menu_search_products'] == '1') { ?>
            <?php get_product_search_form();?>
          <?php } else { ?>
            <?php get_search_form(); ?>
          <?php } ?>
        </div>
      </li>
    <?php } ?>
   <?php  $output  = ob_get_contents();
        ob_end_clean();
    return $items . $output;
}

add_filter( 'add_to_cart_fragments', 'kt_get_refreshed_fragments' );
 function kt_get_refreshed_fragments($fragments) {
    // Get mini cart
    ob_start();

    woocommerce_mini_cart();

    $mini_cart = ob_get_clean();

    // Fragments and mini cart are returned
    $fragments['div.kt-header-mini-cart-refreash'] ='<div class="kt-header-mini-cart-refreash">' . $mini_cart . '</div>';

    return $fragments;

  }
  add_filter( 'add_to_cart_fragments', 'kt_get_refreshed_fragments_number' );
 function kt_get_refreshed_fragments_number($fragments) {
    global $woocommerce;
    // Get mini cart
    ob_start();

    ?><span class="kt-cart-total"><?php echo $woocommerce->cart->cart_contents_count; ?></span> <?php

    $fragments['span.kt-cart-total'] = ob_get_clean();

    return $fragments;

  }