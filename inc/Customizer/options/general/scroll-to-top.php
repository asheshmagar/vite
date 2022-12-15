<?php
/**
 * Options for scroll to top.
 *
 * @package Vite
 */

$core = vite( 'core' );

$options = [
	'vite[scroll-to-top]'               => [
		'section' => 'vite[scroll-to-top]',
		'type'    => 'vite-toggle',
		'title'   => __( 'Enable', 'vite' ),
		'default' => $core->get_theme_mod_defaults()['scroll-to-top'],
	],
	'vite[scroll-to-top-position]'      => [
		'section'     => 'vite[scroll-to-top]',
		'type'        => 'vite-buttonset',
		'title'       => __( 'Position', 'vite' ),
		'default'     => $core->get_theme_mod_defaults()['scroll-to-top-position'],
		'choices'     => [
			'left'  => __( 'Left', 'vite' ),
			'right' => __( 'Right', 'vite' ),
		],
		'condition'   => [
			'vite[scroll-to-top]' => true,
		],
		'input_attrs' => [
			'separator' => true,
		],
	],
	'vite[scroll-to-top-left-offset]'   => [
		'section'     => 'vite[scroll-to-top]',
		'type'        => 'vite-slider',
		'title'       => __( 'Left offset', 'vite' ),
		'default'     => $core->get_theme_mod_defaults()['scroll-to-top-left-offset'],
		'input_attrs' => [
			'units'      => [ 'px' ],
			'responsive' => true,
			'separator'  => true,
		],
		'condition'   => [
			'vite[scroll-to-top]'          => true,
			'vite[scroll-to-top-position]' => 'left',
		],
	],
	'vite[scroll-to-top-right-offset]'  => [
		'section'     => 'vite[scroll-to-top]',
		'type'        => 'vite-slider',
		'title'       => __( 'Right offset', 'vite' ),
		'default'     => $core->get_theme_mod_defaults()['scroll-to-top-right-offset'],
		'input_attrs' => [
			'units'      => [ 'px' ],
			'responsive' => true,
			'separator'  => true,
		],
		'condition'   => [
			'vite[scroll-to-top]'          => true,
			'vite[scroll-to-top-position]' => 'right',
		],
	],
	'vite[scroll-to-top-bottom-offset]' => [
		'section'     => 'vite[scroll-to-top]',
		'type'        => 'vite-slider',
		'title'       => __( 'Bottom offset', 'vite' ),
		'default'     => $core->get_theme_mod_defaults()['scroll-to-top-bottom-offset'],
		'input_attrs' => [
			'units'      => [ 'px' ],
			'responsive' => true,
			'separator'  => true,
		],
		'condition'   => [
			'vite[scroll-to-top]' => true,
		],
	],
	'vite[scroll-to-top-button-size]'   => [
		'section'     => 'vite[scroll-to-top]',
		'type'        => 'vite-slider',
		'title'       => __( 'Button Size', 'vite' ),
		'default'     => $core->get_theme_mod_defaults()['scroll-to-top-button-size'],
		'input_attrs' => [
			'units'      => [ 'px', 'rem', 'em' ],
			'responsive' => true,
			'separator'  => true,
		],
		'condition'   => [
			'vite[scroll-to-top]' => true,
		],
	],
	'vite[scroll-to-top-icon-size]'     => [
		'section'     => 'vite[scroll-to-top]',
		'type'        => 'vite-slider',
		'title'       => __( 'Icon Size', 'vite' ),
		'default'     => $core->get_theme_mod_defaults()['scroll-to-top-icon-size'],
		'input_attrs' => [
			'separator' => true,
		],
		'condition'   => [
			'vite[scroll-to-top]' => true,
		],
	],
	'vite[scroll-to-top-border]'        => [
		'section'     => 'vite[scroll-to-top]',
		'type'        => 'vite-border',
		'title'       => __( 'Border', 'vite' ),
		'default'     => $core->get_theme_mod_defaults()['scroll-to-top-border'],
		'input_attrs' => [
			'separator' => true,
		],
		'condition'   => [
			'vite[scroll-to-top]' => true,
		],
	],
	'vite[scroll-to-top-radius]'        => [
		'section'     => 'vite[scroll-to-top]',
		'type'        => 'vite-dimensions',
		'title'       => __( 'Border Radius', 'vite' ),
		'default'     => $core->get_theme_mod_defaults()['scroll-to-top-radius'],
		'input_attrs' => [
			'units'     => [ 'px', 'rem', 'em', '%' ],
			'separator' => true,
		],
		'condition'   => [
			'vite[scroll-to-top]' => true,
		],
	],
];

vite( 'customizer' )->add( 'settings', $options );
