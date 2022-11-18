<?php
/**
 * Template part for displaying header html.
 *
 * @since x.x.x
 * @package Vite
 */

defined( 'ABSPATH' ) || exit;

$header_html_type = $args['type'] ?? '1';

vite( 'header' )->render_header_html( $header_html_type );
