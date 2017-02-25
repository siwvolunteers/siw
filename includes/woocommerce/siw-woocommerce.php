<?php
/*
(c)2015-2016 SIW Internationale Vrijwilligersprojecten
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
//trailing nullen verbergen
add_filter('woocommerce_price_trim_zeros', '__return_true');

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
		'title'    => __( 'Stel een vraag', 'siw' ),
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
	$sortby['startdate'] =  __('Startdatum', 'siw');
	$sortby['country'] = __('Land', 'siw');
	$sortby['random'] = __('Willekeurig', 'siw');
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
add_action( 'admin_menu', 'siw_wc_hide_custom_fields', 999 );
function siw_wc_hide_custom_fields() {
	remove_meta_box( 'postcustom' , 'shop_order' , 'normal' );
	remove_meta_box( 'woocommerce-order-downloads', 'shop_order', 'normal');
	remove_meta_box( 'slugdiv', 'product', 'normal');
	remove_meta_box( 'postcustom' , 'product' , 'normal' );
	remove_meta_box( 'woocommerce-product-images' , 'product', 'side', 'low' );
	remove_meta_box( 'commentsdiv' , 'product' , 'normal' );

	//Diverse metaboxes verbergen voor niet-admins
	if ( !current_user_can('manage_options') ){
		remove_meta_box('woocommerce-product-data' , 'product', 'normal');
		remove_meta_box('postimagediv', 'product', 'side');
		remove_meta_box('tagsdiv-product_tag', 'product', 'normal');
		remove_meta_box('product_catdiv', 'product', 'normal');
	}

}
//editor verbergen bij projecten voor niet-admins
add_action( 'admin_init', 'siw_hide_editor' );
function siw_hide_editor() {

	if (!isset($_GET['post'])){
		return;
	}
	$post_id = $_GET['post'];

	//Alleen uitvoeren bij groepsprojecten
	if ('product' != get_post_type( $post_id) ) {
		return;
	}

	//Verberg editor voor niet-admins
	if ( !current_user_can('manage_options') ){
		remove_post_type_support('product', 'editor');
	}
}

//Projectsamenvating
add_action( 'add_meta_boxes' , 'siw_woo_show_meta_boxes', 999 );
function siw_woo_show_meta_boxes() {

	//metabox met projectbeschrijving tonen voor niet-admins
	if ( !current_user_can('manage_options') ){
		add_meta_box(
			'siw_show_project_description',
			esc_html__( 'Projectbeschrijving', 'siw' ),
			'siw_show_project_description',
			'product',
			'normal',
			'high'
		);

	}
}
//Toon projectbeschrijving
function siw_show_project_description( $object ){
	$content = $object->post_content;
	$content = preg_replace('/\[pane title="(.*?)"\]/', '<h4>$1</h4><p>', $content);
	$content = preg_replace('/\[\/pane\]/', '</p><hr>', $content);
	$content = preg_replace('/\[(.*?)\]/', '', $content);
	echo wp_kses_post( $content );
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
		'title'      => __('Aanmelding', 'siw'),
		'pages'      => array( 'shop_order' ),
		'context'    => 'normal',
		'priority'   => 'default',
		'show_names' => true,
		'fields' => array(

			array(
				'name'		=> __('Talenkennis', 'siw'),
				'type' 		=> 'title',
				'id'		=> 'language_skill'
			),
			array(
				'name'		=> __('Taal 1', 'siw'),
				'id'		=> 'language1',
				'type'		=> 'select',
				'options'	=> $languages,
			),
			array(
				'name'		=> __('Niveau taal 1', 'siw'),
				'id'		=> 'language1Skill',
				'type'		=> 'radio_inline',
				'options'	=> $language_skill,
			),
			array(
				'name'		=> __('Taal 2', 'siw'),
				'id'		=> 'language2',
				'type'		=> 'select',
				'options'	=> $languages,
			),
			array(
				'name'		=> __('Niveau taal 2', 'siw'),
				'id'		=> 'language2Skill',
				'type'		=> 'radio_inline',
				'options'	=> $language_skill,
			),
			array(
				'name'		=> __('Taal 3', 'siw'),
				'id'		=> 'language3',
				'type'		=> 'select',
				'options'	=> $languages,
			),
			array(
				'name'		=> __('Niveau taal 3', 'siw'),
				'id'		=> 'language3Skill',
				'type'		=> 'radio_inline',
				'options'	=> $language_skill,
			),
			array(
				'name'		=> __('Gegevens voor PO', 'siw'),
				'desc'		=> __('s.v.p. in het engels invullen', 'siw'),
				'type' 		=> 'title',
				'id'		=> 'informationForPartner'
			),
			array(
				'name'		=> __('Motivation', 'siw'),
				'id' 		=> 'motivation',
				'type'		=> 'textarea'
			),
			array(
				'name'		=> __('Health issues', 'siw'),
				'id' 		=> 'healthIssues',
				'type'		=> 'textarea'
			),
			array(
				'name'		=> __('Volunteer experience', 'siw'),
				'id' 		=> 'volunteerExperience',
				'type'		=> 'textarea'
			),
			array(
				'name'		=> __('Together with', 'siw'),
				'id' 		=> 'togetherWith',
				'type'		=> 'text_medium'
			),
			array(
				'name'		=> __('Gegevens noodcontact', 'siw'),
				'type' 		=> 'title',
				'id'		=> 'emergencyContact'
			),
			array(
				'name'		=> __('Naam', 'siw'),
				'id' 		=> 'emergencyContactName',
				'type'		=> 'text_medium'
			),
			array(
				'name'		=> __('Telefoonnummer', 'siw'),
				'id' 		=> 'emergencyContactPhone',
				'type'		=> 'text_medium'
			),
		),
	);

	return $meta_boxes;
}


//projectgegevens in metaboxes
add_filter( 'cmb_meta_boxes', 'siw_wc_project_metaboxes' ,999 );

function siw_wc_project_metaboxes( array $meta_boxes ){

	$visibility_options = array(
		''		=> __('Automatisch', 'siw'),
		'hide'	=> __('Verbergen', 'siw'),
		//'show'	=> 'Tonen',
	);

	$meta_boxes[] = array(
		'id'			=> 'woocommerce_project_meta',
		'title'			=> __('Extra opties', 'siw'),
		'pages'			=> array( 'product' ),
		'context'		=> 'normal',
		'priority'		=> 'default',
		'show_names'	=> true,
		'fields'		=> array(

			array(
				'name'		=> __('Zichtbaarheid', 'siw'),
				'id'		=> 'manual_visibility',
				'type'		=> 'select',
				'options'	=> $visibility_options,
			),
			array(
				'name'		=> __('Opnieuw importeren', 'siw'),
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

//actie 'Exporteer naar PLATO' toevoegen aan order-scherm
add_action( 'woocommerce_order_actions', 'siw_add_order_action_export_to_plato' );
function siw_add_order_action_export_to_plato( $actions ) {
	$actions['siw_export_to_plato'] = __( 'Exporteer naar PLATO', 'siw' );
	return $actions;
}

add_action( 'woocommerce_order_action_siw_export_to_plato', 'siw_export_application_to_plato' );
function siw_export_application_to_plato( $order ) {
	siw_wc_export_application_to_plato( $order->id );
}


//admin columns verbergen
add_filter('manage_edit-product_columns', 'siw_product_remove_admin_columns', 10);
function siw_product_remove_admin_columns( $columns ){
	unset( $columns['product_type']);
	//Yoast
	unset( $columns['wpseo-title']);
	unset( $columns['wpseo-metadesc']);
	unset( $columns['wpseo-focuskw']);
	return $columns;
}


//admin columns verbergen
add_filter('manage_edit-shop_order_columns', 'siw_shop_order_remove_admin_columns', 10);
function siw_shop_order_remove_admin_columns( $columns ){
	unset( $columns['shipping_address']);
	unset( $columns['customers_message']);
	unset( $columns['order_note']);
	return $columns;
}

//admin column voor export naar PLATO
add_filter('manage_edit-shop_order_columns', 'siw_shop_order_admin_export_column_header', 10);

function siw_shop_order_admin_export_column_header( $columns ) {

	$new_columns = array();
	foreach ( $columns as $column_name => $column_info ) {
		$new_columns[ $column_name ] = $column_info;
		if ( 'order_status' == $column_name ){
			$new_columns['exported'] = __( 'Export naar PLATO', 'siw' );
		}
	}
	return $new_columns;
}

add_action('manage_shop_order_posts_custom_column', 'siw_shop_order_admin_export_column_value', 10, 2);
function siw_shop_order_admin_export_column_value( $column_name, $post_id ) {
	if ( 'exported' == $column_name ) {
		$exported = get_post_meta( $post_id, '_exported_to_plato', true );

		//export via xml export suite
		$exported_via_xml_suite = get_post_meta( $post_id, '_wc_customer_order_xml_export_suite_is_exported', true );

		if ('success' == $exported or 1 == $exported_via_xml_suite ) {
			$dashicon = 'yes';
		}
		else if ('failed' == $exported ){
			$dashicon = 'no';
		}
		else{
			$dashicon = 'minus';
		}
		echo sprintf('<span class="dashicons dashicons-%s"></span>', $dashicon );
	}
}


/*
Checkbox in projectscherm om project af te keuren
*/
add_action('post_submitbox_start', 'siw_show_reject_project_checkbox');
function siw_show_reject_project_checkbox(){
	$post_id = get_the_ID();

	//Alleen tonen bij groepsprojecten
	if ( 'product' != get_post_type( $post_id) ) {
		return;
	}

	//Alleen tonen als project ter review staat.
	if ( 'draft' != get_post_status ( $post_id ) ){
		return;
	}

	wp_nonce_field('reject_project_nonce_'.$post_id, 'reject_project_nonce');
	?>
	<div class="hide-rejected-project">
		<label><input type="checkbox" value="1" name="reject_project" /><?php _e('Project afkeuren en direct verbergen', 'siw'); ?></label>
	</div>
	<?php
}

/*
Verbergen van afgekeurde projecten
*/
add_action( 'publish_product', 'siw_hide_rejected_project', 10, 2 );
function siw_hide_rejected_project( $post_id, $post ){

	//TODO: is deze check nodig?
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
		return;
	}
	//Nonce check
	if ( !isset($_POST['reject_project_nonce']) || !wp_verify_nonce($_POST['reject_project_nonce'], 'reject_project_nonce_'.$post_id)){
		return;
	}
	//Check op capability
	if (!current_user_can('edit_post', $post_id)) {
		return;
	}

	//Afgekeurd project direct verbergen //TODO: meta zetten
	if ( isset( $_POST['reject_project']) and 1 == $_POST['reject_project'] ) {
		siw_hide_workcamp( $post_id );
	}
}
