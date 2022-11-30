<?php
/**
 * Performance service provider.
 *
 * @package Vite
 * @since 1.0.0
 */

namespace Vite\ServiceProvider;

defined( 'ABSPATH' ) || exit;

use Vite\Performance\Performance;

/**
 * Theme service provider.
 */
class PerformanceServiceProvider extends ViteAbstractServiceProvider {

	/**
	 * {@inheritDoc}
	 *
	 * @param string $id Service id.
	 */
	public function provides( string $id ): bool {
		$services = [
			'performance',
			Performance::class,
		];

		return in_array( $id, $services, true );
	}

	/**
	 * {@inheritDoc}
	 */
	public function register() {
		$this
			->getContainer()
			->addShared( 'performance', Performance::class )
			->addArgument( 'font' );
	}
}
