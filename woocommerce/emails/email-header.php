<?php
/**
 * Email Header
 *
 * @author  WooThemes
 * @package WooCommerce/Templates/Emails
 * @version 2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$site_url = get_site_url();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title><?php echo $email_heading; ?></title>
	</head>
	<body <?php echo is_rtl() ? 'rightmargin' : 'leftmargin'; ?>="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" bgcolor="#ffffff">
		<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" bgcolor="#eeeeee">
			<tr>
				<td align="center">&nbsp;</td>
			</tr>
			<tr>
				<td align="center" valign="top">
					<table border="0" cellpadding="0" cellspacing="0" width="600" bgcolor="#ffffff" style="border-radius:3px !important">
						<tr>
							<td align="center">&nbsp;</td>
						</tr>
						<tr>
							<td>
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td width="10%">&nbsp;</td>
										<td width="20%"><a href="<?php echo $site_url; ?>/" target="_blank"><img src="<?php echo SIW_ASSETS_URL; ?>images/mail/logo.png" width="144" height="76" border="0" alt="logo" title="Bezoek onze website"/></a></td>
										<td width="60%" style="vertical-align:bottom;border-bottom: solid #ff9900;font-family:Verdana, normal; color:#666666; font-size:0.95em; font-weight:bold;" align="center">
											<?php echo $email_heading; ?>
										</td>
										<td width="10%">&nbsp;</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td align="center">&nbsp;</td>
						</tr>
						<tr>
							<td>
							<!-- Body -->
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td width="10%">&nbsp;</td>
										<td width="80%">
