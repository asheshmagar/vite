<?php
/**
 * WalkerComment.
 *
 * @package vite
 * @since x.x.x
 */

namespace Vite;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

use Walker_Comment;
use WP_Comment;

/**
 * Class WalkerComment.
 */

class WalkerComment extends Walker_Comment {

	/**
	 * {@inheritDoc}
	 *
	 * @param WP_Comment $comment Comment to display.
	 * @param int        $depth   Depth of the current comment.
	 * @param array      $args    An array of arguments.
	 * @return void
	 */
	protected function html5_comment( $comment, $depth, $args ) {
		$tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
		?>
		<<?php echo $tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( $this->has_children ? 'parent' : '', $comment ); ?>>
			<article id="comment-<?php comment_ID(); ?>" class="comment">
				<header class="comment-header">
					<div class="comment-avatar">
						<?php
							$avatar = get_avatar( $comment, $args['avatar_size'] );
							echo wp_kses_post( $avatar );
						?>
					</div>
					<div class="comment-meta">
						<?php
						$comment_author_url = get_comment_author_url( $comment );
						$comment_author     = get_comment_author( $comment );
						if ( 0 !== $args['avatar_size'] ) {
							if ( ! empty( $comment_author_url ) ) {
								printf( '<a href="%s" rel="external nofollow" class="url">%s</a>', $comment_author_url, esc_html( $comment_author ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped --Escaped in https://developer.wordpress.org/reference/functions/get_comment_author_url/
							}
						}
						/* translators: 1: Comment date, 2: Comment time. */
						$comment_timestamp = sprintf( __( '%1$s at %2$s', 'vite' ), get_comment_date( '', $comment ), get_comment_time() );
						?>
						<a href="<?php echo esc_url( get_comment_link( $comment, $args ) ); ?>">
							<time datetime="<?php comment_time( 'c' ); ?>" title="<?php echo esc_attr( $comment_timestamp ); ?>">
								<?php echo esc_html( $comment_timestamp ); ?>
							</time>
						</a>
					</div>
					<?php
					if ( '0' === $comment->comment_approved ) {
						?>
						<p class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'vite' ); ?></p>
						<?php
					}
					?>
				</header>
				<div class="comment-content">
					<?php comment_text(); ?>
				</div>
				<footer class="comment-footer">
					<?php
					edit_comment_link( __( 'Edit', 'vite' ) );
					comment_reply_link(
						array_merge(
							$args,
							array(
								'add_below' => 'comment',
								'depth'     => $depth,
								'max_depth' => $args['max_depth'],
							)
						)
					);
					?>
				</footer>
			</article>
		<?php
	}
}
