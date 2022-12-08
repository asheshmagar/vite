<?php
/**
 * JSON trait.
 *
 * @package Vite
 */

namespace Vite\Traits;

/**
 * JSON trait.
 */
trait JSON {

	/**
	 * Holds all json data.
	 *
	 * @var array
	 */
	private $jsons = [];

	/**
	 * Get JSON to array.
	 *
	 * @param string $file File.
	 * @return array|mixed
	 */
	public function json_to_array( string $file ) {
		$hash = md5( $file );

		if ( isset( $this->jsons[ $hash ] ) ) {
			return $this->jsons[ $hash ];
		}

		if ( ! file_exists( $file ) ) {
			return [];
		}

		ob_start();
		include $file;
		$this->jsons[ $hash ] = json_decode( ob_get_clean(), true );

		return $this->jsons[ $hash ];
	}
}
