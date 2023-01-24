<?php
/**
 * Abstract Compatibility.
 *
 * @package Vite
 */

namespace Vite\Compatibility;

use Vite\Traits\Hook;

/**
 * Class AbstractCompatibility.
 */
abstract class AbstractCompatibility {

	use Hook;

	/**
	 * Plugin slug.
	 *
	 * @var string
	 */
	protected $slug;

	/**
	 * Constructor.
	 *
	 * @param string $slug Plugin slug.
	 */
	public function __construct( string $slug ) {
		$this->slug = $slug;
	}

	/**
	 * Init compatibility.
	 *
	 * @return void
	 */
	abstract public function init();
}
