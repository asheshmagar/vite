<?php
/**
 * Comments service provider.
 *
 * @package Theme
 * @since 1.0.0
 */

namespace Theme\ServiceProvider;

defined( 'ABSPATH' ) || exit;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Theme\Comments;

/**
 * Breadcrumbs service provider.
 */
class CommentsServiceProvider extends AbstractServiceProvider {

	/**
	 * {@inheritDoc}
	 *
	 * @param string $id Service id.
	 */
	public function provides( string $id ): bool {
		$services = [
			'comments',
			Comments::class,
		];

		return in_array( $id, $services, true );
	}

	/**
	 * {@inheritDoc}
	 */
	public function register(): void {
		$this->getContainer()->addShared( 'comments', Comments::class );
	}
}
