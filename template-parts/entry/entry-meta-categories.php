<?php
/**
 * Displays the post meta categories.
 *
 * @package Vite
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$core = vite( 'core' );

/**
 * Action: vite/entry-elements/meta/categories/start.
 *
 * Fires before the entry meta categories is displayed.
 *
 * @since 1.0.0
 */
$core->action( 'entry-elements/meta/categories/start' );

vite( 'entry-elements' )->render_entry_meta_categories();

/**
 * Action: vite/entry-elements/meta/categories/end.
 *
 * Fires after the entry meta categories is displayed.
 *
 * @since 1.0.0
 */
$core->action( 'entry-elements/meta/categories/end' );
