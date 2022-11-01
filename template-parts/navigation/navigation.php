<?php
/**
 * Template file for single post navigation.
 *
 * @package vite
 * @since 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

the_post_navigation(
	[
		'prev_text' => '<span class="nav-subtitle screen-reader-text">' . __( 'Previous Post', 'vite' ) . '</span><span class="nav-icon">' . vite( 'icon' )->get_icon( 'chevron-left', [ 'size' => 10 ] ) . '</span><span class="nav-title">%title</span>',
		'next_text' => '<span class="nav-subtitle screen-reader-text">' . __( 'Next Post', 'vite' ) . '</span><span class="nav-title">%title</span><span class="nav-icon">' . vite( 'icon' )->get_icon( 'chevron-right', [ 'size' => 10 ] ) . '</span>',
	]
);
