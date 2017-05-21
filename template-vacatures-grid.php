<?php
/*
Template Name: Vacatures Grid
*/
?>
<?php get_header(); ?>
<?php get_template_part('templates/page', 'header'); ?>
<div id="content" class="container">
	<div class="row">
			<?php global $post ;
			$open_application_email = antispambot( SIW_PLUGIN::siw_get_setting('open_application_email') );
			?>
		<div class="main <?php echo kadence_main_class();?>" role="main">
			<div class="pageclass entry-content" itemprop="mainContentOfPage">
				<div class="row">
					<div class="col-md-9">
						<h3><?php esc_html_e('Vacatures', 'siw');?></h3>
						<p>
						<?php esc_html_e('SIW ontvangt al ruim zestig jaar buitenlandse vrijwilligers op diverse projecten in Nederland en zendt Nederlandse vrijwilligers uit naar projecten over de hele wereld. Ruim 70 vrijwilligere medewerkers zetten zich hier vol overgave voor in. Regelmatig zijn we op zoek naar nieuwe collega\'s. Ben jij op zoek naar een functie bij een organisatie met een internationaal speelveld en kom jij graag in aanraking met andere culturen? Wellicht heeft SIW Internationale Vrijwilligersprojecten dan een vacature voor jou.', 'siw');?>
						</p>
						<div id="kad-blog-grid" class="rowtight init-isotope siw-vacature-grid" data-fade-in="1"  data-iso-selector=".b_item" data-iso-style="masonry" data-iso-filter="false">
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
						if ( $wp_query->have_posts() ):
							while ( $wp_query->have_posts() ){
								$wp_query->the_post();?>
								<div class="tcol-md-6 tcol-sm-6 tcol-xs-12 tcol-ss-12 b_item kad_blog_item">
									<?php get_template_part('templates/content', 'vacature-grid');?>
								</div><?php
							}
						?>
						</div><?php
						else:?>
						</div>
						<div>
							<p>
							<em>
							<?php echo esc_html_e( 'Helaas zijn er op dit moment geen vacatures beschikbaar. Houd onze website in de gaten, meld je aan voor onze nieuwsbrief of stuur ons een open sollicitatie.', 'siw'); ?>
							</em>
							</p>
						</div>
						<?php endif ?>
						<?php $wp_query = null;$wp_query = $temp; // Reset ?>
						<?php wp_reset_query(); ?>
					</div>
					<div class="col-md-3">
						<h3><?php esc_html_e( 'Open sollicitatie', 'siw');?></h3>
						<p>
						<?php printf( wp_kses_post( __('Is er op dit moment geen geschikte vacature voor jou bij SIW? Je kunt ons ook een open sollicitatie sturen. Wij zijn altijd op zoek naar vrijwillige medewerkers die ons kunnen helpen met diverse kantoorwerkzaamheden. Stuur jouw motivatie en curriculum vitae onder vermelding van \'Open sollicitatie\' naar  <a class="email" href="mailto:%s">%s</a>', 'siw' ) ), $open_application_email, $open_application_email);?>
						</p>
					</div>
				</div>
		<?php do_action('kt_after_pagecontent'); ?>
	</div><!-- /.main -->
  <?php get_footer(); ?>
