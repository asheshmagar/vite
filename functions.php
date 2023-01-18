<?php
/**
 * Theme functions and definitions.
 *
 * @since x.x.x
 * @package Vite
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

require_once __DIR__ . '/inc/Constants.php'; // Load theme constants.
require_once __DIR__ . '/inc/BackCompat.php'; // Load theme back-compat.

if ( ! call_user_func( 'Vite\maybe_load_theme' ) ) {
	return;
}

require_once __DIR__ . '/vendor/autoload.php'; // Load composer autoloader.

vite( 'theme' )->init(); // Init theme.
