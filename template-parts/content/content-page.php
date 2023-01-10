<?php
/**
 * Template part for displaying page content in page.php.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Vite
 * @since   1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$elements = vite( 'core' )->get_theme_mod( 'page-header-elements' );
$core     = vite( 'core' );

/**
 * Action: vite/page/content/start.
 *
 * Fires before the page content.
 *
 * @since x.x.x
 */
$core->action( 'page/content/start' );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="vite-post__header">
	<?php
	/**
	 * Action: vite/page/content/header.
	 *
	 * Fires in the page content header.
	 *
	 * @param array $elements The header entry elements.
	 * @since x.x.x
	 */
	$core->action( 'page/content/header', $elements );
	?>
	</div>
	<div class="vite-post__content">
		<?php
		/**
		 * Action: vite/page/content/content.
		 *
		 * Fires in the page content.
		 *
		 * @since x.x.x
		 */
		$core->action( 'page/content' );
		the_content();
		wp_link_pages(
			[
				'before' => '<div class="page-links">' . __( 'Pages', 'vite' ),
				'after'  => '</div>',
			]
		);
		?>
	</div>
</article>
<?php
/**
 * Action: vite/page/content/end.
 *
 * Fires after the page content.
 *
 * @since x.x.x
 */
$core->action( 'page/content/end' );
