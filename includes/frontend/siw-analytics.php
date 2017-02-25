<?php
/*
(c)2015 SIW Internationale Vrijwilligersprojecten
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


///vervang Google Analytics functie door custom functie
add_action( 'wp_footer', 'siw_add_google_analytics' );

function siw_add_google_analytics() {
	$google_analytics_id = siw_get_setting('google_analytics_id');
	$google_analytics_enable_linkid = siw_get_setting('google_analytics_enable_linkid');

	if( $google_analytics_id ){?>
		<script>
		(function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
			function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
			e=o.createElement(i);r=o.getElementsByTagName(i)[0];
			e.src='//www.google-analytics.com/analytics.js';
			r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
			ga('create','<?php echo $google_analytics_id; ?>',{'siteSpeedSampleRate': 100});
			ga('set', 'anonymizeIp', true);
			ga('set', 'forceSSL', true);
			<?php if ( $google_analytics_enable_linkid ){?>
			ga('require', 'linkid', {
				'levels': 5
			});
			<?php }?>
			ga('send','pageview');
		</script><?php
	}

}
