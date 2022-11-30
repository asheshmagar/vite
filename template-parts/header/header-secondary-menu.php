<?php
/**
 * Template part for displaying header primary navigation.
 *
 * @since x.x.x
 * @package Vite
 */

defined( 'ABSPATH' ) || exit;

$secondary_menu = null;
$core			= vite( 'core' );

if ( is_customize_preview() ) {
	$secondary_menu = vite( 'customizer' )->get_setting( 'header-secondary-menu', '0' );
}

/**
 * Action: vite/header/secondary-menu/start
 *
 * Fires before header secondary menu.
 *
 * @since x.x.x
 */
$core->action( 'header/secondary-menu/start' );

vite( 'nav-menu' )->render_menu( 'secondary', $secondary_menu );

/**
 * Action: vite/header/secondary-menu/end
 *
 * Fires after header secondary menu.
 *
 * @since x.x.x
 */
$core->action( 'header/secondary-menu/end' );
