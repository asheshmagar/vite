<?php
/**
 * Control class.
 *
 * @since x.x.x
 * @package Vite;
 */

namespace Vite\Customizer\Type;

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
	private $controls;

	/**
	 * Set.
	 *
	 * @param string      $type Control name.
	 * @param array|mixed $props Control attributes.
	 * @return void
	 */
	public function set( string $type, ?array $props ) {
		global $wp_customize;
		$this->controls[ $type ] = $props;
		isset( $props['callback'] ) && $wp_customize->register_control_type( $props['callback'] );
	}

	/**
	 * Get.
	 *
	 * @param string      $type Control type.
	 * @param string|null $prop Get control property empty|callback|sanitize_callback.
	 * @return array|mixed
	 */
	public function get( string $type, ?string $prop = null ) {
		if (
			isset( $this->controls[ $type ] ) &&
			isset( $prop ) &&
			in_array( $prop, [ 'callback', 'sanitize_callback' ], true )
		) {
			return $this->controls[ $type ][ $prop ] ?? null;
		}
		return $this->controls[ $type ] ?? array();
	}
}
