<?php
/**
 * Template part for displaying page header breadcrumbs.
 *
 * @since x.x.x
 * @package Vite
 */

defined( 'ABSPATH' ) || exit;

vite( 'breadcrumbs' )->breadcrumbs(
	[
		'before' => '<div class="page-header-breadcrumbs">',
		'after'  => '</div>',
	]
);
