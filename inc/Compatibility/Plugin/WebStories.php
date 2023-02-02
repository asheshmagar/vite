<?php
/**
 * WebStories compatibility.
 *
 * @package Vite
 */

namespace Vite\Compatibility\Plugin;

defined( 'ABSPATH' ) || exit;

/**
 * WebStories compatibility.
 */
class WebStories extends Base {

	/**
	 * {@inheritDoc}
	 */
	public function init() {
		add_action( 'after_setup_theme', [ $this, 'setup' ] );
		add_action( 'vite/body/start', [ $this, 'embed' ] );
	}

	/**
	 * Setup WebStories.
	 *
	 * @return void
	 */
	public function setup() {
		add_theme_support( 'web-stories' );
	}

	/**
	 * Embed theme stories.
	 *
	 * @return void
	 */
	public function embed() {
		function_exists( 'Google\Web_Stories\render_theme_stories' ) && \Google\Web_Stories\render_theme_stories();
	}
}
