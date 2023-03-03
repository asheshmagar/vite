<?php
/**
 * Class ScriptsStyles.
 *
 * @package Vite
 */

namespace Vite;

defined( 'ABSPATH' ) || exit;

use Vite\Traits\{Hook, Mods};

/**
 * Class Styles
 *
 * @package Vite
 */
class ScriptsStyles {

	use Mods, Hook;

	/**
	 * Holds the manifest.
	 *
	 * @var array|null
	 */
	private $manifest;

	/**
	 * Init.
	 *
	 * @since x.x.x
	 */
	public function init() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue' ] );
		add_action( 'wp_head', [ $this, 'remove_no_js' ], 2 );
		add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_editor' ] );
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

		if (
			in_array( $handle, $defer_scripts, true ) &&
			! preg_match( '/\s+(defer|async)\s*=\s*(["\'])\s*\2\s*/', $tag )
		) {
			return str_replace( ' src', ' defer src', $tag );
		}

		return $tag;
	}

	/**
	 * Enqueue.
	 *
	 * @return void
	 */
	public function enqueue() {
		$remote_google_fonts_url = $this->get_mod( 'google-fonts-url', '' );
		$is_local_google_fonts   = $this->get_mod( 'local-google-fonts', false );
		$dynamic_css_output      = $this->get_mod( 'dynamic-css-output', 'inline' );
		$dynamic_css             = vite( 'customizer' )->dynamic_css->get();

		if ( ! empty( $remote_google_fonts_url ) ) {
			if ( $is_local_google_fonts ) {
				$remote_google_fonts_url = vite( 'performance' )->local_font->get( $remote_google_fonts_url );
			}
			wp_enqueue_style( 'vite-google-fonts', $remote_google_fonts_url, [], VITE_VERSION );
		}

		$this->enqueue_asset(
			'vite-frontend',
			[
				'in_footer' => false,
				'defer'     => true,
			]
		);

		$this->enqueue_asset( 'vite-style' );

		if ( ! empty( $dynamic_css ) ) {
			'inline' === $dynamic_css_output && wp_add_inline_style( 'vite-style', $dynamic_css );
		}

		wp_localize_script(
			'vite-frontend',
			'_VITE_',
			[ 'publicPath' => VITE_ASSETS_URI . 'dist/' ]
		);
	}

	/**
	 * Remove no-js class from html tag.
	 */
	public function remove_no_js() {
		wp_print_inline_script_tag(
			'!function(e){e.className=e.className.replace(/\bno-js\b/,"js")}(document.documentElement);'
		);
	}

	/**
	 * Enqueue asset.
	 *
	 * @param string $handle Script or style handle.
	 * @param array  $options Options.
	 *
	 * @return void
	 */
	public function enqueue_asset( string $handle, array $options = [] ) {
		try {
			$manifest = $this->get_manifest();
		} catch ( \Exception $e ) {
			return;
		}
		$key = str_replace( 'vite-', '', $handle );

		if ( ! isset( $manifest[ $key ] ) ) {
			return;
		}

		$asset  = $this->get_asset( $manifest[ $key ]['asset'] ?? '' );
		$script = $manifest[ $key ]['script'] ?? '';
		$style  = $manifest[ $key ]['style'] ?? '';

		$options = wp_parse_args(
			$options,
			[
				'dependencies'     => [],
				'css-dependencies' => [],
				'css-media'        => 'all',
				'version'          => null,
				'in_footer'        => true,
				'preload'          => false,
				'defer'            => false,
			]
		);

		if ( ! empty( $script ) ) {
			if ( $options['defer'] ) {
				add_filter(
					'script_loader_tag',
					function( string $tag, string $target_handle ) use ( $handle ) {
						if ( $target_handle === $handle && ! preg_match( '/\s+(defer|async)\s*=\s*(["\'])\s*\2\s*/', $tag ) ) {
							$tag = str_replace( ' src', ' defer src', $tag );
						}
						return $tag;
					},
					10,
					2
				);
			}
			if (
				wp_register_script(
					$handle,
					VITE_ASSETS_URI . 'dist/' . $script,
					array_merge( $asset['dependencies'], $options['dependencies'] ),
					$options['version'],
					$options['in_footer']
				)
			) {
				wp_enqueue_script( $handle );
			}
		}

		if ( ! empty( $style ) ) {
//			if ( $options['preload'] ) {
//				add_filter(
//					'style_loader_tag',
//					function( string $tag, string $target_handle ) use ( $handle ) {
//						if ( $target_handle === $handle ) {
//							$tag = str_replace( ' href', ' rel="preload" as="style" href', $tag );
//						}
//						return $tag;
//					},
//					10,
//					2
//				);
//			}
			if (
				wp_register_style(
					$handle,
					VITE_ASSETS_URI . 'dist/' . $style,
					$options['css-dependencies'],
					$options['version'],
					$options['css-media']
				)
			) {
				wp_enqueue_style( $handle );
			}
		}
	}

	/**
	 * Get manifest file.
	 *
	 * @return false|mixed|null The manifest file.
	 * @throws \Exception Manifest file not found.
	 */
	private function get_manifest() {
		if ( ! isset( $this->manifest ) ) {
			$manifest = VITE_ASSETS_DIR . 'dist/manifest.json';
			if ( ! file_exists( $manifest ) ) {
				throw new \Exception( 'Manifest file not found' );
			}
			ob_start();
			try {
				include $manifest;
				$this->manifest = json_decode( ob_get_clean(), true );
			} catch ( \Exception $e ) {
				ob_end_clean();
				throw new \Exception( $e->getMessage() );
			}
		}
		return $this->filter( 'manifest', $this->manifest );
	}

	/**
	 * Get asset.
	 *
	 * @param string $file PHP file name.
	 * @return array|mixed
	 */
	private function get_asset( string $file = '' ) {
		if ( ! file_exists( VITE_ASSETS_DIR . "dist/$file" ) || empty( $file ) ) {
			return [
				'dependencies' => [],
				'version'      => VITE_VERSION,
			];
		}
		return require VITE_ASSETS_DIR . "dist/$file";
	}
}
