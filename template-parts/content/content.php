<?php
/**
 * Template part for displaying posts.
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
	'theme_content_elements',
	[
		'thumbnail',
		'meta',
		'header',
		'summary',
		'footer',
	]
);
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="post-wrap">
		<?php theme( 'entry-elements' )->render_entry_elements( $elements ); ?>
	</div>
</article>
