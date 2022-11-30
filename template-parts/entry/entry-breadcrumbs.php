<?php
/**
 * Template part for displaying entry breadcrumbs.
 *
 * @since x.x.x
 * @package Vite
 */

defined( 'ABSPATH' ) || exit;

$core = vite( 'core' );

/**
 * Action: vite/entry-elements/breadcrumbs/start
 *
 * Fires before the entry breadcrumbs.
 *
 * @since x.x.x
 */
$core->action( 'entry-elements/breadcrumbs/start' );

vite( 'breadcrumbs' )->breadcrumbs();

/**
 * Action: vite/entry-elements/breadcrumbs/end
 *
 * Fires after the entry breadcrumbs.
 *
 * @since x.x.x
 */
$core->action( 'entry-elements/breadcrumbs/end' );
