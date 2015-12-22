<?php
/*
(c)2015 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

 
///vervang Google Analytics functie door custom functie en voeg Pingdom Real Use Monitoring toe
add_action( 'after_setup_theme', 'siw_after_theme_setup' );
function siw_after_theme_setup() {
	$google_analytics_id = siw_get_google_analytics_id();
	define('GOOGLE_ANALYTICS_ID', $google_analytics_id); 
	if (GOOGLE_ANALYTICS_ID) {
		remove_action('wp_footer', 'kadence_google_analytics', 20);
		add_action( 'wp_footer', 'siw_google_analytics', 20 );
	}
	
	$pingdom_rum_id = siw_get_pingdom_rum_id();
	define('PINGDOM_RUM_ID', $pingdom_rum_id );
	if ( PINGDOM_RUM_ID ){
		add_action('wp_head','siw_pingdom_rum');
	}
	
}

function siw_google_analytics() {?>
<script>
(function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
	function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
	e=o.createElement(i);r=o.getElementsByTagName(i)[0];
	e.src='//www.google-analytics.com/analytics.js';
	r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
	ga('create','<?php echo GOOGLE_ANALYTICS_ID; ?>',{'siteSpeedSampleRate': 100});
	ga('set', 'anonymizeIp', true);
	ga('send','pageview');
</script>
<?php }

function siw_pingdom_rum() {?>
<script>
var _prum = [['id', '<?php echo PINGDOM_RUM_ID; ?>'],
             ['mark', 'firstbyte', (new Date()).getTime()]];
(function() {
    var s = document.getElementsByTagName('script')[0]
      , p = document.createElement('script');
    p.async = 'async';
    p.src = '//rum-static.pingdom.net/prum.min.js';
    s.parentNode.insertBefore(p, s);
})();
</script>
<?php }