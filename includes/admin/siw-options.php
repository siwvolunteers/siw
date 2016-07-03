<?php
/*
(c)2015 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_action( 'admin_menu', 'siw_add_settings_menu' );
add_action( 'admin_menu', 'siw_settings_general_init');
add_action( 'admin_init', 'siw_settings_signatures_init' );
add_action( 'admin_init', 'siw_settings_plato_init' );
add_action( 'admin_init', 'siw_settings_tariffs_init' );
add_action( 'admin_init', 'siw_settings_evs_init' );
add_action( 'admin_init', 'siw_settings_api_init' );
add_action( 'admin_init', 'siw_settings_jobs_init');
add_action( 'admin_init', 'siw_settings_agenda_init');
add_action( 'admin_init', 'siw_settings_forms_init');
add_action( 'admin_init', 'siw_settings_community_day_init');
add_action( 'admin_init', 'siw_settings_login_init');

function siw_add_settings_menu(){ 
	add_menu_page( 'Instellingen SIW', 'Instellingen SIW', 'manage_options', 'siw_settings', 'siw_settings_page','dashicons-admin-settings',110);
	add_submenu_page( 'siw_settings', 'Instellingen SIW', 'Agenda', 'manage_options', 'siw_settings');
	add_submenu_page( 'siw_settings', 'Instellingen SIW', 'Algemeen', 'manage_options', 'admin.php?page=siw_settings&tab=general');
	add_submenu_page( 'siw_settings', 'Instellingen SIW', 'API keys', 'manage_options', 'admin.php?page=siw_settings&tab=api');
	add_submenu_page( 'siw_settings', 'Instellingen SIW', 'Community Day', 'manage_options', 'admin.php?page=siw_settings&tab=community_day');	
	add_submenu_page( 'siw_settings', 'Instellingen SIW', 'EVS', 'manage_options', 'admin.php?page=siw_settings&tab=evs');
	add_submenu_page( 'siw_settings', 'Instellingen SIW', 'Formulieren', 'manage_options', 'admin.php?page=siw_settings&tab=forms');
	add_submenu_page( 'siw_settings', 'Instellingen SIW', 'Login', 'manage_options', 'admin.php?page=siw_settings&tab=login');
	add_submenu_page( 'siw_settings', 'Instellingen SIW', 'Ondertekening', 'manage_options', 'admin.php?page=siw_settings&tab=signatures');
	add_submenu_page( 'siw_settings', 'Instellingen SIW', 'PLATO', 'manage_options', 'admin.php?page=siw_settings&tab=plato');
	add_submenu_page( 'siw_settings', 'Instellingen SIW', 'Tarieven', 'manage_options', 'admin.php?page=siw_settings&tab=tariffs');
	add_submenu_page( 'siw_settings', 'Instellingen SIW', 'Vacatures', 'manage_options', 'admin.php?page=siw_settings&tab=jobs');
}

function siw_settings_general_init(){ 

	register_setting( 'siw_general', 'siw_general_iban', 'sanitize_text_field' );
	register_setting( 'siw_general', 'siw_general_kvk', 'sanitize_text_field' );
	register_setting( 'siw_general', 'siw_general_phone', 'sanitize_text_field' );
	register_setting( 'siw_general', 'siw_general_email', 'sanitize_email' );
	
	//secties
	add_settings_section(
		'siw_general', 
		__( 'Algemeen', 'siw' ), 
		'__return_false', 
		'siw_general'
	);
	add_settings_field( 
		'siw_general_iban', 
		__( 'IBAN', 'siw' ), 
		'siw_settings_show_text_field', 
		'siw_general',
		'siw_general', 
		'siw_general_iban' 
	);
	add_settings_field( 
		'siw_general_kvk', 
		__( 'KvK nummer', 'siw' ), 
		'siw_settings_show_text_field', 
		'siw_general',
		'siw_general', 
		'siw_general_kvk' 
	);
	add_settings_field( 
		'siw_general_phone', 
		__( 'Telefoonnummer', 'siw' ), 
		'siw_settings_show_text_field', 
		'siw_general',
		'siw_general', 
		'siw_general_phone' 
	);
	add_settings_field( 
		'siw_general_email', 
		__( 'E-mail', 'siw' ), 
		'siw_settings_show_email_field', 
		'siw_general',
		'siw_general', 
		'siw_general_email' 
	);	
}


function siw_settings_signatures_init(){ 
	//ondertekening e-mails
	register_setting( 'siw_signatures', 'siw_signature_general', 'sanitize_text_field' );
	register_setting( 'siw_signatures', 'siw_signature_project', 'sanitize_text_field' );
	register_setting( 'siw_signatures', 'siw_signature_camp_leader', 'sanitize_text_field' );
	register_setting( 'siw_signatures', 'siw_signature_evs', 'sanitize_text_field' );
	register_setting( 'siw_signatures', 'siw_signature_op_maat', 'sanitize_text_field' );
	register_setting( 'siw_signatures', 'siw_signature_community_day', 'sanitize_text_field' );
	register_setting( 'siw_signatures', 'siw_signature_workcamp', 'sanitize_text_field' );	

	//secties
	add_settings_section(
		'siw_signatures_contact_forms', 
		__( 'Contactformulieren', 'siw' ), 
		'__return_false', 
		'siw_signatures'
	);
	add_settings_section(
		'siw_signatures_application_forms', 
		__( 'Aanmeldformulieren', 'siw' ), 
		'__return_false', 
		'siw_signatures'
	);
	//velden
	add_settings_field( 
		'siw_signature_general', 
		__( 'Algemeen', 'siw' ), 
		'siw_settings_show_text_field', 
		'siw_signatures',
		'siw_signatures_contact_forms', 
		'siw_signature_general' 
	);	
	add_settings_field( 
		'siw_signature_project', 
		__( 'Project', 'siw' ), 
		'siw_settings_show_text_field', 
		'siw_signatures',
		'siw_signatures_contact_forms', 
		'siw_signature_project' 
	);		
	add_settings_field( 
		'siw_signature_camp_leader', 
		__( 'Projectbegeleider', 'siw' ), 
		'siw_settings_show_text_field', 
		'siw_signatures',
		'siw_signatures_contact_forms', 
		'siw_signature_camp_leader' 
	);	
	add_settings_field( 
		'siw_signature_evs', 
		__( 'EVS aanmelding', 'siw' ), 
		'siw_settings_show_text_field', 
		'siw_signatures',
		'siw_signatures_application_forms', 
		'siw_signature_evs' 
	);	
	add_settings_field( 
		'siw_signature_op_maat', 
		__( 'Op maat aanmelding', 'siw' ), 
		'siw_settings_show_text_field', 
		'siw_signatures',
		'siw_signatures_application_forms', 
		'siw_signature_op_maat' 
	);		
	add_settings_field( 
		'siw_signature_community_day', 
		__( 'Community day', 'siw' ), 
		'siw_settings_show_text_field', 
		'siw_signatures',
		'siw_signatures_application_forms', 
		'siw_signature_community_day' 
	);	
	add_settings_field( 
		'siw_signature_workcamp', 
		__( 'Groepsproject', 'siw' ), 
		'siw_settings_show_text_field', 
		'siw_signatures',
		'siw_signatures_application_forms', 
		'siw_signature_workcamp' 
	);		
	
}

//instellingen voor plato
function siw_settings_plato_init(){ 
	register_setting( 'siw_plato', 'siw_plato_outgoing_placements_name', 'sanitize_text_field' );
	register_setting( 'siw_plato', 'siw_plato_outgoing_placements_email', 'sanitize_email' );
	register_setting( 'siw_plato', 'siw_plato_nr_of_days_before_start_to_hide_project', 'absint' );		
	register_setting( 'siw_plato', 'siw_plato_force_full_import', 'siw_sanitize_checkbox');
	register_setting( 'siw_plato', 'siw_plato_organization_web_key', 'sanitize_text_field' );	

	//secties
	add_settings_section(
		'siw_plato_outgoing_placements', 
		__( 'Outgoing placements', 'siw' ), 
		'siw_settings_plato_outgoing_placements_header', 
		'siw_plato'
	);
	add_settings_section(
		'siw_plato_import', 
		__( 'Import', 'siw' ), 
		'__return_false', 
		'siw_plato'
	);
	add_settings_section(
		'siw_plato_webservice', 
		__( 'Webservice', 'siw' ), 
		'__return_false', 
		'siw_plato'
	);
	//velden
	add_settings_field( 
		'siw_plato_outgoing_placements_name', 
		__( 'Naam', 'siw' ), 
		'siw_settings_show_text_field', 
		'siw_plato',
		'siw_plato_outgoing_placements', 
		'siw_plato_outgoing_placements_name' 
	);	
	add_settings_field( 
		'siw_plato_outgoing_placements_email', 
		__( 'E-mail', 'siw' ), 
		'siw_settings_show_email_field', 
		'siw_plato',
		'siw_plato_outgoing_placements', 
		'siw_plato_outgoing_placements_email' 
	);
	add_settings_field( 
		'siw_plato_nr_of_days_before_start_to_hide_project', 
		__( 'Verberg project vanaf aantal dagen voor start project.', 'siw' ), 
		'siw_settings_show_number_field', 
		'siw_plato',
		'siw_plato_import', 
		'siw_plato_nr_of_days_before_start_to_hide_project' 
	);	
	add_settings_field( 
		'siw_plato_force_full_import', 
		__( 'Forceer volledige import', 'siw' ), 
		'siw_settings_show_checkbox_field', 
		'siw_plato',
		'siw_plato_import', 
		'siw_plato_force_full_import' 
	);		
	add_settings_field( 
		'siw_plato_organization_web_key', 
		__( 'Organization key', 'siw' ), 
		'siw_settings_show_text_field', 
		'siw_plato',
		'siw_plato_webservice', 
		'siw_plato_organization_web_key' 
	);		
}

function siw_settings_tariffs_init(){
	register_setting( 'siw_tariffs', 'siw_tariffs_workcamp_student', 'absint' );
	register_setting( 'siw_tariffs', 'siw_tariffs_workcamp_regular', 'absint'  );
	register_setting( 'siw_tariffs', 'siw_tariffs_workcamp_discount_second_project', 'absint');
	register_setting( 'siw_tariffs', 'siw_tariffs_workcamp_discount_third_project', 'absint');
	register_setting( 'siw_tariffs', 'siw_tariffs_op_maat_student', 'absint'  );
	register_setting( 'siw_tariffs', 'siw_tariffs_op_maat_regular', 'absint'  );
	register_setting( 'siw_tariffs', 'siw_tariffs_evs_deposit', 'absint'  );
	
	//secties
	add_settings_section(
		'siw_tarrifs_workcamp', 
		__( 'Groepsprojecten', 'siw' ), 
		'siw_settings_tarrifs_workcamp_header', 
		'siw_tariffs'
	);
	add_settings_section(
		'siw_tarrifs_op_maat', 
		__( 'Op maat', 'siw' ), 
		'siw_settings_tariffs_op_maat_header', 
		'siw_tariffs'
	);
	add_settings_section(
		'siw_tarrifs_evs', 
		__( 'EVS', 'siw' ), 
		'siw_settings_tariffs_evs_header', 
		'siw_tariffs'
	);
	add_settings_field( 
		'siw_tariffs_workcamp_student', 
		__( 'Student', 'siw' ), 
		'siw_settings_show_amount_field', 
		'siw_tariffs',
		'siw_tarrifs_workcamp', 
		'siw_tariffs_workcamp_student' 
	);
	add_settings_field( 
		'siw_tariffs_workcamp_regular', 
		__( 'Regulier', 'siw' ), 
		'siw_settings_show_amount_field', 
		'siw_tariffs',
		'siw_tarrifs_workcamp', 
		'siw_tariffs_workcamp_regular' 
	);	
	add_settings_field( 
		'siw_tariffs_workcamp_discount_second_project', 
		__( 'Korting 2e project', 'siw' ), 
		'siw_settings_show_percentage_field', 
		'siw_tariffs',
		'siw_tarrifs_workcamp', 
		'siw_tariffs_workcamp_discount_second_project' 
	);		
	add_settings_field( 
		'siw_tariffs_workcamp_discount_third_project', 
		__( 'Korting 3e project', 'siw' ), 
		'siw_settings_show_percentage_field', 
		'siw_tariffs',
		'siw_tarrifs_workcamp', 
		'siw_tariffs_workcamp_discount_third_project' 
	);			
	add_settings_field( 
		'siw_tariffs_op_maat_student', 
		__( 'Student', 'siw' ), 
		'siw_settings_show_amount_field', 
		'siw_tariffs',
		'siw_tarrifs_op_maat', 
		'siw_tariffs_op_maat_student' 
	);
	add_settings_field( 
		'siw_tariffs_op_maat_regular', 
		__( 'Regulier', 'siw' ), 
		'siw_settings_show_amount_field', 
		'siw_tariffs',
		'siw_tarrifs_op_maat', 
		'siw_tariffs_op_maat_regular' 
	);
	add_settings_field( 
		'siw_tariffs_evs_deposit', 
		__( 'Borg', 'siw' ), 
		'siw_settings_show_amount_field', 
		'siw_tariffs',
		'siw_tarrifs_evs', 
		'siw_tariffs_evs_deposit' 
	);		
}


function siw_settings_evs_init(){
	register_setting( 'siw_evs', 'siw_evs_weeks_before_deadline', 'absint'  );
	register_setting( 'siw_evs', 'siw_evs_deadline_1', 'sanitize_text_field' );
	register_setting( 'siw_evs', 'siw_evs_deadline_2', 'sanitize_text_field' );
	register_setting( 'siw_evs', 'siw_evs_deadline_3', 'sanitize_text_field' );
	register_setting( 'siw_evs', 'siw_evs_deadline_4', 'sanitize_text_field' );
	register_setting( 'siw_evs', 'siw_evs_deadline_5', 'sanitize_text_field' );
	
	//secties
	add_settings_section(
		'siw_evs_deadlines', 
		__( 'Deadlines', 'siw' ), 
		'siw_settings_evs_deadlines_header', 
		'siw_evs'
	);
	add_settings_field( 
		'siw_evs_weeks_before_deadline', 
		__( 'Aantal weken voor deadline', 'siw' ), 
		'siw_settings_show_number_field', 
		'siw_evs',
		'siw_evs_deadlines', 
		'siw_evs_weeks_before_deadline' 
	);
	add_settings_field( 
		'siw_evs_deadline_1', 
		__( 'Deadline 1', 'siw' ), 
		'siw_settings_show_date_field', 
		'siw_evs',
		'siw_evs_deadlines', 
		'siw_evs_deadline_1' 
	);
	add_settings_field( 
		'siw_evs_deadline_2', 
		__( 'Deadline 2', 'siw' ), 
		'siw_settings_show_date_field', 
		'siw_evs',
		'siw_evs_deadlines', 
		'siw_evs_deadline_2' 
	);
	add_settings_field( 
		'siw_evs_deadline_3', 
		__( 'Deadline 3', 'siw' ), 
		'siw_settings_show_date_field', 
		'siw_evs',
		'siw_evs_deadlines', 
		'siw_evs_deadline_3' 
	);
	add_settings_field( 
		'siw_evs_deadline_4', 
		__( 'Deadline 4', 'siw' ), 
		'siw_settings_show_date_field', 
		'siw_evs',
		'siw_evs_deadlines', 
		'siw_evs_deadline_4' 
	);
	add_settings_field( 
		'siw_evs_deadline_5', 
		__( 'Deadline 5', 'siw' ), 
		'siw_settings_show_date_field', 
		'siw_evs',
		'siw_evs_deadlines', 
		'siw_evs_deadline_5' 
	);
}


function siw_settings_api_init(){
	register_setting( 'siw_api', 'siw_api_google_analytics_id', 'sanitize_text_field' );
	register_setting( 'siw_api', 'siw_api_google_analytics_enable_linkid', 'siw_sanitize_checkbox' );
	register_setting( 'siw_api', 'siw_api_postcode_api_key', 'sanitize_text_field' );
	register_setting( 'siw_api', 'siw_api_pingdom_rum_id', 'sanitize_text_field' );
	
	//secties
	add_settings_section(
		'siw_api_keys', 
		__( 'API keys', 'siw' ), 
		'__return_false', 
		'siw_api'
	);
	add_settings_field( 
		'siw_api_google_analytics_id', 
		__( 'Google Analytics ID', 'siw' ), 
		'siw_settings_show_text_field', 
		'siw_api',
		'siw_api_keys', 
		'siw_api_google_analytics_id' 
	);
	add_settings_field( 
		'siw_api_google_analytics_enable_linkid', 
		__( 'Enhanced link attribution', 'siw' ), 
		'siw_settings_show_checkbox_field', 
		'siw_api',
		'siw_api_keys', 
		'siw_api_google_analytics_enable_linkid' 
	);	
	add_settings_field( 
		'siw_api_postcode_api_key', 
		__( 'Postcode API key', 'siw' ), 
		'siw_settings_show_text_field', 
		'siw_api',
		'siw_api_keys', 
		'siw_api_postcode_api_key' 
	);
	add_settings_field( 
		'siw_api_pingdom_rum_id', 
		__( 'Pingdom RUM ID', 'siw' ), 
		'siw_settings_show_text_field', 
		'siw_api',
		'siw_api_keys', 
		'siw_api_pingdom_rum_id' 
	);

}


function siw_settings_jobs_init(){
	register_setting( 'siw_jobs', 'siw_jobs_company_profile', 'wp_kses_post' );
	register_setting( 'siw_jobs', 'siw_jobs_mission_statement', 'wp_kses_post' );
	register_setting( 'siw_jobs', 'siw_jobs_parent_page', 'absint');

	
	//secties
	add_settings_section(
		'siw_jobs', 
		__( 'Vacatures', 'siw' ), 
		'__return_false', 
		'siw_jobs'
	);
	add_settings_field( 
		'siw_jobs_company_profile', 
		__( 'Wie zijn wij?', 'siw' ), 
		'siw_settings_show_textarea_field', 
		'siw_jobs',
		'siw_jobs', 
		'siw_jobs_company_profile' 
	);
	add_settings_field( 
		'siw_jobs_mission_statement', 
		__( 'Missie', 'siw' ), 
		'siw_settings_show_textarea_field', 
		'siw_jobs',
		'siw_jobs', 
		'siw_jobs_mission_statement' 
	);
	add_settings_field( 
		'siw_jobs_parent_page', 
		__( 'Vacature pagina', 'siw' ), 
		'siw_settings_show_page_select', 
		'siw_jobs',
		'siw_jobs', 
		'siw_jobs_parent_page' 
	);
}


function siw_settings_agenda_init(){
	register_setting( 'siw_agenda', 'siw_agenda_parent_page', 'absint' );

	
	//secties
	add_settings_section(
		'siw_agenda', 
		__( 'Agenda', 'siw' ), 
		'__return_false', 
		'siw_agenda'
	);
	add_settings_field( 
		'siw_agenda_parent_page', 
		__( 'Agenda pagina', 'siw' ), 
		'siw_settings_show_page_select', 
		'siw_agenda',
		'siw_agenda', 
		'siw_agenda_parent_page' 
	);
}

function siw_settings_forms_init(){
	register_setting( 'siw_forms', 'siw_forms_community_day', 'absint'  );
	register_setting( 'siw_forms', 'siw_forms_evs', 'absint'  );
	register_setting( 'siw_forms', 'siw_forms_op_maat', 'absint'  );
	register_setting( 'siw_forms', 'siw_forms_algemeen', 'absint'  );
	register_setting( 'siw_forms', 'siw_forms_project', 'absint'  );
	register_setting( 'siw_forms', 'siw_forms_begeleider', 'absint'  );
	
	//secties
	add_settings_section(
		'siw_forms', 
		__( 'Formulieren', 'siw' ), 
		'__return_false', 
		'siw_forms'
	);
	add_settings_field( 
		'siw_forms_community_day', 
		__( 'Community Day', 'siw' ), 
		'siw_settings_show_vfb_form_select', 
		'siw_forms',
		'siw_forms', 
		'siw_forms_community_day' 
	);
	add_settings_field( 
		'siw_forms_evs', 
		__( 'EVS', 'siw' ), 
		'siw_settings_show_vfb_form_select', 
		'siw_forms',
		'siw_forms', 
		'siw_forms_evs' 
	);
	add_settings_field( 
		'siw_forms_op_maat', 
		__( 'Op maat', 'siw' ), 
		'siw_settings_show_vfb_form_select', 
		'siw_forms',
		'siw_forms', 
		'siw_forms_op_maat' 
	);
	add_settings_field( 
		'siw_forms_algemeen', 
		__( 'Contactformulier algemeen', 'siw' ), 
		'siw_settings_show_cf7_form_select', 
		'siw_forms',
		'siw_forms', 
		'siw_forms_algemeen' 
	);
	add_settings_field( 
		'siw_forms_project', 
		__( 'Contactformulier project', 'siw' ), 
		'siw_settings_show_cf7_form_select', 
		'siw_forms',
		'siw_forms', 
		'siw_forms_project' 
	);
	add_settings_field( 
		'siw_forms_begeleider', 
		__( 'Projectbegeleider', 'siw' ), 
		'siw_settings_show_cf7_form_select', 
		'siw_forms',
		'siw_forms', 
		'siw_forms_begeleider' 
	);
}


function siw_settings_community_day_init(){
	register_setting( 'siw_community_day', 'siw_community_day_1', 'sanitize_text_field' );
	register_setting( 'siw_community_day', 'siw_community_day_2', 'sanitize_text_field' );
	register_setting( 'siw_community_day', 'siw_community_day_3', 'sanitize_text_field' );
	register_setting( 'siw_community_day', 'siw_community_day_4', 'sanitize_text_field' );
	register_setting( 'siw_community_day', 'siw_community_day_5', 'sanitize_text_field' );
	register_setting( 'siw_community_day', 'siw_community_day_6', 'sanitize_text_field' );
	register_setting( 'siw_community_day', 'siw_community_day_7', 'sanitize_text_field' );
	register_setting( 'siw_community_day', 'siw_community_day_8', 'sanitize_text_field' );
	register_setting( 'siw_community_day', 'siw_community_day_9', 'sanitize_text_field' );
	register_setting( 'siw_community_day', 'siw_community_day_vfb_dates_field', 'absint' );
	
	//secties
	add_settings_section(
		'siw_community_day', 
		__( 'Community days', 'siw' ), 
		'siw_settings_community_day_header', 
		'siw_community_day'
	);
	add_settings_section(
		'siw_community_day_vfb_fields', 
		__( 'Formuliervragen', 'siw' ), 
		'__return_false', 
		'siw_community_day'
	);	
	add_settings_field( 
		'siw_community_day_1', 
		__( 'Community day 1', 'siw' ), 
		'siw_settings_show_date_field', 
		'siw_community_day',
		'siw_community_day', 
		'siw_community_day_1' 
	);
	add_settings_field( 
		'siw_community_day_2', 
		__( 'Community day 2', 'siw' ), 
		'siw_settings_show_date_field', 
		'siw_community_day',
		'siw_community_day', 
		'siw_community_day_2' 
	);
	add_settings_field( 
		'siw_community_day_3', 
		__( 'Community day 3', 'siw' ), 
		'siw_settings_show_date_field', 
		'siw_community_day',
		'siw_community_day', 
		'siw_community_day_3' 
	);
	add_settings_field( 
		'siw_community_day_4', 
		__( 'Community day 4', 'siw' ), 
		'siw_settings_show_date_field', 
		'siw_community_day',
		'siw_community_day', 
		'siw_community_day_4' 
	);
	add_settings_field( 
		'siw_community_day_5', 
		__( 'Community day 5', 'siw' ), 
		'siw_settings_show_date_field', 
		'siw_community_day',
		'siw_community_day', 
		'siw_community_day_5' 
	);
	add_settings_field( 
		'siw_community_day_6', 
		__( 'Community day 6', 'siw' ), 
		'siw_settings_show_date_field', 
		'siw_community_day',
		'siw_community_day', 
		'siw_community_day_6' 
	);
	add_settings_field( 
		'siw_community_day_7', 
		__( 'Community day 7', 'siw' ), 
		'siw_settings_show_date_field', 
		'siw_community_day',
		'siw_community_day', 
		'siw_community_day_7' 
	);
	add_settings_field( 
		'siw_community_day_8', 
		__( 'Community day 8', 'siw' ), 
		'siw_settings_show_date_field', 
		'siw_community_day',
		'siw_community_day', 
		'siw_community_day_8' 
	);
	add_settings_field( 
		'siw_community_day_9', 
		__( 'Community day 9', 'siw' ), 
		'siw_settings_show_date_field', 
		'siw_community_day',
		'siw_community_day', 
		'siw_community_day_9' 
	);
	add_settings_field( 
		'siw_community_day_vfb_dates_field', 
		__( 'Datums', 'siw' ), 
		'siw_settings_show_vfb_field', 
		'siw_community_day',
		'siw_community_day_vfb_fields', 
		'siw_community_day_vfb_dates_field' 
	);
}




//login

function siw_settings_login_init(){
	register_setting( 'siw_login', 'siw_login_whitelist_ip_1', 'sanitize_text_field' );
	register_setting( 'siw_login', 'siw_login_whitelist_ip_2', 'sanitize_text_field' );
	register_setting( 'siw_login', 'siw_login_whitelist_ip_3', 'sanitize_text_field' );
	register_setting( 'siw_login', 'siw_login_whitelist_ip_4', 'sanitize_text_field' );
	register_setting( 'siw_login', 'siw_login_whitelist_ip_5', 'sanitize_text_field' );
	
	//secties
	add_settings_section(
		'siw_login_whitelist', 
		__( 'Whitelist', 'siw' ), 
		'__return_false', 
		'siw_login'
	);
	add_settings_field( 
		'siw_login_whitelist_ip_1', 
		__( 'IP 1', 'siw' ), 
		'siw_settings_show_ip_field', 
		'siw_login',
		'siw_login_whitelist', 
		'siw_login_whitelist_ip_1' 
	);
	add_settings_field( 
		'siw_login_whitelist_ip_2', 
		__( 'IP 2', 'siw' ), 
		'siw_settings_show_ip_field', 
		'siw_login',
		'siw_login_whitelist', 
		'siw_login_whitelist_ip_2' 
	);
	add_settings_field( 
		'siw_login_whitelist_ip_3', 
		__( 'IP 3', 'siw' ), 
		'siw_settings_show_ip_field', 
		'siw_login',
		'siw_login_whitelist', 
		'siw_login_whitelist_ip_3' 
	);
	add_settings_field( 
		'siw_login_whitelist_ip_4', 
		__( 'IP 4', 'siw' ), 
		'siw_settings_show_ip_field', 
		'siw_login',
		'siw_login_whitelist', 
		'siw_login_whitelist_ip_4' 
	);
	add_settings_field( 
		'siw_login_whitelist_ip_5', 
		__( 'IP 5', 'siw' ), 
		'siw_settings_show_ip_field', 
		'siw_login',
		'siw_login_whitelist', 
		'siw_login_whitelist_ip_5' 
	);
}

//functies op secties te tonen

function siw_settings_plato_outgoing_placements_header() { 
	echo __( 'Afzender van de aanmelding bij export naar PLATO', 'siw' );
}
function siw_settings_community_day_header() { 
	echo __( 'Gebruikt in shortcode [siw_volgende_community_day]', 'siw' );
}
function siw_settings_tariffs_evs_header() { 
	echo __( 'Gebruikt in shortcode [siw_evs_borg]', 'siw' );
}
function siw_settings_tarrifs_workcamp_header() { 
	echo __( 'Gebruikt in PLATO-import en in shortcode [siw_inschrijfgeld_groepsproject tarief="regulier|student"] en [siw_korting_groepsproject aantal="tweede|derde"]', 'siw' );
}
function siw_settings_tariffs_op_maat_header() { 
	echo __( 'Gebruikt in shortcode [siw_inschrijfgeld_op_maat tarief="regulier|student"]', 'siw' );
}
function siw_settings_evs_deadlines_header() { 
	echo __( 'Gebruikt in shortcodes [siw_evs_volgende_deadline] en [siw_evs_volgende_vertrekmoment]', 'siw' );
}




//functies om velden te tonen
function siw_settings_show_text_field( $option ) {
	?>
	<input type='text' name='<?php echo esc_attr( $option ); ?>' value='<?php echo esc_attr( get_option( $option ) ); ?>' size="50">
	<?php
}

function siw_settings_show_textarea_field( $option ) {
	?>
	<textarea name='<?php echo esc_attr( $option ); ?>' maxlength="2000" rows="10" cols="100"><?php echo esc_textarea( get_option( $option ) ); ?></textarea>
	<?php
}

function siw_settings_show_amount_field( $option ){
	?> &euro;
	<input type='number' name='<?php echo esc_attr( $option ); ?>' value='<?php echo esc_attr( get_option( $option ) ); ?>' min="1" max="1000">
	<?php
}
function siw_settings_show_percentage_field( $option ){
	?>
	<input type='number' name='<?php echo esc_attr( $option ); ?>' value='<?php echo esc_attr( get_option( $option ) ); ?>' min="1" max="99">%
	<?php
}

function siw_settings_show_email_field( $option ){
	?>
	<input type='email' name='<?php echo esc_attr( $option ); ?>' value='<?php echo esc_attr( get_option( $option ) ); ?>' size="50">
	<?php
}
function siw_settings_show_number_field( $option ) { 
	?>
	<input type='number' name='<?php echo esc_attr( $option ); ?>' value='<?php echo esc_attr( get_option( $option ) ); ?>' min="1" max="30"> 
	<?php
}
function siw_settings_show_date_field( $option ) { 
	?>
	<input type='date' name='<?php echo esc_attr( $option ); ?>' value='<?php echo esc_attr( get_option( $option ) ); ?>'> 
	<?php
}

function siw_settings_show_checkbox_field( $option ){
	?>
	<input type='checkbox' name='<?php echo esc_attr( $option ); ?>' <?php checked( esc_attr( get_option( $option ) ), 1 ); ?> value='1'>
	<?php
}	


function siw_settings_show_ip_field( $option ){
	?>
	<input type='text' name='<?php echo esc_attr( $option ); ?>' value='<?php echo esc_attr( get_option( $option ) ); ?>' placeholder='172.16.254.1' pattern='((^|\.)((25[0-5])|(2[0-4]\d)|(1\d\d)|([1-9]?\d))){4}$'">
	<?php
}

function siw_settings_show_vfb_form_select( $option ) {

	global $wpdb;
	if (!isset( $wpdb->vfbp_forms )) {
		$wpdb->vfbp_forms = $wpdb->prefix . 'vfbp_forms';
	}
	
	$query = "SELECT $wpdb->vfbp_forms.id, $wpdb->vfbp_forms.title FROM $wpdb->vfbp_forms ORDER BY $wpdb->vfbp_forms.title ASC";
	
	$forms = $wpdb->get_results($query, ARRAY_A);

    if (!empty($forms)) {
		echo '<select name="', esc_attr( $option ), '">';
		foreach ( $forms as $form ) {
			echo '<option value="', esc_attr( $form[id] ), '"', get_option( $option ) == $form[id] ? ' selected="selected"' : '', '>', esc_html( $form[title] ), '</option>';
		}
		echo '</select>'; 
	}
}

function siw_settings_show_cf7_form_select( $option ) {
	$args = array(
		'posts_per_page'	=> -1,
		'orderby'			=> 'title',
		'order'				=> 'ASC',
		'post_type'			=> 'wpcf7_contact_form',
		'fields' 			=> 'ids'
	);
	$forms = get_posts( $args ); 
	
    if (!empty($forms)) {
		echo '<select name="', esc_attr( $option ), '">';
		foreach ( $forms as $form ) {
			echo '<option value="', $form, '"', get_option( $option ) == $form ? ' selected="selected"' : '', '>', esc_html( get_the_title( $form ) ), '</option>';
		}
		echo '</select>'; 
	}	
}

function siw_settings_show_vfb_field( $option ){

	$form_id = siw_get_vfb_form_id('community_day');

	global $wpdb;
	if (!isset( $wpdb->vfbp_fields )) {
		$wpdb->vfbp_fields = $wpdb->prefix . 'vfbp_fields';
	}
	
	$query = "SELECT $wpdb->vfbp_fields.id, $wpdb->vfbp_fields.data FROM $wpdb->vfbp_fields WHERE $wpdb->vfbp_fields.form_id = %d ORDER BY $wpdb->vfbp_fields.field_order ASC";
	$fields = $wpdb->get_results( $wpdb->prepare( $query, $form_id), ARRAY_A);

	if(!empty($fields)){
		echo '<select name="', esc_attr( $option ), '">';
		foreach ( $fields as $field){
			$id = $field['id'];
			$label = maybe_unserialize( $field['data'] )['label'];
			echo '<option value="', $id, '"', get_option( $option ) == $id ? ' selected="selected"' : '', '>', esc_html( $label ), '</option>';
		
		}
		echo '</select>'; 
	}

}



function siw_settings_show_page_select( $option ) {
	$pages = get_pages(); 
    if (!empty($pages)) {
		echo '<select name="', esc_attr( $option ), '">';
		  foreach ( $pages as $page ) {
		    echo '<option value="', $page->ID, '"', get_option( $option ) == $page->ID ? ' selected="selected"' : '', '>',(($page->post_parent)?'-- ':''), esc_html( $page->post_title ), '</option>';
		  }
		  echo '</select>'; 
	}
}

function siw_settings_page(  ) {?>
    <div class="wrap">  
        <h2>Instellingen SIW</h2>
        <?php settings_errors();
		$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'agenda';
		?>        
		<h2 class="nav-tab-wrapper">
			<a href="?page=siw_settings&tab=agenda" class="nav-tab <?php echo 'agenda' == $active_tab ? 'nav-tab-active' : ''; ?>">Agenda</a>	
			<a href="?page=siw_settings&tab=general" class="nav-tab <?php echo 'general' == $active_tab ? 'nav-tab-active' : ''; ?>">Algemeen</a>
			<a href="?page=siw_settings&tab=api" class="nav-tab <?php echo 'api' == $active_tab ? 'nav-tab-active' : ''; ?>">API keys</a>
			<a href="?page=siw_settings&tab=community_day" class="nav-tab <?php echo 'community_day' == $active_tab ? 'nav-tab-active' : ''; ?>">Community Day</a>			
			<a href="?page=siw_settings&tab=evs" class="nav-tab <?php echo 'evs' == $active_tab ? 'nav-tab-active' : ''; ?>">EVS</a>
			<a href="?page=siw_settings&tab=forms" class="nav-tab <?php echo 'forms' == $active_tab ? 'nav-tab-active' : ''; ?>">Formulieren</a>
			<a href="?page=siw_settings&tab=login" class="nav-tab <?php echo 'login' == $active_tab ? 'nav-tab-active' : ''; ?>">Login</a>
			<a href="?page=siw_settings&tab=signatures" class="nav-tab <?php echo 'signatures' == $active_tab ? 'nav-tab-active' : ''; ?>">Ondertekening</a>
			<a href="?page=siw_settings&tab=plato" class="nav-tab <?php echo 'plato' == $active_tab ? 'nav-tab-active' : ''; ?>">PLATO</a>
			<a href="?page=siw_settings&tab=tariffs" class="nav-tab <?php echo 'tariffs' == $active_tab ? 'nav-tab-active' : ''; ?>">Tarieven</a>
			<a href="?page=siw_settings&tab=jobs" class="nav-tab <?php echo 'jobs' == $active_tab ? 'nav-tab-active' : ''; ?>">Vacatures</a>
		</h2>        
        <form method="post" action="options.php">
			<?php  
			if( 'general' == $active_tab ) {
				settings_fields( 'siw_general' );
				do_settings_sections( 'siw_general' );
			}
			else if( 'signatures' == $active_tab ) {
				settings_fields( 'siw_signatures' );
				do_settings_sections( 'siw_signatures' );
			}
			else if( 'tariffs' == $active_tab ) {
				settings_fields( 'siw_tariffs' );
				do_settings_sections( 'siw_tariffs' );				
			}
			else if( 'plato' == $active_tab ) {
				settings_fields( 'siw_plato' );
				do_settings_sections( 'siw_plato' );
			}
			else if( 'evs' == $active_tab ) {
				settings_fields( 'siw_evs' );
				do_settings_sections( 'siw_evs' );
			} 
			else if( 'api' == $active_tab ) {
				settings_fields( 'siw_api' );
				do_settings_sections( 'siw_api' );
			}
			else if( 'jobs' == $active_tab ) {
				settings_fields( 'siw_jobs' );
				do_settings_sections( 'siw_jobs' );
			}
			else if( 'agenda' == $active_tab ) {
				settings_fields( 'siw_agenda' );
				do_settings_sections( 'siw_agenda' );
			}
			else if( 'forms' == $active_tab ) {
				settings_fields( 'siw_forms' );
				do_settings_sections( 'siw_forms' );
			} 
			else if( 'community_day' == $active_tab ) {
				settings_fields( 'siw_community_day' );
				do_settings_sections( 'siw_community_day' );
			} 	
			else if( 'login' == $active_tab ) {
				settings_fields( 'siw_login' );
				do_settings_sections( 'siw_login' );
			} 			
			submit_button();
			?>
		</form>     
    </div><?php
}

/*
Sanitize-functies
*/
function siw_sanitize_checkbox( $checked ) {
	return ( ( isset( $checked ) && true == $checked ) ? true : false );
}


/*
E-mail templates bijwerken na aanpassen ondertekening
*/

add_action( 'update_option_siw_signature_evs', 'siw_update_email_template_evs', 10, 3 );
function siw_update_email_template_evs($old_value, $value ) {
	siw_update_vfb_mail_template('evs');
}

add_action( 'update_option_siw_signature_op_maat', 'siw_update_email_template_op_maat', 10, 3 );
function siw_update_email_template_op_maat($old_value, $value ) {
	siw_update_vfb_mail_template('op_maat');
}

add_action( 'update_option_siw_signature_community_day', 'siw_update_email_template_community_day', 10, 3 );
function siw_update_email_template_community_day($old_value, $value ) {
	siw_update_vfb_mail_template('community_day');
}

//cd-opties bijwerken nu update datums
add_action( 'update_option_siw_community_day_1', 'siw_update_community_day_options', 10, 3 );
add_action( 'update_option_siw_community_day_2', 'siw_update_community_day_options', 10, 3 );
add_action( 'update_option_siw_community_day_3', 'siw_update_community_day_options', 10, 3 );
add_action( 'update_option_siw_community_day_4', 'siw_update_community_day_options', 10, 3 );
add_action( 'update_option_siw_community_day_5', 'siw_update_community_day_options', 10, 3 );
add_action( 'update_option_siw_community_day_6', 'siw_update_community_day_options', 10, 3 );
add_action( 'update_option_siw_community_day_7', 'siw_update_community_day_options', 10, 3 );
add_action( 'update_option_siw_community_day_8', 'siw_update_community_day_options', 10, 3 );
add_action( 'update_option_siw_community_day_9', 'siw_update_community_day_options', 10, 3 );