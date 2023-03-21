<?php
/**
 * The template for offline page in PWA.
 *
 * @package Vite
 */

defined( 'ABSPATH' ) || exit;

$core = vite( 'core' );

get_header();

/**
 * Action: vite/pwa/offline/start.
 */
$core->action( 'pwa/offline/start' );

/**
 * Action: vite/pwa/offline.
 *
 * @see \Vite\Compatibility\Plugin\PWA::offline()
 */
$core->action( 'pwa/offline' );

/**
 * Action: vite/pwa/offline/end.
 */
$core->action( 'pwa/offline/end' );
?>
	</div>
<?php
get_footer();


