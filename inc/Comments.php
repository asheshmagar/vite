<?php
/**
 * Comments.
 *
 * @package Vite.
 * @since 1.0.0
 */

namespace Vite;

/**
 * Class Comments.
 */
class Comments {

	/**
	 * Init.
	 */
	public function init(): void {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_comments_reply_script' ] );
		add_action( 'vite_comments', [ $this, 'comments_list' ] );
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
		do_action( 'vite_before_comments_list' );
		$this->comments_list_markup();
		do_action( 'vite_after_comments_list' );
	}

	/**
	 * Comments list markup.
	 *
	 * @return void
	 */
	public function comments_list_markup() {
		$comments_count = (int) get_comments_number();

		if ( have_comments() ) {
			printf(
				wp_kses_post( '<h2 class="comments-title">%s</h2>' ),
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
			wp_kses_post( '<ol class="comment-list">%s</ol>' ),
			wp_list_comments(
				[
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 40,
					'echo'        => false,
					'walker'      => new WalkerComment(),
				]
			)
		);

		the_comments_navigation();

		if ( ! comments_open() ) {
			?>
			<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'vite' ); ?></p>
			<?php
		}

		comment_form();
	}
}
