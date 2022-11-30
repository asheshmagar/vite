<?php
/**
 * EntryElements service provider.
 *
 * @package Vite
 * @since 1.0.0
 */

namespace Vite\ServiceProvider;

defined( 'ABSPATH' ) || exit;

use Vite\EntryElements;

/**
 * EntryElements service provider.
 */
class EntryElementsServiceProvider extends ViteAbstractServiceProvider {

	/**
	 * {@inheritDoc}
	 *
	 * @param string $id Service id.
	 */
	public function provides( string $id ): bool {
		$services = [
			'entry-elements',
			EntryElements::class,
		];

		return in_array( $id, $services, true );
	}

	/**
	 * {@inheritDoc}
	 */
	public function register() {
		$this->getContainer()->addShared( 'entry-elements', EntryElements::class );
	}
}
