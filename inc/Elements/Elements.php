<?php
/**
 * Base element class.
 *
 * @package Vite
 */

namespace Vite\Elements;

use Vite\Traits\{Hook, HTMLAttrs, SmartTags, Mods};

/**
 * Base element class.
 */
abstract class Elements {

	use HTMLAttrs, Hook, SmartTags, Mods;

	/**
	 * Render.
	 *
	 * @param string $element Element.
	 * @param array  $args Args.
	 *
	 * @return void
	 */
	final public function render( string $element, array $args = [] ) {
		$element = str_replace( '-', '_', $element );

		if ( method_exists( $this, $element ) ) {
			if ( isset( $args[0] ) && is_array( $args[0] ) ) {
				$args = $args[0];
			}

			$args['context'] = $args['context'] ?? 'header';

			$this->$element( $args );
		}
	}
}
