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
		'label'    => __( 'Header', 'vite' ),
		'priority' => 10,
	),
	array(
		'name'     => 'vite[archive]',
		'type'     => 'section',
		'label'    => __( 'Archive/Blog', 'vite' ),
		'priority' => 10,
	),
	array(
		'name'     => 'vite[single]',
		'type'     => 'section',
		'label'    => __( 'Single Post', 'vite' ),
		'priority' => 10,
	),
];

$control_settings = [
	[
		'name'        => 'vite[global-palette]',
		'type'        => 'control',
		'section'     => 'vite[global-colors]',
		'control'     => 'vite-color',
		'label'       => __( 'Global Palette', 'vite' ),
		'priority'    => 10,
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
					'label' => __( 'Color 5', 'vite' ),
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
				[
					'id'    => '--global--color--9',
					'label' => __( 'Color 9', 'vite' ),
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
		'label'     => __( 'Base', 'vite' ),
		'priority'  => 10,
		'selectors' => [ 'body' ],
		'transport' => 'postMessage',
	],
	[
		'name'      => 'vite[h1-typography]',
		'type'      => 'control',
		'section'   => 'vite[global-typography]',
		'control'   => 'vite-typography',
		'label'     => __( 'Heading 1 (h1)', 'vite' ),
		'priority'  => 10,
		'selectors' => [ 'h1' ],
		'transport' => 'postMessage',
	],
	[
		'name'      => 'vite[h2-typography]',
		'type'      => 'control',
		'section'   => 'vite[global-typography]',
		'control'   => 'vite-typography',
		'label'     => __( 'Heading 2 (h2)', 'vite' ),
		'priority'  => 10,
		'selectors' => [ 'h2' ],
		'transport' => 'postMessage',
	],
	[
		'name'      => 'vite[h3-typography]',
		'type'      => 'control',
		'section'   => 'vite[global-typography]',
		'control'   => 'vite-typography',
		'label'     => __( 'Heading 3 (h3)', 'vite' ),
		'priority'  => 10,
		'selectors' => [ 'h3' ],
		'transport' => 'postMessage',
	],
	[
		'name'      => 'vite[h4-typography]',
		'type'      => 'control',
		'section'   => 'vite[global-typography]',
		'control'   => 'vite-typography',
		'label'     => __( 'Heading 4 (h4)', 'vite' ),
		'priority'  => 10,
		'selectors' => [ 'h4' ],
		'transport' => 'postMessage',
	],
	[
		'name'      => 'vite[h5-typography]',
		'type'      => 'control',
		'section'   => 'vite[global-typography]',
		'control'   => 'vite-typography',
		'label'     => __( 'Heading 5 (h5)', 'vite' ),
		'priority'  => 10,
		'selectors' => [ 'h5' ],
		'transport' => 'postMessage',
	],
	[
		'name'      => 'vite[h6-typography]',
		'type'      => 'control',
		'section'   => 'vite[global-typography]',
		'control'   => 'vite-typography',
		'label'     => __( 'Heading 6 (h6)', 'vite' ),
		'priority'  => 10,
		'selectors' => [ 'h6' ],
		'transport' => 'postMessage',
	],
	[
		'name'        => 'vite[header]',
		'type'        => 'control',
		'default'     => vite( 'customizer' )->get_defaults()['header'],
		'control'     => 'vite-builder',
		'label'       => __( 'Header builder', 'vite' ),
		'section'     => 'vite[header-builder]',
		'priority'    => 30,
		'choices'     => [
			'logo'                 => [
				'name'    => __( 'Logo', 'vite' ),
				'section' => 'label_tagline',
			],
			'primary-navigation'   => [
				'name'    => __( 'Primary Navigation', 'vite' ),
				'section' => 'vite_primary_navigation',
			],
			'secondary-navigation' => [
				'name'    => __( 'Secondary Navigation', 'vite' ),
				'section' => 'vite_secondary_navigation',
			],
			'search'               => [
				'name'    => __( 'Search', 'vite' ),
				'section' => 'vite_header_search',
			],
			'button'               => [
				'name'    => __( 'Button', 'vite' ),
				'section' => 'vite_header_button',
			],
			'social'               => [
				'name'    => __( 'Social', 'vite' ),
				'section' => 'vite_header_social',
			],
			'html'                 => [
				'name'    => __( 'HTML', 'vite' ),
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
	[
		'name'        => 'vite[archive-elements]',
		'type'        => 'control',
		'control'     => 'vite-sortable',
		'label'       => __( 'Archive elements', 'vite' ),
		'section'     => 'vite[archive]',
		'choices'     => [
			[
				'id'    => 'title',
				'label' => __( 'Title', 'vite' ),
			],
			[
				'id'    => 'featured-image',
				'label' => __( 'Featured Image', 'vite' ),
			],
			[
				'id'    => 'meta',
				'label' => __( 'Meta', 'vite' ),
			],
			[
				'id'    => 'excerpt',
				'label' => __( 'Excerpt', 'vite' ),
			],
			[
				'id'    => 'read-more',
				'label' => __( 'Read more', 'vite' ),
			],
		],
		'default'     => vite( 'customizer' )->get_defaults()['archive-elements'],
		'input_attrs' => [
			'idWithInnerItems' => 'meta',
			'innerItems'       => [
				[
					'id'    => 'author',
					'label' => __( 'Author', 'vite' ),
				],
				[
					'id'    => 'published-date',
					'label' => __( 'Published Date', 'vite' ),
				],
				[
					'id'    => 'updated-date',
					'label' => __( 'Updated Date', 'vite' ),
				],
				[
					'id'    => 'categories',
					'label' => __( 'Categories', 'vite' ),
				],
				[
					'id'    => 'tags',
					'label' => __( 'Tags', 'vite' ),
				],
				[
					'id'    => 'comments',
					'label' => __( 'Comments', 'vite' ),
				],
			],
		],
		'transport'   => 'postMessage',
		'partial'     => [
			'selector'            => '.vite-posts',
			'container_inclusive' => false,
			'render_callback'     => function() {
				vite( 'core' )->the_loop();
			},
		],
	],
	[
		'name'        => 'vite[single-header-elements]',
		'type'        => 'control',
		'control'     => 'vite-sortable',
		'label'       => __( 'Header/Title elements', 'vite' ),
		'section'     => 'vite[single]',
		'choices'     => [
			[
				'id'    => 'title',
				'label' => __( 'Title', 'vite' ),
			],
			[
				'id'    => 'meta',
				'label' => __( 'Meta', 'vite' ),
			],
			[
				'id'    => 'breadcrumbs',
				'label' => __( 'Breadcrumbs', 'vite' ),
			],
		],
		'default'     => vite( 'customizer' )->get_defaults()['single-header-elements'],
		'input_attrs' => [
			'idWithInnerItems' => 'meta',
			'innerItems'       => [
				[
					'id'    => 'author',
					'label' => __( 'Author', 'vite' ),
				],
				[
					'id'    => 'published-date',
					'label' => __( 'Published Date', 'vite' ),
				],
				[
					'id'    => 'updated-date',
					'label' => __( 'Updated Date', 'vite' ),
				],
				[
					'id'    => 'categories',
					'label' => __( 'Categories', 'vite' ),
				],
				[
					'id'    => 'tags',
					'label' => __( 'Tags', 'vite' ),
				],
			],
		],
		'transport'   => 'postMessage',
		'partial'     => [
			'selector'            => '.vite-single',
			'container_inclusive' => true,
			'render_callback'     => function() {
				get_template_part( 'template-parts/content/content', 'single' );
			},
		],
	],
];

return array_merge( $panel_settings, $section_settings, $control_settings );
