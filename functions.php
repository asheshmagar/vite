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
! defined( 'THEME_VERSION' ) && define( 'THEME_VERSION', 'x.x.x' );
! defined( 'THEME_ASSETS_DIR' ) && define( 'THEME_ASSETS_DIR', get_theme_file_path( '/assets/' ) );
! defined( 'THEME_ASSETS_URI' ) && define( 'THEME_ASSETS_URI', get_theme_file_uri( '/assets/' ) );

// Load composer autoloader.
require_once __DIR__ . '/vendor/autoload.php';
