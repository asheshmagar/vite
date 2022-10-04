<?php
/**
 * Template part for displaying page header.
 *
 * @since x.x.x
 * @package Theme
 */

defined( 'ABSPATH' ) || exit;

theme( 'styles' )->print_styles( 'theme-page-header' );
theme( 'page-header' )->render_page_header();
