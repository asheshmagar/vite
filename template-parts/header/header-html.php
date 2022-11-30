<?php
/**
 * Template part for displaying header html.
 *
 * @since x.x.x
 * @package Vite
 */

defined( 'ABSPATH' ) || exit;

$header_html_type = $args['type'] ?? '1';
$core              = vite( 'core' );

/**
 * Action: vite/header/html-{type}/start
 *
 * Fires before header html.
 *
 * @since x.x.x
 */
$core->action( "header/html-$header_html_type/start" );

vite( 'header' )->render_header_html( $header_html_type );

/**
 * Action: vite/header/html-{type}/end
 *
 * Fires after header html.
 *
 * @since x.x.x
 */
$core->action( "header/html-$header_html_type/end" );
