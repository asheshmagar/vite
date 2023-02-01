<?php
/**
 * Class Theme.
 *
 * @since x.x.x
 * @package Vite
 */

namespace Vite;

defined( 'ABSPATH' ) || exit;

use Vite\Traits\Mods;

/**
 * Class Theme.
 */
class Vite {

	use Mods;

	/**
	 * Init.
	 *
	 * @return void
	 */
	public function init() {
		$aliases = $this->filter(
			'aliases',
			[
				'customizer',
				'nav-menu',
				'widgets',
				'comments',
				'performance',
				'scripts-styles',
				'template-hooks',
				'compatibility',
				'schema',
			]
		);

		foreach ( $aliases as $alias ) {
			vite( $alias )->init();
		}

		$this->init_hooks();
		$this->action( 'loaded' );
	}

	/**
	 * Init hooks.
	 *
	 * @return void
	 */
	private function init_hooks() {
		add_action( 'after_setup_theme', [ $this, 'setup' ] );
	}

	/**
	 * Theme setup.
	 *
	 * @return void
	 */
	public function setup() {
		$this->set_content_width();
		$this->load_textdomain();
		$this->add_theme_supports();
		$this->add_image_sizes();
	}

	/**
	 * Add theme supports.
	 *
	 * @return void
	 */
	private function add_theme_supports() {
		$features = [
			'html5'                               => [
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
				'navigation-widgets',
			],
			'post-thumbnails'                     => true,
			'align-wide'                          => true,
			'wp-block-styles'                     => true,
			'editor-styles'                       => true,
			'custom-logo'                         => $this->filter(
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
			'post-formats'                        => [
				'aside',
				'image',
				'video',
				'quote',
				'link',
				'gallery',
				'audio',
				'status',
			],
		];

		/**
		 * Filter: vite/supports.
		 *
		 * Filter the theme supports.
		 *
		 * @since x.x.x
		 * @param array $features Theme supports.
		 */
		$features = $this->filter( 'supports', $features );

		foreach ( $features as $feature => $args ) {
			if ( $args && is_array( $args ) ) {
				add_theme_support( $feature, $args );
			} else {
				add_theme_support( $feature );
			}
		}

		add_editor_style( './assets/dist/editor-style.css' );
	}

	/**
	 * Add image sizes.
	 *
	 * @return void
	 */
	private function add_image_sizes() {
		add_image_size( 'vite-featured-image-large', 1584, 992, true );
		add_image_size( 'vite-featured-image', 540, 340, true );
	}

	/**
	 * Load text domain.
	 *
	 * @return void
	 */
	private function load_textdomain() {
		load_theme_textdomain( 'vite', get_template_directory() . '/languages' );
	}

	/**
	 * Set content width.
	 *
	 * @return void
	 */
	private function set_content_width() {
		global $content_width;
		$content_width = $this->get_mod( 'container-wide-width' )['value'] ?? 1200;
	}
}
