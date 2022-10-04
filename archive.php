<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Theme
 * @since   1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();
?>
	<main id="primary" class="site-main">
		<?php
			do_action( 'theme_before_archive' );
			theme( 'core' )->the_loop();
			do_action( 'theme_after_archive' );
		?>
	</main>
<?php
get_sidebar();
get_footer();
