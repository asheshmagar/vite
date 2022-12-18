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
		'default'     => vite( 'core' )->get_theme_mod_defaults()['header-social-links'],
		'partial'     => [
			'selector'        => '.vite-header .vite-social',
			'render_callback' => function () {
				get_template_part( 'template-parts/builder-elements/socials', '', [ 'context' => 'header' ] );

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
		'default'     => vite( 'core' )->get_theme_mod_defaults()['header-social-icons-size'],
		'partial'     => [
			'selector'        => '.vite-header .vite-social',
			'render_callback' => function () {
				get_template_part( 'template-parts/builder-elements/socials', '', [ 'context' => 'header' ] );

			},
		],
		'input_attrs' => [
			'min'       => 10,
			'max'       => 100,
			'step'      => 1,
			'separator' => true,
		],
	],
	'vite[header-social-icons-color-type]' => [
		'section'     => 'vite[header-socials]',
		'type'        => 'vite-buttonset',
		'title'       => __( 'Icons Color Type', 'vite' ),
		'default'     => vite( 'core' )->get_theme_mod_defaults()['header-social-icons-color-type'],
		'partial'     => [
			'selector'        => '.vite-header .vite-social',
			'render_callback' => function () {
				get_template_part( 'template-parts/builder-elements/socials', '', [ 'context' => 'header' ] );

			},
		],
		'choices'     => [
			'custom' => __( 'Custom', 'vite' ),
			'brand'  => __( 'Brand', 'vite' ),
		],
		'condition'   => [
			'vite[header-social-links]!' => [],
		],
		'input_attrs' => [
			'separator' => true,
			'cols'      => 2,
		],
	],
	'vite[header-social-icons-colors]'     => [
		'section'     => 'vite[header-socials]',
		'type'        => 'vite-color',
		'title'       => __( 'Icons Colors', 'vite' ),
		'default'     => vite( 'core' )->get_theme_mod_defaults()['header-social-icons-colors'],
		'input_attrs' => [
			'colors'    => [
				[
					'id'    => '--link--color',
					'label' => __( 'Normal', 'vite' ),
				],
				[
					'id'    => '--link--hover--color',
					'label' => __( 'Hover', 'vite' ),
				],
			],
			'separator' => true,
		],
		'condition'   => [
			'vite[header-social-icons-color-type]' => 'custom',
			'vite[header-social-links]!'           => [],
		],
	],
	'vite[header-social-icons-spacing]'    => [
		'section'     => 'vite[header-socials]',
		'type'        => 'vite-slider',
		'title'       => __( 'Icons Spacing', 'vite' ),
		'input_attrs' => [
			'min'       => 0,
			'max'       => 100,
			'step'      => 1,
			'units'     => [ 'px', 'rem', 'em' ],
			'separator' => true,
		],
	],
	'vite[header-social-icons-margin]'     => [
		'section'     => 'vite[header-socials]',
		'type'        => 'vite-dimensions',
		'title'       => __( 'Margin', 'vite' ),
		'input_attrs' => [
			'units'      => [
				'px',
				'%',
				'em',
				'rem',
			],
			'responsive' => true,
			'separator'  => true,
		],
	],
];

vite( 'customizer' )->add( 'settings', $options );
