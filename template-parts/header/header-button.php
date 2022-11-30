<?php
/**
 * Template part for displaying header button.
 *
 * @since x.x.x
 * @package Vite
 */

defined( 'ABSPATH' ) || exit;

$header_button_type = $args['type'] ?? '1';
$core               = vite( 'core' );

/**
 * Action: vite/header/button-{type}/start
 *
 * Fires before header button.
 *
 * @since x.x.x
 */
$core->action( "header/button-$header_button_type/start" );

vite( 'header' )->render_header_button( $header_button_type );

/**
 * Action: vite/header/button-{type}/end
 *
 * Fires after header button.
 *
 * @since x.x.x
 */
$core->action( "header/button-$header_button_type/end" );
