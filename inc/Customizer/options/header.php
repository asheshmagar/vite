<?php

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
			'secondary-menu' => [
				'name'    => __( 'Secondary menu', 'vite' ),
				'section' => 'vite[header-secondary-menu]',
			],
			'search'         => [
				'name'    => __( 'Search', 'vite' ),
				'section' => 'vite[header-search]',
			],
			'button'         => [
				'name'    => __( 'Button', 'vite' ),
				'section' => 'vite[header-button]',
			],
			'social'         => [
				'name'    => __( 'Social', 'vite' ),
				'section' => 'vite[header-social]',
			],
			'html'           => [
				'name'    => __( 'HTML', 'vite' ),
				'section' => 'vite[header-html]',
			],
		],
		'mobile'  => [
			'logo'                => [
				'name'    => __( 'Logo', 'vite' ),
				'section' => 'vite[header-logo]',
			],
			'mobile-menu'         => [
				'name'    => __( 'Mobile menu', 'vite' ),
				'section' => 'vite[header][mobile-menu]',
			],
			'mobile-menu-trigger' => [
				'name'    => __( 'Trigger', 'vite' ),
				'section' => 'vite[header][mobile-menu-trigger]',
			],
			'button'              => [
				'name'    => __( 'Button', 'vite' ),
				'section' => 'vite[header][button]',
			],
			'social'              => [
				'name'    => __( 'Social', 'vite' ),
				'section' => 'vite[header][social]',
			],
			'html'                => [
				'name'    => __( 'HTML', 'vite' ),
				'section' => 'vite[header][html]',
			],
			'search'              => [
				'name'    => __( 'Search', 'vite' ),
				'section' => 'vite[header][search]',
			],
		],
	]
);

vite( 'customizer' )->add(
	'settings',
	[
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
		'vite[header-builder-tabs]'       => [
			'type'    => 'vite-tabs',
			'section' => 'vite[header-builder-settings]',
		],
		'vite[header-builder-components]' => [
			'section'     => 'vite[header-builder-settings]',
			'type'        => 'vite-builder-components',
			'title'       => __( 'Available Components', 'vite' ),
			'choices'     => $header_elements,
			'input_attrs' => [
				'group' => 'vite[header]',
			],
			'condition'   => [
				'vite-tab' => 'general',
			],
		],
		'vite[header-background]'         => [
			'section'   => 'vite[header-builder-settings]',
			'type'      => 'vite-background',
			'title'     => __( 'Background', 'vite' ),
			'default'   => vite( 'customizer' )->get_defaults()['header-background'],
			'condition' => [
				'vite-tab' => 'design',
			],
			'selectors' => [ '.site-header' ],
		],
		'vite[header-html]'               => [
			'section'     => 'vite[header-html]',
			'type'        => 'vite-editor',
			'title'       => __( 'HTML', 'vite' ),
			'description' => __( 'You can use HTML tags here.', 'vite' ),
			'default'     => vite( 'customizer' )->get_defaults()['header-html'],
			'condition'   => [
				'vite-tab' => 'general',
			],
			'partial'     => [
				'selector'            => '.header-html',
				'container_inclusive' => true,
				'render_callback'     => function() {
					get_template_part( 'template-parts/header/header', 'html' );
				},
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
				'noUnits'    => false,
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
				'noUnits'    => false,
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
				'noUnits'    => false,
				'units'      => [ 'px' ],
				'responsive' => true,
			],
			'selectors'   => [ '.site-header [data-row="bottom"] > .container' ],
			'properties'  => [ '--min--height' ],
		],
	]
);
