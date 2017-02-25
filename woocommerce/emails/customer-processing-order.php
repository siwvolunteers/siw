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


//gegevens aanmelding
$application_number = $order->get_order_number();
//ondertekening
$signature_name = siw_get_setting('workcamp_application_signature_name');
$signature_title = siw_get_setting('workcamp_application_signature_title');
$iban = SIW_IBAN;

//bepaal onderwerp
if ($order->has_status( 'processing' ) && ('bacs' != $order->payment_method)){
	$subject = 'Bevestiging aanmelding #' . $application_number;
}
if ($order->has_status( 'processing' ) && ('bacs' === $order->payment_method)){
	$subject = 'Bevestiging betaling aanmelding #' . $application_number;
}
if($order->has_status( 'on-hold' )){
	$subject = 'Bevestiging aanmelding #' . $application_number;
}
/**
 * @hooked WC_Emails::email_header() Output the email header
 **/
do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<div style="font-family:'Open Sans', Verdana, normal; color:#444; font-size:0.9em; ">
<p>Beste <?php echo $order->billing_first_name ?>,<br/><br/>
<?php if($order->has_status( 'on-hold' )){?>
Heel erg bedankt voor je aanmelding voor een vrijwilligersproject via SIW! We doen ons best om ervoor te zorgen dat dit voor jou een onvergetelijke ervaring wordt!<br/><br/>Je inschrijving wordt pas in behandeling genomen als we je betaling ontvangen hebben.<br/><br/>
Je kunt je betaling overmaken naar <?php echo esc_html( $iban );?> o.v.v. je aanmeldnummer (<?php echo $application_number;?>).<br/>

<?php
}
if ( $order->has_status( 'processing' ) && ('bacs' != $order->payment_method )){?>
Heel erg bedankt voor je aanmelding en betaling voor een vrijwilligersproject via SIW! We doen ons best om ervoor te zorgen dat dit voor jou een onvergetelijke ervaring wordt!<br/><br/>
<?php
}
if ( $order->has_status( 'processing' ) && ('bacs' === $order->payment_method )){?>
Heel erg bedankt voor je betaling.<br/><br/>
<?php
}
if ($order->has_status( 'processing' ) ){?>
We gaan je aanmelding doorzetten naar onze partnerorganisatie en nemen contact met je op zodra we bericht hebben ontvangen over je plaatsing.<br/>
Gemiddeld duurt het 5 werkdagen om een aanmelding voor een project binnen Europa te verwerken. Voor een projectaanmelding buiten Europa duurt het ongeveer 2 weken voor je van ons hoort of je definitief geplaatst bent.<br/><br/>
We willen je er nadrukkelijk op wijzen dat deze email nog geen bevestiging is van deelname, maar een bevestiging van ontvangst van je betaling. Boek nog geen tickets, totdat je van ons ook een bevestiging hebt ontvangen van je plaatsing. Het kan zijn dat in de tussentijd het maximale deelnemersaantal op het project is bereikt.<br/><br/>
<?php
}
?>
Als je nog vragen hebt, aarzel dan niet om contact met ons op te nemen.<br/><br/>
Met vriendelijke groet,<br/><br/>
<?php echo esc_html( $signature_name )?><br/>
<span style="color:#808080"><?php echo esc_html( $signature_title )?> </span>
</p>
</div>

<?php siw_wc_email_show_project_details( $order, $application_number );?>

<?php siw_wc_email_show_application_details( $order );?>


<?php /**
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action( 'woocommerce_email_footer', $email );
?>
