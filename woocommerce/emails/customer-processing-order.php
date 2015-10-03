<?php
/**
 * Customer processing order email
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
//hulplijstjes
$genders = siw_get_shared_array('gender');
$nationalities = siw_get_shared_array('nationalities');
$languages = siw_get_shared_array('languages');
$language_skill = siw_get_shared_array('language_skill');

//ondertekening
$signature = siw_get_mail_signature_name('aanmelding_groepsproject');


//gegevens aanmelding
$application_number = $order->get_order_number();


//persoonsgegevens
$first_name = $order->billing_first_name;
$last_name = $order->billing_last_name;
$full_name = $first_name . ' ' . $last_name;
$date_of_birth = $order->billing_dob;		
$gender = $genders[ $order->billing_gender ];	
$nationality = $nationalities[ $order->billing_nationality ];

//adres formatteren
$adress = $order->billing_address_1 . ' ' . $order->billing_housenumber . '<br/>'.$order->billing_postcode . ' ' . $order->billing_city . '<br/>' . $order->billing_country;
$email = $order->billing_email;
$phone = $order->billing_phone;		

//gegevens noodcontact
$emergency_contact_name = get_post_meta( $order->id, 'emergencyContactName', true );
$emergency_contact_phone = get_post_meta( $order->id, 'emergencyContactPhone', true );


//talenkennis
$language_1 = $languages[get_post_meta( $order->id, 'language1', true )];
$language_1_skill = $language_skill[get_post_meta( $order->id, 'language1Skill', true )];
$language_2 = $languages[get_post_meta( $order->id, 'language2', true )];
$language_2_skill = $language_skill[get_post_meta( $order->id, 'language2Skill', true )];	
$language_3 = $languages[get_post_meta( $order->id, 'language3', true )];
$language_3_skill = $language_skill[get_post_meta( $order->id, 'language3Skill', true )];	


//gegevens voor PO
$motivation = get_post_meta( $order->id, 'motivation', true );	
$health_issues = get_post_meta( $order->id, 'healthIssues', true );
$volunteer_experience = get_post_meta( $order->id, 'volunteerExperience', true );
$together_with = get_post_meta( $order->id, 'togetherWith', true ); 


//bepaal onderwerp
if ($order->has_status( 'processing' ) && ('bacs' != $order->payment_method)){
	$subject = 'Bevestiging aanmelding #'.$application_number;
}
if ($order->has_status( 'processing' ) && ('bacs' === $order->payment_method)){
	$subject = 'Bevestiging betaling aanmelding #'.$application_number;
}
if($order->has_status( 'on-hold' )){
	$subject = 'Bevestiging aanmelding #'.$application_number;
}
do_action('woocommerce_email_header', $subject);
?>

<font style="font-family:'Open Sans', Verdana, normal; color:#444; font-size:14px; ">
<p>Beste <?php echo $first_name ?>,<br/><br/>
<?php if($order->has_status( 'on-hold' )){?>
Heel erg bedankt voor je aanmelding voor een vrijwilligersproject via SIW! We doen ons best om ervoor te zorgen dat dit voor jou een onvergetelijke ervaring wordt!<br/><br/>Je inschrijving wordt pas in behandeling genomen als we je betaling ontvangen hebben.<br/><br/>
Je kunt je betaling overmaken naar NL28 INGB 0000 0040 75 o.v.v. je aanmeldnummer (<?php echo $application_number;?>).<br/>

<?php
}
if ($order->has_status( 'processing' ) && ('bacs' != $order->payment_method)){?>
Heel erg bedankt voor je aanmelding en betaling voor een vrijwilligersproject via SIW! We doen ons best om ervoor te zorgen dat dit voor jou een onvergetelijke ervaring wordt!<br/><br/>
<?php
}
if ($order->has_status( 'processing' ) && ('bacs' === $order->payment_method)){?>
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
<?php echo $signature?> 
</p>
</font>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<?php
siw_wc_generate_email_table_header_row('Je aanmelding');		
siw_wc_generate_email_table_row('Aanmeldnummer', $application_number );	


foreach ( $order->get_items() as $item_id => $item ) {
	$_product     = apply_filters( 'woocommerce_order_item_product', $order->get_product_from_item( $item ), $item );
	$item_meta    = new WC_Order_Item_Meta( $item['item_meta'], $_product );
	
	//projectdetails formatteren
	$item_details = apply_filters( 'woocommerce_order_item_name', $item['name'], $item ) . ' (' . get_post_meta($_product->id, 'projectduur', true) . ')';
	if ( $item_meta->meta ) {	
		$item_details .= '<br/><small>' . nl2br( $item_meta->display( true, true, '_', "\n" ) ) . '</small>';
	} 
	siw_wc_generate_email_table_row('Project', $item_details);	
}

$discount = $order->get_total_discount();
$subtotal = $order->get_subtotal();
$total = $order->get_total();

//subtotaal alleen tonen als het afwijkt van het totaal
if ($subtotal != $total){
	siw_wc_generate_email_table_row('Subtotaal', $order->get_subtotal_to_display() );	
	siw_wc_generate_email_table_row('Korting', '-' . $order->get_discount_to_display() );	
}
siw_wc_generate_email_table_row('Totaal', $order->get_formatted_order_total() );	
siw_wc_generate_email_table_row('Betaalwijze', $order->payment_method_title );	
?>	
</table>


<table width="100%" border="0" cellspacing="0" cellpadding="0">
<?php 
//Persoonsgegevens
siw_wc_generate_email_table_header_row('Je gegevens');		
siw_wc_generate_email_table_row('Naam', $full_name );		
siw_wc_generate_email_table_row('Geboortedatum', $date_of_birth );		
siw_wc_generate_email_table_row('Geslacht', $gender );	
siw_wc_generate_email_table_row('Nationaliteit', $nationality );	
siw_wc_generate_email_table_row('Adres', $adress );	
siw_wc_generate_email_table_row('E-mailadres', $email );		
siw_wc_generate_email_table_row('Telefoonnummer', $phone );		

//gegevens noodcontact
siw_wc_generate_email_table_header_row('Noodcontact');	
siw_wc_generate_email_table_row('Naam', $emergency_contact_name);		
siw_wc_generate_email_table_row('Telefoonnummer', $emergency_contact_phone);			

//talenkennis
siw_wc_generate_email_table_header_row('Talenkennis');		
siw_wc_generate_email_table_row( $language_1, $language_1_skill);		
if ($language_2){
	siw_wc_generate_email_table_row( $language_2, $language_2_skill);	
}	
if ($language_3){
	siw_wc_generate_email_table_row( $language_3, $language_3_skill);	
}	

//gegevens voor PO
siw_wc_generate_email_table_header_row('Informatie voor partnerorganisatie');	
siw_wc_generate_email_table_row('Motivation', $motivation);	
if ($health_issues){
	siw_wc_generate_email_table_row('Health issues', $health_issues);
}
if ($volunteer_experience){
	siw_wc_generate_email_table_row('Volunteer experience', $volunteer_experience);
}
if ($together_with){
	siw_wc_generate_email_table_row('Together with', $together_with);
}
?>	
</table>


<?php do_action( 'woocommerce_email_footer' ); ?>
