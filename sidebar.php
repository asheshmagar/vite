<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Vite
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

vite( 'sidebar' )->render_sidebar( [ 'should_render' => false ] );
