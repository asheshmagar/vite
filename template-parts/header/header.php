<?php
/**
 * Template part for displaying header.
 *
 * @since x.x.x
 * @package Theme
 */

defined( 'ABSPATH' ) || exit;

theme( 'styles' )->print_styles( 'theme-header' );
theme( 'header' )->render_header();
