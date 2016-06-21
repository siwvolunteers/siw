<?php
/*
(c)2016 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp', 'siw_schedule_custom_cron_jobs' );
function siw_schedule_custom_cron_jobs() {
	$cron_time = siw_get_cron_time();	
	$cron_timestamp = strtotime( 'tomorrow ' . $cron_time);
	
	//no_index zetten voor afgelopen evenementen
	if ( ! wp_next_scheduled( 'siw_no_index_past_events' ) ) {
		wp_schedule_event( $cron_timestamp , 'daily', 'siw_no_index_past_events' );
	}
	
	//no_index zetten voor verlopen vacatures
	if ( ! wp_next_scheduled( 'siw_no_index_expired_jobs' ) ) {
		wp_schedule_event( $cron_timestamp , 'daily', 'siw_no_index_expired_jobs' );
	}	
	//opties in aanmeldformulier Community Day bijwerken.
	if ( ! wp_next_scheduled( 'siw_update_community_day_options' ) ) {
		wp_schedule_event( $cron_timestamp , 'daily', 'siw_update_community_day_options' );
	}	
}
//CD-formulier
add_action('siw_update_community_day_options', 'siw_update_community_day_options');

add_action('siw_no_index_past_events', 'siw_no_index_past_events');
function siw_no_index_past_events(){
	$args = array(
		'post_type'			=> 'agenda',
		'fields'			=> 'ids',
		'posts_per_page'	=> -1,
	);
	$events = get_posts( $args ); 
	foreach ( $events as $event_id ) {
		$noindex = 0;
		$start_ts = get_post_meta( $event_id, 'siw_agenda_start', true );
		if ( $start_ts < time()){
			$noindex = 1;
		}
		update_post_meta( $event_id, '_yoast_wpseo_meta-robots-noindex', $noindex);
		
	}
}

add_action('siw_no_index_expired_jobs', 'siw_no_index_expired_jobs');
function siw_no_index_expired_jobs(){
	$args = array(
		'post_type'			=> 'vacatures',
		'fields'			=> 'ids',
		'posts_per_page'	=> -1,
	);
	$jobs = get_posts( $args ); 
	foreach ( $jobs as $job_id ){
		$noindex = 0;
		$deadline_ts = get_post_meta( $job_id, 'siw_vacature_deadline', true );
		if ( $deadline_ts < time()){
			$noindex = 1;
		}
		update_post_meta( $job_id, '_yoast_wpseo_meta-robots-noindex', $noindex);		
	}
}
