<?php
/**
 * Control class.
 */

namespace Theme\Customizer\Types;

defined( 'ABSPATH' ) || exit;

/**
 * Class Control.
 */
class Control {

	/**
	 * Controls.
	 *
	 * @var array $controls.
	 */
	private static $controls;

	/**
	 * Add control.
	 *
	 * @param string      $name Control name.
	 * @param array|mixed $attributes Control attributes.
	 * @return void
	 */
	public static function add_control( string $name, ?array $attributes ) {
		global $wp_customize;
		self::$controls[ $name ] = $attributes;
		if ( isset( $attributes['callback'] ) ) {
			$wp_customize->register_control_type( $attributes['callback'] );
		}
	}

	/**
	 * Get control instance.
	 *
	 * @param string $control_type Control type.
	 * @return string|null
	 */
	public static function get_control_instance( string $control_type ): ?string {
		$control_class = self::get_control( $control_type );
		return isset( $control_class['callback'] ) && class_exists( $control_class['callback'] ) ? $control_class['callback'] : null;
	}

	/**
	 * Get control.
	 *
	 * @param string $control_type Control type.
	 * @return array|mixed
	 */
	public static function get_control( string $control_type ) {
		return self::$controls[ $control_type ] ?? array();
	}

	/**
	 * Get control sanitize callback.
	 *
	 * @param string $control Control.
	 * @return false|mixed
	 */
	public static function get_sanitize_callback( string $control ): ?callable {
		return self::$controls[ $control ]['sanitize_callback'] ?? null;
	}
}
