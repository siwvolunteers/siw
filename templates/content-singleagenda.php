<?php get_template_part('templates/post', 'header'); ?>

<?php global $post;

	$start_ts = get_post_meta( $post->ID, 'siw_agenda_start', true );
	$end_ts = get_post_meta( $post->ID, 'siw_agenda_eind', true );
	$date_range = siw_get_date_range_in_text( date("Y-m-d",$start_ts),  date("Y-m-d",$end_ts), false );
	$start_time = date("H:i",$start_ts);
	$end_time = date("H:i",$end_ts);
	$program = get_post_meta( get_the_ID(), $prefix . 'siw_agenda_programma', true );
	$description = get_post_meta( get_the_ID(), $prefix . 'siw_agenda_beschrijving', true );
	$location = get_post_meta( $post->ID, 'siw_agenda_locatie', true ); 
	$address = get_post_meta( $post->ID, 'siw_agenda_adres', true );
	$postal_code = get_post_meta( $post->ID, 'siw_agenda_postcode', true );
	$city = get_post_meta( $post->ID, 'siw_agenda_plaats', true );
	$vfb_form_id = siw_get_vfb_form_id('community_day');
	
	$application = get_post_meta( $post->ID, 'siw_agenda_aanmelden', true );
	$application_explanation = get_post_meta( $post->ID, 'siw_agenda_aanmelden_toelichting', true );	
	$application_link_url = get_post_meta( $post->ID, 'siw_agenda_aanmelden_link_url', true );	
	$application_link_text = get_post_meta( $post->ID, 'siw_agenda_aanmelden_link_tekst', true );	
	
	$location_map = '[gmap address="' . $address . ', ' . $postal_code . ' ' . $city . '" title="' . $location . '" zoom="15" maptype="ROADMAP"]';

?>
	
<div id="content" class="container">
    <div class="row single-article">
		<div class="main col-md-12 kt-nosidebar" role="main">
		<?php while (have_posts()) : the_post(); ?>		
		<article <?php post_class() ?> id="agenda-<?php the_ID(); ?>">
			<div class="postclass">
				<header class="agenda-header">
					<h1><?php the_title();?></h1>
					<h2><?php echo $date_range, ', ',$start_time, ' - ', $end_time;?></h2>					
				</header>
				<div class="row">
					<div class="col-md-10">
						<p><?php echo wpautop($description); ?></p>				
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<?php if($program){?>
						<h3>Programma</h3>
						<div class="row">
						<?php
						foreach ( (array) $program as $key => $item ) {
						?>
							<div class="col-md-2 ">
								<p><b><?php echo date("H:i", strtotime($item['starttijd'])), '-', date("H:i", strtotime($item['eindtijd']));?></b></p>
							</div>
							<div class="col-md-10 ">
								<p><?php echo $item['omschrijving'];?></p>
							</div>
						<?php }?>
						</div>
						<?php }?>

						<h3>Locatie</h3>
						<p>
							<b>
							<?php echo $location; ?><br/>
							<?php echo $address; ?><br/>
							<?php echo $postal_code, ' ', $city; ?><br/>
							</b>
						</p>
						<p><?php echo do_shortcode( $location_map );?></p>
					</div>
					<div class="col-md-6">
						<h3>Aanmelden</h3>
						<?php if ($application == 'formulier'){
							echo do_shortcode( '[vfb id=' . $vfb_form_id . ']' );
						}else{?>
							<p><?php echo wpautop($application_explanation); ?></p>				
						<?php
							if ( $application_link_url){?>
							<a href="<?php echo $application_link_url;?>" target="_blank"><?php echo (($application_link_text)? $application_link_text : $application_link_url)?> <i class="kt-icon-newtab"></i></a>
						
						<?php
							}
						} ?>
					</div>
				</div>
				<footer class="single-footer clearfix">
				<?php $categories= get_the_terms($post->ID,'soort_evenement');
				if ( $categories && ! is_wp_error( $categories ) ){
					$agenda_categories = array();
					foreach ( $categories as $category ) {
						$agenda_categories[] = $category->name;
					}
				$agenda_category = join( ", ", $agenda_categories );
				}
				if ( $agenda_category ) {
					?> <span class="postedinbottom"><i class="kt-icon-tag4"></i> <?php echo $agenda_category; ?></span><?php
				}?>
				</footer>
			</div>
		</article>
<?php endwhile; ?>