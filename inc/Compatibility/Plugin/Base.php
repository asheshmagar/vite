<?php
/**
 * Abstract Compatibility.
 *
 * @package Vite
 */

namespace Vite\Compatibility\Plugin;

use Vite\Traits\Hook;

/**
 * Class AbstractCompatibility.
 */
abstract class Base {

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
