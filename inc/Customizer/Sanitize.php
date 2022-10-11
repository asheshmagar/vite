<?php

namespace Vite\Customizer;

use WP_Customize_Setting;

/**
 * Sanitize.
 */
class Sanitize {

	/**
	 * Sanitize radio.
	 *
	 * @param string               $input Input from customizer.
	 * @param WP_Customize_Setting $setting Settings.
	 * @return string
	 */
	public function sanitize_radio( string $input, WP_Customize_Setting $setting ): string {
		$input   = sanitize_text_field( $input );
		$choices = $setting->manager->get_control( $setting->id )->choices;
		return in_array( $input, array_keys( $choices ), true ) ? $input : ( $setting->default ?? '' );
	}

	/**
	 * Sanitize color.
	 *
	 * @param string|array         $input Input.
	 * @param WP_Customize_Setting $setting Settings.
	 * @return string|array
	 */
	public function sanitize_color( $input, WP_Customize_Setting $setting ) {
		$sanitize = function( $color ) use ( $setting ) {
			$color = sanitize_text_field( $color );

			if ( false !== strpos( $color, 'rgba' ) ) {
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
	 * @param string               $input Input.
	 * @param WP_Customize_Setting $setting Settings.
	 * @return string
	 */
	public function sanitize_input( string $input, WP_Customize_Setting $setting ): string {
		$type = $setting->manager->get_control( $setting->id )->input_attrs['type'] ?? 'text';

		switch ( $type ) {
			case 'url':
				return esc_url_raw( $input );
			case 'email':
				return sanitize_email( $input );
			case 'number':
				return sanitize_int( $input, $setting );
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
	 * @param string|array $input Input.
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
	 * @param array                $input Input.
	 * @param WP_Customize_Setting $setting Settings.
	 * @return array
	 */
	public function sanitize_border( array $input, WP_Customize_Setting $setting ): array {
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
			$input['style'] = sanitize_text_field( $input['style'] );
		} else {
			$input['style'] = 'none';
		}
		if ( isset( $input['color'] ) ) {
			$input['color'] = sanitize_color( $input['color'], $setting );
		}
		if ( isset( $input['width'] ) ) {
			$input['width'] = sanitize_slider( $input['width'] );
		}
		if ( isset( $input['radius'] ) ) {
			$input['width'] = sanitize_slider( $input['width'] );
		}

		return $input;
	}

	/**
	 * Sanitize background.
	 *
	 * @param array                $input Input.
	 * @param WP_Customize_Setting $setting Settings.
	 * @return array
	 */
	public function sanitize_background( array $input, WP_Customize_Setting $setting ): array {
		if ( isset( $input['type'] ) && in_array( $input['type'], [ 'color', 'gradient', 'image' ], true ) ) {
			$input['type'] = sanitize_text_field( $input['type'] );
		} else {
			$input['type'] = 'color';
		}
		if ( isset( $input['color'] ) ) {
			$input['color'] = sanitize_color( $input['color'], $setting );
		}
		if ( isset( $input['gradient'] ) ) {
			$input['gradient'] = sanitize_color( $input['gradient'], $setting );
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
	 *
	 * @return float|string
	 */
	public function sanitize_int( $number, WP_Customize_Setting $setting ) {
		return is_numeric( $number ) ? floatval( $number ) : $setting->default;
	}
}
