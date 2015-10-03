<?php 
//Shortcode for Carousels
function kad_carousel_shortcode_function( $atts, $content) {
	extract(shortcode_atts(array(
		'type' => '',
		'columns' => '4',
		'orderby' => '',
		'speed' => '9000',
		'scroll' => '',
		'cat' => '',
		'fullwidth' => 'standard',
		'readmore' => false,
		'items' => '8'
), $atts));
	$carousel_rn = (rand(10,100));
	if(empty($type)) {$type = 'post';}
	if(empty($orderby)) {$orderby = 'menu_order';}
	if($orderby == 'menu_order') {$order = 'ASC';} else {$order = 'DESC';} 
	if(empty($cat)) {$cat = '';}
	if(empty($scroll) || $scroll == 1) {$scroll = 'items:1,';} else {$scroll = '';}
		global $pinnacle;
       	if(isset($pinnacle['product_shop_style'])) {$product_shop_style = $pinnacle['product_shop_style'];} else {$product_shop_style = 'kad-simple-shop';}
       			if ($type == "portfolio") {
					if(isset($pinnacle['portfolio_style_default'])) {
		      			$pstyleclass = $pinnacle['portfolio_style_default'];
		      		} else {
		      			$pstyleclass = 'padded_style';
		      		}
		      		if(isset($pinnacle['portfolio_hover_style_default'])) {
		      			$phoverstyleclass = $pinnacle['portfolio_hover_style_default'];
		      		} else {
		      			$phoverstyleclass = 'p_lightstyle';
		      		}
		      		$shortcodeclasses[] = $pstyleclass;
				    $shortcodeclasses[] = $phoverstyleclass;
				    $shortcodeclasses[] = 'kt-portfolio-carousel';
		      	} else {
		      		$shortcodeclasses[] = '';
		      	}
ob_start(); ?>
				<div class="carousel_outerrim kad-animation" data-animation="fade-in" data-delay="0">
				<div class="home-margin fredcarousel">
				<div id="carouselcontainer-<?php echo $carousel_rn; ?>" class="rowtight <?php echo implode(" ", $shortcodeclasses);?> fadein-carousel">
				<div id="carousel-<?php echo $carousel_rn; ?>" class="clearfix <?php echo $product_shop_style;?> blog_carousel caroufedselclass products">
	<?php if ($type == "portfolio") {
		      		if(isset($pinnacle['portfolio_ratio_default'])) {
                   	$pimgratio = $pinnacle['portfolio_ratio_default'];
                	} else {
                   	 $pimgratio = "square";
                   	}
			if($fullwidth == 'fullwidth') {
                if ($columns == '2') {$itemsize = 'tcol-sxl-3 tcol-xl-4 tcol-md-6 tcol-sm-6 tcol-xs-12 tcol-ss-12'; $slidewidth = 700; $sxl = 4;  $xl = 3; $md = 2; $sm = 2; $xs = 1; $ss = 1;} 
			    else if ($columns == '3'){ $itemsize = 'tcol-sxl-25 tcol-xl-3 tcol-md-4 tcol-sm-4 tcol-xs-6 tcol-ss-12'; $slidewidth = 460; $sxl = 5;  $xl = 4; $md = 3; $sm = 3; $xs = 2; $ss = 1;} 
			    else if ($columns == '6'){ $itemsize = 'tcol-sxl-2 tcol-xl-2 tcol-md-2 tcol-sm-3 tcol-xs-4 tcol-ss-6'; $slidewidth = 300; $sxl = 6; $xl = 6; $md = 6; $sm = 4; $xs = 3; $ss = 2;} 
			    else if ($columns == '5'){ $itemsize = 'tcol-sxl-2 tcol-xl-2 tcol-md-25 tcol-sm-3 tcol-xs-4 tcol-ss-6'; $slidewidth = 300; $sxl = 6;  $xl = 6; $md = 5; $sm = 4; $xs = 3; $ss = 2;} 
			    else {$itemsize = 'tcol-sxl-2 tcol-xl-25 tcol-md-3 tcol-sm-4 tcol-xs-6 tcol-ss-12'; $slidewidth = 360; $sxl = 6; $xl = 5; $md = 4; $sm = 3; $xs = 2; $ss = 1;}
            } else {
		        if ($columns == '2') {$itemsize = 'tcol-md-6 tcol-sm-6 tcol-xs-12 tcol-ss-12'; $slidewidth = 600; $sxl = 2; $xl = 2; $md = 2; $sm = 2; $xs = 1; $ss = 1;} 
		        else if ($columns == '3'){ $itemsize = 'tcol-md-4 tcol-sm-4 tcol-xs-6 tcol-ss-12'; $slidewidth = 400; $sxl = 3; $xl = 3; $md = 3; $sm = 3; $xs = 2; $ss = 1;} 
		        else if ($columns == '6'){ $itemsize = 'tcol-md-2 tcol-sm-3 tcol-xs-4 tcol-ss-6'; $slidewidth = 300;  $sxl = 6; $xl = 6; $md = 6; $sm = 4; $xs = 3; $ss = 2;} 
		        else if ($columns == '5'){ $itemsize = 'tcol-md-25 tcol-sm-3 tcol-xs-4 tcol-ss-6'; $slidewidth = 300; $sxl = 5; $xl = 5; $md = 5; $sm = 4; $xs = 3; $ss = 2;} 
		        else {$itemsize = 'tcol-md-3 tcol-sm-4 tcol-xs-6 tcol-ss-12'; $slidewidth = 300; $sxl = 4; $xl = 4; $md = 4; $sm = 3; $xs = 2; $ss = 1;}
		    }

			     if($pimgratio == 'portrait') {
						$temppimgheight = $slidewidth * 1.35;
						$slideheight = floor($temppimgheight);
					} else if($pimgratio == 'landscape') {
						$temppimgheight = $slidewidth / 1.35;
						$slideheight = floor($temppimgheight);
					} else if($pimgratio == 'widelandscape') {
						$temppimgheight = $slidewidth / 2;
						$slideheight = floor($temppimgheight);
					} else {
						$slideheight = $slidewidth;
					}
		        if(!empty($cat)){$portfolio_category = $cat;} else {$portfolio_category = '';}
				$wp_query = null; 
				$wp_query = new WP_Query();
						$wp_query->query(array('orderby' => $orderby,'order' => $order,'post_type' => 'portfolio','portfolio-type'=>$portfolio_category,'posts_per_page' => $items));
						if ( $wp_query ) :  while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
						<?php global $post;?>
						<div class="<?php echo $itemsize;?> all p-item">
                			<div class="portfolio-item grid_item postclass kad-light-gallery">
					
                       <?php $postsummery = get_post_meta( $post->ID, '_kad_post_summery', true );
						     if ($postsummery == 'slider') { ?>
						     <div class="portfolio-imagepadding">
                           <div class="flexslider kt-flexslider loading imghoverclass clearfix" data-flex-speed="7000" data-flex-anim-speed="400" data-flex-animation="fade" data-flex-auto="true">
                       <ul class="slides kad-light-gallery">
                          <?php 
                          global $post;
	                      $image_gallery = get_post_meta( $post->ID, '_kad_image_gallery', true );
	                            $attachments = array_filter( explode( ',', $image_gallery ) );
	                              if ($attachments) {
	                              foreach ($attachments as $attachment) {
	                                $thumbnailURL = wp_get_attachment_url($attachment , 'full');
	                                $image = aq_resize($thumbnailURL, $slidewidth, $slideheight, true);
	                                  if(empty($image)) {$image = $thumbnailURL;}?>
	                                  <li><a href="<?php the_permalink() ?>" class=""><img src="<?php echo $image ?>" width="<?php echo $slidewidth;?>" height="<?php echo $slideheight;?>" class="" />
	                                  <div class="portfolio-hoverover"></div>
	                                       <div class="portfolio-table">
	                                       		<div class="portfolio-cell">
	                                       			<?php if($pstyleclass == "padded_style" ) { ?>
		                                       				<a href="<?php the_permalink() ?>" class="kad-btn kad-btn-primary"><?php echo __('View details', 'pinnacle');?></a>
		                                       		<?php } elseif($pstyleclass == "flat-no-margin" || $pstyleclass == "flat-w-margin" ) { ?>
		                                       				<h5><?php the_title();?></h5>
                           									<?php $terms = get_the_terms( $post->ID, 'portfolio-type' ); if ($terms) {?> <p class="cportfoliotag"><?php $output = array(); foreach($terms as $term){ $output[] = $term->name;} echo implode(', ', $output); ?></p> <?php } ?>
		                                       		<?php } ?>
		                                       </div>
	                                       </div>
	                                   </a>
	                                   </li>
	                                <?php }
	                          }  ?>                            
					</ul>
              	</div> <!--Flex Slides-->
              </div>
              <?php } else if($postsummery == 'videolight') { 

							if (has_post_thumbnail( $post->ID ) ) {
									$image_url = wp_get_attachment_image_src( 
									get_post_thumbnail_id( $post->ID ), 'full' ); 
									$thumbnailURL = $image_url[0]; 
									 $image = aq_resize($thumbnailURL, $slidewidth, $slideheight, true);
									 $video_string = get_post_meta( $post->ID, '_kad_post_video_url', true );
									 if(!empty($video_string)) {$video_url = $video_string;} else {$video_url = $thumbnailURL;}

									if(empty($image)) {$image = $thumbnailURL;} ?>
									<div class="portfolio-imagepadding kt-portfolio-video">
										<div class="portfolio-hoverclass">
											<a href="<?php the_permalink() ?>" class="">
	                                       <img src="<?php echo $image ?>" alt="<?php the_title(); ?>" class="kad-lightboxhover">
	                                       <div class="portfolio-hoverover"></div>
	                                       <div class="portfolio-table">
	                                       		<div class="portfolio-cell">
	                                       			<?php if($pstyleclass == "padded_style" ) { ?>
		                                       				<a href="<?php the_permalink() ?>" class="kad-btn kad-btn-primary"><?php echo __('View details', 'pinnacle');?></a>
		                                       		<?php } elseif($pstyleclass == "flat-no-margin" || $pstyleclass == "flat-w-margin" ) { ?>
		                                       				<h5><?php the_title();?></h5>
                           									<?php $terms = get_the_terms( $post->ID, 'portfolio-type' ); if ($terms) {?> <p class="cportfoliotag"><?php $output = array(); foreach($terms as $term){ $output[] = $term->name;} echo implode(', ', $output); ?></p> <?php } ?>
		                                       		<?php } ?>
		                                       </div>
	                                       </div>
	                                   </a>
	                                   </div>
	                                </div>
	                                <?php $image = null; $thumbnailURL = null;?>
                <?php }
              } else {
								if (has_post_thumbnail( $post->ID ) ) {
									$image_url = wp_get_attachment_image_src( 
									get_post_thumbnail_id( $post->ID ), 'full' ); 
									$thumbnailURL = $image_url[0]; 
									 $image = aq_resize($thumbnailURL, $slidewidth, $slideheight, true);

									if(empty($image)) {$image = $thumbnailURL;} ?>
									<div class="portfolio-imagepadding">
										<div class="portfolio-hoverclass">
											<a href="<?php the_permalink() ?>" class="">
	                                       <img src="<?php echo $image ?>" alt="<?php the_title(); ?>" width="<?php echo $slidewidth;?>" height="<?php echo $slideheight;?>" class="kad-lightboxhover">
	                                       <div class="portfolio-hoverover"></div>
	                                       <div class="portfolio-table">
	                                       		<div class="portfolio-cell">
	                                       			<?php if($pstyleclass == "padded_style" ) { ?>
		                                       				<a href="<?php the_permalink() ?>" class="kad-btn kad-btn-primary"><?php echo __('View details', 'pinnacle');?></a>
		                                       		<?php } elseif($pstyleclass == "flat-no-margin" || $pstyleclass == "flat-w-margin" ) { ?>
		                                       				<h5><?php the_title();?></h5>
                           									<?php $terms = get_the_terms( $post->ID, 'portfolio-type' ); if ($terms) {?> <p class="cportfoliotag"><?php $output = array(); foreach($terms as $term){ $output[] = $term->name;} echo implode(', ', $output); ?></p> <?php } ?>
		                                       		<?php } ?>
		                                       </div>
	                                       </div>
	                                   </a>
	                                   </div>
	                                </div>
	                                <?php $image = null; $thumbnailURL = null;?>
                           <?php } } ?>
                           <?php if($pstyleclass == "padded_style" ) { ?>
		              		<a href="<?php the_permalink() ?>" class="portfoliolink">
			              		<div class="piteminfo">   
			                          <h5><?php the_title();?></h5>
			                           <?php $terms = get_the_terms( $post->ID, 'portfolio-type' ); if ($terms) {?> <p class="cportfoliotag"><?php $output = array(); foreach($terms as $term){ $output[] = $term->name;} echo implode(', ', $output); ?></p> <?php } ?>
			                    </div>
		                	</a>
                	<?php } ?>
                		</div>
                    </div>
					<?php endwhile; else: ?>
					<li class="error-not-found"><?php _e('Sorry, no portfolio entries found.', 'pinnacle');?></li>
				<?php endif; $wp_query = null; wp_reset_query(); ?>
            </div></div>

            <?php } else if($type == "post") {
            	global $postcolumn; 
            	if ($columns == '2') {$itemsize = 'tcol-md-6 tcol-sm-6 tcol-xs-12 tcol-ss-12'; $postcolumn = '2'; $md = 2; $sm = 2; $xs = 2; $ss = 1;} 
			    else if ($columns == '3'){ $itemsize = 'tcol-md-4 tcol-sm-4 tcol-xs-6 tcol-ss-12'; $postcolumn = '3'; $md = 3; $sm = 3; $xs = 2; $ss = 1;}
			    else if ($columns == '1'){ $itemsize = 'tcol-lg-12 tcol-md-12 tcol-sm-12 tcol-xs-12 tcol-ss-12'; $postcolumn = '1'; $md = 1; $sm = 1; $xs = 1; $ss = 1;}
			    else {$itemsize = 'tcol-md-3 tcol-sm-4 tcol-xs-6 tcol-ss-12'; $postcolumn = '4'; $md = 4; $sm = 3; $xs = 2; $ss = 1;}
			    $sxl = $md; $xl = $md;
				$wp_query = null; 
				$wp_query = new WP_Query();
				$wp_query->query(array('orderby' => $orderby,'order' => $order,'post_type' => 'post','category_name'=>$cat,'posts_per_page' => $items));
						if ( $wp_query ) :  while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
						<div class="<?php echo $itemsize;?> b_item kad_blog_item">
							<?php get_template_part('templates/content', 'post-grid-carousel');?>
					</div>
				<?php endwhile; else: ?>
				<div class="error-not-found"><?php _e('Sorry, no post entries found.', 'pinnacle');?></div>
				<?php endif; $wp_query = null; wp_reset_query(); ?>								
				</div>
				</div>
            <?php } else if($type == "featured-products") {
            	global $woocommerce_loop;
				  if($columns == 1) {$md = 1; $sm = 1; $xs = 1; $ss = 1; $woocommerce_loop['columns'] = 3;
				   }else {
				  	$woocommerce_loop['columns'] = $columns;
					if ($columns == '2') {$md = 2; $sm = 2; $xs = 1; $ss = 1;} 
			        else if ($columns == '3'){ $md = 3; $sm = 3; $xs = 2; $ss = 1;} 
		            else if ($columns == '6'){ $md = 6; $sm = 4; $xs = 3; $ss = 2;} 
			        else if ($columns == '5'){ $md = 5; $sm = 4; $xs = 3; $ss = 2;} 
			        else { $md = 4; $sm = 3; $xs = 3; $ss = 1;} 
			    	}
			    	$sxl = $md; $xl = $md;
				  $wp_query = null; 
				  $wp_query = new WP_Query();
				  $wp_query->query(array('post_type' => 'product','meta_key' => '_featured','meta_value' => 'yes','post_status' => 'publish','orderby' => $orderby,'order' => $order, 'posts_per_page' => $items));
					if ( $wp_query ) : while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
					<?php woocommerce_get_template_part( 'content', 'product' ); ?>
					<?php endwhile; ?>
					<?php endif; ?>         
                    <?php $wp_query = null;  wp_reset_query(); ?>
				</div>
				</div>
           <?php  } else if($type == "sale-products") {
           			if (class_exists('woocommerce')) {
					  global $woocommerce, $woocommerce_loop;
						$product_ids_on_sale = woocommerce_get_product_ids_on_sale(); $product_ids_on_sale[] = 0;
						$meta_query = array();
			          $meta_query[] = $woocommerce->query->visibility_meta_query();
			          $meta_query[] = $woocommerce->query->stock_status_meta_query();
      				}
      				if($columns == 1) {$md = 1; $sm = 1; $xs = 1; $ss = 1; $woocommerce_loop['columns'] = 3;
				   }else {
				  $woocommerce_loop['columns'] = $columns;
					if ($columns == '2') {$md = 2; $sm = 2; $xs = 1; $ss = 1;} 
			        else if ($columns == '3'){ $md = 3; $sm = 3; $xs = 2; $ss = 1;} 
		            else if ($columns == '6'){ $md = 6; $sm = 4; $xs = 3; $ss = 2;} 
			        else if ($columns == '5'){ $md = 5; $sm = 4; $xs = 3; $ss = 2;} 
			        else { $md = 4; $sm = 3; $xs = 3; $ss = 1;} 
			    	}
			    	$sxl = $md; $xl = $md;
			    	if(!empty($cat)){$product_category = $cat;} else {$product_category = '';}
				  $wp_query = null; 
				  $wp_query = new WP_Query();
				  $wp_query->query(array('post_type' => 'product','meta_query' => $meta_query,'post__in' => $product_ids_on_sale,'product_cat'=>$product_category,'post_status' => 'publish','orderby' => $orderby,'order' => $order,'posts_per_page' => $items));
					if ( $wp_query ) : while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
					<?php woocommerce_get_template_part( 'content', 'product' ); ?>
					<?php endwhile; ?>
					<?php endif; ?>         
                    <?php $wp_query = null;  wp_reset_query(); ?>
				</div>
				</div>
           <?php } else if($type == "best-products") {
					  global $woocommerce_loop;
					if($columns == 1) {$md = 1; $sm = 1; $xs = 1; $ss = 1; $woocommerce_loop['columns'] = 3;
				   }else {
					  $woocommerce_loop['columns'] = $columns;
						if ($columns == '2') {$md = 2; $sm = 2; $xs = 1; $ss = 1;} 
				        else if ($columns == '3'){ $md = 3; $sm = 3; $xs = 2; $ss = 1;} 
			            else if ($columns == '6'){ $md = 6; $sm = 4; $xs = 3; $ss = 2;} 
				        else if ($columns == '5'){ $md = 5; $sm = 4; $xs = 3; $ss = 2;} 
				        else { $md = 4; $sm = 3; $xs = 3; $ss = 1;} 
			    	}
			    	$sxl = $md; $xl = $md;
				  $wp_query = null; 
				  $wp_query = new WP_Query();
				  $wp_query->query(array('post_type' => 'product','meta_key'=> 'total_sales','orderby' => 'meta_value_num','post_status' => 'publish','posts_per_page' => $items));
					if ( $wp_query ) : while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
					<?php woocommerce_get_template_part( 'content', 'product' ); ?>
					<?php endwhile; ?>
					<?php endif; ?>         
                    <?php $wp_query = null;  wp_reset_query(); ?>
				</div>
				</div>
            <?php } else if($type == "cat-products") {
					  global $woocommerce_loop;
					if($columns == 1) {$md = 1; $sm = 1; $xs = 1; $ss = 1; $woocommerce_loop['columns'] = 3;
				   }else {
					  $woocommerce_loop['columns'] = $columns;
						if ($columns == '2') {$md = 2; $sm = 2; $xs = 1; $ss = 1;} 
				        else if ($columns == '3'){ $md = 3; $sm = 3; $xs = 2; $ss = 1;} 
			            else if ($columns == '6'){ $md = 6; $sm = 4; $xs = 3; $ss = 2;} 
				        else if ($columns == '5'){ $md = 5; $sm = 4; $xs = 3; $ss = 2;} 
				        else { $md = 4; $sm = 3; $xs = 3; $ss = 1;} 
			    	}
			    	$sxl = $md; $xl = $md;
			    	if(!empty($cat)){$product_category = $cat;} else {$product_category = '';}
				  $wp_query = null; 
				  $wp_query = new WP_Query();
				  $wp_query->query(array('post_type' => 'product','orderby' => $orderby, 'order' => $order, 'product_cat'=>$product_category, 'post_status' => 'publish','posts_per_page' => $items, 'meta_key' => '_visibility', 'meta_value' => 'visible'));
					if ( $wp_query ) : while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
					<?php woocommerce_get_template_part( 'content', 'product' ); ?>
					<?php endwhile; ?>
					<?php endif; ?>         
                    <?php $wp_query = null;  wp_reset_query(); ?>
				</div>
				</div>
           <?php } ?>
			<div class="clearfix"></div>
            <a id="prevport-<?php echo $carousel_rn; ?>" class="prev_carousel kt-icon-arrow-left" href="#"></a>
			<a id="nextport-<?php echo $carousel_rn; ?>" class="next_carousel kt-icon-arrow-right" href="#"></a>
			</div></div>
			<script type="text/javascript"> jQuery( window ).load(function () {var $wcontainer = jQuery('#carouselcontainer-<?php echo $carousel_rn; ?>'); var $container = jQuery('#carousel-<?php echo $carousel_rn; ?>');
	 				setWidths(); 
	 				function initCarousel_short() {
	 					$container.carouFredSel({
							scroll: { <?php echo $scroll; ?> easing: "swing", duration: 700, pauseOnHover : true}, auto: {play: true, timeoutDuration: <?php echo $speed; ?>},
							prev: '#prevport-<?php echo $carousel_rn; ?>', next: '#nextport-<?php echo $carousel_rn; ?>', pagination: false, swipe: true, items: {visible: null}
						});
	 				}
	 				initCarousel_short();
		 				jQuery(window).on("debouncedresize", function( event ) {
		 					$container.trigger("destroy");
		 					setWidths();
							initCarousel_short();
						});
		 			$wcontainer.animate({'opacity' : 1});
					function getUnitWidth() {var width;
					if(jQuery(window).width() <= 540) {
					width = $wcontainer.width() / <?php echo $ss;?>;
					} else if(jQuery(window).width() <= 768) {
					width = $wcontainer.width() / <?php echo $xs;?>;
					} else if(jQuery(window).width() <= 990) {
					width = $wcontainer.width() / <?php echo $sm;?>;
					} else if(jQuery(window).width() >= 1800) {
					width = $wcontainer.width() / <?php echo $sxl;?>;
					}else if(jQuery(window).width() >= 1400) {
					width = $wcontainer.width() / <?php echo $xl;?>;
					} else {
					width = $wcontainer.width() / <?php echo $md;?>;
					}
					return width;
					}
					function setWidths() {
					var unitWidth = getUnitWidth() -1;
					$container.children().css({ width: unitWidth });
					} });
			</script>				

	<?php  $output = ob_get_contents();
		ob_end_clean();
	return $output;
}