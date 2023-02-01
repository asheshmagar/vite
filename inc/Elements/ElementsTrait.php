<?php
/**
 * Elements trait.
 *
 * @package Vite
 */

namespace Vite\Elements;

use Vite\Traits\{ Mods, HTMLAttrs };

trait ElementsTrait {

	use Mods , HTMLAttrs {
		HTMLAttrs::add_action insteadof Mods;
		HTMLAttrs::add_filter insteadof Mods;
		HTMLAttrs::action insteadof Mods;
		HTMLAttrs::filter insteadof Mods;
		HTMLAttrs::remove_filter insteadof Mods;
		HTMLAttrs::remove_action insteadof Mods;
	}

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
