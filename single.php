<?php
/**
 * The template for displaying single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 * @package Theme
 */

namespace Theme;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();
do_action( 'theme_single' );
get_footer();
