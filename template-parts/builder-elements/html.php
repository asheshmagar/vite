<?php
/**
 * Template part for displaying html.
 *
 * @since x.x.x
 * @package Vite
 */

defined( 'ABSPATH' ) || exit;

vite( 'builder-elements' )->render( 'html', $args ?? [] );
