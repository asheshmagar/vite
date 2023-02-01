<?php
/**
 * PHP functions either missing from older PHP versions or not included by default.
 *
 * @package Vite
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'str_starts_with' ) ) {

	/**
	 * Polyfill for `str_starts_with()` function added in PHP 8.0.
	 *
	 * Performs a case-sensitive check indicating if
	 * the haystack begins with needle.
	 *
	 * @param string $haystack The string to search in.
	 * @param string $needle   The substring to search for in the `$haystack`.
	 *
	 * @return bool True if `$haystack` starts with `$needle`, otherwise false.
	 */
	function str_starts_with( string $haystack, string $needle ): bool {
		return '' === $needle || 0 === strpos( $haystack, $needle );
	}
}

if ( ! function_exists( 'str_ends_with' ) ) {

	/**
	 * Polyfill for `str_ends_with()` function added in PHP 8.0.
	 *
	 * Performs a case-sensitive check indicating if
	 * the haystack ends with needle.
	 *
	 * @param string $haystack The string to search in.
	 * @param string $needle   The substring to search for in the `$haystack`.
	 *
	 * @return bool True if `$haystack` ends with `$needle`, otherwise false.
	 */
	function str_ends_with( string $haystack, string $needle ): bool {
		return '' === $needle || substr( $haystack, -strlen( $needle ) ) === $needle;
	}
}

if ( ! function_exists( 'str_contains' ) ) {

	/**
	 * Polyfill for `str_contains()` function added in PHP 8.0.
	 *
	 * Performs a case-sensitive check indicating if needle is
	 * contained in haystack.
	 *
	 * @param string $haystack The string to search in.
	 * @param string $needle   The substring to search for in the haystack.
	 *
	 * @return bool True if `$needle` is in `$haystack`, otherwise false.
	 */
	function str_contains( string $haystack, string $needle ): bool {
		return '' === $needle || false !== strpos( $haystack, $needle );
	}
}

if ( ! function_exists( 'str_contains_arr' ) ) {

	/**
	 * Performs a case-sensitive check indicating if any of the needles
	 * are contained in haystack.
	 *
	 * @param string $haystack The string to search in.
	 * @param array  $needles  The substrings to search for in the haystack.
	 *
	 * @return bool True if any of the `$needles` are in `$haystack`, otherwise false.
	 */
	function str_contains_arr( string $haystack, array $needles ): bool {
		foreach ( $needles as $needle ) {
			if ( str_contains( $haystack, $needle ) ) {
				return true;
			}
		}

		return false;
	}
}

