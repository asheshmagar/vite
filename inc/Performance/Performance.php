<?php
/**
 *
 */

namespace Vite\Performance;

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
		if ( $this->get_theme_mod( 'emoji-script', true ) ) {
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
					$emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/' );
					$urls          = array_diff( $urls, array( $emoji_svg_url ) );
				}
				return $urls;
			},
			10,
			2
		);
	}

	/**
	 * Get local font.
	 *
	 * @param array $fonts Array of fonts.
	 * @return string
	 */
	public function get_local_fonts_url( array $fonts = [] ): string {
		if ( empty( $fonts ) ) {
			return '';
		}

		$families  = array_keys( $fonts );
		$fonts_url = add_query_arg(
			[
				'family'  => implode(
					'|',
					array_map(
						function( $f ) use ( $fonts ) {
							return $f . ':' . implode( ',', $fonts[ $f ] );
						},
						$families
					)
				),
				'display' => 'swap',
			],
			'https://fonts.googleapis.com/css'
		);

		return $this->filter( 'performance/local-fonts/url', $this->local_font->get( $fonts_url ) );
	}
}
