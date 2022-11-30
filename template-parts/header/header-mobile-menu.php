<?php
/**
 * Template part for displaying header mobile navigation.
 *
 * @package vite
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$mobile_menu = null;
$core        = vite( 'core' );

if ( is_customize_preview() ) {
	$mobile_menu = vite( 'customizer' )->get_setting( 'header-mobile-menu', '0' );
}

/**
 * Action: vite/header/mobile-menu/start
 *
 * Fires before header mobile menu.
 *
 * @since 1.0.0
 */
$core->action( 'header/mobile-menu/start' );

vite( 'nav-menu' )->render_menu( 'mobile', $mobile_menu );

/**
 * Action: vite/header/mobile-menu/end
 *
 * Fires after header mobile menu.
 *
 * @since 1.0.0
 */
$core->action( 'header/mobile-menu/end' );
