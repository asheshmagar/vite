<?php
/**
 * Template part for displaying page header description.
 */

defined( 'ABSPATH' ) || exit;

$core = vite( 'core' );

/**
 * Action: vite/page-header/description/start
 *
 * Fires before page header description.
 *
 * @since x.x.x
 */
$core->action( 'page-header/description/start' );

vite( 'page-header' )->render_page_header_description();

/**
 * Action: vite/page-header/description/end
 *
 * Fires after page header description.
 *
 * @since x.x.x
 */
$core->action( 'page-header/description/end' );
