<?php
/**
 * \Theme\Supports service provider.
 *
 * @package Theme
 * @since 1.0.0
 */

namespace Theme\ServiceProvider;

defined( 'ABSPATH' ) || exit;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Theme\Supports;

/**
 * Supports service provider.
 */
class SupportsServiceProvider extends AbstractServiceProvider {

	/**
	 * {@inheritDoc}
	 *
	 * @param string $id Service id.
	 */
	public function provides( string $id ): bool {
		$services = [
			'supports',
			Supports::class,
		];

		return in_array( $id, $services, true );
	}

	/**
	 * {@inheritDoc}
	 */
	public function register(): void {
		$this->getContainer()->addShared( 'supports', Supports::class );
	}
}
