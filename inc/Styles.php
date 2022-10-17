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
		wp_register_style( 'vite-style', VITE_ASSETS_URI . 'dist/style.css', [], VITE_VERSION );
		// wp_register_style( 'vite-global', VITE_ASSETS_URI . 'dist/global.css', [ 'vite-normalize' ], VITE_VERSION );
		// wp_register_style( 'vite-header', VITE_ASSETS_URI . 'dist/header.css', [], VITE_VERSION );
		// wp_register_style( 'vite-page-header', VITE_ASSETS_URI . 'dist/page-header.css', [], VITE_VERSION );
		// wp_register_style( 'vite-content', VITE_ASSETS_URI . 'dist/content.css', [], VITE_VERSION );
		// wp_register_style( 'vite-footer', VITE_ASSETS_URI . 'dist/footer.css', [], VITE_VERSION );
	}

	/**
	 * Enqueue.
	 *
	 * @return void
	 */
	public function enqueue() {
		$dynamic_css = vite( 'dynamic-css' )->init();
		wp_enqueue_style( 'vite-style' );
		wp_add_inline_style( 'vite-style', $dynamic_css->make()->get() );
//		$dynamic_css = $upload_dir['basedir'] . '/vite/vite-dynamic.css';
//		if ( file_exists( $dynamic_css ) ) {
////			wp_enqueue_style( 'vite-dynamic', "{$upload_dir['baseurl']}/vite/vite-dynamic.css", [ 'vite-style' ], VITE_VERSION );
//		}
		// wp_enqueue_style( 'vite-header' );
		// wp_enqueue_style( 'vite-page-header' );
		// wp_enqueue_style( 'vite-content' );
		// wp_enqueue_style( 'vite-footer' );
	}

	private function get_dynamic_css() {
		$css = vite('customizer')->css->make()->get();
		error_log( print_r( $css, true ) );
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
