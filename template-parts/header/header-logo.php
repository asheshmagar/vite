<?php
/**
 * Template part for displaying header logo.
 *
 * @since x.x.x
 * @package Vite
 */

defined( 'ABSPATH' ) || exit;

$core = vite( 'core' );

/**
 * Action: vite/header/logo/start
 *
 * Fires before header logo.
 *
 * @since x.x.x
 */
$core->action( 'header/logo/start' );

vite( 'header' )->render_header_logo();

/**
 * Action: vite/header/logo/end
 *
 * Fires after header logo.
 *
 * @since x.x.x
 */
$core->action( 'header/logo/end' );
