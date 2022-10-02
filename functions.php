<?php
/**
 * Theme functions and definitions.
 *
 * @since x.x.x
 * @package Theme
 */

use Theme\Theme;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Define constants.
 */
! defined( 'THEME_VERSION' ) && define( 'THEME_VERSION', 'x.x.x' );
! defined( 'THEME_ASSETS_DIR' ) && define( 'THEME_ASSETS_DIR', get_theme_file_path( '/assets/' ) );
! defined( 'THEME_ASSETS_URI' ) && define( 'THEME_ASSETS_URI', get_theme_file_uri( '/assets/' ) );

/**
 * Load composer autoloader.
 */
require_once __DIR__ . '/vendor/autoload.php';

/**
 * Initialize theme.
 *
 * @return Theme|null
 */
function theme(): ?Theme {
	return Theme::init();
}

theme();
