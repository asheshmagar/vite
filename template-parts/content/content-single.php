<?php
/**
 * Template part for displaying page content in single.php.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Vite
 * @since   1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$elements = vite( 'core' )->get_mod( 'single-header-elements' );
$core     = vite( 'core' );

/**
 * Action: vite/single/content/start.
 *
 * Fires before the single content.
 *
 * @since x.x.x
 */
$core->action( 'single/content/start' );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?><?php vite( 'seo' )->print_schema_microdata( 'article' ); ?>>
	<header class="vite-post__header">
		<?php
		/**
		 * Action: vite/single/content/header.
		 *
		 * Fires in the single content header.
		 *
		 * @param array $elements The header entry elements.
		 * @since x.x.x
		 */
		$core->action( 'single/content/header', $elements );
		?>
	</header>
	<?php $core->action( 'single/content/content/start' ); ?>
	<div class="vite-post__content">
		<?php
		/**
		 * Action: vite/single/content/content.
		 *
		 * Fires in the single content.
		 *
		 * @since x.x.x
		 */
		$core->action( 'single/content/content' );
		the_content(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'vite' ),
					[
						'span' => [
							'class' => [],
						],
					]
				),
				get_the_title()
			)
		);
		wp_link_pages(
			[
				'before' => '<div class="page-links">' . esc_html__( 'Pages', 'vite' ),
				'after'  => '</div>',
			]
		);
		?>
	</div>
	<?php $core->action( 'single/content/content/end' ); ?>
</article>
<?php
/**
 * Action: vite/single/content/end.
 *
 * Fires after the single content.
 *
 * @since x.x.x
 */
$core->action( 'single/content/end' );
