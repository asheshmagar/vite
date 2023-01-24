<?php
/**
 * Global layout options.
 *
 * @package Vite
 */

defined( 'ABSPATH' ) || exit;

$options = [
	'vite[container-wide-width]'   => [
		'section'     => 'vite[global-layout]',
		'type'        => 'vite-slider',
		'title'       => __( 'Container max width', 'vite' ),
		'default'     => vite( 'core' )->get_mod_defaults()['container-wide-width'],
		'input_attrs' => [
			'min'   => 728,
			'max'   => 2000,
			'step'  => 1,
			'units' => [ 'px' ],
		],
		'css'         => [
			'selector' => ':root',
			'property' => '--full--width',
			'context'  => 'global',
		],
	],
	'vite[container-narrow-width]' => [
		'section'     => 'vite[global-layout]',
		'type'        => 'vite-slider',
		'title'       => __( 'Container narrow width', 'vite' ),
		'default'     => vite( 'core' )->get_mod_defaults()['container-narrow-width'],
		'input_attrs' => [
			'min'       => 428,
			'max'       => 1028,
			'step'      => 1,
			'units'     => [ 'px' ],
			'separator' => true,
		],
		'css'         => [
			'selector' => ':root',
			'property' => '--narrow--width',
			'context'  => 'global',
		],
	],
	'vite[content-spacing]'        => [
		'section'     => 'vite[global-layout]',
		'type'        => 'vite-dimensions',
		'title'       => __( 'Content spacing', 'vite' ),
		'default'     => vite( 'core' )->get_mod_defaults()['content-spacing'],
		'input_attrs' => [
			'units'        => [ 'px', 'rem', 'em' ],
			'responsive'   => true,
			'separator'    => true,
			'sides'        => [ 'top', 'bottom' ],
			'default_unit' => 'rem',
		],
		'css'         => [
			'selector' => '.vite-content',
			'property' => 'padding',
			'context'  => 'content',
		],
	],
];

vite( 'customizer' )->add( 'settings', $options );
