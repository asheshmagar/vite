<?php
/**
 * \Vite\NavMenu service provider.
 *
 * @package Vite
 * @since 1.0.0
 */

namespace Vite\ServiceProvider;

defined( 'ABSPATH' ) || exit;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Vite\NavMenu;

/**
 * NavMenu service provider.
 */
class NavMenuServiceProvider extends AbstractServiceProvider {

	/**
	 * {@inheritDoc}
	 *
	 * @param string $id Service id.
	 */
	public function provides( string $id ): bool {
		$services = [
			'nav-menu',
			NavMenu::class,
		];

		return in_array( $id, $services, true );
	}

	/**
	 * {@inheritDoc}
	 */
	public function register(): void {
		$this->getContainer()->addShared( 'nav-menu', NavMenu::class );
	}
}
