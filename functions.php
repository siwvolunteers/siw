<?php
/*
(c)2015-2016 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/*backend*/
require_once('includes/admin/siw-admin.php');
require_once('includes/admin/siw-dashboard-widgets.php');
require_once('includes/admin/siw-login.php');
require_once('includes/admin/siw-options.php');
require_once('includes/admin/siw-shortcodes.php');

/*custom post types*/
require_once('includes/post-types/siw-agenda.php');
require_once('includes/post-types/siw-jobs.php');

/*frontend functionaliteit*/
require_once('includes/frontend/siw-analytics.php');
require_once('includes/frontend/siw-display.php');
require_once('includes/frontend/siw-frontend-widgets.php');
require_once('includes/frontend/siw-js-css.php');
require_once('includes/frontend/siw-newsletter.php');
require_once('includes/frontend/siw-postcode.php');

/*groepsprojecten (PLATO en WooCommerce)*/
require_once('includes/woocommerce/siw-woocommerce.php');
require_once('includes/woocommerce/siw-woocommerce-email.php');
require_once('includes/woocommerce/siw-woocommerce-checkout.php');
require_once('includes/woocommerce/siw-woocommerce-import.php');
require_once('includes/woocommerce/siw-woocommerce-export.php');

/*systeemfunctionaliteit*/
require_once('includes/system/siw-cron.php');
require_once('includes/system/siw-email.php');
require_once('includes/system/siw-get-information.php');
require_once('includes/system/siw-search.php');
require_once('includes/system/siw-system.php');



