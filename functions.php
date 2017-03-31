<?php
/*
(c)2015-2016 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/*systeemfunctionaliteit*/
require_once('includes/system/siw-email.php');
require_once('includes/system/siw-get-information.php');
require_once('includes/system/siw-vfb.php');

/*instellingen niet laden als functionality plugin deze al geladen heeft*/
if ( ! defined('SIW_OPT_NAME') ) {
	require_once('includes/settings/siw-settings.php');
}

/*backend*/
require_once('includes/admin/siw-options.php');

/*groepsprojecten (PLATO en WooCommerce)*/
require_once('includes/woocommerce/siw-woocommerce-import.php');



if ( ! function_exists('siw_show_quick_search_widget') ) {
	function siw_show_quick_search_widget () {?>
	<div class="snelzoeken">
	<h4><?php esc_html_e('Snel zoeken','siw');?></h4>
	<?php echo do_shortcode( '[searchandfilter id="57"]');//TODO: vervangen door slug of optie?>
	</div>
	<?php
	}
}
