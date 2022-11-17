<?php
/**
 * Comments service provider.
 *
 * @package Vite
 * @since 1.0.0
 */

namespace Vite\ServiceProvider;

defined( 'ABSPATH' ) || exit;

use Vite\Comments\Comments;
use Vite\Comments\WalkerComment;

/**
 * Breadcrumbs service provider.
 */
class CommentsServiceProvider extends ViteAbstractServiceProvider {

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
		$this
			->getContainer()
			->addShared( 'comments', Comments::class )
			->addArgument( new WalkerComment() );
	}
}
