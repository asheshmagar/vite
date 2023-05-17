<?php
/**
 * Theme functions and definitions.
 *
 * @since x.x.x
 *
 * @package Vite
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Load theme constants.
require_once __DIR__ . '/inc/Constants.php';

// Load theme back-compat.
require_once __DIR__ . '/inc/BackCompat.php';

// Return early if requirements are not met.
if ( ! call_user_func( 'Vite\maybe_load_theme' ) ) {
	return;
}

// Load composer autoloader.
require_once __DIR__ . '/vendor/autoload.php';

// Run theme.
vite( 'theme' )->init();
