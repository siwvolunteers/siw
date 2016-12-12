<?php get_template_part('templates/post', 'header'); ?>

<?php global $post;
	$deadline_ts				= get_post_meta( $post->ID, 'siw_vacature_deadline', true );
	$deadline					= siw_get_date_in_text( date("Y-m-d",$deadline_ts), false);
	$missie						= siw_get_jobs_mission_statement();
	$inleiding					= get_post_meta( $post->ID, 'siw_vacature_inleiding', true );
	$wie_zijn_wij				= siw_get_jobs_company_profile();
	$wie_ben_jij				= get_post_meta( $post->ID, 'siw_vacature_wie_ben_jij', true );
	$wat_ga_je_doen				= get_post_meta( $post->ID, 'siw_vacature_wat_ga_je_doen', true );
	$wat_bieden_wij_jou			= get_post_meta( $post->ID, 'siw_vacature_wat_bieden_wij_jou', true );
	$contactpersoon_naam		= get_post_meta( $post->ID, 'siw_vacature_contactpersoon_naam', true );
	$contactpersoon_functie		= get_post_meta( $post->ID, 'siw_vacature_contactpersoon_functie', true );
	$contactpersoon_email		= get_post_meta( $post->ID, 'siw_vacature_contactpersoon_email', true );
	$contactpersoon_telefoon	= get_post_meta( $post->ID, 'siw_vacature_contactpersoon_telefoon', true );
	$solliciteren_naam			= get_post_meta( $post->ID, 'siw_vacature_solliciteren_naam', true );	
	$solliciteren_functie		= get_post_meta( $post->ID, 'siw_vacature_solliciteren_functie', true );	
	$solliciteren_email			= get_post_meta( $post->ID, 'siw_vacature_solliciteren_email', true );
	$gesprekken					= get_post_meta( $post->ID, 'siw_vacature_gesprekken', true );
	$meervoud					= get_post_meta( $post->ID, 'siw_vacature_meervoud', true );
	
	$content = '[accordion]';
	$content .= '[pane title="Wie zijn wij?"]' . wp_kses_post( wpautop( $missie ) . wpautop( $wie_zijn_wij ) ) . '[/pane]';
	$content .= '[pane title="Wie ben jij?"]' . wp_kses_post( wpautop( $wie_ben_jij ) ). '[/pane]';
	$content .= '[pane title="Wat ga je doen?"]' . wp_kses_post( wpautop( $wat_ga_je_doen ) ) . '[/pane]';
	$content .= '[pane title="Wat bieden wij jou?"]' . wp_kses_post( wpautop( $wat_bieden_wij_jou ) ) . '[/pane]';
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
						<?php the_excerpt(); ?>
						<p><?php echo 'Wij zijn op zoek naar', ( $meervoud ?':':' een:');?></p>
						<h5 class="job-title"><?php the_title();?></h5>
						<?php echo ($inleiding ? wp_kses_post( wpautop( $inleiding ) ) : ''  ); ?>
						<?php echo do_shortcode( $content );?>
					</div>
					<div class="col-md-5">
					<?php if( $deadline_ts >= time() ):?>
						<h3>Meer weten?</h3>
						<p>Voor meer informatie kun je contact opnemen met:<br />
						<?php echo $contactpersoon_naam, (( $contactpersoon_functie )?' ('. esc_html( $contactpersoon_functie ) . '), ':', '), '<a class="email" href="mailto:', antispambot( $contactpersoon_email ), '">', antispambot( $contactpersoon_email ),'</a>';?>
						</p>
						<h3>Solliciteren?</h3>
						<p>
						<?php echo 'Je motivatie met cv kun je uiterlijk ', $deadline, ' sturen naar ', $solliciteren_naam, (( $solliciteren_functie )?' (' . esc_html($solliciteren_functie) . ')':''), 
						', <a class="email" href="mailto:', antispambot( $solliciteren_email ),'">', antispambot( $solliciteren_email ), '</a>, onder vermelding van "Sollicitatie ', the_title_attribute(),'".</p>';?>
						<p><?php echo esc_html( $gesprekken );?></p>
						<?php else: ?>
						<h5>Het is helaas niet meer mogelijk om op deze vacature te reageren</h5>
						<?php endif; ?>
					</div>
				</div>
				<footer class="single-footer clearfix">
					<?php do_action('siw_vacature_footer');?>
				</footer>
			</div>
		</article>
<?php endwhile; ?>