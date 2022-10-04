<?php
/**
 * The template for displaying page.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 * @package Theme
 */

namespace Theme;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();
?>
	<main id="primary" class="site-main">
		<?php
			do_action( 'theme_before_page' );
			theme( 'core' )->the_loop();
			do_action( 'theme_after_page' );
		?>
	</main>
	<?php get_sidebar(); ?>
	</div>
	<?php
	get_footer();
