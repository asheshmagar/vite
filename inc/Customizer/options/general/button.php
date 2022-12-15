<?php
/**
 * General options.
 *
 * @package Vite
 */

$options = [
	'vite[buttons-color]'         => [
		'section'     => 'vite[global-buttons]',
		'type'        => 'vite-color',
		'title'       => __( 'Colors', 'vite' ),
		'default'     => vite( 'core' )->get_theme_mod_defaults()['buttons-colors'],
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
	],
	'vite[buttons-bg-color]'      => [
		'section'     => 'vite[global-buttons]',
		'type'        => 'vite-color',
		'title'       => __( 'Background Colors', 'vite' ),
		'default'     => vite( 'core' )->get_theme_mod_defaults()['buttons-bg-colors'],
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
	],
	'vite[buttons-padding]'       => [
		'section'     => 'vite[global-buttons]',
		'type'        => 'vite-dimensions',
		'title'       => __( 'Padding', 'vite' ),
		'default'     => vite( 'core' )->get_theme_mod_defaults()['buttons-padding'],
		'input_attrs' => [
			'units'      => [ 'px', 'rem', 'em' ],
			'responsive' => true,
			'separator'  => true,
		],
	],
	'vite[buttons-border]'        => [
		'section'     => 'vite[global-buttons]',
		'type'        => 'vite-border',
		'title'       => __( 'Border', 'vite' ),
		'default'     => vite( 'core' )->get_theme_mod_defaults()['buttons-border'],
		'input_attrs' => [
			'separator' => true,
		],
	],
	'vite[buttons-border-radius]' => [
		'section'     => 'vite[global-buttons]',
		'type'        => 'vite-dimensions',
		'title'       => __( 'Border Radius', 'vite' ),
		'default'     => vite( 'core' )->get_theme_mod_defaults()['buttons-border-radius'],
		'input_attrs' => [
			'units'      => [ 'px', 'rem', 'em' ],
			'responsive' => true,
			'separator'  => true,
		],
	],
	'vite[buttons-typography]'    => [
		'section'     => 'vite[global-buttons]',
		'type'        => 'vite-typography',
		'title'       => __( 'Typography', 'vite' ),
		'input_attrs' => [
			'separator' => true,
		],
	],
];

vite( 'customizer' )->add( 'settings', $options );
