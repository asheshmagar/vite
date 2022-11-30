<?php
/**
 * Theme service provider.
 *
 * @package Vite
 * @since 1.0.0
 */

namespace Vite\ServiceProvider;

defined( 'ABSPATH' ) || exit;

use Vite\Performance\WebFontLoader;

/**
 * Theme service provider.
 */
class WebFontLoaderServerProvider extends ViteAbstractServiceProvider {

	/**
	 * {@inheritDoc}
	 *
	 * @param string $id Service id.
	 */
	public function provides( string $id ): bool {
		$services = [
			'font',
			WebFontLoader::class,
		];

		return in_array( $id, $services, true );
	}

	/**
	 * {@inheritDoc}
	 */
	public function register() {
		$this->getContainer()->add( 'font', WebFontLoader::class );
	}
}
