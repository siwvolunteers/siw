<?php
/*
(c)2015-2016 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// mail sturen bij statusovergang van 'on hold' naar 'in processing'
add_action( 'woocommerce_email', 'siw_wc_order_status_on_hold_to_processing_email' );

function siw_wc_order_status_on_hold_to_processing_email( $email_class ) {
	add_action( 'woocommerce_order_status_on-hold_to_processing_notification', array( $email_class->emails['WC_Email_Customer_Processing_Order'], 'trigger' ) );
}


//functie om tabelrij in Woocommerce-emails te genereren

function siw_wc_generate_email_table_row( $name, $value = '&nbsp;'){?>
	<tr>
		<td width="35%">
			<font style="font-family:'Open Sans', Verdana, normal; color:#444; font-size:14px; ">
				<?php echo $name; ?>
			</font></td>
		<td width="5%"><font style="font-family:'Open Sans', Verdana, normal; color:#444; font-size:14px; "></font></td>
		<td width="50%">
			<font style="font-family:'Open Sans', Verdana, normal; color:#444; font-size:14px; font-style:italic">
				<?php echo $value; ?>
			</font>
		</td>
	</tr>
<?php
}
function siw_wc_generate_email_table_header_row( $name ){?>
	<tr>
		<td width="35%">
			<font style="font-family:'Open Sans', Verdana, normal; color:#444; font-size:14px; font-weight:bold">
				<?php echo $name; ?>
			</font></td>
		<td width="5%"><font style="font-family:'Open Sans', Verdana, normal; color:#444; font-size:14px; "></font></td>
		<td width="50%"><font style="font-family:'Open Sans', Verdana, normal; color:#444; font-size:14px; "></font></td>
	</tr>
<?php
}


function siw_wc_email_show_project_details( $order, $application_number){
	?>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<?php
	siw_wc_generate_email_table_header_row('Aanmelding');
	siw_wc_generate_email_table_row('Aanmeldnummer', $application_number );

	foreach ( $order->get_items() as $item_id => $item ) {
		$_product     = apply_filters( 'woocommerce_order_item_product', $order->get_product_from_item( $item ), $item );
		$item_meta    = new WC_Order_Item_Meta( $item, $_product );

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
	if ( $subtotal != $total ){
		siw_wc_generate_email_table_row('Subtotaal', $order->get_subtotal_to_display() );
		siw_wc_generate_email_table_row('Korting', '-' . $order->get_discount_to_display() );
	}
	siw_wc_generate_email_table_row('Totaal', $order->get_formatted_order_total() );
	siw_wc_generate_email_table_row('Betaalwijze', $order->payment_method_title );
	?>
	</table>
	<?php
}

function siw_wc_email_show_application_details( $order ){

	//hulplijstjes
	$genders = siw_get_array('gender');
	$nationalities = siw_get_array('nationalities');
	$languages = siw_get_array('languages');
	$language_skill = siw_get_array('language_skill');

	//naam, gegeboortedatum, geslacht en nationaliteit
	$first_name = $order->billing_first_name;
	$last_name = $order->billing_last_name;
	$full_name = $first_name . ' ' . $last_name;
	$date_of_birth = $order->billing_dob;
	$gender = $genders[ $order->billing_gender ];
	$nationality = $nationalities[ $order->billing_nationality ];

	//adres formatteren
	$adress = $order->billing_address_1 . ' ' . $order->billing_housenumber . '<br/>' . $order->billing_postcode . ' ' . $order->billing_city . '<br/>' . $order->billing_country;
	$email = $order->billing_email;
	$phone = $order->billing_phone;

	//gegevens noodcontact
	$emergency_contact_name = get_post_meta( $order->id, 'emergencyContactName', true );
	$emergency_contact_phone = get_post_meta( $order->id, 'emergencyContactPhone', true );

	//talenkennis
	$language_1 = $languages[get_post_meta( $order->id, 'language1', true )];
	$language_1_skill = $language_skill[ get_post_meta( $order->id, 'language1Skill', true ) ];

	$language_2_code = get_post_meta( $order->id, 'language2', true );
	$language_2 = !empty( $language_2_code ) ? $languages[ $language_2_code ] : '';
	$language_2_skill_code = get_post_meta( $order->id, 'language2Skill', true );
	$language_2_skill = !empty( $language_2_skill_code ) ? $language_skill[ $language_2_skill_code ] : '';

	$language_3_code = get_post_meta( $order->id, 'language3', true );
	$language_3 = !empty( $language_3_code ) ? $languages[ $language_3_code ] : '';
	$language_3_skill_code = get_post_meta( $order->id, 'language3Skill', true );
	$language_3_skill = !empty( $language_3_skill_code ) ? $language_skill[ $language_3_skill_code ] : '';

	//gegevens voor PO
	$motivation = get_post_meta( $order->id, 'motivation', true );
	$health_issues = get_post_meta( $order->id, 'healthIssues', true );
	$volunteer_experience = get_post_meta( $order->id, 'volunteerExperience', true );
	$together_with = get_post_meta( $order->id, 'togetherWith', true );
	?>

	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<?php
	//Persoonsgegevens
	siw_wc_generate_email_table_header_row('Persoonsgegevens');
	siw_wc_generate_email_table_row('Naam', $full_name );
	siw_wc_generate_email_table_row('Geboortedatum', $date_of_birth );
	siw_wc_generate_email_table_row('Geslacht', $gender );
	siw_wc_generate_email_table_row('Nationaliteit', $nationality );
	siw_wc_generate_email_table_row('Adres', $adress );
	siw_wc_generate_email_table_row('E-mailadres', $email );
	siw_wc_generate_email_table_row('Telefoonnummer', $phone );

	//gegevens noodcontact
	siw_wc_generate_email_table_header_row('Noodcontact');
	siw_wc_generate_email_table_row('Naam', $emergency_contact_name );
	siw_wc_generate_email_table_row('Telefoonnummer', $emergency_contact_phone );

	//talenkennis
	siw_wc_generate_email_table_header_row('Talenkennis');
	siw_wc_generate_email_table_row( $language_1, $language_1_skill );
	if ( $language_2_code ){
		siw_wc_generate_email_table_row( $language_2, $language_2_skill );
	}
	if ( $language_3_code ){
		siw_wc_generate_email_table_row( $language_3, $language_3_skill );
	}

	//gegevens voor PO
	siw_wc_generate_email_table_header_row('Informatie voor partnerorganisatie');
	siw_wc_generate_email_table_row('Motivation', $motivation );
	if ( $health_issues ){
		siw_wc_generate_email_table_row('Health issues', $health_issues );
	}
	if ( $volunteer_experience ){
		siw_wc_generate_email_table_row('Volunteer experience', $volunteer_experience );
	}
	if ( $together_with ){
		siw_wc_generate_email_table_row('Together with', $together_with );
	}
	?>
	</table>
<?php
}

//
add_action ('siw_send_projects_for_approval_email', 'siw_send_projects_for_approval_email');
function siw_send_projects_for_approval_email(){

	$projects_for_approval = array();
	$unassigned_projects_for_approval = '';
	//zoek zichtbare en toegestane projecten met status 'draft'
	$meta_query_args = array(
		'relation'	=>	'AND',
		array(
			'key'		=>	'_visibility',
			'value'		=>	'visible',
			'compare'	=>	'='
		),
		array(
			'key'		=>	'allowed',
			'value'		=>	'yes',
			'compare'	=>	'=' //TODO: is dit nodig?
		),
	);
	$args = array(
		'posts_per_page'	=> -1,
		'post_type'			=> 'product',
		'post_status'		=> 'draft',
		'meta_query'		=> $meta_query_args,
		'fields' 			=> 'ids'
	);
	$project_ids = get_posts( $args );

	//maak message per regiospecialist/e-mailadres aan

	//TODO: Link naar zoekresultaten bijv: https://local.siw.nl/wp-admin/edit.php?s=18699%2C18741&post_type=product&action=-1
	foreach ( $project_ids as $project_id ){
		$country = get_post_meta( $project_id, 'land', true );
		$project = wc_get_product( $project_id );
	  	$project_code = $project->get_sku();
		$regiospecialist_id = siw_get_setting( $country . '_regiospecialist');
		//$email = siw_get_setting( $country . '_email');


		$project_name = get_the_title( $project_id );
		$admin_link ='<a href="' . admin_url( 'post.php?post=' . $project_id . '&action=edit' ) . '">' . $project_code . '-' . $project_name . '<a/><br/>';

		if ( '' != $regiospecialist_id){
			if ( !isset( $projects_for_approval[ $regiospecialist_id ] ) ){
				$projects_for_approval[ $regiospecialist_id ] = $admin_link;
			}
			else{
				$projects_for_approval[ $regiospecialist_id ] .= $admin_link;
			}
		}
		else{
			$unassigned_projects_for_approval .= $admin_link;
		}
	}


	//zoek e-mailadres coördinator op
	$supervisor_id = siw_get_setting('plato_import_supervisor');//TODO: fallback als dit niets oplevert
	$supervisor = get_userdata( $supervisor_id );
	$supervisor_email = $supervisor->user_email;
	$supervisor_first_name = $supervisor->first_name;

	//zet headers
	$headers = array(
		'Content-Type: text/html; charset=UTF-8',
		'From: SIW website <webmaster@siw.nl>',
		'CC: ' . $supervisor_email,
	);

	//verstuur een e-mail naar de regiospecialist met links naar te beoordelen projecten
	foreach ( $projects_for_approval as $regiospecialist_id => $projectlist ){
		$user_info = get_userdata( $regiospecialist_id );
		$first_name = $user_info->first_name;
		$email = $user_info->user_email;
		$subject = 'Nog te beoordelen projecten';
		$message = 'Beste ' . $first_name . ',<br/><br/>';
		$message .= 'De volgende projecten wachten op jouw beoordeling:<br/><br/>' . $projectlist;
		wp_mail( $email, $subject, $message, $headers );
	}

	//als er te beoordelen projecten zijn die niet aan een regiospecialist zijn toegewezen stuur dan een mail naar de coördinator
	//zet headers
	$headers = array(
		'Content-Type: text/html; charset=UTF-8',
		'From: SIW website <webmaster@siw.nl>',
	);

	if ( !empty( $unassigned_projects_for_approval ) ){
		$subject = 'Nog te beoordelen projecten';
		$message = 'Beste ' . $supervisor_first_name . ',<br/><br/>';
		$message .= 'De volgende projecten wachten op beoordeling, maar zijn niet toegewezen aan een regiospecialist:<br/>' . $unassigned_projects_for_approval;
		wp_mail( $supervisor_email, $subject, $message, $headers );
	}

}

