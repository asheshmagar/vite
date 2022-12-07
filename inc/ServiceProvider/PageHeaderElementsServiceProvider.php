<?php
/**
 * PageHeader service provider.
 *
 * @package Vite
 * @since 1.0.0
 */

namespace Vite\ServiceProvider;

defined( 'ABSPATH' ) || exit;

use Vite\Elements\PageHeaderElements;

/**
 * PageHeader service provider.
 */
class PageHeaderElementsServiceProvider extends ViteAbstractServiceProvider {

	/**
	 * {@inheritDoc}
	 *
	 * @param string $id Service id.
	 */
	public function provides( string $id ): bool {
		$services = [
			'page-header-elements',
			PageHeaderElements::class,
		];

		return in_array( $id, $services, true );
	}

	/**
	 * {@inheritDoc}
	 */
	public function register() {
		$this->getContainer()
			->addShared( 'page-header-elements', PageHeaderElements::class )
			->addArguments( [ 'core', 'customizer' ] );
	}
}
