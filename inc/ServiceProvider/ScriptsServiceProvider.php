<?php
/**
 * Scripts service provider.
 *
 * @package Theme
 * @since 1.0.0
 */

namespace Theme\ServiceProvider;

defined( 'ABSPATH' ) || exit;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Theme\Scripts;

/**
 * Scripts service provider.
 */
class ScriptsServiceProvider extends AbstractServiceProvider {

	/**
	 * {@inheritDoc}
	 *
	 * @param string $id Service id.
	 */
	public function provides( string $id ): bool {
		$services = [
			'scripts',
			Scripts::class,
		];

		return in_array( $id, $services, true );
	}

	/**
	 * {@inheritDoc}
	 */
	public function register(): void {
		$this->getContainer()->addShared( 'scripts', Scripts::class );
	}
}
