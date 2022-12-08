<?php
/**
 * Class CSS.
 *
 * @package Vite
 * @since x.x.x
 */

namespace Vite;

use Vite\Traits\Hook;
use Vite\Traits\Mods;

/**
 * Class CSS.
 */
class DynamicCSS {

	use Mods;
	const STATES = [
		'normal',
		'hover',
		'active',
		'focus',
	];

	const DEVICES = [
		'desktop',
		'tablet',
		'mobile',
	];

	const SIDES = [
		'top',
		'right',
		'bottom',
		'left',
	];

	/**
	 * CSS data.
	 *
	 * @var array $css_data
	 */
	public $css_data = [];

	/**
	 * Context.
	 *
	 * Either customizer or meta.
	 *
	 * @var string
	 */
	public $context = 'customizer';

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
	 * Holds fonts.
	 *
	 * @var array
	 */
	private $fonts = [];

	/**
	 * Init CSS data.
	 *
	 * @param array $settings Customizer settings.
	 * @return void
	 */
	public function init_css_data( array $settings = [] ) {
		if ( empty( $settings ) ) {
			return;
		}
		foreach ( $settings as $key => $setting ) {
			if ( isset( $setting['selectors'] ) ) {
				$this->css_data[ $key ] = $setting;
			}
		}
	}

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
					$css .= sprintf( '@media (max-width: 1024px) { %s }', $v );
					break;
				case 'mobile':
					$css .= sprintf( '@media (max-width: 767px) { %s }', $v );
					break;
			}
		}

		return $css;
	}

	/**
	 * Enqueue dynamic CSS and font styles.
	 *
	 * @return void
	 */
	public function enqueue() {
		$css       = $this->make()->get();
		$fonts_url = vite( 'performance' )->get_local_fonts_url( $this->fonts );

		if ( ! empty( $fonts_url ) ) {
			wp_enqueue_style( 'vite-font', $fonts_url, [], VITE_VERSION );
		}

		if ( ! empty( $css ) ) {
			wp_add_inline_style( 'vite-style', $this->minify( $css ) );
		}
	}

	/**
	 * Check if it is default.
	 *
	 * @param mixed $value Theme mod value.
	 * @param mixed $default Theme mod default.
	 * @return bool
	 */
	private function is_default( $value, $default ): bool {
		if ( is_array( $value ) && is_array( $default ) ) {
			array_multisort( $value );
			array_multisort( $default );
			return wp_json_encode( $value ) === wp_json_encode( $default );
		}
		return $value === $default;
	}

	/**
	 * Make css.
	 *
	 * @return $this
	 */
	public function make(): DynamicCSS {
		if ( ! empty( $this->css_data ) ) {
			foreach ( $this->css_data as $id => $d ) {
				$type = $d['type'] ?? '';
				preg_match( '/\[([^]]*)]/', $id, $match );

				$value   = $this->get_theme_mod( $match[1] );
				$default = $this->get_theme_mod_default( (string) $match[1] );

				if ( empty( $value ) || $this->is_default( $value, $default ) ) {
					continue;
				}

				switch ( $type ) {
					case 'vite-dimensions':
						if ( is_array( $value ) ) {
							if ( $this->array_some(
								function( $a ) {
									return in_array( $a, static::SIDES, true );
								},
								array_keys( $value )
							) ) {
								$this->css['desktop'] .= $this->make_css( $d['selectors'], $d['properties'], $this->dimension_css( $value ) );
							} elseif ( $this->array_some(
								function( $a ) {
									return in_array( $a, static::DEVICES, true );
								},
								array_keys( $value )
							) ) {
								foreach ( static::DEVICES as $device ) {
									if ( isset( $value[ $device ] ) ) {
										$this->css[ $device ] .= $this->make_css( $d['selectors'], $d['properties'], $this->dimension_css( $value[ $device ] ) );
									}
								}
							}
						}
						break;
					case 'vite-slider':
						if ( is_array( $value ) ) {
							if ( $this->array_some(
								function( $a ) {
									return in_array( $a, static::DEVICES, true );
								},
								array_keys( $value )
							) ) {
								foreach ( static::DEVICES as $device ) {
									if ( isset( $value[ $device ] ) ) {
										$values = $value[ $device ];
										if ( is_scalar( $values ) ) {
											$this->css[ $device ] .= $this->make_css( $d['selectors'], $d['properties'], $values );
										} else {
											if ( isset( $values['value'] ) ) {
												$unit                  = $values['unit'] ?? ( $d['input_attrs']['defaultUnit'] ?? 'px' );
												$unit                  = '-' === $unit ? '' : $unit;
												$this->css[ $device ] .= $this->make_css( $d['selectors'], $d['properties'], "{$values['value']}$unit" );
											}
										}
									}
								}
							} else {
								if ( isset( $value['value'] ) ) {
									$unit                  = $value['unit'] ?? ( $d['input_attrs']['defaultUnit'] ?? 'px' );
									$unit                  = '-' === $unit ? '' : $unit;
									$this->css['desktop'] .= $this->make_css( $d['selectors'], $d['properties'], "{$value['value']}$unit" );
								}
							}
						} else {
							$this->css['desktop'] .= $this->make_css( $d['selectors'], $d['properties'], $value );
						}
						break;
					case 'vite-background':
						$type = $value['type'] ?? 'color';

						if ( 'color' === $type && isset( $value['color'] ) ) {
							$this->css['desktop'] .= $this->make_css( $d['selectors'], [], "background-color: {$value['color']};" );
						} elseif ( 'gradient' === $type && isset( $value['gradient'] ) ) {
							$this->css['desktop'] .= $this->make_css( $d['selectors'], [], "background: {$value['gradient']};" );
						} else {
							unset( $value['gradient'] );
							unset( $value['type'] );

							$desktop = [];
							$tablet  = [];
							$mobile  = [];

							foreach ( static::DEVICES as $device ) {
								foreach ( $value  as $k => $v ) {
									if ( isset( $v ) && is_scalar( $v ) ) {
										$desktop[ $k ] = $v;
										continue;
									}

									if ( isset( $v[ $device ] ) ) {
										${$device}[ $k ] = $v[ $device ];
									}
								}
							}

							if ( ! empty( $tablet ) ) {
								$this->css['tablet'] .= $this->make_css( $d['selectors'], [], $this->background_css( $tablet ) );
							}
							if ( ! empty( $mobile ) ) {
								$this->css['mobile'] .= $this->make_css( $d['selectors'], [], $this->background_css( $mobile ) );
							}
							if ( ! empty( $desktop ) ) {
								$this->css['desktop'] .= $this->make_css( $d['selectors'], [], $this->background_css( $desktop ) );
							}
						}
						break;
					case 'vite-typography':
						$desktop = [];
						$tablet  = [];
						$mobile  = [];

						foreach ( static::DEVICES as $device ) {
							foreach ( $value as $k => $v ) {
								if ( isset( $v ) && is_scalar( $v ) ) {
									$desktop[ $k ] = $v;
									continue;
								}
								if ( isset( $v[ $device ] ) ) {
									${$device}[ $k ] = $v[ $device ];
								}
							}
						}

						if ( ! empty( $desktop ) ) {
							$this->css['desktop'] .= $this->make_css( $d['selectors'], [], $this->typography_css( $desktop ) );
						}

						if ( ! empty( $tablet ) ) {
							$this->css['tablet'] .= $this->make_css( $d['selectors'], [], $this->typography_css( $tablet ) );
						}

						if ( ! empty( $mobile ) ) {
							$this->css['mobile'] .= $this->make_css( $d['selectors'], [], $this->typography_css( $mobile ) );
						}
						break;
					case 'vite-border':
						if ( isset( $value['color']['hover'] ) ) {
							$this->css['desktop'] .= $this->make_css(
								array_map(
									function( $s ) {
										return $s . ':hover';
									},
									$d['selectors']
								),
								[ 'border-color' ],
								$value['color']['hover']
							);
						}
						$this->css['desktop'] .= $this->make_css( $d['selectors'], [], $this->border_css( $value ) );
						break;
					default:
						if ( is_array( $value ) ) {
							if ( $this->array_some(
								function( $a ) {
									return in_array( $a, static::DEVICES, true );
								},
								array_keys( $value )
							) ) {
								foreach ( static::DEVICES as $device ) {
									if ( isset( $value[ $device ] ) ) {
										$this->css[ $device ] .= $this->make_css( $d['selectors'], $d['properties'], (string) $value[ $device ] );
									}
								}
							}

							if ( $this->array_some(
								function( $a ) {
									return in_array( $a, static::STATES, true );
								},
								array_keys( $value )
							) ) {
								foreach ( $value as $k => $v ) {
									$this->css['desktop'] .= $this->make_css(
										array_map(
											function( $s ) use ( $k ) {
												if ( empty( $k ) || ! in_array( $k, static::STATES, true ) ) {
													return false;
												}
												if ( 'normal' === $k ) {
													return $s;
												}
												return "$s:$k";
											},
											$d['selectors']
										),
										$d['properties'],
										(string) $v
									);
								}
							}
							if ( ':root' === $d['selectors'][0] ) {
								$css = "{$d['selectors'][0]} {";
								foreach ( $value as $k => $v ) {
									$css .= "$k: $v;";
								}
								$css                  .= '}';
								$this->css['desktop'] .= $css;
							}
						} else {
							$this->css['desktop'] .= $this->make_css( $d['selectors'], $d['properties'], (string) $value );
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

		if ( ! empty( $properties ) ) {
			$properties[] = '';
			return sprintf( '%s { %s }', implode( ',', $selectors ), implode( ": $value;", $properties ) );
		}

		return sprintf( '%s { %s }', implode( ',', $selectors ), $value );
	}

	/**
	 * Dimension CSS
	 *
	 * @param array $value Saved value.
	 * @return string
	 */
	private function dimension_css( array $value = [] ): string {
		$unit = $value['unit'] ?? 'px';

		$css  = ( $value['top'] ?? 0 ) . $unit;
		$css .= ' ' . ( $value['right'] ?? 0 ) . $unit;
		$css .= ' ' . ( $value['bottom'] ?? 0 ) . $unit;
		$css .= ' ' . ( $value['left'] ?? 0 ) . $unit;

		return $css;
	}

	/**
	 * Border CSS.
	 *
	 * @param array $value Border value.
	 * @return string
	 */
	private function border_css( array $value = [] ): string {
		$css = '';

		if ( isset( $value['radius'] ) ) {
			$radius = $value['radius'];

			if ( ! empty( $radius['value'] ) ) {
				$radius['unit'] = $radius['unit'] ?? 'px';
				$css           .= "border-radius: {$radius['value']}{$radius['unit']};";
			}
		}

		if ( isset( $value['style'] ) && 'none' !== $value['style'] ) {
			$css .= "border-style: {$value['style']};";

			if ( isset( $value['width'] ) ) {
				$width = $value['width'];

				if ( ! empty( $width['value'] ) ) {
					$width['unit'] = $width['unit'] ?? 'px';
					$css          .= "border-width: {$width['value']}{$width['unit']};";
				}
			}

			if ( isset( $value['color']['normal'] ) ) {
				$css .= "border-color: {$value['color']['normal']};";
			}
		}

		return $css;
	}

	/**
	 * Typography CSS.
	 *
	 * @param array $value Typography value.
	 * @return string
	 */
	private function typography_css( array $value = [] ):string {
		$css = '';
		if ( ! empty( $value['family'] ) ) {
			if ( 'System Default' === $value['family'] ) {
				$css .= 'font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;';
			} else {
				$css .= "font-family: {$value['family']};";

				if ( ! isset( $this->fonts[ $value['family'] ] ) ) {
					$this->fonts[ $value['family'] ] = [];
				}

				$weight = $value['weight'] ?? '400';

				if ( ! in_array( $weight, $this->fonts[ $value['family'] ], true ) ) {
					$this->fonts[ $value['family'] ][] = "$weight";
				}
			}
		}

		if ( ! empty( $value['weight'] ) ) {
			$css .= "font-weight: {$value['weight']};";
		}

		if ( ! empty( $value['size'] ) ) {
			$size = $value['size'];
			if ( ! empty( $size['value'] ) ) {
				$size['unit'] = $size['unit'] ?? 'px';
				$css         .= "font-size: {$size['value']}{$size['unit']};";
			}
		}

		if ( ! empty( $value['style'] ) ) {
			$css .= "font-style: {$value['style']};";
		}

		if ( ! empty( $value['lineHeight'] ) ) {
			$line_height = $value['lineHeight'];

			if ( ! empty( $line_height['value'] ) ) {
				$line_height['unit'] = $line_height['unit'] ?? '';
				$line_height['unit'] = '-' === $line_height['unit'] ? '' : $line_height['unit'];
				$css                .= "line-height: {$line_height['value']}{$line_height['unit']};";
			}
		}
		if ( ! empty( $value['letterSpacing'] ) ) {
			$letter_spacing = $value['letterSpacing'];
			if ( ! empty( $letter_spacing['value'] ) ) {
				$letter_spacing['unit'] = $letter_spacing['unit'] ?? 'px';
				$css                   .= "letter-spacing: {$letter_spacing['value']}{$letter_spacing['unit']};";
			}
		}
		if ( ! empty( $value['transform'] ) ) {
			$css .= "text-transform: {$value['transform']};";
		}

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

		if ( ! empty( $value['image'] ) ) {
			$css  = 'background: url(' . $value['image'] . ') ';
			$css .= ( $value['position'] ?? '50% 50%' ) . '/';
			$css .= ( $value['size'] ?? 'auto' ) . ' ';
			$css .= ( $value['repeat'] ?? 'repeat' ) . ' ';
			$css .= ( $value['attachment'] ?? 'scroll' ) . ' ';
			$css .= $value['color'] ?? '';
		} else {
			if ( isset( $value['position'] ) ) {
				$css .= "background-position: {$value['position']};";
			}
			if ( isset( $value['size'] ) ) {
				$css .= "background-size: {$value['size']};";
			}
			if ( isset( $value['repeat'] ) ) {
				$css .= "background-repeat: {$value['repeat']};";
			}
			if ( isset( $value['attachment'] ) ) {
				$css .= "background-attachment: {$value['attachment']};";
			}
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
		$css = $this->filter( 'dynamic/css', $this->get() );

		if ( empty( $css ) ) {
			return;
		}

		$css = $this->minify( $css );

		global $wp_filesystem;
		$upload_dir_url = wp_upload_dir();
		$upload_dir     = trailingslashit( $upload_dir_url['basedir'] ) . 'vite/';
		$filename       = 'vite-dynamic.css';

		! $wp_filesystem && require_once ABSPATH . 'wp-admin/includes/file.php';

		WP_Filesystem( false, $upload_dir_url['basedir'], true );

		! $wp_filesystem->is_dir( $upload_dir ) && $wp_filesystem->mkdir( $upload_dir );

		$wp_filesystem->put_contents( "$upload_dir$filename", $css );
	}

	/**
	 * Minify CSS.
	 *
	 * @param string $css CSS to minify.
	 * @return array|string|string[]|null
	 */
	public function minify( string $css ) {
		return preg_replace(
			[
				'#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')|/\*(?!!)(?>.*?\*/)|^\s*|\s*$#s',
				'#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|/\*(?>.*?\*/))|\s*+;\s*+(})\s*+|\s*+([*$~^|]?+=|[{};,>~]|\s(?![\d.])|!important\b)\s*+|([[(:])\s++|\s++([])])|\s++(:)\s*+(?!(?>[^{}"\']++|"(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')*+{)|^\s++|\s++\z|(\s)\s+#si',
				'#(?<=[\s:])(0)(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)#i',
				'#:(0\s+0|0\s+0\s+0\s+0)(?=[;}]|!important)#i',
				'#(background-position):0(?=[;}])#i',
				'#(?<=[\s:,\-])0+\.(\d+)#',
				'#(/\*(?>.*?\*/))|(?<!content:)([\'"])([a-z_][a-z\d\-_]*?)\2(?=[\s{}\];,])#si',
				'#(/\*(?>.*?\*/))|(\burl\()([\'"])(\S+?)\3(\))#si',
				'#(?<=[\s:,\-]\#)([a-f0-6]+)\1([a-f0-6]+)\2([a-f0-6]+)\3#i',
				'#(?<=[{;])(border|outline):none(?=[;}!])#',
				'#(/\*(?>.*?\*/))|(^|[{}])[^\s{}]+\{}#s',
			],
			[
				'$1',
				'$1$2$3$4$5$6$7',
				'$1',
				':0',
				'$1:0 0',
				'.$1',
				'$1$3',
				'$1$2$4$5',
				'$1$2$3',
				'$1:0',
				'$1$2',
			],
			$css
		);
	}
}
