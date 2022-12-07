<?php
/**
 * Template elements.
 *
 * @package Vite
 */

namespace Vite\Elements;

use Vite\Core;
use Vite\Customizer\Customizer;

/**
 * Elements.
 */
class Elements {

	/**
	 * Holds instance of Core.
	 *
	 * @var Core|null
	 */
	protected ?Core $core;

	/**
	 * Holds instance of Customizer.
	 *
	 * @var Customizer|null
	 */
	protected ?Customizer $customizer;

	/**
	 * Template constructor.
	 *
	 * @param Core       $core Core instance.
	 * @param Customizer $customizer Customizer instance.
	 */
	final public function __construct( Core $core, Customizer $customizer ) {
		$this->core       = $core;
		$this->customizer = $customizer;
	}

	/**
	 * Template render.
	 *
	 * @param string $component Component name.
	 * @param array  $args Arguments.
	 * @return void
	 */
	final public function render( string $component, array $args = [] ) {
		$component = str_replace( '-', '_', $component );
		if ( method_exists( $this, $component ) ) {
			$this->set_context( $args['context'] ?? '' );
			$this->$component( $args );
		}
	}

	/**
	 * Set context.
	 *
	 * @param string $context Context.
	 * @return void
	 */
	public function set_context( string $context = '' ) {}
}
