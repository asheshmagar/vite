<?php

namespace Vite;

class Footer {

	/**
	 * Print footer.
	 *
	 * @return void
	 */
	public function render_footer_bar() {
		$content = vite( 'customizer' )->get_setting( 'footer-bar-html', '' );
		$content = vite( 'core' )->parse_smart_tags( $content );
		?>

		<?php
	}

	public function render_footer() {
		?>
		<footer class="site-footer vite-site-footer">

		</footer>
		<?php
	}
}
