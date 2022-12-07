<?php
/**
 * Comments.
 *
 * @package Vite.
 * @since 1.0.0
 */

namespace Vite\Comments;

/**
 * Class Comments.
 */
class Comments {

	/**
	 * Walker comment.
	 *
	 * @var WalkerComment|null
	 */
	protected $walker_comment = null;

	/**
	 * Constructor.
	 *
	 * @param WalkerComment $walker_comment Instance of WalkerComment.
	 */
	public function __construct( WalkerComment $walker_comment ) {
		$this->walker_comment = $walker_comment;
	}

	/**
	 * Init.
	 */
	public function init() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_comments_reply_script' ] );
		vite( 'core' )->add_action( 'vite/comments', [ $this, 'comments_list' ] );
	}

	/**
	 * Enqueue comments reply script.
	 *
	 * @return void
	 */
	public function enqueue_comments_reply_script() {
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}

	/**
	 * Comments list.
	 *
	 * @return void
	 */
	public function comments_list() {
		vite( 'core' )->action( 'comments/list/start' );
		$this->comments_list_markup();
		vite( 'core' )->action( 'comments/list/end' );
	}

	/**
	 * Comments list markup.
	 *
	 * @return void
	 */
	private function comments_list_markup() {
		$comments_count = (int) get_comments_number();

		if ( have_comments() ) {
			printf(
				wp_kses_post( '<h2 class="vite-comments__title">%s</h2>' ),
				sprintf(
					esc_html(
						/* translators: %1$s: Comments count, %2$s: Post title. */
						_nx(
							'%1$s thought on &ldquo;%2$s&rdquo;',
							'%1$s thoughts on &ldquo;%2$s&rdquo;',
							$comments_count,
							'comments title',
							'vite'
						)
					),
					esc_html( number_format_i18n( $comments_count ) ),
					wp_kses_post( get_the_title() )
				)
			);
		}

		printf(
			wp_kses_post( '<ol class="vite-comments__list">%s</ol>' ),
			wp_list_comments(
				[
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 40,
					'echo'        => false,
					'walker'      => $this->walker_comment,
				]
			)
		);

		the_comments_navigation();

		if ( ! comments_open() ) {
			?>
			<p class="vite-no-comments"><?php esc_html_e( 'Comments are closed.', 'vite' ); ?></p>
			<?php
		}

		$comment_for_args = vite( 'core' )->filter(
			'comment/form/args',
			[
				'title_reply_before' => '<h2 id="reply-title" class="vite-comment-respond__title">',
				'class_container'    => 'vite-comment-respond',
			]
		);

		comment_form( $comment_for_args );
	}
}
