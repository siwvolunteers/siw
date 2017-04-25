<?php get_template_part('templates/post', 'header'); ?>

<?php global $post;
	$job_data 		= SIW_PLUGIN::siw_get_job_data( $post->ID );
	$missie			= SIW_PLUGIN::siw_get_setting('mission_statement');
	$wie_zijn_wij	= SIW_PLUGIN::siw_get_setting('company_profile');

	$content = '[accordion]';
	$content .= '[pane title="Wie zijn wij?"]' . wp_kses_post( wpautop( $missie ) . wpautop( $wie_zijn_wij ) ) . '[/pane]';
	$content .= '[pane title="Wie ben jij?"]' . wp_kses_post( wpautop( $job_data['wie_ben_jij'] ) ). '[/pane]';
	$content .= '[pane title="Wat ga je doen?"]' . wp_kses_post( wpautop( $job_data['wat_ga_je_doen'] ) ) . '[/pane]';
	$content .= '[pane title="Wat bieden wij jou?"]' . wp_kses_post( wpautop( $job_data['wat_bieden_wij_jou'] ) ) . '[/pane]';
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
						<p><?php printf( esc_html__( 'Wij zijn op zoek naar%s:', 'siw' ), $job_data['meervoud'] ?'':__(' een', 'siw') );?></p>
						<h5 class="job-title"><?php the_title();?></h5>
						<?php echo ( $job_data['inleiding'] ? wp_kses_post( wpautop( $job_data['inleiding'] ) ) : ''  ); ?>
						<?php echo do_shortcode( $content );?>
					</div>
					<div class="col-md-5">
					<?php if( $job_data['deadline_datum'] >= date('Y-m-d') ):?>
						<h3><?php esc_html_e('Meer weten?', 'siw');?></h3>
						<p>
						<?php printf( wp_kses_post( __('Voor meer informatie kun je contact opnemen met:<br />%s, <a class="email" href="mailto:%s">%s</a>', 'siw' ) ), $job_data['contactpersoon_naam'],  $job_data['contactpersoon_email'], $job_data['contactpersoon_email'] );?>
						</p>
						<h3><?php esc_html_e('Solliciteren?','siw');?></h3>
						<p>
						<?php printf( wp_kses_post( __('Je motivatie met cv kun je uiterlijk %s sturen naar:<br />%s, <a class="email" href="mailto:%s">%s</a> onder vermelding van "Sollicitatie %s"', 'siw' ) ), $job_data['deadline'], $job_data['solliciteren_naam'], $job_data['solliciteren_email'], $job_data['solliciteren_email'], the_title_attribute( array( 'echo' => false ) ) );?>
						</p>
						<p><?php echo wp_kses_post( $job_data['toelichting_solliciteren'] );?></p>
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
