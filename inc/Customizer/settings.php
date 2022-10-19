<?php
/**
 * Settings.
 *
 * @package Vite
 */

$panel_settings = [
	[
		'name'     => 'vite[global]',
		'type'     => 'panel',
		'label'    => __( 'Global', 'vite' ),
		'priority' => 10,
	],
	[
		'name'     => 'vite[header]',
		'type'     => 'vite-builder-panel',
		'label'    => __( 'Header', 'vite' ),
		'priority' => 10,
	],
];

$section_settings = [
	[
		'name'     => 'vite[global-colors]',
		'type'     => 'section',
		'label'    => __( 'Colors', 'vite' ),
		'panel'    => 'vite[global]',
		'priority' => 10,
	],
	[
		'name'     => 'vite[global-typography]',
		'type'     => 'section',
		'label'    => __( 'Typography', 'vite' ),
		'panel'    => 'vite[global]',
		'priority' => 10,
	],
	array(
		'name'     => 'vite[header-builder]',
		'type'     => 'vite-builder-section',
		'panel'    => 'vite[header]',
		'label'    => esc_html__( 'Header', 'customind' ),
		'priority' => 10,
	),
];

$control_settings = [
	[
		'name'        => 'vite[global-palette]',
		'type'        => 'control',
		'section'     => 'vite[global-colors]',
		'control'     => 'vite-color',
		'label'       => esc_html__( 'Global Palette', 'customind' ),
		'priority'    => 10,
		'input_attrs' => [
			'colors' => [
				[
					'id'    => '--global--color--1',
					'label' => esc_html__( 'Color 1', 'customind' ),
				],
				[
					'id'    => '--global--color--2',
					'label' => esc_html__( 'Color 2', 'customind' ),
				],
				[
					'id'    => '--global--color--3',
					'label' => esc_html__( 'Color 3', 'customind' ),
				],
				[
					'id'    => '--global--color--4',
					'label' => esc_html__( 'Color 4', 'customind' ),
				],
				[
					'id'    => '--global--color--5',
					'label' => esc_html__( 'Color 5', 'customind' ),
				],
				[
					'id'    => '--global--color--6',
					'label' => esc_html__( 'Color 6', 'customind' ),
				],
				[
					'id'    => '--global--color--7',
					'label' => esc_html__( 'Color 7', 'customind' ),
				],
				[
					'id'    => '--global--color--8',
					'label' => esc_html__( 'Color 8', 'customind' ),
				],
				[
					'id'    => '--global--color--9',
					'label' => esc_html__( 'Color 9', 'customind' ),
				],
			],
		],
		'transport'   => 'postMessage',
		'selectors'   => [ ':root' ],
		'properties'  => [ '' ],
	],
	[
		'name'      => 'vite[base-typography]',
		'type'      => 'control',
		'section'   => 'vite[global-typography]',
		'control'   => 'vite-typography',
		'label'     => esc_html__( 'Base', 'customind' ),
		'priority'  => 10,
		'selectors' => [ 'body' ],
		'transport' => 'postMessage',
	],
	[
		'name'      => 'vite[h1-typography]',
		'type'      => 'control',
		'section'   => 'vite[global-typography]',
		'control'   => 'vite-typography',
		'label'     => esc_html__( 'Heading 1 (h1)', 'customind' ),
		'priority'  => 10,
		'selectors' => [ 'h1' ],
		'transport' => 'postMessage',
	],
	[
		'name'      => 'vite[h2-typography]',
		'type'      => 'control',
		'section'   => 'vite[global-typography]',
		'control'   => 'vite-typography',
		'label'     => esc_html__( 'Heading 2 (h2)', 'customind' ),
		'priority'  => 10,
		'selectors' => [ 'h2' ],
		'transport' => 'postMessage',
	],
	[
		'name'      => 'vite[h3-typography]',
		'type'      => 'control',
		'section'   => 'vite[global-typography]',
		'control'   => 'vite-typography',
		'label'     => esc_html__( 'Heading 3 (h3)', 'customind' ),
		'priority'  => 10,
		'selectors' => [ 'h3' ],
		'transport' => 'postMessage',
	],
	[
		'name'      => 'vite[h4-typography]',
		'type'      => 'control',
		'section'   => 'vite[global-typography]',
		'control'   => 'vite-typography',
		'label'     => esc_html__( 'Heading 4 (h4)', 'customind' ),
		'priority'  => 10,
		'selectors' => [ 'h4' ],
		'transport' => 'postMessage',
	],
	[
		'name'      => 'vite[h5-typography]',
		'type'      => 'control',
		'section'   => 'vite[global-typography]',
		'control'   => 'vite-typography',
		'label'     => esc_html__( 'Heading 5 (h5)', 'customind' ),
		'priority'  => 10,
		'selectors' => [ 'h5' ],
		'transport' => 'postMessage',
	],
	[
		'name'      => 'vite[h6-typography]',
		'type'      => 'control',
		'section'   => 'vite[global-typography]',
		'control'   => 'vite-typography',
		'label'     => esc_html__( 'Heading 6 (h6)', 'customind' ),
		'priority'  => 10,
		'selectors' => [ 'h6' ],
		'transport' => 'postMessage',
	],
	[
		'name'        => 'vite[header]',
		'type'        => 'control',
		'default'     => [
			'top'    => [
				'left'   => [],
				'center' => [],
				'right'  => [],
			],
			'main'   => [
				'left'   => [ 'logo' ],
				'center' => [],
				'right'  => [ 'primary-navigation', 'search' ],
			],
			'bottom' => [
				'left'   => [],
				'center' => [],
				'right'  => [],
			],
		],
		'control'     => 'vite-builder',
		'label'       => esc_html__( 'Header builder', 'vite' ),
		'section'     => 'vite[header-builder]',
		'priority'    => 30,
		'choices'     => [
			'logo'                 => [
				'name'    => esc_html__( 'Logo', 'vite' ),
				'section' => 'label_tagline',
			],
			'primary-navigation'   => [
				'name'    => esc_html__( 'Primary Navigation', 'vite' ),
				'section' => 'vite_primary_navigation',
			],
			'secondary-navigation' => [
				'name'    => esc_html__( 'Secondary Navigation', 'vite' ),
				'section' => 'vite_secondary_navigation',
			],
			'search'               => [
				'name'    => esc_html__( 'Search', 'vite' ),
				'section' => 'vite_header_search',
			],
			'button'               => [
				'name'    => esc_html__( 'Button', 'vite' ),
				'section' => 'vite_header_button',
			],
			'social'               => [
				'name'    => esc_html__( 'Social', 'vite' ),
				'section' => 'vite_header_social',
			],
			'html'                 => [
				'name'    => esc_html__( 'HTML', 'vite' ),
				'section' => 'vite_header_html',
			],
		],
		'input_attrs' => [
			'areas' => [
				'top'    => [
					'left'   => 'Top Left',
					'center' => 'Top Center',
					'right'  => 'Top Right',
				],
				'main'   => [
					'left'   => 'Main Left',
					'center' => 'Main center',
					'right'  => 'Main Right',
				],
				'bottom' => [
					'left'   => 'Bottom Left',
					'center' => 'Bottom Center',
					'right'  => 'Bottom Right',
				],
			],
		],
		'transport'   => 'postMessage',
		'partial'     => [
			'selector'            => '.site-header',
			'container_inclusive' => true,
			'render_callback'     => function() {
				get_template_part( 'template-parts/header/header', '' );
			},
		],
	],
];

return array_merge( $panel_settings, $section_settings, $control_settings );
