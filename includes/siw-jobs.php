<?php
/*
(c)2015 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_action( 'init', 'siw_cpt_vacatures', 0 );
function siw_cpt_vacatures() {
	$labels = array(
		'name'                => _x( 'Vacatures', 'Post Type General Name', 'siw' ),
		'singular_name'       => _x( 'Vacature', 'Post Type Singular Name', 'siw' ),
		'menu_name'           => __( 'Vacatures', 'siw' ),
		'name_admin_bar'      => __( 'Vacatures', 'siw' ),
		'parent_item_colon'   => __( 'Parent Item:', 'siw' ),
		'all_items'           => __( 'Alle vacatures', 'siw' ),
		'add_new_item'        => __( 'Vacature toevoegen', 'siw' ),
		'add_new'             => __( 'Toevoegen', 'siw' ),
		'new_item'            => __( 'Nieuwe vacature', 'siw' ),
		'edit_item'           => __( 'Bewerk vacature', 'siw' ),
		'update_item'         => __( 'Update vacature', 'siw' ),
		'view_item'           => __( 'Bekijk vacature', 'siw' ),
		'search_items'        => __( 'Zoek vacature', 'siw' ),
		'not_found'           => __( 'Niet gevonden', 'siw' ),
		'not_found_in_trash'  => __( 'Niet gevonden in de prullenbak', 'siw' ),
	);
	$args = array(
		'label'               => __( 'Vacature', 'siw' ),
		'description'         => __( 'Vactures', 'siw' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'excerpt', 'revisions', ),
		'taxonomies'          => array( 'job_type' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-nametag',
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'has_archive'         => false,		
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'post',
	);
	register_post_type( 'vacatures', $args );

}


//soort vacature
add_action( 'init', 'siw_taxonomy_job_type', 0 );
function siw_taxonomy_job_type() {

	$labels = array(
		'name'                       => _x( 'Soort vacature', 'Taxonomy General Name', 'siw' ),
		'singular_name'              => _x( 'Taxonomy', 'Taxonomy Singular Name', 'siw' ),
		'menu_name'                  => __( 'Soort vacature', 'siw' ),
		'all_items'                  => __( 'All Items', 'siw' ),
		'parent_item'                => __( 'Parent Item', 'siw' ),
		'parent_item_colon'          => __( 'Parent Item:', 'siw' ),
		'new_item_name'              => __( 'New Item Name', 'siw' ),
		'add_new_item'               => __( 'Add New Item', 'siw' ),
		'edit_item'                  => __( 'Edit Item', 'siw' ),
		'update_item'                => __( 'Update Item', 'siw' ),
		'view_item'                  => __( 'View Item', 'siw' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'siw' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'siw' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'siw' ),
		'popular_items'              => __( 'Popular Items', 'siw' ),
		'search_items'               => __( 'Search Items', 'siw' ),
		'not_found'                  => __( 'Not Found', 'siw' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
        'query_var' => true,
	);
	register_taxonomy( 'soort_vacature', array( 'vacatures' ), $args );
}


//kolom in admin menu
add_filter('manage_vacatures_posts_columns', 'siw_vacature_admin_deadline_column_header', 10);

function siw_vacature_admin_deadline_column_header($columns) {
    $columns['deadline'] = 'Deadline';
    return $columns;
}

add_action('manage_vacatures_posts_custom_column', 'siw_vacature_admin_deadline_column_value', 10, 2);
function siw_vacature_admin_deadline_column_value($column_name, $post_id) {
    if ($column_name == 'deadline') {
        $deadline = get_post_meta( $post_id, 'siw_vacature_deadline', true );
        if ($deadline) {
            echo date('j F Y', $deadline);;
        }
    }
}

/*sorteren op deadline*/
add_filter( 'manage_edit-vacatures_sortable_columns', 'siw_vacature_deadline_column_sorting' );
function siw_vacature_deadline_column_sorting( $columns ) {
  $columns['deadline'] = 'deadline';
  return $columns;
}

add_filter( 'request', 'siw_vacature_deadline_column_orderby' );
function siw_vacature_deadline_column_orderby( $vars ) {
    if ( isset( $vars['orderby'] ) && 'deadline' == $vars['orderby'] ) {
        $vars = array_merge( $vars, array(
            'meta_key' => 'siw_vacature_deadline',
            'orderby' => 'meta_value'
        ) );
    }
    return $vars;
}



//vacaturegegevens in metaboxes
add_filter( 'cmb_meta_boxes', 'siw_jobs_metaboxes' );

function siw_jobs_metaboxes( array $meta_boxes ){
	$prefix = 'siw_vacature_';
	$meta_boxes[] = array(
		'id'         => 'vacature_meta',
		'title'      => 'Vacature',
		'pages'      => array( 'vacatures' ), 
		'context'    => 'normal',
		'priority'   => 'default',
		'show_names' => true,
		'fields' => array(
			array(
				'name'    => 'Beschrijving',
				'id'      => $prefix . 'beschrijving',
				'type'    => 'title',
			),		
			array(
				'name'    => 'Wie ben jij? *',
				'id'      => $prefix . 'wie_ben_jij',
				'type'    => 'wysiwyg',
				'options' => array(
					'wpautop' => true, 
					'media_buttons' => false, 
					'teeny' => true, 
				),
			    'attributes'  => array(
					'required'    => 'required',
				),
			),
			array(
				'name'    => 'Wat ga je doen? *',
				'id'      => $prefix . 'wat_ga_je_doen',
				'type'    => 'wysiwyg',
				'options' => array(
					'wpautop' => true,
					'media_buttons' => false,
					'teeny' => true, 
				),
			    'attributes'  => array(
					'required'    => 'required',
				),
			),
			array(
				'name'    => 'Wat bieden wij jou? *',
				'id'      => $prefix . 'wat_bieden_wij_jou',
				'type'    => 'wysiwyg',
				'options' => array(
					'wpautop' => true,
					'media_buttons' => false,
					'teeny' => true,
				),
			    'attributes'  => array(
					'required'    => 'required',
				),
			),
			array(
				'name'    => 'Contactpersoon?',
				'id'      => $prefix . 'contactpersoon',
				'type'    => 'title',
			),
			array(
				'name'    => 'Naam *',
				'id'      => $prefix . 'contactpersoon_naam',
				'type'    => 'text_medium',
			    'attributes'  => array(
					'required'    => 'required',
				),
			),
			array(
				'name'    => 'Functie',
				'id'      => $prefix . 'contactpersoon_functie',
				'type'    => 'text_medium',
			),
			array(
				'name'    => 'E-mail *',
				'id'      => $prefix . 'contactpersoon_email',
				'type'    => 'text_email',
			    'attributes'  => array(
					'required'    => 'required',
				),
			),
			array(
				'name'    => 'Solliciteren?',
				'id'      => $prefix . 'solliciteren',
				'type'    => 'title',
			),
			array(
				'name'    => 'Deadline *',
				'id'      => $prefix . 'deadline',
				'type' => 'text_date_timestamp',
				'date_format' => 'Y-m-d',
			    'attributes'  => array(
					'required'    => 'required',
				),
			),
			array(
				'name'    => 'Gesprekken',
				'id'      => $prefix . 'gesprekken',
				'type'    => 'text',
				'attributes'  => array(
					'placeholder' => 'Op 30 of 31 februari 2015 vinden in de avond de gesprekken plaats.',
				),
			),
			array(
				'name'    => 'Naam *',
				'id'      => $prefix . 'solliciteren_naam',
				'type'    => 'text_medium',
			    'attributes'  => array(
					'required'    => 'required',
				),
			),
			array(
				'name'    => 'Functie',
				'id'      => $prefix . 'solliciteren_functie',
				'type'    => 'text_medium',
			),
			array(
				'name'    => 'E-mail *',
				'id'      => $prefix . 'solliciteren_email',
				'type'    => 'text_email',
			    'attributes'  => array(
					'required'    => 'required',
				),
			),		
		),
	);

	//instellingen vacature pagina
	$meta_boxes[] = array(
		'id'         => 'siw_vacatures_metabox',
		'title'      => __('Vacatures grid opties', 'siw'),
		'pages'      => array( 'page' ), 
		'show_on' => array('key' => 'page-template', 'value' => array('template-vacatures-grid.php')),
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true,
		'fields' => array(
			array(
				'name'    => __('Kies het aantal kolommen:', 'siw'),
				'desc'    => '',
				'id'      => $prefix . 'columns',
				'type'    => 'select',
				'options' => array(
					array( 'name' => __('Vier kolommen', 'siw'), 'value' => '4', ),
					array( 'name' => __('Drie kolommen', 'siw'), 'value' => '3', ),
					array( 'name' => __('Twee kolommen', 'siw'), 'value' => '2', ),
				),
			),
		));
	
	return $meta_boxes;
}	