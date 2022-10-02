<?php
/**
 * Sanitize class.
 *
 * @package Customind
 */

namespace Theme\Customizer;

use WP_Customize_Setting;

/**
 * Sanitize class.
 */
class Sanitize {

	/**
	 * Sanitize checkbox.
	 *
	 * @param mixed $input Input.
	 * @return int|string
	 */
	public static function sanitize_checkbox( $input ) {
		return ( ( 1 === $input || '1' === $input || true === (bool) $input ) ? 1 : '' );
	}

	/**
	 * Sanitize int.
	 *
	 * @param int|string           $number Number.
	 * @param WP_Customize_Setting $setting Settings.
	 *
	 * @return int|string
	 */
	public static function sanitize_int( $number, WP_Customize_Setting $setting ) {
		return ( is_numeric( $number ) ? intval( $number ) : $setting->default );
	}

	/**
	 * Sanitize HTML.
	 *
	 * @param string $input Input.
	 * @return string
	 */
	public static function sanitize_html( string $input ): string {
		return wp_kses_post( $input );
	}

	/**
	 * Sanitize a string key.
	 *
	 * @param string $input Input.
	 * @return string
	 */
	public static function sanitize_key( string $input ): string {
		return sanitize_key( $input );
	}

	/**
	 * Sanitize text field.
	 *
	 * @param string $input Input.
	 * @return string
	 */
	public static function sanitize_text_field( string $input ): string {
		return sanitize_text_field( $input );
	}

	/**
	 * Sanitize radio select.
	 *
	 * @param string               $input Input.
	 * @param WP_Customize_Setting $setting Settings.
	 * @return string
	 */
	public static function sanitize_radio_select( string $input, WP_Customize_Setting $setting ): string {
		$input   = self::sanitize_text_field( $input );
		$choices = $setting->manager->get_control( $setting->id )->choices;
		return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
	}

	/**
	 * Sanitize hex color.
	 *
	 * @param string $input Input.
	 * @return string
	 */
	public static function sanitize_hex_color( string $input ): string {
		if ( empty( $input ) ) {
			return '';
		}
		if ( preg_match( '|^#([A-Fa-f\d]{3}){1,2}$|', $input ) ) {
			return $input;
		}
		return '';
	}

	/**
	 * Sanitize alpha color.
	 *
	 * @param string               $input Input.
	 * @param WP_Customize_Setting $setting Settings.
	 * @return string
	 */
	public static function sanitize_alpha_color( string $input, WP_Customize_Setting $setting ): string {
		if ( '' === $input ) {
			return '';
		}

		if ( 'header_textcolor' === $setting->id && 'blank' === $input ) {
			return 'blank';
		}

		$color = str_replace( ' ', '', $input );

		if ( false !== strpos( $color, 'rgb' ) ) {
			sscanf( $color, 'rgba(%d,%d,%d,%f)', $red, $green, $blue, $alpha );
			$alpha = $alpha ? $alpha : 1;
			return self::to_hex( $red, $green, $blue, $alpha );
		}

		return self::sanitize_hex_color( $input );
	}

	/**
	 * Convert rga to hex.
	 *
	 * @param int $red Red.
	 * @param int $green Green.
	 * @param int $blue Blue.
	 * @param int $alpha Alpha.
	 * @return string
	 */
	public static function to_hex( int $red, int $green, int $blue, int $alpha = 1 ): string {
		$red   = dechex( (int) $red );
		$green = dechex( (int) $green );
		$blue  = dechex( (int) $blue );
		$alpha = (float) $alpha;
		if ( strlen( $red ) < 2 ) {
			$red = '0' . $red;
		}
		if ( strlen( $green ) < 2 ) {
			$green = '0' . $green;
		}
		if ( strlen( $blue ) < 2 ) {
			$blue = '0' . $blue;
		}
		if ( $alpha < 1 ) {
			$alpha = $alpha * 255;
			if ( $alpha < 7 ) {
				$alpha = '0' . dechex( $alpha );
			} else {
				$alpha = dechex( $alpha );
			}
			return "#$red$green$blue$alpha";
		}
		return "#$red$green$blue";
	}

	/**
	 * Sanitize false values.
	 *
	 * @return false
	 */
	public static function sanitize_false_values(): bool {
		return false;
	}

	/**
	 * Sanitize number.
	 *
	 * @param string               $input Input.
	 * @param WP_Customize_Setting $setting Settings.
	 * @return float|int|string
	 */
	public static function sanitize_number( string $input, WP_Customize_Setting $setting ) {
		$input_attrs = $setting->manager->get_control( $setting->id )->input_attrs;
		if ( isset( $input_attrs ) ) {
			$input_attrs['min']  = $input_attrs['min'] ?? 0;
			$input_attrs['step'] = $input_attrs['step'] ?? 1;
			if ( isset( $input_attrs['max'] ) && $input > $input_attrs['max'] ) {
				$input = $input_attrs['max'];
			} elseif ( $input < $input_attrs['min'] ) {
				$input = $input_attrs['min'];
			}
			if ( $input ) {
				$input = (int) $input;
			}
		}
		return is_numeric( $input ) ? $input : $setting->default;
	}

	/**
	 * Sanitize email.
	 *
	 * @param string               $input Input.
	 * @param WP_Customize_Setting $setting Setting.
	 * @return string
	 */
	public static function sanitize_email( string $input, WP_Customize_Setting $setting ): string {
		$email = sanitize_email( $input );
		return ! is_null( $email ) ? $email : $setting->default;
	}

	/**
	 * Sanitize URL.
	 *
	 * @param string $input Input.
	 * @return string
	 */
	public static function sanitize_url( string $input ): string {
		return esc_url_raw( $input );
	}

	/**
	 * Sanitize dropdown categories.
	 *
	 * @param string               $input Input.
	 * @param WP_Customize_Setting $setting Settings.
	 * @return int
	 */
	public static function sanitize_dropdown_categories( string $input, WP_Customize_Setting $setting ): int {
		$input = absint( $input );
		return term_exists( $input, 'category' ) ? $input : $setting->default;
	}

	/**
	 * Sanitize image upload.
	 *
	 * @param string               $input Input.
	 * @param WP_Customize_Setting $setting Settings.
	 * @return string
	 */
	public static function sanitize_image_upload( string $input, WP_Customize_Setting $setting ): string {
		$mimes = array(
			'jpg|jpeg|jpe' => 'image/jpeg',
			'gif'          => 'image/gif',
			'png'          => 'image/png',
			'bmp'          => 'image/bmp',
			'tiff|tif'     => 'image/tiff',
			'ico'          => 'image/x-icon',
		);
		$file  = wp_check_filetype( $input, $mimes );
		return $file['ext'] ? $input : '';
	}

	/**
	 * Sanitize background.
	 *
	 * @param mixed                $input Input.
	 * @param WP_Customize_Setting $setting Settings.
	 * @return array
	 */
	public static function sanitize_background( $input, WP_Customize_Setting $setting ): array {
		if ( ! is_array( $input ) ) {
			return [];
		}
		$output = array();
		if ( isset( $input['background-color'] ) ) {
			$output['background-color'] = self::sanitize_alpha_color( $input['background-color'], $setting );
		}
		if ( isset( $input['background-image'] ) ) {
			$output['background-image'] = self::sanitize_image_upload( $input['background-image'], $setting );
		}
		if ( isset( $input['background-repeat'] ) ) {
			$output['background-repeat'] = self::sanitize_text_field( $input['background-repeat'] );
		}
		if ( isset( $input['background-position'] ) ) {
			$output['background-position'] = self::sanitize_text_field( $input['background-position'] );
		}
		if ( isset( $input['background-size'] ) ) {
			$output['background-size'] = self::sanitize_text_field( $input['background-size'] );
		}
		if ( isset( $input['background-attachment'] ) ) {
			$output['background-attachment'] = self::sanitize_text_field( $input['background-attachment'] );
		}
		return $output;
	}

	/**
	 * Sanitize typography.
	 *
	 * @param mixed                $input Input.
	 * @param WP_Customize_Setting $setting Settings.
	 * @return array
	 */
	public static function sanitize_typography( $input, WP_Customize_Setting $setting ): array {
		return $input;
	}

	/**
	 * Sanitize sortable.
	 *
	 * @param mixed                $input Input.
	 * @param WP_Customize_Setting $setting Settings.
	 * @return array
	 */
	public static function sanitize_sortable( $input, WP_Customize_Setting $setting ): array {
		$choices    = $setting->manager->get_control( $setting->id )->choices;
		$input_keys = $input;
		foreach ( (array) $input_keys as $key => $value ) {
			if ( ! array_key_exists( $value, $choices ) ) {
				unset( $input[ $key ] );
			}
		}
		return ( is_array( $input ) ? $input : ( is_array( $setting->default ) ? $setting->default : [ $setting->default ] ) );
	}
}
