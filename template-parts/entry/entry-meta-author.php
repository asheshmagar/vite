<?php
/**
 * Displays the post meta author.
 *
 * @package Vite
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$core = vite( 'core' );

/**
 * Action: vite/entry-elements/meta/author/start.
 *
 * Fires before the entry meta author is displayed.
 *
 * @since 1.0.0
 */
$core->action( 'entry-elements/meta/author/start' );

vite( 'entry-elements' )->render_entry_meta_author();

/**
 * Action: vite/entry-elements/meta/author/end.
 *
 * Fires after the entry meta author is displayed.
 *
 * @since 1.0.0
 */
$core->action( 'entry-elements/meta/author/end' );
