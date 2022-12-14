<?php
/**
 *
 */

namespace Vite\Elements;

use Vite\Traits\Mods;

trait ElementsTrait {

	use Mods;

	/**
	 * Render.
	 *
	 * @param string $element Element.
	 * @param array  $args Args.
	 *
	 * @return void
	 */
	public function render( string $element, array $args = [] ) {
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
