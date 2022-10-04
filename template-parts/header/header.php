<?php
/**
 * Template part for displaying header.
 *
 * @since x.x.x
 * @package Vite
 */

defined( 'ABSPATH' ) || exit;

vite( 'styles' )->print_styles( 'vite-header' );
vite( 'header' )->render_header();
