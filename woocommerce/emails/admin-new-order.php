<?php
/**
 * Admin new order email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/admin-new-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author WooThemes
 * @package WooCommerce/Templates/Emails/HTML
 * @version 2.5.0
 */

 if ( ! defined( 'ABSPATH' ) ) {
 	exit;
 }

//gegevens aanmelding
$application_number = $order->get_order_number();

//bepaal status
if ( $order->has_status( 'on-hold' ) ) {
	$application_status = __( 'nog niet betaald', 'siw' );
}
if( $order->has_status( 'processing' ) ) {
	$application_status = __( 'inclusief betaling', 'siw' );
}
$email_heading = sprintf( esc_html__( 'Nieuwe aanmelding (%s)', 'siw'), $application_status );
 /**
  * @hooked WC_Emails::email_header() Output the email header
  */
do_action( 'woocommerce_email_header', $email_heading, $email ); ?>
<div style="font-family:Verdana, normal; color:#444; font-size:14px; ">
<p><?php
printf( esc_html__( 'Er is een nieuwe aanmelding (%s) binnengekomen:', 'siw' ),  $application_status ); echo BR;
?>
<a href="<?php echo admin_url( 'post.php?post=' . $order->get_id() . '&action=edit' ); ?>"><?php printf( esc_html__( 'Aanmelding %s', 'siw' ), $application_number );?></a>
</p>
</div>
<?php do_action( 'siw_wc_email_show_project_details', $order, $application_number );?>
<?php do_action( 'siw_wc_email_show_application_details', $order );?>

<?php  /**
  * @hooked WC_Emails::email_footer() Output the email footer
  */
 do_action( 'woocommerce_email_footer', $email ); ?>
