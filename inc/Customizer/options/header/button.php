<?php
/**
 * Header button options.
 *
 * @package Vite
 */

$options = array_reduce(
	[ 1, 2 ],
	function( $acc, $curr ) {
		$acc[ "vite[header-button-$curr-text]" ]          = [
			'section' => "vite[header-button-$curr]",
			'type'    => 'vite-input',
			'title'   => __( 'Text', 'vite' ),
			'default' => vite( 'customizer' )->get_defaults()[ "header-button-$curr-text" ],
			'partial' => [
				'selector'        => ".header-button-$curr",
				'render_callback' => function() use ( $curr ) {
					get_template_part( 'template-parts/header/header', 'button', [ 'type' => $curr ] );
				},
			],
		];
		$acc[ "vite[header-button-$curr-link]" ]          = [
			'section' => "vite[header-button-$curr]",
			'type'    => 'vite-input',
			'title'   => __( 'Link', 'vite' ),
			'default' => vite( 'customizer' )->get_defaults()[ "header-button-$curr-link" ],
			'partial' => [
				'selector'        => ".header-button-$curr",
				'render_callback' => function() use ( $curr ) {
					get_template_part( 'template-parts/header/header', 'button', [ 'type' => $curr ] );
				},
			],
		];
		$acc[ "vite[header-button-$curr-target]" ]        = [
			'section' => "vite[header-button-$curr]",
			'type'    => 'vite-toggle',
			'title'   => __( 'Open in new tab', 'vite' ),
			'default' => vite( 'customizer' )->get_defaults()[ "header-button-$curr-target" ],
			'partial' => [
				'selector'        => ".header-button-$curr",
				'render_callback' => function() use ( $curr ) {
					get_template_part( 'template-parts/header/header', 'button', [ 'type' => $curr ] );
				},
			],
		];
		$acc[ "vite[header-button-$curr-nofollow]" ]      = [
			'section' => "vite[header-button-$curr]",
			'type'    => 'vite-toggle',
			'title'   => __( 'No follow', 'vite' ),
			'default' => vite( 'customizer' )->get_defaults()[ "header-button-$curr-nofollow" ],
			'partial' => [
				'selector'        => ".header-button-$curr",
				'render_callback' => function() use ( $curr ) {
					get_template_part( 'template-parts/header/header', 'button', [ 'type' => $curr ] );
				},
			],
		];
		$acc[ "vite[header-button-$curr-sponsored]" ]     = [
			'section' => "vite[header-button-$curr]",
			'type'    => 'vite-toggle',
			'title'   => __( 'Sponsored', 'vite' ),
			'default' => vite( 'customizer' )->get_defaults()[ "header-button-$curr-sponsored" ],
			'partial' => [
				'selector'        => ".header-button-$curr",
				'render_callback' => function() use ( $curr ) {
					get_template_part( 'template-parts/header/header', 'button', [ 'type' => $curr ] );
				},
			],
		];
		$acc[ "vite[header-button-$curr-download]" ]      = [
			'section' => "vite[header-button-$curr]",
			'type'    => 'vite-toggle',
			'title'   => __( 'Download', 'vite' ),
			'default' => vite( 'customizer' )->get_defaults()[ "header-button-$curr-download" ],
			'partial' => [
				'selector'        => ".header-button-$curr",
				'render_callback' => function() use ( $curr ) {
					get_template_part( 'template-parts/header/header', 'button', [ 'type' => $curr ] );
				},
			],
		];
		$acc[ "vite[header-button-$curr-style]" ]         = [
			'section' => "vite[header-button-$curr]",
			'type'    => 'vite-buttonset',
			'title'   => __( 'Style', 'vite' ),
			'default' => vite( 'customizer' )->get_defaults()[ "header-button-$curr-style" ],
			'partial' => [
				'selector'        => ".header-button-$curr",
				'render_callback' => function() use ( $curr ) {
					get_template_part( 'template-parts/header/header', 'button', [ 'type' => $curr ] );
				},
			],
			'choices' => [
				'filled'   => __( 'Filled', 'vite' ),
				'outlined' => __( 'Outlined', 'vite' ),
			],
		];
		$acc[ "vite[header-button-$curr-font-colors]" ]   = [
			'section'     => "vite[header-button-$curr]",
			'type'        => 'vite-color',
			'title'       => __( 'Font colors', 'vite' ),
			'default'     => vite( 'customizer' )->get_defaults()[ "header-button-$curr-font-colors" ],
			'input_attrs' => [
				'colors' => [
					[
						'id'    => '--button--color',
						'label' => __( 'Normal', 'vite' ),
					],
					[
						'id'    => '--button--hover--color',
						'label' => __( 'Hover', 'vite' ),
					],
				],
			],
			'selectors'   => [ ".header-button-$curr .button" ],
			'properties'  => [ '' ],
		];
		$acc[ "vite[header-button-$curr-button-colors]" ] = [
			'section'     => "vite[header-button-$curr]",
			'type'        => 'vite-color',
			'title'       => __( 'Button colors', 'vite' ),
			'default'     => vite( 'customizer' )->get_defaults()[ "header-button-$curr-button-colors" ],
			'input_attrs' => [
				'colors' => [
					[
						'id'    => '--button--bg--color',
						'label' => __( 'Normal', 'vite' ),
					],
					[
						'id'    => '--button--hover--bg--color',
						'label' => __( 'Hover', 'vite' ),
					],
				],
			],
			'selectors'   => [ ".header-button-$curr .button" ],
			'properties'  => [ '' ],
		];
		$acc[ "vite[header-button-$curr-radius]" ]        = [
			'section'     => "vite[header-button-$curr]",
			'type'        => 'vite-dimensions',
			'title'       => __( 'Button radius', 'vite' ),
			'default'     => vite( 'customizer' )->get_defaults()[ "header-button-$curr-radius" ],
			'input_attrs' => [
				'units'      => [
					'px',
					'%',
					'em',
					'rem',
				],
				'responsive' => true,
			],
			'selectors'   => [ ".header-button-$curr .button" ],
			'properties'  => [ '--button--border--radius' ],
		];

		return $acc;
	},
	[]
);

vite( 'customizer' )->add( 'settings', $options );
