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


//gegevens aanmelding
$application_number = $order->get_order_number();
//ondertekening
$signature_name = siw_get_setting('workcamp_application_signature_name');
$signature_title = siw_get_setting('workcamp_application_signature_title');
$iban = SIW_IBAN;

/**
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<div style="font-family:'Open Sans', Verdana, normal; color:#444; font-size:0.9em; ">
<p>Beste <?php echo $order->billing_first_name ?>,<br/><br/>
Heel erg bedankt voor je aanmelding voor een vrijwilligersproject via SIW! We doen ons best om ervoor te zorgen dat dit voor jou een onvergetelijke ervaring wordt!<br/><br/>Je inschrijving wordt pas in behandeling genomen als we je betaling ontvangen hebben.<br/><br/>
Je kunt je betaling overmaken naar <?php echo esc_html( $iban );?> o.v.v. je aanmeldnummer (<?php echo esc_html( $application_number );?>).<br/><br/>
Als je nog vragen hebt, aarzel dan niet om contact met ons op te nemen.<br/><br/>
Met vriendelijke groet,<br/><br/>
<?php echo esc_html( $signature_name )?><br/>
<span style="color:#808080"><?php echo esc_html( $signature_title )?> </span>
</p>
</div>

<?php siw_wc_email_show_project_details( $order, $application_number );?>

<?php siw_wc_email_show_application_details( $order );?>

<?php
/**
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action( 'woocommerce_email_footer', $email );
