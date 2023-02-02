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
	'vite[content-layout]'         => [
		'section'     => 'vite[global-layout]',
		'type'        => 'vite-select',
		'title'       => __( 'Content layout', 'vite' ),
		'default'     => 'default',
		'input_attrs' => [
			'separator' => true,
		],
		'choices'     => [
			'default'    => __( 'Default', 'vite' ),
			'full-width' => __( 'Full Width', 'vite' ),
			'narrow'     => __( 'Narrow', 'vite' ),
		],
		'transport'   => 'refresh',
	],
	'vite[content-style]'          => [
		'section'     => 'vite[global-layout]',
		'type'        => 'vite-select',
		'title'       => __( 'Content Style', 'vite' ),
		'default'     => 'default',
		'input_attrs' => [
			'separator' => true,
		],
		'choices'     => [
			'boxed'  => __( 'Boxed', 'vite' ),
			'normal' => __( 'Normal', 'vite' ),
		],
		'transport'   => 'refresh',
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
