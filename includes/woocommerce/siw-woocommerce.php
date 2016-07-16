<?php
/*
(c)2015 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/*
Extra functies voor woocommerce
*/

//Loop: add to cart knop verbergen, datum + projectcode tonen
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');
add_action('woocommerce_after_shop_loop_item_title','siw_wc_show_duration_and_project_code',1);

function siw_wc_show_duration_and_project_code(){
	global $product;
	$duration = get_post_meta( $product->id, 'projectduur', true);
	$project_code = get_post_meta( $product->id, '_sku', true);

	echo '<p>' . esc_html( $duration ) . '</p><hr style="margin:5px;">';	
	echo '<p style="margin-bottom:5px;"><small>' . esc_html( $project_code ) . '</small></p>';

}


//projectpagina: meta-info verbergen (tags, categorie, SKU)
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

//redundante headers in tabs verwijderen
add_filter('woocommerce_product_description_heading', '__return_false');
add_filter('woocommerce_product_additional_information_heading', '__return_false');

//verwijderen review-tab:
remove_action( 'woocommerce_product_tabs', 'woocommerce_product_reviews_tab', 30);

//extra tab toevoegen met contact form 7 formulier
add_filter('woocommerce_product_tabs','siw_wc_enquiry_tab',10,1);
function siw_wc_enquiry_tab( $tabs ){	
	$tabs['enquiry'] = array(
		'title'    => __( 'Stel een vraag', 'woocommerce' ),
		'priority' => 100,
		'callback' => 'siw_wc_product_enquiry_form'
	);	
	return $tabs;
}
function siw_wc_product_enquiry_form(){
	$contact_form_id = siw_get_cf7_form_id('project');
	echo do_shortcode('[contact-form-7 id="' . esc_attr( $contact_form_id ) . '"]');
}

//trailing slash toevoegen bij AJAX-filtering
add_filter('yith_wcan_untrailingslashit', '__return_false');

//AJAX-filtering ook op zoekresultaten-pagina
add_filter( 'yith_wcan_is_search', '__return_false' );

//sorteeropties aanpassen
add_filter( 'woocommerce_get_catalog_ordering_args', 'siw_wc_catalog_ordering' );

function siw_wc_catalog_ordering( $args ) {
	$orderby_value = isset( $_GET['orderby'] ) ? woocommerce_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );
	switch( $orderby_value ) {
		case 'random':
			$sort_args['orderby']  = 'rand';
			$sort_args['order']    = '';
			$sort_args['meta_key'] = '';
			break;
		case 'startdate':
			$sort_args['orderby']  = 'meta_value';
			$sort_args['order']    = 'asc';
			$sort_args['meta_key'] = 'startdatum';
			break;
		case 'country':
			$sort_args['orderby'] = 'meta_value';
			$sort_args['order'] = 'asc';
			$sort_args['meta_key'] = 'land';
			break;		
	}
	return $sort_args;
}

add_filter( 'woocommerce_default_catalog_orderby_options', 'siw_wc_catalog_orderby' );
add_filter( 'woocommerce_catalog_orderby', 'siw_wc_catalog_orderby' );

function siw_wc_catalog_orderby( $sortby ) {
	$sortby['startdate'] = 'Startdatum';
	$sortby['country'] = 'Land';
	$sortby['random'] = 'Willekeurig';
	//unset($sortby["price"]);
	//unset($sortby["date"]);
	//unset($sortby["popularity"]);
	//unset($sortby["price-desc"]);
	
	return $sortby;
}	


//random volgorde toevoegen aan woocommerce shortcodes
add_filter('woocommerce_shortcode_products_query', 'siw_wc_shortcode_add_orderby_random', 10, 2);

function siw_wc_shortcode_add_orderby_random ( $args, $atts ) {
	if ( 'random' == $atts['orderby'] ) {
		$args['orderby']  = 'rand';
		$args['order']    = '';
		$args['meta_key'] = '';
	}
	return $args;
	return $atts;
}



//custom fields verbergen op orderscherm en projectscherm
add_action( 'add_meta_boxes' , 'siw_wc_hide_custom_fields', 999 );
function siw_wc_hide_custom_fields() {
	remove_meta_box( 'postcustom' , 'shop_order' , 'normal' ); 
	remove_meta_box( 'woocommerce-order-downloads', 'shop_order', 'normal');
	remove_meta_box( 'postcustom' , 'product' , 'normal' ); 
	remove_meta_box( 'woocommerce-product-images' , 'product', 'side', 'low' );
	remove_meta_box( 'commentsdiv' , 'product' , 'normal' ); 
}

/*
Functies voor tonen order op adminscherm
*/

//tonen adresgegevens op adminscherm
add_filter('woocommerce_admin_billing_fields', 'siw_wc_admin_address_fields');

function siw_wc_admin_address_fields( $fields ){
 
	$email = $fields['email']; 
	$phone = $fields['phone']; 
	
	//zelfe volgorde + extra velden als bij checkout gebruiken
	$fields = siw_wc_checkout_address_fields($fields);

	//geslacht tonen als select i.p.v. drowdown.
	$fields['gender']['type'] = 'select';
	
	//reassign email and phone fields
	$fields['email'] = $email;
	$fields['phone'] = $phone;

	return $fields;
}

//ordergegevens in metaboxes
add_filter( 'cmb_meta_boxes', 'siw_wc_order_meta_boxes' );

function siw_wc_order_meta_boxes( array $meta_boxes ){

	$languages = siw_get_array('languages');
	$language_skill = siw_get_array('language_skill');
	$gender = siw_get_array('gender');
	$nationalities = siw_get_array('nationalities');
	

	$meta_boxes[] = array(
		'id'         => 'woocommerce_order_meta',
		'title'      => 'Aanmelding',
		'pages'      => array( 'shop_order' ), 
		'context'    => 'normal',
		'priority'   => 'default',
		'show_names' => true, 
		'fields' => array(
			
			array(
				'name'	=> 'Talenkennis',
				'type' 	=> 'title',
				'id'	=> 'language_skill'
			),			
			array(
				'name'		=> 'Taal 1',
				'id'		=> 'language1',
				'type'		=> 'select',
				'options'	=> $languages,
			),		
			array(
				'name'		=> 'Niveau taal 1',
				'id'		=> 'language1Skill',
				'type'		=> 'radio_inline',
				'options'	=> $language_skill,
			),
			array(
				'name'		=> 'Taal 2',
				'id'		=> 'language2',
				'type'		=> 'select',
				'options'	=> $languages,
			),		
			array(
				'name'		=> 'Niveau taal 2',
				'id'		=> 'language2Skill',
				'type'		=> 'radio_inline',
				'options'	=> $language_skill,
			),
			array(
				'name'		=> 'Taal 3',
				'id'		=> 'language3',
				'type'		=> 'select',
				'options'	=> $languages,
			),		
			array(
				'name'		=> 'Niveau taal 3',
				'id'		=> 'language3Skill',
				'type'		=> 'radio_inline',
				'options'	=> $language_skill,
			),				
			array(
				'name'	=> 'Gegevens voor PO',
				'desc'	=> 's.v.p. in het engels invullen',
				'type' 	=> 'title',
				'id'	=> 'informationForPartner'
			),		
			array(
				'name'	=> 'Motivation',
				'id' 	=> 'motivation',
				'type'	=> 'textarea'
			),
			array(
				'name'	=> 'Health issues',
				'id' 	=> 'healthIssues',
				'type'	=> 'textarea'
			),
			array(
				'name'	=> 'Volunteer experience',
				'id' 	=> 'volunteerExperience',
				'type'	=> 'textarea'
			),			
			array(
				'name'	=> 'Together with',
				'id' 	=> 'togetherWith',
				'type'	=> 'text_medium'
			),	
			array(
				'name'	=> 'Gegevens noodcontact',
				'type' 	=> 'title',
				'id'	=> 'emergencyContact'
			),		
			array(
				'name'	=> 'Naam',
				'id' 	=> 'emergencyContactName',
				'type'	=> 'text_medium'
			),
			array(
				'name'	=> 'Telefoonnummer',
				'id' 	=> 'emergencyContactPhone',
				'type'	=> 'text_medium'
			),		
		),
	);

	return $meta_boxes;
}


//projectgegevens in metaboxes
add_filter( 'cmb_meta_boxes', 'siw_wc_project_metaboxes' ,999 );

function siw_wc_project_metaboxes( array $meta_boxes ){

	$visibility_options = array(
		''		=> 'Automatisch',
		'hide'	=> 'Verbergen',
		//'show'	=> 'Tonen',
	);

	$meta_boxes[] = array(
		'id'			=> 'woocommerce_project_meta',
		'title'			=> 'Extra opties',
		'pages'			=> array( 'product' ), 
		'context'		=> 'normal',
		'priority'		=> 'default',
		'show_names'	=> true,
		'fields'		=> array(
			
			array(
				'name'		=> 'Zichtbaarheid',
				'id'		=> 'manual_visibility',
				'type'		=> 'select',
				'options'	=> $visibility_options,
			),
			array(
				'name'		=> 'Opnieuw importeren',
				'id'		=> 'import_again',
				'type'		=> 'checkbox',
			),	
		),
	);
	//verbergen
	$sidebar = array_search('product_post_side_metabox', array_column( $meta_boxes, 'id'));
	$meta_boxes[ $sidebar ]['pages'] = array();
	
	$video = array_search('product_post_metabox', array_column( $meta_boxes, 'id'));
	$meta_boxes[ $video ]['pages'] = array();	

	$tab_1 = array_search('kad_custom_tab_01', array_column( $meta_boxes, 'id'));
	$meta_boxes[ $tab_1 ]['pages'] = array();	

	$tab_2 = array_search('kad_custom_tab_02', array_column( $meta_boxes, 'id'));
	$meta_boxes[ $tab_2 ]['pages'] = array();	

	$tab_3 = array_search('kad_custom_tab_03', array_column( $meta_boxes, 'id'));
	$meta_boxes[ $tab_3 ]['pages'] = array();	
	
	$subtitle_keys = array_keys( array_column($meta_boxes, 'id'), 'subtitle_metabox');
	foreach( $subtitle_keys as $subtitle ){
		$meta_boxes[ $subtitle ]['pages'] = array_diff( $meta_boxes[$subtitle]['pages'], array('product'));
	}
	
	return $meta_boxes;
}	

add_action( 'woocommerce_order_actions', 'siw_add_order_action_export_to_plato' );


function siw_add_order_action_export_to_plato( $actions ) {
	$actions['siw_export_to_plato'] = __( 'Exporteer naar PLATO', 'siw' );
	return $actions;
}

// process the custom order meta box order action
add_action( 'woocommerce_order_action_siw_export_to_plato', 'siw_export_application_to_plato' );

function siw_export_application_to_plato( $order ) {
	siw_wc_export_paid_applications ( $order->id );
}