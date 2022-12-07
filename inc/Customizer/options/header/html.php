<?php
/**
 * Header HTML options.
 *
 * @package Vite
 */

$options = array_reduce(
	[ 1, 2 ],
	function( $acc, $curr ) {
		$acc[ "vite[header-html-$curr]" ] = [
			'section' => "vite[header-html-$curr]",
			'type'    => 'vite-editor',
			'title'   => __( 'HTML', 'vite' ),
			'default' => vite( 'customizer' )->get_defaults()[ "header-html-$curr" ],
			'partial' => [
				'selector'        => ".vite-html--$curr",
				'render_callback' => function() use ( $curr ) {
					get_template_part( 'template-parts/header/header', 'html', [ 'type' => $curr ] );
				},
			],
		];
		return $acc;
	},
	[]
);

vite( 'customizer' )->add( 'settings', $options );
