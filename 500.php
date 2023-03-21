<?php
/**
 * The template for 500 error page in PWA.
 *
 * @package Vite
 */

defined( 'ABSPATH' ) || exit;

$core = vite( 'core' );

get_header();

/**
 * Action: vite/pwa/500/start.
 */

$core->action( 'pwa/500/start' );

/**
 * Action: vite/pwa/500.
 *
 * @see \Vite\Compatibility\Plugin\PWA::error()
 */
$core->action( 'pwa/500' );

/**
 * Action: vite/pwa/500/end.
 */
$core->action( 'pwa/500/end' );
?>
	</div>
<?php
get_footer();


