<?php get_template_part('templates/post', 'header'); ?>

<?php global $post;

	$work ='
	<ul>
	<li>Recreational and educational activities for the the promotion of interculturality, legality and civil coexistence, and active citizenship at the youth Centro “Il Cantiere” for children, youngsters and adults</li>
	<li>"ATM Europe": promotion of the Erasmus plus and the EVS among young people of Campania</li>
	<li>Support for the management of the fields of international volunteering</li>
	<li>workshops, seminars and conferences relating to inter-culture, democracy, intercultural education, the environment, , at the Youth Center "Il Cantiere", where even attend courses in Italia</li>
	<li>Events of territorial to sensitize the citizens on issues of social and cultural interest</li>
	<li>Best sports and recreational and educational for children</li>
	<li>Best non-formal education for pupils rights "The Road of Rights", in connection with the local schools</li>
	<li>Support and participation in workshops of Virtual Mobility, using social networks and metaverses (like Second Life)</li>
	<li>Programming, launch and promotion of a program of performances at the "TAV – Teatro, animazione, visioni" at the Youth Center "Il Cantiere" in Frattamaggiore (NA)</li>
	<li>Laboratory theatrical and artistic-cultural, including a workshop of Theatre of the Oppressed and one of the Living Theatre</li>
	</ul>
	';
	$organisation = '
Cantiere Giovani is a Social cooperative born in 2001. Cantiere Giovani is fostering new socio-cultural and educational opportunities at a local, national and international level. The purpose is to bring out the best in young people, especially the ones with fewer opportunities: to give them the opportunity to develop skills, take part to life-changing experiences, provide them with tools to realize their ambitions and hopes.

	';

	$requirements = '
	<ul>
	<li>Work in a team</li>
	<li>Self-management skills</li>
	<li>Respect the objectives and deadlines</li>
	<li>Intercultural approach to activities with the local community</li>
	</ul>
';




	$content = '[accordion]';
	$content .= '[pane title="'. esc_html__('Wat ga je doen?', 'siw' ) . '"]' . wp_kses_post( wpautop( $work ) ) . '[/pane]';
	$content .= '[pane title="'. esc_html__('Bij welke organisatie ga je werken?', 'siw' ) . '"]' . wp_kses_post( wpautop( $organisation ) ). '[/pane]';
	$content .= '[pane title="'. esc_html__('Wat zijn de vereisten?', 'siw' ) . '"]' . wp_kses_post( wpautop( $requirements ) ) . '[/pane]';
	$content .= '[/accordion]';


?>

<div id="content" class="container">
    <div class="row single-article">
		<div class="main col-md-12 kt-nosidebar" role="main">
		<?php while (have_posts()) : the_post(); ?>
		<article <?php post_class() ?> id="evs-project-<?php the_ID(); ?>">
			<div class="postclass">
				<header class="agenda-header">
					<h1>Roemenië | Onderwijs, cultuur en sport</h1>
					<h5>Augustus 2017 – november 2017</h5>
					<div class="pullquote-center vacature-quote">
						Hier kan een wervende quote komen
					</div>
					<hr>
				</header>
				<div class="row">
					<div class="col-md-7">
						<div class="row">
							<div class="col-xs-3">
								<p><b>Beschrijving</b></p>
							</div>
							<div class="col-xs-9">
								Vanaf augustus 2017 kun jij 3,5 maand naar het onderwijsproject “InterACT with me” in Boekarest!
							</div>
						</div>
						<div class="row">
							<div class="col-xs-3">
								<p><b>Locatie</b></p>
							</div>
							<div class="col-xs-9">
								Boekarest, Roemenië
							</div>
						</div>
						<div class="row">
							<div class="col-xs-3">
								<p><b>Tijdsduur</b></p>
							</div>
							<div class="col-xs-9">
								Augustus 2017 – november 2017 (3,5 maand)
							</div>
						</div>
						<div class="row">
							<div class="col-xs-3">
								<p><b>Deadline</b></p>
							</div>
							<div class="col-xs-9">
								Zo snel mogelijk
							</div>
						</div>
						<?php echo do_shortcode( $content );?>
					</div>
					<div class="col-md-5">
<?php the_post_thumbnail();?>
						<p>
					</div>
				</div>
				<footer class="single-footer clearfix">
					<?php do_action('siw_agenda_footer');?>
				</footer>
			</div>
		</article>
<?php endwhile; ?>
