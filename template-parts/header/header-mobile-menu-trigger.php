<?php
/**
 * Template part for displaying header mobile menu trigger.
 *
 * @package vite
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$core = vite( 'core' );

/**
 * Action: vite/header/mobile-menu-trigger/start
 *
 * Fires before header mobile menu trigger.
 *
 * @since 1.0.0
 */
$core->action( 'header/mobile-menu-trigger/start' );

vite( 'header' )->render_header_mobile_menu_trigger();

/**
 * Action: vite/header/mobile-menu-trigger/end
 *
 * Fires after header mobile menu trigger.
 *
 * @since 1.0.0
 */
$core->action( 'header/mobile-menu-trigger/end' );
