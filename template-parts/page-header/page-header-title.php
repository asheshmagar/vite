<?php
/**
 * Template part for displaying page header title.
 *
 * @since x.x.x
 * @package Vite
 */

defined( 'ABSPATH' ) || exit;

$core = vite( 'core' );

/**
 * Action: vite/page-header/title/start
 *
 * Fires before page header title.
 *
 * @since x.x.x
 */
$core->action( 'page-header/title/start' );

vite( 'page-header' )->render_page_header_title();

/**
 * Action: vite/page-header/title/end
 *
 * Fires after page header title.
 *
 * @since x.x.x
 */
$core->action( 'page-header/title/end' );
