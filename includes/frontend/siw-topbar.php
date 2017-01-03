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
			'value'		=>	strtotime( date("Y-m-d") ) + ( $hide_topbar_days_before_event * DAY_IN_SECONDS ),
			'compare'	=>	'>='
		),
		array(
			'key'		=> 'siw_agenda_start',
			'value'		=> strtotime( date("Y-m-d") ) + ( $show_topbar_days_before_event * DAY_IN_SECONDS ),
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
		'meta_query'			=>	$meta_query_args,
		'fields' 				=> 'ids'
	);
	$next_event_for_topbar = get_posts( $query_args );
	
	if(!empty( $next_event_for_topbar ) ){
		$post_id = $next_event_for_topbar[0];
		$start_ts = get_post_meta( $post_id, 'siw_agenda_start', true );
		$end_ts = get_post_meta( $post_id, 'siw_agenda_eind', true );
		$date_range = siw_get_date_range_in_text( date("Y-m-d",$start_ts),  date("Y-m-d",$end_ts), false );
		$permalink = get_permalink( $post_id );
		$title = get_the_title( $post_id );
	?>
<div id="topbar" class="topclass">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div id="eventbar">
	<span class="hide_on_mobile"> Maak kennis met SIW. </span>Kom naar de <a id="topbar_link" href="<?php echo esc_url( $permalink ); ?>" title="Meer informatie over de <?php echo esc_attr( $title );?>"><?php echo esc_html( $title );?></a>  <?php echo ( (date("Y-m-d", $start_ts ) == date("Y-m-d", $end_ts ) ) ?'op ':'van '), esc_html( $date_range );?>!
				</div>
			</div>
		</div>
	</div>
</div>
<?php	
	}
}