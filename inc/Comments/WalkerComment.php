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

use Vite\Traits\HTMLAttrs;
use Walker_Comment;
use WP_Comment;

/**
 * Class WalkerComment.
 */
class WalkerComment extends Walker_Comment {

	use HTMLAttrs;

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
		<<?php echo esc_html( $tag ); ?> id="comment-<?php comment_ID(); ?>" class="vite-comments__item <?php echo esc_attr( implode( ' ', get_comment_class( $this->has_children ? 'vite-comments__item--parent' : '', $comment ) ) ); ?>">
			<article
				<?php
				$this->print_html_attributes(
					'comment',
					[
						'id'    => 'comment-' . get_comment_ID(),
						'class' => [ 'vite-comment' ],
					]
				);
				?>
				>
				<header class="vite-comment__header">
					<div class="vite-comment__avatar">
						<?php
							$avatar = get_avatar( $comment, $args['avatar_size'] );
							echo wp_kses_post( $avatar );
						?>
					</div>
					<div class="vite-comment__meta">
						<div
						<?php
						$this->print_html_attributes(
							'comment/author',
							[
								'class' => [ 'vite-comment__meta-author', 'vcard' ],
							]
						);
						?>
						>
							<cite
								<?php
								$this->print_html_attributes(
									'comment/author/name',
									[
										'class' => [ 'fn' ],
									]
								);
								?>
							>
							<?php
							$comment_author_url = get_comment_author_url( $comment );
							$comment_author     = get_comment_author( $comment );
							if ( 0 !== $args['avatar_size'] ) {
								if ( ! empty( $comment_author_url ) ) {
									?>
									<a class="url" href="<?php echo esc_url( esc_url( $comment_author_url ) ); ?>" rel="external nofollow ugc">
										<?php echo esc_html( $comment_author ); ?>
									</a>
									<?php
								} else {
									?>
									<span><?php echo esc_html( $comment_author ); ?></span>
									<?php
								}
							}
							?>
							</cite>
						</div>
						<?php
						/* translators: 1: Comment date, 2: Comment time. */
						$comment_timestamp = sprintf( __( '%1$s at %2$s', 'vite' ), get_comment_date( '', $comment ), get_comment_time() );
						?>
						<div class="vite-comment__meta-inner">
							<a class="vite-comment__meta-time" href="<?php echo esc_url( get_comment_link( $comment, $args ) ); ?>">
								<time
								<?php
								$this->print_html_attributes(
									'comment/time',
									[
										'datetime' => get_comment_time( 'c' ),
									]
								);
								?>
								>
									<?php echo esc_html( $comment_timestamp ); ?>
								</time>
							</a>
							<?php
							if ( current_user_can( 'edit_comment', $comment->comment_ID ) ) {
								?>
								<a class="vite-comment__meta-edit" href="<?php echo esc_url( get_edit_comment_link( $comment ) ); ?>"><?php esc_html_e( 'Edit', 'vite' ); ?></a>
								<?php
							}
							?>
						</div>
					</div>
				</header>
				<div
				<?php
				$this->print_html_attributes(
					'comment/content',
					[
						'class' => [ 'vite-comment__content' ],
					]
				);
				?>
				>
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
