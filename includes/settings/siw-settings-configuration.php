<?php
/*
(c)2016 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
function siw_settings_show_configuration_section( $opt_name ) {
	/* Velden*/
	$email_fields[] = array(
		'id'			=> 'coordinator_op_maat_email',
		'type'			=> 'text',
		'title'			=> __( 'Coördinator op maat', 'siw' ),
		'validate'		=> 'email',
	);

	/* Secties */
	Redux::setSection( $opt_name, array(
		'title'			=> __( 'Configuratie', 'siw' ),
		'id'			=> 'configuration',
		'icon'			=> 'el el-cogs',
	));

	Redux::setSection( $opt_name, array(
		'title'			=> __( 'E-mailadressen', 'siw' ),
		'id'			=> 'email',
		'subsection'	=> true,
		'fields'		=> $email_fields,
	));
}
