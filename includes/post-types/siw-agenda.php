<?php
/*
(c)2015-2016 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


function siw_cpt_agenda() {

	$labels = array(
		'name'					=> _x( 'Agenda', 'Post Type General Name', 'siw' ),
		'singular_name'			=> _x( 'Agenda', 'Post Type Singular Name', 'siw' ),
		'menu_name'				=> __( 'Agenda', 'siw' ),
		'name_admin_bar'		=> __( 'Agenda', 'siw' ),
		'parent_item_colon'		=> __( 'Parent Item:', 'siw' ),
		'all_items'				=> __( 'Alle evenementen', 'siw' ),
		'add_new_item'			=> __( 'Evenement toevoegen', 'siw' ),
		'add_new'				=> __( 'Toevoegen', 'siw' ),
		'new_item'				=> __( 'Nieuw evenement', 'siw' ),
		'edit_item'				=> __( 'Bewerk evenement', 'siw' ),
		'update_item'			=> __( 'Update evenement', 'siw' ),
		'view_item'				=> __( 'Bekijk evenement', 'siw' ),
		'search_items'			=> __( 'Zoek evenement', 'siw' ),
		'not_found'				=> __( 'Niet gevonden', 'siw' ),
		'not_found_in_trash'	=> __( 'Niet gevonden in de prullenbak', 'siw' ),
	);
	$args = array(
		'label'					=> __( 'Evenement', 'siw' ),
		'description'			=> __( 'Evenement', 'siw' ),
		'labels'				=> $labels,
		'supports'				=> array( 'title', 'excerpt', 'thumbnail', 'revisions', ),
		'taxonomies'			=> array( 'agenda_type' ),
		'hierarchical'			=> false,
		'public'				=> true,
		'show_ui'				=> true,
		'show_in_menu'			=> true,
		'menu_position'			=> 5,
		'menu_icon'				=> 'dashicons-calendar-alt',
		'show_in_admin_bar'		=> true,
		'show_in_nav_menus'		=> true,
		'can_export'			=> true,
		'has_archive'			=> false,
		'exclude_from_search'	=> false,
		'publicly_queryable'	=> true,
		'capability_type'		=> 'event',
		'map_meta_cap'			=> true,
	);
	register_post_type( 'agenda', $args );

}
add_action( 'init', 'siw_cpt_agenda', 0 );

// Register Custom Taxonomy
function siw_taxonomy_agenda_type() {

	$labels = array(
		'name'							=> _x( 'Soort evenement', 'Taxonomy General Name', 'siw' ),
		'singular_name'					=> _x( 'Taxonomy', 'Taxonomy Singular Name', 'siw' ),
		'menu_name'						=> __( 'Soort evement', 'siw' ),
		'all_items'						=> __( 'All Items', 'siw' ),
		'parent_item'					=> __( 'Parent Item', 'siw' ),
		'parent_item_colon'				=> __( 'Parent Item:', 'siw' ),
		'new_item_name'					=> __( 'New Item Name', 'siw' ),
		'add_new_item'					=> __( 'Add New Item', 'siw' ),
		'edit_item'						=> __( 'Edit Item', 'siw' ),
		'update_item'					=> __( 'Update Item', 'siw' ),
		'view_item'						=> __( 'View Item', 'siw' ),
		'separate_items_with_commas'	=> __( 'Separate items with commas', 'siw' ),
		'add_or_remove_items'			=> __( 'Add or remove items', 'siw' ),
		'choose_from_most_used'			=> __( 'Choose from the most used', 'siw' ),
		'popular_items'					=> __( 'Popular Items', 'siw' ),
		'search_items'					=> __( 'Search Items', 'siw' ),
		'not_found'						=> __( 'Not Found', 'siw' ),
	);
	$args = array(
		'labels'						=> $labels,
		'hierarchical'					=> true,
		'public'						=> true,
		'show_ui'						=> true,
		'show_admin_column'				=> true,
		'show_in_nav_menus'				=> true,
		'query_var'						=> true,
		'capabilities' => array(
			'assign_terms' => 'edit_events'
		),
	);
	register_taxonomy( 'soort_evenement', array( 'agenda' ), $args );

}
add_action( 'init', 'siw_taxonomy_agenda_type', 0 );




//agendagegevens in metaboxes
add_filter( 'cmb_meta_boxes', 'siw_agenda_metaboxes' );

function siw_agenda_metaboxes( array $meta_boxes ){
	$prefix = 'siw_agenda_';
	$meta_boxes[] = array(
		'id'			=> 'agenda_meta',
		'title'			=> 'Agenda',
		'pages'			=> array( 'agenda' ), 
		'context'		=> 'normal',
		'priority'		=> 'default',
		'show_names'	=> true,
		'fields'		=> array(
			array(
				'name'			=> 'Beschrijving? *',
				'id'			=> $prefix . 'beschrijving',
				'type'			=> 'wysiwyg',
				'options'		=> array(
					'wpautop'		=> true, 
					'media_buttons'	=> false, 
					'teeny'			=> true, 
				),
			    'attributes'	=> array(
					'required'		=> 'required',
				),
			),		
			array(
				'name'			=> 'Tijden',
				'type'			=> 'title',
				'id'			=> $prefix . 'tijden_title'
			),
			array(
				'name'			=> 'Start *',
				'id'			=> $prefix . 'start',
				'type'			=> 'text_datetime_timestamp',
				'date_format'	=> 'Y-m-d',
				'time_format'	=> 'H:i',
			    'attributes'	=> array(
					'required'		=> 'required',
				),
			),
			array(
				'name'			=> 'Eind *',
				'id'			=> $prefix . 'eind',
				'type'			=> 'text_datetime_timestamp',
				'date_format'	=> 'Y-m-d',
				'time_format'	=> 'H:i',
			    'attributes'	=> array(
					'required'		=> 'required',
				),
			),
			array(
				'name'			=> 'Locatie',
				'type'			=> 'title',
				'id'			=> $prefix . 'locatie_title'
			),
			array(
				'name'			=> 'Locatie *',
				'id'			=> $prefix . 'locatie',
				'type'			=> 'text_medium',
				'attributes'	=> array(
					'required'		=> 'required',
				),
			),			
			array(
				'name'			=> 'Adres *',
				'id'			=> $prefix . 'adres',
				'type'			=> 'text_medium',
			    'attributes'	=> array(
					'required'		=> 'required',
				),
			),
			array(
				'name'			=> 'Postcode *',
				'id'			=> $prefix . 'postcode',
				'type'			=> 'text_medium',
			    'attributes'	=> array(
					'required'		=> 'required',
				),
			),
			array(
				'name'			=> 'Plaats *',
				'id'			=> $prefix . 'plaats',
				'type'			=> 'text_medium',
			    'attributes'	=> array(
					'required'		=> 'required',
				),
			),
			array(
				'name'			=> 'Aanmelden',
				'type'			=> 'title',
				'id'			=> $prefix . 'aanmelden_title'
			),
			array(
				'name'			=> 'Aanmelden via: *',
				'desc'			=> 'Eventuele extra velden verschijnen na opslaan',
				'id'			=> $prefix . 'aanmelden',
				'type'			=> 'radio_inline',
				'options'		=> array(
					'formulier'		=> __( 'Aanmeldformulier Community day', 'siw' ),
					'aangepast'		=> __( 'Aangepaste tekst en link', 'siw' ),

				),
			    'attributes'	=> array(
					'required'		=> 'required',
				),
			),
			array(
				'name'			=> 'Toelichting aanmelden *',
				'id'			=> $prefix . 'aanmelden_toelichting',
				'type'			=> 'wysiwyg',
				'options'		=> array(
					'wpautop'		=> true, 
					'media_buttons'	=> false, 
					'teeny'			=> true, 
				),
			    'attributes'	=> array(
					'required'		=> 'required',
				),
				'show_on_cb'	=> 'siw_event_show_custom_application_fields', 
			),
			array(
				'name'			=> __( 'Link om aan te melden', 'siw' ),
				'id'			=> $prefix . 'aanmelden_link_url',
				'type'			=> 'text_url',
				'show_on_cb'	=> 'siw_event_show_custom_application_fields', 
			),	
			array(
				'name'			=> __( 'Tekst voor link', 'siw' ),
				'id'			=> $prefix . 'aanmelden_link_tekst',
				'type'			=> 'text_medium',
				'show_on_cb'	=> 'siw_event_show_custom_application_fields', 
			),			
			array(
				'id'			=> $prefix . 'programma',
				'type'			=> 'group',
				'description'	=> 'Programma',
				'options'		=> array(
					'group_title'	=> 'Onderdeel {#}',
					'add_button'	=> 'Onderdeel toevoegen',
					'remove_button'	=> 'Verwijder onderdeel',
					'sortable'		=> true, // beta
				),
				'fields'		=> array(
					array(
						'name'			=> 'Starttijd',
						'id'			=> 'starttijd',
						'type'			=> 'text_time',
						'time_format'	=> 'H:i',
					),
					array(
						'name'			=> 'Eindtijd',
						'id'			=> 'eindtijd',
						'type'			=> 'text_time',
						'time_format'	=> 'H:i',
					),
					array(
						'name'			=> 'Omschrijving',
						'id'			=> 'omschrijving',
						'type'			=> 'textarea_small',
					),
				),
			),
		),
	);

	return $meta_boxes;
}	


function siw_event_show_custom_application_fields($field){
	$application = get_post_meta( $field->object_id, 'siw_agenda_aanmelden', 1 );
	if ( 'aangepast' == $application ){
		return true;
	}
	return false;
}

//kolom in admin menu
add_filter('manage_agenda_posts_columns', 'siw_agenda_admin_start_column_header', 10);

function siw_agenda_admin_start_column_header($columns) {
	$columns['start'] = 'Start';
	return $columns;
}

add_action('manage_agenda_posts_custom_column', 'siw_agenda_admin_start_column_value', 10, 2);
function siw_agenda_admin_start_column_value($column_name, $post_id) {
	if ( 'start' == $column_name ) {
		$start = get_post_meta( $post_id, 'siw_agenda_start', true );
		if ($start) {
			$time = date("H:i", $start );
			$date = siw_get_date_in_text( date("Y-m-d", $start ), true);
			echo $date . ' ' . $time;
		}
	}
}

/*sorteren op deadline*/
add_filter( 'manage_edit-agenda_sortable_columns', 'siw_agenda_start_column_sorting' );
function siw_agenda_start_column_sorting( $columns ) {
	$columns['start'] = 'start';
	return $columns;
}

add_filter( 'request', 'siw_agenda_start_column_orderby' );
function siw_agenda_start_column_orderby( $vars ) {
	if ( (isset( $vars['post_type'] ) && 'agenda' == $vars['post_type'] )|| ( isset( $vars['orderby'] ) && 'start' == $vars['orderby'] ) ) {
		$vars = array_merge( $vars, array(
			'meta_key' => 'siw_agenda_start',
			'orderby' => 'meta_value'
		) );
	}
	return $vars;
}


/*
Widget
*/
add_action( 'widgets_init', 'siw_register_agenda_widget' );
function siw_register_agenda_widget() {
	register_widget( 'siw_agenda' );
}


class siw_agenda extends WP_Widget {
 
public function __construct() {
	$widget_ops = array(
		'class'			=> 'siw_agenda',
		'description'	=> __( 'Toont het eerstvolgende evenement', 'siw' )
	);

	parent::__construct(
		'siw_agenda',
		__( 'SIW: Agenda', 'siw' ),
		$widget_ops
	);
}
 
 
	public function form( $instance ) {
		$widget_defaults = array(
			'title'	=> 'Agenda',
		);
		$instance = wp_parse_args( (array) $instance, $widget_defaults );
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Titel', 'siw' ); ?></label>
			<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" class="widefat" value="<?php echo esc_attr( $instance['title'] ); ?>">
		</p>

		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		return $instance;
	}
 
	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );

		$agenda_page = siw_get_parent_page('agenda');
		$meta_quer_args = array(
			'relation'	=>	'AND',
			array(
				'key'		=>	'siw_agenda_eind',
				'value'		=>	time(),
				'compare'	=>	'>='
			)
		);
		$query_args = array(
			'post_type'				=>	'agenda',
			'posts_per_page'		=>	1,
			'post_status'			=>	'publish',
			'ignore_sticky_posts'	=>	true,
			'meta_key'				=>	'siw_agenda_start',
			'orderby'				=>	'meta_value_num',
			'order'					=>	'ASC',
			'meta_query'			=>	$meta_quer_args
		);
		$siw_agenda = new WP_Query( $query_args );

		echo $before_widget;
		if ( $title ) {
			echo $before_title . $title . $after_title;
		}
		?>
		<?php if ($siw_agenda->have_posts()):?>
		<ul class="siw_events">
		<?php	
			while( $siw_agenda->have_posts() ): $siw_agenda->the_post();
				$start_ts = get_post_meta( get_the_ID(), 'siw_agenda_start', true );
				$end_ts = get_post_meta( get_the_ID(), 'siw_agenda_eind', true );
				$date_range = siw_get_date_range_in_text( date("Y-m-d",$start_ts),  date("Y-m-d",$end_ts), false );
				$location = get_post_meta( get_the_ID(), 'siw_agenda_locatie', true ); 
				$address = get_post_meta( get_the_ID(), 'siw_agenda_adres', true );
				$postal_code = get_post_meta( get_the_ID(), 'siw_agenda_postcode', true );
				$city = get_post_meta( get_the_ID(), 'siw_agenda_plaats', true );
				$start_time = date("H:i",$start_ts);
				$end_time = date("H:i",$end_ts);
			?>
				<li class="siw_event">
					<h5 class="siw_event_title">
					<a href="<?php esc_url( the_permalink() ); ?>" class="siw_event_link"><?php esc_html( the_title() ); ?></a>
					</h5>
					<span class="siw_event_duration" >
						<?php echo esc_html( $date_range );?> <br/>
						<?php echo esc_html( $start_time . '&nbsp;-&nbsp;' . $end_time );?><br/>
					</span>
					<span class="siw_event_location"><?php echo esc_html( $location. ',&nbsp;' . $city );?></span>
					<script type="application/ld+json">
[{
"@context" : "http://schema.org",
"name" : "<?php esc_attr( the_title() );?>",
"description" : "<?php echo esc_attr( get_the_excerpt() );?>",
"image" : "<?php esc_url( the_post_thumbnail_url() );?>",
"@type" : "event",
"startDate" : "<?php echo esc_attr( date('Y-m-d',$start_ts ) ); ?>",
"endDate" : "<?php echo esc_attr( date('Y-m-d',$end_ts ) ); ?>",
"location" : {
	"@type" : "Place",
	"name" : "<?php echo esc_attr( $location ); ?>",
	"address" : "<?php echo esc_attr( $address . ', ' .$postal_code . ' ' . $city ); ?>"
},
"url": "<?php echo esc_url( the_permalink() ); ?>"
}]
					</script>
					</li>
			<?php endwhile;?>
		</ul>
		<p class="siw_agenda_page_link">
			<a href="<?php echo esc_url( get_page_link( $agenda_page ) ); ?>"><?php _e('Bekijk de volledige agenda.', 'siw'); ?></a>
		</p>
		<?php else: ?>
		<p><?php _e('Er zijn momenteel geen geplande activiteiten.', 'siw'); ?></p>
		<?php endif;
		wp_reset_query();
		echo $after_widget;
	}
}
     
