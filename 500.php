<?php
/**
 * The template for 500 error page in PWA.
 *
 * @package Vite
 */

defined( 'ABSPATH' ) || exit;

get_header();

/**
 * Action: vite/pwa/500/start.
 */

vite( 'core' )->action( 'pwa/500/start' );

/**
 * Action: vite/pwa/500.
 *
 * @see \Vite\Compatibility\PWA::error()
 */
vite( 'core' )->action( 'pwa/500' );

/**
 * Action: vite/pwa/500/end.
 */
vite( 'core' )->action( 'pwa/500/end' );
?>
	</div>
<?php
get_footer();


