<?php
/**
 * The template for displaying single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 * @package Vite
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$core = vite( 'core' );

get_header();
?>
	<main
	<?php
	$core->print_html_attributes(
		'single/main',
		[
			'id'    => 'main',
			'class' => [ 'vite-main' ],
		]
	);
	?>
	>
		<?php
			/**
			 * Action: vite/single/start.
			 *
			 * Fires before the single post.
			 *
			 * @since x.x.x
			 */
			$core->action( 'single/start' );

			/**
			 * Action: vite/single.
			 *
			 * Fires in the single post.
			 *
			 * @since x.x.x
			 */
			$core->action( 'single' );

			$core->the_loop();

			/**
			 * Action: vite/single/end.
			 *
			 * Fires after the single post.
			 *
			 * @since x.x.x
			 */
			$core->action( 'single/end' );
		?>
	</main>
	<?php get_sidebar(); ?>
	</div>
	<?php
	get_footer();
