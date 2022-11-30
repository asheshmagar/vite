<?php
/**
 * Core service provider.
 *
 * @package Vite
 * @since 1.0.0
 */

namespace Vite\ServiceProvider;

defined( 'ABSPATH' ) || exit;

use Vite\Customizer\Customizer;
use Vite\Customizer\Sanitize;

/**
 * Core service provider.
 */
class CustomizerServiceProvider extends ViteAbstractServiceProvider {

	/**
	 * {@inheritDoc}
	 *
	 * @param string $id Service id.
	 */
	public function provides( string $id ): bool {
		$services = [
			'customizer',
			Customizer::class,
		];

		return in_array( $id, $services, true );
	}

	/**
	 * {@inheritDoc}
	 */
	public function register() {
		$this
			->getContainer()
			->addShared( 'customizer', Customizer::class )
			->addArguments( [ 'dynamic-css', new Sanitize() ] );
	}
}
