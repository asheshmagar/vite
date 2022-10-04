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

vite( 'styles' )->print_styles( 'vite-content' );

$elements = apply_filters(
	'theme_content_page_elements',
	[
		'thumbnail',
		'header',
		'meta',
		'breadcrumbs',
		'content',
	]
);
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php vite( 'entry-elements' )->render_entry_elements( $elements ); ?>
</article>
