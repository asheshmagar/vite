<?php
/**
 * Global layout options.
 *
 * @package Vite
 */

$options = [
	'vite[container-wide-width]'   => [
		'section'     => 'vite[global-layout]',
		'type'        => 'vite-slider',
		'title'       => __( 'Container max width', 'vite' ),
		'default'     => vite( 'customizer' )->get_defaults()['container-wide-width'],
		'input_attrs' => [
			'min'   => 728,
			'max'   => 2000,
			'step'  => 1,
			'units' => [ 'px' ],
		],
	],
	'vite[container-narrow-width]' => [
		'section'     => 'vite[global-layout]',
		'type'        => 'vite-slider',
		'title'       => __( 'Container narrow width', 'vite' ),
		'default'     => vite( 'customizer' )->get_defaults()['container-narrow-width'],
		'input_attrs' => [
			'min'   => 428,
			'max'   => 1028,
			'step'  => 1,
			'units' => [ 'px' ],
		],
	],
	'vite[content-spacing]'        => [
		'section'     => 'vite[global-layout]',
		'type'        => 'vite-slider',
		'title'       => __( 'Content spacing', 'vite' ),
		'default'     => vite( 'customizer' )->get_defaults()['content-spacing'],
		'input_attrs' => [
			'units'      => [ 'px', 'rem', 'em' ],
			'responsive' => true,
		],
		'selectors'   => [ '.site-content' ],
		'properties'  => [ 'padding-block' ],
	],
];

vite( 'customizer' )->add( 'settings', $options );
