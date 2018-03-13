<?php
/**
 * Customer on-hold order email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-processing-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     2.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
//TODO: waarom niet customer-processing-order.php includen?

//gegevens aanmelding
$application_number = $order->get_order_number();
//ondertekening
$signature_name = apply_filters( 'siw_setting', false, 'workcamp_application_signature_name' );
$signature_title = apply_filters( 'siw_setting', false, 'workcamp_application_signature_title' );


/**
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<div style="font-family:Verdana, normal; color:#444; font-size:0.9em; ">
<p><?php
printf( esc_html__( 'Beste %s,', 'siw'), $order->get_billing_first_name() ); echo BR2;
esc_html_e( 'Heel erg bedankt voor je aanmelding voor een vrijwilligersproject via SIW!', 'siw' ); echo SPACE;
esc_html_e( 'We doen ons best om ervoor te zorgen dat dit voor jou een onvergetelijke ervaring wordt!', 'siw' ); echo BR2;
esc_html_e( 'Je inschrijving wordt pas in behandeling genomen als we je betaling ontvangen hebben.', 'siw' ); echo BR2;
printf( esc_html__( 'Je kunt je betaling overmaken naar %s o.v.v. je aanmeldnummer (%s).', 'siw' ), SIW_IBAN, $application_number ); echo BR;
esc_html_e( 'Als je nog vragen hebt, aarzel dan niet om contact met ons op te nemen.', 'siw' ); echo BR2;
esc_html_e( 'Met vriendelijke groet,', 'siw' ); echo BR2;
echo esc_html( $signature_name )?><br/>
<span style="color:#808080"><?php echo esc_html( $signature_title )?> </span>
</p>
</div>

<?php do_action( 'siw_wc_email_show_project_details', $order, $application_number );?>
<?php do_action( 'siw_wc_email_show_application_details', $order );?>

<?php
/**
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action( 'woocommerce_email_footer', $email );
