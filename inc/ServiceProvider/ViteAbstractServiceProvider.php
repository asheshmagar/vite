<?php
/**
 * Vite service provider.
 *
 * @package Vite
 * @since 1.0.0
 */

namespace Vite\ServiceProvider;

use League\Container\ServiceProvider\AbstractServiceProvider;

/**
 * Abstract service provider.
 */
abstract class ViteAbstractServiceProvider extends AbstractServiceProvider {

	/**
	 * The register method is where you define services
	 * in the same way you would directly with the container.
	 * A convenience getter for the container is provided, you
	 * can invoke any of the methods you would when defining
	 * services directly, but remember, any alias added to the
	 * container here, when passed to the `provides` method
	 * must return true, or it will be ignored by the container.
	 *
	 * @return void
	 */
	abstract public function register(): void;
}
