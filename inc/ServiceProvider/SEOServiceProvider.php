<?php
/**
 * PageHeader service provider.
 *
 * @package Vite
 * @since 1.0.0
 */

namespace Vite\ServiceProvider;

defined( 'ABSPATH' ) || exit;

use Vite\SEO;

/**
 * PageHeader service provider.
 */
class SEOServiceProvider extends ViteAbstractServiceProvider {

	/**
	 * {@inheritDoc}
	 *
	 * @param string $id Service id.
	 */
	public function provides( string $id ): bool {
		$services = [
			'seo',
			SEO::class,
		];

		return in_array( $id, $services, true );
	}

	/**
	 * {@inheritDoc}
	 */
	public function register() {
		$this->getContainer()->addShared( 'seo', SEO::class );
	}
}
