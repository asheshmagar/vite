<?php
/**
 * Displays the post published date.
 *
 * @package Vite
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$core = vite( 'core' );

/**
 * Action: vite/entry-elements/meta/published-date/start.
 *
 * Fires before the entry meta published date is displayed.
 *
 * @since 1.0.0
 */
$core->action( 'entry-elements/meta/published-date/start' );

vite( 'entry-elements' )->render_entry_date();

/**
 * Action: vite/entry-elements/meta/published-date/end.
 *
 * Fires after the entry meta published date is displayed.
 *
 * @since 1.0.0
 */
$core->action( 'entry-elements/meta/published-date/end' );
