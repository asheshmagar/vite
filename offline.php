<?php
/**
 * The template for offline page in PWA.
 *
 * @package Vite
 */

defined( 'ABSPATH' ) || exit;

get_header();

/**
 * Action: vite/pwa/offline/start.
 */
vite( 'core' )->action( 'pwa/offline/start' );

/**
 * Action: vite/pwa/offline.
 *
 * @see \Vite\Compatibility\Plugin\PWA::offline()
 */
vite( 'core' )->action( 'pwa/offline' );

/**
 * Action: vite/pwa/offline/end.
 */
vite( 'core' )->action( 'pwa/offline/end' );
?>
	</div>
<?php
get_footer();


