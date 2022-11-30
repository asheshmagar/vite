<?php
/**
 * Template part for displaying a pagination
 *
 * @package Vite
 */

$core = vite( 'core' );

/**
 * Filter: vite/pagination/args.
 *
 * Filters pagination arguments.
 *
 * @since x.x.x
 */
$pagination_args = $core->filter(
	'pagination/args',
	[
		'mid_size'  => 3,
		'prev_text' => sprintf(
			'<span class="screen-reader-text">%s</span>%s',
			__( 'Previous Page', 'vite' ),
			vite( 'icon' )->get_icon( is_rtl() ? 'chevron-right' : 'chevron-left', [ 'size' => 12 ] )
		),
		'next_text' => sprintf(
			'<span class="screen-reader-text">%s</span>%s',
			__( 'Next Page', 'vite' ),
			vite( 'icon' )->get_icon( is_rtl() ? 'chevron-left' : 'chevron-right', [ 'size' => 12 ] )
		),
	]
);

/**
 * Action: vite/pagination/start
 *
 * Fires before pagination.
 *
 * @since x.x.x
 */
$core->action( 'pagination/start' );

the_posts_pagination( $pagination_args );

/**
 * Action: vite/pagination/end
 *
 * Fires after pagination.
 *
 * @since x.x.x
 */
$core->action( 'pagination/end' );
