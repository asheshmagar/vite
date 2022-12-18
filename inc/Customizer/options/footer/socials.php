<?php
/**
 * Footer socials options.
 *
 * @package Vite
 */

$options = [
	'vite[footer-social-links]'            => [
		'section'     => 'vite[footer-socials]',
		'type'        => 'vite-sortable',
		'title'       => __( 'Socials Links', 'vite' ),
		'default'     => vite( 'core' )->get_theme_mod_defaults()['footer-social-links'],
		'partial'     => [
			'selector'        => '.vite-footer .vite-social',
			'render_callback' => function () {
				get_template_part( 'template-parts/builder-elements/socials', '', [ 'context' => 'footer' ] );

			},
		],
		'choices'     => vite( 'core' )->get_social_networks(),
		'input_attrs' => [
			'removable' => true,
		],
	],
	'vite[footer-social-icons-size]'       => [
		'section'     => 'vite[footer-socials]',
		'type'        => 'vite-slider',
		'title'       => __( 'Icons Size', 'vite' ),
		'default'     => vite( 'core' )->get_theme_mod_defaults()['footer-social-icons-size'],
		'partial'     => [
			'selector'        => '.vite-footer .vite-social',
			'render_callback' => function () {
				get_template_part( 'template-parts/builder-elements/socials', '', [ 'context' => 'footer' ] );

			},
		],
		'input_attrs' => [
			'min'       => 10,
			'max'       => 100,
			'step'      => 1,
			'separator' => true,
		],
	],
	'vite[footer-social-icons-color-type]' => [
		'section'     => 'vite[footer-socials]',
		'type'        => 'vite-buttonset',
		'title'       => __( 'Icons Color Type', 'vite' ),
		'default'     => vite( 'core' )->get_theme_mod_defaults()['footer-social-icons-color-type'],
		'partial'     => [
			'selector'        => '.vite-footer .vite-social',
			'render_callback' => function () {
				get_template_part( 'template-parts/builder-elements/socials', '', [ 'context' => 'footer' ] );

			},
		],
		'choices'     => [
			'custom' => __( 'Custom', 'vite' ),
			'brand'  => __( 'Brand', 'vite' ),
		],
		'condition'   => [
			'vite[footer-social-links]!' => [],
		],
		'input_attrs' => [
			'separator' => true,
			'cols'      => 2,
		],
	],
	'vite[footer-social-icons-colors]'     => [
		'section'     => 'vite[footer-socials]',
		'type'        => 'vite-color',
		'title'       => __( 'Icons Colors', 'vite' ),
		'default'     => vite( 'core' )->get_theme_mod_defaults()['footer-social-icons-colors'],
		'input_attrs' => [
			'colors'    => [
				[
					'id'    => '--link--color',
					'label' => __( 'Normal', 'vite' ),
				],
				[
					'id'    => '--link--hover--color',
					'label' => __( 'Hover', 'vite' ),
				],
			],
			'separator' => true,
		],
		'condition'   => [
			'vite[footer-social-icons-color-type]' => 'custom',
			'vite[footer-social-links]!'           => [],
		],
		'selectors'   => [ '.vite-footer .vite-social a' ],
		'properties'  => [ '' ],
	],
	'vite[footer-social-icons-spacing]'    => [
		'section'     => 'vite[footer-socials]',
		'type'        => 'vite-slider',
		'title'       => __( 'Icons Spacing', 'vite' ),
		'input_attrs' => [
			'min'       => 0,
			'max'       => 100,
			'step'      => 1,
			'units'     => [ 'px', 'rem', 'em' ],
			'separator' => true,
		],
		'selectors'   => [ '.vite-footer .vite-social__list' ],
		'properties'  => [ 'gap' ],
	],
	'vite[footer-social-icons-margin]'     => [
		'section'     => 'vite[footer-socials]',
		'type'        => 'vite-dimensions',
		'title'       => __( 'Margin', 'vite' ),
		'input_attrs' => [
			'units'      => [
				'px',
				'%',
				'em',
				'rem',
			],
			'responsive' => true,
			'separator'  => true,
		],
		'css'         => [
			'selector' => '.vite-footer .vite-social',
			'property' => 'margin',
			'context'  => 'footer',
		],
	],
];

vite( 'customizer' )->add( 'settings', $options );
