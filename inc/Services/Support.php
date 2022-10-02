<?php
/**
 *
 */

namespace Theme\Services;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Support.
 */
class Support extends Service {

	public const FEATURES = [
		'html5'       => [
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		],
		'post-thumbnails',
		'align-wide',
		'wp-block-styles',
		'editor-styles',
		'editor-font-sizes',
		'editor-color-palette',
		'custom-logo' => [
			'width'       => 150,
			'height'      => 75,
			'flex-width'  => true,
			'flex-height' => true,
		],
		'custom-header',
		'customize-selective-refresh-widgets',
	];

	/**
	 * {inheritdoc}
	 */
	public function init(): void {
		add_action( 'after_setup_theme', [ $this, 'add_theme_supports' ] );
	}

	/**
	 * Add theme supports.
	 *
	 * @return void
	 */
	public function add_theme_supports() {
		$features = apply_filters( 'theme_supports', self::FEATURES );
		foreach ( $features as $feature => $args ) {
			if ( $args && is_array( $args ) ) {
				add_theme_support( $feature, $args );
			} else {
				add_theme_support( $feature );
			}
		}
	}
}
