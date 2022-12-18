<?php
/**
 *
 */

namespace Vite;

use Vite\Traits\Mods;

/**
 * Class CSS.
 */
class DynamicCSS {

	use Mods;

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

	const RADIUS_SIDES = [
		'top-left',
		'top-right',
		'bottom-right',
		'bottom-left',
	];

	/**
	 * Configs.
	 *
	 * @var array
	 */
	public $configs = [];

	/**
	 * Contexts.
	 *
	 * @var string[]
	 */
	private $contexts = [
		'global'  => 'vite-global',
		'header'  => 'vite-header',
		'content' => 'vite-content',
		'footer'  => 'vite-footer',
	];

	/**
	 * Holds generated CSS.
	 *
	 * @var string[]
	 */
	private $css_data = [
		'global'  => [
			'desktop' => [],
			'tablet'  => [],
			'mobile'  => [],
		],
		'header'  => [
			'desktop' => [],
			'tablet'  => [],
			'mobile'  => [],
		],
		'content' => [
			'desktop' => [],
			'tablet'  => [],
			'mobile'  => [],
		],
		'footer'  => [
			'desktop' => [],
			'tablet'  => [],
			'mobile'  => [],
		],
	];

	private $css = [
		'all'     => '',
		'global'  => '',
		'header'  => '',
		'content' => '',
		'footer'  => '',
	];

	/**
	 * Holds google fonts.
	 *
	 * @var array
	 */
	private $google_fonts = [];

	/**
	 * Initialize.
	 *
	 * @param array $settings Settings.
	 */
	public function init( array $settings = [] ): DynamicCSS {
		if ( ! empty( $settings ) ) {
			foreach ( $settings as $key => $setting ) {
				if ( isset( $setting['css'] ) ) {
					$this->configs[ $key ]         = $setting['css'];
					$this->configs[ $key ]['type'] = $setting['type'] ?? '';
				}
			}
		}
		return $this;
	}

	/**
	 * Get CSS.
	 *
	 * @return string[]
	 */
	public function get(): array {
		return $this->generate()->make();
	}

	public function save() {
		$this->generate()->make();
		$this->save_to_file();
	}

	/**
	 * Generate.
	 */
	private function generate(): ?DynamicCSS {
		if ( empty( $this->configs ) ) {
			return null;
		}
		foreach ( $this->configs as $key => $config ) {
			$context = $config['context'] ?? 'global';
			$type    = $config['type'] ?? '';

			if ( empty( $type ) ) {
				continue;
			}

			preg_match( '/\[([^]]*)]/', $key, $match );

			$key     = $match[1] ?? $key;
			$value   = $this->get_theme_mod( $key );
			$default = $this->get_theme_mod_default( $key );

			if ( empty( $value ) || $this->is_default( $value, $default ) ) {
				continue;
			}

			$selector = $config['selector'] ?? '';
			$property = $config['property'] ?? '';

			switch ( $type ) {
				case 'vite-dimensions':
					$pattern = $config['pattern'] ?? '%property-%side';
					$this->append( $this->responsive_dimensions( $selector, $property, $value, $pattern ), $context );
					break;
				case 'vite-typography':
					$this->append( $this->typography( $selector, $value ), $context );
					break;
				case 'vite-color':
					$this->append( $this->color( $selector, $property, $value ), $context );
					break;
				case 'vite-background':
					$this->append( $this->background( $selector, $value ), $context );
					break;
				case 'vite-border':
					$this->append( $this->border( $selector, $value ), $context );
					break;
				case 'vite-slider':
					$this->append( $this->range( $selector, $property, $value ), $context );
					break;
				default:
					$this->append( $this->common( $selector, $property, $value ), $context );
			}
		}

		return $this;
	}

	/**
	 * Typography.
	 *
	 * @param string|string[] $selector CSS selector.
	 * @param array           $typography Typography saved settings.
	 * @return string[]
	 */
	private function typography( $selector, array $typography = [] ): array {
		$css = [
			'desktop' => '',
			'tablet'  => '',
			'mobile'  => '',
		];

		$selector = is_array( $selector ) ? implode( ',', $selector ) : $selector;

		foreach ( self::DEVICES as $device ) {
			if ( 'desktop' === $device ) {
				if ( ! empty( $typography['family'] ) ) {
					$family = $typography['family'];
					if ( ! in_array( $family, [ 'Default', 'Inherit' ], true ) ) {
						if ( ! isset( $this->google_fonts[ $family ] ) ) {
							$this->google_fonts[ $family ] = [];
						}
						$this->google_fonts[ $family ][] = (int) $typography['weight'] ?? 400;
					}
					$css[ $device ] .= sprintf( 'font-family:%s;', $family );
				}

				! empty( $typography['weight'] ) && $css[ $device ]    .= sprintf( 'font-weight:%s;', $typography['weight'] );
				! empty( $typography['transform'] ) && $css[ $device ] .= 'text-transform: ' . $typography['transform'] . ';';
				! empty( $typography['style'] ) && $css[ $device ]     .= 'font-style: ' . $typography['style'] . ';';
			}

			! empty( $typography['size'][ $device ]['value'] ) && $css[ $device ] .= 'font-size: ' . $typography['size'][ $device ]['value'] . ( $typography['size'][ $device ]['unit'] ?? 'px' ) . ';';

			$line_height_unit = $typography['lineHeight'][ $device ]['unit'] ?? '';
			$line_height_unit = '-' === $line_height_unit ? '' : $line_height_unit;

			! empty( $typography['lineHeight'][ $device ]['value'] ) && $css[ $device ]    .= 'line-height: ' . $typography['lineHeight'][ $device ]['value'] . $line_height_unit . ';';
			! empty( $typography['letterSpacing'][ $device ]['value'] ) && $css[ $device ] .= 'letter-spacing: ' . $typography['letterSpacing'][ $device ]['value'] . ( $typography['letterSpacing'][ $device ]['unit'] ?? 'px' ) . ';';
		}

		return [
			'desktop' => $this->attach( $selector, $css['desktop'] ),
			'tablet'  => $this->attach( $selector, $css['tablet'] ),
			'mobile'  => $this->attach( $selector, $css['mobile'] ),
		];
	}

	/**
	 * Border.
	 *
	 * @param string|string[] $selector CSS selector.
	 * @param array           $border Border saved settings.
	 * @return string[]
	 */
	private function border( $selector, array $border ): array {
		$css = [
			'desktop' => [],
			'tablet'  => [],
			'mobile'  => [],
		];
		if ( ! isset( $border['style'] ) || 'none' === $border['style'] ) {
			return $css;
		}

		$properties       = '';
		$properties_hover = '';

		$properties .= 'border-style: ' . $border['style'] . ';';

		! empty( $border['color']['normal'] ) && $properties      .= 'border-color: ' . $border['color']['normal'] . ';';
		! empty( $border['color']['hover'] ) && $properties_hover .= 'border-color: ' . $border['color']['hover'] . ';';

		$properties .= implode( '', array_values( $this->dimensions( '', 'border', $border['width'], '%property-%side-width' ) ) );

		$css['desktop'] = array_merge( $this->attach( $selector, $properties ), $this->attach( $selector, $properties_hover, 'hover' ) );
		return $css;
	}

	/**
	 * Dimensions.
	 *
	 * @param string|string[] $selector CSS selector.
	 * @param string|string[] $property CSS property.
	 * @param array           $dimensions Saved dimensions.
	 * @param string          $pattern CSS property pattern.
	 * @return array
	 */
	private function dimensions( $selector, $property, array $dimensions, string $pattern = '%property-%side' ): array {
		$selector = is_array( $selector ) ? implode( ',', $selector ) : $selector;
		$property = is_array( $property ) ? $property : [ $property ];
		$css      = '';
		$sides    = static::SIDES;

		if ( count( array_intersect( static::RADIUS_SIDES, array_keys( $dimensions ) ) ) ) {
			$sides = static::RADIUS_SIDES;
		}

		foreach ( $property as $prop ) {
			foreach ( $sides as $side ) {
				$css_prop = str_replace( [ '%property', '%side' ], [ $prop, $side ], $pattern );
				$unit     = $dimensions['unit'] ?? 'px';
				( isset( $dimensions[ $side ] ) && 'auto' !== $dimensions[ $side ] ) && $css .= $css_prop . ': ' . $dimensions[ $side ] . $unit . ';';
			}
		}

		if ( ! empty( $css ) ) {
			return $this->attach( $selector, $css );
		}

		return [ '' => '' ];
	}

	/**
	 * Responsive dimensions.
	 *
	 * @param string|string[] $selector CSS selector.
	 * @param string|string[] $property CSS property.
	 * @param array           $dimensions Saved dimensions.
	 * @param string          $pattern CSS property pattern.
	 * @return array
	 */
	private function responsive_dimensions( $selector, $property, array $dimensions, string $pattern = '%property-%side' ): array {
		$css = [
			'desktop' => [],
			'tablet'  => [],
			'mobile'  => [],
		];

		if ( count( array_intersect( static::DEVICES, array_keys( $dimensions ) ) ) ) {
			foreach ( static::DEVICES as $device ) {
				if ( ! empty( $dimensions[ $device ] ) ) {
					$css[ $device ] = $this->dimensions( $selector, $property, $dimensions[ $device ], $pattern );
				}
			}
		} else {
			$css['desktop'] = $this->dimensions( $selector, $property, $dimensions, $pattern );
		}

		return $css;
	}

	/**
	 * Background.
	 *
	 * @param string|string[] $selector CSS selector.
	 * @param array           $background Background saved settings.
	 * @return string[]
	 */
	private function background( $selector, array $background ): array {
		$css = [
			'desktop' => '',
			'tablet'  => '',
			'mobile'  => '',
		];

		if ( ! isset( $background['type'] ) ) {
			return $css;
		}

		if ( 'color' === $background['type'] ) {
			! empty( $background['color'] ) && $css['desktop'] .= 'background-color: ' . $background['color'] . ';';
		} elseif ( 'gradient' === $background['type'] ) {
			! empty( $gradient ) && $css['desktop'] .= 'background: ' . $gradient . ';';
		} elseif ( 'image' === $background['type'] ) {
			! empty( $background['image'] ) && $css['desktop'] .= 'background-image: url(' . $background['image'] . ');';
			! empty( $background['color'] ) && $css['desktop'] .= 'background-color: ' . $background['color'] . ';';
			foreach ( static::DEVICES as $device ) {
				! empty( $background['position'][ $device ] ) && $css[ $device ]   .= 'background-position: ' . $background['position'][ $device ] . ';';
				! empty( $background['repeat'][ $device ] ) && $css[ $device ]     .= 'background-repeat: ' . $background['repeat'][ $device ] . ';';
				! empty( $background['size'][ $device ] ) && $css[ $device ]       .= 'background-size: ' . $background['size'][ $device ] . ';';
				! empty( $background['attachment'][ $device ] ) && $css[ $device ] .= 'background-attachment: ' . $background['attachment'][ $device ] . ';';
			}
		}

		return [
			'desktop' => ! empty( $css['desktop'] ) ? $this->attach( $selector, $css['desktop'] ) : [],
			'tablet'  => ! empty( $css['tablet'] ) ? $this->attach( $selector, $css['tablet'] ) : [],
			'mobile'  => ! empty( $css['mobile'] ) ? $this->attach( $selector, $css['mobile'] ) : [],
		];
	}

	/**
	 * Range.
	 *
	 * @param string|string[]  $selector CSS selector.
	 * @param string|string[]  $property CSS property.
	 * @param int|string|array $range Saved range.
	 * @return string[]
	 */
	private function range( $selector, $property, $range ): array {
		$css = [
			'desktop' => '',
			'tablet'  => '',
			'mobile'  => '',
		];

		if ( ! is_array( $range ) || empty( $selector ) || empty( $property ) ) {
			return $css;
		}

		$property = is_array( $property ) ? $property : [ $property ];

		if ( count( array_intersect( static::DEVICES, array_keys( $range ) ) ) ) {
			foreach ( static::DEVICES as $device ) {
				if ( ! empty( $range[ $device ]['value'] ) ) {
					$prop = array_reduce(
						$property,
						function( $acc, $curr ) use ( $device, $range ) {
							$acc .= $curr . ': ' . $range[ $device ]['value'] . ( $range[ $device ]['unit'] ?? 'px' ) . ';';
							return $acc;
						},
						''
					);

					$css[ $device ] .= $prop;
				}
			}
		} else {
			if ( ! empty( $range['value'] ) ) {
				$prop           = array_reduce(
					$property,
					function( $acc, $curr ) use ( $range ) {
						$acc .= $curr . ': ' . $range['value'] . ( $range['unit'] ?? 'px' ) . ';';
						return $acc;
					},
					''
				);
				$css['desktop'] = $prop;
			}
		}

		return [
			'desktop' => ! empty( $css['desktop'] ) ? $this->attach( $selector, $css['desktop'] ) : [],
			'tablet'  => ! empty( $css['tablet'] ) ? $this->attach( $selector, $css['tablet'] ) : [],
			'mobile'  => ! empty( $css['mobile'] ) ? $this->attach( $selector, $css['mobile'] ) : [],
		];
	}

	/**
	 * Color.
	 *
	 * @param string|string[] $selector CSS selector.
	 * @param string|string[] $property CSS property.
	 * @param string|array    $color Color saved settings.
	 * @return string[]
	 */
	private function color( $selector, $property, $color ): array {
		$css = [
			'desktop' => '',
			'tablet'  => '',
			'mobile'  => '',
		];
		if ( empty( $color ) ) {
			return $css;
		}

		$temp = [];
		if ( is_array( $color ) ) {
			foreach ( $color as $k => $v ) {
				if ( ! empty( $v ) ) {
					$css['desktop'] = array_merge( $temp, $this->attach( $selector, ( $this->str_starts_with( '--', $k ) ? $k : $property ) . ': ' . $v . ';', ( $this->str_starts_with( '--', $k ) ? 'normal' : $k ) ) );
				}
			}
		} else {
			$css['desktop'] = $this->attach( $selector, ( $property . ': ' . $color . ';' ) );
		}

		return $css;
	}

	/**
	 * Common.
	 *
	 * @param string|string[] $selector CSS selector.
	 * @param string|string[] $property CSS property.
	 * @param string|array    $value Saved settings.
	 * @return array|string[]
	 */
	private function common( $selector, $property, $value ): array {
		if ( empty( $value ) ) {
			return [
				'desktop' => [],
				'tablet'  => [],
				'mobile'  => [],
			];
		}

		if ( is_array( $value ) && count( array_intersect( static::DEVICES, array_keys( $value ) ) ) ) {
			$css = [
				'desktop' => [],
				'tablet'  => [],
				'mobile'  => [],
			];

			foreach ( static::DEVICES as $device ) {
				if ( ! empty( $value[ $device ] ) ) {
					$css[ $device ] = array_merge( $css[ $device ], $this->attach( $selector, $property . ': ' . $value[ $device ] . ';' ) );
				}
			}

			return $css;
		}

		return [
			'desktop' => $this->attach( $selector, $property . ': ' . $value . ';' ),
			'tablet'  => [],
			'mobile'  => [],
		];
	}

	/**
	 * Attach selector and properties to make valid CSS.
	 *
	 * @param string|string[] $selector CSS selector.
	 * @param string          $properties CSS properties.
	 * @param string          $state CSS state.
	 */
	private function attach( $selector, string $properties, string $state = 'normal' ): array {
		$state    = 'normal' === $state ? '' : ':' . $state;
		$selector = is_array( $selector ) ? $selector : [ $selector ];

		if ( empty( $selector ) || empty( $properties ) ) {
			return [ '' => '' ];
		}

		$i = 1;

		$selector = array_reduce(
			$selector,
			function( $acc, $curr ) use ( $state, $selector, &$i ) {
				if ( count( $selector ) === $i ) {
					$acc .= $curr . $state;
				} else {
					$acc .= $curr . $state . ',';
				}
				$i++;
				return $acc;
			},
			''
		);
		return [
			$selector => $properties,
		];
	}

	/**
	 * Check if the value default value.
	 *
	 * @param mixed $value Saved value.
	 * @param mixed $default Default value.
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
	 * Append CSS.
	 *
	 * @param array  $css_data CSS data.
	 * @param string $context Context.
	 * @return void
	 */
	private function append( array $css_data, string $context ) {
		if ( empty( $css_data ) ) {
			return;
		}

		foreach ( $css_data as $d => $css ) {
			if ( empty( $css ) ) {
				continue;
			}
			foreach ( $css as $selector => $properties ) {
				if ( isset( $this->css_data[ $context ][ $d ][ $selector ] ) ) {
					$this->css_data[ $context ][ $d ][ $selector ] .= $properties;
				} else {
					$this->css_data[ $context ][ $d ][ $selector ] = $properties;
				}
			}
		}
	}

	/**
	 * Make CSS.
	 *
	 * @return string[]
	 */
	private function make(): array {
		foreach ( $this->css_data as $context => $data ) {
			foreach ( $data as $device => $css_data ) {
				if ( ! empty( $css_data ) ) {
					if ( 'desktop' === $device ) {
						foreach ( $css_data as $selector => $properties ) {
							if ( ! empty( $selector ) && ! empty( $properties ) ) {
								$this->css[ $context ] .= $this->minify( $selector . '{' . $properties . '}' );
							}
						}
					}
					if ( 'tablet' === $device ) {
						foreach ( $css_data as $selector => $properties ) {
							if ( ! empty( $selector ) && ! empty( $properties ) ) {
								$this->css[ $context ] .= '@media(max-width:1024px){' . $this->minify( $selector . '{' . $properties . '}' ) . '}';
							}
						}
					}
					if ( 'mobile' === $device ) {
						foreach ( $css_data as $selector => $properties ) {
							if ( ! empty( $selector ) && ! empty( $properties ) ) {
								$this->css[ $context ] .= '@media(max-width:767px){' . $this->minify( $selector . '{' . $properties . '}' ) . '}';
							}
						}
					}
				}
			}
		}

		$this->css['all'] = $this->css['global'] . $this->css['content'] . $this->css['footer'] . $this->css['header'] . $this->css['content'];

		return $this->css;
	}

	/**
	 * Check if the string starts with the given substring.
	 *
	 * @param string $needle Needle.
	 * @param string $haystack Haystack.
	 * @return bool
	 */
	private function str_starts_with( string $needle, string $haystack ): bool {
		$length = strlen( $needle );
		return substr( $haystack, 0, $length ) === $needle;
	}

	/**
	 * Minify CSS.
	 *
	 * @param string $css CSS.
	 * @return array|string|string[]|null
	 */
	private function minify( string $css ) {
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

	private function save_to_file() {
		foreach ( $this->css as $key => $value ) {
			$this->create_file( "$key.css", $value );
		}
	}

	private function create_file( $filename, $content ) {
		global $wp_filesystem;
		$upload_dir_url = wp_upload_dir();
		$upload_dir     = trailingslashit( $upload_dir_url['basedir'] ) . 'vite/';

		! $wp_filesystem && require_once ABSPATH . 'wp-admin/includes/file.php';
		WP_Filesystem( false, $upload_dir_url['basedir'], true );
		! $wp_filesystem->is_dir( $upload_dir ) && $wp_filesystem->mkdir( $upload_dir );

		return $wp_filesystem->put_contents( "$upload_dir$filename", $content );
	}
}
