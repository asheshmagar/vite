<?php
/**
 * Sanitize.
 *
 * @package Vite
 */

namespace Vite\Customizer;

defined( 'ABSPATH' ) || exit;

use WP_Customize_Setting;

/**
 * Sanitize.
 */
class Sanitize {

	/**
	 * Sanitize radio.
	 *
	 * @param mixed                $input Input from customizer.
	 * @param WP_Customize_Setting $setting Settings.
	 * @return string
	 */
	public function sanitize_radio( $input, WP_Customize_Setting $setting ) {
		$input   = sanitize_text_field( $input );
		$choices = $setting->manager->get_control( $setting->id )->choices;

		if ( is_numeric( $input ) ) {
			$input = (int) $input;
		}

		return in_array( $input, array_keys( $choices ), true ) ? $input : ( $setting->default ?? '' );
	}

	/**
	 * Sanitize color.
	 *
	 * @param mixed                $input Input.
	 * @param WP_Customize_Setting $setting Settings.
	 * @return mixed
	 */
	public function sanitize_color( $input, WP_Customize_Setting $setting ) {
		$sanitize = function( $color ) use ( $setting ) {
			$color = sanitize_text_field( $color );

			if ( str_contains( $color, 'var(--' ) ) {
				return $color;
			}

			if ( str_contains( $color, 'rgba' ) ) {
				$color = str_replace( ' ', '', $color );

				sscanf( $color, 'rgba(%d,%d,%d,%f)', $red, $green, $blue, $alpha );

				$alpha = $alpha ?? '1';

				return "rgba($red,$green,$blue,$alpha)";
			}

			return sanitize_hex_color( $color );
		};

		if ( is_array( $input ) ) {
			$keys = array_keys( $input );
			return array_reduce(
				$keys,
				function( $acc, $curr ) use ( $sanitize, $input ) {
					if ( isset( $input[ $curr ] ) ) {
						$acc[ $curr ] = $sanitize( $input[ $curr ] );
					}
					return $acc;
				},
				[]
			);
		}

		return $sanitize( $input );
	}


	/**
	 * Sanitize text.
	 *
	 * @param mixed                $input Input.
	 * @param WP_Customize_Setting $setting Settings.
	 * @return string
	 */
	public function sanitize_input( $input, WP_Customize_Setting $setting ) {
		$type = $setting->manager->get_control( $setting->id )->input_attrs['type'] ?? 'text';

		switch ( $type ) {
			case 'url':
				return esc_url_raw( $input );
			case 'email':
				return sanitize_email( $input );
			case 'number':
				return $this->sanitize_int( $input, $setting );
			default:
				return sanitize_text_field( $input );
		}
	}

	/**
	 * Sanitize slider.
	 *
	 * @param mixed $input Input.
	 * @return mixed
	 */
	public function sanitize_slider( $input ) {
		return $input;
	}

	/**
	 * Sanitize buttonset.
	 *
	 * @param mixed $input Input.
	 * @return array|string
	 */
	public function sanitize_buttonset( $input ) {
		if ( is_array( $input ) ) {
			return array_map( 'sanitize_text_field', $input );
		}
		return sanitize_text_field( $input );
	}

	/**
	 * Sanitize border.
	 *
	 * @param mixed                $input Input.
	 * @param WP_Customize_Setting $setting Settings.
	 * @return mixed
	 */
	public function sanitize_border( $input, WP_Customize_Setting $setting ) {
		if ( empty( $input ) ) {
			return $input;
		}

		$value         = [];
		$border_styles = [
			'none',
			'solid',
			'dotted',
			'dashed',
			'double',
			'groove',
			'ridge',
			'inset',
			'outset',
		];

		if ( isset( $input['style'] ) && in_array( $input['style'], $border_styles, true ) ) {
			$value['style'] = sanitize_text_field( $input['style'] );
		}
		if ( isset( $input['color'] ) ) {
			$value['color'] = $this->sanitize_color( $input['color'], $setting );
		}
		if ( isset( $input['width'] ) ) {
			$value['width'] = $this->sanitize_dimensions( $input['width'] );
		}

		return $value;
	}

	/**
	 * Sanitize background.
	 *
	 * @param mixed                $input Input.
	 * @param WP_Customize_Setting $setting Settings.
	 * @return mixed
	 */
	public function sanitize_background( $input, WP_Customize_Setting $setting ) {
		if ( empty( $input ) ) {
			return $input;
		}
		if ( isset( $input['type'] ) && in_array( $input['type'], [ 'color', 'gradient', 'image' ], true ) ) {
			$input['type'] = sanitize_text_field( $input['type'] );
		} else {
			$input['type'] = 'color';
		}
		if ( isset( $input['color'] ) ) {
			$input['color'] = $this->sanitize_color( $input['color'], $setting );
		}
		if ( isset( $input['gradient'] ) ) {
			$input['gradient'] = sanitize_text_field( $input['gradient'] );
		}
		if ( isset( $input['image'] ) ) {
			$input['image'] = esc_url_raw( $input['image'] );
		}
		foreach ( [ 'position', 'repeat', 'size', 'attachment' ] as $prop ) {
			if ( isset( $input[ $prop ] ) && is_array( $input[ $prop ] ) ) {
				$input[ $prop ] = array_map( 'sanitize_text_field', $input[ $prop ] );
			}
		}

		return $input;
	}

	/**
	 * Sanitize checkbox.
	 *
	 * @param mixed $input Input.
	 * @return bool
	 */
	public function sanitize_checkbox( $input ): bool {
		return 1 === $input || '1' === $input || true === (bool) $input;
	}

	/**
	 * Sanitize int.
	 *
	 * @param int|string           $number Number.
	 * @param WP_Customize_Setting $setting Settings.
	 * @return float|string
	 */
	public function sanitize_int( $number, WP_Customize_Setting $setting ) {
		return is_numeric( $number ) ? floatval( $number ) : $setting->default;
	}

	/**
	 * Sanitize sortable.
	 *
	 * @param mixed $input Input.
	 * @return mixed
	 */
	public function sanitize_sortable( $input ) {
		if ( empty( $input ) ) {
			return $input;
		}
		foreach ( $input as $i => $val ) {
			foreach ( $val as $k => $v ) {
				switch ( $k ) {
					case 'id':
					case 'label':
					case 'value':
					case 'color':
						$input[ $i ][ $k ] = sanitize_text_field( $input[ $i ][ $k ] );
						break;
					case 'visible':
						$input[ $i ][ $k ] = (bool) $input[ $i ][ $k ];
						break;
					case 'items':
						$input[ $i ][ $k ] = $this->sanitize_sortable( $input[ $i ][ $k ] );
						break;
					default:
						unset( $input[ $i ][ $k ] );
						break;
				}
			}
		}
		return $input;
	}

	/**
	 * Sanitize typography.
	 *
	 * @param mixed $input Input.
	 * @return mixed
	 */
	public function sanitize_typography( $input ) {
		if ( empty( $input ) ) {
			return $input;
		}
		foreach ( $input as $key => $value ) {
			switch ( $key ) {
				case 'family':
				case 'weight':
				case 'style':
				case 'transform':
					$input[ $key ] = sanitize_text_field( $value );
					break;
				case 'size':
				case 'lineHeight':
				case 'letterSpacing':
					foreach ( [ 'desktop', 'tablet', 'mobile' ] as $device ) {
						if ( isset( $input[ $key ][ $device ]['value'] ) ) {
							$input[ $key ][ $device ]['value'] = (float) $input[ $key ][ $device ]['value'];
						}
						if ( isset( $input[ $key ][ $device ]['unit'] ) ) {
							$input[ $key ][ $device ]['unit'] = sanitize_text_field( $input[ $key ][ $device ]['unit'] );
						}
					}
					break;
			}
		}
		return $input;
	}

	/**
	 * Sanitize dimensions.
	 *
	 * @param mixed $input Input.
	 * @return mixed|array
	 */
	public function sanitize_dimensions( $input ) {
		if ( empty( $input ) ) {
			return $input;
		}
		$sanitize = function( $arr ) {
			foreach ( [ 'top', 'right', 'bottom', 'left', 'unit', 'sync' ] as $k ) {
				if ( 'sync' === $k ) {
					if ( isset( $arr[ $k ] ) ) {
						$arr[ $k ] = (bool) $arr[ $k ];
					}
				} else {
					if ( isset( $arr[ $k ] ) ) {
						$arr[ $k ] = sanitize_text_field( $arr[ $k ] );
					}
				}
			}
			return $arr;
		};

		$values = array_values( $input );

		if ( isset( $values[0] ) && is_array( $values[0] ) ) {
			$value = array_map( $sanitize, $input );
		} else {
			$value = $sanitize( (array) $input );
		}

		return $value;
	}
}
