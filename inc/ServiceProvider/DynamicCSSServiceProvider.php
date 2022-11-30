<?php
/**
 * DynamicCSS service provider.
 *
 * @package Vite
 * @since 1.0.0
 */

namespace Vite\ServiceProvider;

defined( 'ABSPATH' ) || exit;

use Vite\DynamicCSS;

/**
 * DynamicCSS service provider.
 */
class DynamicCSSServiceProvider extends ViteAbstractServiceProvider {

	/**
	 * {@inheritDoc}
	 *
	 * @param string $id Service id.
	 */
	public function provides( string $id ): bool {
		$services = [
			'dynamic-css',
			DynamicCSS::class,
		];

		return in_array( $id, $services, true );
	}

	/**
	 * {@inheritDoc}
	 */
	public function register() {
		$this->getContainer()->add( 'dynamic-css', DynamicCSS::class );
	}
}
