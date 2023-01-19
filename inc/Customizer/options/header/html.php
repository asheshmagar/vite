<?php
/**
 * Header HTML options.
 *
 * @package Vite
 */

$options = array_reduce(
	[ 1, 2 ],
	function ( $acc, $curr ) {
		$acc[ "vite[header-html-$curr]" ] = [
			'section'     => "vite[header-html-$curr]",
			'type'        => 'vite-editor',
			'title'       => __( 'HTML', 'vite' ),
			'default'     => vite( 'core' )->get_theme_mod_default( "header-html-$curr" ),
			'partial'     => [
				'selector'        => ".vite-header .vite-html--$curr",
				'render_callback' => function () use ( $curr ) {
					get_template_part(
						'template-parts/builder-elements/html',
						'',
						[
							'type'    => $curr,
							'context' => 'header',
						]
					);
				},
			],
			'description' => sprintf(
				/* Translators: %s: Smart tags description */
				__( 'Shortcodes and HTML tags are allowed. %s', 'vite' ),
				vite( 'core' )->description_smart_tags()
			),
		];
		$acc[ "vite[header-html-$curr-display]" ]    = [
			'section'     => "vite[header-html-$curr]",
			'type'        => 'vite-buttonset',
			'title'       => __( 'Display on', 'vite' ),
			'default'     => vite( 'core' )->get_theme_mod_defaults()[ "header-html-$curr-display" ],
			'partial'     => [
				'selector'        => ".vite-header .vite-html--$curr",
				'render_callback' => function () use ( $curr ) {
					get_template_part(
						'template-parts/builder-elements/html',
						'',
						[
							'type'    => $curr,
							'context' => 'header',
						]
					);
				},
			],
			'choices'     => [
				'desktop' => __( 'Desktop', 'vite' ),
				'tablet'  => __( 'Tablet', 'vite' ),
				'mobile'  => __( 'Mobile', 'vite' ),
			],
			'input_attrs' => [
				'multiple'  => true,
				'separator' => true,
			],
		];
		$acc[ "vite[header-html-$curr-text-color]" ] = [
			'section'     => "vite[header-html-$curr]",
			'type'        => 'vite-color',
			'title'       => __( 'Text Color', 'vite' ),
			'input_attrs' => [
				'separator' => true,
				'colors'    => [
					[
						'id'    => '--text--color',
						'label' => __( 'Normal', 'vite' ),
					],
				],
			],
			'css'         => [
				'selector' => ".vite-header .vite-html--$curr",
				'property' => 'color',
				'context'  => 'header',
			],
		];
		$acc[ "vite[header-html-$curr-link-color]" ] = [
			'section'     => "vite[header-html-$curr]",
			'type'        => 'vite-color',
			'title'       => __( 'Link Colors', 'vite' ),
			'input_attrs' => [
				'colors' => [
					[
						'id'    => '--link-color',
						'label' => __( 'Normal', 'vite' ),
					],
					[
						'id'    => '--link-hover-color',
						'label' => __( 'Hover', 'vite' ),
					],
				],
			],
			'css'         => [
				'selector' => ".vite-header .vite-html--$curr",
				'property' => 'color',
				'context'  => 'header',
			],
		];
		$acc[ "vite[header-html-$curr-typography]" ] = [
			'section'     => "vite[header-html-$curr]",
			'type'        => 'vite-typography',
			'title'       => __( 'Typography', 'vite' ),
			'input_attrs' => [
				'separator' => true,
			],
			'css'         => [
				'selector' => ".vite-header .vite-html--$curr",
				'context'  => 'header',
			],
		];
		$acc[ "vite[header-html-$curr-margin]" ]     = [
			'section'     => "vite[header-html-$curr]",
			'type'        => 'vite-dimensions',
			'title'       => __( 'Margin', 'vite' ),
			'input_attrs' => [
				'separator'  => true,
				'responsive' => true,
			],
			'css'         => [
				'selector' => ".vite-header .vite-html--$curr",
				'property' => 'margin',
				'context'  => 'header',
			],
		];

		return $acc;
	},
	[]
);

vite( 'customizer' )->add( 'settings', $options );
