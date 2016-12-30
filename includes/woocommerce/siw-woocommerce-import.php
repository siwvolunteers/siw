<?php
/*
(c)2015 SIW Internationale Vrijwilligersprojecten
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

function siw_wc_date_to_month( $date ){
	$month = '';
	if ( '1970-01-01' != $date ){
		$month = date("Y", strtotime( $date )) . date("m", strtotime( $date ));
	}	
	return $month;
}

function siw_wc_project_duration_in_days( $startdate, $enddate ){
	$startdate = strtotime( $startdate );
	$enddate = strtotime( $enddate );	
	$duration_in_seconds = $enddate - $startdate;
	$project_duration_in_days = round( $duration_in_seconds/(60*60*24) );
	return $project_duration_in_days;
}



function siw_wc_project_work( $work_codes, $format='string' ){
	$work_types = explode(",", $work_codes);
	$project_work = siw_get_array('project_work');
	$work='';

	foreach ($work_types as $work_type) {
		if (isset ( $project_work[ $work_type ])){
			$work .= $project_work[ $work_type ]."|";
		}
	}	

	if ( 'array' == $format ){
		$work = explode("|", $work);	
	}
	return $work;
}


function siw_wc_continent_from_country($country_code){
	$continent = '';
	$project_countries = siw_get_array('project_countries');	
	if (isset ( $project_countries[ $country_code ]['continent'])){
		$continent = $project_countries[ $country_code ]['continent'];
	}
	return $continent;
}

function siw_wc_country($country_code){
	$country = '';
	$project_countries = siw_get_array('project_countries');	
	if (isset ( $project_countries[ $country_code ]['slug'])){
		$country = $project_countries[ $country_code ]['slug'];
	}	
	return $country;
}

//functie om landnaam te bepalen

function siw_wc_country_name( $country_code ){
	$country_name = '';
	$project_countries = siw_get_array('project_countries');	
	if (isset ( $project_countries[ $country_code ]['name'])){
		$country_name = $project_countries[ $country_code ]['name'];
	}
	return $country_name;
}

/*bepalen of land toegestaan is/aangeboden wordt*/
function siw_wc_is_country_allowed( $country_code ){
	$allowed = 'no';
	$project_countries = siw_get_array('project_countries');	
	if (isset ( $project_countries[ $country_code ]['allowed'])){
		$allowed = $project_countries[ $country_code ]['allowed'];
	}
	return $allowed;	
}



function siw_wc_local_fee( $local_fee_amount, $local_fee_currency ){
	$local_fee_amount = (float) $local_fee_amount;
	$local_fee = '';
	if ($local_fee_amount > 0){
		$local_fee = $local_fee_currency . " " . $local_fee_amount;
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
	if ( 1 == $volunteers_female ){
		$female_label = " vrouw";
	}
	else{
		$female_label = " vrouwen";
	}	
	$number_of_volunteers = $volunteers_total;
	
	//alleen specificatie als totaal aantal vrijwilligers overeenkomt met som van aantal mannen en aantal vrouwen.
	if ($volunteers_total == ($volunteers_male + $volunteers_female)){
		$number_of_volunteers .= " (" . $volunteers_male.$male_label . " en " . $volunteers_female . $female_label . ")";
	}
	return $number_of_volunteers;
}


function siw_wc_free_places_left( $free_places_male, $free_places_female ){
	$free_places_left = 'yes';
	$free_places_male = (integer) $free_places_male;
	$free_places_female = (integer) $free_places_female;	
	$free_places_total = $free_places_male + $free_places_female;
	if ( $free_places_total <= 0){
		$free_places_left = 'no';
	}
	return $free_places_left;
}

function siw_wc_age_range( $minimum_age, $maximum_age ){
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


function siw_wc_is_teenage_project( $minimum_age, $maximum_age, $project_type ){
	$minimum_age = (integer) $minimum_age;
	$maximum_age = (integer) $maximum_age;
	$teenage_project = '';
	if (( $minimum_age < 17 and $minimum_age > 12 and $maximum_age < 20 ) or 'TEEN' == $project_type ){
		$teenage_project = 'tieners';
	}
	return $teenage_project;
}


function siw_wc_project_languages( $languages ){
	$language_codes = explode(",", $languages );
	$project_languages = siw_get_array('project_languages');
	$languages = '';
	foreach ( $language_codes as $code ) {
		$languages .= $project_languages[strtoupper( $code )] . "|";
	}
	return $languages;

}

//functie om soort werk te tonen
function siw_wc_project_work_in_text( $work_codes, $number ){
	$work_array = siw_wc_project_work( $work_codes, 'array' );
	$project_work_in_text = $work_array[0];
	if (($number > 1)and ($work_array[1] != '')){
		$project_work_in_text .= ' en ' . $work_array[1];
	}
	
	return $project_work_in_text;
}


//functie om projectnaam te genereren
function siw_wc_project_name( $country_code, $work_codes ){
	$country = siw_wc_country_name( $country_code );
	$work = siw_wc_project_work_in_text( $work_codes,2);
	$project_name = $country . ' | ' . ucfirst( $work );
	return $project_name;
}

//functie om te bepalen of een Verklaring omtrent het gedrag vereist is
function siw_wc_is_vog_required( $work_codes ){
	$work = siw_wc_project_work( $work_codes );
	$is_vog_required = '';
	if ((strpos( $work,'kinderen'))!==false) {
		$is_vog_required = 'Ja';
	}
	return $is_vog_required;
}

//functie om te bepalen of project direct gepubliceerd mag worden
function siw_wc_determine_post_status( $work_codes, $country ){
	$post_status = 'publish';
	$work_array = explode(",", $work_codes);
	$allowed = siw_wc_is_country_allowed($country);
	if ( in_array('KIDS', $work_array ) && 'yes' == $allowed ){
		$post_status = 'draft';
	}
	return $post_status;
}


//projectlocatie
function siw_wc_is_project_location_available( $latitude ){	
	$latitude = (float) $latitude;
	$project_location_available = '';
	if ( 0 != $latitude ){
		$project_location_available = 'Locatie';
	}
	return $project_location_available;
}

function siw_wc_project_location_map( $latitude, $longitude ){
	$project_location_map = '';
	$project_location_available = siw_wc_is_project_location_available( $latitude );
	if ( 'Locatie' == $project_location_available ){
		$project_location_map='\[gmap address="' . $latitude . ',' . $longitude . '" title="Projectlocatie" zoom="7" maptype="ROADMAP"]';
	}
	return $project_location_map;
}


//functie om zin met projectduur te genereren
function siw_wc_project_duration_in_text( $startdate, $enddate ){
	$month_array = siw_get_array('month_to_text');
	$end_day = date("j", strtotime( $enddate ));
	$end_month = date("n",strtotime( $enddate ));
	$start_day = date("j", strtotime( $startdate ));
	$start_month = date("n",strtotime( $startdate ));	
	$project_duration_in_text = $start_day;
	if ($start_month != $end_month){
		$project_duration_in_text .= ' ' . $month_array[ $start_month ];
	}
	$project_duration_in_text .= ' t/m ' . $end_day . ' ' . $month_array[ $end_month ];	
	return $project_duration_in_text;
}


//functie om projectsamenvatting te genereren
function siw_wc_project_summary( $project_type, $country, $workcode, $startdate, $enddate, $numvol, $minimum_age, $maximum_age, $family){
	
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
	
	if ( 'tieners' == $teenage_project ){
		$project_summary .= "Dit is een tienerproject (" . $age_range_in_text . '). ';
	}

	else if ( 'familie' == $family_project ){
		$project_summary .= 'Dit is een familieproject. ';
	}
	
	$project_summary .= 'Samen met ' . $other_volunteer . ' andere vrijwilligers ga je voor ' . $project_duration_in_days . ' dagen naar een ' . $work . 'project.<br/>';
	
	$project_summary .= 'Het project duurt van ' . $project_duration_in_text . '.';
	
	return $project_summary;
}


//function om projectbeschrijving te genereren
function siw_wc_project_description( $work, $accommodation, $location, $organisation, $requirements, $notes, $description ){
	$project_description = '\[accordion]';
	if (strlen( $description ) > 3){
		$project_description .= '\[pane title="Beschrijving"]' . htmlspecialchars_decode( $description ) . '\[/pane]';
	}	
	if (strlen( $work ) > 3){
		$project_description .= '\[pane title="Werk"]' . $work . '\[/pane]';
	}	
	if (strlen( $accommodation ) > 3){
		$project_description .= '\[pane title="Accommodatie en maaltijden"]' . $accommodation . '\[/pane]';
	}
	if (strlen( $location ) > 3){
		$project_description .= '\[pane title="Locatie en vrije tijd"]' . $location . '\[/pane]';
	}
	if (strlen( $organisation ) > 3){
		$project_description .= '\[pane title="Organisatie"]' . $organisation . '\[/pane]';
	}
	if (strlen( $requirements ) > 3){
		$project_description .= '\[pane title="Vereisten"]' . $requirements . '\[/pane]';
	}
	if (strlen( $notes ) > 3){
		$project_description .= '\[pane title="Opmerkingen"]' . $notes . '\[/pane]';
	}
	$project_description .= '\[/accordion]';
	return $project_description;
}


//SEO functies

function siw_wc_seo_title($country_code,$work_codes){
	//SIW Vrijwilligerswerk | [werk]-project in [land]
	$work = siw_wc_project_work_in_text( $work_codes, 1) ;
	$country = siw_wc_country_name( $country_code );
	$seo_title = 'SIW Vrijwilligerswerk | ' . ucfirst( $work ) . 'project in ' . $country;
	
	return $seo_title;
}

function siw_wc_seo_summary( $startdate, $enddate, $country, $work_codes ){
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

function siw_wc_set_project_slug( $code, $country, $work, $end_date ){
	$year =  date("Y", strtotime($end_date));
	$project_slug = $year . '-' . $code . '-';
	$project_slug .= siw_wc_project_name( $country, $work );
	return $project_slug;

}


/*functie om basis van projecteigenschappen een standaardfoto toe te wijzen TODO: Coderedundatie verminderen*/

function siw_wc_select_project_image( $country_code, $work_codes ) {

	$base_directory = ABSPATH.'wp-content/uploads/wpallimport/files/';
	$country = siw_wc_country( $country_code );
	$continent = siw_wc_continent_from_country( $country_code );
	$work_array = siw_wc_project_work( $work_codes, 'array' );
	$work_array = array_filter( $work_array );
	$url='';
	
	//alleen een afbeelding zoeken als er een continent gevonden is.
	if ( $continent ){
		foreach ( $work_array as $work ){
			$relative_directory = $continent . '/' . $work . '/'.$country;
			$dir = $base_directory . $relative_directory;
			if (file_exists( $dir )){
				$files = array_diff( scandir($dir), array(".", "..", "Thumbs.db") );	
				$files = array_filter( $files, "siw_is_file");
				
				if (sizeof( $files ) > 0){
					$random_image = array_rand($files, 1);
					$filename = $files[ $random_image ];
					$url = $relative_directory . '/' . $filename;
					break;
				}
			}
		}
		
		if ( '' == $url ){
			foreach ( $work_array as $work ){
				$relative_directory = $continent.'/'.$work;
				$dir = $base_directory . $relative_directory;
				if (file_exists( $dir )){
					$files = array_diff( scandir( $dir ), array(".", "..", "Thumbs.db") );
					$files = array_filter( $files, "siw_is_file");
				
					if (sizeof( $files ) > 0){
						$random_image = array_rand( $files, 1);
						$filename = $files[ $random_image ];
						$url = $relative_directory . '/' . $filename;
						break;
					}
				}
			}
		}
		if ( '' == $url ){
			$relative_directory = $continent;
			$dir = $base_directory . $relative_directory;
			if (file_exists( $dir )){
				$files = array_diff( scandir( $dir ), array(".", "..", "Thumbs.db") );
				$files = array_filter( $files, "siw_is_file");
				
				if (sizeof( $files ) > 0){
					$random_image = array_rand( $files, 1);
					$filename = $files[ $random_image ];
					$url = $relative_directory . '/' . $filename;
				}
			}
		}
	}
	return $url;
}	

//hulpfunctie voor selectProjectImage
function siw_is_file( $value ){
	$is_file = false;
	if ((strpos( $value,'.'))!==false) {
		$is_file = true;
	}
	return $is_file;
}



//functie om aparte tarieven voor regulier en studenten toe te voegen
add_action('pmxi_saved_post', 'siw_wc_set_variations_prices', 10, 1);

function siw_wc_set_variations_prices( $product_id ){

	$tariff_array = siw_wc_get_tariff_array();
	$args = array(
		'post_type'		=> 'product_variation',
		'post_parent'	=> $product_id,
		'fields' 		=> 'ids'
	);
	$variations = get_posts( $args ); 
	foreach ( $variations as $variation_id ) {
		$tariff = get_post_meta( $variation_id, 'attribute_pa_tarief', true);
		$price = $tariff_array[ $tariff ];
		update_post_meta( $variation_id, '_regular_price', $price );
		update_post_meta( $variation_id, '_price', $price );
		update_post_meta( $variation_id, '_virtual', 'yes');
	}
}

add_filter('wp_all_import_is_post_to_update', 'siw_wc_is_post_to_update', 10, 2);
function siw_wc_is_post_to_update( $product_id, $xml ) {
	$update = false;
	
	//bepalen of het de fpl-import is
	$is_fpl = array_key_exists( 'reserved', $xml );
	if( $is_fpl ){
		$update = true;
	}
	
	//bepalen of het project gemarkeerd is om opnieuw te importeren
	$import_again = get_post_meta( $product_id, 'import_again', true);
	if ( $import_again ){
		$update = true;
	}
	
	//controleer optie 'Forceer volledige import'
	$force_full_import = siw_wc_get_force_full_import();
	if ( $force_full_import ){
		$update = true;
	}

	return $update;
}

add_action('pmxi_after_xml_import', 'siw_wc_hide_projects_after_import', 10, 1);
function siw_wc_hide_projects_after_import( $import_id ){
	//Zet vervolgacties klaar
	wp_schedule_single_event( time() + ( 15 * MINUTE_IN_SECONDS ), 'siw_hide_workcamps');
	wp_schedule_single_event( time() + ( 45 * MINUTE_IN_SECONDS ), 'siw_send_projects_for_approval_email');
	//TODO: email voor nieuwe projecten
	//TODO: emails alleen voor volledige import niet voor FPL
}

/*
Functie om groepsprojecten te verbergen die aan 1 of meer van onderstaande voorwaarden voldoen:
- Het project begint binnen x dagen (configuratie)
- Het project is in een niet-toegestaan land is
- Het project is expliciet verborgen
- Er zijn geen vrije plaatsen meer
*/
add_action('siw_hide_workcamps', 'siw_hide_workcamps');

function siw_hide_workcamps() {	

	$days = siw_wc_get_nr_of_days_before_start_to_hide_project();
	$limit = date("Y-m-d",strtotime(date("Y-m-d")."+".$days." days"));

	$meta_query_args = array(
		'relation'	=>	'AND',
		array(
			'key'		=>	'_visibility',
			'value'		=>	'visible',
			'compare'	=>	'='
		),
		array(
			'relation'	=>	'OR',
			array(
				'key'		=> 'freeplaces',
				'value'		=> 'no',
				'compare'	=> '='
			),
			array(
				'key'		=> 'manual_visibility',
				'value'		=> 'hide',
				'compare'	=> '='
			),
			array(
				'key'		=> 'startdatum',
				'value'		=> $limit,
				'compare'	=> '<='
			),
			array(
				'key'		=> 'allowed',
				'value'		=> 'no',
				'compare'	=> '='
			),
		),
	);
	
	$args = array(
		'posts_per_page'	=> -1,
		'post_type'			=> 'product',
		'meta_query'		=> $meta_query_args,
		'fields' 			=> 'ids',
		'post_status'		=> 'any',
	);
	
	$products = get_posts( $args ); 
	foreach ( $products as $product_id ) {
		//project 'publiceren' als project eigenlijk ter review stond
		if ('publish' != get_post_status( $product_id )){
			wp_publish_post( $product_id );
		}
		siw_hide_workcamp( $product_id );
	}
}


//losse functie om 1 groepsproject te verbergen
function siw_hide_workcamp( $product_id ){
	update_post_meta( $product_id, '_visibility', 'hidden');
	update_post_meta( $product_id, '_stock_status', 'outofstock');
	update_post_meta( $product_id, '_featured', 'no');
	update_post_meta( $product_id, '_yoast_wpseo_meta-robots-noindex','1');
		
	$varationsargs = array(
		'post_type' 	=> 'product_variation',
		'post_parent'	=> $product_id,
		'fields' 		=> 'ids'
	);
	$variations = get_posts( $varationsargs ); 
	foreach ( $variations as $variation_id ) {
		update_post_meta( $variation_id, '_stock_status', 'outofstock');
	}	
}
