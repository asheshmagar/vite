<?php
/**
 * Displays the post excerpt.
 *
 * @package Vite
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$core = vite( 'core' );

/**
 * Action: vite/entry-elements/excerpt/start
 *
 * Fires before the entry excerpt.
 *
 * @since x.x.x
 */
$core->action( 'entry-elements/excerpt/start' );

vite( 'entry-elements' )->render_entry_excerpt();

/**
 * Action: vite/entry-elements/entry-excerpt/end
 *
 * Fires after the entry excerpt.
 *
 * @since 1.0.0
 */
$core->action( 'entry-elements/excerpt/end' );
