<?php
/**
 * Customer processing order email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-processing-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     2.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/* gegevens aanmelding */
$application_number = $order->get_order_number();
//ondertekening
$signature_name = SIW_PLUGIN::siw_get_setting('workcamp_application_signature_name');
$signature_title = SIW_PLUGIN::siw_get_setting('workcamp_application_signature_title');


$tariff = 'regulier';
foreach ( $order->get_items() as $item_id => $item ) {
	if ( 'student' == $item['pa_tarief'] ) {
		$tariff = 'student';
	}
}

/*Bepaal leeftijd */
$age = SIW_PLUGIN::siw_get_age_from_date( $order->get_meta('_billing_dob') );

//bepaal onderwerp
if ( $order->has_status( 'processing' ) && ( 'bacs' != $order->get_payment_method() ) ) {
	$email_heading = sprintf( __( 'Bevestiging aanmelding #%s', 'siw' ), $application_number );
}
if ( $order->has_status( 'processing' ) && ( 'bacs' === $order->get_payment_method() ) ) {
	$email_heading = sprintf( __( 'Bevestiging betaling aanmelding #%s', 'siw'), $application_number );
}
if ( $order->has_status( 'on-hold' ) ) {
	$email_heading = sprintf( __( 'Bevestiging aanmelding #%s', 'siw'), $application_number );
}
/**
 * @hooked WC_Emails::email_header() Output the email header
 **/
do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<div style="font-family:Verdana, normal; color:#444; font-size:0.9em; ">
<p><?php
printf( esc_html__( 'Beste %s,', 'siw'), $order->get_billing_first_name() ); echo BR2;

if ( $order->has_status( 'on-hold' ) ) {
	esc_html_e( 'Heel erg bedankt voor je aanmelding voor een vrijwilligersproject via SIW!', 'siw' ); echo SPACE;
	esc_html_e( 'We doen ons best om ervoor te zorgen dat dit voor jou een onvergetelijke ervaring wordt!', 'siw' ); echo BR2;
	esc_html_e( 'Je inschrijving wordt pas in behandeling genomen als we je betaling ontvangen hebben.', 'siw' ); echo BR2;
	printf( esc_html__( 'Je kunt je betaling overmaken naar %s o.v.v. je aanmeldnummer (%s).', 'siw' ), SIW_IBAN, $application_number ); echo BR;
}
if ( $order->has_status( 'processing' ) && ('bacs' != $order->get_payment_method() ) ) {
	esc_html_e( 'Heel erg bedankt voor je aanmelding en betaling voor een vrijwilligersproject via SIW!', 'siw' ); echo SPACE;
	esc_html_e( 'We doen ons best om ervoor te zorgen dat dit voor jou een onvergetelijke ervaring wordt!', 'siw' ); echo BR2;
}
if ( $order->has_status( 'processing' ) && ( 'bacs' === $order->get_payment_method() ) ) {
	esc_html_e( 'Heel erg bedankt voor je betaling.', 'siw' ); echo BR2;
}
if ( $order->has_status( 'processing' ) ) {
	esc_html_e( 'We gaan je aanmelding doorzetten naar onze partnerorganisatie en nemen contact met je op zodra we bericht hebben ontvangen over je plaatsing.', 'siw' ); echo BR;
	esc_html_e( 'Gemiddeld duurt het 5 werkdagen om een aanmelding voor een project binnen Europa te verwerken.', 'siw' ); echo SPACE;
	esc_html_e( 'Voor een projectaanmelding buiten Europa duurt het ongeveer 2 weken voor je van ons hoort of je definitief geplaatst bent', 'siw' ); echo BR2;
	esc_html_e( 'We willen je er nadrukkelijk op wijzen dat deze email nog geen bevestiging is van deelname, maar een bevestiging van ontvangst van je betaling.', 'siw' ); echo SPACE;
	esc_html_e( 'Boek nog geen tickets, totdat je van ons ook een bevestiging hebt ontvangen van je plaatsing.', 'siw' ); echo SPACE;
	esc_html_e( 'Het kan zijn dat in de tussentijd het maximale deelnemersaantal op het project is bereikt.', 'siw' ); echo BR2;
}
//TODO: waar komen deze teksten?
/*
if ( 'student' == $tariff && 18 <= $age ) {
	esc_html_e('Tekst over studentenbewijs.', 'siw'); echo BR2;
}
if ( 18 > $age ) {
	esc_html_e('Tekst over toestemming ouders. Inclusief link.', 'siw'); echo BR2;
}
*/
esc_html_e( 'Als je nog vragen hebt, aarzel dan niet om contact met ons op te nemen.', 'siw'); echo BR2;
esc_html_e( 'Met vriendelijke groet,', 'siw' ); echo BR2;
echo esc_html( $signature_name ); echo BR;?>
<span style="color:#808080"><?php echo esc_html( $signature_title )?> </span>
</p>
</div>

<?php SIW_PLUGIN::siw_wc_email_show_project_details( $order, $application_number );?>

<?php SIW_PLUGIN::siw_wc_email_show_application_details( $order );?>


<?php /**
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action( 'woocommerce_email_footer', $email );
?>
