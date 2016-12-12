<?php
/*
(c)2016 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Redux' ) ) {
	return;
}

$opt_name = 'siw';
$theme = wp_get_theme();
$args = array(
	'opt_name'				=> $opt_name,
	'display_name'			=> $theme->get( 'Name' ),
	'display_version'		=> $theme->get( 'Version' ),
	'menu_type'				=> 'menu',
	'menu_title'			=> __( 'Instellingen SIW', 'siw' ),
	'page_title'			=> __( 'Instellingen SIW', 'siw' ),
	'admin_bar'				=> false,
	'dev_mode'				=> false,
	'page_priority'			=> null,
	'page_permissions'		=> 'manage_options',
	'page_icon'				=> 'icon-themes', //TODO welke icons kan je hier gebruiken?
	'page_slug'				=> 'siw-settings',
	'save_defaults'			=> true,
	'default_mark'			=> '',
	'show_import_export'	=> false,
	'transient_time'		=> 60 * MINUTE_IN_SECONDS,
	'footer_credit'			=> '&nbsp;',
	'use_cdn'				=> false,
	'hide_expand'			=> true,
	'hide_reset'			=> true,
);


/*
includes voor secties
*/

require_once('siw-settings-configuration.php');
require_once('siw-settings-countries.php');





	Redux::setArgs( $opt_name, $args );
    



/*
Bepaal volgorde van secties
*/	

	/*Configuratie*/
	siw_settings_show_configuration_section( $opt_name );
	/*Landen*/
	siw_settings_show_countries_section( $opt_name );

