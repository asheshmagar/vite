<?php
/**
 * Displays the post meta.
 *
 * @package Vite
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$meta_elements = $args['meta-elements'] ?? [];
$core          = vite( 'core' );

/**
 * Action: vite/entry-elements/meta/start.
 *
 * Fires before the entry meta is displayed.
 *
 * @since 1.0.0
 */
$core->action( 'entry-elements/meta/start' );

vite( 'entry-elements' )->render_entry_meta( $meta_elements );

/**
 * Action: vite/entry-elements/meta/end.
 *
 * Fires after the entry meta is displayed.
 *
 * @since 1.0.0
 */
$core->action( 'entry-elements/meta/end' );
