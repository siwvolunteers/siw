<?php
/*
(c)2015 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_action( 'admin_menu', 'siw_add_settings_menu' );
add_action( 'admin_init', 'siw_settings_signatures_init' );
add_action( 'admin_init', 'siw_settings_plato_init' );
add_action( 'admin_init', 'siw_settings_tariffs_init' );
add_action( 'admin_init', 'siw_settings_evs_init' );
add_action( 'admin_init', 'siw_settings_api_init' );

function siw_add_settings_menu(){ 
	add_menu_page( 'Instellingen SIW', 'Instellingen SIW', 'manage_options', 'siw_settings', 'siw_settings_page','dashicons-admin-settings');
}

function siw_settings_signatures_init(){ 
	//ondertekening e-mails
	register_setting( 'siw_signatures', 'siw_signature_general' );
	register_setting( 'siw_signatures', 'siw_signature_project' );
	register_setting( 'siw_signatures', 'siw_signature_camp_leader' );
	register_setting( 'siw_signatures', 'siw_signature_evs' );
	register_setting( 'siw_signatures', 'siw_signature_op_maat' );
	register_setting( 'siw_signatures', 'siw_signature_community_day' );
	register_setting( 'siw_signatures', 'siw_signature_workcamp' );	

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
	/*
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
	);	*/
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
	register_setting( 'siw_plato', 'siw_plato_outgoing_placements_name' );
	register_setting( 'siw_plato', 'siw_plato_outgoing_placements_email' );
	register_setting( 'siw_plato', 'siw_plato_nr_of_days_before_start_to_hide_project' );		
	register_setting( 'siw_plato', 'siw_plato_organization_web_key' );	

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
		'siw_plato_organization_web_key', 
		__( 'Organization key', 'siw' ), 
		'siw_settings_show_text_field', 
		'siw_plato',
		'siw_plato_webservice', 
		'siw_plato_organization_web_key' 
	);		
}

function siw_settings_tariffs_init(){
	register_setting( 'siw_tariffs', 'siw_tariffs_workcamp_student' );
	register_setting( 'siw_tariffs', 'siw_tariffs_workcamp_regular' );
	register_setting( 'siw_tariffs', 'siw_tariffs_op_maat_student' );
	register_setting( 'siw_tariffs', 'siw_tariffs_op_maat_regular' );
	register_setting( 'siw_tariffs', 'siw_tariffs_evs_deposit' );
	
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
	register_setting( 'siw_evs', 'siw_evs_deadline_1' );
	register_setting( 'siw_evs', 'siw_evs_deadline_2' );
	register_setting( 'siw_evs', 'siw_evs_deadline_3' );
	register_setting( 'siw_evs', 'siw_evs_deadline_4' );
	register_setting( 'siw_evs', 'siw_evs_deadline_5' );
	
	//secties
	add_settings_section(
		'siw_evs_deadlines', 
		__( 'Deadlines', 'siw' ), 
		'siw_settings_evs_deadlines_header', 
		'siw_evs'
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
	register_setting( 'siw_api', 'siw_api_google_analytics_id' );
	register_setting( 'siw_api', 'siw_api_postcode_api_key' );
	register_setting( 'siw_api', 'siw_api_pingdom_rum_id' );
	
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
//functies op secties te tonen

function siw_settings_plato_outgoing_placements_header() { 
	echo __( 'Afzender van de aanmelding bij export naar PLATO', 'siw' );
}
function siw_settings_tariffs_evs_header() { 
	echo __( 'Gebruikt in shortcode [siw_evs_borg]', 'siw' );
}
function siw_settings_tarrifs_workcamp_header() { 
	echo __( 'Gebruikt in PLATO-import en in shortcode [siw_inschrijfgeld_groepsproject tarief="regulier|student"]', 'siw' );
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
	<input type='text' name='<?php echo $option; ?>' value='<?php echo get_option($option); ?>' size="50">
	<?php
}

function siw_settings_show_amount_field( $option ){
	?> &euro;
	<input type='number' name='<?php echo $option; ?>' value='<?php echo get_option($option); ?>' min="1" max="1000">
	<?php
}

function siw_settings_show_email_field( $option ){
	?>
	<input type='email' name='<?php echo $option; ?>' value='<?php echo get_option($option); ?>' size="50">
	<?php
}
function siw_settings_show_number_field( $option ) { 
	?>
	<input type='number' name='<?php echo $option; ?>' value='<?php echo get_option($option); ?>' min="1" max="30"> 
	<?php
}
function siw_settings_show_date_field( $option ) { 
	?>
	<input type='date' name='<?php echo $option; ?>' value='<?php echo get_option($option); ?>'> 
	<?php
}

function siw_settings_page(  ) {?>
    <div class="wrap">  
        <h2>Instellingen SIW</h2>
        <?php settings_errors();
		$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'signatures';
		?>        
		<h2 class="nav-tab-wrapper">
			<!--<a href="?page=siw_settings&tab=general" class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>">Algemeen</a>-->
			<a href="?page=siw_settings&tab=signatures" class="nav-tab <?php echo $active_tab == 'signatures' ? 'nav-tab-active' : ''; ?>">Ondertekening</a>
			<a href="?page=siw_settings&tab=tariffs" class="nav-tab <?php echo $active_tab == 'tariffs' ? 'nav-tab-active' : ''; ?>">Tarieven</a>
			<a href="?page=siw_settings&tab=plato" class="nav-tab <?php echo $active_tab == 'plato' ? 'nav-tab-active' : ''; ?>">PLATO</a>
			<a href="?page=siw_settings&tab=evs" class="nav-tab <?php echo $active_tab == 'evs' ? 'nav-tab-active' : ''; ?>">EVS</a>			
			<a href="?page=siw_settings&tab=api" class="nav-tab <?php echo $active_tab == 'api' ? 'nav-tab-active' : ''; ?>">API keys</a>	
		</h2>        
        <form method="post" action="options.php">
			<?php  
			if( $active_tab == 'general' ) {
				settings_fields( 'siw_general' );
				do_settings_sections( 'siw_general' );
			}
			else if( $active_tab == 'signatures' ) {
				settings_fields( 'siw_signatures' );
				do_settings_sections( 'siw_signatures' );
			}
			else if( $active_tab == 'tariffs' ) {
				settings_fields( 'siw_tariffs' );
				do_settings_sections( 'siw_tariffs' );				
			}
			else if( $active_tab == 'plato' ) {
				settings_fields( 'siw_plato' );
				do_settings_sections( 'siw_plato' );
			}
			else if( $active_tab == 'evs' ) {
				settings_fields( 'siw_evs' );
				do_settings_sections( 'siw_evs' );
			} 
			else if( $active_tab == 'api' ) {
				settings_fields( 'siw_api' );
				do_settings_sections( 'siw_api' );
			} 			
			submit_button();
			?>
		</form>     
    </div><?php
}