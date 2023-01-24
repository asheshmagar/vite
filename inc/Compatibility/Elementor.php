<?php

namespace Vite\Compatibility;

use Vite\Traits\Mods;
use Elementor as ElementorCore;

defined( 'ABSPATH' ) || exit;

class Elementor extends AbstractCompatibility {

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
