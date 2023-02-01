<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Vite
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$core = vite( 'core' );

/**
 * Action: vite/sidebar/start.
 *
 * Fires before the sidebar.
 *
 * @since x.x.x
 */
$core->action( 'sidebar/start' );

/**
 * Action: vite/sidebar.
 *
 * Fires in the sidebar.
 *
 * @since x.x.x
 */
$core->action( 'sidebar' );

vite( 'widgets' )->render_sidebar( [ 'should_render' => false ] );

/**
 * Action: vite/sidebar/end.
 *
 * Fires after the sidebar.
 *
 * @since x.x.x
 */
$core->action( 'sidebar/end' );
