<?php
/**
 * The template for displaying page.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 * @package Vite
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$core = vite( 'core' );

get_header();
?>
<main id="main" class="site-main">
	<?php
	/**
	 * Action: vite/404/start.
	 *
	 * Fires before the 404 page.
	 *
	 * @since x.x.x
	 */
	$core->action( '404/start' );

	/**
	 * Action: vite/404.
	 *
	 * Fires in the 404 page.
	 *
	 * @since x.x.x
	 */
	$core->action( '404' );

	/**
	 * Action: vite/404/end.
	 *
	 * Fires after the 404 page.
	 *
	 * @since x.x.x
	 */
	$core->action( '404/end' );
	?>
</main>
</div>
<?php
get_footer();
