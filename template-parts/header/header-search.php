<?php
/**
 * Template part for displaying header search.
 *
 * @since x.x.x
 * @package Vite
 */

defined( 'ABSPATH' ) || exit;

$core = vite( 'core' );

/**
 * Action: vite/header/search/start
 *
 * Fires before header search.
 *
 * @since x.x.x
 */
$core->action( 'header/search/start' );

vite( 'header' )->render_header_search();

/**
 * Action: vite/header/search/end
 *
 * Fires after header search.
 *
 * @since x.x.x
 */
$core->action( 'header/search/end' );
