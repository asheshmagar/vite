<?php
/**
 * Class Styles.
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
class Styles {

	/**
	 * Init.
	 *
	 * @since x.x.x
	 */
	public function init(): void {
		add_action( 'init', [ $this, 'register' ], 0 );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue' ] );
	}

	/**
	 * Register styles.
	 *
	 * @return void
	 */
	public function register() {
		wp_register_style( 'vite-normalize', VITE_ASSETS_URI . 'dist/normalize.css', [], VITE_VERSION );
		wp_register_style( 'vite-global', VITE_ASSETS_URI . 'dist/global.css', [ 'vite-normalize' ], VITE_VERSION );
		wp_register_style( 'vite-header', VITE_ASSETS_URI . 'dist/header.css', [], VITE_VERSION );
		wp_register_style( 'vite-page-header', VITE_ASSETS_URI . 'dist/page-header.css', [], VITE_VERSION );
		wp_register_style( 'vite-content', VITE_ASSETS_URI . 'dist/content.css', [], VITE_VERSION );
		wp_register_style( 'vite-footer', VITE_ASSETS_URI . 'dist/footer.css', [], VITE_VERSION );
	}

	/**
	 * Enqueue.
	 *
	 * @return void
	 */
	public function enqueue() {
		wp_enqueue_style( 'vite-global' );
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
