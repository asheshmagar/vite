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

	/**
	 * Add image sizes.
	 *
	 * @return void
	 */
	public function add_image_sizes() {
		add_image_size( 'vite-featured-image-large', 1584, 992, true );
		add_image_size( 'vite-featured-image', 540, 340, true );
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
			'editor-styles'                       => true,
			'custom-logo'                         => apply_filters(
				'vite_custom_logo_args',
				[
					'width'       => 150,
					'height'      => 75,
					'flex-width'  => true,
					'flex-height' => true,
				]
			),
			'customize-selective-refresh-widgets' => true,
		];
		$features = apply_filters( 'vite_vite_supports', $features );
		foreach ( $features as $feature => $args ) {
			if ( $args && is_array( $args ) ) {
				add_theme_support( $feature, $args );
			} else {
				add_theme_support( $feature );
			}
		}
	}
}
