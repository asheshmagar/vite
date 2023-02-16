<?php
/**
 *
 */

namespace Vite;

defined( 'ABSPATH' ) || exit;

use Vite\Traits\Mods;

/**
 * Class Performance.
 */
class Performance {

	use Mods;

	/**
	 * Holds WebFontLoader.
	 *
	 * @var Performance|null
	 */
	public $local_font = null;

	/**
	 * Constructor.
	 *
	 * @param WebFontLoader $local_font Hold instance of WebFontLoader.
	 */
	public function __construct( WebFontLoader $local_font ) {
		$this->local_font = $local_font;
	}

	/**
	 * Init.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'init', [ $this, 'disable_emoji_script' ] );
	}

	/**
	 * Disable emoji script.
	 *
	 * @return void
	 */
	public function disable_emoji_script() {
		if ( $this->get_mod( 'emoji-script', true ) ) {
			return;
		}

		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		add_filter(
			'tiny_mce_plugins',
			function( $plugins ) {
				if ( is_array( $plugins ) ) {
					return array_diff( $plugins, array( 'wpemoji' ) );
				} else {
					return [];
				}
			}
		);
		add_filter(
			'wp_resource_hints',
			function( $urls, $relation_type ) {
				if ( 'dns-prefetch' === $relation_type ) {

					/**
					 * Filters the URL to the emoji SVG image file.
					 *
					 * @since 1.0.0
					 */
					$emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/' );
					$urls          = array_diff( $urls, array( $emoji_svg_url ) );
				}
				return $urls;
			},
			10,
			2
		);
	}
}
