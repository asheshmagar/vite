<?php
/**
 * Interface for services.
 *
 * @since x.x.x
 * @package Theme
 */

namespace Theme\Services;

interface ServiceInterface {

	/**
	 * Init services.
	 *
	 * Actions and filters should be added here.
	 *
	 * @return void
	 */
	public function init(): void;

	/**
	 * Get services.
	 *
	 * @return array|null
	 */
	public function services(): ?array;
}
