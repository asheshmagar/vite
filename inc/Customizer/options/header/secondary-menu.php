<?php
/**
 * Header secondary menu options.
 *
 * @package Vite
 */

$options = [
	'vite[header-secondary-menu]'               => [
		'section'     => 'vite[header-secondary-menu]',
		'type'        => 'vite-select',
		'title'       => __( 'Menu', 'vite' ),
		'default'     => get_theme_mod( 'nav_menu_locations' )['secondary'] ?? '0',
		'choices'     => [ '0' => __( 'Default', 'vite' ) ] + vite( 'core' )->get_menus(),
		'partial'     => [
			'selector'            => '.header-secondary-menu',
			'container_inclusive' => true,
			'render_callback'     => function() {
				get_template_part( 'template-parts/header/header', 'secondary-menu' );
			},
		],
		'input_attrs' => [
			'allow_reset' => false,
		],
	],
	'vite[header-secondary-menu-items-spacing]' => [
		'section'     => 'vite[header-secondary-menu]',
		'type'        => 'vite-slider',
		'title'       => __( 'Items Spacing', 'vite' ),
		'default'     => vite( 'customizer' )->get_defaults()['header-secondary-menu-items-spacing'],
		'input_attrs' => [
			'min'   => 0,
			'max'   => 100,
			'step'  => 1,
			'units' => [ 'px' ],
		],
		'selectors'   => [ '.secondary-menu > li > a' ],
		'properties'  => [ '--padding--inline' ],
	],
	'vite[header-secondary-menu-colors]'        => [
		'section'     => 'vite[header-secondary-menu]',
		'type'        => 'vite-color',
		'title'       => __( 'Colors', 'vite' ),
		'default'     => vite( 'customizer' )->get_defaults()['header-secondary-menu-colors'],
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
		'selectors'   => [ '.secondary-menu' ],
		'properties'  => [ '' ],
	],
];

vite( 'customizer' )->add( 'settings', $options );
