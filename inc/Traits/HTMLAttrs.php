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

	use Hook;

	/**
	 * Print HTML attributes.
	 *
	 * @param string $context The context.
	 * @param array  $attributes The attributes.
	 * @param bool   $echo Whether to echo or return.
	 * @param mixed  ...$args Additional arguments.
	 * @return void|string
	 */
	public function print_html_attributes( string $context, array $attributes, bool $echo = true, ...$args ) {
		$attributes = $this->filter( "html-attributes/$context", $attributes, ...$args );

		if ( empty( $attributes ) ) {
			return;
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

		if ( ! $echo ) {
			return $attrs;
		}
	}
}
