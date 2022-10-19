<?php
/**
 * Class ScriptsStyles.
 *
 * @package Vite
 */

namespace Vite;

defined( 'ABSPATH' ) || exit;

/**
 * Class Styles
 *
 * @package Vite
 */
class ScriptsStyles {

	/**
	 * Init.
	 *
	 * @since x.x.x
	 */
	public function init(): void {
		add_action( 'init', [ $this, 'register' ], 0 );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue' ] );
		add_action( 'wp_head', [ $this, 'remove_no_js' ], 2 );
	}

	/**
	 * Register styles.
	 *
	 * @return void
	 */
	public function register() {
		wp_register_style( 'vite-style', VITE_ASSETS_URI . 'dist/style.css', [], VITE_VERSION );
	}

	/**
	 * Enqueue.
	 *
	 * @return void
	 */
	public function enqueue() {
		wp_enqueue_style( 'vite-style' );
		vite( 'dynamic-css' )->enqueue();
	}

	/**
	 * Remove no-js class from html tag.
	 */
	public function remove_no_js() {
		?>
		<script>!function(e){e.className=e.className.replace(/\bno-js\b/,"js")}(document.documentElement);</script>
		<?php
	}

	/**
	 * Print styles.
	 *
	 * @param string ...$handles One or more style handles to be printed.
	 * @return void
	 */
	public function print_styles( string ...$handles ): void {
		$registered_handles = [
			'vite-global',
			'vite-header',
			'vite-page-header',
			'vite-content',
			'vite-footer',
		];

		$handles = array_filter(
			$handles,
			function( $handle ) use ( $registered_handles ) {
				return in_array( $handle, $registered_handles, true );
			}
		);

		if ( ! empty( $handles ) ) {
			wp_print_styles( $handles );
		}
	}
}
