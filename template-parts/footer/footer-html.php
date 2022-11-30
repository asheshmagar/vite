<?php
/**
 * The template for displaying the footer html.
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$core = vite( 'core' );

/**
 * Action: vite/footer/html/start.
 *
 * Fires before the footer html.
 *
 * @since x.x.x
 */
$core->action( 'footer/html/start' );

vite( 'footer' )->render_footer_html();

/**
 * Action: vite/footer/html/end.
 *
 * Fires after the footer html.
 *
 * @since x.x.x
 */
$core->action( 'footer/html/end' );
