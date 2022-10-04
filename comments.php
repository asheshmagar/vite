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
?>
<div id="comments" class="comments-area">
<?php
	do_action( 'theme_before_comments' );
	do_action( 'theme_comments' );
	do_action( 'theme_after_comments' );
?>
</div><!-- #comments -->
