<?php
/**
 * Header options.
 *
 * @package Vite
 */

defined( 'ABSPATH' ) || exit;

$core = vite( 'core' );

$header_elements = $core->filter(
	'builder/header/elements',
	[
		'desktop' => [
			'logo'     => [
				'name'    => __( 'Logo', 'vite' ),
				'section' => 'vite[header-logo]',
			],
			'menu-1'   => [
				'name'    => __( 'Primary Menu', 'vite' ),
				'section' => 'vite[header-menu-1]',
			],
			'search'   => [
				'name'    => __( 'Search', 'vite' ),
				'section' => 'vite[header-search]',
			],
			'menu-2'   => [
				'name'    => __( 'Secondary Menu', 'vite' ),
				'section' => 'vite[header-menu-2]',
			],
			'socials'  => [
				'name'    => __( 'Socials', 'vite' ),
				'section' => 'vite[header-socials]',
			],
			'button-1' => [
				'name'    => __( 'Button 1', 'vite' ),
				'section' => 'vite[header-button-1]',
			],
			'button-2' => [
				'name'    => __( 'Button 2', 'vite' ),
				'section' => 'vite[header-button-2]',
			],
			'html-1'   => [
				'name'    => __( 'HTML 1', 'vite' ),
				'section' => 'vite[header-html-1]',
			],
			'html-2'   => [
				'name'    => __( 'HTML 2', 'vite' ),
				'section' => 'vite[header-html-2]',
			],
		],
		'mobile'  => [
			'logo'     => [
				'name'    => __( 'Logo', 'vite' ),
				'section' => 'vite[header-logo]',
			],
			'menu-3'   => [
				'name'    => __( 'Mobile Menu', 'vite' ),
				'section' => 'vite[header-menu-3]',
			],
			'trigger'  => [
				'name'    => __( 'Trigger', 'vite' ),
				'section' => 'vite[header-trigger]',
			],
			'search'   => [
				'name'    => __( 'Search', 'vite' ),
				'section' => 'vite[header-search]',
			],
			'socials'  => [
				'name'    => __( 'Socials', 'vite' ),
				'section' => 'vite[header-socials]',
			],
			'button-1' => [
				'name'    => __( 'Button 1', 'vite' ),
				'section' => 'vite[header-button-1]',
			],
			'button-2' => [
				'name'    => __( 'Button 2', 'vite' ),
				'section' => 'vite[header-button-2]',
			],
			'html-1'   => [
				'name'    => __( 'HTML 1', 'vite' ),
				'section' => 'vite[header-html-1]',
			],
			'html-2'   => [
				'name'    => __( 'HTML 2', 'vite' ),
				'section' => 'vite[header-html-2]',
			],
		],
	]
);

$row_options = array_reduce(
	[ 'top', 'main', 'bottom' ],
	function( $acc, $curr ) use ( $core ) {
		$acc[ "vite[header-$curr-row-layout]" ] = [
			'section'     => "vite[header-$curr-row]",
			'type'        => 'vite-buttonset',
			'title'       => __( 'Layout', 'vite' ),
			'default'     => $core->get_mod_defaults()['header-top-row-layout'],
			'choices'     => [
				'contained'  => __( 'Contained', 'vite' ),
				'full-width' => __( 'Full width', 'vite' ),
			],
			'partial'     => [
				'selector'            => '.vite-header',
				'container_inclusive' => true,
				'render_callback'     => function() {
					get_template_part( 'template-parts/header/header', '' );
				},
			],
			'input_attrs' => [
				'cols' => 2,
			],
		];
		$acc[ "vite[header-$curr-row-height]" ] = [
			'section'     => "vite[header-$curr-row]",
			'type'        => 'vite-slider',
			'title'       => __( 'Height', 'vite' ),
			'default'     => $core->get_mod_defaults()[ "header-$curr-row-height" ],
			'input_attrs' => [
				'min'        => 0,
				'max'        => 400,
				'step'       => 1,
				'units'      => [ 'px' ],
				'responsive' => true,
			],
			'css'         => [
				'selector' => ".vite-header__row-$curr",
				'property' => '--min--height',
			],
		];
		return $acc;
	},
	[]
);

$options = [
	'vite[header]'                    => [
		'section'     => 'vite[header-builder]',
		'type'        => 'vite-header-builder',
		'title'       => __( 'Header', 'vite' ),
		'default'     => $core->get_mod_defaults()['header'],
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
			'selector'            => '.vite-header',
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
	'vite[header-sticky]'             => [
		'section'     => 'vite[header-builder-settings]',
		'type'        => 'vite-toggle',
		'title'       => __( 'Sticky', 'vite' ),
		'default'     => $core->get_mod_defaults()['[header-sticky'] ?? false,
		'input_attrs' => [
			'separator' => true,
		],
	],
	'vite[header-sticky-row]'         => [
		'section'   => 'vite[header-builder-settings]',
		'type'      => 'vite-select',
		'title'     => __( 'Sticky row', 'vite' ),
		'default'   => $core->get_mod_defaults()['header-sticky-row'],
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
		'default'     => $core->get_mod_defaults()['header-sticky-enable'],
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
		'default'     => $core->get_mod_defaults()['[header-transparent'] ?? false,
		'input_attrs' => [
			'separator' => true,
		],
	],
	'vite[header-transparent-enable]' => [
		'section'     => 'vite[header-builder-settings]',
		'type'        => 'vite-buttonset',
		'title'       => __( 'Enable on', 'vite' ),
		'default'     => $core->get_mod_defaults()['header-transparent-enable'],
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
		'default'     => $core->get_mod_defaults()['header-background'],
		'selectors'   => [ '.vite-header' ],
		'input_attrs' => [
			'separator' => true,
		],
		'css' => [
			'selector' => '.vite-header',
		],
	],
];

vite( 'customizer' )->add( 'settings', array_merge( $options, $row_options ) );
