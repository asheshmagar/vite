<?php
/**
 * Header options.
 *
 * @package Vite
 */

$header_elements = apply_filters(
	'vite_header_builder_elements',
	[
		'desktop' => [
			'logo'           => [
				'name'    => __( 'Logo', 'vite' ),
				'section' => 'vite[header-logo]',
			],
			'primary-menu'   => [
				'name'    => __( 'Primary menu', 'vite' ),
				'section' => 'vite[header-primary-menu]',
			],
			'search'         => [
				'name'    => __( 'Search', 'vite' ),
				'section' => 'vite[header-search]',
			],
			'secondary-menu' => [
				'name'    => __( 'Secondary menu', 'vite' ),
				'section' => 'vite[header-secondary-menu]',
			],
			'socials'        => [
				'name'    => __( 'Socials', 'vite' ),
				'section' => 'vite[header-socials]',
			],
			'button-1'       => [
				'name'    => __( 'Button 1', 'vite' ),
				'section' => 'vite[header-button-1]',
			],
			'button-2'       => [
				'name'    => __( 'Button 2', 'vite' ),
				'section' => 'vite[header-button-2]',
			],
			'html-1'         => [
				'name'    => __( 'HTML 1', 'vite' ),
				'section' => 'vite[header-html-1]',
			],
			'html-2'         => [
				'name'    => __( 'HTML 2', 'vite' ),
				'section' => 'vite[header-html-2]',
			],
		],
		'mobile'  => [
			'logo'                => [
				'name'    => __( 'Logo', 'vite' ),
				'section' => 'vite[header-logo]',
			],
			'mobile-menu'         => [
				'name'    => __( 'Mobile menu', 'vite' ),
				'section' => 'vite[header-mobile-menu]',
			],
			'mobile-menu-trigger' => [
				'name'    => __( 'Trigger', 'vite' ),
				'section' => 'vite[header-mobile-menu-trigger]',
			],
			'search'              => [
				'name'    => __( 'Search', 'vite' ),
				'section' => 'vite[header-search]',
			],
			'socials'             => [
				'name'    => __( 'Socials', 'vite' ),
				'section' => 'vite[header-socials]',
			],
			'button-1'            => [
				'name'    => __( 'Button 1', 'vite' ),
				'section' => 'vite[header-button-1]',
			],
			'button-2'            => [
				'name'    => __( 'Button 2', 'vite' ),
				'section' => 'vite[header-button-2]',
			],
			'html-1'              => [
				'name'    => __( 'HTML 1', 'vite' ),
				'section' => 'vite[header-html-1]',
			],
			'html-2'              => [
				'name'    => __( 'HTML 2', 'vite' ),
				'section' => 'vite[header-html-2]',
			],
		],
	]
);

$options = [
	'vite[header]'                    => [
		'section'     => 'vite[header-builder]',
		'type'        => 'vite-header-builder',
		'title'       => __( 'Header', 'vite' ),
		'default'     => vite( 'customizer' )->get_defaults()['header'],
		'choices'     => $header_elements,
		'input_attrs' => [
			'areas'    => [
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
			'sections' => [
				'vite[header-builder-settings]',
			],
		],
		'partial'     => [
			'selector'            => '.site-header',
			'container_inclusive' => true,
			'render_callback'     => function() {
				get_template_part( 'template-parts/header/header', '' );
			},
		],
	],
	'vite[header-builder-components]' => [
		'section'     => 'vite[header-builder-settings]',
		'type'        => 'vite-builder-components',
		'title'       => __( 'Available Components', 'vite' ),
		'choices'     => $header_elements,
		'input_attrs' => [
			'group' => 'vite[header]',
		],
	],
	'vite[header-top-row-layout]'     => [
		'section' => 'vite[header-top-row]',
		'type'    => 'vite-buttonset',
		'title'   => __( 'Layout', 'vite' ),
		'default' => vite( 'customizer' )->get_defaults()['header-top-row-layout'],
		'choices' => [
			'contained'  => __( 'Contained', 'vite' ),
			'full-width' => __( 'Full width', 'vite' ),
		],
		'partial' => [
			'selector'            => '.site-header',
			'container_inclusive' => true,
			'render_callback'     => function() {
				get_template_part( 'template-parts/header/header', '' );
			},
		],
	],
	'vite[header-top-row-height]'     => [
		'section'     => 'vite[header-top-row]',
		'type'        => 'vite-slider',
		'title'       => __( 'Height', 'vite' ),
		'default'     => vite( 'customizer' )->get_defaults()['header-top-row-height'],
		'input_attrs' => [
			'min'        => 0,
			'max'        => 400,
			'step'       => 1,
			'units'      => [ 'px' ],
			'responsive' => true,
		],
		'selectors'   => [ '.site-header [data-row="top"] > .container' ],
		'properties'  => [ '--min--height' ],
	],
	'vite[header-main-row-layout]'    => [
		'section' => 'vite[header-main-row]',
		'type'    => 'vite-buttonset',
		'title'   => __( 'Layout', 'vite' ),
		'default' => vite( 'customizer' )->get_defaults()['header-main-row-layout'],
		'choices' => [
			'contained'  => __( 'Contained', 'vite' ),
			'full-width' => __( 'Full width', 'vite' ),
		],
		'partial' => [
			'selector'            => '.site-header',
			'container_inclusive' => true,
			'render_callback'     => function() {
				get_template_part( 'template-parts/header/header', '' );
			},
		],
	],
	'vite[header-main-row-height]'    => [
		'section'     => 'vite[header-main-row]',
		'type'        => 'vite-slider',
		'title'       => __( 'Height', 'vite' ),
		'default'     => vite( 'customizer' )->get_defaults()['header-main-row-height'],
		'input_attrs' => [
			'min'        => 0,
			'max'        => 400,
			'step'       => 1,
			'units'      => [ 'px' ],
			'responsive' => true,
		],
		'selectors'   => [ '.site-header [data-row="main"] > .container' ],
		'properties'  => [ '--min--height' ],
	],
	'vite[header-bottom-row-layout]'  => [
		'section' => 'vite[header-bottom-row]',
		'type'    => 'vite-buttonset',
		'title'   => __( 'Layout', 'vite' ),
		'default' => vite( 'customizer' )->get_defaults()['header-bottom-row-layout'],
		'choices' => [
			'contained'  => __( 'Contained', 'vite' ),
			'full-width' => __( 'Full width', 'vite' ),
		],
		'partial' => [
			'selector'            => '.site-header',
			'container_inclusive' => true,
			'render_callback'     => function() {
				get_template_part( 'template-parts/header/header', '' );
			},
		],
	],
	'vite[header-bottom-row-height]'  => [
		'section'     => 'vite[header-bottom-row]',
		'type'        => 'vite-slider',
		'title'       => __( 'Height', 'vite' ),
		'default'     => vite( 'customizer' )->get_defaults()['header-bottom-row-height'],
		'input_attrs' => [
			'min'        => 0,
			'max'        => 400,
			'step'       => 1,
			'units'      => [ 'px' ],
			'responsive' => true,
		],
		'selectors'   => [ '.site-header [data-row="bottom"] > .container' ],
		'properties'  => [ '--min--height' ],
	],
	'vite[header-sticky]'             => [
		'section'     => 'vite[header-builder-settings]',
		'type'        => 'vite-toggle',
		'title'       => __( 'Sticky', 'vite' ),
		'default'     => vite( 'customizer' )->get_defaults()['[header-sticky'] ?? false,
		'input_attrs' => [
			'separator' => true,
		],
	],
	'vite[header-sticky-row]'         => [
		'section'   => 'vite[header-builder-settings]',
		'type'      => 'vite-select',
		'title'     => __( 'Sticky row', 'vite' ),
		'default'   => vite( 'customizer' )->get_defaults()['header-sticky-row'],
		'choices'   => [
			'top'         => __( 'Top', 'vite' ),
			'main'        => __( 'Main', 'vite' ),
			'bottom'      => __( 'Bottom', 'vite' ),
			'top+main'    => __( 'Top + Main', 'vite' ),
			'top+bottom'  => __( 'Top + Bottom', 'vite' ),
			'main+bottom' => __( 'Main + Bottom', 'vite' ),
			'all'         => __( 'All', 'vite' ),
		],
		'condition' => [
			'vite[header-sticky]' => true,
		],
	],
	'vite[header-sticky-enable]'      => [
		'section'     => 'vite[header-builder-settings]',
		'type'        => 'vite-buttonset',
		'title'       => __( 'Enable on', 'vite' ),
		'default'     => vite( 'customizer' )->get_defaults()['header-sticky-enable'],
		'choices'     => [
			'desktop' => __( 'Desktop', 'vite' ),
			'tablet'  => __( 'Tablet', 'vite' ),
			'mobile'  => __( 'Mobile', 'vite' ),
		],
		'input_attrs' => [
			'multiple' => true,
		],
		'condition'   => [
			'vite[header-sticky]' => true,
		],
	],
	'vite[header-transparent]'        => [
		'section'     => 'vite[header-builder-settings]',
		'type'        => 'vite-toggle',
		'title'       => __( 'Transparent', 'vite' ),
		'default'     => vite( 'customizer' )->get_defaults()['[header-transparent'] ?? false,
		'input_attrs' => [
			'separator' => true,
		],
	],
	'vite[header-transparent-enable]' => [
		'section'     => 'vite[header-builder-settings]',
		'type'        => 'vite-buttonset',
		'title'       => __( 'Enable on', 'vite' ),
		'default'     => vite( 'customizer' )->get_defaults()['header-transparent-enable'],
		'choices'     => [
			'desktop' => __( 'Desktop', 'vite' ),
			'tablet'  => __( 'Tablet', 'vite' ),
			'mobile'  => __( 'Mobile', 'vite' ),
		],
		'input_attrs' => [
			'multiple' => true,
		],
		'condition'   => [
			'vite[header-transparent]' => true,
		],
	],
	'vite[header-background]'         => [
		'section'     => 'vite[header-builder-settings]',
		'type'        => 'vite-background',
		'title'       => __( 'Background', 'vite' ),
		'default'     => vite( 'customizer' )->get_defaults()['header-background'],
		'selectors'   => [ '.site-header' ],
		'input_attrs' => [
			'separator' => true,
		],
	],
];

vite( 'customizer' )->add( 'settings', $options );