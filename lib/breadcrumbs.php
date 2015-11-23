<?php 
function kadence_display_page_breadcrumbs() {
  global $pinnacle;
   if(isset($pinnacle['show_breadcrumbs_page'])) {
  if($pinnacle['show_breadcrumbs_page'] == 1 ) {$showbreadcrumbs = true;} else { $showbreadcrumbs = false;}
} else {$showbreadcrumbs = true;}
  return $showbreadcrumbs;
}
function kadence_display_archive_breadcrumbs() {
  global $pinnacle;
   if(isset($pinnacle['show_breadcrumbs_archive'])) {
  if($pinnacle['show_breadcrumbs_archive'] == 1 ) {$showbreadcrumbs = true;} else { $showbreadcrumbs = false;}
} else {$showbreadcrumbs = true;}
  return $showbreadcrumbs;
}
function kadence_display_post_breadcrumbs() {
  global $pinnacle;
   if(isset($pinnacle['show_breadcrumbs_post'])) {
  if($pinnacle['show_breadcrumbs_post'] == 1 ) {$showbreadcrumbs = true;} else { $showbreadcrumbs = false;}
} else {$showbreadcrumbs = true;}
  return $showbreadcrumbs;
}
function kadence_display_shop_breadcrumbs() {
  global $pinnacle;
   if(isset($pinnacle['show_breadcrumbs_shop'])) {
  if($pinnacle['show_breadcrumbs_shop'] == 1 ) {$showbreadcrumbs = true;} else { $showbreadcrumbs = false;}
} else {$showbreadcrumbs = true;}
  return $showbreadcrumbs;
}
function kadence_display_product_breadcrumbs() {
  global $pinnacle;
   if(isset($pinnacle['show_breadcrumbs_product'])) {
  if($pinnacle['show_breadcrumbs_product'] == 1 ) {$showbreadcrumbs = true;} else { $showbreadcrumbs = false;}
} else {$showbreadcrumbs = true;}
  return $showbreadcrumbs;
}
function kadence_display_portfolio_breadcrumbs() {
  global $pinnacle;
   if(isset($pinnacle['show_breadcrumbs_portfolio'])) {
  if($pinnacle['show_breadcrumbs_portfolio'] == 1 ) {$showbreadcrumbs = true;} else { $showbreadcrumbs = false;}
} else {$showbreadcrumbs = true;}
  return $showbreadcrumbs;
}

function kadence_breadcrumbs() {
  global $post, $wp_query, $pinnacle;
  
  $delimiter = '/'; // delimiter between crumbs
  if(!empty($pinnacle['home_breadcrumb_text'])) {$home = $pinnacle['home_breadcrumb_text'];} else {$home = 'Home';}
  $showCurrent = 1; // 1 - show current post/page title in breadcrumbs, 0 - don't show
  $before = '<span class="kad-breadcurrent">'; // tag before the current crumb
  $after = '</span>'; // tag after the current crumb

$prepend = '';
if (class_exists('woocommerce') && isset($pinnacle['shop_breadcrumbs']) && $pinnacle['shop_breadcrumbs'] == 1) {
    $shop_page_id = woocommerce_get_page_id( 'shop' );
    $shop_page    = get_post( $shop_page_id );
      if (get_option( 'page_on_front' ) !== $shop_page_id ) {
              $prepend = '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="' . get_permalink( $shop_page ) . '"><span itemprop="title">' . get_the_title($shop_page_id) . '</span></a></span> ' . $delimiter;
            }
}

  $homeLink = home_url();
  
  if (is_home() || is_front_page()) {
  
    '';
  
  } else {
  
    echo '<div id="kadbreadcrumbs" class=""><span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="' . $homeLink . '"><span itemprop="title">' . $home . '</span></a></span> ' . $delimiter . ' ';
  
    if ( is_category() ) {
       if( !empty($pinnacle['blog_link'])){ 
              $bparentpagelink = get_page_link($pinnacle['blog_link']); $bparenttitle = get_the_title($pinnacle['blog_link']);
              echo '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="'.$bparentpagelink. '"><span itemprop="title">' . $bparenttitle . '</span></a></span> ' . $delimiter . ' ';
            } 
      $thisCat = get_category(get_query_var('cat'), false);
      if ($thisCat->parent != 0) echo get_category_parents($thisCat->parent, TRUE, ' ' . $delimiter . ' ');
      echo $before . ' &ldquo;' . single_cat_title('', false) . '&ldquo;' . $after;
  
    } elseif ( is_search() ) {
      echo $before . __('Search results for', 'pinnacle').' "' . get_search_query() . '"' . $after;
  
    } elseif ( is_day() ) {
      echo '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="' . get_year_link(get_the_time('Y')) . '"><span itemprop="title">' . get_the_time('Y') . '</span></a></span> ' . $delimiter . ' ';
      echo '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '"><span itemprop="title">' . get_the_time('F') . '</span></a></span> ' . $delimiter . ' ';
      echo $before . get_the_time('d') . $after;
  
    } elseif ( is_month() ) {
      echo '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="' . get_year_link(get_the_time('Y')) . '"><span itemprop="title">' . get_the_time('Y') . '</span></a></span> ' . $delimiter . ' ';
      echo $before . get_the_time('F') . $after;
  
    } elseif ( is_year() ) {
      echo $before . get_the_time('Y') . $after;
  
    } elseif ( is_single() && !is_attachment() ) {
        if ( get_post_type() != 'post' ) {
          $post_type = get_post_type();
              if($post_type == "portfolio") {
                  if( !empty($pinnacle['portfolio_link']) ) { 
                    $parentpagelink = get_page_link($pinnacle['portfolio_link']); $parenttitle = get_the_title($pinnacle['portfolio_link']);
                    echo '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="'.$parentpagelink. '"><span itemprop="title">' . $parenttitle . '</span></a></span> ' . $delimiter . ' ';
                  } 
                  if ( $terms = wp_get_post_terms( $post->ID, 'portfolio-type', array( 'orderby' => 'parent', 'order' => 'DESC' ) ) ) {
                    $main_term = $terms[0];
                    $ancestors = get_ancestors( $main_term->term_id, 'portfolio-type' );
                    $ancestors = array_reverse( $ancestors );
                        foreach ( $ancestors as $ancestor ) {
                        $ancestor = get_term( $ancestor, 'portfolio-type' );
                        echo ' <span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="' . get_term_link( $ancestor->slug, 'portfolio-type' ) . '"><span itemprop="title">' . $ancestor->name . '</span></a></span> ' . $delimiter;
                        }
                    echo ' <span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="' . get_term_link( $main_term->slug, 'portfolio-type' ) . '"><span itemprop="title">' . $main_term->name . '</span></a></span> ' . $delimiter;
                  }
              } 
          if($post_type == "product") {
              echo $prepend;
                  if ( $terms = wp_get_post_terms( $post->ID, 'product_cat', array( 'orderby' => 'parent', 'order' => 'DESC' ) ) ) {
                      $main_term = $terms[0];
                      $ancestors = get_ancestors( $main_term->term_id, 'product_cat' );
                      $ancestors = array_reverse( $ancestors );
                          foreach ( $ancestors as $ancestor ) {
                            $ancestor = get_term( $ancestor, 'product_cat' );
                            echo ' <span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="' . get_term_link( $ancestor->slug, 'product_cat' ) . '"><span itemprop="title">' . $ancestor->name . '</span></a></span> ' . $delimiter;
                          }
                    echo ' <span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="' . get_term_link( $main_term->slug, 'product_cat' ) . '"><span itemprop="title">' . $main_term->name . '</span></a></span> ' . $delimiter;
                  }
          }
           if($post_type == "staff") {
              if( !empty($pinnacle['staff_link']) ) { 
                    $parentpagelink = get_page_link($pinnacle['staff_link']); $parenttitle = get_the_title($pinnacle['staff_link']);
                    echo '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="'.$parentpagelink. '"><span itemprop="title">' . $parenttitle . '</span></a></span> ' . $delimiter . ' ';
                  } 
                  if ( $terms = wp_get_post_terms( $post->ID, 'staff-group', array( 'orderby' => 'parent', 'order' => 'DESC' ) ) ) {
                      $main_term = $terms[0];
                      $ancestors = get_ancestors( $main_term->term_id, 'staff-group' );
                      $ancestors = array_reverse( $ancestors );
                          foreach ( $ancestors as $ancestor ) {
                            $ancestor = get_term( $ancestor, 'staff-group' );
                            echo ' <span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="' . get_term_link( $ancestor->slug, 'staff-group' ) . '"><span itemprop="title">' . $ancestor->name . '</span></a></span> ' . $delimiter;
                          }
                    echo ' <span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="' . get_term_link( $main_term->slug, 'staff-group' ) . '"><span itemprop="title">' . $main_term->name . '</span></a></span> ' . $delimiter;
                  }
          }
		if ($post_type == 'vacatures'){
			$vacature_parent = get_option('siw_jobs_parent_page');
			if( !empty($vacature_parent) ) { 
				$parentpagelink = get_page_link($vacature_parent); $parenttitle = get_the_title($vacature_parent);
				echo '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="'.$parentpagelink. '"><span itemprop="title">' . $parenttitle . '</span></a></span> ' . $delimiter . ' ';
			} 
		}
       echo $before .' ' . get_the_title() . $after;
      } else {
            if( !empty($pinnacle['blog_link'])){ 
              $bparentpagelink = get_page_link($pinnacle['blog_link']); $bparenttitle = get_the_title($pinnacle['blog_link']);
              echo '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="'.$bparentpagelink. '"><span itemprop="title">' . $bparenttitle . '</span></a></span> ' . $delimiter . ' ';
            } 
           if ( $terms = wp_get_post_terms( $post->ID, 'category', array( 'orderby' => 'parent', 'order' => 'DESC' ) ) ) {
              $cat = $terms[0];
            } else {
            $cat = get_the_category(); $cat = $cat[0];
            }
            $ancestors = get_ancestors( $cat->term_id, 'category' );
            $ancestors = array_reverse( $ancestors );
              foreach ( $ancestors as $ancestor ) {
                $ancestor = get_term( $ancestor, 'category' );
                echo ' <span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="' . get_term_link( $ancestor->slug, 'category' ) . '"><span itemprop="title">' . $ancestor->name . '</span></a></span> ' . $delimiter;
              }
              echo ' <span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="' . get_term_link( $cat->slug, 'category' ) . '"><span itemprop="title">' . $cat->name . '</span></a></span> ' . $delimiter;
             echo $before .' ' . get_the_title() . $after;
        }
     } elseif (is_tax('portfolio-type')) {
            if( !empty($pinnacle['portfolio_link']) ) { 
              $parentpagelink = get_page_link($pinnacle['portfolio_link']); $parenttitle = get_the_title($pinnacle['portfolio_link']);
              echo '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="'.$parentpagelink. '"><span itemprop="title">' . $parenttitle . '</span></a></span> ' . $delimiter . ' ';
            } 
            echo $before . kadence_title() . $after;
     } elseif ( is_tax('product_cat') ) {
        echo $prepend;
        $current_term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
        $ancestors = array_reverse( get_ancestors( $current_term->term_id, get_query_var( 'taxonomy' ) ) );
        foreach ( $ancestors as $ancestor ) {
          $ancestor = get_term( $ancestor, get_query_var( 'taxonomy' ) );
          echo ' <span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="' . get_term_link( $ancestor->slug, get_query_var( 'taxonomy' ) ) . '"><span itemprop="title">' . esc_html( $ancestor->name ) . '</span></a></span> ' . $delimiter;
        }
      echo $before . ' ' . esc_html( $current_term->name ) . $after;

  } elseif ( is_tax('product_tag') ) {
    $queried_object = $wp_query->get_queried_object();
    echo $prepend . $before . ' &ldquo;' . $queried_object->name . '&rdquo;' . $after;

  } elseif (class_exists('woocommerce') && is_shop()) {
      woocommerce_page_title();
    } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
      $post_type = get_post_type_object(get_post_type());
      echo $before . $post_type->labels->singular_name . $after;
  
    } elseif ( is_attachment() ) {
      echo ' ' . $before . get_the_title() . $after;
  
    } elseif ( is_page() && !$post->post_parent ) {
      echo $before . get_the_title() . $after;
  
    } elseif ( is_page() && $post->post_parent ) {
      $parent_id  = $post->post_parent;
      $breadcrumbs = array();
      while ($parent_id) {
        $page = get_page($parent_id);
        $breadcrumbs[] = '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="' . get_permalink($page->ID) . '"><span itemprop="title">' . get_the_title($page->ID) . '</span></a></span>';
        $parent_id  = $page->post_parent;
      }
      $breadcrumbs = array_reverse($breadcrumbs);
      for ($i = 0; $i < count($breadcrumbs); $i++) {
        echo $breadcrumbs[$i];
        if ($i != count($breadcrumbs)-1) echo ' ' . $delimiter . ' ';
      }
      echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
  
    } elseif ( is_tag() ) {
      if( !empty($pinnacle['blog_link'])){ 
              $bparentpagelink = get_page_link($pinnacle['blog_link']); $bparenttitle = get_the_title($pinnacle['blog_link']);
              echo '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="'.$bparentpagelink. '"><span itemprop="title">' . $bparenttitle . '</span></a></span> ' . $delimiter . ' ';
            } 
      echo $before . ' &ldquo;' . single_tag_title('', false) . '&ldquo;' . $after;
  
    } elseif ( is_author() ) {
       global $author;
      $userdata = get_userdata($author);
      echo $before . $userdata->display_name . $after;
  
    } elseif ( is_404() ) {
      echo $before . __('Error 404', 'pinnacle') . $after;
    }
  
    if ( get_query_var('paged') ) {
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
      echo __(' - Page', 'pinnacle') . ' ' . get_query_var('paged');
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
    }
  
    echo '</div>';
  
  }
} 
?>