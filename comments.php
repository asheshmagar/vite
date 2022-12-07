<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 * @since x.x.x
 * @package Vite
 */

defined( 'ABSPATH' ) || exit;

if ( post_password_required() ) {
	return;
}

$core = vite( 'core' );
?>
<div id="comments" class="vite-comments<?php print( esc_attr( have_comments() ? '' : ' vite-comments--empty' ) ); ?>">
<?php
	/**
	 * Action: vite/comments/start.
	 *
	 * Fires before the comments.
	 *
	 * @since x.x.x
	 */
	$core->action( 'comments/start' );

	/**
	 * Action: vite/comments.
	 *
	 * Fires in the comments' area.
	 *
	 * @see vite\Comments\Comments::comments_list()
	 * @since x.x.x
	 */
	$core->action( 'comments' );

	/**
	 * Action: vite/comments/end.
	 *
	 * Fires after the comments.
	 *
	 * @since x.x.x
	 */
	$core->action( 'comments/end' );
?>
</div>
