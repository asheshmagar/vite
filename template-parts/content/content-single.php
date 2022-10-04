<?php
/**
 * Template part for displaying page content in single.php.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Theme
 * @since   1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

theme( 'styles' )->print_styles( 'theme-content' );

$elements = apply_filters(
	'theme_content_single_elements',
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
	<?php theme( 'entry-elements' )->render_entry_elements( $elements ); ?>
</article>
