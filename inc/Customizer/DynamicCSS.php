<?php
/**
 * Class CSS.
 *
 * @package Vite
 * @since x.x.x
 */

namespace Vite\Customizer;

/**
 * Class CSS.
 */
class DynamicCSS {

	/**
	 * CSS data.
	 *
	 * @var array $css_data
	 */
	public $css_data = [];

	/**
	 * CSS.
	 *
	 * @var string[]
	 */
	private $css = [
		'desktop' => '',
		'tablet'  => '',
		'mobile'  => '',
	];

	/**
	 * Get CSS.
	 *
	 * @return string
	 */
	public function get(): string {
		$css = '';

		foreach ( $this->css as $k => $v ) {
			if ( empty( $v ) ) {
				continue;
			}
			switch ( $k ) {
				case 'desktop':
					$css .= $v;
					break;
				case 'tablet':
					$css .= '@media (max-width: 1024px) {' . $v . '}';
					break;
				case 'mobile':
					$css .= '@media (max-width: 767px) {' . $v . '}';
					break;
			}
		}

		return $css;
	}

	/**
	 * Add.
	 *
	 * @param array $data Data.
	 * @return void
	 */
	public function add( array $data = [] ) {
		if (
			! isset( $data['selector'] ) ||
			! isset( $data['properties'] ) ||
			! isset( $data['name'] )
		) {
			return;
		}

		$this->css_data[] = [
			'name'       => $data['name'],
			'control'    => $data['control'] ?? null,
			'selectors'  => $data['selectors'] ?? null,
			'properties' => $data['properties'] ?? null,
		];
	}

	/**
	 * Make css.
	 *
	 * @return $this
	 */
	public function make(): DynamicCSS {
		if ( ! empty( $this->css_data ) ) {
			foreach ( $this->css_data as $d ) {
				if ( ! isset( $d['selectors'] ) ) {
					continue;
				}
				$type  = $d['control'] ?? null;
				$value = get_theme_mod( $d['name'] );
				switch ( $type ) {
					case 'vite-dimensions':
						if ( ! empty( $value ) ) {
							if ( $this->array_some(
								function( $a ) {
									return in_array( $a, [ 'top', 'right', 'bottom', 'left' ], true );
								},
								$value
							) ) {
								$this->css['desktop'] = $this->make_css( $d['selectors'], $d['properties'], $this->dimension_css( $value ) );
							} elseif ( $this->array_some(
								function( $a ) {
									return in_array( $a, [ 'desktop', 'tablet', 'mobile' ], true );
								},
								$value
							) ) {
								foreach ( $value as $d => $v ) {
									$this->css[ $d ] = $this->make_css( $d['selectors'], $d['properties'], $this->dimension_css( $v ?? [] ) ?? '' );
								}
							}
						}
						break;
					case 'vite-background':
						if ( ! empty( $value ) ) {
							$this->css['desktop'] .= $this->make_css( $d['selectors'], $d['properties'], $this->background_css( $value ) );
						}
						break;
					case 'vite-typography':
						if ( ! empty( $value ) ) {
							$desktop_size = $value['size']['desktop'] ?? '';

							if ( ! empty( $value['size'] ) ) {
								foreach ( $value['size']  as $k => $v ) {
									if ( 'desktop' === $k || empty( $v ) ) {
										continue;
									}
									$this->css[ $k ] .= $this->make_css( $d['selectors'], [ 'font-size' ], $v );
								}

								unset( $value['size'] );
							}

							$css = implode( ',', $d['selectors'] ) . ' {';

							foreach ( $value as $k => $v ) {
								if ( empty( $v ) ) {
									continue;
								}
								$css .= $k . ':' . $v . ';';
							}

							if ( ! empty( $desktop_size ) ) {
								$css .= 'font-size:' . $desktop_size . ';';
							}

							$css .= '}';

							$this->css['desktop'] .= $css;
						}
						break;
					default:
						if ( ! empty( $value ) ) {
							if ( is_array( $value ) ) {
								foreach ( $value as $k => $v ) {
									if ( ! empty( $v ) ) {
										$this->css[ $k ] .= $this->make_css( $d['selectors'], $d['properties'], $v );
									}
								}
							} else {
								$this->css['desktop'] .= $this->make_css( $d['selectors'], $d['properties'], $value );
							}
						}
				}
			}
		}
		return $this;
	}

	/**
	 * Make CSS.
	 *
	 * @param array  $selectors Array of selectors.
	 * @param array  $properties Array of properties.
	 * @param string $value  Value.
	 * @return string
	 */
	private function make_css( array $selectors, array $properties, string $value ): string {
		if ( empty( $value ) ) {
			return '';
		}
		return sprintf( '%s { %s }', implode( ',', $selectors ), implode( ": $value;", $properties ) );
	}

	/**
	 * Dimension CSS
	 *
	 * @param array $value Saved value.
	 * @return string
	 */
	private function dimension_css( array $value = [] ): string {
		$css  = $value['top'] ?? '0px';
		$css .= ' ' . ( $value['right'] ?? '0px' );
		$css .= ' ' . ( $value['bottom'] ?? '0px' );
		$css .= ' ' . ( $value['left'] ?? '0px' );

		return $css;
	}

	/**
	 * Background CSS.
	 *
	 * @param array $value Saved value.
	 * @return string
	 */
	private function background_css( array $value = [] ): string {
		$css = '';

		$css .= $value['color'] ?? '';

		if ( ! empty( $value['image'] ) ) {
			$css  = 'url(' . $value['image'] . ') ';
			$css .= ( $value['position'] ?? '0% 0%' ) . '/';
			$css .= $value['size'] ?? 'auto ';
			$css .= $value['repeat'] ?? 'repeat ';
			$css .= $value['attachment'] ?? 'scroll ';
			$css .= $value['color'] ?? '';
		}

		return $css;
	}

	/**
	 * Array some.
	 *
	 * JS Array.some() equivalent.
	 *
	 * @param callable $callback callback.
	 * @param array    $array Array.
	 * @return bool
	 */
	private function array_some( callable $callback, array $array ): bool {
		foreach ( $array as $arr ) {
			if ( call_user_func( $callback, $arr ) === true ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Save CSS in file system.
	 *
	 * @return void
	 */
	public function save() {
		$css = apply_filters( 'vite_dynamic_css', $this->get() );

		if ( empty( $css ) ) {
			return;
		}

		global $wp_filesystem;
		$upload_dir_url = wp_upload_dir();
		$upload_dir     = trailingslashit( $upload_dir_url['basedir'] ) . 'vite/';
		$filename       = 'vite-dynamic.css';

		! $wp_filesystem && require_once ABSPATH . 'wp-admin/includes/file.php';

		WP_Filesystem( false, $upload_dir_url['basedir'], true );

		! $wp_filesystem->is_dir( $upload_dir ) && $wp_filesystem->mkdir( $upload_dir );

		$wp_filesystem->put_contents( "$upload_dir$filename", $css );
	}
}
