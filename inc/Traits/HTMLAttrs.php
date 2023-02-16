<?php
/**
 * Trait HTMLAttrs.
 *
 * @package Vite
 */

namespace Vite\Traits;

/**
 * Trait HTMLAttrs.
 */
trait HTMLAttrs {

	/**
	 * Print HTML attributes.
	 *
	 * @param string $context The context.
	 * @param array  $attributes The attributes.
	 * @param bool   $echo Whether to echo or return.
	 * @param mixed  ...$args Additional arguments.
	 * @return bool|string
	 */
	public function print_html_attributes( string $context, array $attributes, bool $echo = true, ...$args ) {
		$hook_handle = "vite/html-attributes/$context";

		/**
		 * Filter the HTML attributes.
		 *
		 * @param array $attributes The attributes.
		 * @param mixed ...$args Additional arguments.
		 * @since 1.0.0
		 */
		$attributes = apply_filters( $hook_handle, $attributes, ...$args );

		if ( empty( $attributes ) ) {
			return false;
		}

		$index  = 0;
		$attrs  = '';
		$length = count( $attributes );

		foreach ( $attributes as $key => $value ) {
			if ( isset( $value ) ) {
				if ( is_array( $value ) ) {
					$value = implode( ' ', array_filter( $value ) );
				}

				if ( $echo ) {
					echo ' ' . esc_attr( $key ) . '="' . esc_attr( $value ) . '"' . ( $length === $index + 1 ? ' ' : '' );
				} else {
					$attrs .= ' ' . esc_attr( $key ) . '="' . esc_attr( $value ) . '"' . ( $length === $index + 1 ? ' ' : '' );
				}
			}
			$index++;
		}

		return ! $echo ? true : $attrs;
	}
}
