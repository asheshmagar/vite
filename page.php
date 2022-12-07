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
	<main id="main" class="vite-main">
		<?php
			/**
			 * Action: vite/page/start.
			 *
			 * Fires before the page.
			 *
			 * @since x.x.x
			 */
			$core->action( 'page/start' );

			/**
			 * Action: vite/page.
			 *
			 * Fires in the page.
			 *
			 * @since x.x.x
			 */
			$core->action( 'page' );

			$core->the_loop();

			/**
			 * Action: vite/page/end.
			 *
			 * Fires after the page.
			 *
			 * @since x.x.x
			 */
			$core->action( 'page/end' );
		?>
	</main>
	<?php get_sidebar(); ?>
	</div>
	<?php
	get_footer();
