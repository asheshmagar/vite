<?php
/**
*
*/

defined( 'ABSPATH' ) || exit;

$options = [
	'vite[footer-menu-4]'               => [
		'section'     => 'vite[footer-menu-4]',
		'type'        => 'vite-select',
		'title'       => __( 'Primary Menu', 'vite' ),
		'default'     => ( get_theme_mod( 'nav_menu_locations' )['menu-4'] ?? '0' ),
		'choices'     => ( [ '0' => __( 'Default', 'vite' ) ] + vite( 'core' )->get_menus() ),
		'partial'     => [
			'selector'            => '.vite-nav--4',
			'container_inclusive' => true,
			'render_callback'     => function () {
				get_template_part(
					'template-parts/builder-elements/menu',
					'',
					[
						'type'    => '4',
						'context' => 'footer',
					]
				);

			},
		],
		'input_attrs' => [ 'allow_reset' => false ],
	],
	'vite[footer-menu-4-colors]'        => [
		'section'     => 'vite[footer-menu-4]',
		'type'        => 'vite-color',
		'title'       => __( 'Colors', 'vite' ),
		'default'     => vite( 'core' )->get_theme_mod_defaults()['footer-menu-4-colors'],
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
				[
					'id'    => '--link--active--color',
					'label' => __( 'Active', 'vite' ),
				],
			],
			'separator' => true,
		],
		'css'         => [
			'selector' => '.vite-nav--4',
			'property' => 'color',
		],
	],
	'vite[footer-menu-4-items-spacing]' => [
		'section'     => 'vite[footer-menu-4]',
		'type'        => 'vite-slider',
		'title'       => __( 'Items Spacing', 'vite' ),
		'default'     => vite( 'core' )->get_theme_mod_defaults()['footer-menu-4-items-spacing'],
		'input_attrs' => [
			'min'       => 0,
			'max'       => 100,
			'step'      => 1,
			'separator' => true,
			'units'     => [ 'px' ],
		],
		'css'         => [
			'selector' => '.vite-nav--4',
			'property' => '--items--spacing',
		],
	],
	'vite[footer-menu-4-typography]'    => [
		'section'     => 'vite[footer-menu-4]',
		'type'        => 'vite-typography',
		'title'       => __( 'Typography', 'vite' ),
		'selectors'   => [ '.vite-nav--4' ],
		'properties'  => [ '' ],
		'input_attrs' => [ 'separator' => true ],
		'css'         => [
			'selector' => '.vite-nav--4',
		],
	],
];

vite( 'customizer' )->add( 'settings', $options );
