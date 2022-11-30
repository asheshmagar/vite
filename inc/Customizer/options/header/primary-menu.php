<?php
/**
 * Header primary menu options.
 *
 * @package Vite
 */

$options = [
	'vite[header-primary-menu]'               => [
		'section'     => 'vite[header-primary-menu]',
		'type'        => 'vite-select',
		'title'       => __( 'Menu', 'vite' ),
		'default'     => get_theme_mod( 'nav_menu_locations' )['primary'] ?? '0',
		'choices'     => [ '0' => __( 'Default', 'vite' ) ] + vite( 'core' )->get_menus(),
		'partial'     => [
			'selector'            => '.header-primary-menu',
			'container_inclusive' => true,
			function() {
				get_template_part( 'template-parts/header/header', 'primary-menu' );
			},
		],
		'input_attrs' => [
			'allow_reset' => false,
		],
	],
	'vite[header-primary-menu-items-spacing]' => [
		'section'     => 'vite[header-primary-menu]',
		'type'        => 'vite-slider',
		'title'       => __( 'Items Spacing', 'vite' ),
		'default'     => vite( 'customizer' )->get_defaults()['header-primary-menu-items-spacing'],
		'input_attrs' => [
			'min'   => 0,
			'max'   => 100,
			'step'  => 1,
			'units' => [ 'px' ],
		],
		'selectors'   => [ '.primary-menu > li > a' ],
		'properties'  => [ '--padding--inline' ],
	],
	'vite[header-primary-menu-colors]'        => [
		'section'     => 'vite[header-primary-menu]',
		'type'        => 'vite-color',
		'title'       => __( 'Colors', 'vite' ),
		'default'     => vite( 'customizer' )->get_defaults()['header-primary-menu-colors'],
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
				[
					'id'    => '--link--active--color',
					'label' => __( 'Active', 'vite' ),
				],
			],
		],
		'selectors'   => [ '.primary-menu' ],
		'properties'  => [ '' ],
	],
	'vite[header-primary-menu-typography]'    => [
		'section'    => 'vite[header-primary-menu]',
		'type'       => 'vite-typography',
		'title'      => __( 'Typography', 'vite' ),
		'selectors'  => [ '.primary-menu li a' ],
		'properties' => [ '' ],
	],
	'vite[header-primary-menu-divider]'       => [
		'section' => 'vite[header-primary-menu]',
		'type'    => 'vite-divider',
		'title'   => __( 'Dropdown Menu', 'vite' ),
	],
];

vite( 'customizer' )->add( 'settings', $options );
