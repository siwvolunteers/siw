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

?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title><?php echo $email_heading; ?></title>
	</head>
    <body <?php echo is_rtl() ? 'rightmargin' : 'leftmargin'; ?>="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" bgcolor="#eeeeee">
	<link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css">
    	<div>
        	<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" bgcolor="#eeeeee">
            	<tr>
                	<td align="center" valign="top">
                    	<table border="0" cellpadding="0" cellspacing="0" width="600" bgcolor="#ffffff">
							<tr>
								<td align="center">&nbsp;</td>
							</tr>
							<tr>
								<td>
									<table width="100%" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td width="10%">&nbsp;</td>
											<td width="20%"><a href="http://www.siw.nl/" target="_blank"><img src="http://www.siw.nl/wp-content/themes/pinnacle_child/assets/images/mail/logo.jpg" width="144" height="76" border="0" alt="logo" title="Bezoek onze website"/></a></td>
											<td width="60%">
												<table width="100%" border="0" cellspacing="0" cellpadding="0">
													<tr>
														<td height="38" align="right" valign="top">
															<table width="100%" border="0" cellspacing="0" cellpadding="0">
																<tr>
																	<td width="auto" align="center"><a href= "http://www.siw.nl/bestemmingen" target="_blank" style="text-decoration:none"><font style="font-family:'Open Sans', Verdana, normal; color:#444444; font-size:9px; text-transform:uppercase">Bestemmingen</font></a></td>
																	<td width="2%" align="center"><font style="font-family:'Open Sans', Verdana, normal; font-size:9px; text-transform:uppercase">|</font></td>
																	<td width="auto" align="center"><a href= "http://www.siw.nl/zo-werkt-het" target="_blank" style="text-decoration:none"><font style="font-family:'Open Sans', Verdana, normal; color:#444444; font-size:9px; text-transform:uppercase">Zo werkt het</font></a></td>
																	<td width="2%" align="center"><font style="font-family:'Open Sans', Verdana, normal; color:#444444; font-size:9px; text-transform:uppercase">|</font></td>
																	<td width="auto" align="center"><a href= "http://www.siw.nl/over-siw" target="_blank" style="text-decoration:none"><font style="font-family:'Open Sans', Verdana, normal; color:#444444; font-size:9px; text-transform:uppercase">Over SIW</font></a></td>
																	<td width="2%" align="center"><font style="font-family:'Open Sans', Verdana, normal; color:#444444; font-size:9px; text-transform:uppercase">|</font></td>
																	<td width="auto" align="center"><a href= "http://www.siw.nl/contact" target="_blank" style="text-decoration:none"><font style="font-family:'Open Sans', Verdana, normal; color:#444444; font-size:9px; text-transform:uppercase">Contact</font></a></td>
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
                            	<td align="center" valign="top">
                                    <!-- Body -->
                                	<table border="0" cellpadding="0" cellspacing="0" width="600" id="template_body">
                                    	<tr>
                                            <td valign="top" id="body_content">
                                                <!-- Content -->
                                                <table border="0" cellpadding="20" cellspacing="0" width="100%">
                                                    <tr>
                                                        <td valign="top">
                                                            <div id="body_content_inner">
