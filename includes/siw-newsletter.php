<?php 
/*
(c)2015 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_filter( 'siw_ajax_allowed_actions', function($actions){
	$actions[]='newsletter_subscription';
	return $actions;
});


add_action( 'siw_ajax_newsletter_subscription', 'siw_newsletter_subscription' );
function siw_newsletter_subscription() {
		$name = $_POST['name'];
		$email = $_POST['email'];
		$list = (integer) $_POST['list'];
		
		if (($name) && (is_email($email)) && ($list) ){
			$user_data = array(
				'firstname' => sanitize_text_field($name),
				'email' => sanitize_email($email),
			);
			$data_subscriber = array(
				'user' => $user_data,
				'user_list' => array('list_ids' => array($list))
			);		
			
			$user_id = WYSIJA::get( 'user', 'helper' )->addSubscriber( $data_subscriber );
			if ( is_numeric( $user_id ) ){
				$data = array('success' => 1, 'message' => 'Controleer de inbox- of spammap nu om je aanmelding te bevestigen.');
			}
			elseif ( $user_id  ){
				$data = array('success' => 1, 'message' => 'Je bent al ingeschreven.');
			}
			else{
				$data = array('succes' => 0, 'message' => 'Er is helaas iets misgegaan. Probeer het later nog eens.');			
			}
		}
		else{
			$data = array('succes' => 0, 'message' => 'Er is helaas iets misgegaan. Probeer het later nog eens.');
		}
		
	$result = json_encode($data);
	echo $result;	
	die();
}

//Widget
add_action( 'widgets_init', 'siw_register_mailpoet_widget' );
function siw_register_mailpoet_widget() {
	register_widget( 'siw_mailpoet_subscription' );
}

class siw_mailpoet_subscription extends WP_Widget {

	public function __construct() {
		$widget_ops = array(
			'class'         =>   'siw_mailpoet_subscription',
			'description'   =>   __( 'Aanmeldformulier voor Mailpoet', 'siw' )
		);
 
		parent::__construct(
			'siw_mailpoet_subscription',          //base id
			__( 'SIW: Aanmelden nieuwsbrief', 'siw' ), //title
			$widget_ops
		);
	}
 
	public function form( $instance ) {
		$widget_defaults = array(
			'title'			=>	'Blijf op de hoogte',
		);
		$instance  = wp_parse_args( (array) $instance, $widget_defaults );
		
		$model_list = WYSIJA::get('list','model');
		$mailpoet_lists = $model_list->get(array('name','list_id'),array('is_enabled'=>1));
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Titel', 'siw' ); ?></label>
			<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" class="widefat" value="<?php echo esc_attr( $instance['title'] ); ?>">
			<label for="<?php echo $this->get_field_id( 'list' ); ?>"><?php _e( 'Lijst', 'siw' ); ?></label>
			<select id="<?php echo $this->get_field_id( 'list' ); ?>" name="<?php echo $this->get_field_name( 'list' ); ?>" class="widefat">
			<?php
			foreach ($mailpoet_lists as $list) {
				echo '<option value="', $list['list_id'], '"', $instance['list'] == $list['list_id'] ? ' selected="selected"' : '', '>', $list['name'], '</option>';
			}
		  echo '</select>'; ?>
		</p>

		
		<?php
	}
	
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		$instance['list'] = $new_instance['list'];
		return $instance;
	}
 
 
    public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		$list = $instance['list'];

		echo $before_widget;
		if ( $title ) {
			echo $before_title . $title . $after_title;
		}?>
		<div>
			<div id="newsletter_message" class="text-center hidden"></div>	
			<div id="newsletter_loading" class="text-center hidden"></div>
			<form id="siw_newsletter_subscription" method="post" autocomplete="on">
				<p>
					Meld je aan voor onze nieuwsbrief en voeg je bij de <?php echo do_shortcode('[wysija_subscribers_count list_id="' . $list . '" ]');?> abonnees.
				</p>
				<p>
					<label>Voornaam *</label>
					<input type="text" name="name" title="Voornaam" id="newsletter_name" required>
				</p>
				<p>
					<label>E-mail *</label>
					<input type="email" name="email" title="E-mail" id="newsletter_email" required>
					
				</p>
				<p>
					<input type="submit" value="Aanmelden">
				</p>
				<input type="hidden" value="<?php echo $list; ?>" name="list_id" id="newsletter_list_id">
			</form>
		</div>
	<?php
	echo $after_widget;
    }
     
}