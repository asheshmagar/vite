<?php
/**
 * Displays the post thumbnail.
 *
 * @package Vite
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$core = vite( 'core' );

/**
 * Action: vite/entry-elements/featured-image.
 *
 * Fires before the featured image.
 *
 * @since 1.0.0
 */
$core->action( 'entry-elements/featured-image/start' );

vite( 'entry-elements' )->render_entry_featured_image();
