<?php
/**
 * Theme functions and definitions.
 *
 * @since x.x.x
 * @package Vite
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Define constants.
 */
! defined( 'VITE_VERSION' ) && define( 'VITE_VERSION', '1.0.0' );
! defined( 'VITE_PHP_MIN_VERSION' ) && define( 'VITE_PHP_MIN_VERSION', '7.2' );
! defined( 'VITE_WP_MIN_VERSION' ) && define( 'VITE_WP_MIN_VERSION', '5.5' );
! defined( 'VITE_ASSETS_DIR' ) && define( 'VITE_ASSETS_DIR', get_theme_file_path( '/assets/' ) );
! defined( 'VITE_ASSETS_URI' ) && define( 'VITE_ASSETS_URI', get_theme_file_uri( '/assets/' ) );

require_once __DIR__ . '/inc/BackCompat.php';

if ( ! call_user_func( 'Vite\maybe_load_theme' ) ) {
	return;
}

// Load composer autoloader.
require_once __DIR__ . '/vendor/autoload.php';

vite( 'theme' )->init();
