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
	 * @param string $file File.
	 * @return array|mixed
	 */
	public function json_to_array( string $file ) {
		if ( ! file_exists( $file ) ) {
			return [];
		}
		ob_start();
		include $file;
		return json_decode( ob_get_clean(), true );
	}
}
