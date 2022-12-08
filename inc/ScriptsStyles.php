<?php
/**
 * Class ScriptsStyles.
 *
 * @package Vite
 */

namespace Vite;

defined( 'ABSPATH' ) || exit;

use Vite\Traits\Hook;

/**
 * Class Styles
 *
 * @package Vite
 */
class ScriptsStyles {

	use Hook;

	const STYLES = [
		'vite-global'  => 'global.css',
		'vite-header'  => 'header.css',
		'vite-content' => 'content.css',
		'vite-footer'  => 'footer.css',
	];

	/**
	 * Init.
	 *
	 * @since x.x.x
	 */
	public function init() {
		add_action( 'init', [ $this, 'register' ], PHP_INT_MAX );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue' ] );
		add_action( 'wp_head', [ $this, 'remove_no_js' ], 2 );
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

		wp_register_script( 'vite-script', VITE_ASSETS_URI . 'dist/frontend.js', [], VITE_VERSION, true );
		wp_register_script( 'vite-customizer', VITE_ASSETS_URI . 'dist/customizer.js', $customizer_asset['dependencies'], $customizer_asset['version'], true );
		wp_register_script( 'vite-meta', VITE_ASSETS_URI . 'dist/meta.js', $meta_asset['dependencies'], $meta_asset['version'], true );
		wp_register_script( 'vite-meta-preview', VITE_ASSETS_URI . 'dist/meta-preview.js', [ 'wp-components' ], $meta_asset['version'], true );
		wp_register_script( 'vite-customizer-preview', VITE_ASSETS_URI . 'dist/customizer-preview.js', array_merge( $customizer_preview_asset['dependencies'], [ 'customize-preview' ] ), $customizer_preview_asset['version'], true );

		wp_register_style( 'vite-customizer', VITE_ASSETS_URI . 'dist/customizer.css', [ 'wp-components' ], $customizer_asset['version'] );
		wp_register_style( 'vite-customizer-preview', false, false, '1.0.0' );

		foreach ( self::STYLES as $handle => $file ) {
			wp_register_style( $handle, VITE_ASSETS_URI . 'dist/' . $file, [], VITE_VERSION );
			wp_style_add_data( $handle, 'precache', true );
		}
	}

	/**
	 * Enqueue.
	 *
	 * @return void
	 */
	public function enqueue() {
		$handles = $this->filter( 'style/handles', array_keys( static::STYLES ) );

		if ( ! empty( $handles ) ) {
			foreach ( $handles as $handle ) {
				wp_enqueue_style( $handle );
			}
		}

		wp_enqueue_script( 'vite-script' );
		wp_localize_script( 'vite-script', '_VITE_', [ 'publicPath' => VITE_ASSETS_URI . 'dist/' ] );
		vite( 'customizer' )->dynamic_css->enqueue();
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
