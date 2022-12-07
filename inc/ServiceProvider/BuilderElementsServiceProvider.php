<?php
/**
 * Builder elements service provider.
 *
 * @package Vite
 * @since 1.0.0
 */

namespace Vite\ServiceProvider;

defined( 'ABSPATH' ) || exit;

use Vite\Elements\BuilderElements;

/**
 * Breadcrumbs service provider.
 */
class BuilderElementsServiceProvider extends ViteAbstractServiceProvider {

	/**
	 * {@inheritDoc}
	 *
	 * @param string $id Service id.
	 */
	public function provides( string $id ): bool {
		$services = [
			'builder-elements',
			BuilderElements::class,
		];

		return in_array( $id, $services, true );
	}

	/**
	 * {@inheritDoc}
	 */
	public function register() {
		$this
			->getContainer()
			->addShared( 'builder-elements', BuilderElements::class )
			->addArguments( [ 'core', 'customizer' ] );
	}
}
