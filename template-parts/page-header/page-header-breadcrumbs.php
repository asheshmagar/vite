<?php
/**
 * Template part for displaying page header breadcrumbs.
 *
 * @since x.x.x
 * @package Theme
 */

defined( 'ABSPATH' ) || exit;

theme( 'breadcrumbs' )->breadcrumbs(
	[
		'before' => '<div class="page-header-breadcrumbs">',
		'after'  => '</div>',
	]
);
