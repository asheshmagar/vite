<?php
/**
 * Theme constants.
 *
 * @package Vite
 */

defined( 'ABSPATH' ) || exit;

! defined( 'VITE_VERSION' ) && define( 'VITE_VERSION', '1.0.0' );
! defined( 'VITE_PHP_MIN_VERSION' ) && define( 'VITE_PHP_MIN_VERSION', '7.0' );
! defined( 'VITE_WP_MIN_VERSION' ) && define( 'VITE_WP_MIN_VERSION', '5.5' );
! defined( 'VITE_ASSETS_DIR' ) && define( 'VITE_ASSETS_DIR', get_theme_file_path( '/assets/' ) );
! defined( 'VITE_ASSETS_URI' ) && define( 'VITE_ASSETS_URI', get_theme_file_uri( '/assets/' ) );
