<?php
/**
 * Template part for displaying page header.
 *
 * @since x.x.x
 * @package Vite
 */

defined( 'ABSPATH' ) || exit;

vite( 'styles' )->print_styles( 'theme-page-header' );
vite( 'page-header' )->render_page_header();