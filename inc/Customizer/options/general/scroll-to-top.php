<?php
/**
 * Options for scroll to top.
 *
 * @package Vite
 */

$core = vite( 'core' );
$icon = vite( 'icon' );

$options = [
	'vite[scroll-to-top]'               => [
		'section' => 'vite[scroll-to-top]',
		'type'    => 'vite-toggle',
		'title'   => __( 'Enable', 'vite' ),
		'default' => $core->get_theme_mod_defaults()['scroll-to-top'],
		'partial' => [
			'selector'            => '.vite-modal--stt',
			'container_inclusive' => true,
			'render_callback'     => function() {
				get_template_part( 'template-parts/scroll-to-top/scroll-to-top' );
			},
		],
	],
	'vite[scroll-to-top-icon]'          => [
		'section'     => 'vite[scroll-to-top]',
		'type'        => 'vite-buttonset',
		'title'       => __( 'Icon', 'vite' ),
		'default'     => 'arrow-up',
		'choices'     => [
			'arrow-up'          => $icon->get_icon(
				'arrow-up',
				[
					'echo' => false,
					'size' => 15,
				]
			),
			'angle-up'          => $icon->get_icon(
				'angle-up',
				[
					'echo' => false,
					'size' => 15,
				]
			),
			'arrow-up-long'     => $icon->get_icon(
				'arrow-up-long',
				[
					'echo' => false,
					'size' => 15,
				]
			),
			'caret-up'          => $icon->get_icon(
				'caret-up',
				[
					'echo' => false,
					'size' => 15,
				]
			),
			'chevron-up'        => $icon->get_icon(
				'chevron-up',
				[
					'echo' => false,
					'size' => 15,
				]
			),
			'circle-arrow-up'   => $icon->get_icon(
				'circle-arrow-up',
				[
					'echo' => false,
					'size' => 15,
				]
			),
			'circle-chevron-up' => $icon->get_icon(
				'circle-chevron-up',
				[
					'echo' => false,
					'size' => 15,
				]
			),
			'circle-up'         => $icon->get_icon(
				'circle-up',
				[
					'echo' => false,
					'size' => 15,
				]
			),
			'square-caret-up'   => $icon->get_icon(
				'square-caret-up',
				[
					'echo' => false,
					'size' => 15,
				]
			),
			'turn-up'           => $icon->get_icon(
				'turn-up',
				[
					'echo' => false,
					'size' => 15,
				]
			),
		],
		'input_attrs' => [
			'icon'      => true,
			'cols'      => 5,
			'separator' => true,
		],
		'partial'     => [
			'selector'            => '.vite-modal--stt',
			'container_inclusive' => true,
			'render_callback'     => function() {
				get_template_part( 'template-parts/scroll-to-top/scroll-to-top' );
			},
		],
		'condition'   => [
			'vite[scroll-to-top]' => true,
		],
	],
	'vite[scroll-top-icon-color]'       => [
		'section'     => 'vite[scroll-to-top]',
		'type'        => 'vite-color',
		'title'       => __( 'Icon Colors', 'vite' ),
		'input_attrs' => [
			'colors'    => [
				[
					'id'    => '--button--color',
					'label' => __( 'Normal', 'vite' ),
				],
				[
					'id'    => '--button--hover--color',
					'label' => __( 'Hover', 'vite' ),
				],
			],
			'separator' => true,
		],
		'condition'   => [
			'vite[scroll-to-top]' => true,
		],
		'css'         => [
			'selector' => '.vite-modal--stt',
			'context'  => 'global',
		],
	],
	'vite[scroll-top-bg-color]'         => [
		'section'     => 'vite[scroll-to-top]',
		'type'        => 'vite-color',
		'title'       => __( 'Background Colors', 'vite' ),
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
		'condition'   => [
			'vite[scroll-to-top]' => true,
		],
		'css'         => [
			'selector' => '.vite-modal--stt',
			'context'  => 'global',
		],
	],
	'vite[scroll-to-top-position]'      => [
		'section'     => 'vite[scroll-to-top]',
		'type'        => 'vite-buttonset',
		'title'       => __( 'Position', 'vite' ),
		'default'     => $core->get_theme_mod_defaults()['scroll-to-top-position'],
		'choices'     => [
			'left'  => __( 'Left', 'vite' ),
			'right' => __( 'Right', 'vite' ),
		],
		'condition'   => [
			'vite[scroll-to-top]' => true,
		],
		'input_attrs' => [
			'separator' => true,
			'cols'      => 2,
		],
		'partial'     => [
			'selector'            => '.vite-modal--stt',
			'container_inclusive' => true,
			'render_callback'     => function() {
				get_template_part( 'template-parts/scroll-to-top/scroll-to-top' );
			},
		],
	],
	'vite[scroll-to-top-edge-offset]'   => [
		'section'     => 'vite[scroll-to-top]',
		'type'        => 'vite-slider',
		'title'       => __( 'Edge offset', 'vite' ),
		'default'     => $core->get_theme_mod_defaults()['scroll-to-top-edge-offset'],
		'input_attrs' => [
			'units'      => [ 'px' ],
			'responsive' => true,
			'separator'  => true,
		],
		'condition'   => [
			'vite[scroll-to-top]' => true,
		],
		'css'         => [
			'selector' => '.vite-modal--stt',
			'property' => '--stt--edge--offset',
			'context'  => 'global',
		],
	],
	'vite[scroll-to-top-bottom-offset]' => [
		'section'     => 'vite[scroll-to-top]',
		'type'        => 'vite-slider',
		'title'       => __( 'Bottom offset', 'vite' ),
		'default'     => $core->get_theme_mod_defaults()['scroll-to-top-bottom-offset'],
		'input_attrs' => [
			'units'      => [ 'px' ],
			'responsive' => true,
			'separator'  => true,
		],
		'condition'   => [
			'vite[scroll-to-top]' => true,
		],
		'css'         => [
			'selector' => '.vite-modal--stt',
			'property' => '--stt--bottom--offset',
			'context'  => 'global',
		],
	],
	'vite[scroll-to-top-button-size]'   => [
		'section'     => 'vite[scroll-to-top]',
		'type'        => 'vite-slider',
		'title'       => __( 'Button Size', 'vite' ),
		'default'     => $core->get_theme_mod_defaults()['scroll-to-top-button-size'],
		'input_attrs' => [
			'units'      => [ 'px', 'rem', 'em' ],
			'responsive' => true,
			'separator'  => true,
		],
		'condition'   => [
			'vite[scroll-to-top]' => true,
		],
		'css'         => [
			'selector' => '.vite-modal--stt .vite-modal--stt__btn',
			'property' => [ 'height', 'width' ],
			'context'  => 'global',
		],
	],
	'vite[scroll-to-top-icon-size]'     => [
		'section'     => 'vite[scroll-to-top]',
		'type'        => 'vite-slider',
		'title'       => __( 'Icon Size', 'vite' ),
		'default'     => $core->get_theme_mod_defaults()['scroll-to-top-icon-size'],
		'input_attrs' => [
			'separator' => true,
		],
		'condition'   => [
			'vite[scroll-to-top]' => true,
		],
		'partial'     => [
			'selector'            => '.vite-modal--stt',
			'container_inclusive' => true,
			'render_callback'     => function() {
				get_template_part( 'template-parts/scroll-to-top/scroll-to-top' );
			},
		],
	],
	'vite[scroll-to-top-border]'        => [
		'section'     => 'vite[scroll-to-top]',
		'type'        => 'vite-border',
		'title'       => __( 'Border', 'vite' ),
		'default'     => $core->get_theme_mod_defaults()['scroll-to-top-border'],
		'input_attrs' => [
			'separator' => true,
		],
		'condition'   => [
			'vite[scroll-to-top]' => true,
		],
		'css'         => [
			'selector' => '.vite-modal--stt .vite-modal--stt__btn',
			'property' => 'border',
			'context'  => 'global',
		],
	],
	'vite[scroll-to-top-radius]'        => [
		'section'     => 'vite[scroll-to-top]',
		'type'        => 'vite-dimensions',
		'title'       => __( 'Border Radius', 'vite' ),
		'default'     => $core->get_theme_mod_defaults()['scroll-to-top-radius'],
		'input_attrs' => [
			'units'     => [ 'px', 'rem', 'em', '%' ],
			'separator' => true,
			'sides'     => [ 'top-left', 'top-right', 'bottom-right', 'bottom-left' ],
		],
		'condition'   => [
			'vite[scroll-to-top]' => true,
		],
		'css'         => [
			'selector' => '.vite-modal--stt .vite-modal--stt__btn',
			'property' => 'border',
			'pattern'  => '%property-%side-radius',
			'context'  => 'global',
		],
	],
];

vite( 'customizer' )->add( 'settings', $options );
