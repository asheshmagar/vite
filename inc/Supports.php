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
	 * Holds the instance of this class.
	 *
	 * @var null|Supports
	 */
	private static $instance = null;

	/**
	 * Init.
	 *
	 * @return Supports|null
	 */
	public static function init(): ?Supports {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Supports constructor.
	 *
	 * @since x.x.x
	 */
	public function __construct() {
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
			'custom-logo'                         => vite( 'core' )->filter(
				'custom-logo/args',
				[
					'width'       => 150,
					'height'      => 75,
					'flex-width'  => true,
					'flex-height' => true,
				]
			),
			'customize-selective-refresh-widgets' => true,
			'title-tag'                           => true,
			'automatic-feed-links'                => true,
		];

		/**
		 * Filter: vite/supports.
		 *
		 * Filter the theme supports.
		 *
		 * @since x.x.x
		 * @param array $features Theme supports.
		 */
		$features = vite( 'core' )->filter( 'supports', $features );

		foreach ( $features as $feature => $args ) {
			if ( $args && is_array( $args ) ) {
				add_theme_support( $feature, $args );
			} else {
				add_theme_support( $feature );
			}
		}
	}
}
