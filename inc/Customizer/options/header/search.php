<?php
/**
 * Header search options.
 *
 * @package Vite
 */

$options = [
	'vite[header-search-label]'            => [
		'section' => 'vite[header-search]',
		'type'    => 'vite-input',
		'title'   => __( 'Label', 'vite' ),
		'default' => vite( 'core' )->get_theme_mod_defaults()['header-search-label'],
		'partial' => [
			'selector'        => '.vite-search',
			'render_callback' => function () {
				get_template_part( 'template-parts/builder-elements/search', '' );
			},
		],
	],
	'vite[header-search-label-position]'   => [
		'section'   => 'vite[header-search]',
		'type'      => 'vite-buttonset',
		'title'     => __( 'Label Position', 'vite' ),
		'default'   => vite( 'core' )->get_theme_mod_defaults()['header-search-label-position'],
		'partial'   => [
			'selector'        => '.vite-search',
			'render_callback' => function () {
				get_template_part( 'template-parts/builder-elements/search', '' );
			},
		],
		'choices'   => [
			'left'   => __( 'Left', 'vite' ),
			'right'  => __( 'Right', 'vite' ),
			'bottom' => __( 'Bottom', 'vite' ),
		],
		'condition' => [
			'vite[header-search-label]!' => '',
		],
	],
	'vite[header-search-label-visibility]' => [
		'section'     => 'vite[header-search]',
		'type'        => 'vite-buttonset',
		'title'       => __( 'Label Visibility', 'vite' ),
		'default'     => vite( 'core' )->get_theme_mod_defaults()['header-search-label-visibility'],
		'partial'     => [
			'selector'        => '.vite-search',
			'render_callback' => function () {
				get_template_part( 'template-parts/builder-elements/search', '' );
			},
		],
		'choices'     => [
			'desktop' => __( 'Desktop', 'vite' ),
			'tablet'  => __( 'Tablet', 'vite' ),
			'mobile'  => __( 'Mobile', 'vite' ),
		],
		'input_attrs' => [
			'multiple' => true,
		],
		'condition'   => [
			'vite[header-search-label]!' => '',
		],
	],
	'vite[header-search-placeholder]'      => [
		'section' => 'vite[header-search]',
		'type'    => 'text',
		'title'   => __( 'Placeholder', 'vite' ),
		'default' => vite( 'core' )->get_theme_mod_defaults()['header-search-placeholder'],
		'partial' => [
			'selector'        => '.vite-search',
			'render_callback' => function () {
				get_template_part( 'template-parts/builder-elements/search', '' );
			},
		],
	],
];

vite( 'customizer' )->add( 'settings', $options );
