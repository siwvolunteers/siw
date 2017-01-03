<?php get_template_part('templates/post', 'header'); ?>

<?php global $post;
	$deadline_ts				= get_post_meta( $post->ID, 'siw_vacature_deadline', true );
	$deadline					= siw_get_date_in_text( date("Y-m-d", $deadline_ts ), false);
	$missie						= siw_get_jobs_mission_statement();
	$inleiding					= get_post_meta( $post->ID, 'siw_vacature_inleiding', true );
	$wie_zijn_wij				= siw_get_jobs_company_profile();
	$wie_ben_jij				= get_post_meta( $post->ID, 'siw_vacature_wie_ben_jij', true );
	$wat_ga_je_doen				= get_post_meta( $post->ID, 'siw_vacature_wat_ga_je_doen', true );
	$wat_bieden_wij_jou			= get_post_meta( $post->ID, 'siw_vacature_wat_bieden_wij_jou', true );
	$contactpersoon_naam		= get_post_meta( $post->ID, 'siw_vacature_contactpersoon_naam', true );
	$contactpersoon_functie		= get_post_meta( $post->ID, 'siw_vacature_contactpersoon_functie', true );
	if ( $contactpersoon_functie ){
		$contactpersoon_naam = $contactpersoon_naam . ' (' . $contactpersoon_functie . ')';
	}
	$contactpersoon_email		= get_post_meta( $post->ID, 'siw_vacature_contactpersoon_email', true );
	$contactpersoon_email		= antispambot( $contactpersoon_email );
	$contactpersoon_telefoon	= get_post_meta( $post->ID, 'siw_vacature_contactpersoon_telefoon', true );// Wordt nog niet gebruikt
	$solliciteren_naam			= get_post_meta( $post->ID, 'siw_vacature_solliciteren_naam', true );	
	$solliciteren_functie		= get_post_meta( $post->ID, 'siw_vacature_solliciteren_functie', true );
	if ( $solliciteren_functie ){
		$solliciteren_naam = $solliciteren_naam . ' (' . $solliciteren_functie . ')';
	}	
	$solliciteren_email			= get_post_meta( $post->ID, 'siw_vacature_solliciteren_email', true );
	$solliciteren_email			= antispambot( $solliciteren_email);
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
						<h3 class="job-heading"><?php esc_html_e('Vacature', 'siw');?></h3>
						<?php the_excerpt(); ?>
						<p><?php printf( esc_html__( 'Wij zijn op zoek naar%s:', 'siw' ), $meervoud ?'':__(' een', 'siw') );?></p>
						<h5 class="job-title"><?php the_title();?></h5>
						<?php echo ( $inleiding ? wp_kses_post( wpautop( $inleiding ) ) : ''  ); ?>
						<?php echo do_shortcode( $content );?>
					</div>
					<div class="col-md-5">
					<?php if( $deadline_ts >= time() ):?>
						<h3><?php esc_html_e('Meer weten?', 'siw');?></h3>
						<p>
						<?php printf( wp_kses_post( __('Voor meer informatie kun je contact opnemen met:<br />%s, <a class="email" href="mailto:%s">%s</a>', 'siw' ) ), $contactpersoon_naam,  $contactpersoon_email, $contactpersoon_email );?>
						</p>
						<h3><?php esc_html_e('Solliciteren?','siw');?></h3>
						<p>
						<?php printf( wp_kses_post( __('Je motivatie met cv kun je uiterlijk %s sturen naar %s
					, <a class="email" href="mailto:%s">%s</a> onder vermelding van "Sollicitatie %s"', 'siw' ) ), $deadline, $solliciteren_naam, $solliciteren_email, $solliciteren_email, the_title_attribute( array( 'echo' => false ) ) );?>
						</p>
						<p><?php echo esc_html( $gesprekken );?></p>
						<?php else: ?>
						<h5><?php esc_html_e('Het is helaas niet meer mogelijk om op deze vacature te reageren', 'siw');?></h5>
						<?php endif; ?>
					</div>
				</div>
				<footer class="single-footer clearfix">
					<?php do_action('siw_vacature_footer');?>
				</footer>
			</div>
		</article>
<?php endwhile; ?>