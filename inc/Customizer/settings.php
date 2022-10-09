<?php
/**
 * Settings.
 *
 * @package Vite
 */

$panel_settings = [
	[
		'name'     => 'vite[panel]',
		'type'     => 'panel',
		'title'    => __( 'Vite Panel', 'vite' ),
		'priority' => 10,
	],
];

$section_settings = [
	[
		'name'     => 'vite[section]',
		'type'     => 'section',
		'title'    => __( 'Vite Section', 'vite' ),
		'panel'    => 'vite[panel]',
		'priority' => 10,
	],
];

$control_settings = [
	[
		'name'        => 'vite[color]',
		'type'        => 'control',
		'control'     => 'vite-color',
		'section'     => 'vite[section]',
		'label'       => __( 'Color', 'vite' ),
		'description' => __( 'An example of color control.', 'vite' ),
	],
	[
		'name'        => 'vite[colors]',
		'type'        => 'control',
		'control'     => 'vite-color',
		'section'     => 'vite[section]',
		'label'       => __( 'Colors', 'vite' ),
		'description' => __( 'An example of color control with multiple buttons.', 'vite' ),
		'input_attrs' => [
			'colors' => [
				[
					'id'    => 'normal',
					'label' => __( 'Normal', 'vite' ),
				],
				[
					'id'    => 'hover',
					'label' => __( 'Hover', 'vite' ),
				],
			],
		],
	],
	[
		'name'        => 'vite[buttonset]',
		'type'        => 'control',
		'control'     => 'vite-buttonset',
		'section'     => 'vite[section]',
		'label'       => __( 'Button set', 'vite' ),
		'description' => __( 'An example of button set control.', 'vite' ),
		'choices'     => [
			'left'  => __( 'Left', 'vite' ),
			'right' => __( 'Right', 'vite' ),
		],
	],
	[
		'name'        => 'vite[buttonset-multiple]',
		'type'        => 'control',
		'control'     => 'vite-buttonset',
		'section'     => 'vite[section]',
		'label'       => __( 'Button set multiple', 'vite' ),
		'description' => __( 'An example of button set control with multiple select.', 'vite' ),
		'choices'     => [
			'left'  => __( 'Left', 'vite' ),
			'right' => __( 'Right', 'vite' ),
			'top'   => __( 'Top', 'vite' ),
		],
		'input_attrs' => [
			'multiple' => true,
		],
	],
	[
		'name'        => 'vite[editor]',
		'type'        => 'control',
		'control'     => 'vite-editor',
		'section'     => 'vite[section]',
		'label'       => __( 'Editor', 'vite' ),
		'description' => __( 'An example of editor control.', 'vite' ),
	],
	[
		'name'        => 'vite[toggle]',
		'type'        => 'control',
		'control'     => 'vite-toggle',
		'section'     => 'vite[section]',
		'label'       => __( 'Toggle/Switch', 'vite' ),
		'description' => __( 'An example of toggle/switch control.', 'vite' ),
	],
	[
		'name'        => 'vite[checkbox]',
		'type'        => 'control',
		'control'     => 'vite-checkbox',
		'section'     => 'vite[section]',
		'label'       => __( 'Checkbox', 'vite' ),
		'description' => __( 'An example of checkbox control.', 'vite' ),
	],
	[
		'name'        => 'vite[select]',
		'type'        => 'control',
		'control'     => 'vite-select',
		'section'     => 'vite[section]',
		'label'       => __( 'Select', 'vite' ),
		'description' => __( 'An example of select control.', 'vite' ),
		'choices'     => [
			'left'  => __( 'Left', 'vite' ),
			'right' => __( 'Right', 'vite' ),
		],
	],
	[
		'name'        => 'vite[text]',
		'type'        => 'control',
		'control'     => 'vite-input',
		'section'     => 'vite[section]',
		'label'       => __( 'Input text', 'vite' ),
		'description' => __( 'An example of input text control.', 'vite' ),
	],
	[
		'name'        => 'vite[number]',
		'type'        => 'control',
		'control'     => 'vite-input',
		'section'     => 'vite[section]',
		'label'       => __( 'Input number', 'vite' ),
		'description' => __( 'An example of number text control.', 'vite' ),
		'input_attrs' => [
			'type' => 'number',
		],
	],
	[
		'name'        => 'vite[url]',
		'type'        => 'control',
		'control'     => 'vite-input',
		'section'     => 'vite[section]',
		'label'       => __( 'Input url', 'vite' ),
		'description' => __( 'An example of url text control.', 'vite' ),
		'input_attrs' => [
			'type' => 'url',
		],
	],
	[
		'name'        => 'vite[email]',
		'type'        => 'control',
		'control'     => 'vite-input',
		'section'     => 'vite[section]',
		'label'       => __( 'Input email', 'vite' ),
		'description' => __( 'An example of email text control.', 'vite' ),
		'input_attrs' => [
			'type' => 'email',
		],
	],
	[
		'name'        => 'vite[radio]',
		'type'        => 'control',
		'control'     => 'vite-radio',
		'section'     => 'vite[section]',
		'label'       => __( 'Radio', 'vite' ),
		'description' => __( 'An example of radio control.', 'vite' ),
		'choices'     => [
			'left'  => __( 'Left', 'vite' ),
			'right' => __( 'Right', 'vite' ),
		],
	],
	[
		'name'        => 'vite[radio-image]',
		'type'        => 'control',
		'control'     => 'vite-radio-image',
		'section'     => 'vite[section]',
		'label'       => __( 'Radio Image', 'vite' ),
		'description' => __( 'An example of radio image control.', 'vite' ),
		'choices'     => [
			'left'  => [
				'label' => __( 'Left', 'vite' ),
				'image' => 'https://via.placeholder.com/150',
			],
			'right' => [
				'label' => __( 'Right', 'vite' ),
				'image' => 'https://via.placeholder.com/150',
			],
		],
	],
	[
		'name'        => 'vite[gradient]',
		'type'        => 'control',
		'control'     => 'vite-gradient',
		'section'     => 'vite[section]',
		'label'       => __( 'Gradient', 'vite' ),
		'description' => __( 'An example of gradient control.', 'vite' ),
	],
	[
		'name'        => 'vite[textarea]',
		'type'        => 'control',
		'control'     => 'vite-textarea',
		'section'     => 'vite[section]',
		'label'       => __( 'Textarea', 'vite' ),
		'description' => __( 'An example of textarea control.', 'vite' ),
	],
	[
		'name'        => 'vite[border]',
		'default'     => [
			'style' => 'none',
		],
		'type'        => 'control',
		'control'     => 'vite-border',
		'section'     => 'vite[section]',
		'label'       => __( 'Border', 'vite' ),
		'description' => __( 'An example of border control.', 'vite' ),
	],
	[
		'name'        => 'vite[slider]',
		'type'        => 'control',
		'control'     => 'vite-slider',
		'section'     => 'vite[section]',
		'label'       => __( 'Slider', 'vite' ),
		'description' => __( 'An example of slider control without unit.', 'vite' ),
		'input_attrs' => [
			'min'  => 0,
			'max'  => 100,
			'step' => 1,
		],
	],
	[
		'name'        => 'vite[slider-responsive]',
		'type'        => 'control',
		'control'     => 'vite-slider',
		'section'     => 'vite[section]',
		'label'       => __( 'Slider Responsive', 'vite' ),
		'description' => __( 'An example of slider control without unit and with responsive.', 'vite' ),
		'input_attrs' => [
			'min'        => 0,
			'max'        => 100,
			'step'       => 1,
			'responsive' => true,
		],
	],
	[
		'name'        => 'vite[slider-units]',
		'type'        => 'control',
		'control'     => 'vite-slider',
		'section'     => 'vite[section]',
		'label'       => __( 'Slider with units', 'vite' ),
		'description' => __( 'An example of slider control with units.', 'vite' ),
		'input_attrs' => [
			'noUnits' => false,
			'units'   => [
				'px',
				'em',
				'rem',
			],
		],
	],
	[
		'name'        => 'vite[slider-units-responsive]',
		'type'        => 'control',
		'control'     => 'vite-slider',
		'section'     => 'vite[section]',
		'label'       => __( 'Slider with units and responsive', 'vite' ),
		'description' => __( 'An example of slider control with units and responsive.', 'vite' ),
		'input_attrs' => [
			'noUnits'    => false,
			'units'      => [
				'px',
				'em',
				'rem',
			],
			'responsive' => true,
		],
	],
	[
		'name'        => 'vite[slider-dimensions]',
		'type'        => 'control',
		'control'     => 'vite-dimensions',
		'section'     => 'vite[section]',
		'label'       => __( 'Dimensions', 'vite' ),
		'description' => __( 'An example of dimensions control.', 'vite' ),
	],
	[
		'name'        => 'vite[slider-dimensions-responsive]',
		'type'        => 'control',
		'control'     => 'vite-dimensions',
		'section'     => 'vite[section]',
		'label'       => __( 'Dimensions', 'vite' ),
		'description' => __( 'An example of dimensions responsive control.', 'vite' ),
		'input_attrs' => [
			'responsive' => true,
		],
	],
	[
		'name'        => 'vite[slider-background]',
		'type'        => 'control',
		'control'     => 'vite-background',
		'section'     => 'vite[section]',
		'label'       => __( 'Background', 'vite' ),
		'description' => __( 'An example of background responsive control.', 'vite' ),
		'input_attrs' => [
			'responsive' => true,
		],
	],
	[
		'name'        => 'vite[slider-sortable]',
		'type'        => 'control',
		'control'     => 'vite-sortable',
		'section'     => 'vite[section]',
		'label'       => __( 'Background', 'vite' ),
		'description' => __( 'An example of sortable control.', 'vite' ),
		'choices'     => [
			'one'   => 'One',
			'two'   => 'Two',
			'three' => 'Three',
		],
	],
];

$settings = apply_filters( 'vite_customizer_control_settings', array_merge( $panel_settings, $section_settings, $control_settings ) );

vite( 'customizer' )->add_settings( $settings );


