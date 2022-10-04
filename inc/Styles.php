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
		wp_register_style( 'vite-normalize', THEME_ASSETS_URI . 'dist/normalize.css', [], THEME_VERSION );
		wp_register_style( 'vite-global', THEME_ASSETS_URI . 'dist/global.css', [ 'vite-normalize' ], THEME_VERSION );
		wp_register_style( 'vite-header', THEME_ASSETS_URI . 'dist/header.css', [], THEME_VERSION );
		wp_register_style( 'vite-page-header', THEME_ASSETS_URI . 'dist/page-header.css', [], THEME_VERSION );
		wp_register_style( 'vite-content', THEME_ASSETS_URI . 'dist/content.css', [], THEME_VERSION );
		wp_register_style( 'vite-footer', THEME_ASSETS_URI . 'dist/footer.css', [], THEME_VERSION );
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
