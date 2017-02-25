<?php
/*
(c)2015 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//Widget
add_action( 'widgets_init', 'siw_register_contact_widget' );
function siw_register_contact_widget() {
	register_widget( 'siw_contact_information' );
}

class siw_contact_information extends WP_Widget {

	public function __construct() {
		$widget_ops = array(
			'class'			=> 'siw_contact_information',
			'description'	=> __( 'Contactinformatie', 'siw' )
		);

		parent::__construct(
			'siw_contact_information',
			__( 'SIW: Contactinformatie', 'siw' ),
			$widget_ops
		);
	}

	public function form( $instance ) {
		$widget_defaults = array(
			'title'			=> 'Contact',
		);
		$instance  = wp_parse_args( (array) $instance, $widget_defaults );
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Titel', 'siw' ); ?></label>
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

		//ophalen gegevens
		$name = SIW_NAME;
		$address = SIW_ADDRESS;
		$postal_code = SIW_POSTAL_CODE;
		$city = SIW_CITY;
		$email = SIW_EMAIL;
		$phone = SIW_PHONE;


		echo $before_widget;
		if ( $title ) {
			echo $before_title . $title . $after_title;
		}?>
		<div class="vcard">
			<h5 class="fn org"><b><?php echo esc_html( $name );?></b></h5>
			<p class="adr">
				<span class="street-address"><?php echo esc_html( $address );?></span><br/>
				<span class="postal-code"><?php echo esc_html( $postal_code );?></span>&nbsp;<span class="locality"><?php echo esc_html( $city );?></span>
			</p>
			<p class="tel fixedtel"><i class="kt-icon-phone3"></i>&nbsp;<?php echo esc_html( $phone );?></p>
			<p><a href="mailto:<?php echo antispambot( $email );?>" class="email"><i class="kt-icon-envelop"></i>&nbsp;<?php echo antispambot( $email );?></a></p>
		</div>
		<?php
		echo $after_widget;
    }

}


/*Widget met quote van deelnemer */
add_action( 'widgets_init', 'siw_register_testimonial_quote_widget' );
function siw_register_testimonial_quote_widget() {
	register_widget( 'siw_testimonial_quote' );
}
class siw_testimonial_quote extends WP_Widget {

	public function __construct() {
		$widget_ops = array(
			'class'			=> 'siw_testimonial_quote',
			'description'	=> __( 'Toont een willekeurige quote van deelnemer', 'siw' )
		);

		parent::__construct(
			'siw_testimonial_quote',
			__( 'SIW: Quote van deelnemer', 'siw' ),
			$widget_ops
		);
	}

	public function form( $instance ) {
		$widget_defaults = array(
			'title'			=> __('Ervaringen van deelnemers', 'siw'),
			'cat'			=> '',
		);
		$instance  = wp_parse_args( (array) $instance, $widget_defaults );

		if (isset($instance['cat'])){
			$cat = esc_attr($instance['cat']);
		}
		else{
			$cat = '';
		}

		$categories= get_terms('testimonial-group');
		$category_options = array();
		$category_options[] = '<option value="">' . __('Alle', 'siw') . '</option>';
		foreach ($categories as $category) {
			if ( $cat == $category->slug) { $selected=' selected="selected"';} else { $selected=""; }
			$category_options[] = '<option value="' . $category->slug .'"' . $selected . '>' . $category->name . '</option>';
		}



		?>
<p>
	<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Titel', 'siw' ); ?></label>
	<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" class="widefat" value="<?php echo esc_attr( $instance['title'] ); ?>">
</p>
<p>
	<label for="<?php echo $this->get_field_id('cat'); ?>"><?php esc_html_e('Categorie', 'siw'); ?></label>
	<select id="<?php echo $this->get_field_id('cat'); ?>" name="<?php echo $this->get_field_name('cat'); ?>"><?php echo implode('', $category_options); ?></select>
</p>
		<?php
	}
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		$instance['cat'] = $new_instance['cat'];
		return $instance;
	}

    public function widget( $args, $instance ) {
		extract( $args );

		$query = new WP_Query( array(
			'post_type'				=> 'testimonial',
			'testimonial-group'		=> $instance['cat'],
			'no_found_rows'			=> true,
			'posts_per_page'		=> 1,
			'orderby'				=> 'rand',
			'post_status'			=> 'publish',
			'ignore_sticky_posts'	=> true )
		);

		if ($query->have_posts()){
			$title = apply_filters( 'widget_title', $instance['title'] );
			echo $before_widget;
			echo '<div class="siw_quote_widget">';
			if ( $title ) {
				echo $before_title . $title . $after_title;
			}
			while ($query->have_posts()) : $query->the_post();
			global $post;
			$quote = get_the_content();
			$name = get_the_title();
			$project = get_post_meta( $post->ID, '_kad_testimonial_location', true );
			?>
			<div class="quote">
				<div class ="text">
				"<?php echo $quote;?>"
				</div>
				<div class = "volunteer">
					<span class="name"><?php echo $name;?></span>
					<span class="separator"> | </span>
					<span class="category"><?php echo $project;?></span>
				</div>
			</div>
		<?php endwhile; ?>
		<?php
		echo '</div>';
		echo $after_widget;

		wp_reset_postdata();
		}
	}

}
