<?php
/**
 *
 */

defined( 'ABSPATH' ) || exit;

$menus = [
	'1' => __( 'Primary Menu', 'vite' ),
	'2' => __( 'Secondary Menu', 'vite' ),
	'3' => __( 'Mobile Menu', 'vite' ),
];

$options = array_reduce(
	array_keys( $menus ),
	function( $acc, $curr ) use ( $menus ) {
		$acc[ "vite[header-menu-$curr]" ] = [
			'section'     => "vite[header-menu-$curr]",
			'type'        => 'vite-select',
			'title'       => $menus[ $curr ],
			'default'     => get_theme_mod( 'nav_menu_locations' )[ "menu-$curr" ] ?? '0',
			'choices'     => [ '0' => __( 'Default', 'vite' ) ] + vite( 'core' )->get_menus(),
			'partial'     => [
				'selector'            => ".vite-nav--$curr",
				'container_inclusive' => true,
				'render_callback'     => function() use ( $curr ) {
					get_template_part( 'template-parts/builder-elements/menu', '', [ 'type' => $curr ] );
				},
			],
			'input_attrs' => [
				'allow_reset' => false,
			],
		];

		$acc[ "vite[header-menu-$curr-colors]" ] = [
			'section'     => "vite[header-menu-$curr]",
			'type'        => 'vite-color',
			'title'       => __( 'Colors', 'vite' ),
			'default'     => vite( 'core' )->get_theme_mod_defaults()[ "header-menu-$curr-colors" ],
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
				'selector' => ".vite-nav--$curr",
				'property' => 'color',
			],
		];

		if ( in_array( (string) $curr, [ '1', '2' ], true ) ) {
			$acc[ "vite[header-menu-$curr-items-spacing]" ] = [
				'section'     => "vite[header-menu-$curr]",
				'type'        => 'vite-slider',
				'title'       => __( 'Items Spacing', 'vite' ),
				'default'     => vite( 'core' )->get_theme_mod_defaults()[ "header-menu-$curr-items-spacing" ],
				'input_attrs' => [
					'min'       => 0,
					'max'       => 100,
					'step'      => 1,
					'separator' => true,
					'units'     => [ 'px' ],
				],
				'css'         => [
					'selector' => ".vite-nav--$curr .vite-nav__link",
					'property' => '--padding--inline',
				],
			];
		}

		$acc[ "vite[header-menu-$curr-typography]" ] = [
			'section'     => "vite[header-menu-$curr]",
			'type'        => 'vite-typography',
			'title'       => __( 'Typography', 'vite' ),
			'selectors'   => [ ".vite-nav--$curr" ],
			'properties'  => [ '' ],
			'input_attrs' => [
				'separator' => true,
			],
			'css'         => [
				'selector' => ".vite-nav--$curr",
			],
		];

		return $acc;
	},
	[]
);

vite( 'customizer' )->add( 'settings', $options );
