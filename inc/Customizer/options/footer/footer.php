<?php

$core = vite( 'core' );

$col_layouts = [
	[
		'desktop' => [
			'100' => '100',
		],
		'mobile'  => [
			'stacked' => __( 'Stacked', 'vite' ),
			'50-50'   => '50/50',
		],
		'tablet'  => [
			'stacked' => __( 'Stacked', 'vite' ),
			'50-50'   => '50/50',
		],
	],
	[
		'desktop' => [
			'50-50' => '50/50',
			'33-66' => '33/66',
			'66-33' => '66/33',
		],
		'mobile'  => [
			'stacked' => __( 'Stacked', 'vite' ),
			'50-50'   => '50/50',
		],
		'tablet'  => [
			'stacked' => __( 'Stacked', 'vite' ),
			'50-50'   => '50/50',
		],
	],
	[
		'desktop' => [
			'33-33-33' => '33/33/33',
			'25-50-25' => '25/50/25',
			'25-25-50' => '25/25/50',
			'50-25-25' => '50/25/25',
		],
		'mobile'  => [
			'stacked' => __( 'Stacked', 'vite' ),
			'50-50'   => '50/50',
		],
		'tablet'  => [
			'stacked' => __( 'Stacked', 'vite' ),
			'50-50'   => '50/50',
		],
	],
	[
		'desktop' => [
			'25-25-25-25' => '25/25/25/25',
			'50-25-25-25' => '50/25/25/25',
			'25-25-25-50' => '25/50/25/25',
		],
		'mobile'  => [
			'stacked' => __( 'Stacked', 'vite' ),
			'50-50'   => '50/50',
		],
		'tablet'  => [
			'stacked' => __( 'Stacked', 'vite' ),
			'50-50'   => '50/50',
		],
	],
	[
		'desktop' => [
			'20-20-20-20-20' => '20/20/20/20/20',
		],
		'mobile'  => [
			'stacked' => __( 'Stacked', 'vite' ),
			'50-50'   => '50/50',
		],
		'tablet'  => [
			'stacked' => __( 'Stacked', 'vite' ),
			'50-50'   => '50/50',
		],
	],
	[
		'desktop' => [
			'16-16-16-16-16-16' => '16/16/16/16/16/16',
		],
		'mobile'  => [
			'stacked' => __( 'Stacked', 'vite' ),
			'50-50'   => '50/50',
		],
		'tablet'  => [
			'stacked' => __( 'Stacked', 'vite' ),
			'50-50'   => '50/50',
		],
	],
];

$row_options = array_reduce(
	[ 'top', 'main', 'bottom' ],
	function( $acc, $curr ) use ( $core, $col_layouts ) {
		$acc[ "vite[footer-$curr-row-layout]" ] = [
			'section'     => "vite[footer-$curr-row]",
			'type'        => 'vite-buttonset',
			'title'       => __( 'Layout', 'vite' ),
			'default'     => $core->get_theme_mod_defaults()[ "footer-$curr-row-layout" ],
			'choices'     => [
				'contained'  => __( 'Contained', 'vite' ),
				'full-width' => __( 'Full width', 'vite' ),
			],
			'partial'     => [
				'selector'            => '.vite-footer',
				'container_inclusive' => true,
				'render_callback'     => function() {
					get_template_part( 'template-parts/footer/footer', '' );
				},
			],
			'input_attrs' => [
				'cols' => 2,
			],
		];

		$acc[ "vite[footer-$curr-row-cols]" ] = [
			'section'     => "vite[footer-$curr-row]",
			'type'        => 'vite-slider',
			'title'       => __( 'Column', 'vite' ),
			'default'     => $core->get_theme_mod_defaults()[ "footer-$curr-row-cols" ],
			'partial'     => [
				'selector'            => '.vite-footer',
				'container_inclusive' => true,
				'render_callback'     => function() {
					get_template_part( 'template-parts/footer/footer', '' );
				},
			],
			'input_attrs' => [
				'min'       => 1,
				'max'       => 6,
				'step'      => 1,
				'marks'     => true,
				'input'     => false,
				'separator' => true,
			],
		];

		$acc[ "vite[footer-$curr-row-col-layout]" ] = [
			'section'     => "vite[footer-$curr-row]",
			'type'        => 'vite-adv-select',
			'title'       => __( 'Layout', 'vite' ),
			'default'     => $core->get_theme_mod_defaults()[ "footer-$curr-row-col-layout" ],
			'choices'     => array_map(
				function( $col ) {
					return array_merge(
						$col,
						[
							'mobile' => [
								'stacked' => __( 'Stacked', 'vite' ),
								'50-50'   => '50/50',
							],
							'tablet' => [
								'stacked' => __( 'Stacked', 'vite' ),
								'50-50'   => '50/50',
							],
						]
					);
				},
				$col_layouts
			),
			'partial'     => [
				'selector'            => '.vite-footer',
				'container_inclusive' => true,
				'render_callback'     => function() {
					get_template_part( 'template-parts/footer/footer', '' );
				},
			],
			'input_attrs' => [
				'choice_dep' => "vite[footer-$curr-row-cols]",
				'responsive' => true,
			],
			'condition'   => [
				"vite[footer-$curr-row-cols]!" => 1,
			],
		];

		$acc[ "vite[footer-$curr-row-col-alignment]" ] = [
			'section'     => "vite[footer-$curr-row]",
			'type'        => 'vite-buttonset',
			'title'       => __( 'Vertical Alignment', 'vite' ),
			'choices'     => [
				'flex-start' => __( 'Top', 'vite' ),
				'center'     => __( 'Middle', 'vite' ),
				'flex-end'   => __( 'Bottom', 'vite' ),
			],
			'partial'     => [
				'selector'            => '.vite-footer',
				'container_inclusive' => true,
				'render_callback'     => function() {
					get_template_part( 'template-parts/footer/footer', '' );
				},
			],
			'input_attrs' => [
				'separator' => true,
			],
		];

		$acc[ "vite[footer-$curr-row-height]" ] = [
			'section'     => "vite[footer-$curr-row]",
			'type'        => 'vite-slider',
			'title'       => __( 'Height', 'vite' ),
			'default'     => $core->get_theme_mod_defaults()[ "footer-$curr-row-height" ],
			'input_attrs' => [
				'min'        => 0,
				'max'        => 400,
				'step'       => 1,
				'units'      => [ 'px' ],
				'responsive' => true,
				'separator'  => true,
			],
			'css'         => [
				'selector' => ".vite-footer__row--$curr",
				'property' => '--container--min--height',
				'context'  => 'header',
			],
		];

		$acc[ "vite[footer-$curr-row-border]" ] = [
			'section'     => "vite[footer-$curr-row]",
			'type'        => 'vite-border',
			'title'       => __( 'Border', 'vite' ),
			'input_attrs' => [
				'sides'       => [ 'top', 'bottom' ],
				'allow_hover' => false,
				'separator'   => true,
			],
			'css'         => [
				'selector' => ".vite-footer__row--$curr",
				'context'  => 'header',
			],
		];

		return $acc;
	},
	[]
);

$footer_elements = $core->filter(
	'builder/footer/elements',
	[
		'desktop' => [
			'menu-4'   => [
				'name'    => __( 'Footer Menu', 'vite' ),
				'section' => 'vite[footer-menu-4]',
			],
			'socials'  => [
				'name'    => __( 'Socials', 'vite' ),
				'section' => 'vite[footer-socials]',
			],
			'html-1'   => [
				'name'    => __( 'HTML 1', 'vite' ),
				'section' => 'vite[footer-html-1]',
			],
			'html-2'   => [
				'name'    => __( 'HTML 2', 'vite' ),
				'section' => 'vite[footer-html-2]',
			],
			'widget-1' => [
				'name'    => __( 'Widget 1', 'vite' ),
				'section' => 'sidebar-widgets-footer-widget-1',
			],
			'widget-2' => [
				'name'    => __( 'Widget 2', 'vite' ),
				'section' => 'sidebar-widgets-footer-widget-2',
			],
			'widget-3' => [
				'name'    => __( 'Widget 3', 'vite' ),
				'section' => 'sidebar-widgets-footer-widget-3',
			],
			'widget-4' => [
				'name'    => __( 'Widget 4', 'vite' ),
				'section' => 'sidebar-widgets-footer-widget-4',
			],
			'widget-5' => [
				'name'    => __( 'Widget 5', 'vite' ),
				'section' => 'sidebar-widgets-footer-widget-5',
			],
			'widget-6' => [
				'name'    => __( 'Widget 6', 'vite' ),
				'section' => 'sidebar-widgets-footer-widget-6',
			],
		],
	]
);

$options = [
	'vite[footer]'                    => [
		'section'     => 'vite[footer-builder]',
		'type'        => 'vite-footer-builder',
		'label'       => __( 'Footer', 'vite' ),
		'choices'     => $footer_elements,
		'default'     => $core->get_theme_mod_default( 'footer' ),
		'priority'    => 10,
		'input_attrs' => [
			'areas'    => [
				'top'    => [
					'1' => 'Top 1',
					'2' => 'Top 2',
					'3' => 'Top 3',
					'4' => 'Top 4',
					'5' => 'Top 5',
					'6' => 'Top 6',
				],
				'main'   => [
					'1' => 'Main 1',
					'2' => 'Main 2',
					'3' => 'Main 3',
					'4' => 'Main 4',
					'5' => 'Main 5',
					'6' => 'Main 6',
				],
				'bottom' => [
					'1' => 'Bottom 1',
					'2' => 'Bottom 2',
					'3' => 'Bottom 3',
					'4' => 'Bottom 4',
					'5' => 'Bottom 5',
					'6' => 'Bottom 6',
				],
			],
			'sections' => [
				'vite[footer-builder-settings]',
			],
		],
		'partial'     => [
			'selector'            => '.vite-footer',
			'container_inclusive' => true,
			'render_callback'     => function() {
				get_template_part( 'template-parts/footer/footer', '' );
			},
		],
	],
	'vite[footer-builder-components]' => [
		'section'     => 'vite[footer-builder-settings]',
		'type'        => 'vite-builder-components',
		'title'       => __( 'Available Components', 'vite' ),
		'choices'     => $footer_elements,
		'input_attrs' => [
			'group'   => 'vite[footer]',
			'context' => 'footer',
		],
	],
	'vite[footer-background]'         => [
		'section'     => 'vite[footer-builder-settings]',
		'type'        => 'vite-background',
		'title'       => __( 'Background', 'vite' ),
		'css'         => [
			'selector' => '.vite-footer',
			'context'  => 'footer',
		],
		'input_attrs' => [
			'separator' => true,
		],
	],
];

vite( 'customizer' )->add( 'settings', array_merge( $options, $row_options ) );
