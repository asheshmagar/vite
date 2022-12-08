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
		vite( 'core' )->action( 'loaded' );
	}

	/**
	 * Init.
	 *
	 * @return void
	 */
	private function init() {
		$this->init_hooks();
		$this->init_aliases();

		( new TemplateHooks() )->init();
		( new ScriptsStyles() )->init();
	}

	/**
	 * Init aliases.
	 *
	 * @return void
	 */
	private function init_aliases() {
		foreach ( static::ALIASES as $alias ) {
			vite( $alias )->init();
		}
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
}
