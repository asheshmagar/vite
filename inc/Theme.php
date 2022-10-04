<?php
/**
 * Class Theme.
 *
 * @since x.x.x
 * @package Theme
 */

namespace Theme;

defined( 'ABSPATH' ) || exit;

/**
 * Class Theme.
 */
class Theme {

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
		theme( 'supports' )->init();
		theme( 'nav-menu' )->init();
		theme( 'template-hooks' )->init();
		theme( 'styles' )->init();
		theme( 'scripts' )->init();
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
		load_theme_textdomain( 'theme', get_template_directory() . '/languages' );
	}
}
