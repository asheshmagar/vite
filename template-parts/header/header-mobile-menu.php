<?php
/**
 * Template part for displaying header mobile navigation.
 *
 * @package vite
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$mobile_menu = null;

if ( is_customize_preview() ) {
	$mobile_menu = vite( 'customizer' )->get_setting( 'header-mobile-menu', '0' );
}

vite( 'nav-menu' )->render_menu( 'mobile', $mobile_menu );
