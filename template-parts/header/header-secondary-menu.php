<?php
/**
 * Template part for displaying header primary navigation.
 *
 * @since x.x.x
 * @package Vite
 */

defined( 'ABSPATH' ) || exit;

$secondary_menu = null;

if ( is_customize_preview() ) {
	$secondary_menu = vite( 'customizer' )->get_setting( 'header-secondary-menu', '0' );
}

vite( 'nav-menu' )->render_menu( 'secondary', $secondary_menu );
