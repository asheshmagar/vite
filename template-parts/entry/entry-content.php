<?php
/**
 * Displays the post content.
 *
 * @package Vite
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$core = vite( 'core' );

/**
 * Action: vite/entry-elements/content/start
 *
 * Fires after the entry breadcrumbs.
 *
 * @since x.x.x
 */
$core->action( 'entry-elements/content/start' );

vite( 'entry-elements' )->render_entry_content();

/**
 * Action: vite/entry-elements/content/end
 *
 * Fires after the entry content.
 *
 * @since 1.0.0
 */
$core->action( 'entry-elements/content/end' );
