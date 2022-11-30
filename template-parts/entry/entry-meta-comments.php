<?php
/**
 * Displays the post meta comments.
 *
 * @package Vite
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$core = vite( 'core' );

/**
 * Action: vite/entry-elements/meta/comments/start.
 *
 * Fires before the entry meta comments is displayed.
 *
 * @since 1.0.0
 */
$core->action( 'entry-elements/meta/comments/start' );

vite( 'entry-elements' )->render_entry_meta_comments();

/**
 * Action: vite/entry-elements/meta/comments/end.
 *
 * Fires after the entry meta comments is displayed.
 *
 * @since 1.0.0
 */
$core->action( 'entry-elements/meta/comments/end' );
