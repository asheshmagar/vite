<?php
/**
 * Header socials options.
 *
 * @package Vite
 */

$options = [
	'vite[header-social-links]'            => [
		'section'     => 'vite[header-socials]',
		'type'        => 'vite-sortable',
		'title'       => __( 'Socials Links', 'vite' ),
		'default'     => vite( 'customizer' )->get_defaults()['header-social-links'],
		'partial'     => [
			'selector'        => '.header-socials',
			'render_callback' => function () {
				get_template_part( 'template-parts/header/header', 'socials' );
			},
		],
		'choices'     => vite( 'core' )->get_social_networks(),
		'input_attrs' => [
			'removable' => true,
		],
	],
	'vite[header-social-icons-size]'       => [
		'section'     => 'vite[header-socials]',
		'type'        => 'vite-slider',
		'title'       => __( 'Icons Size', 'vite' ),
		'default'     => vite( 'customizer' )->get_defaults()['header-social-icons-size'],
		'partial'     => [
			'selector'        => '.header-socials',
			'render_callback' => function () {
				get_template_part( 'template-parts/header/header', 'socials' );
			},
		],
		'input_attrs' => [
			'min'  => 10,
			'max'  => 100,
			'step' => 1,
		],
	],
	'vite[header-social-icons-color-type]' => [
		'section'   => 'vite[header-socials]',
		'type'      => 'vite-buttonset',
		'title'     => __( 'Icons Color Type', 'vite' ),
		'default'   => vite( 'customizer' )->get_defaults()['header-social-icons-color-type'],
		'partial'   => [
			'selector'        => '.header-socials',
			'render_callback' => function () {
				get_template_part( 'template-parts/header/header', 'socials' );
			},
		],
		'choices'   => [
			'custom' => __( 'Custom', 'vite' ),
			'brand'  => __( 'Brand', 'vite' ),
		],
		'condition' => [
			'vite[header-social-links]!' => [],
		],
	],
	'vite[header-social-icons-colors]'     => [
		'section'     => 'vite[header-socials]',
		'type'        => 'vite-color',
		'title'       => __( 'Icons Colors', 'vite' ),
		'default'     => vite( 'customizer' )->get_defaults()['header-social-icons-colors'],
		'input_attrs' => [
			'colors' => [
				[
					'id'    => '--link--color',
					'label' => __( 'Normal', 'vite' ),
				],
				[
					'id'    => '--link--hover--color',
					'label' => __( 'Hover', 'vite' ),
				],
			],
		],
		'condition'   => [
			'vite[header-social-icons-color-type]' => 'custom',
			'vite[header-social-links]!'           => [],
		],
		'selectors'   => [ '.header-socials a' ],
		'properties'  => [ '' ],
	],
	'vite[header-social-icons-spacing]'    => [
		'section'     => 'vite[header-socials]',
		'type'        => 'vite-slider',
		'title'       => __( 'Icons Spacing', 'vite' ),
		'input_attrs' => [
			'min'     => 0,
			'max'     => 100,
			'step'    => 1,
			'units'   => [ 'px', 'rem', 'em' ],
		],
		'selectors'   => [ '.header-socials' ],
		'properties'  => [ 'gap' ],
	],
];

vite( 'customizer' )->add( 'settings', $options );