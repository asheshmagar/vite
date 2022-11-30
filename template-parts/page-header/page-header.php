<?php
/**
 * Template part for displaying page header.
 *
 * @since x.x.x
 * @package Vite
 */

defined( 'ABSPATH' ) || exit;

$elements = $args['elements'] ?? [];
$core     = vite( 'core' );

/**
 * Action: vite/page-header/start
 *
 * Fires before page header.
 *
 * @params array $elements Page header elements.
 * @since x.x.x
 */
$core->action( 'page-header/start', $elements );

vite( 'page-header' )->render_page_header( $elements );

/**
 * Action: vite/page-header/end
 *
 * Fires after page header.
 *
 * @params array $elements Page header elements.
 * @since x.x.x
 */
$core->action( 'page-header/end', $elements );
