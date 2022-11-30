<?php
/**
 * Template part for displaying page header breadcrumbs.
 *
 * @since x.x.x
 * @package Vite
 */

defined( 'ABSPATH' ) || exit;

$core = vite( 'core' );

/**
 * Action: vite/page-header/breadcrumbs/start
 *
 * Fires before page header breadcrumbs.
 *
 * @since x.x.x
 */
$core->action( 'page-header/breadcrumbs/start' );

vite( 'breadcrumbs' )->breadcrumbs(
	[
		'before' => '<div class="page-header-breadcrumbs">',
		'after'  => '</div>',
	]
);

/**
 * Action: vite/page-header/breadcrumbs/end
 *
 * Fires after page header breadcrumbs.
 *
 * @since x.x.x
 */
$core->action( 'page-header/breadcrumbs/end' );
