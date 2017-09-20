<?php
/*
(c)2015-2016 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
require_once( get_stylesheet_directory() . '/includes/class-siw-plugin.php' );
require_once( get_stylesheet_directory() . '/includes/email.php' );
require_once( get_stylesheet_directory() . '/includes/get-information.php' );
require_once( get_stylesheet_directory() . '/includes/vfb.php' );
require_once( get_stylesheet_directory() . '/includes/options.php' );


/* Icons toevoegen aan head*/
add_action('wp_head', function() { ?>
	<link rel="apple-touch-icon" sizes="180x180" href="/wp-content/themes/siw/assets/icons/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/wp-content/themes/siw/assets/icons/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="192x192" href="/wp-content/themes/siw/assets/icons/android-chrome-192x192.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/wp-content/themes/siw/assets/icons/favicon-16x16.png">
	<link rel="manifest" href="/wp-content/themes/siw/assets/icons/manifest.json">
	<link rel="mask-icon" href="/wp-content/themes/siw/assets/icons/safari-pinned-tab.svg" color="#ff9900">
	<link rel="shortcut icon" href="/wp-content/themes/siw/assets/icons/favicon.ico">
	<meta name="msapplication-config" content="/wp-content/themes/siw/assets/icons/browserconfig.xml">
<?php
});
