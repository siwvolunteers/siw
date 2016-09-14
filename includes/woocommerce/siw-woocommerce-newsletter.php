<?php
/*
(c)2016 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'woocommerce_get_settings_checkout', 'siw_woo_settings_newsletter', 10);
function siw_woo_settings_newsletter( $settings ) {

	//Mailpoet-lijsten ophalen
	$model_list = WYSIJA::get('list','model');
	$mailpoet_lists = $model_list->get(array('name','list_id'),array('is_enabled'=>1));

	foreach ( $mailpoet_lists as $list ) {
		$lists[$list['list_id']]=$list['name'];
	}
	
	//sectie voor opties toevoegen
	$settings[] = array(
		'title' => __( 'Aanmelden voor nieuwsbrief tijdens checkout', 'siw' ),
		'type'  => 'title',
		'id'    => 'siw_woo_newsletter_options',
	);
	$settings[] = array(
		'title' => __( 'Lijst', 'siw' ),
		'type'  => 'select',
		'id'    => 'siw_woo_newsletter_list',
		'options' => $lists,
	);
	$settings[] = array(
		'type' => 'sectionend',
		'id' => 'siw_woo_newsletter_options',
	);
	return $settings;
}

/*
Checkbox toevoegen aan checkout
*/
add_action( 'woocommerce_after_checkout_billing_form', 'siw_woo_checkout_newsletter_checkbox' );
function siw_woo_checkout_newsletter_checkbox(){
	$checkout = WC()->checkout(); 
	woocommerce_form_field( 'newsletter_signup', array(
		'type'			=> 'checkbox',
		'class'			=> array('form-row-wide'),
		'clear'			=> true,
		'label'			=> __('Ja, ik wil graag de SIW nieuwsbrief ontvangen', 'siw'),
		), $checkout->get_value( 'newsletter_signup' )
	);
}

/*
Klant toevoegen aan Mailpoetlijst
*/
add_action( 'woocommerce_checkout_order_processed', 'siw_woo_checkout_newsletter_subscribe', 2, 10);
function siw_woo_checkout_newsletter_subscribe( $order_id, $posted_form ){
	$newsletter_signup = isset( $_POST['newsletter_signup'] ) ? 1 : 0;	
	$list = (integer)get_option('siw_woo_newsletter_list');
	if ( 1 == $newsletter_signup ){
		$user_data = array(
			'email'		=> sanitize_text_field($_POST['billing_email']),
			'firstname'	=> sanitize_text_field($_POST['billing_first_name']),
			'lastname'	=> sanitize_email($_POST['billing_last_name']),
		);
		$data_subscriber = array(
			'user'		=> $user_data,
			'user_list'	=> array('list_ids' => array($list))
		);
		$user_id = WYSIJA::get( 'user', 'helper' )->addSubscriber( $data_subscriber, true );
	}
}

