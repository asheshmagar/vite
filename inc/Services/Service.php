<?php
/**
 *
 */

namespace Theme\Services;

/**
 * Abstract service.
 */
abstract class Service implements ServiceInterface {

	/**
	 * {inheritdoc}
	 */
	abstract public function init(): void;

	/**
	 * {inheritdoc}
	 */
	public function services(): array {
		return [];
	}
}
