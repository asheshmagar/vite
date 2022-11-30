<?php
/**
 * PageHeader service provider.
 *
 * @package Vite
 * @since 1.0.0
 */

namespace Vite\ServiceProvider;

defined( 'ABSPATH' ) || exit;

use Vite\PageHeader;

/**
 * PageHeader service provider.
 */
class PageHeaderServiceProvider extends ViteAbstractServiceProvider {

	/**
	 * {@inheritDoc}
	 *
	 * @param string $id Service id.
	 */
	public function provides( string $id ): bool {
		$services = [
			'page-header',
			PageHeader::class,
		];

		return in_array( $id, $services, true );
	}

	/**
	 * {@inheritDoc}
	 */
	public function register() {
		$this->getContainer()->addShared( 'page-header', PageHeader::class );
	}
}
