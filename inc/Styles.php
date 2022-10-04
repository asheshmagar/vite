<?php

namespace Theme;

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

	public function register() {
		wp_register_style( 'theme-normalize', THEME_ASSETS_URI . 'dist/normalize.css', [], THEME_VERSION );
		wp_register_style( 'theme-global', THEME_ASSETS_URI . 'dist/global.css', [ 'theme-normalize' ], THEME_VERSION );
		wp_register_style( 'theme-header', THEME_ASSETS_URI . 'dist/header.css', [], THEME_VERSION );
		wp_register_style( 'theme-page-header', THEME_ASSETS_URI . 'dist/page-header.css', [], THEME_VERSION );
		wp_register_style( 'theme-content', THEME_ASSETS_URI . 'dist/content.css', [], THEME_VERSION );
		wp_register_style( 'theme-footer', THEME_ASSETS_URI . 'dist/footer.css', [], THEME_VERSION );
	}

	public function enqueue() {
		wp_enqueue_style( 'theme-global' );
	}

	public function print_styles( ...$args ) {
		wp_print_styles( $args );
	}
}
