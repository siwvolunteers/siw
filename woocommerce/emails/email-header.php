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
$site_url = site_url('', 'http' );
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title><?php echo $email_heading; ?></title>
	</head>
    <body <?php echo is_rtl() ? 'rightmargin' : 'leftmargin'; ?>="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" bgcolor="#ffffff">
	<link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css">
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
										<td width="20%"><a href="<?php echo $site_url; ?>/" target="_blank"><img src="<?php echo $site_url; ?>/wp-content/themes/siw/assets/images/mail/logo.jpg" width="144" height="76" border="0" alt="logo" title="Bezoek onze website"/></a></td>
										<td width="60%">
											<table width="100%" border="0" cellspacing="0" cellpadding="0">
												<tr>
													<td height="38" align="right" valign="top">
														<table width="100%" border="0" cellspacing="0" cellpadding="0">
															<tr>
																<td width="auto" align="center"><a href= "<?php echo $site_url; ?>/bestemmingen" target="_blank" style="text-decoration:none"><font style="font-family:'Open Sans', Verdana, normal; color:#444444; font-size:9px; text-transform:uppercase">Bestemmingen</font></a></td>
																<td width="2%" align="center"><font style="font-family:'Open Sans', Verdana, normal; font-size:9px; text-transform:uppercase">|</font></td>
																<td width="auto" align="center"><a href= "<?php echo $site_url; ?>/zo-werkt-het" target="_blank" style="text-decoration:none"><font style="font-family:'Open Sans', Verdana, normal; color:#444444; font-size:9px; text-transform:uppercase">Zo werkt het</font></a></td>
																<td width="2%" align="center"><font style="font-family:'Open Sans', Verdana, normal; color:#444444; font-size:9px; text-transform:uppercase">|</font></td>
																<td width="auto" align="center"><a href= "<?php echo $site_url; ?>/over-siw" target="_blank" style="text-decoration:none"><font style="font-family:'Open Sans', Verdana, normal; color:#444444; font-size:9px; text-transform:uppercase">Over SIW</font></a></td>
																<td width="2%" align="center"><font style="font-family:'Open Sans', Verdana, normal; color:#444444; font-size:9px; text-transform:uppercase">|</font></td>
																<td width="auto" align="center"><a href= "<?php echo $site_url; ?>/contact" target="_blank" style="text-decoration:none"><font style="font-family:'Open Sans', Verdana, normal; color:#444444; font-size:9px; text-transform:uppercase">Contact</font></a></td>
															</tr>
														</table>
													</td>
												</tr>
												<tr>
													<td height="38" style="vertical-align:bottom;border-bottom: solid #ff9900;" align="center">
														<font style="font-family:'Open Sans', Verdana, normal; color:#666666; font-size:13px; font-weight:bold;">
															<?php echo $email_heading; ?>
														</font>
													</td>
												</tr>
											</table>
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