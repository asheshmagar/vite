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
$core             = vite( 'core' );

/**
 * Action: vite/content/start.
 *
 * Fires before the content.
 *
 * @since x.x.x
 */
$core->action( 'archive/content/start' );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> data-first-element="<?php echo esc_attr( $first_element['id'] ?? 'false' ); ?>" data-last-element="<?php echo esc_attr( $last_element['id'] ?? 'false' ); ?>"<?php vite( 'seo' )->print_schema_microdata( 'article' ); ?>>
	<div class="post-wrap">
		<?php
		/**
		 * Action: vite/content.
		 *
		 * Fires in the content.
		 *
		 * @param array $elements The entry elements.
		 * @since 1.0.0
		 */
		$core->action( 'archive/content', $elements );

		vite( 'entry-elements' )->render_entry_elements( $elements );
		?>
	</div>
</article>
<?php
/**
 * Action: vite/content/end.
 *
 * Fires after the content.
 *
 * @since 1.0.0
 */
$core->action( 'archive/content/end' );
