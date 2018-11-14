<?php get_template_part('templates/post', 'header'); ?>

<?php global $post;
	$job_data 		= apply_filters( 'siw_job_data', array(), $post->ID ); 
	$wie_zijn_wij	= apply_filters( 'siw_setting', false, 'company_profile' );

	$panes = array(
		array(
			'title' => __( 'Wat ga je doen?', 'siw' ),
			'content' => $job_data['wat_ga_je_doen'] . apply_filters( 'siw_list', '', $job_data['wat_ga_je_doen_lijst'] ),
		),
		array(
			'title' => __( 'Wie ben jij?', 'siw' ),
			'content' =>  $job_data['wie_ben_jij'] . apply_filters( 'siw_list', '', $job_data['wie_ben_jij_lijst'] ),
		),
		array(
			'title' => __( 'Wat bieden wij jou?', 'siw' ),
 			'content' => $job_data['wat_bieden_wij_jou'] . apply_filters( 'siw_list', '', $job_data['wat_bieden_wij_jou_lijst'] ),
		),
		array(
			'title' => __( 'Wie zijn wij?', 'siw' ),
			'content' => $wie_zijn_wij,
		),
	);
	$content = apply_filters( 'siw_accordion', '', $panes );
?>

<div id="content" class="container">
    <div class="row single-article">
		<div class="main col-md-12 kt-nosidebar" role="main">
		<?php while (have_posts()) : the_post(); ?>
		<article <?php post_class() ?> id="vacatures-<?php the_ID(); ?>">
			<div class="postclass">
				<header class="agenda-header">
					<h1><?php the_title();?></h1>
					<h5>
						<?php
						( isset( $job_data['betaald'] ) && true == $job_data['betaald'] ) ? esc_html_e( 'Betaalde functie', 'siw') : esc_html_e( 'Vrijwillige functie', 'siw');
						echo( ! empty( $job_data['uur_per_week'] ) ? SPACE . '(' . sprintf( esc_html__( '%s uur/week', 'siw') . ')', $job_data['uur_per_week'] ) : ''); ?>
					</h5>
					<hr>
				</header>
				<div class="row">
					<div class="col-md-7">
						<?php echo ( ! empty( $job_data['inleiding'] ) ? wp_kses_post( wpautop( $job_data['inleiding'] ) ) : ''  ); ?>
					</div>
					<div class="col-md-5">
						<?php if ( ! empty( $job_data['highlight_quote'] ) ): ?>
						<div class="pullquote-center vacature-quote">
							<?php echo esc_html( $job_data['highlight_quote'] );?>
						</div>
						<?php endif ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-7">
						<h3><?php esc_html_e( 'Wat houdt deze vacature in?', 'siw' );?></h3>
						<?php echo do_shortcode( $content );?>
					</div>
					<div class="col-md-5">
					<?php if( $job_data['deadline_datum'] >= date('Y-m-d') ):?>
						<h3><?php esc_html_e('Meer weten?', 'siw');?></h3>
						<p>
						<?php printf( wp_kses_post( __( 'Voor meer informatie kun je contact opnemen met:', 'siw' ) . '<br />%s, <a class="email" href="mailto:%s">%s</a>'), $job_data['contactpersoon_naam'],  $job_data['contactpersoon_email'], $job_data['contactpersoon_email'] );?>
						</p>
						<h3><?php esc_html_e( 'Solliciteren?','siw' );?></h3>
						<p>
						<?php printf( wp_kses_post( __( 'Je motivatie met cv kun je uiterlijk %s sturen naar:<br />%s, <a class="email" href="mailto:%s">%s</a> onder vermelding van "Sollicitatie %s"', 'siw' ) ), $job_data['deadline'], $job_data['solliciteren_naam'], $job_data['solliciteren_email'], $job_data['solliciteren_email'], the_title_attribute( array( 'echo' => false ) ) );?>
						</p>
						<p><?php echo wp_kses_post( $job_data['toelichting_solliciteren'] );?></p>
						<?php else: ?>
						<h5><?php esc_html_e( 'Het is helaas niet meer mogelijk om op deze vacature te reageren', 'siw' );?></h5>
						<?php endif; ?>
					</div>
				</div>
				<footer class="single-footer clearfix">
					<?php do_action( 'siw_vacature_footer' );?>
				</footer>
			</div>
			<?php echo $job_data['json_ld']?>
		</article>
<?php endwhile; ?>
