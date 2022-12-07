<?php
/**
 * Template part for displaying menu.
 *
 * @package vite
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

vite( 'builder-elements' )->render( 'menu', $args ?? [] );
