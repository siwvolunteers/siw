<?php
/*
(c)2015-2016 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/*backend*/
require_once('includes/siw-admin.php');
require_once('includes/siw-dashboard-widgets.php');
require_once('includes/siw-login.php');
require_once('includes/siw-options.php');
require_once('includes/siw-shortcodes.php');

/*custom post types*/
require_once('includes/siw-agenda.php');
require_once('includes/siw-jobs.php');

/*frontend functionaliteit*/
require_once('includes/siw-js-css.php');
require_once('includes/siw-display.php');
require_once('includes/siw-frontend-widgets.php');
require_once('includes/siw-newsletter.php');
require_once('includes/siw-postcode.php');
require_once('includes/siw-search.php');

/*Groepsprojecten (PLATO en WooCommerce)*/
require_once('includes/siw-woocommerce.php');
require_once('includes/siw-woocommerce-checkout.php');
require_once('includes/siw-plato-import.php');
require_once('includes/siw-plato-export.php');

/*systeemfunctionaliteit*/
require_once('includes/siw-analytics.php');
require_once('includes/siw-email.php');
require_once('includes/siw-get-information.php');
require_once('includes/siw-system.php');



