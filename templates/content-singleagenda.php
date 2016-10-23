<?php get_template_part('templates/post', 'header'); ?>

<?php global $post;

	$id = $post->ID;

	$start_ts					= get_post_meta( $id, 'siw_agenda_start', true );
	$start_date					= date("Y-m-d", $start_ts );
	$end_ts						= get_post_meta( $id, 'siw_agenda_eind', true );
	$date_range					= siw_get_date_range_in_text( date("Y-m-d", $start_ts ),  date("Y-m-d", $end_ts ), false );
	$start_time					= date("H:i", $start_ts );
	$end_time					= date("H:i", $end_ts );
	$program					= get_post_meta( $id, 'siw_agenda_programma', true );
	$description				= get_post_meta( $id, 'siw_agenda_beschrijving', true );
	$location					= get_post_meta( $id, 'siw_agenda_locatie', true ); 
	$address					= get_post_meta( $id, 'siw_agenda_adres', true );
	$postal_code				= get_post_meta( $id, 'siw_agenda_postcode', true );
	$city						= get_post_meta( $id, 'siw_agenda_plaats', true );
	$vfb_form_id				= siw_get_vfb_form_id('community_day');
	$application				= get_post_meta( $id, 'siw_agenda_aanmelden', true );
	$application_explanation	= get_post_meta( $id, 'siw_agenda_aanmelden_toelichting', true );
	$application_link_url		= get_post_meta( $id, 'siw_agenda_aanmelden_link_url', true );
	$application_link_text		= get_post_meta( $id, 'siw_agenda_aanmelden_link_tekst', true );
	$location_map				= '[gmap address="' . $address . ', ' . $postal_code . ' ' . $city . '" title="' . $location . '" zoom="15" maptype="ROADMAP"]';
	$hide_form_days_before_cd = siw_get_hide_form_days_before_cd();
	$limit_date = date("Y-m-d", strtotime( date("Y-m-d")."+" . $hide_form_days_before_cd . " days") );
	$text_after_hide_cd_form = siw_get_text_after_hide_cd_form();
	$agenda_page_url = get_permalink ( siw_get_parent_page('agenda') );
?>
	
<div id="content" class="container">
    <div class="row single-article">
		<div class="main col-md-12 kt-nosidebar" role="main">
		<?php while (have_posts()) : the_post(); ?>
		<article <?php post_class() ?> id="agenda-<?php the_ID(); ?>">
			<div class="postclass">
				<header class="agenda-header">
					<h1><?php the_title();?></h1>
					<h2><?php echo esc_html( $date_range  . ', ' . $start_time  . '&nbsp;-&nbsp;' . $end_time );?></h2>
				</header>
				<div class="row">
					<div class="col-md-10">
						<?php echo wp_kses_post( wpautop( $description ) ); ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<?php if( $program ){?>
						<h3>Programma</h3>
						<div class="row">
						<?php
						foreach ( (array) $program as $key => $item ) {
						?>
							<div class="col-md-3">
								<p><b><?php echo esc_html( date("H:i", strtotime($item['starttijd'])) .  '&nbsp;-&nbsp;' . date("H:i", strtotime( $item['eindtijd'] ) ) );?></b></p>
							</div>
							<div class="col-md-9">
								<?php echo wp_kses_post( wpautop( $item['omschrijving'] ) );?>
							</div>		
						<?php }?>
						</div>
						<?php }?>

						<h3>Locatie</h3>
						<p>
							<b>
							<?php echo esc_html( $location ); ?><br/>
							<?php echo esc_html( $address ); ?><br/>
							<?php echo esc_html( $postal_code . ' ' . $city ); ?><br/>
							</b>
						</p>
						<?php echo do_shortcode( $location_map );?>
					</div>
					<div class="col-md-6">
						<h3>Aanmelden</h3>
						<?php if( $start_date > date("Y-m-d") ):?>
						<?php if ('formulier' == $application ){
							if ( $start_date >= $limit_date ){
								echo do_shortcode( '[vfb id=' . $vfb_form_id . ']' );
							}
							else{
								echo wp_kses_post( wpautop( $text_after_hide_cd_form ) );
							}
						}else{?>
							<?php echo wp_kses_post( wpautop( $application_explanation )); ?>
						<?php
							if ( $application_link_url ){?>
							<a href="<?php echo esc_url( $application_link_url );?>" target="_blank"><?php echo ( $application_link_text )? esc_html( $application_link_text ) : esc_html( $application_link_url )?> <i class="kt-icon-newtab"></i></a>
						
						<?php
							}
						} ?>
						<?php else: ?>
						<p>Dit evenement is helaas al afgelopen. Bekijk de toekomstige evenementen in de <a href="<?php echo esc_url($agenda_page_url);?>">agenda</a>.</p>
						<?php endif; ?>
					</div>
				</div>
				<footer class="single-footer clearfix">
					<?php do_action('siw_agenda_footer');?>
				</footer>
			</div>
		</article>
<?php endwhile; ?>
