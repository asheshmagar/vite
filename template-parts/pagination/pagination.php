<?php
/**
 * Template part for displaying a pagination
 *
 * @package Vite
 */

$pagination_args = apply_filters(
	'vite_pagination_args',
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

the_posts_pagination( $pagination_args );
