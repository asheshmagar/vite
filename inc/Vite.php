<?php
/**
 * Class Theme.
 *
 * @since x.x.x
 * @package Vite
 */

namespace Vite;

defined( 'ABSPATH' ) || exit;

/**
 * Class Theme.
 */
class Vite {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Init.
	 *
	 * @return void
	 */
	private function init() {
		vite( 'supports' )->init();
		vite( 'nav-menu' )->init();
		vite( 'template-hooks' )->init();
		vite( 'styles' )->init();
		vite( 'scripts' )->init();
		$this->init_hooks();
	}

	/**
	 * Init hooks.
	 *
	 * @return void
	 */
	private function init_hooks() {
		add_action( 'after_setup_theme', [ $this, 'load_textdomain' ] );
	}

	/**
	 * Load text domain.
	 *
	 * @return void
	 */
	public function load_textdomain() {
		load_theme_textdomain( 'vite', get_template_directory() . '/languages' );
	}
}