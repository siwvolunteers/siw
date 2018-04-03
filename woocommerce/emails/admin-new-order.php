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

do_action( 'woocommerce_email_header', $email_heading, $email );

do_action( 'siw_woocommerce_email_order_admin', $order );

do_action( 'siw_woocommerce_email_order_table', $order );


/**
  * @hooked WC_Emails::email_footer() Output the email footer
  */
 do_action( 'woocommerce_email_footer', $email ); ?>
