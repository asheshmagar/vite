<?php
/**
 * Displays the post meta.
 *
 * @package Vite
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$meta_elements = $args['meta-elements'] ?? [];

vite( 'entry-elements' )->render_entry_meta( $meta_elements );
