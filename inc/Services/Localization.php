<?php
/**
 * Interface for services.
 *
 * @since x.x.x
 * @package Theme
 */

namespace Theme\Services;

/**
 * Localization.
 */
class Localization extends Service {

	public const TEXT_DOMAIN = 'theme';

	/**
	 * {inheritdoc}
	 */
	public function init(): void {
		add_action( 'after_setup_theme', [ $this, 'load_textdomain' ] );
	}

	/**
	 * Load text domain.
	 *
	 * @return void
	 */
	public function load_textdomain() {
		load_theme_textdomain( static::TEXT_DOMAIN, get_template_directory() . '/languages' );
	}
}
