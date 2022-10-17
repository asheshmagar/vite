<?php
/**
 * The template for displaying page.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 * @package Vite
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();
?>
	<main id="primary" class="site-main">
		<?php
			do_action( 'vite_before_page' );
			vite( 'core' )->the_loop();
			do_action( 'vite_after_page' );
		?>
	</main>
	<?php get_sidebar(); ?>
	</div>
	<?php
	get_footer();
