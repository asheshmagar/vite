<?php
/**
 * Header HTML options.
 *
 * @package Vite
 */

$options = array_reduce(
	[ 1, 2 ],
	function( $acc, $curr ) {
		$acc[ "vite[footer-html-$curr]" ]            = [
			'section' => "vite[footer-html-$curr]",
			'type'    => 'vite-editor',
			'title'   => __( 'HTML', 'vite' ),
			'default' => vite( 'core' )->get_theme_mod_defaults()[ "footer-html-$curr" ],
			'partial' => [
				'selector'        => ".vite-footer .vite-html--$curr",
				'render_callback' => function() use ( $curr ) {
					get_template_part(
						'template-parts/builder-elements/html',
						'',
						[
							'type'    => $curr,
							'context' => 'footer',
						]
					);
				},
			],
		];
		$acc[ "vite[footer-html-$curr-alignment]" ]  = [
			'section'     => "vite[footer-html-$curr]",
			'type'        => 'vite-buttonset',
			'title'       => __( 'Alignment', 'vite' ),
			'default'     => vite( 'core' )->get_theme_mod_defaults()[ "footer-html-$curr-alignment" ],
			'partial'     => [
				'selector'        => ".vite-footer .vite-html--$curr",
				'render_callback' => function() use ( $curr ) {
					get_template_part(
						'template-parts/builder-elements/html',
						'',
						[
							'type'    => $curr,
							'context' => 'footer',
						]
					);
				},
			],
			'choices'     => [
				'left'   => __( 'Left', 'vite' ),
				'center' => __( 'Center', 'vite' ),
				'right'  => __( 'Right', 'vite' ),
			],
			'input_attrs' => [
				'responsive' => true,
				'separator'  => true,
			],
		];
		$acc[ "vite[footer-html-$curr-display]" ]    = [
			'section'     => "vite[footer-html-$curr]",
			'type'        => 'vite-buttonset',
			'title'       => __( 'Display on', 'vite' ),
			'default'     => vite( 'core' )->get_theme_mod_defaults()[ "footer-html-$curr-display" ],
			'partial'     => [
				'selector'        => ".vite-footer .vite-html--$curr",
				'render_callback' => function() use ( $curr ) {
					get_template_part(
						'template-parts/builder-elements/html',
						'',
						[
							'type'    => $curr,
							'context' => 'footer',
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
		$acc[ "vite[footer-html-$curr-text-color]" ] = [
			'section'     => "vite[footer-html-$curr]",
			'type'        => 'vite-color',
			'title'       => __( 'Text Color', 'vite' ),
			'input_attrs' => [
				'separator' => true,
				'colors'    => [
					[
						'id'    => 'normal',
						'label' => __( 'Normal', 'vite' ),
					],
				],
			],
		];
		$acc[ "vite[footer-html-$curr-link-color]" ] = [
			'section'     => "vite[footer-html-$curr]",
			'type'        => 'vite-color',
			'title'       => __( 'Link Colors', 'vite' ),
			'input_attrs' => [
				'colors' => [
					[
						'id'    => 'normal',
						'label' => __( 'Normal', 'vite' ),
					],
					[
						'id'    => 'hover',
						'label' => __( 'Hover', 'vite' ),
					],
				],
			],
		];
		$acc[ "vite[footer-html-$curr-typography]" ] = [
			'section'     => "vite[footer-html-$curr]",
			'type'        => 'vite-typography',
			'title'       => __( 'Typography', 'vite' ),
			'input_attrs' => [
				'separator' => true,
			],
		];
		$acc[ "vite[footer-html-$curr-margin]" ]     = [
			'section'     => "vite[footer-html-$curr]",
			'type'        => 'vite-dimensions',
			'title'       => __( 'Margin', 'vite' ),
			'input_attrs' => [
				'separator'  => true,
				'responsive' => true,
			],
		];
		return $acc;
	},
	[]
);

vite( 'customizer' )->add( 'settings', $options );
