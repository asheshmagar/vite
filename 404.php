<?php
/**
 * The template for displaying page.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 * @package Vite
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();
do_action( 'vite_404' );
get_footer();
