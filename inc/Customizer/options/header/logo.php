<?php
/**
 * Header logo options.
 *
 * @package Vite
 */

$options = [
	'vite[mobile-logo]'                   => [
		'section'  => 'vite[header-logo]',
		'type'     => 'image',
		'title'    => __( 'Mobile Logo', 'vite' ),
		'height'   => 50,
		'width'    => 125,
		'priority' => 2,
	],
	'vite[header-site-branding-elements]' => [
		'section' => 'vite[header-logo]',
		'type'    => 'vite-select',
		'title'   => __( 'Layout', 'vite' ),
		'default' => vite( 'customizer' )->get_defaults()['header-site-branding-elements'],
		'choices' => [
			'logo'                   => __( 'Logo', 'vite' ),
			'logo-title'             => __( 'Logo & Title', 'vite' ),
			'logo-title-description' => __( 'Logo & Title & Description', 'vite' ),
		],
		'partial' => [
			'selector'        => '.site-branding',
			'render_callback' => function() {
				get_template_part( 'template-parts/header/header', 'logo' );
			},
		],
	],
	'vite[header-site-branding-layout]'   => [
		'section' => 'vite[header-logo]',
		'type'    => 'vite-select',
		'title'   => __( 'Layout', 'vite' ),
		'default' => vite( 'customizer' )->get_defaults()['header-site-branding-layout'],
		'choices' => [
			'inline'  => __( 'Inline', 'vite' ),
			'stacked' => __( 'Stacked', 'vite' ),
		],
		'partial' => [
			'selector'        => '.site-branding',
			'render_callback' => function() {
				get_template_part( 'template-parts/header/header', 'logo' );
			},
		],
	],
	'vite[header-site-title-colors]'      => [
		'section'     => 'vite[header-logo]',
		'type'        => 'vite-color',
		'title'       => __( 'Site title colors', 'vite' ),
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
		'selectors'   => [ '.site-branding .site-title a' ],
		'properties'  => [ '' ],
	],
	'vite[header-site-title-typography]'  => [
		'default'     => vite( 'customizer' )->get_defaults()['header-site-title-typography'],
		'section'     => 'vite[header-logo]',
		'type'        => 'vite-typography',
		'title'       => __( 'Site title typography', 'vite' ),
		'input_attrs' => [],
		'selectors'   => [ '.site-branding .site-title' ],
	],
];

vite( 'customizer' )->add( 'settings', $options );