<?php
/**
 * Displays the post meta tags.
 *
 * @package Vite
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$core = vite( 'core' );

/**
 * Action: vite/entry-elements/meta/tags/start.
 *
 * Fires before the entry meta tags is displayed.
 *
 * @since 1.0.0
 */
$core->action( 'entry-elements/meta/tags/start' );

vite( 'entry-elements' )->render_entry_meta_tags();

/**
 * Action: vite/entry-elements/meta/tags/end.
 *
 * Fires after the entry meta tags is displayed.
 *
 * @since 1.0.0
 */
$core->action( 'entry-elements/meta/tags/end' );
