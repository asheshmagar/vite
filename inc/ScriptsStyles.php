<?php
/**
 * Class ScriptsStyles.
 *
 * @package Vite
 */

namespace Vite;

defined( 'ABSPATH' ) || exit;

use Vite\Traits\Mods;

/**
 * Class Styles
 *
 * @package Vite
 */
class ScriptsStyles {

	use Mods;

	/**
	 * Init.
	 *
	 * @since x.x.x
	 */
	public function init() {
		add_action( 'init', [ $this, 'register' ], PHP_INT_MAX );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue' ] );
		add_action( 'wp_head', [ $this, 'remove_no_js' ], 2 );
		add_filter( 'script_loader_tag', [ $this, 'defer_scripts' ], 10, 2 );
	}

	/**
	 * Defer scripts if not deferred.
	 *
	 * @param string $tag    The `<script>` tag for the enqueued script.
	 * @param string $handle The script's registered handle.
	 *
	 * @return array|string|string[]
	 */
	public function defer_scripts( string $tag, string $handle ) {
		if ( ! $this->filter( 'defer/scripts', true ) ) {
			return $tag;
		}

		$defer_scripts = $this->filter( 'defer/scripts/list', [ 'vite-script' ] );

		if ( in_array( $handle, $defer_scripts, true ) && ! preg_match( '/\s+(defer|async)\s*=\s*(["\'])\s*\2\s*/', $tag ) ) {
			return str_replace( ' src', ' defer src', $tag );
		}

		return $tag;
	}

	/**
	 * Register styles.
	 *
	 * @return void
	 */
	public function register() {
		$customizer_asset         = $this->get_asset( 'customizer' );
		$customizer_preview_asset = $this->get_asset( 'customizer-preview' );
		$meta_asset               = $this->get_asset( 'meta' );

		wp_register_script( 'vite-script', VITE_ASSETS_URI . 'dist/frontend.js', [], VITE_VERSION );
		wp_register_script( 'vite-customizer', VITE_ASSETS_URI . 'dist/customizer.js', $customizer_asset['dependencies'], $customizer_asset['version'], true );
		wp_register_script( 'vite-meta', VITE_ASSETS_URI . 'dist/meta.js', $meta_asset['dependencies'], $meta_asset['version'], true );
		wp_register_script( 'vite-meta-preview', VITE_ASSETS_URI . 'dist/meta-preview.js', [ 'wp-components' ], $meta_asset['version'], true );
		wp_register_script( 'vite-customizer-preview', VITE_ASSETS_URI . 'dist/customizer-preview.js', array_merge( $customizer_preview_asset['dependencies'], [ 'customize-preview' ] ), $customizer_preview_asset['version'], true );

		wp_register_style( 'vite-customizer', VITE_ASSETS_URI . 'dist/customizer.css', [ 'wp-components' ], $customizer_asset['version'] );
		wp_register_style( 'vite-customizer-preview', VITE_ASSETS_URI . 'dist/customizer-preview.css', [], '1.0.0' );

		wp_register_style( 'vite-style', VITE_ASSETS_URI . 'dist/style.css', [], VITE_VERSION );
		wp_style_add_data( 'vite-style', 'precache', true );
	}

	/**
	 * Enqueue.
	 *
	 * @return void
	 */
	public function enqueue() {
		$remote_google_fonts_url = $this->get_theme_mod( 'google-fonts-url', '' );
		$is_local_google_fonts   = $this->get_theme_mod( 'local-google-fonts', false );
		$dynamic_css             = vite( 'customizer' )->dynamic_css->get();
		$dynamic_css_output      = $this->get_theme_mod( 'dynamic-css-output', 'inline' );

		if ( ! empty( $remote_google_fonts_url ) ) {
			if ( $is_local_google_fonts ) {
				$remote_google_fonts_url = vite( 'performance' )->local_font->get( $remote_google_fonts_url );
			}
			wp_enqueue_style( 'vite-google-fonts', $remote_google_fonts_url, [], VITE_VERSION );
		}

		wp_enqueue_style( 'vite-style' );

		if ( ! empty( $dynamic_css ) ) {
			'inline' === $dynamic_css_output && wp_add_inline_style( 'vite-style', $dynamic_css );
		}

		wp_enqueue_script( 'vite-script' );
		wp_localize_script( 'vite-script', '_VITE_', [ 'publicPath' => VITE_ASSETS_URI . 'dist/' ] );
	}

	/**
	 * Remove no-js class from html tag.
	 */
	public function remove_no_js() {
		?>
		<script>!function(e){e.className=e.className.replace(/\bno-js\b/,"js")}(document.documentElement);</script>
		<?php
	}

	/**
	 * Get asset.
	 *
	 * @param string $file_name Filename.
	 * @return array
	 */
	private function get_asset( string $file_name ): array {
		$file = VITE_ASSETS_DIR . "dist/$file_name.asset.php";
		return file_exists( $file ) ? require $file : [
			'dependencies' => [],
			'version'      => VITE_VERSION,
		];
	}
}
