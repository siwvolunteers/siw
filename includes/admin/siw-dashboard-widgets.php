<?php
/*
(c)2015 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
Dashboard widgets
**/

add_action( 'admin_enqueue_scripts', 'siw_dashboard_widget_display_enqueues' );
function siw_dashboard_widget_display_enqueues( $hook ) {
	if( 'index.php' != $hook ) {
		return;
	}
	wp_enqueue_style( 'dashboard-widget-styles', get_stylesheet_directory_uri() . '/assets/css/siw-dashboard-widgets.css' );
}


add_action( 'wp_dashboard_setup', 'siw_register_evs_applications_widget' );
function siw_register_evs_applications_widget() {
	wp_add_dashboard_widget(
		'siw_evs_applications_widget',
		'EVS aanmeldingen',
		'siw_display_applications_widget',
		'',
		array(
			'applications' => siw_get_vfb_applications_per_month('evs', 5),
		)			
	);
}

add_action( 'wp_dashboard_setup', 'siw_register_op_maat_applications_widget' );
function siw_register_op_maat_applications_widget() {
	wp_add_dashboard_widget(
		'siw_op_maat_applications_widget',
		'Op maat aanmeldingen',
		'siw_display_applications_widget',
		'',
		array(
			'applications' => siw_get_vfb_applications_per_month('op_maat', 5),
		)
	);
}

add_action( 'wp_dashboard_setup', 'siw_register_community_day_applications_widget' );
function siw_register_community_day_applications_widget() {
	wp_add_dashboard_widget(
		'siw_community_day_applications_widget',
		'Community Day aanmeldingen',
		'siw_display_applications_widget',
		'',
		array(
			'applications' => siw_get_vfb_applications_per_month('community_day', 5),
		)
	);
}

add_action( 'wp_dashboard_setup', 'siw_register_woocommerce_applications_widget' );
function siw_register_woocommerce_applications_widget() {
	wp_add_dashboard_widget(
		'siw_woocommerce_applications_widget',
		'Groepsprojecten aanmeldingen',
		'siw_display_applications_widget',
		'',
		array(
			'applications' => siw_get_woocommerce_application_per_month(5),
		)
	);
}

function siw_display_applications_widget( $var, $args ) {
	$applications=$args['args']['applications'];
	$months = siw_get_array('months');
	if (!empty($applications)){
		
		foreach ( $applications as $application ){
			$application_months[] = $months[ $application['application_month'] ] . ' (' . $application['application_count'] . ')';
			$application_counts[] = (integer) $application['application_count'];
		}
		$application_months = array_reverse( $application_months );
		$application_counts = array_reverse( $application_counts );
		
		$highest_value = max( $application_counts );
		$data_points = count( $application_counts );
		$bar_width = 100 / $data_points - 2;
		$total_height = 120;
		?>
		<div class="comment-stat-bars" style="height:<?php echo $total_height ?>px;">
			<?php
				foreach( $application_counts as $count ) :
					$count_percentage = $count/$highest_value;
					$bar_height = $total_height * $count_percentage;
					$border_width = $total_height - $bar_height;
			?>
			<div class="comment-stat-bar" style="height:<?php echo $total_height ?>px; border-top-width:<?php echo $border_width ?>px; width: <?php echo $bar_width ?>%;"></div>
			<?php endforeach ?>
		</div>
		<div class='comment-stat-labels'>
			<?php foreach( $application_months as $month ) : ?>
			<div class='comment-stat-label' style='width: <?php echo $bar_width ?>%;'><?php echo $month ?></div>
		<?php endforeach ?>
		</div>
		<div class='comment-stat-caption'>Aanmeldingen van de afgelopen <?php echo $data_points?> maanden</div>

		<?php 
	}
	else{
	?>
	<div class='comment-stat-caption'>Geen aanmeldingen gevonden</div>
	<?php
	}
}




function siw_get_vfb_applications_per_month( $form, $results ){
	$form_id = siw_get_vfb_form_id($form);
	global $wpdb;
	$query =	"SELECT Year($wpdb->posts.post_date)  AS application_year, 
						Month($wpdb->posts.post_date) AS application_month, 
						Count(*) 				      AS application_count 
					FROM   $wpdb->posts 
						JOIN $wpdb->postmeta 
							ON $wpdb->posts.id = $wpdb->postmeta.post_id 
					WHERE  $wpdb->posts.post_type = 'vfb_entry' 
						AND $wpdb->postmeta.meta_key = '_vfb_form_id' 
						AND $wpdb->postmeta.meta_value = %d 
					GROUP  BY application_year, 
							application_month 
					ORDER  BY application_year DESC, 
							application_month DESC 
					LIMIT  %d; ";

	$applications = $wpdb->get_results( $wpdb->prepare( $query, $form_id, $results ), ARRAY_A);

	return $applications;
}


function siw_get_woocommerce_application_per_month( $results ){

	global $wpdb;
	$query =	"SELECT Year($wpdb->posts.post_date)  AS application_year, 
						Month($wpdb->posts.post_date) AS application_month, 
						Count(*) 				      AS application_count 
					FROM   $wpdb->posts 
					WHERE  $wpdb->posts.post_type = 'shop_order' 
						AND $wpdb->posts.post_status IN ('wc-processing', 'wc-completed') 
					GROUP  BY application_year, 
							application_month 
					ORDER  BY application_year DESC, 
							application_month DESC 
					LIMIT  %d; ";
	$applications_per_month = $wpdb->get_results( $wpdb->prepare( $query, $results ), ARRAY_A);
	return $applications_per_month;

}
