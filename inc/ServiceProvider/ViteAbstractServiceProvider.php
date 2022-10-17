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
	 * The provides method is a way to let the container
	 * know that a service is provided by this service
	 * provider. Every service that is registered via
	 * this service provider must have an alias added
	 * to this array, or it will be ignored.
	 *
	 * @param string $id Service id.
	 */
	abstract public function provides( string $id ): bool;

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
