<?php
/**
 * \Vite\NavMenu service provider.
 *
 * @package Vite
 * @since 1.0.0
 */

namespace Vite\ServiceProvider;

defined( 'ABSPATH' ) || exit;

use Vite\Icon;
use League\Container\ServiceProvider\AbstractServiceProvider;

/**
 * Icon service provider.
 */
class IconServiceProvider extends AbstractServiceProvider {

	/**
	 * The provides method is a way to let the container
	 * know that a service is provided by this service
	 * provider. Every service that is registered via
	 * this service provider must have an alias added
	 * to this array, or it will be ignored.
	 *
	 * @param string $id Service id.
	 */
	public function provides( string $id ): bool {
		$services = [
			'icon',
			Icon::class,
		];

		return in_array( $id, $services, true );
	}

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
	public function register(): void {
		$this->getContainer()->addShared( 'icon', Icon::class );
	}
}
