<?php
/*
(c)2015 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_action('kt_before_header_content','siw_next_event_topbar');
function siw_next_event_topbar(){
	$show_topbar_days_before_event = siw_get_show_topbar_days_before_event();
	$hide_topbar_days_before_event = siw_get_hide_topbar_days_before_event();
	$meta_query_args = array(
		'relation'	=>	'AND',
		array(
			'key'		=>	'siw_agenda_eind',
			'value'		=>	time() + ($hide_topbar_days_before_event * 24 * 60 * 60),
			'compare'	=>	'>='
		),
		array(
			'key'		=> 'siw_agenda_start',
			'value'		=> time() + ($show_topbar_days_before_event * 24 * 60 * 60),
			'compare'	=>	'<='
		),
	);
	$query_args = array(
		'post_type'				=>	'agenda',
		'posts_per_page'		=>	1,
		'post_status'			=>	'publish',
		'ignore_sticky_posts'	=>	true,
		'meta_key'				=>	'siw_agenda_start',
		'orderby'				=>	'meta_value_num',
		'order'					=>	'ASC',
		'meta_query'			=>	$meta_query_args
	);
	$siw_agenda = new WP_Query( $query_args );
	if ( $siw_agenda->have_posts()){
		while( $siw_agenda->have_posts() ): $siw_agenda->the_post();
		$start_ts = get_post_meta( get_the_ID(), 'siw_agenda_start', true );
		$end_ts = get_post_meta( get_the_ID(), 'siw_agenda_eind', true );
		$date_range = siw_get_date_range_in_text( date("Y-m-d",$start_ts),  date("Y-m-d",$end_ts), false );
		?>
<div id="topbar" class="topclass">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div id="eventbar">
	<span class="hide_on_mobile"> Maak kennis met SIW. </span>Kom naar de <a href="<?php esc_url( the_permalink() ); ?>" title="Meer informatie over de <?php esc_attr( the_title() );?>"><?php esc_html( the_title() );?></a>  <?php echo ( (date("Y-m-d", $start_ts ) == date("Y-m-d", $end_ts ) ) ?'op ':'van '), esc_html( $date_range );?>!
				</div>
			</div>
		</div>
	</div>
</div>
<?php	

		endwhile;
	}
	wp_reset_query();	
}