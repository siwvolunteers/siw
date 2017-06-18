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
if ( $order->has_status( 'on-hold' )) {
	$application_status = '(nog niet betaald)';
}
if( $order->has_status( 'processing' )){
	$application_status = '(inclusief betaling)';
}
$email_heading = 'Nieuwe aanmelding ' . $application_status;
 /**
  * @hooked WC_Emails::email_header() Output the email header
  */
do_action( 'woocommerce_email_header', $email_heading, $email ); ?>
<div style="font-family:Verdana, normal; color:#444; font-size:14px; ">
<p>
Er is een nieuwe aanmelding <?php echo $application_status; ?> binnengekomen:<br/>
<a href="<?php echo admin_url( 'post.php?post=' . $order->id . '&action=edit' ); ?>">Aanmelding <?php echo $application_number;?><a/>
</p>
</div>
<?php SIW_PLUGIN::siw_wc_email_show_project_details( $order, $application_number);?>
<?php SIW_PLUGIN::siw_wc_email_show_application_details( $order );?>

<?php  /**
  * @hooked WC_Emails::email_footer() Output the email footer
  */
 do_action( 'woocommerce_email_footer', $email ); ?>
