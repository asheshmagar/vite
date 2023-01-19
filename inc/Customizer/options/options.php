<?php
/**
 * Options.
 *
 * @package Vite
 */

defined( 'ABSPATH' ) || exit;

$directories = [
	__DIR__ . '/general',
	__DIR__ . '/header',
	__DIR__ . '/footer',
	__DIR__ . '/archive',
	__DIR__ . '/single',
];

foreach ( $directories as $directory ) {
	if ( ! file_exists( $directory ) ) {
		continue;
	}
	$files = scandir( $directory );
	foreach ( $files as $file ) {
		if ( in_array( $file, [ '.', '..' ], true ) ) {
			continue;
		}
		$file_path = $directory . '/' . $file;
		file_exists( $file_path ) && require $file_path;
	}
}
