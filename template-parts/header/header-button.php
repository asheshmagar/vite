<?php
/**
 * Template part for displaying header button.
 *
 * @since x.x.x
 * @package Vite
 */

defined( 'ABSPATH' ) || exit;

$header_button_type = $args['type'] ?? '1';

vite( 'header' )->render_header_button( $header_button_type );
