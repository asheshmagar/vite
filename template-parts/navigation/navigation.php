<?php
/**
 * Template file for single post navigation.
 *
 * @package vite
 * @since 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$core = vite( 'core' );

/**
 * Action: vite/single/post-navigation/start
 *
 * Fires before single post navigation.
 *
 * @since 1.0.0
 */
$core->action( 'single/post-navigation/start' );

the_post_navigation(
	[
		'prev_text' => '<span class="nav-subtitle screen-reader-text">' . __( 'Previous Post', 'vite' ) . '</span><span class="nav-icon">' . vite( 'icon' )->get_icon( 'chevron-left', [ 'size' => 10 ] ) . '</span><span class="nav-title">%title</span>',
		'next_text' => '<span class="nav-subtitle screen-reader-text">' . __( 'Next Post', 'vite' ) . '</span><span class="nav-title">%title</span><span class="nav-icon">' . vite( 'icon' )->get_icon( 'chevron-right', [ 'size' => 10 ] ) . '</span>',
	]
);

/**
 * Action: vite/single/post-navigation/end
 *
 * Fires after single post navigation.
 *
 * @since 1.0.0
 */
$core->action( 'single/post-navigation/end' );
