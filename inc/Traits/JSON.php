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
	 * Get JSON to array.
	 *
	 * @param string      $file File.
	 * @param null|string $key Key to hold the JSON array.
	 * @return array|mixed
	 */
	public function json_to_array( string $file, string $key = null ) {
		$key = $key ?? md5( $file );

		if ( isset( $this->{$key} ) ) {
			return $this->{$key};
		}

		if ( ! file_exists( $file ) ) {
			return [];
		}

		ob_start();
		include $file;
		$this->{$key} = json_decode( ob_get_clean(), true );

		return $this->{$key};
	}
}
