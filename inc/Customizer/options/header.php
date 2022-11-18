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
			'search'         => [
				'name'    => __( 'Search', 'vite' ),
				'section' => 'vite[header-search]',
			],
			'secondary-menu' => [
				'name'    => __( 'Secondary menu', 'vite' ),
				'section' => 'vite[header-secondary-menu]',
			],
			'social'         => [
				'name'    => __( 'Social', 'vite' ),
				'section' => 'vite[header-social]',
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
			'social'              => [
				'name'    => __( 'Social', 'vite' ),
				'section' => 'vite[header-social]',
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

vite( 'customizer' )->add(
	'settings',
	[
		'vite[header]'                         => [
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
		'vite[header-builder-components]'      => [
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
		'vite[mobile-logo]'                    => [
			'section'  => 'vite[header-logo]',
			'type'     => 'image',
			'title'    => __( 'Mobile Logo', 'vite' ),
			'height'   => 50,
			'width'    => 125,
			'priority' => 2,
		],
		'vite[header-background]'              => [
			'section'   => 'vite[header-builder-settings]',
			'type'      => 'vite-background',
			'title'     => __( 'Background', 'vite' ),
			'default'   => vite( 'customizer' )->get_defaults()['header-background'],
			'condition' => [
				'vite-tab' => 'design',
			],
			'selectors' => [ '.site-header' ],
		],
		'vite[header-html-1]'                  => [
			'section'     => 'vite[header-html-1]',
			'type'        => 'vite-editor',
			'title'       => __( 'HTML', 'vite' ),
			'description' => __( 'You can use HTML tags here.', 'vite' ),
			'default'     => vite( 'customizer' )->get_defaults()['header-html-1'],
			'condition'   => [
				'vite-tab' => 'general',
			],
			'partial'     => [
				'selector'            => '.header-html-1',
				'container_inclusive' => true,
				'render_callback'     => function() {
					get_template_part( 'template-parts/header/header', 'html', [ 'type' => 1 ] );
				},
			],
		],
		'vite[header-html-2]'                  => [
			'section'     => 'vite[header-html-2]',
			'type'        => 'vite-editor',
			'title'       => __( 'HTML', 'vite' ),
			'description' => __( 'You can use HTML tags here.', 'vite' ),
			'default'     => vite( 'customizer' )->get_defaults()['header-html-2'],
			'condition'   => [
				'vite-tab' => 'general',
			],
			'partial'     => [
				'selector'            => '.header-html-2',
				'container_inclusive' => true,
				'render_callback'     => function() {
					get_template_part( 'template-parts/header/header', 'html', [ 'type' => 2 ] );
				},
			],
		],
		'vite[header-top-row-layout]'          => [
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
		'vite[header-top-row-height]'          => [
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
		'vite[header-main-row-layout]'         => [
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
		'vite[header-main-row-height]'         => [
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
		'vite[header-bottom-row-layout]'       => [
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
		'vite[header-bottom-row-height]'       => [
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
		'vite[header-sticky]'                  => [
			'section'     => 'vite[header-builder-settings]',
			'type'        => 'vite-toggle',
			'title'       => __( 'Sticky', 'vite' ),
			'default'     => vite( 'customizer' )->get_defaults()['[header-sticky'] ?? false,
			'conditional' => [
				'vite-tab' => 'general',
			],
		],
		'vite[header-sticky-row]'              => [
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
				'vite-tab'            => 'general',
			],
		],
		'vite[header-sticky-enable]'           => [
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
				'vite-tab'            => 'general',
			],
		],
		'vite[header-transparent]'             => [
			'section' => 'vite[header-builder-settings]',
			'type'    => 'vite-toggle',
			'title'   => __( 'Transparent', 'vite' ),
			'default' => vite( 'customizer' )->get_defaults()['[header-transparent'] ?? false,
		],
		'vite[header-transparent-enable]'      => [
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
		'vite[header-site-branding-elements]'  => [
			'section' => 'vite[header-logo]',
			'type'    => 'vite-select',
			'title'   => __( 'Layout', 'vite' ),
			'default' => vite( 'customizer' )->get_defaults()['header-site-branding-elements'],
			'choices' => [
				'logo'                   => __( 'Logo', 'vite' ),
				'logo-title'             => __( 'Logo & Title', 'vite' ),
				'logo-title-description' => __( 'Logo & Title & Description', 'vite' ),
			],
			'partial' => [
				'selector'        => '.site-branding',
				'render_callback' => function () {
					get_template_part( 'template-parts/header/header', 'logo' );
				},
			],
		],
		'vite[header-site-branding-layout]'    => [
			'section' => 'vite[header-logo]',
			'type'    => 'vite-select',
			'title'   => __( 'Layout', 'vite' ),
			'default' => vite( 'customizer' )->get_defaults()['header-site-branding-layout'],
			'choices' => [
				'inline'  => __( 'Inline', 'vite' ),
				'stacked' => __( 'Stacked', 'vite' ),
			],
			'partial' => [
				'selector'        => '.site-branding',
				'render_callback' => function () {
					get_template_part( 'template-parts/header/header', 'logo' );
				},
			],
		],
		'vite[header-site-title-colors]'       => [
			'section'     => 'vite[header-logo]',
			'type'        => 'vite-color',
			'title'       => __( 'Site title colors', 'vite' ),
			'input_attrs' => [
				'colors' => [
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
			'selectors'   => [ '.site-branding .site-title a' ],
			'properties'  => [ '' ],
		],
		'vite[header-site-title-typography]'   => [
			'default'     => vite( 'customizer' )->get_defaults()['header-site-title-typography'],
			'section'     => 'vite[header-logo]',
			'type'        => 'vite-typography',
			'title'       => __( 'Site title typography', 'vite' ),
			'input_attrs' => [],
			'selectors'   => [ '.site-branding .site-title' ],
		],
		'vite[header-primary-menu]'            => [
			'section' => 'vite[header-primary-menu]',
			'type'    => 'vite-select',
			'title'   => __( 'Menu', 'vite' ),
			'default' => get_theme_mod( 'nav_menu_locations' )['primary'] ?? '0',
			'choices' => [ '0' => __( 'Default', 'vite' ) ] + vite( 'core' )->get_menus(),
			'partial' => [
				'selector'            => '.header-primary-menu',
				'container_inclusive' => true,
				'render_callback'     => function() {
					get_template_part( 'template-parts/header/header', 'primary-menu' );
				},
			],
		],
		'vite[header-secondary-menu]'          => [
			'section' => 'vite[header-secondary-menu]',
			'type'    => 'vite-select',
			'title'   => __( 'Menu', 'vite' ),
			'default' => get_theme_mod( 'nav_menu_locations' )['secondary'] ?? '0',
			'choices' => [ '0' => __( 'Default', 'vite' ) ] + vite( 'core' )->get_menus(),
			'partial' => [
				'selector'            => '.header-secondary-menu',
				'container_inclusive' => true,
				'render_callback'     => function() {
					get_template_part( 'template-parts/header/header', 'secondary-menu' );
				},
			],
		],
		'vite[header-primary-menu-navigate]'   => [
			'section'     => 'vite[header-primary-menu]',
			'type'        => 'vite-navigate',
			'input_attrs' => [
				'target_label' => __( 'Manage menu', 'vite' ),
				'target_id'    => 'menu_locations',
			],
		],
		'vite[header-button-1-text]'           => [
			'section' => 'vite[header-button-1]',
			'type'    => 'text',
			'title'   => __( 'Text', 'vite' ),
			'default' => vite( 'customizer' )->get_defaults()['header-button-1-text'],
			'partial' => [
				'selector'        => '.header-button-1',
				'render_callback' => function () {
					get_template_part( 'template-parts/header/header', 'button', [ 'type' => 1 ] );
				},
			],
		],
		'vite[header-button-1-link]'           => [
			'section' => 'vite[header-button-1]',
			'type'    => 'text',
			'title'   => __( 'Text', 'vite' ),
			'default' => vite( 'customizer' )->get_defaults()['header-button-1-link'],
			'partial' => [
				'selector'        => '.header-button-1',
				'render_callback' => function () {
					get_template_part( 'template-parts/header/header', 'button', [ 'type' => 1 ] );
				},
			],
		],
		'vite[header-button-1-target]'         => [
			'section' => 'vite[header-button-1]',
			'type'    => 'vite-toggle',
			'title'   => __( 'Open in new tab', 'vite' ),
			'default' => vite( 'customizer' )->get_defaults()['header-button-1-target'],
			'partial' => [
				'selector'        => '.header-button-1',
				'render_callback' => function () {
					get_template_part( 'template-parts/header/header', 'button', [ 'type' => 1 ] );
				},
			],
		],
		'vite[header-button-1-nofollow]'       => [
			'section' => 'vite[header-button-1]',
			'type'    => 'vite-toggle',
			'title'   => __( 'No follow', 'vite' ),
			'default' => vite( 'customizer' )->get_defaults()['header-button-1-nofollow'],
			'partial' => [
				'selector'        => '.header-button-1',
				'render_callback' => function () {
					get_template_part( 'template-parts/header/header', 'button', [ 'type' => 1 ] );
				},
			],
		],
		'vite[header-button-1-sponsored]'      => [
			'section' => 'vite[header-button-1]',
			'type'    => 'vite-toggle',
			'title'   => __( 'Sponsored', 'vite' ),
			'default' => vite( 'customizer' )->get_defaults()['header-button-1-sponsored'],
			'partial' => [
				'selector'        => '.header-button-1',
				'render_callback' => function () {
					get_template_part( 'template-parts/header/header', 'button', [ 'type' => 1 ] );
				},
			],
		],
		'vite[header-button-1-download]'       => [
			'section' => 'vite[header-button-1]',
			'type'    => 'vite-toggle',
			'title'   => __( 'Download', 'vite' ),
			'default' => vite( 'customizer' )->get_defaults()['header-button-1-download'],
			'partial' => [
				'selector'        => '.header-button-1',
				'render_callback' => function () {
					get_template_part( 'template-parts/header/header', 'button', [ 'type' => 1 ] );
				},
			],
		],
		'vite[header-button-1-style]'          => [
			'section' => 'vite[header-button-1]',
			'type'    => 'vite-buttonset',
			'title'   => __( 'Style', 'vite' ),
			'default' => vite( 'customizer' )->get_defaults()['header-button-1-style'],
			'partial' => [
				'selector'        => '.header-button-1',
				'render_callback' => function () {
					get_template_part( 'template-parts/header/header', 'button', [ 'type' => 1 ] );
				},
			],
			'choices' => [
				'filled'   => __( 'Filled', 'vite' ),
				'outlined' => __( 'Outlined', 'vite' ),
			],
		],
		'vite[header-button-2-text]'           => [
			'section' => 'vite[header-button-2]',
			'type'    => 'text',
			'title'   => __( 'Text', 'vite' ),
			'default' => vite( 'customizer' )->get_defaults()['header-button-2-text'],
			'partial' => [
				'selector'        => '.header-button-2',
				'render_callback' => function () {
					get_template_part( 'template-parts/header/header', 'button', [ 'type' => 2 ] );
				},
			],
		],
		'vite[header-button-2-link]'           => [
			'section' => 'vite[header-button-2]',
			'type'    => 'text',
			'title'   => __( 'Text', 'vite' ),
			'default' => vite( 'customizer' )->get_defaults()['header-button-2-link'],
			'partial' => [
				'selector'        => '.header-button-2',
				'render_callback' => function () {
					get_template_part( 'template-parts/header/header', 'button', [ 'type' => 2 ] );
				},
			],
		],
		'vite[header-button-2-target]'         => [
			'section' => 'vite[header-button-2]',
			'type'    => 'vite-toggle',
			'title'   => __( 'Open in new tab', 'vite' ),
			'default' => vite( 'customizer' )->get_defaults()['header-button-2-target'],
			'partial' => [
				'selector'        => '.header-button-2',
				'render_callback' => function () {
					get_template_part( 'template-parts/header/header', 'button', [ 'type' => 2 ] );
				},
			],
		],
		'vite[header-button-2-nofollow]'       => [
			'section' => 'vite[header-button-2]',
			'type'    => 'vite-toggle',
			'title'   => __( 'No follow', 'vite' ),
			'default' => vite( 'customizer' )->get_defaults()['header-button-2-nofollow'],
			'partial' => [
				'selector'        => '.header-button-2',
				'render_callback' => function () {
					get_template_part( 'template-parts/header/header', 'button', [ 'type' => 2 ] );
				},
			],
		],
		'vite[header-button-2-sponsored]'      => [
			'section' => 'vite[header-button-2]',
			'type'    => 'vite-toggle',
			'title'   => __( 'Sponsored', 'vite' ),
			'default' => vite( 'customizer' )->get_defaults()['header-button-2-sponsored'],
			'partial' => [
				'selector'        => '.header-button-2',
				'render_callback' => function () {
					get_template_part( 'template-parts/header/header', 'button', [ 'type' => 2 ] );
				},
			],
		],
		'vite[header-button-2-download]'       => [
			'section' => 'vite[header-button-2]',
			'type'    => 'vite-toggle',
			'title'   => __( 'Download', 'vite' ),
			'default' => vite( 'customizer' )->get_defaults()['header-button-2-download'],
			'partial' => [
				'selector'        => '.header-button-2',
				'render_callback' => function () {
					get_template_part( 'template-parts/header/header', 'button', [ 'type' => 2 ] );
				},
			],
		],
		'vite[header-button-2-style]'          => [
			'section' => 'vite[header-button-2]',
			'type'    => 'vite-buttonset',
			'title'   => __( 'Style', 'vite' ),
			'default' => vite( 'customizer' )->get_defaults()['header-button-2-style'],
			'partial' => [
				'selector'        => '.header-button-2',
				'render_callback' => function () {
					get_template_part( 'template-parts/header/header', 'button', [ 'type' => 2 ] );
				},
			],
			'choices' => [
				'filled'   => __( 'Filled', 'vite' ),
				'outlined' => __( 'Outlined', 'vite' ),
			],
		],
		'vite[header-mobile-menu]'             => [
			'section' => 'vite[header-mobile-menu]',
			'type'    => 'vite-select',
			'title'   => __( 'Menu', 'vite' ),
			'default' => get_theme_mod( 'nav_menu_locations' )['mobile'] ?? '0',
			'choices' => [ '0' => __( 'Default', 'vite' ) ] + vite( 'core' )->get_menus(),
			'partial' => [
				'selector'            => '.header-mobile-menu',
				'container_inclusive' => true,
				'render_callback'     => function() {
					get_template_part( 'template-parts/header/header', 'mobile-menu' );
				},
			],
		],
		'vite[header-search-label]'            => [
			'section' => 'vite[header-search]',
			'type'    => 'text',
			'title'   => __( 'Label', 'vite' ),
			'default' => vite( 'customizer' )->get_defaults()['header-search-label'],
			'partial' => [
				'selector'        => '.search-modal-trigger',
				'render_callback' => function () {
					get_template_part( 'template-parts/header/header', 'search' );
				},
			],
		],
		'vite[header-search-label-position]'   => [
			'section'   => 'vite[header-search]',
			'type'      => 'vite-buttonset',
			'title'     => __( 'Label Position', 'vite' ),
			'default'   => vite( 'customizer' )->get_defaults()['header-search-label-position'],
			'partial'   => [
				'selector'        => '.search-modal-trigger',
				'render_callback' => function () {
					get_template_part( 'template-parts/header/header', 'search' );
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
			'default'     => vite( 'customizer' )->get_defaults()['header-search-label-visibility'],
			'partial'     => [
				'selector'        => '.search-modal-trigger',
				'render_callback' => function () {
					get_template_part( 'template-parts/header/header', 'search' );
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
	]
);
