<?php get_template_part('templates/post', 'header'); ?>

<?php global $post;
	$event_data 				= SIW_PLUGIN::siw_get_event_data( $post->ID );
	$location_map				= sprintf('[gmap address="%s, %s %s" title="%s" zoom="15" maptype="ROADMAP"]', esc_attr( $event_data['address'] ), esc_attr( $event_data['postal_code'] ), esc_attr( $event_data['city'] ), esc_attr( $event_data['location'] ) );
	$hide_form_days_before_cd	= SIW_PLUGIN::siw_get_setting('hide_application_form_days_before_info_day');
	$limit_date					= date("Y-m-d", time() + ( $hide_form_days_before_cd * DAY_IN_SECONDS ) );
	$vfb_form_id				= siw_get_vfb_form_id('community_day');
	$agenda_page_url 			= get_permalink ( siw_get_setting('agenda_parent_page') );
?>

<div id="content" class="container">
    <div class="row single-article">
		<div class="main col-md-12 kt-nosidebar" role="main">
		<?php while (have_posts()) : the_post(); ?>
		<article <?php post_class() ?> id="agenda-<?php the_ID(); ?>">
			<div class="postclass">
				<header class="agenda-header">
					<h1><?php the_title();?></h1>
					<h2><?php echo esc_html( $event_data['duration'] );?></h2>
				</header>
				<div class="row">
					<div class="col-md-10">
						<?php echo wp_kses_post( wpautop( $event_data['description'] ) ); ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<?php if( $event_data['program'] ){?>
						<h3><?php esc_html_e('Programma', 'siw');?></h3>
						<div class="row">
						<?php
						foreach ( (array) $event_data['program'] as $key => $item ) {
						?>
							<div class="col-xs-3">
								<p><b><?php echo esc_html( date("H:i", strtotime( $item['starttijd'])) .  '&nbsp;-&nbsp;' . date("H:i", strtotime( $item['eindtijd'] ) ) );?></b></p>
							</div>
							<div class="col-xs-9">
								<?php echo wp_kses_post( wpautop( $item['omschrijving'] ) );?>
							</div>
						<?php }?>
						</div>
						<?php }?>

						<h3><?php esc_html_e('Locatie', 'siw');?></h3>
						<p>
							<b>
							<?php echo esc_html( $event_data['location'] ); ?><br/>
							<?php echo esc_html( $event_data['address'] ); ?><br/>
							<?php echo esc_html( $event_data['postal_code'] . ' ' . $event_data['city'] ); ?><br/>
							</b>
						</p>
						<?php echo do_shortcode( $location_map );?>
					</div>
					<div class="col-md-6">
						<h3><?php esc_html_e('Aanmelden', 'siw');?></h3>
						<?php if( $event_data['end_date'] > date("Y-m-d") ):?>
						<?php if ('formulier' == $event_data['application'] ){
							if ( $event_data['start_date'] >= $limit_date ){
								echo do_shortcode( '[vfb id=' . $vfb_form_id . ']' );
							}
							else{
								echo wp_kses_post( wpautop( ($text_after_hide_cd_form )) ? $text_after_hide_cd_form : __('Het is helaas niet meer mogelijk om je aan te melden.','siw'));
							}
						}else{?>
							<?php echo wp_kses_post( wpautop( $event_data['application_explanation'] )); ?>
						<?php
							if ( $event_data['application_link_url'] ){?>
							<a href="<?php echo esc_url( $event_data['application_link_url'] );?>" target="_blank"><?php echo ( $event_data['application_link_text'] ) ? esc_html( $event_data['application_link_text'] ) : esc_html( $event_data['$application_link_url'] )?> <i class="kt-icon-newtab"></i></a>

						<?php
							}
						} ?>
						<?php else: ?>
						<p>
						<?php printf( wp_kses_post( __('Dit evenement is helaas al afgelopen. Bekijk de toekomstige evenementen in de <a href="%s">agenda</a>', 'siw' ) ), esc_url( $agenda_page_url ) );?>
						</p>
						<?php endif; ?>
					</div>
				</div>
				<footer class="single-footer clearfix">
					<?php do_action('siw_agenda_footer');?>
				</footer>
			</div>
		</article>
<?php endwhile; ?>
