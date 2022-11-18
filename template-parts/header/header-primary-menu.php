<?php
/**
 * Template part for displaying header primary navigation.
 *
 * @since x.x.x
 * @package Vite
 */

defined( 'ABSPATH' ) || exit;

$primary_menu = null;

if ( is_customize_preview() ) {
	$primary_menu = vite( 'customizer' )->get_setting( 'header-primary-menu', '0' );
}

vite( 'nav-menu' )->render_menu( 'primary', $primary_menu );
