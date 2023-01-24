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
	 * @param null|string $property Property to hold the JSON array.
	 * @return array|mixed
	 */
	public function json_to_array( string $file, string $property = null ) {
		$property = $property ?? md5( $file );

		if ( isset( $this->{$property} ) ) {
			return $this->{$property};
		}

		if ( ! file_exists( $file ) ) {
			$this->{$property} = [];
		} else {
			ob_start();
			include $file;
			$this->{$property} = json_decode( ob_get_clean(), true );
		}

		return $this->{$property};
	}
}
