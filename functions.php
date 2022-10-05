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
! defined( 'VITE_VERSION' ) && define( 'VITE_VERSION', 'x.x.x' );
! defined( 'VITE_ASSETS_DIR' ) && define( 'VITE_ASSETS_DIR', get_theme_file_path( '/assets/' ) );
! defined( 'VITE_ASSETS_URI' ) && define( 'VITE_ASSETS_URI', get_theme_file_uri( '/assets/' ) );

// Load composer autoloader.
require_once __DIR__ . '/vendor/autoload.php';
