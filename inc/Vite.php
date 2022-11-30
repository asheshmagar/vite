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

	const ALIASES = [
		'customizer',
		'seo',
		'nav-menu',
		'sidebar',
		'comments',
		'performance',
	];

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->init();
		vite( 'core' )->action( 'init' );
	}

	/**
	 * Init.
	 *
	 * @return void
	 */
	private function init() {
		$this->init_hooks();
		$this->init_aliases();
		Supports::init();
		TemplateHooks::init();
		ScriptsStyles::init();
	}

	/**
	 * Init aliases.
	 *
	 * @return void
	 */
	private function init_aliases() {
		$aliases = vite( 'core' )->filter( 'aliases', static::ALIASES );
		foreach ( $aliases as $alias ) {
			vite( $alias )->init();
		}
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
