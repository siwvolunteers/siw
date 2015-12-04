<?php get_template_part('templates/post', 'header'); ?>

<?php global $post;
	$deadline_ts = get_post_meta( $post->ID, 'siw_vacature_deadline', true );
	$deadline = siw_get_date_in_text( date("Y-m-d",$deadline_ts), false);
	$mission = siw_get_jobs_mission_statement();
	$wie_zijn_wij = siw_get_jobs_company_profile();
	$wie_ben_jij = get_post_meta( $post->ID, 'siw_vacature_wie_ben_jij', true );			
	$wat_ga_je_doen = get_post_meta( $post->ID, 'siw_vacature_wat_ga_je_doen', true );			
	$wat_bieden_wij_jou = get_post_meta( $post->ID, 'siw_vacature_wat_bieden_wij_jou', true );	
	$contactpersoon_naam = get_post_meta( $post->ID, 'siw_vacature_contactpersoon_naam', true );	
	$contactpersoon_functie = get_post_meta( $post->ID, 'siw_vacature_contactpersoon_functie', true );	
	$contactpersoon_email =  explode("@", get_post_meta( $post->ID, 'siw_vacature_contactpersoon_email', true ));
	$contactpersoon_telefoon = get_post_meta( $post->ID, 'siw_vacature_contactpersoon_telefoon', true );	
	$solliciteren_naam = get_post_meta( $post->ID, 'siw_vacature_solliciteren_naam', true );	
	$solliciteren_functie = get_post_meta( $post->ID, 'siw_vacature_solliciteren_functie', true );	
	$solliciteren_email = explode("@", get_post_meta( $post->ID, 'siw_vacature_solliciteren_email', true ));
	$gesprekken = get_post_meta( $post->ID, 'siw_vacature_gesprekken', true );	
	
	$content = '[accordion]';
	$content .= '[pane title="Wie zijn wij?"]' . $wie_zijn_wij . '[/pane]';
	$content .= '[pane title="Wie ben jij?"]' . $wie_ben_jij . '[/pane]';
	$content .= '[pane title="Wat ga je doen?"]' . $wat_ga_je_doen . '[/pane]';	
	$content .= '[pane title="Wat bieden wij jou?"]' . $wat_bieden_wij_jou . '[/pane]';	
	$content .= '[/accordion]';
?>
	
<div id="content" class="container">
    <div class="row single-article">
		<div class="main col-md-12 kt-nosidebar" role="main">
		<?php while (have_posts()) : the_post(); ?>		
		<article <?php post_class() ?> id="vacatures-<?php the_ID(); ?>">
			<div class="postclass">
				<header>
				</header>
				<div class="row">
					<div class="col-md-7">
						<h3 class="job-heading">Vacature</h3>
						<p><?php echo get_the_excerpt(); ?> Wij zijn op zoek naar een:</p>
						<h5 class="job-title"><?php the_title();?></h5>
						<p class="job-mission"><?php echo $mission; ?></p>
						<?php echo do_shortcode( $content );?>
					</div>
					<div class="col-md-5">
					<?php if($deadline_ts >= time()):?>
						<h3>Meer weten?</h3>
						<p>Voor meer informatie kun je contact opnemen met:<br />
						<?php echo $contactpersoon_naam, (($contactpersoon_functie)?' ('.$contactpersoon_functie.')':'');?>, 
						<script language="JavaScript">
							var username = "<?php echo $contactpersoon_email[0]; ?>";
							var hostname = "<?php echo $contactpersoon_email[1]; ?>";
							var linktext = username + "@" + hostname ;
							document.write("<a href='" + "mail" + "to:" + username + "@" + hostname + "'>" + linktext + "</a>");
						</script>.
						</p>
						<h3>Solliciteren?</h3>
						<p>
						Je motivatie met cv kun je uiterlijk <?php echo $deadline; ?> sturen naar SIW Internationale Vrijwilligersprojecten t.a.v. <?php echo $solliciteren_naam, (($solliciteren_functie)?' ('.$solliciteren_functie.')':'');?>, 
						<script language="JavaScript">
							var username = "<?php echo $solliciteren_email[0]; ?>";
							var hostname = "<?php echo $solliciteren_email[1]; ?>";
							var linktext = username + "@" + hostname ;
							document.write("<a href='" + "mail" + "to:" + username + "@" + hostname + "'>" + linktext + "</a>");
						</script>, onder vermelding van "Sollicitatie <?php the_title();?>".</p>
						<p><?php echo $gesprekken;?></p>
						<?php else: ?>
						<h5>Het is helaas niet meer mogelijk om op deze vacature te reageren</h5>
						<?php endif; ?>
					</div>
				</div>
				<footer class="single-footer clearfix">
				<?php $categories= get_the_terms($post->ID,'soort_vacature');
				if ( $categories && ! is_wp_error( $categories ) ){
					$vacature_categories = array();
					foreach ( $categories as $category ) {
						$vacature_categories[] = $category->name;
					}
				$vacature_category = join( ", ", $vacature_categories );
				}
				if ( $vacature_category ) {
					?> <span class="postedinbottom"><i class="kt-icon-tag4"></i> <?php echo $vacature_category; ?></span><?php
				}?>
				</footer>
			</div>
		</article>
<?php endwhile; ?>