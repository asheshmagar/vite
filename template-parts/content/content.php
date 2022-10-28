<?php
/**
 * Template part for displaying posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Vite
 * @since   1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$elements = vite( 'customizer' )->get_setting( 'archive-elements' );

$visible_elements = array_filter(
	$elements,
	function( $element ) {
		return $element['visible'];
	}
);
$first_element    = reset( $visible_elements );
$last_element     = end( $visible_elements );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> data-first-element="<?php echo esc_attr( $first_element['id'] ?? 'false' ); ?>" data-last-element="<?php echo esc_attr( $last_element['id'] ?? 'false' ); ?>">
	<div class="post-wrap">
		<?php vite( 'entry-elements' )->render_entry_elements( $elements ); ?>
	</div>
</article>
