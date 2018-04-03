<?php
/**
 * Checkout terms and conditions checkbox
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.1.1
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$terms_page_id = wc_get_page_id( 'terms' );

if ( $terms_page_id > 0 && apply_filters( 'woocommerce_checkout_show_terms', true ) ) :

	do_action( 'woocommerce_checkout_before_terms_and_conditions' ); ?>

	<p class="form-row terms wc-terms-and-conditions control-checkbox">
		<input type="checkbox" class="input-checkbox" name="terms" <?php checked( apply_filters( 'woocommerce_terms_is_checked_default', isset( $_POST['terms'] ) ), true ); ?> id="terms" />
		<span class="control-indicator"></span>
		<label for="terms" class="checkbox"><?php _e( 'Ik heb de <a id="open-terms-and-conditions" data-toggle="modal" data-target="#siw-terms" >inschrijfvoorwaarden</a> gelezen en ga akkoord ', 'siw' ); ?> <span class="required">*</span></label>
		<input type="hidden" name="terms-field" value="1" />
	</p>
	<?php do_action( 'woocommerce_checkout_after_terms_and_conditions' ); ?>
<?php endif;


/* HTML voor lightbox aan footer toevoegen */
add_action( 'wp_footer', function() use( $terms_page_id ) {
	$terms_page = get_post( $terms_page_id );
	?>
	<div class="modal fade" id="siw-terms" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title"><?php esc_html_e( $terms_page->post_title );?></h4>
				</div>
				<div class="modal-body">
				<?php echo wp_kses_post( wpautop( do_shortcode( $terms_page->post_content ) ) ); ?>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default kad-btn" data-dismiss="modal"><?php esc_html_e('Sluiten', 'siw');?></button>
				</div>
			</div>
		</div>
	</div>
	<?php
});

?>
