<?php
/**
 * \Theme\TemplateHooks service provider.
 *
 * @package Theme
 * @since 1.0.0
 */

namespace Theme\ServiceProvider;

defined( 'ABSPATH' ) || exit;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Theme\TemplateHooks;

/**
 * TemplateHooks service provider.
 */
class TemplateHooksServiceProvider extends AbstractServiceProvider {

	/**
	 * {@inheritDoc}
	 *
	 * @param string $id Service id.
	 */
	public function provides( string $id ): bool {
		$services = [
			'template-hooks',
			TemplateHooks::class,
		];

		return in_array( $id, $services, true );
	}

	/**
	 * {@inheritDoc}
	 */
	public function register(): void {
		$this->getContainer()->addShared( 'template-hooks', TemplateHooks::class );
	}
}
