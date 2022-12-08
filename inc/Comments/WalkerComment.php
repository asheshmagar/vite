<?php
/**
 * WalkerComment.
 *
 * @package vite
 * @since x.x.x
 */

namespace Vite\Comments;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

use Walker_Comment;
use WP_Comment;

/**
 * Class WalkerComment.
 */
class WalkerComment extends Walker_Comment {

	/**
	 * Starts the list before the elements are added.
	 *
	 * @param string $output Used to append additional content (passed by reference).
	 * @param int    $depth  Optional. Depth of the current comment. Default 0.
	 * @param array  $args   Optional. Uses 'style' argument for type of HTML list. Default empty array.
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		$GLOBALS['comment_depth'] = $depth + 1; // phpcs:ignore

		switch ( $args['style'] ) {
			case 'div':
				break;
			case 'ol':
				$output .= '<ol class="vite-comments__list-children">' . "\n";
				break;
			case 'ul':
			default:
				$output .= '<ul class="vite-comments__list-children">' . "\n";
				break;
		}
	}

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
		<<?php echo $tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> id="comment-<?php comment_ID(); ?>" class="vite-comments__item <?php echo esc_attr( implode( ' ', get_comment_class( $this->has_children ? 'vite-comments__item--parent' : '', $comment ) ) ); ?>">
			<article id="comment-<?php comment_ID(); ?>" class="vite-comment"<?php vite( 'seo' )->print_schema_microdata( 'comment' ); ?>>
				<header class="vite-comment__header">
					<div class="vite-comment__avatar">
						<?php
							$avatar = get_avatar( $comment, $args['avatar_size'] );
							echo wp_kses_post( $avatar );
						?>
					</div>
					<div class="vite-comment__meta">
						<?php
						$comment_author_url = get_comment_author_url( $comment );
						$comment_author     = get_comment_author( $comment );
						if ( 0 !== $args['avatar_size'] ) {
							if ( ! empty( $comment_author_url ) ) {
								?>
								<a class="vite-comment__meta-author" href="<?php echo esc_url( $comment_author_url ); ?>"<?php vite( 'seo' )->print_schema_microdata( 'author' ); ?>>
									<?php echo esc_html( $comment_author ); ?>
								</a>
								<?php
							} else {
								?>
								<span class="vite-comment__meta-author"<?php vite( 'seo' )->print_schema_microdata( 'author' ); ?>><?php echo esc_html( $comment_author ); ?></span>
								<?php
							}
						}
						/* translators: 1: Comment date, 2: Comment time. */
						$comment_timestamp = sprintf( __( '%1$s at %2$s', 'vite' ), get_comment_date( '', $comment ), get_comment_time() );
						?>
						<div class="vite-comment__meta-inner">
							<?php
							printf(
								'<a class="vite-comment__meta-time" href="%s">%s</a>',
								esc_url( get_comment_link( $comment, $args ) ),
								esc_html( $comment_timestamp )
							);
							if ( current_user_can( 'edit_comment', $comment->comment_ID ) ) {
								?>
								<a class="vite-comment__meta-edit" href="<?php echo esc_url( get_edit_comment_link( $comment ) ); ?>"><?php esc_html_e( 'Edit', 'vite' ); ?></a>
								<?php
							}
							?>
						</div>
					</div>
				</header>
				<div class="vite-comment__content">
					<?php
					comment_text();
					if ( '0' === $comment->comment_approved ) {
						printf( '<em class="vite-comment-awaiting-moderation">%s</em>', esc_html__( 'Your comment is awaiting moderation.', 'vite' ) );
					}
					?>
				</div>
				<footer class="vite-comment__footer">
					<?php
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
