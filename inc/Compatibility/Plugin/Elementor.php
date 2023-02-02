<?php

namespace Vite\Compatibility\Plugin;

use Vite\Traits\Mods;

defined( 'ABSPATH' ) || exit;

class Elementor extends Base {

	use Mods;

	/**
	 * {@inheritDoc}
	 */
	public function init() {
		add_action( 'after_setup_theme', [ $this, 'setup' ] );
	}

	public function setup() {
		add_theme_support( 'header-footer-elementor' );
	}
}
