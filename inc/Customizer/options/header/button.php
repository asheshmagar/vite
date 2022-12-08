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
			'default' => vite( 'core' )->get_theme_mod_defaults()[ "header-button-$curr-text" ],
			'partial' => [
				'selector'        => ".vite-button--$curr",
				'render_callback' => function() use ( $curr ) {
					get_template_part( 'template-parts/header/header', 'button', [ 'type' => $curr ] );
				},
			],
		];
		$acc[ "vite[header-button-$curr-link]" ]          = [
			'section' => "vite[header-button-$curr]",
			'type'    => 'vite-input',
			'title'   => __( 'Link', 'vite' ),
			'default' => vite( 'core' )->get_theme_mod_defaults()[ "header-button-$curr-link" ],
			'partial' => [
				'selector'        => ".vite-button--$curr",
				'render_callback' => function() use ( $curr ) {
					get_template_part( 'template-parts/header/header', 'button', [ 'type' => $curr ] );
				},
			],
		];
		$acc[ "vite[header-button-$curr-target]" ]        = [
			'section' => "vite[header-button-$curr]",
			'type'    => 'vite-toggle',
			'title'   => __( 'Open in new tab', 'vite' ),
			'default' => vite( 'core' )->get_theme_mod_defaults()[ "header-button-$curr-target" ],
			'partial' => [
				'selector'        => ".vite-button--$curr",
				'render_callback' => function() use ( $curr ) {
					get_template_part( 'template-parts/header/header', 'button', [ 'type' => $curr ] );
				},
			],
		];
		$acc[ "vite[header-button-$curr-nofollow]" ]      = [
			'section' => "vite[header-button-$curr]",
			'type'    => 'vite-toggle',
			'title'   => __( 'No follow', 'vite' ),
			'default' => vite( 'core' )->get_theme_mod_defaults()[ "header-button-$curr-nofollow" ],
			'partial' => [
				'selector'        => ".vite-button--$curr",
				'render_callback' => function() use ( $curr ) {
					get_template_part( 'template-parts/header/header', 'button', [ 'type' => $curr ] );
				},
			],
		];
		$acc[ "vite[header-button-$curr-sponsored]" ]     = [
			'section' => "vite[header-button-$curr]",
			'type'    => 'vite-toggle',
			'title'   => __( 'Sponsored', 'vite' ),
			'default' => vite( 'core' )->get_theme_mod_defaults()[ "header-button-$curr-sponsored" ],
			'partial' => [
				'selector'        => ".vite-button--$curr",
				'render_callback' => function() use ( $curr ) {
					get_template_part( 'template-parts/header/header', 'button', [ 'type' => $curr ] );
				},
			],
		];
		$acc[ "vite[header-button-$curr-download]" ]      = [
			'section' => "vite[header-button-$curr]",
			'type'    => 'vite-toggle',
			'title'   => __( 'Download', 'vite' ),
			'default' => vite( 'core' )->get_theme_mod_defaults()[ "header-button-$curr-download" ],
			'partial' => [
				'selector'        => ".vite-button--$curr",
				'render_callback' => function() use ( $curr ) {
					get_template_part( 'template-parts/header/header', 'button', [ 'type' => $curr ] );
				},
			],
		];
		$acc[ "vite[header-button-$curr-style]" ]         = [
			'section' => "vite[header-button-$curr]",
			'type'    => 'vite-buttonset',
			'title'   => __( 'Style', 'vite' ),
			'default' => vite( 'core' )->get_theme_mod_defaults()[ "header-button-$curr-style" ],
			'partial' => [
				'selector'        => ".vite-button--$curr",
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
			'default'     => vite( 'core' )->get_theme_mod_defaults()[ "header-button-$curr-font-colors" ],
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
			'selectors'   => [ ".vite-button--$curr .vite-button__link" ],
			'properties'  => [ '' ],
		];
		$acc[ "vite[header-button-$curr-button-colors]" ] = [
			'section'     => "vite[header-button-$curr]",
			'type'        => 'vite-color',
			'title'       => __( 'Button colors', 'vite' ),
			'default'     => vite( 'core' )->get_theme_mod_defaults()[ "header-button-$curr-button-colors" ],
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
			'selectors'   => [ ".vite-button--$curr .vite-button__link" ],
			'properties'  => [ '' ],
		];
		$acc[ "vite[header-button-$curr-radius]" ]        = [
			'section'     => "vite[header-button-$curr]",
			'type'        => 'vite-dimensions',
			'title'       => __( 'Button radius', 'vite' ),
			'default'     => vite( 'core' )->get_theme_mod_defaults()[ "header-button-$curr-radius" ],
			'input_attrs' => [
				'units'      => [
					'px',
					'%',
					'em',
					'rem',
				],
				'responsive' => true,
			],
			'selectors'   => [ ".vite-button--$curr .vite-button__link" ],
			'properties'  => [ '--button--border--radius' ],
		];

		return $acc;
	},
	[]
);

vite( 'customizer' )->add( 'settings', $options );
