<?php
/*
(c)2015 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/*
Extra functies voor woocommerce checkout
*/



/*
Checkout scripts toevoegen
*/
add_action('wp_enqueue_scripts', 'siw_wc_checkout_scripts');
function siw_wc_checkout_scripts(){
	if (is_checkout()){
		wp_enqueue_script( 'siw-wc-checkout-scripts' );
	}
}

//verwijderen verzendadres
add_filter( 'woocommerce_shipping_fields', 'siw_wc_remove_shipping_fields' );
function siw_wc_remove_shipping_fields($fields){
    unset( $fields["shipping_country"] );
    unset( $fields["shipping_address_1"] );
    unset( $fields["shipping_city"] );
    unset( $fields["shipping_state"] );
    unset( $fields["shipping_postcode"] );
    return $fields;
}


//verbergen opmerkingen
add_filter('woocommerce_enable_order_notes_field', '__return_false');


//voorkomen dat woocommerce het postcodeveld steeds verplaatst.
add_filter('woocommerce_get_country_locale', 'siw_wc_override_locale_nl', 99);
function siw_wc_override_locale_nl($fields){
	$fields['NL']['postcode_before_city'] = false;
	return $fields;
}

//volgorde velden aanpassen
add_filter('woocommerce_default_address_fields','siw_wc_checkout_address_fields');
function siw_wc_checkout_address_fields($fields){
	//standaardlijsten ophalen
	$gender = siw_get_array('gender');
	$nationalities = siw_get_array('nationalities');
 
	//toevoegen geslacht
	$address_fields['gender'] = array(
		'label'     => __('Geslacht', 'woocommerce'),
		'required'  => true,
		'class'     => array('form-row-wide'),
		'clear'     => true,
		'type'  	=> 'radio',
		'options'   => $gender,
    ); 
	$address_fields['first_name'] = $fields['first_name'];
	$address_fields['last_name'] = $fields['last_name'];	
	/*toevoegen geboortedatum	*/
	$address_fields['dob'] = array(
		'label'     	=> __('Geboortedatum', 'woocommerce'),
		'required'  	=> true,
		'class'     	=> array('form-row-first'),
		'placeholder'   => __('dd-mm-jjjj'),
		'type'  		=> 'text',
    );

	//toevoegen nationaliteit
	$address_fields['nationality'] = array(
		'label'     => __('Nationaliteit', 'woocommerce'),
		'required'  => true,
		'class'     => array('form-row-last'),
		'clear'     => true,
		'type'  	=> 'select',
		'options'   => $nationalities,
		'default'	=> 'HOL'
    );
	$address_fields['postcode'] = $fields['postcode'];	
	$address_fields['housenumber'] = array(
		'label'		=> 'Huisnummer',
		'required'  => true,
		'class'     => array('form-row-last'),
		'clear'     => true,
		'type'		=> 'text',
	);
	$address_fields['address_1'] = $fields['address_1'];
	$address_fields['city'] = $fields['city'];
	$address_fields['country'] = $fields['country'];
	
	//pas eigenschappen van standaardvelden aan
	$address_fields['postcode']['class'] = array('form-row-first');
	$address_fields['postcode']['placeholder'] = '1234 AB';
	$address_fields['postcode']['clear'] = false;
	$address_fields['address_1']['class'] = array('form-row-first');
	$address_fields['address_1']['label'] = 'Straat';
	$address_fields['address_1']['placeholder'] = '';
	$address_fields['city']['class'] = array('form-row-last');	
	return $address_fields;
}

//mailpoet opt-in verplaatsen
remove_action( 'woocommerce_after_order_notes', 'on_checkout_page' );
add_action( 'woocommerce_after_checkout_billing_form', 'on_checkout_page' );



//informatie voor PO
add_action( 'woocommerce_checkout_after_customer_details' ,'siw_wc_checkout_information_for_po' );

function siw_wc_checkout_information_for_po(){ 

    $checkout = WC()->checkout(); 
	
	//informatie voor PO
	echo '<div id="infoForPartner"><h3>' . __('Informatie voor partnerorganisatie') . '</h3>';
  	woocommerce_form_field( 'motivation', array(
        'type'          => 'textarea',
		'class'		=> array('form-row-first'),
		'required'		=> true,
		'label'         => __('Motivation'),
		'placeholder'   => __('Vul hier (in het Engels) in waarom je graag aan je gekozen project wil deelnemen.'),
        ), $checkout->get_value( 'motivation' )
	);
	
	woocommerce_form_field( 'healthIssues', array(
        'type'          => 'textarea',
		'class'		=> array('form-row-last'),
		'required'		=> false,
		'label'         => __('Allergies/diet/health issues'),
		'placeholder'   => __('Heb je een allergie, gebruik je medicijnen of volg je een di�et, vul dat dan hier in (in het Engels).'),
        ), $checkout->get_value( 'healthIssues' )
	);
	
	woocommerce_form_field( 'volunteerExperience', array(
        'type'          => 'textarea',
		'class'		=> array('form-row-first'),
		'required'		=> false,
		'label'         => __('Volunteer experience'),
		'placeholder'   => __('Heb je eerder vrijwilligerswerk gedaan? Beschrijf dat dan hier (in het Engels).'),
        ), $checkout->get_value( 'volunteerExperience' )
	);
	
	woocommerce_form_field( 'togetherWith', array(
        'type'          => 'text',
		'class'		=> array('form-row-last'),
		'required'		=> false,
		'clear'     => true,  
		'label'         => __('Together with'),
		'placeholder'   => __('Wil je graag met iemand aan een project deelnemen. Vul zijn of haar naam dan hier in.'),
        ), $checkout->get_value( 'togetherWith' )
	);
    echo '</div>';
	
}	

add_action( 'woocommerce_before_order_notes', 'siw_wc_checkout_extra_information' );
 
function siw_wc_checkout_extra_information( $checkout ) {
 
	//lijsten van talen en niveau
	$languages = siw_get_array('languages');
	$language_skill = siw_get_array('language_skill');
	
    echo '<div id="languageSkills"><h3>' . __('Talenkennis') . '</h3>';
 
	woocommerce_form_field('language1', array(
		    'type'  => 'select',
			'class'     => array('form-row-first'),
			'label'     => __('Taal 1', 'woocommerce'),
			'required'  => true,
			'clear'     => true,  
			'options'   => $languages
		), $checkout->get_value( 'language1' )
	);
 
	woocommerce_form_field('language1Skill', array(
		    'type'  => 'radio',
			'class'     => array('form-row-wide'),
			'label'     => __('Niveau taal 1', 'woocommerce'),
			'required'  => true,
			'clear'     => true,  
			'options'   => $language_skill
		), $checkout->get_value( 'language1Skill')
	);

	woocommerce_form_field('language2', array(
		    'type'  => 'select',
			'class'     => array('form-row-first'),
			'label'     => __('Taal 2', 'woocommerce'),
			'required'  => false,
			'clear'     => true,  
			'options'   => $languages
		), $checkout->get_value( 'language2' )
	);
 
	woocommerce_form_field('language2Skill', array(
		    'type'  => 'radio',
			'class'     => array('form-row-wide'),
			'label'     => __('Niveau taal 2', 'woocommerce'),
			'required'  => false,
			'clear'     => true,  
			'options'   => $language_skill
		), $checkout->get_value( 'language2Skill' )
	);		

	woocommerce_form_field('language3', array(
		    'type'  => 'select',
			'class'     => array('form-row-first'),
			'label'     => __('Taal 3', 'woocommerce'),
			'required'  => false,
			'clear'     => true,  
			'blank' => __('Click here to select', 'dot'),
			'options'   => $languages,
		), $checkout->get_value( 'language3' )
	);
 
	woocommerce_form_field('language3Skill', array(
		    'type'  => 'radio',
			'class'     => array('form-row-wide'),
			'label'     => __('Niveau taal 3', 'woocommerce'),
			'required'  => false,
			'clear'     => true,  
			'options'   => $language_skill
		), $checkout->get_value( 'language3Skill' )
	);		
		
		
    echo '</div>';
 	
	// gegevens noodcontact
	echo '<div id="emergencyContact"><h3>' . __('Noodcontact') . '</h3>';
	woocommerce_form_field( 'emergencyContactName', array(
        'type'          => 'text',
		'class'		=> array('form-row-first'),
		'required'		=> true,
		'label'         => __('Naam'),
        ), $checkout->get_value( 'emergencyContactName' )
	);
   
	woocommerce_form_field( 'emergencyContactPhone', array(
        'type'          => 'text',
		'class'		=> array('form-row-last'),
		'required'		=> true,
		'label'         => __('Telefoonnummer'),
		'clear'         => true      
        ), $checkout->get_value( 'emergencyContactPhone' )
	);
  
    echo '</div>';	
}

//controleren extra velden
add_action('woocommerce_checkout_process', 'siw_wc_checkout_validate_checkout_fields');

function siw_wc_checkout_validate_checkout_fields() {
	// controleer of geboortedatum het juiste formaat heeft en of de deelnemer minimaal 14 jaar is.
    if ($_POST['billing_dob']) {
        $date = explode("-", $_POST['billing_dob']);
		if(!checkdate($date[1], $date[0], $date[2]) or $date[2]<1900 )
			wc_add_notice( __('<strong>Geboortedatum</strong> is ongeldig.'),'error');
		else {
			$date14YearsAdded = strtotime(date("Y-m-d", strtotime($date[2].'-'.$date[1].'-'.$date[0])) . " +14 year");
			if(date("Y-m-d", $date14YearsAdded)>date("Y-m-d"))
				wc_add_notice( __('<strong>Geboortedatum</strong> de minimumleeftijd voor deelname is 14 jaar.'),'error' );
		}
	}
	
	//controleer of de taal 1 en het niveau van taal 1 geselecteerd zijn.
	if (!$_POST['language1']) {
		wc_add_notice(__('<strong>Taal 1</strong> is niet geselecteerd.'), 'error');
	}
	if (!$_POST['language1Skill']) {
		wc_add_notice(__('<strong>Niveau taal 1</strong> is niet geselecteerd.'), 'error');
	}
	
	//controleer of het niveau van taal 2 gekozen is als er een tweede taal geselecteerd is
	if ($_POST['language2'] and !$_POST['language2Skill']) {
		wc_add_notice(__('<strong>Niveau taal 2</strong> is niet geselecteerd.'), 'error');	
	}

	//controleer of het niveau van taal 3 gekozen is als er een derde taal geselecteerd is
	if ($_POST['language3'] and !$_POST['language3Skill']) {
		wc_add_notice(__('<strong>Niveau taal 3</strong> is niet geselecteerd.'), 'error');	
	}	
	
	//controleer of gegevens noodcontact gevuld zijn
	if (!$_POST['emergencyContactName'] or !$_POST['emergencyContactPhone']) {
		wc_add_notice(__('<strong>Gegevens noodcontact</strong> zijn niet ingevuld.'), 'error');
	}
	
	//controleer of de motivatie gevuld is
	if (!$_POST['motivation']) {
		wc_add_notice(__('<strong>Motivation</strong> is niet ingevuld.'), 'error');
	}
}




//opslaan extra velden
add_action('woocommerce_checkout_update_order_meta', 'siw_wc_checkout_save_checkout_fields');

function siw_wc_checkout_save_checkout_fields( $order_id ) {
	
	//talenkennis
	if (!empty($_POST['language1'])) {
		update_post_meta($order_id, 'language1', esc_attr($_POST['language1']));
		update_post_meta($order_id, 'language1Skill', esc_attr($_POST['language1Skill']));
	}
	if (!empty($_POST['language2'])) {
		update_post_meta($order_id, 'language2', esc_attr($_POST['language2']));
		update_post_meta($order_id, 'language2Skill', esc_attr($_POST['language2Skill']));
	}	
	if (!empty($_POST['language3'])) {
		update_post_meta($order_id, 'language3', esc_attr($_POST['language3']));
		update_post_meta($order_id, 'language3Skill', esc_attr($_POST['language3Skill']));
	}	
	
	//noodcontact
	if (!empty($_POST['emergencyContactName'])) {
		update_post_meta($order_id, 'emergencyContactName', sanitize_text_field($_POST['emergencyContactName']));
	}
	if (!empty($_POST['emergencyContactPhone'])) {
		update_post_meta($order_id, 'emergencyContactPhone', sanitize_text_field($_POST['emergencyContactPhone']));
	}	
	
	//informatie voor PO
	if(!empty($_POST['motivation'])) {
		update_post_meta($order_id, 'motivation', sanitize_text_field($_POST['motivation']));
	}	
	if(!empty($_POST['healthIssues'])) {
		update_post_meta($order_id, 'healthIssues', sanitize_text_field($_POST['healthIssues']));
	}	
	if(!empty($_POST['volunteerExperience'])) {
		update_post_meta($order_id, 'volunteerExperience', sanitize_text_field($_POST['volunteerExperience']));
	}		
	if(!empty($_POST['togetherWith'])) {
		update_post_meta($order_id, 'togetherWith', sanitize_text_field($_POST['togetherWith']));
	}	
}	

/*
Multi-step checkout
*/
remove_filter('woocommerce_locate_template', 'wcmultichecout_woocommerce_locate_template', 1, 3);

add_action('wp_enqueue_scripts', 'siw_woocommerce_multistep_checkout_scripts');
function siw_woocommerce_multistep_checkout_scripts(){
	global $wp_scripts;
	if ( is_checkout() && $wp_scripts->registered['wmc-wizard']){
		$wp_scripts->registered['wmc-wizard']->src = get_stylesheet_directory_uri() . '/assets/js/woocommerce-multistep-checkout/wizard.js';
	}
	if ($wp_scripts->registered['jquery-validate']){
		$wp_scripts->registered['jquery-validate']->src = get_stylesheet_directory_uri() . '/assets/js/woocommerce-multistep-checkout/jquery.validate.js';
	}
}

/*Voorwaarden link vervangen door modal  */
add_filter('woocommerce_checkout_show_terms', '__return_false');
add_action('woocommerce_review_order_after_submit', 'siw_woocommerce_show_terms_link');

function siw_woocommerce_show_terms_link(){
$terms_page = get_post( woocommerce_get_page_id('terms') );
?>
<p class="form-row terms">
	<label for="terms" class="checkbox"><?php printf( __( 'Ik heb de <a id="open-terms-and-conditions" data-toggle="modal" data-target="#kt-modal-terms" >inschrijfvoorwaarden</a> gelezen en ga akkoord ', 'siw' ), esc_url( wc_get_page_permalink( 'terms' ) ) ); ?></label>
	<input type="checkbox" class="input-checkbox" name="terms" <?php checked( apply_filters( 'woocommerce_terms_is_checked_default', isset( $_POST['terms'] ) ), true ); ?> id="terms" />
</p>
<div class="hide-button">
<?php echo do_shortcode( '[kad_modal btnsize="small" id = "terms" btntitle="voorwaarden" btncolor="#ffffff" title="' . $terms_page->post_title . '"]' . apply_filters('the_content', $terms_page->post_content) . '[/kad_modal]' );?>
</div>
<?php
}




/**
Opslaan dat gebruiker akkoord is gegaan met voorwaarden

add_action('woocommerce_checkout_update_order_meta', 'woo_save_terms_and_conditions_status');
function woo_save_terms_and_conditions_status( $order_id ) {
    if ($_POST['terms']) update_post_meta( $order_id, '_terms', esc_attr($_POST['terms']));
}
add_action( 'woocommerce_admin_order_data_after_billing_address', 'woo_display_terms_and_conditions_status', 10, 1 );
 
function woo_display_terms_and_conditions_status($order){
	$terms = get_post_meta( $order->id, '_terms', true );
	$terms_status = ( $terms == 'on' ? __('accepted') : __('undefined') );
    echo '<p><strong>'.__('Terms & conditions').':</strong> ' . $terms_status . '</p>';
}
 **/