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
			'class'         =>   'siw_contact_information',
			'description'   =>   __( 'Contactinformatie', 'siw' )
		);
 
		parent::__construct(
			'siw_contact_information',          //base id
			__( 'SIW: Contactinformatie', 'siw' ), //title
			$widget_ops
		);
	}
 
	public function form( $instance ) {
		$widget_defaults = array(
			'title'			=>	'Contact',
		);
		$instance  = wp_parse_args( (array) $instance, $widget_defaults );
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
		$list = $instance['list'];

		//ophalen gegevens
		$name = siw_get_general_information('naam');;
		$address = 'Willemstraat 7';
		$postal_code = '3511 RJ';
		$city = 'Utrecht';
		$email = sanitize_email( siw_get_general_information('email') );
		$phone = siw_get_general_information('telefoon');
		
		
		echo $before_widget;
		if ( $title ) {
			echo $before_title . $title . $after_title;
		}?>
		<div class="vcard">
			<h5 class="fn org"><b><?php echo esc_html( $name );?></b></h5>
			<p class="adr">
				<span class="street-address"><?php echo esc_html( $address );?></span><br/>
				<span class="postal-code"><?php echo esc_html( $postal_code );?></span>&nbsp;<span class="locality"><?php echo esc_html( $city );?><span>
			</p>
			<p class="tel fixedtel"><i class="kt-icon-phone3"></i>&nbsp;<?php echo esc_html( $phone );?></p>
			<p><a href="mailto:<?php echo antispambot( $email );?>" class="email"><i class="kt-icon-envelop"></i>&nbsp;<?php echo antispambot( $email );?></a></p>
		</div>	
		<?php
		echo $after_widget;
    }
     
}