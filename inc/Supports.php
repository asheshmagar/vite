<?php
/**
 *
 */

namespace Vite;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Support.
 */
class Supports {

	/**
	 * Init.
	 *
	 * @since x.x.x
	 */
	public function init(): void {
		add_action( 'after_setup_theme', [ $this, 'add_theme_supports' ] );
		add_action( 'after_setup_theme', [ $this, 'add_image_sizes' ] );
	}

	public function add_image_sizes() {
		add_image_size( 'theme_single', 1584, 992, true );
		add_image_size( 'theme_thumbnail', 540, 340, true );
		add_image_size( 'theme_medium', 360, 224, true );
	}

	/**
	 * Add theme supports.
	 *
	 * @return void
	 */
	public function add_theme_supports() {
		$features = [
			'html5'                               => [
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			],
			'post-thumbnails'                     => true,
			'align-wide'                          => true,
			'wp-block-styles'                     => true,
//			'editor-styles'                       => true,
//			'editor-font-sizes'                   => true,
//			'editor-color-palette'                => true,
			'custom-logo'                         => apply_filters(
				'theme_custom_logo_args',
				[
					'width'       => 150,
					'height'      => 75,
					'flex-width'  => true,
					'flex-height' => true,
				]
			),
			'custom-header'                       => true,
			'customize-selective-refresh-widgets' => true,
		];
		$features = apply_filters( 'theme_theme_supports', $features );
		foreach ( $features as $feature => $args ) {
			if ( $args && is_array( $args ) ) {
				add_theme_support( $feature, $args );
			} else {
				add_theme_support( $feature );
			}
		}
	}
}
