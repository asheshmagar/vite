<?php
/**
 * PageHeader service provider.
 *
 * @package Theme
 * @since 1.0.0
 */

namespace Theme\ServiceProvider;

defined( 'ABSPATH' ) || exit;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Theme\PageHeader;

/**
 * PageHeader service provider.
 */
class PageHeaderServiceProvider extends AbstractServiceProvider {

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
	public function register(): void {
		$this->getContainer()->addShared( 'page-header', PageHeader::class );
	}
}
