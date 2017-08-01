<?php
/*
 * (c)2017 SIW Internationale Vrijwilligersprojecten
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class om functies uit functionality-plugin aan te roepen
 */
class SIW_Plugin {

	private static $instance;

	/**
	* Creates or returns an instance of this class.
	*
	* @return  Foo A single instance of this class.
	*/
	public static function get_instance() {

		if ( null == self::$instance ) {
			self::$instance = new SIW_Plugin;
		}

		return self::$instance;

	}

	private function __construct() {

	}

	/*--------------------------------------------*
	* Functions
	*--------------------------------------------*/
	public static function siw_show_quick_search_widget() {
		if ( ! function_exists( '\siw_show_quick_search_widget' ) ) {
			return;
		}
		\siw_show_quick_search_widget();
	}

	public static function siw_get_setting( $setting ) {
		if ( ! function_exists( '\siw_get_setting' ) ) {
			return;
		}
		return \siw_get_setting( $setting );

	}

	public static function siw_get_date_in_text( $date, $year = true ) {
		if ( ! function_exists( '\siw_get_date_in_text' ) ) {
			return;
		}
		return \siw_get_date_in_text( $date, $year = true );
	}

	public static function siw_get_date_range_in_text( $date_start, $date_end, $year = true ) {
		if ( ! function_exists( '\siw_get_date_range_in_text' ) ) {
			return;
		}
		return \siw_get_date_range_in_text( $date_start, $date_end, $year = true );
	}

	public static function siw_get_job_data( $post_id ) {
		if ( ! function_exists( '\siw_get_job_data' ) ) {
			return;
		}
		return \siw_get_job_data( $post_id );
	}

	public static function siw_get_event_data( $post_id ) {
		if ( ! function_exists( '\siw_get_event_data' ) ) {
			return;
		}
		return \siw_get_event_data( $post_id );
	}

	public static function siw_wc_email_show_project_details( $order, $application_number ) {
		if ( ! function_exists( '\siw_wc_email_show_project_details' ) ) {
			return;
		}
		return \siw_wc_email_show_project_details( $order, $application_number );
	}

	public static function siw_wc_email_show_application_details( $order ) {
		if ( ! function_exists( '\siw_wc_email_show_application_details' ) ) {
			return;
		}
		return \siw_wc_email_show_application_details( $order );
	}

	public static function siw_get_age_from_date( $date ) {
		if ( ! function_exists( '\siw_get_age_from_date' ) ) {
			return;
		}
		return \siw_get_age_from_date( $date );
	}



}
