<?php

namespace Vite\Customizer;

use WP_Customize_Setting;

/**
 * Sanitize checkbox.
 *
 * @param mixed $input Input.
 * @return bool
 */
function sanitize_checkbox( $input ): bool {
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
function sanitize_int( $number, WP_Customize_Setting $setting ) {
	return is_numeric( $number ) ? floatval( $number ) : $setting->default;
}

/**
 * Sanitize radio.
 *
 * @param string               $input Input from customizer.
 * @param WP_Customize_Setting $setting Settings.
 * @return string
 */
function sanitize_radio( string $input, WP_Customize_Setting $setting ): string {
	$input   = sanitize_text_field( $input );
	$choices = $setting->manager->get_control( $setting->id )->choices;
	return in_array( $input, array_keys( $choices ), true ) ? $input : ( $setting->default ?? '' );
}

/**
 * Sanitize color.
 *
 * @param string|array         $input Input.
 * @param WP_Customize_Setting $setting Settings.
 * @return string
 */
function sanitize_color( $input, WP_Customize_Setting $setting ): string {
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
		return array_walk(
			$input,
			function( &$key, $value ) use ( $sanitize ) {
				$key = $sanitize( $value );
			}
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
function sanitize_input( string $input, WP_Customize_Setting $setting ): string {
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
 * Sanitize buttonset.
 *
 * @param string|array $input Input.
 * @return array|string
 */
function sanitize_buttonset( $input ) {
	if ( is_array( $input ) ) {
		return array_map( 'sanitize_text_field', $input );
	}
	return sanitize_text_field( $input );
}

function sanitize_border( $input ) {
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
	}
}
