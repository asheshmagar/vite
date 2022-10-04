<?php
/**
 * The template for displaying single posts.
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
			do_action( 'theme_before_single' );
			vite( 'core' )->the_loop();
			do_action( 'theme_after_single' );
		?>
	</main>
	<?php get_sidebar(); ?>
	</div>
	<?php
	get_footer();
