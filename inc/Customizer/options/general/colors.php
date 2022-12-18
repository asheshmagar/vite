<?php
/**
 * Global colors options.
 *
 * @package Vite
 */

$options = [
	'vite[global-palette]' => [
		'title'       => __( 'Global', 'vite' ),
		'type'        => 'vite-color',
		'default'     => vite( 'core' )->get_theme_mod_defaults()['global-palette'],
		'input_attrs' => [
			'colors' => [
				[
					'id'    => '--global--color--1',
					'label' => __( 'Color 1', 'vite' ),
				],
				[
					'id'    => '--global--color--2',
					'label' => __( 'Color 2', 'vite' ),
				],
				[
					'id'    => '--global--color--3',
					'label' => __( 'Color 3', 'vite' ),
				],
				[
					'id'    => '--global--color--4',
					'label' => __( 'Color 4', 'vite' ),
				],
				[
					'id'    => '--global--color--5',
					'label' => __( 'Color 6', 'vite' ),
				],
				[
					'id'    => '--global--color--6',
					'label' => __( 'Color 6', 'vite' ),
				],
				[
					'id'    => '--global--color--7',
					'label' => __( 'Color 7', 'vite' ),
				],
				[
					'id'    => '--global--color--8',
					'label' => __( 'Color 8', 'vite' ),
				],
			],
		],
		'section'     => 'vite[global-colors]',
		'css'         => [
			'selector' => ':root',
			'context'  => 'global',
		],
	],
	'vite[text-color]'     => [
		'section'     => 'vite[global-colors]',
		'type'        => 'vite-color',
		'title'       => __( 'Text color', 'vite' ),
		'default'     => vite( 'core' )->get_theme_mod_defaults()['text-color'],
		'input_attrs' => [
			'colors'    => [
				[
					'id'    => '--text--color',
					'label' => __( 'Normal', 'vite' ),
				],
			],
			'separator' => true,
		],
		'transport'   => 'postMessage',
		'css'         => [
			'selector' => ':root',
			'context'  => 'global',
		],
	],
	'vite[link-colors]'    => [
		'section'     => 'vite[global-colors]',
		'type'        => 'vite-color',
		'title'       => __( 'Link colors', 'vite' ),
		'default'     => vite( 'core' )->get_theme_mod_defaults()['text-color'],
		'input_attrs' => [
			'separator' => true,
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
		],
		'transport'   => 'postMessage',
		'css'         => [
			'selector' => ':root',
			'context'  => 'global',
		],
	],
	'vite[heading-color]'  => [
		'section'     => 'vite[global-colors]',
		'type'        => 'vite-color',
		'title'       => __( 'Heading color', 'vite' ),
		'default'     => vite( 'core' )->get_theme_mod_defaults()['heading-color'],
		'input_attrs' => [
			'separator' => true,
			'colors'    => [
				[
					'id'    => '--heading--color',
					'label' => __( 'Normal', 'vite' ),
				],
			],
		],
		'transport'   => 'postMessage',
		'css'         => [
			'selector' => ':root',
			'context'  => 'global',
		],
	],
	'vite[button-colors]'  => [
		'section'     => 'vite[global-colors]',
		'type'        => 'vite-color',
		'title'       => __( 'Button colors', 'vite' ),
		'default'     => vite( 'core' )->get_theme_mod_defaults()['button-colors'],
		'input_attrs' => [
			'separator' => true,
			'colors'    => [
				[
					'id'    => '--button--color',
					'label' => __( 'Normal', 'vite' ),
				],
				[
					'id'    => '--button--hover--color',
					'label' => __( 'Hover', 'vite' ),
				],
				[
					'id'    => '--button--bg--color',
					'label' => __( 'Normal background', 'vite' ),
				],
				[
					'id'    => '--button--hover--bg--color',
					'label' => __( 'Hover background', 'vite' ),
				],
			],
		],
		'transport'   => 'postMessage',
		'css'         => [
			'selector' => ':root',
			'context'  => 'global',
		],
	],
	'vite[border-color]'   => [
		'section'     => 'vite[global-colors]',
		'type'        => 'vite-color',
		'title'       => __( 'Border color', 'vite' ),
		'default'     => vite( 'core' )->get_theme_mod_defaults()['border-color'],
		'input_attrs' => [
			'separator' => true,
			'colors'    => [
				[
					'id'    => '--border--color',
					'label' => __( 'Border color', 'vite' ),
				],
			],
		],
		'transport'   => 'postMessage',
		'css'         => [
			'selector' => ':root',
			'context'  => 'global',
		],
	],
];

vite( 'customizer' )->add( 'settings', $options );
