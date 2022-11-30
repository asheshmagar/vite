<?php
/**
 * Template part for displaying header primary navigation.
 *
 * @since x.x.x
 * @package Vite
 */

defined( 'ABSPATH' ) || exit;

$primary_menu = null;
$core		 = vite( 'core' );

if ( is_customize_preview() ) {
	$primary_menu = vite( 'customizer' )->get_setting( 'header-primary-menu', '0' );
}

/**
 * Action: vite/header/primary-menu/start
 *
 * Fires before header primary menu.
 *
 * @since x.x.x
 */
$core->action( 'header/primary-menu/start' );

vite( 'nav-menu' )->render_menu( 'primary', $primary_menu );

/**
 * Action: vite/header/primary-menu/end
 *
 * Fires after header primary menu.
 *
 * @since x.x.x
 */
$core->action( 'header/primary-menu/end' );
