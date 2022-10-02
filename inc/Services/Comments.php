<?php

namespace Theme\Services;

use function is_singular;
use function comments_open;
use function get_option;

class Comments extends Service {

	/**
	 * Init service.
	 *
	 * @inheritDoc
	 */
	public function init(): void {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_comments_reply_script' ] );
	}

	/**
	 * @return void
	 */
	public function enqueue_comments_reply_script() {
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}
}
