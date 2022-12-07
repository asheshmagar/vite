<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Vite
 * @since x.x.x
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$core = vite( 'core' );

get_header();
?>
	<main id="main" class="vite-main">
		<?php
			/**
			 * Action: vite/archive/start.
			 *
			 * Fires before the archive.
			 *
			 * @since x.x.x
			 */
			$core->action( 'archive/start' );

			/**
			 * Action: vite/archive.
			 *
			 * Fires in the archive.
			 *
			 * @since x.x.x
			 */
			$core->action( 'archive' );

			$core->the_loop();

			/**
			 * Action: vite/archive/end.
			 *
			 * Fires after the archive.
			 *
			 * @since x.x.x
			 */
			$core->action( 'archive/end' );
		?>
	</main>
	<?php
	get_sidebar();
	?>
	</div>
	<?php
	get_footer();
