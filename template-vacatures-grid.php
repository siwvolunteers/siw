<?php
/*
Template Name: Vacatures Grid
*/
?>
<?php get_header(); ?>
<?php get_template_part('templates/page', 'header'); ?>
<div id="content" class="container">
	<div class="row">
			<?php global $post, $pinnacle, $postcolumn;
			if ( isset( $pinnacle['pinnacle_animate_in']) && $pinnacle['pinnacle_animate_in'] == 1) { $animate = 1;} else { $animate = 0;}
			$blog_grid_column = get_post_meta( $post->ID, 'siw_vacature_columns', true );
			if ( $blog_grid_column == '2') { $itemsize = 'tcol-md-6 tcol-sm-6 tcol-xs-12 tcol-ss-12'; $postcolumn = '2';}
			else if ( $blog_grid_column == '3'){ $itemsize = 'tcol-md-4 tcol-sm-4 tcol-xs-6 tcol-ss-12'; $postcolumn = '3';}
			else { $itemsize = 'tcol-md-3 tcol-sm-4 tcol-xs-6 tcol-ss-12'; $postcolumn = '4';}
					?>
		<div class="main <?php echo kadence_main_class();?>" role="main">
			<div class="entry-content" itemprop="mainContentOfPage">
				<?php get_template_part('templates/content', 'page'); ?>
			</div>
			<div id="kad-blog-grid" class="rowtight init-isotope siw-vacature-grid" data-fade-in="<?php echo esc_attr( $animate );?>"  data-iso-selector=".b_item" data-iso-style="masonry" data-iso-filter="false">
				<?php
				$meta_query = array(
					'relation'	=> 'AND',
					array(
						'key'		=> 'siw_vacature_deadline',
						'value'		=> time(),
						'compare'	=> '>=',
					)
				);
				$temp = $wp_query;
				$wp_query = null;
				$wp_query = new WP_Query();
				$wp_query->query(array(
					'paged'				=> false,
					'post_type'			=> 'vacatures',
					'posts_per_page'	=> -1,
					'meta_query'		=> $meta_query,
					)
				);
				$count =0;
				if ( $wp_query->have_posts() ){
					while ( $wp_query->have_posts() ){
						$wp_query->the_post();?>
						<div class="<?php echo esc_attr($itemsize);?> b_item kad_blog_item">
							<?php get_template_part('templates/content', 'vacature-grid');?>
						</div><?php
					}
				?>
			</div><?php
			} else{?>
		</div>
		<div>
			<h5><?php _e('Helaas, er zijn momenteel geen vacatures.', 'siw'); ?></h5>
		</div>
		<?php
		}?>
		<?php $wp_query = null;$wp_query = $temp; // Reset ?>
		<?php wp_reset_query(); ?>
		<?php do_action('kt_after_pagecontent'); ?>
	</div><!-- /.main -->
  <?php get_footer(); ?>
