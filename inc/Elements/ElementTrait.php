<?php
/**
 *
 */

namespace Vite\Elements;

use Vite\Core;
use Vite\Customizer\Customizer;

trait ElementTrait {

	/**
	 * Instance of core.
	 *
	 * @var null|Core
	 */
	private $core = null;

	/**
	 * Instance of customizer.
	 *
	 * @var null|Customizer
	 */
	private $customizer = null;

	/**
	 * Render.
	 *
	 * @param string $element Element.
	 * @param array  $args Args.
	 * @return void
	 */
	public function render( string $element, array $args = [] ) {
		$element = str_replace( '-', '_', $element );

		if ( method_exists( $this, $element ) ) {
			$this->set_props();

			if ( isset( $args[0] ) && is_array( $args[0] ) ) {
				$args = $args[0];
			}

			$args['core']       = $this->core;
			$args['customizer'] = $this->customizer;
			$args['context']    = $args['context'] ?? 'header';

			$this->$element( $args );
		}
	}

	/**
	 * Set props.
	 *
	 * @return void
	 */
	private function set_props() {
		if ( is_null( $this->core ) ) {
			$this->core = vite( 'core' );
		}
		if ( is_null( $this->customizer ) ) {
			$this->customizer = vite( 'customizer' );
		}
	}
}
