<?php
/**
 * Header logo options.
 *
 * @package Vite
 */

defined( 'ABSPATH' ) || exit;

$options = [
	'vite[mobile-logo]'                        => [
		'section'     => 'vite[header-logo]',
		'type'        => 'image',
		'title'       => __( 'Mobile Logo', 'vite' ),
		'height'      => 50,
		'width'       => 125,
		'priority'    => 2,
		'input_attrs' => [
			'separator' => true,
		],
	],
	'vite[header-site-branding-elements]'      => [
		'section'     => 'vite[header-logo]',
		'type'        => 'vite-select',
		'title'       => __( 'Elements', 'vite' ),
		'default'     => vite( 'core' )->get_theme_mod_defaults()['header-site-branding-elements'],
		'choices'     => [
			'logo'                   => __( 'Logo', 'vite' ),
			'logo-title'             => __( 'Logo & Title', 'vite' ),
			'logo-title-description' => __( 'Logo & Title & Description', 'vite' ),
		],
		'partial'     => [
			'selector'        => '.vite-brand',
			'render_callback' => function() {
				get_template_part( 'template-parts/builder-elements/logo', '', [ 'context' => 'header' ] );
			},
		],
		'input_attrs' => [
			'separator' => true,
		],
		'priority'    => 2,
	],
	'vite[header-site-branding-layout]'        => [
		'section'   => 'vite[header-logo]',
		'type'      => 'vite-select',
		'title'     => __( 'Layout', 'vite' ),
		'default'   => vite( 'core' )->get_theme_mod_defaults()['header-site-branding-layout'],
		'choices'   => [
			'inline'  => __( 'Inline', 'vite' ),
			'stacked' => __( 'Stacked', 'vite' ),
		],
		'partial'   => [
			'selector'        => '.vite-brand',
			'render_callback' => function() {
				get_template_part( 'template-parts/builder-elements/logo', '', [ 'context' => 'header' ] );
			},
		],
		'condition' => [
			'vite[header-site-branding-elements]' => [ 'logo-title', 'logo-title-description' ],
		],
	],
	'vite[header-site-title-colors]'           => [
		'section'     => 'vite[header-logo]',
		'type'        => 'vite-color',
		'title'       => __( 'Site title colors', 'vite' ),
		'input_attrs' => [
			'colors'    => [
				[
					'id'    => '--link-color',
					'label' => __( 'Normal', 'vite' ),
				],
				[
					'id'    => '--link-hover-color',
					'label' => __( 'Hover', 'vite' ),
				],
			],
			'separator' => true,

		],
		'css' => [
			'selector' => '.vite-brand__title',
			'property' => 'color',
			'context' => 'header'
		],
	],
	'vite[header-site-description]'           => [
		'section'     => 'vite[header-logo]',
		'type'        => 'vite-color',
		'title'       => __( 'Site description color', 'vite' ),
		'input_attrs' => [
			'colors'    => [
				[
					'id'    => '--text--color',
					'label' => __( 'Normal', 'vite' ),
				],
			],
			'separator' => true,
		],
		'css' => [
			'selector' => '.vite-brand__description',
			'property' => 'color',
			'context' => 'header'
		],
	],
	'vite[header-site-title-typography]'       => [
		'default'     => vite( 'core' )->get_theme_mod_defaults()['header-site-title-typography'],
		'section'     => 'vite[header-logo]',
		'type'        => 'vite-typography',
		'title'       => __( 'Site title typography', 'vite' ),
		'input_attrs' => [
			'separator' => true,
		],
		'css' => [
			'selector' => '.vite-brand__title a',
			'context' => 'header'
		],
	],
	'vite[header-site-description-typography]' => [
		'section'     => 'vite[header-logo]',
		'type'        => 'vite-typography',
		'title'       => __( 'Site description typography', 'vite' ),
		'input_attrs' => [
			'separator' => true,
		],
		'css' => [
			'selector' => '.vite-brand__description',
			'context' => 'header'
		],
	],
];

vite( 'customizer' )->add( 'settings', $options );
