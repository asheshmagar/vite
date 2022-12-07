<?php
/**
 *
 */

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
					get_template_part( 'template-parts/header/header', "menu-$curr" );
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
			'default'     => vite( 'customizer' )->get_defaults()[ "header-menu-$curr-colors" ],
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
			'selectors'   => [ ".vite-nav--$curr" ],
			'properties'  => [ '' ],
		];

		if ( in_array( $curr, [ '1', '2' ], true ) ) {
			$acc[ "vite[header-menu-$curr-items-spacing]" ] = [
				'section'     => "vite[header-menu-$curr]",
				'type'        => 'vite-slider',
				'title'       => __( 'Items Spacing', 'vite' ),
				'default'     => vite( 'customizer' )->get_defaults()[ "header-menu-$curr-items-spacing" ],
				'input_attrs' => [
					'min'  => 0,
					'max'  => 100,
					'step' => 1,
				],
				'selectors'   => [ ".vite-nav--$curr" ],
				'properties'  => [ '--items--spacing: {{value}}px;' ],
			];
		}

		$acc[ "vite[header-menu-$curr-typography]" ] = [
			'section'    => "vite[header-menu-$curr]",
			'type'       => 'vite-typography',
			'title'      => __( 'Typography', 'vite' ),
//			'default'    => vite( 'customizer' )->get_defaults()[ "header-menu-$curr-typography" ],
			'selectors'  => [ ".vite-nav--$curr" ],
			'properties' => [ '' ],
		];

		return $acc;
	},
	[]
);
