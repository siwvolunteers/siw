<?php
/*
(c)2016 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
function siw_settings_show_countries_section( $opt_name ) {

	/* Sorteer landen op naam i.p.v. op code	*/
	$project_countries = siw_get_array('project_countries');

	$country_name = array();
	foreach ( $project_countries as $key => $row ) {
		$country_name[ $key ] = $row['name'];
	}
	array_multisort( $country_name, SORT_ASC, $project_countries );


	/*
	Velden
	*/
	foreach ( $project_countries as $country ) {
		$slug = $country['slug'];
		$name = $country['name'];
		$allowed = ('yes' == $country['allowed'] )? true : false;
		$continent = $country['continent'];
		$country_fields[ $continent ][] = array(
			'id'		=> $slug . '_section_start',
			'type'		=> 'section',
			'title'		=> $name,
			'indent'	=> true,
		);
		$country_fields[ $continent ][] = array(
			'id'			=> $slug . '_allowed',
			'type'			=> 'switch',
			'title'			=> __( 'Toegestaan', 'siw' ),
			'on'			=> 'Ja',
			'off'			=> 'Nee',
			'default'		=> $allowed,
		);
		$country_fields[ $continent ][] = array(
			'id'			=> $slug . '_email',
			'type'			=> 'text',
			'title'			=> __( 'E-mailadres regiospecialist', 'siw' ),
			'validate'		=> 'email',
			'required'		=> array( $slug . '_allowed','equals', true)
		);
		$country_fields[ $continent ][] = array(
			'id'		=> $slug . '_section_end',
			'type'		=> 'section',
			'indent'	=> false,
		);
	}

	/* Secties */
	Redux::setSection( $opt_name, array(
		'title'			=> __( 'Landen', 'siw' ),
		'id'			=> 'countries',
		'icon'			=> 'el el-globe',//TODO
	));
	Redux::setSection( $opt_name, array(
		'title'			=> __( 'Afrika', 'siw' ),
		'id'			=> 'countries_afrika',
		'subsection'	=> true,
		'fields'		=> $country_fields['afrika-midden-oosten']	,
	));
	Redux::setSection( $opt_name, array(
		'title'			=> __( 'Azië', 'siw' ),
		'id'			=> 'countries_asia',
		'subsection'	=> true,
		'fields'		=> $country_fields['azie']	,
	));
	Redux::setSection( $opt_name, array(
		'title'			=> __( 'Europa', 'siw' ),
		'id'			=> 'countries_europe',
		'subsection'	=> true,
		'fields'		=> $country_fields['europa']	,
	));
	Redux::setSection( $opt_name, array(
		'title'			=> __( 'Latijns-America', 'siw' ),
		'id'			=> 'countries_latin_america',
		'subsection'	=> true,
		'fields'		=> $country_fields['latijns-amerika']	,
	));
	Redux::setSection( $opt_name, array(
		'title'			=> __( 'Noord-Amerika', 'siw' ),
		'id'			=> 'countries_north_america',
		'subsection'	=> true,
		'fields'		=> $country_fields['noord-amerika']	,
	));
}
