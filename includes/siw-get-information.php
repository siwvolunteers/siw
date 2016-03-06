<?php
/*
(c)2015 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
//API keys
function siw_get_postcode_api_key(){
	$postcode_api_key = get_option('siw_api_postcode_api_key');
	return $postcode_api_key;
}

function siw_get_plato_organization_webkey(){
	$organization_webkey = get_option('siw_plato_organization_web_key');//
	return $organization_webkey;
}

function siw_get_google_analytics_id(){
	$google_analytics_id = get_option('siw_api_google_analytics_id');
	return $google_analytics_id;
}
function siw_get_pingdom_rum_id(){
	$pingdom_rum_id = get_option('siw_api_pingdom_rum_id');
	return $pingdom_rum_id;
}

function siw_get_general_information ( $type ){
	switch ( $type ){
		case "iban":
			$iban = get_option('siw_general_iban');
			return $iban;
		case "kvk":
			$kvk = get_option('siw_general_kvk');
			return $kvk;		
		case "telefoon":
			$phone = get_option('siw_general_phone');
			return $phone;		
		case "email":
			$email = get_option('siw_general_email');
			return $email;		
		case "naam":
			$naam = get_bloginfo('name');
			return $naam;
	}
}

//ip whitelist
function siw_get_ip_whitelist(){
	$ip_whitelist[]= get_option('siw_login_whitelist_ip_1');
	$ip_whitelist[]= get_option('siw_login_whitelist_ip_2');
	$ip_whitelist[]= get_option('siw_login_whitelist_ip_3');
	$ip_whitelist[]= get_option('siw_login_whitelist_ip_4');
	$ip_whitelist[]= get_option('siw_login_whitelist_ip_5');
	return $ip_whitelist;
}

//pagina's
function siw_get_parent_page( $type ){
    switch ($type) {
        case  "vacatures":
			$parent_page = get_option('siw_jobs_parent_page');
			return $parent_page;
        case  "agenda":
			$parent_page = get_option('siw_agenda_parent_page');
			return $parent_page;
	}
}

//formulieren
function siw_get_vfb_form_id( $type ){
    switch ($type) {
        case  "community_day":
			$form_id = get_option('siw_forms_community_day');
			return $form_id;
        case  "evs":
			$form_id = get_option('siw_forms_evs');
			return $form_id;
        case  "op_maat":
			$form_id = get_option('siw_forms_op_maat');
			return $form_id;
	}
}

function siw_get_cf7_form_id( $type ){
    switch ($type) {
        case  "algemeen":
			$form_id = get_option('siw_forms_algemeen');
			return $form_id;
        case  "project":
			$form_id = get_option('siw_forms_project');
			return $form_id;
        case  "begeleider":
			$form_id = get_option('siw_forms_begeleider');
			return $form_id;
	}
}
	
function siw_get_jobs_company_profile(){
	$company_profile = get_option('siw_jobs_company_profile');
	return $company_profile;
}

function siw_get_jobs_mission_statement(){
	$mission = get_option('siw_jobs_mission_statement');
	return $mission;

}

function siw_get_db_backup_time(){
	$db_backup_time = '03:00';
	return $db_backup_time;
}

function siw_get_files_backup_time(){
	$files_backup_time = '04:00';
	return $files_backup_time;
}

//EVS
function siw_get_evs_next_deadline(){
	$evs_deadlines[]= get_option('siw_evs_deadline_1');
	$evs_deadlines[]= get_option('siw_evs_deadline_2');
	$evs_deadlines[]= get_option('siw_evs_deadline_3');
	$evs_deadlines[]= get_option('siw_evs_deadline_4');
	$evs_deadlines[]= get_option('siw_evs_deadline_5');
	
	asort($evs_deadlines);
	$weeks = get_option( 'siw_evs_weeks_before_deadline' );
	$limit = date("Y-m-d",strtotime(date("Y-m-d")."+".$weeks." weeks"));

	foreach($evs_deadlines as $evs_deadline => $evs_deadline_date) {
		if ($evs_deadline_date > $limit){
			$evs_next_deadline = $evs_deadline_date;
			break;
		}
	}
	return $evs_next_deadline;
}

function siw_get_next_community_day(){
	$community_days[]= get_option('siw_community_day_1');
	$community_days[]= get_option('siw_community_day_2');
	$community_days[]= get_option('siw_community_day_3');
	$community_days[]= get_option('siw_community_day_4');
	$community_days[]= get_option('siw_community_day_5');
	$community_days[]= get_option('siw_community_day_6');
	
	asort( $community_days );
	$today = date("Y-m-d");
	
	foreach($community_days as $community_day => $community_day_date) {
		if ($community_day_date > $today){
			$next_community_day = $community_day_date;
			break;
		}
	}
	return $next_community_day;	
}

//afzender plato export
function siw_get_outgoing_placements_officer(){
	$outgoing_placements_officer = get_option('siw_plato_outgoing_placements_name');
	return $outgoing_placements_officer;
}

function siw_get_outgoing_placements_email(){
	$outgoing_placements_email = get_option('siw_plato_outgoing_placements_email');
	return $outgoing_placements_email;
}

//ondertekening e-mails
function siw_get_mail_signature_name( $type ){
    switch ($type) {
        case  "contact_algemeen":
			$signature_name = get_option('siw_signature_general');
			return $signature_name;
			
        case  "contact_np":
			$signature_name = get_option('siw_signature_camp_leader');
			return $signature_name;
			
        case  "contact_project":
			$signature_name = get_option('siw_signature_project');
			return $signature_name;
			
        case  "aanmelding_groepsproject":
			$signature_name = get_option('siw_signature_workcamp');
			return $signature_name;
			
        case  "aanmelding_evs":
			$signature_name = get_option('siw_signature_evs');
			return $signature_name;	
			
        case  "aanmelding_op_maat":
			$signature_name = get_option('siw_signature_op_maat');
			return $signature_name;
			
        case  "aanmelding_community_day":
			$signature_name = get_option('siw_signature_community_day');
			return $signature_name;		
	}
}
//tarieven
function siw_get_fee_op_maat( $tariff ){
    switch ($tariff) {
        case  "student":
			$fee = get_option('siw_tariffs_op_maat_student');
			return $fee;
			
        case  "regulier":
			$fee = get_option('siw_tariffs_op_maat_regular');
			return $fee;					
	}
}

function siw_get_fee_workcamp( $tariff ){
    switch ($tariff) {
        case  "student":
			$fee = get_option('siw_tariffs_workcamp_student');
			return $fee;
			
        case  "regulier":
			$fee = get_option('siw_tariffs_workcamp_regular');
			return $fee;					
	}
}

function siw_get_evs_deposit(){
	$evs_deposit = get_option('siw_tariffs_evs_deposit');
	return $evs_deposit;
}


//datum
function siw_get_date_in_text( $date, $year = true ){
	$date_array = date_parse( $date );
	$month_array = siw_get_array('month_to_text');
	$day = $date_array['day'];
	$month = $month_array[$date_array['month']];
	$date_in_text = $day . ' ' . $month;
	if ($year){
		$year = $date_array['year'];
		$date_in_text .=  ' ' . $year;
	}
	return $date_in_text;

}

function siw_get_date_range_in_text ($date_start, $date_end, $year = true){
	//als beide datums gelijk zijn gebruik dan siw_get_date_in_text
	if ( $date_start == $date_end){
		$date_range_in_text = siw_get_date_in_text( $date_start, $year );
	}
	else{
		$date_start_array = date_parse( $date_start );
		$date_end_array = date_parse( $date_end );
		$month_array = siw_get_array('month_to_text');
	
		$date_range_in_text = $date_start_array['day'];
		if ( $date_start_array['month'] != $date_end_array['month']){
			$date_range_in_text .= ' ' . $month_array[$date_start_array['month']];
		}
		if ( ($date_start_array['year'] != $date_end_array['year']) and $year){
			$date_range_in_text .= ' ' . $date_start_array['year'];
		}
		$date_range_in_text .= ' t/m ';
		$date_range_in_text .= $date_end_array['day'];
		$date_range_in_text .= ' ' . $month_array[$date_end_array['month']];
		if ( $year ){
			$date_range_in_text .= ' ' . $date_end_array['year'];
		}
		
	}
	return $date_range_in_text;

}

//PLATO import
function siw_wc_get_tariff_array(){
	$tariff_array = array(
		"regulier"=>number_format(get_option('siw_tariffs_workcamp_regular'),2),
		"student"=>number_format(get_option('siw_tariffs_workcamp_student'),2)
	);
	return $tariff_array;
}


function siw_wc_get_nr_of_days_before_start_to_hide_project(){
	$nr_of_days_before_start_to_hide_project = get_option('siw_plato_nr_of_days_before_start_to_hide_project');
	return $nr_of_days_before_start_to_hide_project;
}

function siw_get_array( $array ){

    switch ($array) {
        case  "gender":
			$gender = array(
				'M' => 'Man',
				'F' => 'Vrouw',
			);
			return $gender;
			
		case "nationalities":
			$nationalities = array(
				'' => '',
				'AFG' =>'Afghanistan',
				'ALB' =>'Albanië',
				'ALG' =>'Algerije',
				'AGO' =>'Angola',
				'ARG' =>'Argentinië',
				'ARM' =>'Armenië',
				'AUS' =>'Australië',
				'AT' =>'Oostenrijk',
				'AZB' =>'Azerbeidzjan',
				'BHS' =>"Bahama's",
				'BAH' =>'Bahrein',
				'BGD' =>'Bangladesh',
				'BBD' =>'Barbados',
				'BYE' =>'Wit-Rusland',
				'BEL' =>'België',
				'BLZ' =>'Belize',
				'BEN' =>'Benin',
				'BRM' =>'Bermuda',
				'BUT' =>'Bhutan',
				'BOL' =>'Bolivia',
				'BOS' =>'Bosnië en Herzegovina',
				'BTW' =>'Botswana',
				'BRZ' =>'Brazilië',
				'BLG' =>'Bulgarije',
				'BKF' =>'Burkina Faso',
				'BM' =>'Myanmar',
				'BDI' =>'Burundi',
				'CMG' =>'Cambodja',
				'CMR' =>'Kameroen',
				'CAN' =>'Canada',
				'CVD' =>'Kaapverdië',
				'CYD' =>'Kaaimaneilanden',
				'CAF' =>'Centraal-Afrikaanse Republiek',
				'TCD' =>'Tsjaad',
				'CHL' =>'Chili',
				'CHI' =>'China',
				'COL' =>'Colombia',
				'COM' =>'Comoren',
				'COG' =>'Congo-Brazzaville',
				'COD' =>'Congo-Kinshasa',
				'CRI' =>'Costa Rica',
				'CRO' =>'Kroatië',
				'CUB' =>'Cuba',
				'CHY' =>'Cyprus',
				'CZE' =>'Tsjechië',
				'DNK' =>'Denemarken',
				'DMA' =>'Dominica',
				'DOM' =>'Dominicaanse Republiek',
				'ECU' =>'Ecuador',
				'EGY' =>'Egypte',
				'SLV' =>'El Salvador',
				'EST' =>'Estland',
				'ETH' =>'Ethiopië',
				'FIN' =>'Finland',
				'FRA' =>'Frankrijk',
				'GEO' =>'Georgië',
				'GER' =>'Duitsland',
				'GHA' =>'Ghana',
				'GBR' =>'Groot-Brittannië',
				'GRE' =>'Griekenland',
				'GL' =>'Groenland',
				'GAT' =>'Guatemala',
				'HT' =>'Haïti',
				'HON' =>'Honduras',
				'HKG' =>'Hongkong',
				'HUN' =>'Hongarije',
				'ISL' =>'IJsland',
				'IND' =>'India',
				'IDN' =>'Indonesië',
				'IRN' =>'Iran',
				'EIR' =>'Ierland',
				'ISR' =>'Israël',
				'ITA' =>'Italië',
				'CIV' =>'Ivoorkust',
				'JM' =>'Jamaica',
				'JPN' =>'Japan',
				'JOR' =>'Jordanië',
				'KZ' =>'Kazachstan',
				'KEN' =>'Kenia',
				'KOR' =>'Zuid-Korea',
				'KGZ' =>'Kirgizië',
				'LAO' =>'Laos',
				'LTV' =>'Letland',
				'LBN' =>'Libanon',
				'LS' =>'Lesotho',
				'LIT' =>'Litouwen',
				'LUX' =>'Luxemburg',
				'MK' =>'Macedonië',
				'MG' =>'Madagaskar',
				'MW' =>'Malawi',
				'MLS' =>'Maleisië',
				'MLI' =>'Mali',
				'MU' =>'Mauritius',
				'MEX' =>'Mexico',
				'MOL' =>'Moldavië',
				'MGL' =>'Mongolië',
				'ME' =>'Montenegro',
				'MAR' =>'Marokko',
				'MOZ' =>'Mozambique',
				'NEP' =>'Nepal',
				'HOL' =>'Nederland',
				'NZL' =>'Nieuw-Zeeland',
				'NIC' =>'Nicaragua',
				'NGR' =>'Niger',
				'NIG' =>'Nigeria',
				'NI' =>'Noord-Ierland',
				'NOR' =>'Noorwegen',
				'PK' =>'Pakistan',
				'PS' =>'Palestina',
				'PAR' =>'Paraguay',
				'PER' =>'Peru',
				'PHL' =>'Filipijnen',
				'POL' =>'Polen',
				'POR' =>'Portugal',
				'ROM' =>'Roemenië',
				'RUS' =>'Rusland',
				'SEN' =>'Senegal',
				'RS' =>'Servië',
				'SL' =>'Sierra Leone',
				'SGP' =>'Singapore',
				'SLK' =>'Slowakije',
				'SLO' =>'Slovenië',
				'ZAF' =>'Zuid-Afrika',
				'ESP' =>'Spanje',
				'LK' =>'Sri Lanka',
				'SVE' =>'Zweden',
				'CH' =>'Zwitserland',
				'TWN' =>'Taiwan',
				'TAN' =>'Tanzania',
				'THA' =>'Thailand',
				'TOG' =>'Togo',
				'TUN' =>'Tunesië',
				'TUR' =>'Turkije',
				'TKM' =>'Turkmenistan',
				'UGA' =>'Oeganda',
				'UKR' =>'Oekraïne',
				'USA' =>'Verenigde Staten',
				'URY' =>'Uruguay',
				'UZB' =>'Oezbekistan',
				'VEN' =>'Venezuela',
				'VTN' =>'Vietnam',
				'YEM' =>'Jemen',
				'ZMB' =>'Zambia',
				'ZIM' =>'Zimbabwe'
			);
			return $nationalities;
			
        case  "languages":
			$languages = array(
				'' =>  'Selecteer een taal',
				'ARA' => 'Arabisch',
				'CAT' => 'Catalaans',
				'CHN' => 'Chinees',
				'DNK' => 'Deens',
				'GER' => 'Duits',
				'ENG' => 'Engels',
				'EST' => 'Estisch ',
				'FIN' => 'Fins',
				'FRA' => 'Frans',
				'GRE' => 'Grieks',
				'HEB' => 'Hebreeuws',
				'ITA' => 'Italiaans',
				'JAP' => 'Japans',
				'KOR' => 'Koreaans',
				'HOL' => 'Nederlands',
				'UKR' => 'Oekraïens',
				'POL' => 'Pools',
				'POR' => 'Portugees',
				'RUS' => 'Russisch',
				'SLK' => 'Slowaaks',
				'ESP' => 'Spaans',
				'CZE' => 'Tsjechisch',
				'TUR' => 'Turks',
				'SWE' => 'Zweeds',
			);
			return $languages;	
			
		case  "language_skill":
			$language_skill = array(
				'1' => 'Matig',
				'2' => 'Redelijk',
				'3' => 'Goed',
				'4' => 'Uitstekend',
			);
			return $language_skill;

        case  "project_work":
			$project_work=array(
				"RENO"	=> "restauratie",
				"ENVI"	=> "natuur",
				"CONS"	=> "constructie",
				"ARCH"	=> "archeologie",
				"SOCI"	=> "sociaal",
				"KIDS"	=> "kinderen",
				"STUD"	=> "thema",
				"DISA"	=> "gehandicapten",
				"MANU"	=> "constructie",
				"EDU"	=> "onderwijs",
				"ELDE"	=> "ouderen",
				"FEST"	=> "festival",
				"CULT"	=> "cultuur",
				"AGRI"	=> "landbouw",
				"ART"	=> "kunst",
				"SPOR"	=> "sport",
				"YOGA"	=> "yoga",
				"LANG"	=> "taalcursus",
				"TRAS"	=> "taal",
				"ZOO"	=> "dieren",
				"ANIM"	=> "dieren",
				"LEAD"	=> "projectbegeleider",
				"HERI"	=> "erfgoed"
			);
			return $project_work;
			
		case "month_to_text":
			$month_to_text=array(
				"1"	 => "januari",
				"2"	 => "februari",
				"3"	 => "maart",
				"4"	 => "april",
				"5"	 => "mei",
				"6"	 => "juni",
				"7"	 => "juli",
				"8"	 => "augustus",
				"9"	 => "september",
				"10" => "oktober",
				"11" => "november",
				"12" => "december"
			);	
			return $month_to_text;
			
		case "project_languages":			
			$project_languages=array(
				'ARA' => 'arabisch',
				'AZE' => 'azerbeidzjaans ',
				'CAT' => 'catalaans',
				'CHN' => 'chinees',
				'HKG' => 'chinees',
				'DNK' => 'deens',
				'GER' => 'duits',
				'ENG' => 'engels',
				'EN'  => 'engels',
				'USA' => 'engels',
				'EST' => 'estisch',
				'FIN' => 'fins',
				'FRA' => 'frans',
				'GRE' => 'grieks',
				'HEB' => 'hebreeuws',
				'ITA' => 'italiaans',
				'JAP' => 'japans',
				'KOR' => 'koreaans',
				'HOL' => 'nederlands',
				'UKR' => 'oekraiens',
				'IRN' => 'perzisch',
				'POL' => 'pools',
				'POR' => 'portugees',
				'RUS' => 'russisch',
				'SLK' => 'slowaaks',
				'ES'  => 'spaans',
				'ESP' => 'spaans',
				'CZE' => 'tsjechisch',
				'TUR' => 'turks',
				'BEL' => 'waals',
				'BLR' => 'wit-russisch',
				'SWE' => 'zweeds'
			);			
			return $project_languages;		

			case "project_countries":	
				$project_countries = array();
				$project_countries['ARG'] = array(
					'slug'		=> 'argentinie',
					'name'  	=> 'Argentinië' ,
					'continent'	=> 'latijns-amerika',
				); 
				$project_countries['ARM'] = array(
					'slug'		=> 'armenie',
					'name'  	=> 'Armenië' ,
					'continent'	=> 'europa',
				); 				
				$project_countries['BDI'] = array(
					'slug'		=> 'burundi',
					'name'  	=> 'Burundi' ,
					'continent'	=> 'afrika-midden-oosten',
				); 					
				$project_countries['BEL'] = array(
					'slug'		=> 'belgie',
					'name'  	=> 'België' ,
					'continent'	=> 'europa',
				); 	
				$project_countries['BLR'] = array(
					'slug'		=> 'wit-rusland',
					'name'  	=> 'Wit-Rusland' ,
					'continent'	=> 'europa',
				); 	
				$project_countries['CAN'] = array(
					'slug'		=> 'canada',
					'name'  	=> 'Canada' ,
					'continent'	=> 'noord-amerika',
				); 	
				$project_countries['CHE'] = array(
					'slug'		=> 'zwitserland',
					'name'  	=> 'Zwitserland' ,
					'continent'	=> 'europa',
				); 	
				$project_countries['CHN'] = array(
					'slug'		=> 'china',
					'name'  	=> 'China' ,
					'continent'	=> 'azie',
				); 
				$project_countries['CRI'] = array(
					'slug'		=> 'costa-rica',
					'name'  	=> 'Costa Rica' ,
					'continent'	=> 'latijns-amerika',
				); 				
				$project_countries['CZE'] = array(
					'slug'		=> 'tsjechie',
					'name'  	=> 'Tsjechië' ,
					'continent'	=> 'europa',
				); 	
				$project_countries['DEU'] = array(
					'slug'		=> 'duitsland',
					'name'  	=> 'Duitsland' ,
					'continent'	=> 'europa',
				); 
				$project_countries['DNK'] = array(
					'slug'		=> 'denemarken',
					'name'  	=> 'Denemarken' ,
					'continent'	=> 'europa',
				);
				$project_countries['ECU'] = array(
					'slug'		=> 'ecuador',
					'name'  	=> 'Ecuador' ,
					'continent'	=> 'latijns-amerika',
				);
				$project_countries['ESP'] = array(
					'slug'		=> 'spanje',
					'name'  	=> 'Spanje' ,
					'continent'	=> 'europa',
				);
				$project_countries['EST'] = array(
					'slug'		=> 'estland',
					'name'  	=> 'Estland' ,
					'continent'	=> 'europa',
				);
				$project_countries['FIN'] = array(
					'slug'		=> 'finland',
					'name'  	=> 'Finland' ,
					'continent'	=> 'europa',
				);
				$project_countries['FRA'] = array(
					'slug'		=> 'frankrijk',
					'name'  	=> 'Frankrijk' ,
					'continent'	=> 'europa',
				);
				$project_countries['GBR'] = array(
					'slug'		=> 'verenigd-koninkrijk',
					'name'  	=> 'Verenigd Koninkrijk' ,
					'continent'	=> 'europa',
				);
				$project_countries['GEO'] = array(
					'slug'		=> 'georgie',
					'name'  	=> 'Georgië' ,
					'continent'	=> 'europa',
				);
				$project_countries['GRC'] = array(
					'slug'		=> 'griekenland',
					'name'  	=> 'Griekenland' ,
					'continent'	=> 'europa',
				);
				$project_countries['HKG'] = array(
					'slug'		=> 'hong-kong',
					'name'  	=> 'Hong Kong' ,
					'continent'	=> 'azie',
				);
				$project_countries['HUN'] = array(
					'slug'		=> 'hongarije',
					'name'  	=> 'Hongarije' ,
					'continent'	=> 'europa',
				);
				$project_countries['HTE'] = array(
					'slug'		=> 'haiti',
					'name'  	=> 'Haïti' ,
					'continent'	=> 'latijns-amerika',
				);
				$project_countries['IDN'] = array(
					'slug'		=> 'indonesie',
					'name'  	=> 'Indonesië' ,
					'continent'	=> 'azie',
				);
				$project_countries['IND'] = array(
					'slug'		=> 'india',
					'name'  	=> 'India' ,
					'continent'	=> 'azie',
				);
				$project_countries['IRL'] = array(
					'slug'		=> 'ierland',
					'name'  	=> 'Ierland' ,
					'continent'	=> 'europa',
				);
				$project_countries['ISL'] = array(
					'slug'		=> 'ijsland',
					'name'  	=> 'IJsland' ,
					'continent'	=> 'europa',
				);
				$project_countries['ITA'] = array(
					'slug'		=> 'italie',
					'name'  	=> 'Italië' ,
					'continent'	=> 'europa',
				);
				$project_countries['JPN'] = array(
					'slug'		=> 'japan',
					'name'  	=> 'Japan' ,
					'continent'	=> 'azie',
				);
				$project_countries['KEN'] = array(
					'slug'		=> 'kenia',
					'name'  	=> 'Kenia' ,
					'continent'	=> 'afrika-midden-oosten',
				);
				$project_countries['KGZ'] = array(
					'slug'		=> 'kirgizie',
					'name'  	=> 'Kirgizië' ,
					'continent'	=> 'azie',
				);
				$project_countries['KHM'] = array(
					'slug'		=> 'cambodja',
					'name'  	=> 'Cambodja' ,
					'continent'	=> 'azie',
				);
				$project_countries['KOR'] = array(
					'slug'		=> 'zuid-korea',
					'name'  	=> 'Zuid-Korea' ,
					'continent'	=> 'azie',
				);
				$project_countries['LKA'] = array(
					'slug'		=> 'sri-lanka',
					'name'  	=> 'Sri Lanka' ,
					'continent'	=> 'azie',
				);
				$project_countries['LTU'] = array(
					'slug'		=> 'litouwen',
					'name'  	=> 'Litouwen' ,
					'continent'	=> 'europa',
				);
				$project_countries['LVA'] = array(
					'slug'		=> 'letland',
					'name'  	=> 'Letland' ,
					'continent'	=> 'europa',
				);
				$project_countries['MAR'] = array(
					'slug'		=> 'marokko',
					'name'  	=> 'Marokko' ,
					'continent'	=> 'afrika-midden-oosten',
				);
				$project_countries['MEX'] = array(
					'slug'		=> 'mexico',
					'name'  	=> 'Mexico' ,
					'continent'	=> 'latijns-amerika',
				);
				$project_countries['MNE'] = array(
					'slug'		=> 'montenegro',
					'name'  	=> 'Montenegro' ,
					'continent'	=> 'europa',
				);
				$project_countries['MNG'] = array(
					'slug'		=> 'mongolie',
					'name'  	=> 'Mongolië' ,
					'continent'	=> 'azie',
				);
				$project_countries['NLD'] = array(
					'slug'		=> 'nederland',
					'name'  	=> 'Nederland' ,
					'continent'	=> 'europa',
				);
				$project_countries['NPL'] = array(
					'slug'		=> 'nepal',
					'name'  	=> 'Nepal' ,
					'continent'	=> 'azie',
				);
				$project_countries['PER'] = array(
					'slug'		=> 'peru',
					'name'  	=> 'Peru' ,
					'continent'	=> 'latijns-amerika',
				);
				$project_countries['POL'] = array(
					'slug'		=> 'polen',
					'name'  	=> 'Polen' ,
					'continent'	=> 'europa',
				);
				$project_countries['PRT'] = array(
					'slug'		=> 'portugal',
					'name'  	=> 'Portugal' ,
					'continent'	=> 'europa',
				);
				$project_countries['RUS'] = array(
					'slug'		=> 'rusland',
					'name'  	=> 'Rusland' ,
					'continent'	=> 'europa',
				);
				$project_countries['SRB'] = array(
					'slug'		=> 'servie',
					'name'  	=> 'Servië' ,
					'continent'	=> 'europa',
				);
				$project_countries['SVK'] = array(
					'slug'		=> 'slowakije',
					'name'  	=> 'Slowakije' ,
					'continent'	=> 'europa',
				);
				$project_countries['TGO'] = array(
					'slug'		=> 'togo',
					'name'  	=> 'Togo' ,
					'continent'	=> 'afrika-midden-oosten',
				);
				$project_countries['THA'] = array(
					'slug'		=> 'thailand',
					'name'  	=> 'Thailand' ,
					'continent'	=> 'azie',
				);
				$project_countries['TUN'] = array(
					'slug'		=> 'tunesie',
					'name'  	=> 'Tunesië' ,
					'continent'	=> 'afrika-midden-oosten',
				);
				$project_countries['TUR'] = array(
					'slug'		=> 'turkije',
					'name'  	=> 'Turkije' ,
					'continent'	=> 'europa',
				);
				$project_countries['TWN'] = array(
					'slug'		=> 'taiwan',
					'name'  	=> 'Taiwan' ,
					'continent'	=> 'azie',
				);
				$project_countries['UGA'] = array(
					'slug'		=> 'uganda',
					'name'  	=> 'Uganda' ,
					'continent'	=> 'afrika-midden-oosten',
				);
				$project_countries['UKR'] = array(
					'slug'		=> 'oekraine',
					'name'  	=> 'Oekraïne' ,
					'continent'	=> 'europa',
				);
				$project_countries['USA'] = array(
					'slug'		=> 'verenigde-staten',
					'name'  	=> 'Verenigde Staten' ,
					'continent'	=> 'noord-amerika',
				);
				$project_countries['VNM'] = array(
					'slug'		=> 'vietnam',
					'name'  	=> 'Vietnam' ,
					'continent'	=> 'azie',
				);
				return $project_countries;	

			case "days":
				$days = array(
					'' => '',
				);
				for ($x = 1 ; $x <= 31; $x++) {
					$days[$x] = $x;
				} 
				return $days;
				
			case "months":
				$months = array(
					''	=> '',
					'1' => 'Januari',
					'2' => 'Februari',
					'3' => 'Maart',
					'4' => 'April',
					'5' => 'Mei',
					'6' => 'Juni',
					'7' => 'Juli',
					'8' => 'Augustus',
					'9' => 'September',
					'10' => 'Oktober',
					'11' => 'November',
					'12' => 'December',
				);
				return $months;
				
			case "years":
				$current_year = (integer) date("Y");
				$min_year = $current_year - 100;
				$max_year = $current_year - 14;					
				$years = array(
					'' => '',
				);
				for ($x = $max_year ; $x >= $min_year; $x--) {
					$years[$x] = $x;
				} 
				return $years;
				
	}
}