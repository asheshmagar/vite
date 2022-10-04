<?php

namespace Theme;

/**
 * Scripts.
 */
class Scripts {

	/**
	 * Init.
	 *
	 * @since x.x.x
	 */
	public function init(): void {
		add_action( 'wp_head', [ $this, 'remove_no_js' ], 2 );
	}

	/**
	 * Remove no-js class from html tag.
	 */
	public function remove_no_js() {
		?>
		<script>!function(e){e.className=e.className.replace(/\bno-js\b/,"js")}(document.documentElement);</script>
		<?php
	}
}
