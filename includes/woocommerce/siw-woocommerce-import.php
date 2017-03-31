<?php
/*
(c)2015-2016 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/*
functies voor de import van projecten uit PLATO
*/

//zet datum in yy-mm-dd om in dd-mm-yy
function siw_wc_format_date( $date ) {
	$formatted_date = '';
	if ( '1970-01-01' != $date ){
		$formatted_date = date("j-n-Y", strtotime($date));
	}
    return $formatted_date;
}

function siw_wc_date_to_month( $date ) {
	$month = '';
	if ( '1970-01-01' != $date ){
		$month = date("Ym", strtotime( $date ) );
	}
	return $month;
}

function siw_wc_project_duration_in_days( $startdate, $enddate ) {
	$startdate = strtotime( $startdate );
	$enddate = strtotime( $enddate );
	$duration_in_seconds = $enddate - $startdate;
	$project_duration_in_days = round( $duration_in_seconds / DAY_IN_SECONDS );
	return $project_duration_in_days;
}

function siw_wc_project_work( $work_codes, $format='string' ) {
	$work_types = explode(",", $work_codes);
	$project_work = siw_get_array('project_work');
	$work='';

	foreach ($work_types as $work_type) {
		if ( isset ( $project_work[ $work_type ]) ) {
			$work .= $project_work[ $work_type ]."|";
		}
	}

	if ( 'array' == $format ) {
		$work = explode("|", $work);
	}
	return $work;
}


function siw_wc_continent_from_country( $country_code ) {
	$continent = '';
	$project_countries = siw_get_array('project_countries');
	if ( isset ( $project_countries[ $country_code ]['continent'] ) ) {
		$continent = $project_countries[ $country_code ]['continent'];
	}
	return $continent;
}

function siw_wc_country( $country_code ) {
	$country = '';
	$project_countries = siw_get_array('project_countries');
	if ( isset ( $project_countries[ $country_code ]['slug'] ) ) {
		$country = $project_countries[ $country_code ]['slug'];
	}
	return $country;
}

//functie om landnaam te bepalen

function siw_wc_country_name( $country_code ) {
	$country_name = '';
	$project_countries = siw_get_array('project_countries');
	if ( isset ( $project_countries[ $country_code ]['name'] ) ) {
		$country_name = $project_countries[ $country_code ]['name'];
	}
	return $country_name;
}

/*bepalen of land toegestaan is/aangeboden wordt*/
function siw_wc_is_country_allowed( $country_code ) {
	$allowed = 'no';
	$project_countries = siw_get_array('project_countries');
	if ( isset ( $project_countries[ $country_code ]['allowed'] ) ) {
		$allowed = $project_countries[ $country_code ]['allowed'];
	}
	return $allowed;
}



function siw_wc_local_fee( $local_fee_amount, $local_fee_currency ) {
	$project_currencies = siw_get_array('project_currencies');
	$local_fee_amount = (float) $local_fee_amount;
	$local_fee = '';
	$local_fee_currency_symbol = $local_fee_currency;
	$local_fee_currency_name = '';
	if ( isset( $project_currencies[ $local_fee_currency ] ) ) {
		$local_fee_currency_symbol = $project_currencies[ $local_fee_currency ]['symbol'];
		$local_fee_currency_name = ' (' . $project_currencies[ $local_fee_currency ]['name'] . ')';
	}
	if ( $local_fee_amount > 0) {
		$local_fee = $local_fee_currency_symbol . ' ' . $local_fee_amount . $local_fee_currency_name;
	}
	return $local_fee;
}

function siw_wc_number_of_volunteers( $volunteers_total, $volunteers_male, $volunteers_female ){
	$volunteers_male = (integer) $volunteers_male;
	$volunteers_female = (integer) $volunteers_female;
	$volunteers_total = (integer) $volunteers_total;
	if ( 1 == $volunteers_male){
		$male_label = " man";
	}
	else{
		$male_label = " mannen";
	}
	if ( 1 == $volunteers_female ) {
		$female_label = " vrouw";
	}
	else{
		$female_label = " vrouwen";
	}
	$number_of_volunteers = $volunteers_total;

	//alleen specificatie als totaal aantal vrijwilligers overeenkomt met som van aantal mannen en aantal vrouwen.
	if ( $volunteers_total == ( $volunteers_male + $volunteers_female ) ) {
		$number_of_volunteers .= " (" . $volunteers_male.$male_label . " en " . $volunteers_female . $female_label . ")";
	}
	return $number_of_volunteers;
}


function siw_wc_free_places_left( $free_places_male, $free_places_female ) {
	$free_places_left = 'yes';
	$free_places_male = (integer) $free_places_male;
	$free_places_female = (integer) $free_places_female;
	$free_places_total = $free_places_male + $free_places_female;
	if ( $free_places_total <= 0){
		$free_places_left = 'no';
	}
	return $free_places_left;
}

function siw_wc_age_range( $minimum_age, $maximum_age ) {
	$minimum_age = (integer) $minimum_age;
	$maximum_age = (integer) $maximum_age;
	if ($minimum_age < 1){
		$minimum_age = 18;
	}
	if ($maximum_age < 1){
		$maximum_age = 99;
	}
	$age_range = '';
	$age_range = $minimum_age . " t/m " . $maximum_age . " jaar";
	return $age_range;
}

function siw_wc_is_family_project( $family, $project_type ){
	$family = (bool) $family;
	$family_project = '';
	if ( $family or 'FAM' == $project_type ){
		$family_project = 'familie';
	}
	return $family_project;
}


function siw_wc_is_teenage_project( $minimum_age, $maximum_age, $project_type ) {
	$minimum_age = (integer) $minimum_age;
	$maximum_age = (integer) $maximum_age;
	$teenage_project = '';
	if ( ( $minimum_age < 17 and $minimum_age > 12 and $maximum_age < 20 ) or 'TEEN' == $project_type ) {
		$teenage_project = 'tieners';
	}
	return $teenage_project;
}


function siw_wc_project_languages( $languages ) {
	$language_codes = explode(",", $languages );
	$project_languages = siw_get_array('project_languages');
	$languages = '';
	foreach ( $language_codes as $code ) {
		$languages .= $project_languages[strtoupper( $code )] . "|";
	}
	return $languages;

}

//functie om soort werk te tonen
function siw_wc_project_work_in_text( $work_codes, $number ) {
	$work_array = siw_wc_project_work( $work_codes, 'array' );
	$project_work_in_text = $work_array[0];
	if ( ( $number > 1) && ( $work_array[1] != '') ) {
		$project_work_in_text .= ' en ' . $work_array[1];
	}

	return $project_work_in_text;
}


//functie om projectnaam te genereren
function siw_wc_project_name( $country_code, $work_codes ) {
	$country = siw_wc_country_name( $country_code );
	$work = siw_wc_project_work_in_text( $work_codes,2);
	$project_name = $country . ' | ' . ucfirst( $work );
	return $project_name;
}

//functie om te bepalen of een Verklaring omtrent het gedrag vereist is
function siw_wc_is_vog_required( $work_codes ) {
	$work_array = explode(",", $work_codes );
	$is_vog_required = '';
	if ( in_array('KIDS', $work_array ) ) {
		$is_vog_required = 'Ja';
	}
	return $is_vog_required;
}

//functie om te bepalen of project direct gepubliceerd mag worden
function siw_wc_determine_post_status( $work_codes, $country ) {
	$post_status = 'publish';
	$work_array = explode(",", $work_codes );
	$allowed = siw_wc_is_country_allowed( $country );
	if ( in_array('KIDS', $work_array ) && 'yes' == $allowed ) {
		$post_status = 'draft';
	}
	return $post_status;
}


//projectlocatie
function siw_wc_is_project_location_available( $latitude ){
	$latitude = (float) $latitude;
	$project_location_available = '';
	if ( 0 != $latitude ) {
		$project_location_available = 'Locatie';
	}
	return $project_location_available;
}

function siw_wc_project_location_map( $latitude, $longitude ) {
	$project_location_map = '';
	$project_location_available = siw_wc_is_project_location_available( $latitude );
	if ( 'Locatie' == $project_location_available ) {
		$project_location_map='\[gmap address="' . $latitude . ',' . $longitude . '" title="Projectlocatie" zoom="7" maptype="ROADMAP"]';
	}
	return $project_location_map;
}


//functie om zin met projectduur te genereren
function siw_wc_project_duration_in_text( $startdate, $enddate ) {
	$month_array = siw_get_array('month_to_text');
	$end_day = date("j", strtotime( $enddate ));
	$end_month = date("n",strtotime( $enddate ));
	$start_day = date("j", strtotime( $startdate ));
	$start_month = date("n",strtotime( $startdate ));
	$project_duration_in_text = $start_day;
	if ( $start_month != $end_month ) {
		$project_duration_in_text .= ' ' . $month_array[ $start_month ];
	}
	$project_duration_in_text .= ' t/m ' . $end_day . ' ' . $month_array[ $end_month ];
	return $project_duration_in_text;
}


//functie om projectsamenvatting te genereren
function siw_wc_project_summary( $project_type, $country, $workcode, $startdate, $enddate, $numvol, $minimum_age, $maximum_age, $family ) {

	//verzamelen gegevens voor samenvatting
	$numvol = (integer) $numvol;
	$other_volunteer = $numvol - 1;
	$age_range_in_text = siw_wc_age_range( $minimum_age, $maximum_age );

	$work = siw_wc_project_work_in_text( $workcode,2);

	$project_duration_in_days = siw_wc_project_duration_in_days( $startdate, $enddate );
	$project_duration_in_text = siw_wc_project_duration_in_text( $startdate, $enddate );
	$teenage_project = siw_wc_is_teenage_project( $minimum_age, $maximum_age, $project_type );
	$family_project = siw_wc_is_family_project( $family, $project_type );

	$project_summary = '';

	if ( 'tieners' == $teenage_project ) {
		$project_summary .= 'Dit is een tienerproject (' . $age_range_in_text . '). ';
	}

	else if ( 'familie' == $family_project ) {
		$project_summary .= 'Dit is een familieproject. ';
	}

	$project_summary .= 'Samen met ' . $other_volunteer . ' andere vrijwilligers ga je voor ' . $project_duration_in_days . ' dagen naar een ' . $work . 'project.<br/>';

	$project_summary .= 'Het project duurt van ' . $project_duration_in_text . '.';

	return $project_summary;
}


//function om projectbeschrijving te genereren
function siw_wc_project_description( $work, $accommodation, $location, $organisation, $requirements, $notes, $description ) {
	$project_description = '\[accordion]';
	if ( strlen( $description ) > 3){
		$project_description .= '\[pane title="Beschrijving"]' . htmlspecialchars_decode( $description ) . '\[/pane]';
	}
	if ( strlen( $work ) > 3){
		$project_description .= '\[pane title="Werk"]' . $work . '\[/pane]';
	}
	if ( strlen( $accommodation ) > 3){
		$project_description .= '\[pane title="Accommodatie en maaltijden"]' . $accommodation . '\[/pane]';
	}
	if ( strlen( $location ) > 3){
		$project_description .= '\[pane title="Locatie en vrije tijd"]' . $location . '\[/pane]';
	}
	if ( strlen( $organisation ) > 3){
		$project_description .= '\[pane title="Organisatie"]' . $organisation . '\[/pane]';
	}
	if ( strlen( $requirements ) > 3){
		$project_description .= '\[pane title="Vereisten"]' . $requirements . '\[/pane]';
	}
	if ( strlen( $notes ) > 3){
		$project_description .= '\[pane title="Opmerkingen"]' . $notes . '\[/pane]';
	}
	$project_description .= '\[/accordion]';
	return $project_description;
}


//SEO functies

function siw_wc_seo_title($country_code,$work_codes) {
	//SIW Vrijwilligerswerk | [werk]-project in [land]
	$work = siw_wc_project_work_in_text( $work_codes, 1) ;
	$country = siw_wc_country_name( $country_code );
	$seo_title = 'SIW Vrijwilligerswerk | ' . ucfirst( $work ) . 'project in ' . $country;

	return $seo_title;
}

function siw_wc_seo_summary( $startdate, $enddate, $country, $work_codes ) {
	$seo_summary = '';
	$project_duration_in_text = siw_wc_project_duration_in_text( $startdate, $enddate );
	$project_summary = '';
	//TODO
	$seo_summary = 'Van ' . $project_duration_in_text . '<br/>';
	$seo_summary .= $project_summary;
	return $seo_summary;
}

function siw_wc_seo_keywords(){
	$seo_keywords = '';
	return $seo_keywords;
}

function siw_wc_set_project_slug( $code, $country, $work, $end_date ) {
	$year =  date("Y", strtotime($end_date));
	$project_slug = $year . '-' . $code . '-';
	$project_slug .= siw_wc_project_name( $country, $work );
	return $project_slug;

}


/*functie om basis van projecteigenschappen een standaardfoto toe te wijzen TODO: Coderedundatie verminderen*/
if ( ! function_exists('siw_wc_select_project_image') ) {
	function siw_wc_select_project_image( $country_code, $work_codes ) {

		$base_directory = ABSPATH.'wp-content/uploads/wpallimport/files/';
		$country = siw_wc_country( $country_code );
		$continent = siw_wc_continent_from_country( $country_code );
		$work_array = siw_wc_project_work( $work_codes, 'array' );
		$work_array = array_filter( $work_array );
		$url='';

		//alleen een afbeelding zoeken als er een continent gevonden is.
		if ( $continent ) {
			foreach ( $work_array as $work ) {
				$relative_directory = $continent . '/' . $work . '/' . $country;
				$dir = $base_directory . $relative_directory;
				if ( file_exists( $dir )){
					$files = array_diff( scandir($dir), array(".", "..", "Thumbs.db") );
					$files = array_filter( $files, "siw_is_file");

					if ( sizeof( $files ) > 0){
						$random_image = array_rand( $files, 1);
						$filename = $files[ $random_image ];
						$url = $relative_directory . '/' . $filename;
						break;
					}
				}
			}

			if ( '' == $url ){
				foreach ( $work_array as $work ) {
					$relative_directory = $continent.'/'.$work;
					$dir = $base_directory . $relative_directory;
					if ( file_exists( $dir )){
						$files = array_diff( scandir( $dir ), array(".", "..", "Thumbs.db") );
						$files = array_filter( $files, "siw_is_file");

						if ( sizeof( $files ) > 0){
							$random_image = array_rand( $files, 1);
							$filename = $files[ $random_image ];
							$url = $relative_directory . '/' . $filename;
							break;
						}
					}
				}
			}
			if ( '' == $url ) {
				$relative_directory = $continent;
				$dir = $base_directory . $relative_directory;
				if ( file_exists( $dir ) ) {
					$files = array_diff( scandir( $dir ), array(".", "..", "Thumbs.db") );
					$files = array_filter( $files, "siw_is_file");

					if ( sizeof( $files ) > 0){
						$random_image = array_rand( $files, 1);
						$filename = $files[ $random_image ];
						$url = $relative_directory . '/' . $filename;
					}
				}
			}
		}
		return $url;
	}
}

//hulpfunctie voor siw_wc_select_project_image
if ( ! function_exists('siw_is_file') ) {
	function siw_is_file( $value ){
		$is_file = false;
		if ( ( strpos( $value, '.') ) !== false) {
			$is_file = true;
		}
		return $is_file;
	}
}
